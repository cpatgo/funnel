<?php

require_once(awebdesk_classes('privatemessage.php'));
require_once awebdesk_functions("mail.php");

function adesk_privatemessage_get($id, $fullInfo = false) {
	$query = "
		SELECT
			p.*
		FROM
			#privmsg p
		WHERE
			p.id = '$id'
		LIMIT
			0, 1
	";

	$sql = adesk_sql_query($query);
	if ( !$sql or mysql_num_rows($sql) == 0 ) return false;
	$data = adesk_sql_fetch_assoc($sql, array("cdate"));
	// thread info
	if ($data['threadid'] > 0) {
		$data['threadid'] = adesk_privatemessage_get($data['threadid']);
	}
	// author info
	$data['author_from'] = user_get($data['user_from']);
	$data['author_to'] = user_get($data['user_to']);
	//if ( !$data['author'] ) $data['author'] = adesk_admin_get();

	return $data;
}

function adesk_privatemessage_get_list($offset = 0, $limit = 999999999, $sort = '02D', $filter = array()) {
	$r = array(
		'total' => 0,
		'cnt' => 0,
		'rows' => array(),
		'offset' => $offset,
		'limit' => $limit
	);
	$admin = adesk_admin_get();
	$site = adesk_site_get();
	if ( $limit == 0 ) $limit = 999999;
	// fetch articles list
	$select = new adesk_Privatemessages();

	// Inbox filter
	if ( isset($filter['user_to']) ) {
		$select->filterInbox($filter['user_to']);
	}

	// Sent filter
	if ( isset($filter['user_from']) ) {
		$select->filterSent($filter['user_from']);
	}

	// Status filter
	if ( isset($filter['is_read']) ) {
		$status = (int)(bool)$filter['is_read'];
		$select->push("AND p.is_read = '$status'");
	}

	// fetch total count
	list($r['total']) = mysql_fetch_row($select->getCount());
	// set order, offset and limit
	$select->orderby(adesk_privatemessage_sort($sort));
	$select->limit = "$offset, $limit";
	// fetch list
	$sql = $select->getList(true);
	$r['rows'] = array();
	if ( !$sql ) dbg(adesk_sql_error());
	$url = $site["p_link"] . ( defined('adesk_PUBLIC') ? '/index.php' : '/manage/desk.php' );
	while ( $row = adesk_sql_fetch_assoc($sql, array("cdate")) ) {
		//$row['user'] = ( $userInfo ? $userInfo : user_get($row['userid']) );
		$row['url'] = $url . "?action=privatemessage#form-" . $row['id'];
		$r['rows'][$row['id']] = $row;
	}
	$r['user'] = user_get($admin["id"]);
	$r['cnt'] = count($r['rows']);
	return $r;
}

function adesk_privatemessage_sort($sort = null) {
	if ( is_null($sort) )
		$sort = ( isset($_GET['comsort']) ? $_GET['comsort'] : ( isset($_SESSION['comsort']) ? $_SESSION['comsort'] : '' ) );
	if ( $sort == "01" ) {
		return "is_read ASC";
	} elseif ( $sort == "01D" ) {
		return "is_read DESC";
	} elseif ( $sort == "02" ) {
		return "cdate ASC";
	} elseif ( $sort == "02D" ) {
		return "cdate DESC";
	} elseif ( $sort == "03" ) {
		return "title ASC";
	} elseif ( $sort == "03D" ) {
		return "title DESC";
	} elseif ( $sort == "99" ) {
		return "score ASC";
	} elseif ( $sort == "99D" ) {
		return "score DESC";
	} else {
		return "cdate DESC";
	}
}

function adesk_privatemessage_select_query(&$so) {
	return $so->query("
		SELECT
			p.*,
			u1.absid AS 'user_from_moreinfo',
			u2.absid AS 'user_to_moreinfo'
		FROM
			#privmsg p,
			#user u1,
			#user u2
		WHERE
			[...]
			AND p.user_from = u1.id
			AND p.user_to = u2.id
	");
}

function adesk_privatemessage_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND p.id = '$id'");

	return adesk_sql_select_row(adesk_privatemessage_select_query($so));
}

function adesk_privatemessage_select_array($so = null, $ids = null) {
	$admin = adesk_admin_get();
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		$tmp = array_map("intval", explode(",", $ids));
		$ids = implode("','", $tmp);
		$so->push("AND p.id IN ('$ids')");
	}

	$results = adesk_sql_select_array(adesk_privatemessage_select_query($so));

	$privatemessages = array();

	foreach($results as $k => $v) {
		$privatemessages[$k] = $v;
		$privatemessages[$k]["user_from_moreinfo"] = adesk_auth_record_id(intval($v["user_from_moreinfo"]));
		$privatemessages[$k]["user_to_moreinfo"] = adesk_auth_record_id(intval($v["user_to_moreinfo"]));
	}
//dbg($privatemessages);
	return $privatemessages;
}

