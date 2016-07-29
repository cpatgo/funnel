<?php

require_once awebdesk_classes("select.php");

function recipient_select_query(&$so, $id) {
	$sid = (int)$id;
	//$sid = (int)adesk_sql_select_one("id", "#campaign_count", "campaignid = '$id' ORDER BY id DESC");
	return $so->query("
		SELECT * FROM #x$sid x WHERE [...]
	");
}

function recipient_select_row($id = 0, $sendid = 0) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND x.id = '$id'");

	return adesk_sql_select_row(recipient_select_query($so, $sendid));
}

function recipient_select_array($so = null, $ids = null, $sendid = 0) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map('intval', $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND x.id IN ('$ids')");
	}
	return adesk_sql_select_array(recipient_select_query($so, $sendid));
}

function recipient_select_array_paginator($id, $sort, $offset, $limit, $filter, $sendid = 0) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'recipient'");
		$so->push($conds);
	}

	$so->count();
	$total = (int)adesk_sql_select_one(recipient_select_query($so, $sendid));

	switch ($sort) {
		default:
		case "01":
			$so->orderby("email"); break;
		case "01D":
			$so->orderby("email DESC"); break;
		case "02":
			$so->orderby("name"); break;
		case "02D":
			$so->orderby("name DESC"); break;
		case "03":
			$so->orderby("sdate"); break;
		case "03D":
			$so->orderby("sdate DESC"); break;
		case "04":
			$so->orderby("ip"); break;
		case "04D":
			$so->orderby("ip DESC"); break;
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$so->limit("$offset, $limit");
	$rows = recipient_select_array($so, null, $sendid);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function recipient_filter_post() {
	$whitelist = array("email", "name", "sdate");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "recipient",
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
			sectionid = 'recipient'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

?>
