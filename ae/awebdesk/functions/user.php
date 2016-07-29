<?php

require_once awebdesk_classes("select.php");

function adesk_user_select_query(&$so, $global = false) {
	$list = adesk_sql_select_list("SELECT absid FROM #user");
	$list_str = implode("','", $list);

	if ($global)
		$clause = "NOT IN";
	else
		$clause = "IN";

	$so->push("AND global.id $clause ('$list_str')");

	# Need to avoid join conditions here; they could easily break the system for people with external globalauth tables.
	#if (adesk_ihook_exists("adesk_user_select_query_conditions"))
	#	$so->push(adesk_ihook("adesk_user_select_query_conditions", 1));

	if (adesk_ihook_exists("adesk_user_select_query_columns") && !$so->counting) {
		$so->slist = array_merge(array("*"), adesk_ihook("adesk_user_select_query_columns", array("paginator")));
	}

	return $so->query("
		SELECT
			*
		FROM
			aweb_globalauth global
		WHERE
			[...]
	");
}

function adesk_user_select_query_localcount(&$so) {
	if (adesk_ihook_exists("adesk_user_select_query_conditions"))
		$so->push(adesk_ihook("adesk_user_select_query_conditions", 1));

	if (adesk_ihook_exists("adesk_user_select_query_columns") && !$so->counting) {
		$so->slist = array_merge(array("*"), adesk_ihook("adesk_user_select_query_columns", array("paginator")));
	}

	return $so->query("
		SELECT
			*
		FROM
			#user global
		WHERE
			[...]
	");
}

function adesk_user_select_query_paginator(&$so) {
	if (adesk_ihook_exists("adesk_user_select_query_conditions"))
		$so->push(adesk_ihook("adesk_user_select_query_conditions"));

	if (adesk_ihook_exists("adesk_user_select_query_columns") && !$so->counting) {
		$so->slist = array_merge(array("*"), adesk_ihook("adesk_user_select_query_columns", array("paginator")));
	}

	return $so->query("
		SELECT
			id,
			absid,
			username,
			first_name,
			last_name,
			email
		FROM
			#user global
		WHERE
			[...]
	");
}

function adesk_user_select_row($id) {
	$id            = intval($id);
	$ary           = adesk_auth_record_id($id);
	return adesk_user_select_prepare($ary);
}

function adesk_user_select_row_email($email) {
	$ary           = adesk_auth_record_email($email);
	return adesk_user_select_prepare($ary);
}

function adesk_user_select_row_username($username) {
	$ary           = adesk_auth_record_username($username);
	return adesk_user_select_prepare($ary);
}

function adesk_user_select_prepare($ary) {
	$userid        = adesk_sql_select_one("SELECT id FROM #user WHERE absid = '$ary[id]'");
	$ary["groups"] = implode(",", adesk_sql_select_list("SELECT groupid FROM #user_group WHERE userid = '$userid'"));

	if (adesk_ihook_exists("acg_user_select_row"))
		$ary = adesk_ihook("acg_user_select_row", $ary);

	return $ary;
}

function adesk_user_select_array($so = null, $global = false, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND id IN ('$ids')");
	}

	$q = adesk_user_select_query($so, $global);
	$rs = mysql_query($q, $GLOBALS["auth_db_link"]);
	$rval = array();

	while ($row = mysql_fetch_assoc($rs))
		$rval[] = $row;

	return $rval;
}

function adesk_user_select_array_paginator($so = null, $global = false, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND id IN ('$ids')");
	}

	$rs = adesk_sql_query($q = adesk_user_select_query_paginator($so));
	$rval = array();

	while ($row = adesk_sql_fetch_assoc($rs))
		$rval[] = $row;

	return $rval;
}

function adesk_user_select_paginator($id, $sort, $offset, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'user'");
		$so->push($conds);
	}

	$so->count();
	$total = adesk_sql_select_one(adesk_user_select_query_paginator($so));

	switch ($sort) {
		default:
		case "01":
			$so->orderby("username"); break;
		case "01D":
			$so->orderby("username DESC"); break;
		case "02":
			$so->orderby("first_name, last_name"); break;
		case "02D":
			$so->orderby("first_name DESC, last_name DESC");  break;
		case "03":
			$so->orderby("email"); break;
		case "03D":
			$so->orderby("email DESC"); break;
	}

	$offset = (int)$offset;
	$so->limit("$offset, 20");
	$so->slist = array(
		"*",
	);
	$rows = adesk_user_select_array_paginator($so);

	$remlist = array();
	foreach ($rows as $k => $row) {
		$groupnames = adesk_sql_select_list("
			SELECT
				title
			FROM
				#group
			WHERE
				id IN (
					SELECT
						groupid
					FROM
						#user_group
					WHERE
						userid = '$row[id]'
				)
		");

		$rows[$k]["groups"] = implode(", ", $groupnames);

		if (adesk_ihook_exists("acg_user_select_array_paginator"))
			$rows[$k] = adesk_ihook("acg_user_select_array_paginator", $rows[$k]);
	}

	foreach ($remlist as $k)
		unset($rows[$k]);

	return array(
		"paginator" => $id,
		"offset"    => $offset,
		"total"     => $total,
		"cnt"		=> count($rows),
		"rows"		=> $rows,
		"adminsleft" => isset($GLOBALS["site"]["adminsLeft"]) ? $GLOBALS["site"]["adminsLeft"] : 99999999,
	);
}

function adesk_user_select_paginator_global($id, $offset) {
	$rows = adesk_user_select_array(null, true);

	return array(
		"paginator" => $id,
		"offset"    => $offset,
		"total"     => count($rows),
		"cnt"		=> count($rows),
		"rows"		=> $rows,
	);
}

function adesk_user_select_list($ids) {
	$ids = array_diff(array_map('intval', explode(',', $ids)), array(0));
	if ( !$ids ) return $ids;
	$r = array();
	foreach ( $ids as $id ) {
		if ( $v = adesk_user_select_row($id) ) $r[] = $v;
	}
	return $r;
}

function adesk_user_select_group($ids) {
	$ids = array_diff(array_map('intval', explode(',', $ids)), array(0));
	if ( !$ids ) return array();
	$groups = implode("', '", $ids);
	$users = adesk_sql_select_list("SELECT u.absid FROM #user u, #user_group g WHERE g.groupid IN ('$groups') AND g.userid = u.id GROUP BY u.id");
	if ( !$users ) return array();
	return array('rows' => adesk_user_select_list(implode(',', $users)));
}

# --
# The rest

function adesk_user_select_one($id) {
	$ary = adesk_auth_record_id($id);
	$tmp = adesk_sql_select_row("SELECT id, approved FROM #user WHERE absid = '$ary[id]'");
	$ary = array_merge($ary, $tmp);
	$ary["absid"] = $id;
	$ary["groups"] = implode(",", adesk_sql_select_list("SELECT groupid FROM #user_group WHERE userid = '$tmp[id]'"));
	return $ary;
}

function adesk_user_filter_post() {
	$whitelist = array("username", "first_name", "last_name", "email");

	$conds = array();
	$ary   = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "user",
		"conds" => "",
		"=tstamp" => "NOW()",
	);

	if (isset($_POST["qsearch"]) && !isset($_POST["content"])) {
		$_POST["content"] = $_POST["qsearch"];
	}

	if (isset($_POST["content"]) and $_POST['content'] != '') {
		$content = adesk_sql_escape($_POST["content"], true);

		if (!isset($_POST["section"]) || !is_array($_POST["section"]))
			$_POST["section"] = $whitelist;

		foreach ($_POST["section"] as $sect) {
			if (!in_array($sect, $whitelist))
				continue;

			if ($content != "")
				$conds[] = "$sect LIKE '%$content%' ";
		}
	}

	if (isset($_POST["search_group"])) {
		$groupid = intval($_POST["search_group"]);
		if ($groupid > 0)
			$conds[] = "(SELECT COUNT(*) FROM #user sub_u, #user_group sub_ug WHERE sub_ug.groupid = '$groupid' AND sub_u.id = sub_ug.userid AND sub_u.absid = global.absid) > 0";
		elseif ($groupid < 0) {
			$cond = adesk_ihook("acg_user_filter_post_group", $groupid);
			if ($cond != "")
				$conds[] = $cond;
		}
	}

	if (adesk_ihook_exists("acg_user_filter_post")) {
		$rval = adesk_ihook("acg_user_filter_post");
		if ($rval != "")
			$conds[] = $rval;
	}

	if (count($conds) < 1)
		return array("filterid" => 0);

	$conds = implode(" OR ", $conds);
	$ary["conds"] = "AND ($conds)";
	$conds_esc = adesk_sql_escape($ary['conds']);

	$filterid = adesk_sql_select_one("
		SELECT
			id
		FROM
			#section_filter
		WHERE
			userid = '$ary[userid]'
		AND
			sectionid = 'user'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

function adesk_user_can_modify($userid) {
	$admin = adesk_admin_get();

	// Never let any admin user modify admin -- unless it's admin itself
	if ($userid == 1 && $admin["id"] != 1)
		return false;

	// People in the main Admin user group can modify anyone else.
	if (in_array(3, $admin["groups"]))
		return true;

	// At this point, you now need to match groups with the user you are modifying...
	$userid = (int)$userid;
	$groupid = (int)adesk_sql_select_one("SELECT groupid FROM #user_group WHERE userid = '$userid'");

	if (!in_array($groupid, $admin["groups"]))
		return false;

	return true;
}
function adesk_user_permission($key) {
	$rval = adesk_ihook("adesk_user_permission", $key);

	if ($rval === $key)
		return false;
	else
		return $rval;
}

function adesk_user_update_post() {
	if (!adesk_user_permission("edit"))
		return adesk_ajax_api_nopermission(_a("edit users"));

	require_once awebdesk_functions("group.php");
	if (!adesk_array_has($_POST, "id", "username", "password", "password_r", "email", "first_name", "last_name"))
		return array("succeeded" => 0, "message" => _a("Internal error: POST does not contain all necessary form variables"));

	$id = intval($_POST["id"]);
	if (!adesk_user_can_modify($userid))
		return adesk_ajax_api_nopermission(_a("edit users"));
	$userid = adesk_sql_select_one("SELECT id FROM #user WHERE absid = '$id'");

	if ( $id == 1 ) $_POST['username'] = 'admin'; // can't let them change admin's username

	if (!adesk_user_check($_POST))
		return adesk_ajax_api_result(0, _a("The username you have selected already exists; please choose another."));

	if (adesk_ihook_exists("acg_user_validate")) {
		$rval = adesk_ihook("acg_user_validate");

		if ($rval !== true)
			return $rval;
	}

	if (isset($_POST["group"]) && is_array($_POST["group"])) {
		if (!adesk_group_canaccess($userid, $_POST["group"]))
			return adesk_ajax_api_result(0, _a("This user cannot belong to all of the groups you have given."));
	}

	if (!isset($_POST["group"]))
		$_POST["group"] = $GLOBALS["admin"]["groups"];

	if (!is_array($_POST["group"]))
		$_POST["group"] = array($_POST["group"]);

	$groups = implode("', '", $_POST['group']);
	$isAdmin = (bool)adesk_sql_select_one('=COUNT(*)', '#group', "id IN ('$groups') AND p_admin = 1");
	$wasAdmin = (bool)adesk_sql_select_one("
		SELECT
			COUNT(u.id)
		FROM
			#user_group u,
			#group g
		WHERE
			u.groupid = g.id
		AND
			u.userid = '$userid'
		AND
			g.p_admin = 1
	");
	if ( !$wasAdmin and $isAdmin ) {
		$site = adesk_site_get();
		// if ( $site['adminsLeft'] < 1 ) {
		// 	return array("succeeded" => 0, "message" => _a("Your current license does not allow you to add any more users with administrative/author privileges"));
		// }
	}

	unset($_POST["password_r"]);

	if ($_POST["password"] == "")
		unset($_POST["password"]);

	if (isset($_POST["password"]))
		$_POST["password"] = md5($_POST["password"]);

	adesk_auth_update($_POST, $id);

	$up = array(
		"username"     => $_POST["username"],
		"first_name"   => $_POST["first_name"],
		"last_name"    => $_POST["last_name"],
		"email"        => $_POST["email"],
	);

	adesk_sql_update("#user", $up, "absid = '$id'");

	if (isset($_POST["group"]) && adesk_user_can_modify($userid) && adesk_group_canaccess($userid, $_POST["group"]))
		adesk_group_relate_user($userid, (array)$_POST["group"]);

	adesk_ihook("acg_user_update_post", $userid, true);

	return adesk_ajax_api_updated(_a("User"));
}

function adesk_user_insert_post() {
	if (!adesk_user_permission("add"))
		return adesk_ajax_api_nopermission(_a("add users"));

	require_once awebdesk_functions("group.php");
	if (!adesk_array_has($_POST, "username", "password", "password_r", "email", "first_name", "last_name"))
		return array("succeeded" => 0, "message" => _a("Internal error: POST does not contain all necessary form variables"));

	if (!adesk_user_check($_POST))
		return adesk_ajax_api_result(0, _a("The username you have selected already exists; please choose another."));

	if (isset($_POST["group"]) && is_array($_POST["group"])) {
		if (!adesk_group_canaccess(0, $_POST["group"]))
			return adesk_ajax_api_result(0, _a("This user cannot belong to all of the groups you have given."));
	}

	if (adesk_ihook_exists("acg_user_validate")) {
		$rval = adesk_ihook("acg_user_validate");

		if ($rval !== true)
			return $rval;
	}

	if (!isset($_POST["group"]))
		$_POST["group"] = $GLOBALS["admin"]["groups"];
	else
		$_POST["group"] = adesk_http_param_forcearray("group");

	$groups = implode("', '", $_POST['group']);
	$isAdmin = (bool)adesk_sql_select_one('=COUNT(*)', '#group', "id IN ('$groups') AND p_admin = 1");

	if ( $isAdmin ) {
		$site = $GLOBALS["site"];
		// if ( isset($site['adminsLeft']) && $site['adminsLeft'] < 1 ) {
		// 	return array("succeeded" => 0, "message" => _a("Your current license does not allow you to add any more users with administrative/author privileges"));
		// }
	}

	$id         = adesk_auth_create_array($_POST);
	$site       = adesk_site_get();

	$ins = array(
		"absid"        => $id,
		"lang"         => $site['lang'],
		"t_offset"     => $site["t_offset"],
		"t_offset_o"   => $site["t_offset_o"],
		"local_zoneid" => $site["local_zoneid"],
		"username"     => $_POST["username"],
		"first_name"   => $_POST["first_name"],
		"last_name"    => $_POST["last_name"],
		"email"        => $_POST["email"],
	);

	adesk_sql_insert("#user", $ins);
	$userid = adesk_sql_insert_id();
	$ins['userid'] = $userid;

	adesk_group_relate_user($userid, (array)$_POST["group"]);
	adesk_ihook("acg_user_update_post", $userid, false);

	return adesk_ajax_api_added(_a("User"), $ins);
}

function adesk_user_check(&$ary) {
	if ( !$ary["username"] ) return false;
	# If there is already a user with this username, we should not add any other.
	$auth = adesk_auth_record_username($ary["username"]);

	if ($auth === null)
		return true;

	if (isset($ary["id"]) && $ary["id"] == $auth["id"])
		return true;

	return false;
}

function adesk_user_delete($id, $extra = null) {
	if (!adesk_user_permission("delete"))
		return adesk_ajax_api_nopermission(_a("delete users"));

	$id  = (int)$id;
	$userid = (int)adesk_sql_select_one("SELECT id FROM #user WHERE absid = '$id'");
	$admin = adesk_admin_get();

	// Can't delete yourself.
	if ($admin["id"] == $userid)
		return adesk_ajax_api_result(false, _a("Cannot delete user"));

	if (!adesk_user_can_modify($userid))
		return adesk_ajax_api_result(false, _a("Cannot delete user"));
	$ary = adesk_auth_record_id($id);

	adesk_ihook("acg_user_delete", $id, $extra);

	if ($ary !== false) {
		$userid = (int)adesk_sql_select_one("SELECT id FROM #user WHERE absid = '$id'");
		adesk_sql_query("DELETE FROM #user WHERE id = '$userid'");
		adesk_sql_query("DELETE FROM #user_group WHERE userid = '$userid'");

		adesk_auth_productset_remove($id);
		return adesk_ajax_api_deleted(_a("User"));
	}

	return adesk_ajax_api_result(false, _a("User not found"));
}

function adesk_user_delete_multi($ids, $extra = null) {
	if (!adesk_user_permission("delete"))
		return adesk_ajax_api_nopermission(_a("delete users"));

	$tmp = array_map("intval", explode(",", $ids));

	$admin = adesk_admin_get();
	foreach ($tmp as $absid) {
		$userid = (int)adesk_sql_select_one("SELECT id FROM #user WHERE absid = '$absid'");
		if ($admin["id"] == $userid and !adesk_user_can_modify($userid))
			return adesk_ajax_api_nopermission(_a("delete users"));
	}
	$in  = array_search(1, $tmp);
	if ($in !== false)
		unset($tmp[$in]);

	adesk_ihook("acg_user_delete_multi", implode(",", $tmp), $extra);

	$ids = implode("','", $tmp);
	$list = adesk_sql_select_list("SELECT id FROM #user WHERE absid IN ('$ids')");
	$liststr = implode("','", $list);
	adesk_sql_query("DELETE FROM #user WHERE absid IN ('$ids')");
	adesk_sql_query("DELETE FROM #user_group WHERE userid IN ('$liststr')");
	foreach ($tmp as $authid)
		adesk_auth_delete($authid);
	return adesk_ajax_api_deleted(_a("Users"));
}

function adesk_user_guest() {
	# Return a row that would be used as an "admin" row in the event that you are not logged in.
	$guest = array_merge(
		adesk_sql_default_row('aweb_globalauth', true),
		adesk_sql_default_row('#user')
	);
	$guest['fullname'] = '';

	return $guest;
}

# --
# Global functions

function adesk_user_global_import($absid, $force = false, $group = array()) {
	if (!$force && !adesk_user_permission("add"))
		return adesk_ajax_api_nopermission(_a("add users"));

	$record = adesk_auth_record_id($absid);

	if ($record === false)
		return adesk_ajax_api_result(false, _a("User does not exist"));

	$ary = array(
		"absid"      => intval($absid),
		"approved"   => 1,
		"username"   => $record["username"],
		"first_name" => $record["first_name"],
		"last_name"  => $record["last_name"],
		"email"      => $record["email"],
	);

	$groups = implode("', '", $group);
	$isAdmin = (bool)adesk_sql_select_one('=COUNT(*)', '#group', "id IN ('$groups') AND p_admin = 1");
	if ( $isAdmin ) {
		$site = $GLOBALS["site"];
		// if ( $site['adminsLeft'] < 1 ) {
		// 	return array("succeeded" => 0, "message" => _a("Your current license does not allow you to add any more users with administrative/author privileges"));
		// }
	}

	adesk_sql_insert("#user", $ary);
	$userid = adesk_sql_insert_id();
 


	if (count($group) > 0) {
		foreach ($group as $groupid) {
			$ary = array(
				"userid"  => $userid,
				"groupid" => $groupid,
			);

			adesk_sql_insert("#user_group", $ary);

 
		}
	}

	adesk_auth_productset_add($absid);

	return adesk_ajax_api_added(_a("User"));
}

function adesk_user_global_delete($absid) {
	if (!adesk_user_permission("delete"))
		return adesk_ajax_api_nopermission(_a("delete users"));

	$record = adesk_auth_record_id($absid);

	if ($record === false)
		return adesk_ajax_api_result(false, _a("User does not exist"));

	adesk_auth_delete($absid);
	return adesk_ajax_api_deleted(_a("User"));
}

function adesk_user_export($ary) {
	$so     = new adesk_Select;
	$admin  = $GLOBALS["admin"];
	$titles = array();
	$keys   = array();

	if ($ary["user"]) {
		$so->slist[] = "username";
		$titles[] = _a("User");
		$keys[] = "username";
	}
	if ($ary["name"]) {
		$so->slist[] = "CONCAT(first_name, ' ', last_name) AS name";
		$titles[] = _a("Name");
		$keys[] = "name";
	}
	if ($ary["email"]) {
		$so->slist[] = "email";
		$titles[] = _a("Email");
		$keys[] = "email";
	}

	if ($ary["filterid"]) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$ary[filterid]' AND userid = '$admin[id]' AND sectionid = 'user'");
		$so->push($conds);
	}

	adesk_http_header_attach("export.csv", 0, "text/csv");

	$rows = adesk_user_select_array($so);

	echo adesk_array_csv(
		$rows,
		$titles,
		$keys
	);

	exit;
}

?>
