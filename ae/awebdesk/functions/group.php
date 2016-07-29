<?php

require_once awebdesk_classes("select.php");

function adesk_group_select_query(&$so, $ihookconditions = true, $opts = array()) {
	if (adesk_ihook_exists("adesk_group_select_query_condition") && $ihookconditions)
		$so->push(adesk_ihook("adesk_group_select_query_condition"));

	if (!$so->counting && adesk_ihook_exists("adesk_group_select_query_columns"))
		$so->slist = array_merge(array("*"), adesk_ihook("adesk_group_select_query_columns", $opts));

	return $so->query("
		SELECT
			*
		FROM
			#group g
		WHERE
			[...]
	");
}

function adesk_group_select_row($id) {
	$id = intval($id);
	if ( !$id ) return false;
	$so = new adesk_Select;
	$so->push("AND id = '$id'");

	$row = adesk_sql_select_row(adesk_group_select_query($so, false));

	if (adesk_ihook_exists("adesk_group_select_row"))
		$row = adesk_ihook("adesk_group_select_row", $row);

	return $row;
}

function adesk_group_select_array_userpage($authid) {
	$authid = intval($authid);
	$ary    = adesk_group_select_array();
	if ($authid > 0) {
		$mine   = adesk_sql_select_list("
			SELECT
				groupid
			FROM
				#user_group
			WHERE
				userid = (
					SELECT
						subu.id
					FROM
						#user subu
					WHERE
						subu.absid = '$authid'
				)
		");
	} else {
		$mine = array();
	}

	foreach ($ary as $i => $group) {
		if (in_array($group["id"], $mine))
			$ary[$i]["_selected"] = 1;
		else
			$ary[$i]["_selected"] = 0;
	}

	return $ary;
}

function adesk_group_select_array($so = null, $ids = null, $opts = array()) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	$so->orders = array("title");

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND id IN ('$ids')");
	}

	$rval = adesk_sql_select_array(adesk_group_select_query($so, true, $opts));

	if (adesk_ihook_exists("adesk_group_select_array"))
		$rval = adesk_ihook("adesk_group_select_array", $rval);

	return $rval;
}

function adesk_group_select_box($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	$so->orders = array("title");

	if ($ids !== null) {
		$tmp = array_map("intval", explode(",", $ids));
		$ids = implode("','", $tmp);
		$so->push("AND id IN ('$ids')");
	}

	$rs  = adesk_sql_query(adesk_group_select_query($so));
	$ary = array();

	while ($row = adesk_sql_fetch_assoc($rs))
		$ary[$row["id"]] = $row;

	return $ary;
}

function adesk_group_select_array_paginator($id, $sort, $offset, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'group'");
		$so->push($conds);
	}

	$so->count();
	$total = adesk_sql_select_one(adesk_group_select_query($so, true, array("paginator")));

	switch ($sort) {
		default:
		case "01":
			$so->orderby("title"); break;
		case "01D":
			$so->orderby("title DESC"); break;
		case "02":
			$so->orderby("descript"); break;
		case "02D":
			$so->orderby("descript DESC"); break;
	}

	$offset = (int)$offset;
	$so->limit("$offset, 20");
	$rows = adesk_group_select_array($so, null, array("paginator"));

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function adesk_group_relate_user($userid, $groups) {
		if ($userid == 1 && $admin["id"] != 1)
		return false;

	if (!adesk_group_permission("delete") && !adesk_group_permission("add") )
		return adesk_ajax_api_nopermission(_a("delete groups"));
	$userid = intval($userid);
	
	//From version 6.3 , We done allow more than one user in group id 3 for security reason.
	//Lets check that first
	if($groups[0] == 3)
	   return adesk_ajax_api_nopermission(_a("move or add users to admin groups or this is not allowed for security reasons."));
	   //do stuffs after making sure its not admin
	adesk_sql_query("DELETE FROM #user_group WHERE userid = '$userid'");	
       $groupid = intval($groups[0]);
	adesk_sql_query("INSERT INTO `#user_group` (userid, groupid) VALUES ('$userid', '$groupid')");

}

function adesk_group_canaccess($userid, $groups) {
	# Start out as true.  We may change this if there is a more global reason for you not
	# to have access to a group; for now, it's all app-specific.
	$rval = true;

	if (adesk_ihook_exists("adesk_group_canaccess"))
		$rval = $rval && adesk_ihook("adesk_group_canaccess", $userid, $groups);

	return $rval;
}

