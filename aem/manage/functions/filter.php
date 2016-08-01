<?php

require_once awebdesk_classes("select.php");

function filter_select_query(&$so) {
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		if ( $admin['id'] > 1 ) {
			if ( !isset($so->permsAdded) ) {
				$so->permsAdded = 1;
				
				$uid = $admin['id'];
				
				$lists = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");
	//get the lists of users
	
	@$liststr = implode("','",$lists);
				$so->push("AND (f.hidden = 1 OR (SELECT COUNT(*) FROM #filter_list l WHERE l.filterid = f.id AND l.listid IN ('$liststr')) > 0)");
			}
		}
	}
	return $so->query("
		SELECT
			f.*
		FROM
			#filter f
		WHERE
			[...]
	");
}

function filter_listfilters($lists) {
	if (!is_array($lists) && !is_string($lists))
		return array();

	if (is_string($lists))
		$lists = explode(",", $lists);

	$liststr = implode("','", array_map('intval', $lists));

	$so = new adesk_Select;
	$so->push("AND (SELECT COUNT(*) FROM #filter_list l WHERE l.filterid = f.id AND l.listid IN ('$liststr')) > 0");
	$so->push("AND f.hidden = 0");

	return adesk_sql_select_array(filter_select_query($so));
}

function filter_allows_campaignuse($filterid) {
	$filterid = (int)$filterid;

	if ($filterid == 0)
		return true;

	$flogic   = (string)adesk_sql_select_one("SELECT logic FROM #filter WHERE id = '$filterid'");

	if ($flogic == "or")
		return false;

	$glist    = adesk_sql_select_list("SELECT logic FROM #filter_group WHERE filterid = '$filterid'");
	foreach ($glist as $glogic) {
		if ($glogic == "or")
			return false;
	}

	return true;
}

function filter_hidden($params) {
	# Create a hidden filter, without any meaningful data.
	$ary = array(
		"name"   => "",
		"logic"  => "and",
		"hidden" => 1,
	);

	adesk_sql_insert("#filter", $ary);
	$id = adesk_sql_insert_id();

	if (is_array($params) && isset($params["type"])) {
		# Grab all filter groups and insert them.  Remember the last group id.

		$gmap = array();
		$lastgroup = 0;

		$grs = adesk_sql_query("SELECT * FROM #filter_group WHERE filterid = '$params[filterid]'");
		while ($row = adesk_sql_fetch_assoc($grs)) {
			# The logic here will always be "and" (as it would have been for the original
			# filter).
			$ins = array(
				"filterid" => $id,
				"logic" => "and",
			);

			adesk_sql_insert("#filter_group", $ins);
			$gmap[$row["id"]] = adesk_sql_insert_id();
			$lastgroup = $gmap[$row["id"]];
		}

		if ($lastgroup > 0) {
			$crs = adesk_sql_query("SELECT * FROM #filter_group_cond WHERE filterid = '$params[filterid]'");
			while ($row = adesk_sql_fetch_assoc($crs)) {
				$ins = array(
					"filterid" => $id,
					"groupid"  => $gmap[$row["groupid"]],
					"type"     => $row["type"],
					"lhs"      => $row["lhs"],
					"op"       => $row["op"],
					"rhs"      => $row["rhs"],
				);

				adesk_sql_insert("#filter_group_cond", $ins);
			}
		}

		if ($lastgroup == 0) {
			# In all likelihood, filterid is zero; there was no filter used.  In that case, we need
			# to add our "last group" here.

			$ins = array(
				"filterid" => $id,
				"logic" => "and",
			);

			adesk_sql_insert("#filter_group", $ins);
			$lastgroup = adesk_sql_insert_id();
		}

		switch ($params["type"]) {
			# If newsub, then sdate needs to be present in $params.  That's the subscription
			# date.  It has to be a date, not a date-time, due to how filters are written;
			# but if it's an issue, we can add stime and create a second condition for that too.
			case "newsub":
				$ins = array(
					"filterid" => $id,
					"groupid"  => $lastgroup,
					"type"     => "standard",
					"lhs"      => "*cdatetime",
					"op"       => "greater",
                    "rhs"      => $params["sdate"],
				);
				adesk_sql_insert("#filter_group_cond", $ins);
				break;

			case "unread":
				# If unread, we only need the campaignid.
				$ins = array(
					"filterid" => $id,
					"groupid"  => $lastgroup,
					"type"     => "action",
					"lhs"      => "notopened",
					"op"       => $params["campaignid"],
                    "rhs"      => "",
				);
				adesk_sql_insert("#filter_group_cond", $ins);
				break;

			default:
				break;
		}
	}

	return $id;
}

function filter_select_row($id, $offset = true) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND f.id = '$id'");

	$filter = adesk_sql_select_row(filter_select_query($so));

	if ( $filter ) {
		# If we are selecting a single filter, we probably want all of the filter groups and conditions.
		$filter["groups"] = adesk_sql_select_array("SELECT * FROM #filter_group WHERE filterid = '$id' ORDER BY id");
		$filter["lists"]  = implode(",", adesk_sql_select_list("SELECT listid FROM #filter_list WHERE filterid = '$id'"));

		for ($i = 0; $i < count($filter["groups"]); $i++) {
			$groupid = $filter["groups"][$i]["id"];
			$filter["groups"][$i]["conds"] = adesk_sql_select_array("SELECT * FROM #filter_group_cond WHERE groupid = '$groupid' ORDER BY id");

			# Fix escapes for like/notlike conds.
			for ($j = 0; $j < count($filter["groups"][$i]["conds"]); $j++) {
				$cond =& $filter["groups"][$i]["conds"][$j];
				if ($cond["op"] == "like" || $cond["op"] == "notlike") {
					$cond["rhs"] = str_replace("\\_", "_", $cond["rhs"]);
					$cond["rhs"] = str_replace("\\%", "%", $cond["rhs"]);
				}

				if ($cond["lhs"] == "*ctime" && $offset) {
					$parts = explode(":", $cond["rhs"]);
					$hour  = (int)$parts[0];
					$off   = adesk_date_offset_hour();
					$hour  += $off;

					if ($hour < 0) {
						$hour = 24 + $hour;	# 24 + -3 = 21, e.g.
					} elseif ($hour > 23) {
						$hour = $hour - 24;	# 25 - 24 = 1, e.g.
					}

					$cond["rhs"] = sprintf("%02d:%02d:%02d", $hour, $parts[1], $parts[2]);
				}
			}
		}
	}

	return $filter;
}

