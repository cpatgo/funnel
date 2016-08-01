<?php

require_once awebdesk_classes("select.php");

function update_select_totals($campaignid, $messageid) {
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
			updates,
			(total_amt - updates) AS didntupdate
		FROM
			$table
		WHERE
			$cond
	");
}

function update_select_query(&$so, $campaignid = 0) {
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		if ( $admin['id'] != 1 ) {
			if ( !isset($so->permsAdded) ) {
				$so->permsAdded = 1;
				$so->push("AND u.campaignid IN (SELECT cl.campaignid FROM #campaign_list cl WHERE cl.listid IN ('" . implode("', '", $admin['lists']) . "'))");
			}
		}
	}

	if ($campaignid > 0)
		$so->push("AND u.campaignid = '$campaignid'");

	if (!$so->counting) {
		$so->slist = array_merge(array(
			"(SELECT s.email FROM #subscriber s WHERE s.id = u.subscriberid) AS a_email",
			"u.subscriberid",
			"u.messageid",
			"u.tstamp",
		), $so->slist);
	}

	return $so->query("
		SELECT
			*
		FROM
			#update u
		WHERE
			[...]
	");
}

function update_select_array($so = null, $ids = null, $campaignid = 0) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND f.id IN ('$ids')");
	}
	return adesk_sql_select_array(update_select_query($so, $campaignid), array('tstamp'));
}

function update_select_array_paginator($id, $sort, $offset, $limit, $filter, $campaignid = 0, $messageid = 0) {
	$messageid = intval($messageid);
	$admin     = adesk_admin_get();
	$so        = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'update'");
		$so->push($conds);
	}

	if ($messageid > 0) {
		$so->push("AND f.messageid = '$messageid'");
	}

	$so->count('DISTINCT(subscriberid)');
	$total = adesk_sql_select_one(update_select_query($so, $campaignid));

	switch ($sort) {
		default:
		case "01":
			$so->orderby("a_email"); break;
		case "01D":
			$so->orderby("a_email DESC"); break;
		case "02":
			$so->orderby("tstamp"); break;
		case "02D":
			$so->orderby("tstamp DESC"); break;
	}

	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = update_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function update_filter_post() {
	$whitelist = array("(SELECT s.email FROM #subscriber s WHERE s.id = u.subscriberid)", "u.tstamp");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "update",
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
			sectionid = 'update'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

?>
