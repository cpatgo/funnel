<?php

require_once awebdesk_classes("select.php");

function bounce_data_select_totals($campaignid, $messageid) {
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
			softbounces,
			hardbounces,
			softbounces + hardbounces AS totalbounces
		FROM
			$table
		WHERE
			$cond
	");
}

function bounce_data_select_query(&$so, $campaignid = 0) {
	if ($campaignid > 0)
		$so->push("AND b.campaignid = '$campaignid'");

	if (!$so->counting) {
		$so->slist = array_merge(array(
			"b.*",
			"c.descript",
		), $so->slist);
	}

	return $so->query("
		SELECT
			b.*,
			c.descript
		FROM
			#bounce_data b,
			#bounce_code c
		WHERE
			[...]
		AND
			b.type = c.type
		AND
			b.code = c.code
	");
}

function bounce_data_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND b.id = '$id'");

	return adesk_sql_select_row(bounce_data_select_query($so));
}

function bounce_data_select_array($so = null, $ids = null, $campaignid = 0) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND b.id IN ('$ids')");
	}

	return adesk_sql_select_array(bounce_data_select_query($so, $campaignid));
}

// api
function bounce_data_select_list($campaignid, $messageid = 0) {
	$so = new adesk_Select;
	if ($messageid > 0) {
		$so->push("AND b.messageid = '$messageid'");
	}
	return bounce_data_select_array($so, null, $campaignid);
}

function bounce_data_select_array_paginator($id, $sort, $offset, $limit, $filter, $campaignid = 0, $messageid = 0) {
	$messageid = intval($messageid);
	$admin     = adesk_admin_get();
	$so        = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'bounce_data'");
		$so->push($conds);
	}

	if ($messageid > 0) {
		$so->push("AND b.messageid = '$messageid'");
	}

	$so->count();
	$total = (int)adesk_sql_select_one(bounce_data_select_query($so, $campaignid));

	switch ($sort) {
		default:
		case '01':
			$so->orderby("b.email"); break;
		case '01D':
			$so->orderby("b.email DESC"); break;
		case '02':
			$so->orderby("b.tstamp"); break;
		case '02D':
			$so->orderby("b.tstamp DESC"); break;
		case '03':
			$so->orderby("b.code"); break;
		case '03D':
			$so->orderby("b.code DESC"); break;
		case '04':
			$so->orderby("b.type"); break;
		case '04D':
			$so->orderby("b.type DESC"); break;
		case '05':
			$so->orderby("c.descript"); break;
		case '05D':
			$so->orderby("c.descript DESC"); break;
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = bounce_data_select_array($so, null, $campaignid);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function bounce_data_filter_post() {
	$whitelist = array("code", "email", "tstamp");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "bounce_data",
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
			if ( $sect == 'code' ) $sect = 'b.code';
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
			sectionid = 'bounce_data'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

?>