function filter_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND f.id IN ('$ids')");
	}
	return adesk_sql_select_array(filter_select_query($so));
}

function filter_select_array_paginator($id, $sort, $offset, $limit, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;
	$so->push("AND f.hidden = 0");

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'filter'");
		$so->push($conds);
	}

	$so->count();
	$total = (int)adesk_sql_select_one(filter_select_query($so));

	switch ($sort) {
		case "01":
			$so->orderby("f.name"); break;
		case "01D":
			$so->orderby("f.name DESC"); break;
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = filter_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function filter_filter_post() {
	$whitelist = array("name");

	$ary = array(
		"userid"    => $GLOBALS['admin']['id'],
		"sectionid" => "filter",
		"conds"     => "",
		"=tstamp"   => "NOW()",
	);

	if (isset($_POST["qsearch"]) && !isset($_POST["content"])) {
		$_POST["content"] = $_POST["qsearch"];
	}

	if (isset($_POST["content"]) and $_POST["content"] != "") {
		$content = adesk_sql_escape($_POST["content"], true);
		$conds = array();

		if (!isset($_POST["section"]) || !is_array($_POST["section"]))
			$_POST["section"] = $whitelist;

		foreach ($_POST["section"] as $sect) {
			if (!in_array($sect, $whitelist))
				continue;
			$conds[] = "$sect LIKE '%$content%'";
		}

		$conds = implode(" OR ", $conds);
		$ary["conds"] = "AND ($conds) ";
	}

	if ( isset($_POST['listid']) ) {
		if ( defined('AWEBVIEW') ) {
			$_SESSION['nlp'] = $_POST['listid'];
		} else {
			$_SESSION['nla'] = $_POST['listid'];
		}
	}
	$nl = null;
	if ( isset($_SESSION['nlp']) and defined('AWEBVIEW') ) {
		$nl = $_SESSION['nlp'];
	} elseif ( isset($_SESSION['nla']) ) {
		$nl = $_SESSION['nla'];
	}
	if ( $nl ) {
		if ( is_array($nl) ) {
			if ( count($nl) > 0 ) {
				$ids = implode("', '", array_map('intval', $nl));
				$ary['conds'] .= "AND (SELECT COUNT(*) FROM #filter_list l WHERE l.filterid = f.id AND l.listid IN ('$ids')) > 0 ";
				//$ary['conds'] .= "AND l.listid IN ('$ids') ";
			} else {
				if ( defined('AWEBVIEW') ) {
					unset($_SESSION['nlp']);
				} else {
					unset($_SESSION['nla']);
				}
			}
		} else {
			$listid = (int)$nl;
			if ( $listid > 0 ) {
				$ary['conds'] .= "AND (SELECT COUNT(*) FROM #filter_list l WHERE l.filterid = f.id AND l.listid = '$listid') = 1 ";
				//$ary['conds'] .= "AND l.listid = '$listid' ";
			} else {
				if ( defined('AWEBVIEW') ) {
					unset($_SESSION['nlp']);
				} else {
					unset($_SESSION['nla']);
				}
			}
		}
	}
	if ( $ary['conds'] == '' ) return array('filterid' => 0);

	$conds_esc = adesk_sql_escape($ary["conds"]);
	$filterid = adesk_sql_select_one("
		SELECT
			id
		FROM
			#section_filter
		WHERE
			userid = '$ary[userid]'
		AND
			sectionid = 'filter'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

function filter_insert_post() {

	$lhs     = adesk_http_param("filter_group_cond_lhs");
	$op      = adesk_http_param("filter_group_cond_op");
	$rhs     = adesk_http_param("filter_group_cond_rhs");
	$condlen = adesk_http_param("filter_group_condlen");	# For each group, how many conditions they have
	$glogic  = adesk_http_param("filter_group_logic");		# Per-group logic
	if ( !$lhs or !$op or !$rhs or !$condlen or !$glogic ) {
		return adesk_ajax_api_result(false, _a("Segment info not provided."));
	}

	$ary = array(
		"name" => adesk_http_param("filter_name"),
		"logic" => adesk_http_param("filter_logic"),
	);

	$sql = adesk_sql_insert("#filter", $ary);
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Sending Filter could not be added."));
	}
	$id = adesk_sql_insert_id();

	filter_update_lists($id);
	filter_update_groupconds($id);

	return adesk_ajax_api_added(_a("Sending Filter"), array("id" => $id, "name" => $ary["name"]));
}

function filter_update_lists($filterid) {
	$p        = adesk_http_param("listid");
	$filterid = intval($filterid);

	if ($p !== false && is_array($p)) {
		$ary = array(
			"filterid" => $filterid,
		);

		adesk_sql_query("DELETE FROM #filter_list WHERE filterid = '$filterid'");
		foreach ($p as $listid) {
			$ary["listid"] = $listid;
			adesk_sql_insert("#filter_list", $ary);
		}
	}
}

function filter_update_groupconds($filterid) {
	$lhs     = adesk_http_param_forcearray("filter_group_cond_lhs");
	$op      = adesk_http_param_forcearray("filter_group_cond_op");
	$rhs     = adesk_http_param_forcearray("filter_group_cond_rhs");
	$condlen = adesk_http_param_forcearray("filter_group_condlen");	# For each group, how many conditions they have
	$glogic  = adesk_http_param_forcearray("filter_group_logic");		# Per-group logic

	# Throw away the first elements in $glogic and $lhs -- these are coming from hidden "example"
	# inputs which are used for cloneNode method calls in javascript.
	array_shift($glogic);
	array_shift($lhs);

	if (count($op) > 0 && $op[0] == "")
		array_shift($op);

	ksort($lhs);
	ksort($op);

	if (is_array($rhs)) {
		# Another weirdo browser quirk; sometimes rhs comes in with no zero index.
		if (!isset($rhs[0]))
			$rhs[0] = "";

		# All this is caused by some crazy bug where adesk_form_post convinces itself to create
		# multiple (2, 3, 4+) array depths--but only for rhs.  Weird.
		while (is_array($rhs[0]))
			$rhs = $rhs[0];

		ksort($rhs);
	}

	ksort($condlen);
	ksort($glogic);

	$filterid = intval($filterid);

	# Clear out the old groups and conditions.
	adesk_sql_query("DELETE FROM #filter_group_cond WHERE filterid = '$filterid'");
	adesk_sql_query("DELETE FROM #filter_group WHERE filterid = '$filterid'");

	for ($i = 0, $clen = count($condlen); $i < $clen; $i++) {
		if (current($condlen) == 0) {
			# This group has probably been deleted; even if not, it's invalid.
			array_shift($condlen);
			continue;
		}

		$insg = array(
			"filterid" => $filterid,
			"logic"    => array_shift($glogic),
		);

		adesk_sql_insert("#filter_group", $insg);
		$groupid = adesk_sql_insert_id();

		for ($j = 0, $len = array_shift($condlen); $j < $len; $j++) {
			$tmp     = explode(":", array_shift($lhs));
			$thisop  = array_shift($op);
			if (is_array($rhs))
				$thisrhs = array_shift($rhs);
			else
				$thisrhs = $thisop;

			if (is_array($thisrhs) && isset($thisrhs[0])) {
				# This is really weird, and shouldn't happen, but sometimes adesk_form_post will
				# push in an extra array level...
				$thisrhs = $thisrhs[0];
			}

			if (count($tmp) != 2)
				continue;

			$type    = $tmp[0];
			$thislhs = $tmp[1];

			$insc = array(
				"filterid" => $filterid,
				"groupid"  => $groupid,
				"type"     => $type,
				"lhs"      => $thislhs,
				"op"       => $thisop,
				"rhs"      => $thisrhs,
			);

			if ($thislhs == "*ctime") {
				$off = adesk_date_offset_hour();
				$parts = explode(":", $insc["rhs"]);
				$hour = (int)$parts[0];
				$hour -= $off;

				if ($hour < 0) {
					$hour = 24 + $hour;	# 24 + -3 = 21, e.g.
				} elseif ($hour > 23) {
					$hour = $hour - 24;	# 25 - 24 = 1, e.g.
				}

				$insc["rhs"] = sprintf("%02d:%02d:%02d", $hour, $parts[1], $parts[2]);
			} elseif ($thislhs == "inlist" || $thislhs == "notinlist") {
				$insc["rhs"] = $insc["op"];
			}

			if ($thisop == "like" || $thisop == "notlike")
				$insc["rhs"] = addcslashes($insc["rhs"], "%_");

			adesk_sql_insert("#filter_group_cond", $insc);
		}
	}
}

function filter_update_post() {
	$ary = array(
		"name" => adesk_http_param("filter_name"),
		"logic" => adesk_http_param("filter_logic"),
	);

	$id = intval($_POST["id"]);
	$sql = adesk_sql_update("#filter", $ary, "id = '$id'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Sending Filter could not be updated."));
	}

	filter_update_lists($id);
	filter_update_groupconds($id);

	return adesk_ajax_api_updated(_a("Sending Filter"), array("id" => $id, "name" => $ary["name"]));
}

