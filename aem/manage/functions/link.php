<?php

function link_selectdropdown_bycampaign($campaignid) {
	$campaignid = intval($campaignid);

	return adesk_sql_select_array("
		SELECT
			id,
			IF(name IS NULL OR name = '', link, name) AS link
		FROM
			#link
		WHERE
			campaignid = '$campaignid'
		AND link != 'open'
		AND tracked = 1
	");
}

function link_didntclick($campaignid) {
	$campaignid = intval($campaignid);
	return adesk_sql_select_one("
		SELECT
			total_amt - (
				SELECT
					COUNT(DISTINCT subscriberid)
				FROM
					#link_data
				WHERE
					linkid IN (SELECT l.id FROM #link l WHERE l.campaignid = '$campaignid' AND l.link != 'open')
			) AS didntclick
		FROM
			#campaign
		WHERE
			id = '$campaignid'
	");
}

function link_select_totals($campaignid, $messageid) {
	$campaignid = intval($campaignid);
	$messageid  = intval($messageid);
	$table      = "#campaign";
	$cond       = "id = '$campaignid'";

	if ($messageid > 0) {
		$table = "#campaign_message";
		$cond  = "messageid = '$messageid' AND campaignid = '$campaignid'";
	}

	$row = adesk_sql_select_row("
		SELECT
			total_amt,
			linkclicks,
			uniquelinkclicks,
			IF(subscriberclicks > 0, linkclicks / subscriberclicks, 0) AS avgclicks
		FROM
			$table
		WHERE
			$cond
	");
	$row["didntclick"] = link_didntclick($campaignid);
	return $row;
}

require_once awebdesk_classes("select.php");

function link_select_query(&$so, $campaignid = 0) {
	if (!$campaignid) {
		$campaignid = intval(adesk_http_param("id"));
	}

	if ($campaignid)
		$so->push("AND l.campaignid = '$campaignid'");

	return $so->query("
		SELECT
			l.id,
			l.name,
			l.link,
			(
				SELECT
					COUNT(*)
				FROM
					#link_data sub1ld
				WHERE
					sub1ld.linkid = l.id
			) AS a_unique,
			(
				SELECT
					IFNULL(SUM(times), 0)
				FROM
					#link_data sub2ld
				WHERE
					sub2ld.linkid = l.id
			) AS a_total
		FROM
			#link l
		WHERE
			[...]
		AND l.link != 'open'
		AND l.tracked = 1
	");
}

function link_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND b.id = '$id'");

	return adesk_sql_select_row(link_select_query($so));
}

function link_select_array($so = null, $ids = null, $campaignid = 0) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND b.id IN ('$ids')");
	}

	return adesk_sql_select_array(link_select_query($so, $campaignid));
}

// api
function link_select_list($campaignid, $messageid = 0) {
	require_once adesk_admin("functions/linkinfo.php");
	$so = new adesk_Select;
	$so->push("AND l.messageid = '$messageid'");
	$links = link_select_array($so, null, $campaignid);
	foreach ($links as $k => $v) {
		$links[$k]["info"] = linkinfo_select_list($v["id"]);
	}
	return $links;
}

function link_select_array_paginator($id, $sort, $offset, $limit, $filter, $campaignid = 0, $messageid = 0) {
	$_GET["id"] = $campaignid;
	$messageid  = intval($messageid);
	$admin      = adesk_admin_get();
	$so         = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'link'");
		$so->push($conds);
	}

	if ($messageid > 0) {
		$so->push("AND l.messageid = '$messageid'");
	}

	$so->count();
	$total = (int)adesk_sql_select_one(link_select_query($so));

	switch ($sort) {
		default:
		case '01':
			$so->orderby("l.link"); break;
		case '01D':
			$so->orderby("l.link DESC"); break;
		case '02':
			$so->orderby("a_unique"); break;
		case '02D':
			$so->orderby("a_unique DESC"); break;
		case '03':
			$so->orderby("a_total"); break;
		case '03D':
			$so->orderby("a_total DESC"); break;
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = link_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function link_filter_post() {
	$whitelist = array("l.link");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "link",
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
			sectionid = 'link'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

?>
