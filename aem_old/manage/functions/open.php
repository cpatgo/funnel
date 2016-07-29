<?php

require_once awebdesk_classes("select.php");

function open_select_totals($campaignid, $messageid) {
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
			opens,
			uniqueopens,
			total_amt,
			total_amt - uniqueopens AS unopens
		FROM
			$table
		WHERE
			$cond
	");
}

function open_select_query(&$so, $campaignid = 0) {
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		if ( $admin['id'] != 1 ) {
			if ( !isset($so->permsAdded) ) {
				$so->permsAdded = 1;
				$so->push("AND l.campaignid IN (SELECT campaignid FROM #campaign_list WHERE listid IN ('" . implode("', '", $admin['lists']) . "'))");
			}
		}
	}

	if ($campaignid > 0)
		$so->push("AND l.campaignid = '$campaignid'");

	if (!$so->counting) {
		$so->slist = array_merge(array(
			"ld.subscriberid",
			"ld.email",
			"ld.tstamp",
			"ld.times",
		), $so->slist);
	}

	return $so->query("
		SELECT
			*
		FROM
			#link l,
			#link_data ld
		WHERE
			[...]
		AND l.id = ld.linkid
		AND l.link = 'open'
		AND l.tracked = 1
	");
}

function open_select_array($so = null, $ids = null, $campaignid = 0) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		$tmp = array_map("intval", explode(",", $ids));
		$ids = implode("','", $tmp);
		$so->push("AND l.id IN ('$ids')");
	}
	return adesk_sql_select_array(open_select_query($so, $campaignid), array('tstamp'));
}

// api
function open_select_list($campaignid, $messageid = 0) {
	$so = new adesk_Select;
	$so->push("AND l.messageid = '$messageid'");
	return open_select_array($so, null, $campaignid);
}

function open_select_array_paginator($id, $sort, $offset, $limit, $filter, $campaignid = 0, $messageid = 0) {
	$messageid = intval($messageid);
	$admin     = adesk_admin_get();
	$so        = new adesk_Select;
	$oldconds  = "";

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'open'");
		$so->push($conds);
	}

	$oldconds = $so->conds;
	if ($messageid > 0) {
		$so->push("AND l.messageid = '$messageid'");
	} else {
		$so->push("AND l.messageid = '0'");
	}

	$so->count();
	$total = adesk_sql_select_one(open_select_query($so, $campaignid));

	if ($total == 0) {
		$so->conds = $oldconds;
		$so->count();
		$total = adesk_sql_select_one(open_select_query($so, $campaignid));
	}

	switch ($sort) {
		default:
		case "01":
			$so->orderby("ld.email"); break;
		case "01D":
			$so->orderby("ld.email DESC"); break;
		case "02":
			$so->orderby("tstamp"); break;
		case "02D":
			$so->orderby("tstamp DESC"); break;
		case '03':
			$so->orderby("ld.times"); break;
		case '03D':
			$so->orderby("ld.times DESC"); break;
	}

	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = open_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function open_filter_post() {
	$whitelist = array("ld.email", "ld.tstamp");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "open",
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
			sectionid = 'open'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

?>