function filter_hide($id) {
	$id = intval($id);
	adesk_sql_query("UPDATE #filter SET hidden = 1 WHERE id = '$id'");

	return adesk_ajax_api_deleted(_a("Sending Filter"));
}

function filter_hide_multi($ids) {
	if ( $ids == '_all' ) $ids = null;
	$so = new adesk_Select();
	$so->slist = array('f.id');
	$so->remove = false;
	$tmp = filter_select_array($so, $ids);
	$idarr = array();
	foreach ( $tmp as $v ) {
		$idarr[] = $v['id'];
	}
	$ids = implode("','", $idarr);
	adesk_sql_query("UPDATE #filter SET hidden = 1 WHERE id IN ('$ids')");
	return adesk_ajax_api_deleted(_a("Sending Filter"));
}

function filter_delete($id) {
	$id = intval($id);
	adesk_sql_query("DELETE FROM #filter WHERE id = '$id'");
	filter_delete_relations(array($id));
	return adesk_ajax_api_deleted(_a("Sending Filter"));
}

function filter_delete_multi($ids, $filter = 0) {
	if ( $ids == '_all' ) $ids = null;
	$so = new adesk_Select();
	$so->slist = array('f.id');
	$so->remove = false;
	$filter = intval($filter);
	if ($filter > 0) {
		$admin = adesk_admin_get();
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'filter'");
		$so->push($conds);
	}
	$tmp = filter_select_array($so, $ids);
	$idarr = array();
	foreach ( $tmp as $v ) {
		$idarr[] = $v['id'];
	}
	$ids = implode("','", $idarr);
	adesk_sql_query("DELETE FROM #filter WHERE id IN ('$ids')");
	filter_delete_relations($idarr);
	return adesk_ajax_api_deleted(_a("Sending Filter"));
}

