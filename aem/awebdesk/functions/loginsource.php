<?php

require_once awebdesk_classes("select.php");
require_once awebdesk_functions("auth.php");

function adesk_loginsource_select_query(&$so) {
	return $so->query("
		SELECT
			*
		FROM
			#loginsource
		WHERE
			[...]
	");
}

function adesk_loginsource_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND id = '$id'");

	return adesk_sql_select_row(adesk_loginsource_select_query($so));
}

function adesk_loginsource_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		$tmp = array_map("intval", explode(",", $ids));
		$ids = implode("','", $tmp);
		$so->push("AND id IN ('$ids')");
	}

	return adesk_sql_select_array(adesk_loginsource_select_query($so));
}

function adesk_loginsource_select_array_paginator($id, $sort, $offset, $limit, $filter) {
	adesk_loginsource_sync();

	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$so->count();
	$total = (int)adesk_sql_select_one(adesk_loginsource_select_query($so));

	switch ($sort) {
		default:
		case "01":
			$so->orderby("enabled DESC, ident"); break;
		case "01D":
			$so->orderby("enabled DESC, ident DESC"); break;
		case "02":
			$so->orderby("enabled DESC, `order`"); break;
		case "02D":
			$so->orderby("enabled DESC, `order` DESC"); break;
	}

	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = adesk_loginsource_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function adesk_loginsource_update_post() {
	$ary = array(
		"enabled"     => intval(isset($_POST["enabled"])),
		"host"        => $_POST["host"],
		"port"        => intval($_POST["port"]),
		"user"        => $_POST["user"],
		"pass"        => $_POST["pass"],
		"dbname"      => $_POST["dbname"],
		"tableprefix" => $_POST["tableprefix"],
		"amsproductid" => $_POST["amsproductid"],
		"basedn"      => $_POST["basedn"],
		"loginusesdn" => intval(isset($_POST["loginusesdn"])),
		"loginattr"   => $_POST["loginattr"],
		"binddn"      => $_POST["binddn"],
		"bindpw"      => $_POST["bindpw"],
		"userattr"    => $_POST["userattr"],
	);

	if ($_POST["ad_basedn"] != "") {
		$ary["basedn"]      = $_POST["ad_basedn"];
		$ary["binddn"]      = $_POST["ad_admin_dn"];
		$ary["bindpw"]      = $_POST["ad_admin_pw"];
		$ary["loginusesdn"] = 1;
		$ary["loginattr"]   = "cn";
		$ary["userattr"]    = "samAccountName";
	}

	if (isset($_POST["groupset"]) && is_array($_POST["groupset"]))
		$ary["groupset"] = implode(",", $_POST["groupset"]);

	$id = intval($_POST["id"]);
	$sql = adesk_sql_update("#loginsource", $ary, "id = '$id'");

	adesk_ihook("acg_loginsource_update", $id);

	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Login source could not be updated."));
	}

	return adesk_ajax_api_updated(_a("Login source"));
}

function adesk_loginsource_sync() {
	# Go through every file in /awebdesk/loginsource and, if it's a valid file, add it to
	# the database.  If there are database entries not present in the filesystem, we should
	# also remove them.

	$unfiles   = adesk_file_find(awebdesk("loginsource"), "");
	// patch to move the local to the front
	if ( in_array(awebdesk("loginsource/local.php"), $unfiles) ) {
		$unfiles   = array_unique(array_merge(array(awebdesk("loginsource/local.php")), $unfiles));
	}
	$unsources = adesk_loginsource_select_array();
	$files     = array();
	$vars      = array();

	foreach ($unfiles as $file) {
		$data = adesk_file_get($file);
		$mat  = array();
		if (preg_match('/\$loginident = "([^"]+)";/', $data, $mat)) {
			$ident = $mat[1];
			$files[$ident] = $file;
		}

		if (preg_match('/\$loginvars = "([^"]*)";/', $data, $mat)) {
			$vars[$ident] = $mat[1];
		}
	}

	# First, check to see what should be removed.

	$delist = array();
	foreach ($unsources as $source) {
		if (!isset($files[$source["ident"]])) {
			$delist[] = $source["id"];
		}

		$sources[$source["ident"]] = $source;
	}

	# Now, add any new files.

	foreach ($files as $ident => $file) {
		$max = adesk_sql_select_one("SELECT MAX(`order`) FROM #loginsource");

		if ($max === "")		# No rows in the table
			$max = 1;
		else
			$max = $max + 1;

		if (!isset($sources[$ident])) {
			$ary = array(
				"enabled"  => $ident == "Local" ? 1 : 0,
				"ident"    => $ident,
				"vars"     => $vars[$ident],
				"file"     => basename($file),
				"groupset" => "3",
				"order"    => $max,
			);

			# This is a temporary fix--we'll have a comprehensive solution for default
			# port values (or perhaps others) in the future.
			if ($ary["ident"] == "LDAP")
				$ary["port"] = 389;

			adesk_sql_insert("#loginsource", $ary);
		} else {
			$ary = array(
				"ident"    => $ident,
				"vars"     => $vars[$ident],
				"file"     => basename($file),
			);

			$id = $sources[$ident]["id"];
			adesk_sql_update("#loginsource", $ary, "id = '$id'");
		}
	}

	# And remove the bad sources.

	$delist_str = implode("','", $delist);
	adesk_sql_query("DELETE FROM #loginsource WHERE id IN ('$delist_str')");
}