function adesk_group_filter_post() {
	$whitelist = array("title", "descript");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "group",
		"conds" => "",
		"=tstamp" => "NOW()",
	);

	if (isset($_POST["qsearch"]) && !isset($_POST["content"])) {
		$_POST["content"] = $_POST["qsearch"];
	}

	if (isset($_POST["content"]) and $_POST['content'] != '') {
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

	$conds_esc = adesk_sql_escape($ary['conds']);
	$filterid = adesk_sql_select_one("
		SELECT
			id
		FROM
			#section_filter
		WHERE
			userid = '$ary[userid]'
		AND
			sectionid = 'group'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

function adesk_group_permission($key) {
	$rval = adesk_ihook("adesk_group_permission", $key);

	if ($rval === $key)
		return false;
	else
		return $rval;
}

function adesk_group_insert_post() {
	if (!adesk_group_permission("add"))
		return adesk_ajax_api_nopermission(_a("add groups"));

	$ary = array(
		"title" => $_POST["title"],
		"descript" => $_POST["descript"],
	);

	$ary = adesk_ihook("adesk_group_insert_post", $ary);

	$done = adesk_sql_insert("#group", $ary);
	if ( !$done ) {
		return adesk_ajax_api_result(false, _a("Group could not be added."), array("group_id" => 0));
	}

	$group_id = adesk_sql_insert_id();

	$r = adesk_ihook("adesk_group_relations", $group_id);
	if ( !$r ) {
		return adesk_ajax_api_added(_a("Group"), array("group_id" => $group_id));
	} else {
		return $r;
	}
}

function adesk_group_update_post() {
	if (!adesk_group_permission("edit"))
		return adesk_ajax_api_nopermission(_a("edit groups"));

	$ary = array(
		"title" => adesk_http_param("title"),
		"descript" => adesk_http_param("descript"),
	);

	$ary = adesk_ihook("adesk_group_insert_post", $ary);

	$id = intval(adesk_http_param("id"));
	adesk_sql_update("#group", $ary, "id = '$id'");

	$r = adesk_ihook("adesk_group_relations", $id);
	adesk_session_drop_cache();
	unset($GLOBALS["admin"]);
	$GLOBALS["admin"] = adesk_admin_get();
	if ( !$r ) {
		return adesk_ajax_api_updated(_a("Group"));
	} else {
		return $r;
	}
}

function adesk_group_delete($id, $alt) {
	if (!adesk_group_permission("delete"))
		return adesk_ajax_api_nopermission(_a("delete groups"));

	$id  = intval($id);
	$alt = intval($alt);

	if ($id < 4)
		return adesk_ajax_api_nopermission(_a("delete groups"));

	adesk_group_delete_relational($id, $alt);
	adesk_sql_query("DELETE FROM #group WHERE id = '$id'");
	return adesk_ajax_api_deleted(_a("Group"));
}

function adesk_group_delete_relational($id, $alt) {
	if (!adesk_group_permission("delete"))
		return adesk_ajax_api_nopermission(_a("delete groups"));
	if ($alt == 3) 
	   return adesk_ajax_api_nopermission(_a("move users to admin groups."));	
	if ($alt == 0) {
		$group = adesk_group_select_row($id);
		$users = adesk_sql_select_array("SELECT * FROM #user_group GROUP BY userid HAVING COUNT(userid) < 2");
		$list = array();

		if ( isset($group["p_admin"]) and $group["p_admin"] == 1 )
			return adesk_ajax_api_nopermission(_a("to move to admin group or its not allowed for security reasons."));
		else
			$alt = 2;	# User group

		foreach ($users as $user) {
			if ($user["groupid"] == $id)
				$list[] = $user["userid"];
		}

		$list_str = implode("','", $list);

		adesk_sql_query("UPDATE #user_group SET groupid = '$alt' WHERE userid IN ('$list_str') AND groupid = '$id'");
		adesk_sql_query("DELETE FROM #user_group WHERE groupid = '$id'");
	} else {
		adesk_sql_query("UPDATE #user_group SET groupid = '$alt' WHERE groupid = '$id'");
	}

	adesk_ihook("adesk_group_delete_relational_post", $id, $alt);
}

function adesk_group_delete_multi($ids, $alt) {
	if (!adesk_group_permission("delete"))
		return adesk_ajax_api_nopermission(_a("delete groups"));

	$tmp = array_map("intval", explode(",", $ids));

	$in  = array_search(1, $tmp);
	if ($in !== false)
		unset($tmp[$in]);
	$in  = array_search(2, $tmp);
	if ($in !== false)
		unset($tmp[$in]);
	$in  = array_search(3, $tmp);
	if ($in !== false)
		unset($tmp[$in]);

	foreach ($tmp as $id)
		adesk_group_delete_relational($id, $alt);

	$ids = implode("','", $tmp);
	adesk_sql_query("DELETE FROM #group WHERE id IN ('$ids')");
	return adesk_ajax_api_deleted(_a("Groups"));
}

?>
