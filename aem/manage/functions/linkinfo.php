<?php

function linkinfo_select_totals($campaignid, $linkid, $messageid) {
	$campaignid = intval($campaignid);
	$messageid  = intval($messageid);
	$table      = "#campaign c";
	$cond       = "c.id = '$campaignid'";

	if ($messageid > 0) {
		$table = "#campaign_message c";
		$cond  = "c.messageid = '$messageid' AND c.campaignid = '$campaignid'";
	}

	$linkid     = intval($linkid);
	$totals     = adesk_sql_select_row("
		SELECT
			(
				SELECT
					c.total_amt
				FROM
					$table
				WHERE
					$cond
			) AS a_total_amt,
			(
				SELECT
					SUM(ld.times)
				FROM
					#link_data ld
				WHERE
					ld.linkid = l.id
			) AS a_clicks,
			(
				SELECT
					COUNT(*)
				FROM
					#link_data ld
				WHERE
					ld.linkid = l.id
			) AS a_uniqueclicks,
			l.name,
			l.link
		FROM
			#link l
		WHERE
			l.id = '$linkid'
	");

	if ( !$totals ) {
		$totals = array(
			'a_total_amt' => 0,
			'a_clicks' => 0,
			'a_uniqueclicks' => 0,
		);
	}

	$totals["a_avg"] = 0;
	if ($totals["a_uniqueclicks"] > 0)
		$totals["a_avg"] = $totals["a_clicks"] / $totals["a_uniqueclicks"];

	return $totals;
}

require_once awebdesk_classes("select.php");

function linkinfo_select_query(&$so) {
	$linkid = intval(adesk_http_param("id"));

	if (!$so->counting) {
		$so->slist = array_merge(array(
			"s.email",
			"ld.subscriberid",
			"ld.tstamp",
			"ld.times",
		), $so->slist);
	}

	return $so->query("
		SELECT
			*
		FROM
			#subscriber s,
			#link_data ld
		WHERE
			ld.linkid = '$linkid'
		AND s.id = ld.subscriberid
	");
}

function linkinfo_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND b.id = '$id'");

	return adesk_sql_select_row(linkinfo_select_query($so));
}

function linkinfo_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND b.id IN ('$ids')");
	}

	return adesk_sql_select_array(linkinfo_select_query($so));
}

// used in api
function linkinfo_select_list($linkid) {
	$_GET["id"] = $linkid;
	$so = new adesk_Select;
	$linkinfo = linkinfo_select_array($so);
	return $linkinfo;
}

function linkinfo_select_array_paginator($id, $sort, $offset, $limit, $filter, $linkid = 0) {
	$_GET["id"] = $linkid;
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'link'");
		$so->push($conds);
	}

	//if ($messageid > 0)

	$so->count();
	$total = (int)adesk_sql_select_one(linkinfo_select_query($so));

	switch ($sort) {
		default:
		case '01':
			$so->orderby("s.email"); break;
		case '01D':
			$so->orderby("s.email DESC"); break;
		case '02':
			$so->orderby("ld.tstamp"); break;
		case '02D':
			$so->orderby("ld.tstamp DESC"); break;
		case '03':
			$so->orderby("ld.times"); break;
		case '03D':
			$so->orderby("ld.times DESC"); break;
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = linkinfo_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function linkinfo_filter_post() {
	$whitelist = array("s.email", "ld.tstamp");

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
