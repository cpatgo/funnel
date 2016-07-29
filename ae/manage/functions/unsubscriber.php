<?php

require_once awebdesk_classes("select.php");

function unsubscriber_select_totals($campaignid, $messageid) {
	$campaignid = intval($campaignid);
	$messageid  = intval($messageid);
	$table      = "#campaign";
	$cond       = "id = '$campaignid'";

	if ($messageid > 0) {
		$table = "#campaign_message";
		$cond  = "messageid = '$messageid' AND campaignid = '$campaignid'";
	}

	return adesk_sql_select_row("
		SELECT
			total_amt,
			unsubscribes,
			unsubreasons
		FROM
			$table
		WHERE
			$cond
	");
}

function unsubscriber_select_query(&$so, $campaignid = 0) {
	$campaignid = intval($campaignid);

	if (!$so->counting) {
		$so->slist = array_merge(array(
			"s.email",
			"l.subscriberid",
			"l.udate",
			"l.unsubreason",
		), $so->slist);
	}

	return $so->query("
		SELECT
			*
		FROM
			#subscriber s,
			#subscriber_list l
		WHERE
			[...]
		AND	s.id = l.subscriberid
		AND l.status = 2
		AND l.unsubcampaignid = '$campaignid'
	");
}

function unsubscriber_select_array($so = null, $ids = null, $campaignid = 0) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND s.id IN ('$ids')");
	}
	return adesk_sql_select_array(unsubscriber_select_query($so, $campaignid), array('tstamp'));
}

// api
function unsubscriber_select_list($campaignid, $messageid = 0) {
	$so = new adesk_Select;
	if ($messageid > 0) {
		$so->push("AND l.unsubmessageid = '$messageid'");
	}
	return unsubscriber_select_array($so, null, $campaignid);
}

function unsubscriber_select_array_paginator($id, $sort, $offset, $limit, $filter, $campaignid, $messageid = 0) {
	$messageid = intval($messageid);
	$admin     = adesk_admin_get();
	$so        = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'unsubscriber'");
		$so->push($conds);
	}

	if ($messageid > 0) {
		$so->push("AND l.unsubmessageid = '$messageid'");
	}

	$so->count();
	$total = adesk_sql_select_one(unsubscriber_select_query($so, $campaignid));

	switch ($sort) {
		default:
		case "01":
			$so->orderby("s.email"); break;
		case "01D":
			$so->orderby("s.email DESC"); break;
		case "99":
			$so->orderby("udate"); break;
		case "99D":
			$so->orderby("udate DESC"); break;
	}

	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = unsubscriber_select_array($so, null, $campaignid);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function unsubscriber_filter_post() {
	$whitelist = array("s.email", "l.udate", "l.unsubreason");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "unsubscriber",
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
			if (!in_array($sect, $whitelist)) {
				continue;
			}
			$conds[] = "$sect LIKE '%$content%'";
		}

		$conds = implode(" OR ", $conds);
		$ary["conds"] = "AND ($conds) ";
	}

	if ( $ary['conds'] == '' ) return array("filterid" => 0);

	$conds_esc = adesk_sql_escape($ary['conds']);
	$filterid = adesk_sql_select_one("
		SELECT
			id
		FROM
			#section_filter
		WHERE
			userid = '$ary[userid]'
		AND
			sectionid = 'unsubscriber'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

?>
