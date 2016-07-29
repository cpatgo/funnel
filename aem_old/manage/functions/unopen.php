<?php

require_once awebdesk_classes("select.php");
require_once adesk_admin("functions/campaign.php");

function unopen_select_query(&$so, $messageid = 0) {
	# Unopened emails are not stored as such in AEM, making a report of them somewhat of a
	# challenge.

	$id      = intval(adesk_http_param("id"));
	$campaign = campaign_select_row($id);
	$sdate   = adesk_sql_escape($campaign["sdate"]);
	$lists   = adesk_array_extract($campaign["lists"], "id");

	# If there's no list at all that we have in common with this campaign, kick us out.
	$admin = adesk_admin_get();
	
	
	
	if (count(array_intersect($lists, $admin["lists"])) == 0)
		return false;

	$liststr = implode("','", $lists);
	$linkid  = adesk_sql_select_one("SELECT id FROM #link WHERE link = 'open' AND campaignid = '$id' AND messageid = '$messageid'");
	$so->greedy = false;

	if ($campaign["filterid"] > 0) {
		$conds = "AND " . filter_compile($campaign["filterid"]);
		$so->push($conds);
	}

	if (!$so->counting) {
		$so->slist = array_merge(array(
			"s.email",
			"l.subscriberid",
			"'$sdate' AS tstamp",
		), $so->slist);
	}

	return $so->query("
		SELECT
			*
		FROM
			#subscriber s,
			#log l
		WHERE
			[...]
		AND
			s.id = l.subscriberid
		AND
			l.campaignid = '$id'
		AND
			(SELECT COUNT(*) FROM #link_data subld WHERE subld.linkid = '$linkid' AND subld.subscriberid = s.id) = 0
	");
}

function unopen_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND s.id IN ('$ids')");
	}
	return adesk_sql_select_array(unopen_select_query($so), array('tstamp'));
}

function unopen_select_array_paginator($id, $sort, $offset, $limit, $filter, $campaignid) {
	$_GET["id"] = $campaignid;
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'open'");
		$so->push($conds);
	}

	$so->count();
	$total = adesk_sql_select_one(unopen_select_query($so));

	switch ($sort) {
		default:
		case "01":
			$so->orderby("s.email"); break;
		case "01D":
			$so->orderby("s.email DESC"); break;
		case "02":
			$so->orderby("tstamp"); break;
		case "02D":
			$so->orderby("tstamp DESC"); break;
	}

	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = unopen_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function unopen_filter_post() {
	$whitelist = array("s.email");

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
