<?php

require_once awebdesk_classes("select.php");

function feedbackloop_select_query(&$so) {
	return $so->query("
		SELECT
			f.*,
			c.name,
			s.email
		FROM
			#feedbackloop f,
			#campaign c,
			#subscriber s
		WHERE
		[...]
		AND
			c.id = f.campaignid
		AND
			s.id = f.subscriberid
	");
}

function feedbackloop_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND f.id = '$id'");

	return adesk_sql_select_row(feedbackloop_select_query($so));
}

function feedbackloop_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map('intval', $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND f.id IN ('$ids')");
	}
	return adesk_sql_select_array(feedbackloop_select_query($so));
}

function feedbackloop_select_array_paginator($id, $sort, $offset, $limit, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'feedbackloop'");
		$so->push($conds);
	}

	$so->count();
	$total = (int)adesk_sql_select_one(feedbackloop_select_query($so));

	switch ($sort) {
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$so->limit("$offset, $limit");
	$rows = feedbackloop_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function feedbackloop_filter_post() {
	$whitelist = array("body", "tstamp");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "feedbackloop",
		"conds" => "",
		"=tstamp" => "NOW()",
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
			sectionid = 'feedbackloop'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

function feedbackloop_delete($id) {
	$id = intval($id);
	adesk_sql_query("DELETE FROM #feedbackloop WHERE id = '$id'");
	return adesk_ajax_api_deleted(_a("Feedback Loop Report"));
}

function feedbackloop_delete_multi($ids, $filter = 0) {
	if ( $ids == '_all' ) {
		$tmp = array();
		$so = new adesk_Select();
		$filter = intval($filter);
		if ($filter > 0) {
			$admin = adesk_admin_get();
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'feedbackloop'");
			$so->push($conds);
		}
		$all = feedbackloop_select_array($so);
		foreach ( $all as $v ) {
			$tmp[] = $v['id'];
		}
	} else {
		$tmp = array_map("intval", explode(",", $ids));
	}
	foreach ( $tmp as $id ) {
		$r = feedbackloop_delete($id);
	}
	return $r;
}

?>