function adesk_loginsource_select_local() {
	$so = new adesk_Select;
	$so->push("AND ident = 'Local'");

	$rows = adesk_loginsource_select_array($so);

	if (count($rows) > 0)
		return $rows[0];
	else
		return false;
}

function adesk_loginsource_determine($user, $pass, $adminaccess = 1) {
	# If the user already exists, we'll return the source record that we should
	# be using to connect with.  If not, we return null.

	$auth   = adesk_auth_record_username($user);
	$source = false;

	if ($auth["id"] == 1) {
		$source               = adesk_loginsource_select_local();
		$file                 = basename($source["file"]);
		$source["_classname"] = sprintf("%sLoginSource", $source["ident"]);

		if (file_exists(awebdesk("loginsource/$file"))) {
			require_once awebdesk("loginsource/$file");
		} else {
			$source = false;
		}
	}

	if ($auth !== false && $auth["sourceid"] > 0) {
		$source = adesk_loginsource_select_row($auth["sourceid"]);

		if ($source && $source["enabled"]) {
			$file                 = basename($source["file"]);
			$source["_classname"] = sprintf("%sLoginSource", $source["ident"]);

			if (file_exists(awebdesk("loginsource/$file"))) {
				require_once awebdesk("loginsource/$file");
			} else {
				$source = false;
			}
		} else {
			# We can't use this source--it's been disabled.  Treat this
			# as if the user had no sourceid at all.
			$source = false;
		}
	}

	if ($source === false) {
		# First, find the groups which we'll accept in our login sources, based on $adminaccess.
		# We always omit the Visitor group (id=1).
		$adminaccess = intval($adminaccess);

		if ($adminaccess == 1)
			$okgroups = adesk_sql_select_list("SELECT id FROM #group WHERE p_admin = '$adminaccess' AND id != 1");
		else
			$okgroups = adesk_sql_select_list("SELECT id FROM #group WHERE id != 1");

		$so = new adesk_Select;
		$so->push("AND enabled = 1");
		$so->orderby("`order` ASC");

		$list = adesk_loginsource_select_array($so);

		# Keep trying login sources till we find one that works.
		$i = 0;
		foreach ($list as $attempt) {
			$agroups               = explode(",", $attempt["groupset"]);

			# Skip this login source if the groups don't match our hoped-for access.
			if (count(array_intersect($okgroups, $agroups)) < 1)
				continue;

			$file                  = basename($attempt["file"]);
			$attempt["_classname"] = sprintf("%sLoginSource", $attempt["ident"]);

			if (file_exists(awebdesk("loginsource/$file"))) {
				require_once awebdesk("loginsource/$file");

				$s = new $attempt["_classname"]($attempt);

				$s->connect();
				if ($s->authok($user, $pass)) {
					$source = $attempt;
					break;
				}
			}
		}

		# If it's still false, then just use the local one.
		if ($source === false) {
			$source               = adesk_loginsource_select_local();
			$file                 = basename($source["file"]);
			$source["_classname"] = sprintf("%sLoginSource", $source["ident"]);

			if (file_exists(awebdesk("loginsource/$file"))) {
				require_once awebdesk("loginsource/$file");
			} else {
				$source = false;
			}
		}
	}

	if ($source === false) {
		# Still???
		die(_a("Your database does not have any login sources that we can use--not even the default local source."));
	}

	return $source;
}

function adesk_loginsource_recognize($sourceid, $order) {
	$ary = array(
		"sourceid" => $sourceid,
		"order"    => $order,
	);

	# First clear out the table, then add the new record.  This method is liable to
	# change in the future.
	#adesk_sql_query("TRUNCATE TABLE #loginsource_use");
	#adesk_sql_insert("#loginsource_use", $ary);
}

function adesk_loginsource_reorder($sourceid, $dir) {
	$sourceid  = intval($sourceid);
	$source    = adesk_loginsource_select_row($sourceid);
	$sourceord = intval($source["order"]);

	if ($dir == "u")
		$swapord = $sourceord - 1;
	elseif ($dir == "d")
		$swapord = $sourceord + 1;
	else
		return;

	if ($swapord < 1)
		return;

	adesk_sql_query("UPDATE #loginsource SET `order` = '$sourceord' WHERE `order` = '$swapord'");
	adesk_sql_query("UPDATE #loginsource SET `order` = '$swapord' WHERE id = '$sourceid'");
}

?>