function filter_delete_relations($ids) {
	adesk_sql_query("DELETE FROM #filter_group WHERE filterid IN ('$ids')");
	adesk_sql_query("DELETE FROM #filter_group_cond WHERE filterid IN ('$ids')");
	adesk_sql_query("DELETE FROM #filter_subscriber WHERE filterid IN ('$ids')");
}

function filter_listids($filterid) {
	$filterid = intval($filterid);
	return adesk_sql_select_list("SELECT listid FROM #filter_list WHERE filterid = '$filterid'");
}

function filter_lists($seq, $def) {
	$so = new adesk_Select;
	//sandeep
	$admin   = adesk_admin_get();
	        $uid = $admin['id'];
	if($uid != 1 ){
	$lists = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");


	//get the lists of users
	
	@$liststr = implode("','",$lists);
	}
	else
	{
	@$liststr = implode("','", $admin["lists"]);
	}

	
	//$liststr = implode("','", $admin["lists"]);

	$so->push("AND id IN ('$liststr')");
	$lists = adesk_sql_select_array($so->query("SELECT id, name FROM #list WHERE [...]"));

	return array(
		"lists" => $lists,
		"seq"   => $seq,
		"def"   => $def,
	);
}

function filter_links($campaignid, $seq, $def) {
	$admin      = $GLOBALS["admin"];
	$campaignid = intval($campaignid);
	$so         = new adesk_Select;

	if ($campaignid > 0)
		$so->push("AND campaignid = '$campaignid'");
	else
		$so->push("AND campaignid IN ((SELECT cl.campaignid FROM #campaign_list cl WHERE cl.listid IN (SELECT lg.listid FROM #list_group lg WHERE lg.groupid IN (SELECT ug.groupid FROM #user_group ug WHERE ug.userid = '$admin[id]'))))");

	$so->push("AND link != 'open'");
	$links = adesk_sql_select_array($so->query("SELECT id, link FROM #link WHERE [...]"));

	return array(
		"links" => $links,
		"seq"   => $seq,
		"def"   => $def,
	);
}