function adesk_privatemessage_select_array_paginator($id, $sort, $offset, $limit, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'privatemessage'");
		$so->push($conds);
	} else {
		$so->push("AND ( p.user_from = '$admin[id]' OR p.user_to = '$admin[id]' )");
	}

	$so->count();
	$total = (int)adesk_sql_select_one(adesk_privatemessage_select_query($so));

	$so->orderby(adesk_privatemessage_sort($sort));

	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = adesk_privatemessage_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function adesk_privatemessage_filter_post() {
	$whitelist = array("title", "content");

	$conds = array();
	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "privatemessage",
		"conds" => "",
		"=tstamp" => "NOW()",
	);

	if (isset($_POST["qsearch"]) && !isset($_POST["content"])) {
		$_POST["content"] = $_POST["qsearch"];
	}

	if (isset($_POST["content"]) and $_POST["content"] != "") {
		$content = adesk_sql_escape($_POST["content"]);

		if (!isset($_POST["section"]) || !is_array($_POST["section"]))
			$_POST["section"] = $whitelist;

		foreach ($_POST["section"] as $sect) {
			if (!in_array($sect, $whitelist))
				continue;
			$conds[] = "$sect LIKE '%$content%'";
		}

		$conds = implode(" OR ", $conds);
		$ary["conds"] = "AND ($conds)";
	}

	if (isset($_POST["privatemessage_filter"])) {
		$filtervalue = $_POST["privatemessage_filter"];

		if ($filtervalue == "all") {
			$ary["conds"] .= "AND (user_from = '$ary[userid]' OR user_to = '$ary[userid]') ";
		}
		else {

			$delete_field = ($filtervalue == "user_to") ? "delete_received" : "delete_sent";

			$ary["conds"] .= "AND (" . $filtervalue . " = " . $ary["userid"] . " AND " . $delete_field . " = 0)";
		}

		//$ary["conds"] .= "AND ($conds)";
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
			sectionid = 'privatemessage'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);

	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

function adesk_privatemessage_insert_post() {
	/*
	if (!permission("pg_privmsg_add"))
		return adesk_ajax_api_nopermission(_a("send Private Messages"));
	*/

	$user_to = adesk_auth_record_username(adesk_http_param("author_autocomplete"));

	if (isset($user_to['id'])) {

		$user_id = adesk_sql_select_one("
			SELECT
				id
			FROM
				#user
			WHERE
				absid = '$user_to[id]'
		");

		$ary = array(
			"user_from" => $GLOBALS['admin']['id'],
			"user_to" => $user_id,
			"threadid" => adesk_http_param("id"),
			"title" => adesk_http_param("title"),
			"content" => adesk_http_param("content"),
			"=cdate" => "NOW()",
		);

		$sql = adesk_sql_insert("#privmsg", $ary);
		if ( !$sql ) {
			return adesk_ajax_api_result(false, _a("Private Message could not be sent."));
		}
		$id = adesk_sql_insert_id();

		//return adesk_ajax_api_added(_a("Private Message"));
		return adesk_ajax_api_result(true, _a("Private Message sent"));
	}
	else {
		return adesk_ajax_api_result(false, _a("That username does not exist."));
	}

	/*
	$email_body = "A new comment has been added to " . $article['title'] . "\n\n";
	$email_body .= "Name = " . adesk_http_param("name") . "\n";
	$email_body .= "Email = " . adesk_http_param("email") . "\n\n";
	$email_body .= "Comment =\n\n" . $comments . "\n\n";
	$email_body .= "To manage this comment, visit:\n";
	$url = $site["p_link"] . ( defined('adesk_PUBLIC') ? '/index.php' : '/manage/desk.php' );
	$email_body .= $url . "?action=comment#form-" . $commentid;

	if ($site["comments_notify_address"] != "") {
		adesk_mail_send("text", $ary["name"], $ary["email"], $email_body, "New Comment For Article: " . $article['title'], $site["comments_notify_address"], "test");
	}
	*/
}

function adesk_privatemessage_update_post($id) {

	$ary = array(
		"is_read" => 1,
		"=rdate" => "NOW()",
	);

	//if (!isset($_SESSION["privatemessage_read_$id"])) {
		$sql = adesk_sql_update("#privmsg", $ary, "id = '$id'");

		$_SESSION["privatemessage_read_$id"] = true;

		if ( !$sql ) {
			return adesk_ajax_api_result(false, _a("Private Message could not be updated."));
		}

		return adesk_ajax_api_updated(_a("Private Message"));
	//}
	//else {
	//	return adesk_ajax_api_result(false, _a("Private Message already set to read."));
	//}
}

function adesk_privatemessage_delete($id) {
	$id = intval($id);
	adesk_sql_query("DELETE FROM #privmsg WHERE id = '$id'");
	return adesk_ajax_api_deleted(_a("Private Message"));
}

function adesk_privatemessage_delete_multi($ids, $filter = 0, $view) {
	if ( $ids == '_all' ) {
		$tmp = array();
		$so = new adesk_Select();
		$filter = intval($filter);
		if ($filter > 0) {
			$admin = adesk_admin_get();
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'privatemessage'");
			$so->push($conds);
		} else {
			$so->push("AND `completed` < `total`"); // active = DEFAULT
			$so->push("AND `ldate` IS NOT NULL"); // active, STALLED INCLUDED
			//$so->push("AND UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(`ldate`) < 4 * 60"); // active BUT NOT STALLED
		}
		$all = adesk_privatemessage_select_array($so);
		foreach ( $all as $v ) {
			$tmp[] = $v['id'];
		}
	} else {
		$tmp = array_map("intval", explode(",", $ids));
	}
	foreach ( $tmp as $id ) {

		// $view is the value of the Inbox/Sent select list
		$field = ($view == "user_to") ? "delete_received" : "delete_sent";

		$sql = adesk_sql_update_one("#privmsg", $field, 1, "id = '$id'");

		//$r = adesk_privatemessage_delete($id);
	}
	//return $r;

	return adesk_ajax_api_deleted(_a("Private Message(s)"));
}

?>
