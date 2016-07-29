<?php

require_once awebdesk_classes("select.php");

function report_trend_client_list_select_query(&$so, $listid = 0) {
	$admin = adesk_admin_get();
	$uid = 1;
	if ( $admin['id'] != 1 ) {
		$lists = implode("', '", $admin["lists"]);
		$so->push("AND cl.listid IN ('$lists')");
		$uid = $admin['id'];
	}
	if ( !isset($so->subqueryconds) ) $so->subqueryconds = '';
	$cnt = 1;
	$query = "
		SELECT
			d.ua AS `name`,
			d.uasrc AS `ua`,
			COUNT(d.id) AS `hits`,
			100 * COUNT(d.id) / ( SELECT SUM(subc.uniqueopens) AS `cnt` FROM #campaign subc, #campaign_list subl WHERE subc.id = subl.campaignid AND subl.listid = '$listid' {$so->subqueryconds} ) AS `perc`,
			( SELECT SUM(subc.uniqueopens) AS `cnt` FROM #campaign subc, #campaign_list subl WHERE subc.id = subl.campaignid AND subl.listid = '$listid' {$so->subqueryconds} ) AS `cnt`
		FROM
#			#campaign c,
			#campaign_list cl,
			#link l,
			#link_data d
		WHERE
		[...]
		AND
			cl.listid = '$listid'
		AND
			l.messageid = 0
		AND
			l.link = 'open'
		AND
			l.tracked = 1
		AND
			( d.ua != '' OR d.uasrc != '' )
		AND
			cl.campaignid = l.campaignid
#		AND
#			cl.campaignid = c.id
		AND
			l.id = d.linkid
		GROUP BY
			d.ua #, d.uasrc
	";
	$query_without_subquery = "
		SELECT
			d.ua AS `name`,
			d.uasrc AS `ua`,
			COUNT(d.id) AS `hits`,
			COUNT(d.id) / SUM(c.uniqueopens) AS `perc`,
			SUM(c.uniqueopens) AS `cnt`
		FROM
			#campaign c,
			#campaign_list cl,
			#link l,
			#link_data d
		WHERE
		[...]
		AND
			cl.listid = '$listid'
		AND
			l.messageid = 0
		AND
			l.link = 'open'
		AND
			l.tracked = 1
		AND
			( d.ua != '' OR d.uasrc != '' )
		AND
			cl.campaignid = l.campaignid
		AND
			cl.campaignid = c.id
		AND
			l.id = d.linkid
		GROUP BY
			d.ua #, d.uasrc
	";
	return $so->query($query);
}
/*
function report_trend_client_list_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND l.id = '$id'");
	$so->push("AND c.id = '$id'");

	return adesk_sql_select_row(report_trend_client_list_select_query($so));
}
*/
function report_trend_client_list_select_array($so = null, $listid = 0, $filter = 0) {
	// select object
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	$listid = (int)$listid;

	// any filters used
	$filter = intval($filter);
	if ($filter > 0) {
		$so = select_filter_comment_parse($so, $filter, 'report_trend_client_list');
	}

	// fetch the rows
	//dbg(adesk_prefix_replace(report_trend_client_list_select_query($so, $listid)));
	$rows = adesk_sql_select_array(report_trend_client_list_select_query($so, $listid));
	/*
	foreach ( $rows as $k => $v ) {
	}
	*/
	return $rows;
}

function report_trend_client_list_select_array_paginator($id, $sort, $offset, $limit, $filter, $listid = 0) {
	$so = new adesk_Select;
	$so->subqueryconds = '';

	$filter = intval($filter);
	if ($filter > 0) {
		$so = select_filter_comment_parse($so, $filter, 'report_trend_client_list');
	}

	$so->count();
	$total = (int)adesk_sql_select_one(report_trend_client_list_select_query($so, $listid));

	switch ($sort) {
		default:
		case "01":
			$so->orderby("name"); break;
		case "01D":
			$so->orderby("name DESC"); break;
		case "02":
			$so->orderby("hits"); break;
		case "02D":
			$so->orderby("hits DESC"); break;
		case "03":
			$so->orderby("perc"); break;
		case "03D":
			$so->orderby("perc DESC"); break;
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = report_trend_client_list_select_array($so, $listid);//dbg($rows);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function report_trend_client_list_filter_post() {
	$whitelist = array("d.ua", "d.uasrc");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "report_trend_client_list",
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

	if ( isset($_POST['datetimefilter'])  ) {
		$_SESSION['report_trend_client_list_datetime'] = $_POST['datetimefilter'];
	}
	if ( isset($_POST['from'])  ) {
		$_SESSION['report_trend_client_list_datetimeto'] = $_POST['from'];
	}
	if ( isset($_POST['to'])  ) {
		$_SESSION['report_trend_client_list_datetimeto'] = $_POST['to'];
	}
	if ( isset($_SESSION['report_trend_client_list_datetime']) and $_SESSION['report_trend_client_list_datetime'] != 'all' ) {
		$comment = '';
		$from = '';
		$to = '';
		switch ( $_SESSION['report_trend_client_list_datetime'] ) {
			case 'today':
				$comment = "AND DATE(d.tstamp) = CURDATE()";
				break;
			case 'week':
				$from = date('Y-m-d', strtotime('last Monday'));
				$comment = "AND d.tstamp >= '$from' AND d.tstamp < DATE(NOW() + INTERVAL 1 DAY)";
				break;
			case 'month':
				$from = substr(adesk_CURRENTDATE, 0, -2) . '01';
				$comment = "AND d.tstamp >= '$from' AND d.tstamp < DATE(NOW() + INTERVAL 1 DAY)";
				break;
			case 'year':
				$from = substr(adesk_CURRENTDATE, 0, -5) . '01-01';
				$comment = "AND d.tstamp >= '$from' AND d.tstamp < DATE(NOW() + INTERVAL 1 DAY)";
				break;
			case 'range':
				if ( isset($_SESSION['report_trend_client_list_datetimefrom']) and isset($_SESSION['report_trend_client_list_datetimeto']) ) {
					// sanitize from
					$from = @strtotime($_SESSION['report_trend_client_list_datetimefrom']);
					if ( $from == -1 ) $from = false;
					if ( $from ) $from = date('Y-m-d', $from);
					// sanitize to
					$to = @strtotime($_SESSION['report_trend_client_list_datetimeto']);
					if ( $to == -1 ) $to = false;
					if ( $to ) $to = date('Y-m-d', $to);
					// make condition
					if ( $from and $to ) {
						$comment = "AND d.tstamp >= '$from' AND d.tstamp < ('$to' + INTERVAL 1 DAY)";
					} else { // reset to a 30 day interval?
						//$comment = "AND d.tstamp >= DATE(CURDATE() - INTERVAL 30 DAY) AND d.tstamp < (NOW() + INTERVAL 1 DAY)";
					}
				}
				break;
		}
		if ( $comment != '' ) {
			$ary['conds'] .= $comment;
			$ary['conds'] .= " /* datetime: " . base64_encode(str_replace('d.tstamp', 'subc.sdate', $comment)) . " */ ";
			//$_SESSION['report_trend_client_list_datetime'] = $_POST['datetimefilter'];
			if ( $from ) $_SESSION['report_trend_client_list_datetimefrom'] = $from;
			if ( $to   ) $_SESSION['report_trend_client_list_datetimeto'] = $to;
		}
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
			sectionid = 'report_trend_client_list'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

?>