function filter_campaign_linkids($campaignid, $open) {
	$campaignid = intval($campaignid);
	$so         = new adesk_Select;

	$so->push("AND campaignid = '$campaignid'");

	if ($open)
		$so->push("AND link = 'open'");
	else
		$so->push("AND link != 'open'");

	return adesk_sql_select_list($so->query("
		SELECT
			id
		FROM
			#link
		WHERE
			[...]
	"));
}

function filter_list_linkids($listids, $open) {
	$so     = new adesk_Select;

	$liststr = implode("','", $listids);
	$so->push("AND campaignid IN (SELECT cl.campaignid FROM #campaign_list cl WHERE cl.listid IN ('$liststr'))");

	if ($open)
		$so->push("AND link = 'open'");
	else
		$so->push("AND link != 'open'");

	return adesk_sql_select_list($so->query("
		SELECT
			l.id
		FROM
			#link l
		WHERE
			[...]
	"));
}

function filter_social_list_linkids($listids, $ref) {
	$so     = new adesk_Select;

	$liststr = implode("','", $listids);
	$so->push("AND campaignid IN (SELECT cl.campaignid FROM #campaign_list cl WHERE cl.listid IN ('$liststr'))");

	$ref = adesk_sql_escape($ref);
	if ($ref == "")
		$so->push("AND ref != ''");
	else
		$so->push("AND ref = '$ref'");

	return adesk_sql_select_list($so->query("
		SELECT
			l.id
		FROM
			#link l
		WHERE
			[...]
	"));
}

function filter_social_campaign_linkids($campaignid, $ref) {
	$campaignid = intval($campaignid);
	$so         = new adesk_Select;

	$so->push("AND campaignid = '$campaignid'");

	$ref = adesk_sql_escape($ref);
	if ($ref == "")
		$so->push("AND ref != ''");
	else
		$so->push("AND ref = '$ref'");

	return adesk_sql_select_list($so->query("
		SELECT
			id
		FROM
			#link
		WHERE
			[...]
	"));
}

function filter_compile($filterid) {
	$filter = filter_select_row($filterid, false);
	return filter_compile_all($filter);
}

function filter_escape_lhs($lhs) {
	# The lhs of a filter will always be enclosed in identifier quotes (backticks).  An
	# escape of those quote characters in MySQL (4.1+) would to have two such characters
	# next to each other.  From http://dev.mysql.com/doc/refman/4.1/en/identifiers.html:
	#
	# The following statement creates a table named a`b that contains a column named c"d:
	# mysql> CREATE TABLE `a``b` (`c"d` INT);
	return preg_replace('/`/m', '``', $lhs);
}

function filter_op($op) {
	switch ($op) {
		default:
		case "equal":
			return "=";
		case "notequal":
			return "!=";
		case "like":
			return "LIKE";
		case "notlike":
			return "NOT LIKE";
		case "greater":
			return ">";
		case "less":
			return "<";
		case "greatereq":
			return ">=";
		case "lesseq":
			return "<=";
	}

	# We shouldn't get to here...
	return "=";
}

function filter_compile_all($filter) {
	$ary = array();

	foreach ($filter["groups"] as $group)
		$ary[] = filter_compile_group($group);

	$logic = sprintf(" %s ", strtoupper($filter["logic"]));

	return "(" . implode($logic, $ary) . ")";
}

function filter_compile_group($group) {
	$ary = array();
	foreach ($group["conds"] as $cond)
		$ary[] = filter_compile_cond($cond);

	$logic = sprintf(" %s ", strtoupper($group["logic"]));

	return "(" . implode($logic, $ary) . ")";
}

function filter_compile_cond($cond) {
	switch ($cond["type"]) {
		default:
			break;
		case "standard":
			return filter_compile_cond_standard($cond);
		case "custom":
			return filter_compile_cond_custom($cond);
		case "action":
			return filter_compile_cond_action($cond);
	}

	return "";
}

function filter_compile_cond_standard($cond) {
	$valprefix = "";
	$valsuffix = "";

	if ($cond["op"] == "like" || $cond["op"] == "notlike") {
		$valprefix = "%";
		$valsuffix = "%";
	}

	switch ($cond["lhs"]) {
		case "*status":
			$op = filter_op($cond["op"]);
			if ($op == "=")
				$op = "IN";
			elseif ($op == "!=")
				$op = "NOT IN";

			# For some time, we had incorrectly given the Unconfirmed status as 4 (it should be
			# 0) on our filter dropdowns.  We need to be able to support that.
			if ($cond["rhs"] == 4)
				$cond["rhs"] = 0;
			return sprintf("(SELECT COUNT(*) FROM #subscriber_list _subq_sl WHERE _subq_sl.subscriberid = s.id AND _subq_sl.status %s ('%d')) > 0", $op, adesk_sql_escape($cond["rhs"]));
		case "*ip":
			return sprintf("INET_NTOA(s.ip) %s '%s%s%s'", filter_op($cond["op"]), $valprefix, adesk_sql_escape($cond["rhs"]), $valsuffix);
		case "*ctime":
			return sprintf("TIME(s.cdate) %s '%s%s%s'", filter_op($cond["op"]), $valprefix, adesk_sql_escape($cond["rhs"]), $valsuffix);
		case "*cdate":
			return sprintf("DATE(s.cdate) %s '%s%s%s'", filter_op($cond["op"]), $valprefix, adesk_sql_escape($cond["rhs"]), $valsuffix);
		case "*cdatetime":
			return sprintf("s.cdate %s '%s%s%s'", filter_op($cond["op"]), $valprefix, adesk_sql_escape($cond["rhs"]), $valsuffix);
		case "*fullname":
			return sprintf("CONCAT(l.first_name, ' ', l.last_name) %s '%s%s%s'", filter_op($cond["op"]), $valprefix, adesk_sql_escape($cond["rhs"]), $valsuffix);
		case "first_name":
			return sprintf("(SELECT COUNT(*) FROM #subscriber_list l WHERE l.subscriberid = s.id AND l.%s %s '%s%s%s') > 0", filter_escape_lhs($cond["lhs"]), filter_op($cond["op"]), $valprefix, adesk_sql_escape($cond["rhs"]), $valsuffix);
		case "last_name":
			return sprintf("(SELECT COUNT(*) FROM #subscriber_list l WHERE l.subscriberid = s.id AND l.%s %s '%s%s%s') > 0", filter_escape_lhs($cond["lhs"]), filter_op($cond["op"]), $valprefix, adesk_sql_escape($cond["rhs"]), $valsuffix);
		default:
			if (preg_match('/^[0-9]+$/', $cond["rhs"]) && $cond["op"] != "like" && $cond["op"] != "notlike")
				return filter_compile_cond_standard_number($cond);
			else
				return sprintf("s.`%s` %s '%s%s%s'", filter_escape_lhs($cond["lhs"]), filter_op($cond["op"]), $valprefix, adesk_sql_escape($cond["rhs"]), $valsuffix);
	}
}

function filter_compile_cond_standard_number($cond) {
	return sprintf("CAST(s.`%s` AS SIGNED INTEGER) %s %s", filter_escape_lhs($cond["lhs"]), filter_op($cond["op"]), adesk_sql_escape($cond["rhs"]));
}

function filter_compile_cond_custom_number($cond, $maybeblank) {
	return sprintf("s.id IN (SELECT _subq_lfv.relid FROM #list_field_value _subq_lfv WHERE fieldid = '%d' AND (CAST(val AS SIGNED INTEGER) %s %s%s))", (int)$cond["lhs"], filter_op($cond["op"]), adesk_sql_escape($cond["rhs"]), $maybeblank);
}

function filter_compile_cond_custom($cond) {
	$valprefix = "";
	$valsuffix = "";
	$maybeblank = "";

	if ($cond["op"] == "like" || $cond["op"] == "notlike") {
		$valprefix = "%";
		$valsuffix = "%";
	}

	if ($cond["rhs"] == "") {
		if ($cond["op"] == "notlike")
			$op = "LIKE";
		elseif ($cond["op"] == "notequal")
			$op = "=";
		else
			$op = filter_op($cond["op"]);
		$maybeblank = sprintf(" OR val %s '%s~|%s'", $op, $valprefix, $valsuffix);
	}

	switch ($cond["op"]) {
		case "notlike":
			$op = "LIKE";
			return sprintf("s.id NOT IN (SELECT _subq_lfv.relid FROM #list_field_value _subq_lfv WHERE fieldid = '%d' AND (val %s '%s%s%s'%s))", intval($cond["lhs"]), $op, $valprefix, adesk_sql_escape($cond["rhs"]), $valsuffix, $maybeblank);
		case "notequal":
			$op = "=";
			return sprintf("s.id NOT IN (SELECT _subq_lfv.relid FROM #list_field_value _subq_lfv WHERE fieldid = '%d' AND (val %s '%s%s%s'%s))", intval($cond["lhs"]), $op, $valprefix, adesk_sql_escape($cond["rhs"]), $valsuffix, $maybeblank);
		case "oneof":
			return sprintf("s.id IN (SELECT _subq_lfv.relid FROM #list_field_value _subq_lfv WHERE fieldid = '%d' AND FIND_IN_SET(val, '%s'))", intval($cond["lhs"]), adesk_sql_escape(implode(",", array_map('trim', explode(",", $cond["rhs"])))));
		default:
			if (preg_match('/^-?[0-9]+$/', $cond["rhs"]) && $cond["op"] != "like" && $cond["op"] != "notlike")
				return filter_compile_cond_custom_number($cond, $maybeblank);
			else
				return sprintf("s.id IN (SELECT _subq_lfv.relid FROM #list_field_value _subq_lfv WHERE fieldid = '%d' AND (val %s '%s%s%s'%s))", intval($cond["lhs"]), filter_op($cond["op"]), $valprefix, adesk_sql_escape($cond["rhs"]), $valsuffix, $maybeblank);
	}
}

function filter_compile_cond_action($cond) {
	$in = "IN";
	if ($cond["rhs"] == 0)
		return filter_compile_cond_action_any($cond);
	switch ($cond["lhs"]) {
		default:
			return "";
		case "linknotclicked":
			$in = "NOT IN";
		case "linkclicked":
			return sprintf("s.id $in (SELECT _subq_ld.subscriberid FROM #link_data _subq_ld WHERE _subq_ld.linkid = '%s')", $cond["rhs"]);
		case "notinlist":
			$in = "NOT IN";
		case "inlist":
			return sprintf("s.id $in (SELECT _subq_sl.subscriberid FROM #subscriber_list _subq_sl WHERE _subq_sl.listid = '%s' AND _subq_sl.status = 1)", adesk_sql_escape($cond["rhs"]));
		case "notopened":
			$in = "NOT IN";
		case "opened":
			if ($cond["op"] != "") {
				# Assume we were passed the campaignid in the op field.
				return sprintf("s.id $in (SELECT _subq_ld.subscriberid FROM #link_data _subq_ld WHERE _subq_ld.linkid = (SELECT _subq_l.id FROM #link _subq_l WHERE _subq_l.link = 'open' AND _subq_l.messageid = '' AND _subq_l.campaignid = '%s' LIMIT 1))", adesk_sql_escape($cond["op"]));
			} else {
				return sprintf("s.id $in (SELECT _subq_ld.subscriberid FROM #link_data _subq_ld WHERE _subq_ld.linkid IN (SELECT _subq_l.id FROM #link _subq_l WHERE _subq_l.link = 'open' AND _subq_l.messageid = '%s'))", adesk_sql_escape($cond["rhs"]));
			}
		case "notforwarded":
			$in = "NOT IN";
		case "forwarded":
			return sprintf("s.id $in (SELECT _subq_f.subscriberid FROM #forward _subq_f WHERE _subq_f.campaignid = '%s')", adesk_sql_escape($cond["rhs"]));
	}

	# We shouldn't get here.
	return "";
}

function filter_compile_cond_action_any($cond) {
	$in       = "IN";
	$linkcond = "";

	switch ($cond["lhs"]) {
		default:
			return "";
		case "linknotclicked":
			$in = "NOT IN";
		case "linkclicked":
			if ($cond["op"] == 0)
				$links = filter_list_linkids(filter_listids($cond["filterid"]), false);
			else
				$links = filter_campaign_linkids($cond["op"], false);

			$linkstr = implode("','", $links);
			return sprintf("s.id $in (SELECT subqld.subscriberid FROM #link_data subqld WHERE subqld.linkid IN ('$linkstr'))");
		case "social":
			if ($cond["op"] == 0)
				$links = filter_social_list_linkids(filter_listids($cond["filterid"]), $cond["rhs"]);
			else
				$links = filter_social_campaign_linkids($cond["op"], $cond["rhs"]);

			$linkstr = implode("','", $links);
			return sprintf("s.id $in (SELECT subqld.subscriberid FROM #link_data subqld WHERE subqld.linkid IN ('$linkstr'))");
		case "notopened":
			$in = "NOT IN";
		case "opened":
			if ($cond["op"] > 0) {
				# Assume we were passed the campaignid in the op field.
				return sprintf("s.id $in (SELECT _subq_ld.subscriberid FROM #link_data _subq_ld WHERE _subq_ld.linkid = (SELECT _subq_l.id FROM #link _subq_l WHERE _subq_l.link = 'open' AND _subq_l.messageid = '' AND _subq_l.campaignid = '%s' LIMIT 1))", adesk_sql_escape($cond["op"]));
			} else {
				$links   = filter_list_linkids(filter_listids($cond["filterid"]), true);
				$linkstr = implode("','", $links);
				return sprintf("s.id $in (SELECT _subq_ld.subscriberid FROM #link_data _subq_ld WHERE _subq_ld.linkid IN ('$linkstr'))", adesk_sql_escape($cond["op"]));
			}
		case "notforwarded":
			$in = "NOT IN";
		case "forwarded":
			$links   = filter_list_linkids(filter_listids($cond["filterid"]), true);
			$linkstr = implode("','", $links);
			return sprintf("s.id $in (SELECT _subq_f.subscriberid FROM #forward _subq_f WHERE _subq_f.campaignid IN ('$linkstr'))");
	}

	return "";
}

function filter_cache($filterid, $update) {
	if ($update) {
		# If we've updated a filter, we need to invalidate every existing
		# filter cache record.
		return adesk_sql_query("UPDATE #subscriber_filter SET dirty = 1, matches = 0 WHERE filterid = '$filterid'");
	} else {
		# If we're not updating a filter, then we have created this filter for the first
		# time and will need to insert rows for every subscriber that doesn't yet have a filter
		# record.
		return adesk_sql_query("
			INSERT INTO
				#subscriber_filter
			(subscriberid, filterid, matches, dirty)
			SELECT
				s.id,
				'$filterid',
				'0',
				'1'
			FROM
				#subscriber s
			WHERE
				(SELECT COUNT(*) FROM #subscriber_filter sf WHERE sf.subscriberid = s.id AND sf.filterid = '$filterid') = '0'
		");
	}
}

function filter_cache_isnew($filterid) {
	// delete rows from subscriber_filter that are not in subscriber
	adesk_sql_delete(
		"#subscriber_filter f",
		"f.filterid = '$filterid' AND ( SELECT COUNT(*) FROM #subscriber s WHERE s.id = f.subscriberid ) = 0"
	);
	// fetch total from subscriber
	$subs = (int)adesk_sql_select_one("=COUNT(*)", "#subscriber");
	// fetch total from subscriber_filter
	$rows = (int)adesk_sql_select_one("=COUNT(*)", "#subscriber_filter", "filterid = '$filterid'");
	// if there is not enough rows...
	return $subs > $rows;

	// old one checked for at least one, this one checks for exact number
	return (int)adesk_sql_select_one("
		SELECT
			COUNT(*)
		FROM
			#subscriber_filter
		WHERE
			filterid = '$filterid'
	") < 1;
}

function filter_cache_subscriber($subscriberid, $update) {
	$subscriberid = intval($subscriberid);

	if ($update) {
		return adesk_sql_query("UPDATE #subscriber_filter SET dirty = 1, matches = 0 WHERE subscriberid = '$subscriberid'");
	} else {
		return adesk_sql_query("
			INSERT INTO
				#subscriber_filter
			(subscriberid, filterid, matches, dirty)
			SELECT
				'$subscriberid',
				f.id,
				'0',
				'1'
			FROM
				#filter f
			WHERE
				(SELECT COUNT(*) FROM #subscriber_filter sf WHERE sf.filterid = f.id AND sf.subscriberid = '$subscriberid') = '0'
		");
	}
}

function filter_analyze($filterid) {
	$filterid = intval($filterid);
	$cond     = filter_compile($filterid);

	# Mark everyone who matches the filter.
	adesk_sql_query("
		UPDATE
			#subscriber s,
			#subscriber_filter sf
		SET
			sf.dirty = '0',
			sf.matches = '1'
		WHERE
			s.id = sf.subscriberid
		AND sf.filterid = '$filterid'
		AND sf.dirty = '1'
		AND	$cond
	");

	# Anyone who's left and is still marked dirty must
	# not have matched the filter, so go back and mark
	# them all appropriately.
	adesk_sql_query("
		UPDATE
			#subscriber_filter sf
		SET
			sf.dirty = '0',
			sf.matches = '0'
		WHERE
			sf.filterid = '$filterid'
		AND sf.dirty = '1'
	");
}

function filter_analyze_subscriber_inlist($subscriberid, $listids) {
	$liststr   = implode("','", $listids);
	$filterids = adesk_sql_select_list("
		SELECT
			filterid
		FROM
			#filter_list
		WHERE
			listid IN ('$liststr')
	");

	foreach ($filterids as $filterid)
		filter_analyze_subscriber($subscriberid, $filterid);
}

function filter_matches($subscriberid, $filterid) {
	$filterid     = intval($filterid);
	$subscriberid = intval($subscriberid);
	$cond         = filter_compile($filterid);
	$submatch     = "1";

	if ($subscriberid > 0)
		$submatch = "s.id = '$subscriberid'";

	# Then analyze him based on $cond.  We don't need to mark his dirty field as zero again.
	return (int)adesk_sql_select_one("
		SELECT
			COUNT(*)
		FROM
			#subscriber s
		WHERE
			$submatch
		AND	$cond
	");
}

function filter_analyze_subscriber($subscriberid, $filterid, $return = false) {
	$filterid = intval($filterid);
	$subscriberid = intval($subscriberid);
	$cond = filter_compile($filterid);

	# First, mark this subscriber's filter record as unmatched.
	adesk_sql_query("UPDATE #subscriber_filter SET dirty = 0, matches = 0 WHERE subscriberid = '$subscriberid' AND filterid = '$filterid'");

	# Then analyze him based on $cond.  We don't need to mark his dirty field as zero again.
	adesk_sql_query($q = "
		UPDATE
			#subscriber s,
			#subscriber_filter sf
		SET
			sf.matches = '1'
		WHERE
			s.id = sf.subscriberid
		AND sf.filterid = '$filterid'
		AND sf.subscriberid = '$subscriberid'
		AND	$cond
	");

	if ($return) {
		return (int)adesk_sql_select_one("
			SELECT
				matches
			FROM
				#subscriber_filter
			WHERE
				subscriberid = '$subscriberid'
			AND
				filterid = '$filterid'
		");
	}
}

function filter_process($process) {
	$offset = -1;
	foreach ( $process['data'] as $filterid => $deletedcache ) {
		$offset++;
		if ( $offset < $process['completed'] ) continue;
		// remove all old rows
		if (!$deletedcache) {
			adesk_sql_delete('#subscriber_filter', "`filterid` = '$filterid'");
			// put dirties
			filter_cache($filterid, false);
			$process['data'][$filterid] = $deletedcache = 1;
			adesk_process_setdata($process['id'], $process['data']);
		}
		// run this filter
		filter_analyze($filterid);
		// update the process
		adesk_process_update($process['id']);
		$process['completed']++;
	}
}

function filter_importv4($filter) {
	if ($filter["type"] != "FILTER")
		return;

	$ins = array(
		"id"  => $filter["id"], // reuse filter id
		"name"  => $filter["name"],
		"logic" => "and",
	);

	adesk_sql_insert("#filter", $ins);
	$filterid = adesk_sql_insert_id();
	$matches  = array();

	if (preg_match('/NOT_ON_LIST:(\d+)/', $filter["content"], $matches)) {
		# Not on list
		return filter_importv4_notinlist($filterid, $matches[1]);
	} elseif (preg_match('/NOT_READ_MESSAGES:(.*)/', $filter["content"], $matches)) {
		# Not read messages
		return filter_importv4_notopen($filterid, $matches[1]);
	} elseif (preg_match('/0 +OR /', $filter["content"])) {
		# This is the filter "based on e-mail addresses" from v4.
		return filter_importv4_addresses($filterid, $filter["content"]);
	} else {
		return filter_importv4_content($filterid, $filter["content"]);
	}

	return true;
}

function filter_importv4_stdfield($field) {
	switch ($field) {
		case "sdate":
			return "*cdate";
		case "name":
			return "*fullname";
		default:
			return $field;
	}
}

function filter_importv4_notopen($filterid, $messageids) {
	global $oldprefix;
	$cids = adesk_sql_select_list("SELECT DISTINCT(mesg_id) FROM `{$oldprefix}messages` WHERE `id` IN ($messageids)");
	if ( count($cids) == 0 ) return true; //?

	$insg = array(
		"filterid" => $filterid,
		"logic"    => "and",
	);

	if (!adesk_sql_insert("#filter_group", $insg))
		return false;

	$groupid = adesk_sql_insert_id();

	foreach ( $cids as $messageid ) {
		$insc = array(
			"filterid" => $filterid,
			"groupid"  => $groupid,
			"lhs"      => "notopened",
			"op"       => 0,
			"rhs"      => $messageid,
			"type"     => "action",
		);
		if ( !adesk_sql_insert("#filter_group_cond", $insc) ) return false;
	}
	return true;
}

function filter_importv4_notinlist($filterid, $listid) {
	$insg = array(
		"filterid" => $filterid,
		"logic"    => "and",
	);

	if (!adesk_sql_insert("#filter_group", $insg))
		return false;

	$groupid = adesk_sql_insert_id();

	$insc = array(
		"filterid" => $filterid,
		"groupid"  => $groupid,
		"lhs"      => "notinlist",
		"op"       => 0,
		"rhs"      => $listid,
		"type"     => "action",
	);

	return adesk_sql_insert("#filter_group_cond", $insc);
}

function filter_importv4_addresses($filterid, $content) {
	$clauses = explode(" OR ", $content);
	$matches = array();

	$insg = array(
		"filterid" => $filterid,
		"logic"    => "or",
	);

	if (!adesk_sql_insert("#filter_group", $insg))
		return false;

	$groupid = adesk_sql_insert_id();

	foreach ($clauses as $clause) {
		if ($clause == "0")
			# Probably the first one -- it always begins "0 OR ..."
			continue;

		elseif (preg_match('/email = \'(.+)\'/', $clause, $matches)) {
			$insc = array(
				"filterid" => $filterid,
				"groupid"  => $groupid,
				"type"     => "standard",
				"lhs"      => "email",
				"op"       => "equal",
				"rhs"      => $matches[1],
			);

			if (!adesk_sql_insert("#filter_group_cond", $insc))
				return false;
		}
	}

	return true;
}

function filter_importv4_op($op) {
	switch ($op) {
		case ">":
			return "greater";
		case ">=":
			return "greatereq";
		case "<":
			return "less";
		case "<=":
			return "lesseq";
		case "!=":
			return "notequal";
		case "LIKE":
			return "like";
		case "NOT LIKE":
			return "notlike";
		case "=":
		default:
			return "equal";
	}

	return "";
}

function filter_importv4_content($filterid, $content) {
	$oary = explode("||DIVOR||", $content);

	foreach ($oary as $orc) {
		$aary = explode("||DIV||", $orc);
		$insg = array(
			"filterid" => $filterid,
			"logic"    => count($oary) > 1 ? "or" : "and",
		);

		if (!adesk_sql_insert("#filter_group", $insg))
			return false;

		$groupid = adesk_sql_insert_id();

		foreach ($aary as $andc) {
			$insc = array(
				"filterid" => $filterid,
				"groupid"  => $groupid,
			);

			if (strpos($andc, "AND") !== false) {
				# This is going to be a custom field, e.g.: (fid = '1' AND val = 'xyz')

				$matches = array();
				if (preg_match('/fid = \'(\d+)\' AND val (NOT )?(\S+) \'(.+)\'/', $andc, $matches)) {
					# Custom field.  Generally follows the form (fid = 'N' AND val OP 'xyz'),
					# although we don't bother to match the parens.

					$insc["type"] = "custom";
					$insc["lhs"]  = $matches[1];
					if ($matches[3] == "LIKE" && $matches[2] == "NOT ")
						$matches[3] = "NOT LIKE";
					$insc["op"]   = filter_importv4_op($matches[3]);
					$insc["rhs"]  = $matches[4];
				}
			} elseif (preg_match('/(\w+) (\S+) \'(.+)\'/', $andc, $matches)) {
					# Standard field.

					$insc["type"] = "standard";
					$insc["lhs"]  = filter_importv4_stdfield($matches[1]);
					$insc["op"]   = filter_importv4_op($matches[2]);
					$insc["rhs"]  = $matches[3];
			}

			if (!adesk_sql_insert("#filter_group_cond", $insc))
				return false;
		}
	}

	return true;
}

?>
