<?php

require_once awebdesk_classes("select.php");

function report_list_select_query(&$so, $listid = 0) {
	$admin = adesk_admin_get();
	$uid = 1;
	if ( $admin['id'] != 1 ) {
		$lists = implode("', '", $admin["lists"]);
		$so->push("AND l.id IN ('$lists')");
		$uid = $admin['id'];
	}
	if ( $listid = (int)$listid ) {
		$so->push("AND l.id = '$listid'");
	}
	if ( !isset($so->subqueryconds) ) $so->subqueryconds = '';
	if ( !isset($so->subqueryavg)   ) $so->subqueryavg = 'ROUND( ( UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(l.cdate) ) / 60 / 60 / 24 )';
	$subqueryconds_udate = str_replace('c.sdate', 'c.udate', $so->subqueryconds);
	$subqueryconds_tstamp = str_replace('c.sdate', 'c.tstamp', $so->subqueryconds);
	return $so->query("
		SELECT
			l.id,
			l.name,
			l.cdate,
			( SELECT COUNT(DISTINCT(c.subscriberid)) FROM #subscriber_list c WHERE c.listid = l.id AND c.status = 1 {$so->subqueryconds} ) AS subscribed,
			( SELECT COUNT(DISTINCT(c.subscriberid)) FROM #subscriber_list c WHERE c.listid = l.id AND c.status = 0 {$so->subqueryconds} ) AS unconfirmed,
			( SELECT COUNT(DISTINCT(c.subscriberid)) FROM #subscriber_list c WHERE c.listid = l.id AND c.status = 2 {$subqueryconds_udate} ) AS unsubscribed,
			( SELECT COUNT(DISTINCT(c.subscriberid)) FROM #bounce_data c WHERE c.listid = l.id {$subqueryconds_tstamp} ) AS bounced,
			( SELECT COUNT(cl.id) FROM #campaign_list cl, #campaign c WHERE cl.campaignid = c.id AND c.status != 0 AND cl.listid = l.id {$so->subqueryconds} ) AS campaigns,
			( SELECT IF(SUM(cl.list_amt) IS NULL, 0, SUM(cl.list_amt)) FROM #campaign_list cl, #campaign c WHERE cl.campaignid = c.id AND c.status != 0 AND cl.listid = l.id {$so->subqueryconds} ) AS emails,
			( SELECT SUM(cl.list_amt) / {$so->subqueryavg} FROM #campaign_list cl, #campaign c WHERE cl.campaignid = c.id AND c.status != 0 AND cl.listid = l.id {$so->subqueryconds} ) AS epd
		FROM
			#list l,
			#user_p p
		WHERE
		[...]
		AND
			p.userid = '$uid'
		AND
			p.listid = l.id
	");
}

function report_list_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND l.id = '$id'");

	return adesk_sql_select_row(report_list_select_query($so));
}

function report_list_select_array($so = null, $ids = null, $filter = 0) {
	// select object
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	// passed ids to filter to
	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND l.id IN ('$ids')");
	}

	// any filters used
	$filter = intval($filter);
	if ($filter > 0) {
		$so = select_filter_comment_parse($so, $filter, 'report_list');
	}

	// fetch the rows
	//dbg(adesk_prefix_replace(report_list_select_query($so)));
	$rows = adesk_sql_select_array(report_list_select_query($so));
	foreach ( $rows as $k => $v ) {
		if ( !$v['campaigns'] ) $rows[$k]['campaigns'] = 0;
		if ( !$v['emails'] ) $rows[$k]['emails'] = 0;
		if ( !$v['epd'] ) $rows[$k]['epd'] = 0;
	}
	return $rows;
}

function report_list_select_array_paginator($id, $sort, $offset, $limit, $filter, $listid = 0) {
	$so = new adesk_Select;
	$so->subqueryconds = '';

	$filter = intval($filter);
	if ($filter > 0) {
		$so = select_filter_comment_parse($so, $filter, 'report_list');
	}

	$so->count();
	$total = (int)adesk_sql_select_one(report_list_select_query($so, $listid));

	switch ($sort) {
		default:
		case "01":
			$so->orderby("name"); break;
		case "01D":
			$so->orderby("name DESC"); break;
		case "02":
			$so->orderby("subscribed"); break;
		case "02D":
			$so->orderby("subscribed DESC"); break;
		case "03":
			$so->orderby("unconfirmed"); break;
		case "03D":
			$so->orderby("unconfirmed DESC"); break;
		case "04":
			$so->orderby("unsubscribed"); break;
		case "04D":
			$so->orderby("unsubscribed DESC"); break;
		case "05":
			$so->orderby("bounced"); break;
		case "05D":
			$so->orderby("bounced DESC"); break;
		case "06":
			$so->orderby("campaigns"); break;
		case "06D":
			$so->orderby("campaigns DESC"); break;
		case "07":
			$so->orderby("emails"); break;
		case "07D":
			$so->orderby("emails DESC"); break;
		case "08":
			$so->orderby("epd"); break;
		case "08D":
			$so->orderby("epd DESC"); break;
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = report_list_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function report_list_filter_post() {
	$whitelist = array("l.name", "l.stringid", "l.analytics_source", "l.twitter_user", "l.to_name");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "report_list",
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
	/*
	if ( isset($_POST['listid']) ) {
		if ( defined('AWEBVIEW') ) {
			$_SESSION['nlp'] = $_POST['listid'];
		} else {
			$_SESSION['nla'] = $_POST['listid'];
		}
	}
	*/
	$nl = null;
	if ( isset($_SESSION['nlp']) and defined('AWEBVIEW') ) {
		$nl = $_SESSION['nlp'];
	} elseif ( isset($_SESSION['nla']) ) {
		$nl = $_SESSION['nla'];
	}
	if ( $nl ) {
		if ( is_array($nl) ) {
			if ( count($nl) > 0 ) {
				$ids = implode("', '", array_map('intval', $nl));
				$ary['conds'] .= "AND l.id IN ('$ids') ";
			} else {
				if ( defined('AWEBVIEW') ) {
					unset($_SESSION['nlp']);
				} else {
					unset($_SESSION['nla']);
				}
			}
		} else {
			$listid = (int)$nl;
			if ( $listid > 0 ) {
				$ary['conds'] .= "AND l.id = '$listid' ";
			} else {
				if ( defined('AWEBVIEW') ) {
					unset($_SESSION['nlp']);
				} else {
					unset($_SESSION['nla']);
				}
			}
		}
	}

	if ( isset($_POST['datetimefilter'])  ) {
		$_SESSION['report_list_datetime'] = $_POST['datetimefilter'];
	}
	if ( isset($_POST['from'])  ) {
		$_SESSION['report_list_datetimefrom'] = $_POST['from'];
	}
	if ( isset($_POST['to'])  ) {
		$_SESSION['report_list_datetimeto'] = $_POST['to'];
	}
	if ( isset($_SESSION['report_list_datetime']) and $_SESSION['report_list_datetime'] != 'all' ) {
		$comment = '';
		$from = '';
		$to = '';
		switch ( $_SESSION['report_list_datetime'] ) {
			case 'today':
				$comment = "AND DATE(c.sdate) = CURDATE()";
				break;
			case 'week':
				$from = date('Y-m-d', strtotime('last Monday'));
				$comment = "AND c.sdate >= '$from' AND c.sdate < DATE(NOW() + INTERVAL 1 DAY)";
				break;
			case 'month':
				$from = substr(adesk_CURRENTDATE, 0, -2) . '01';
				$comment = "AND c.sdate >= '$from' AND c.sdate < DATE(NOW() + INTERVAL 1 DAY)";
				break;
			case 'year':
				$from = substr(adesk_CURRENTDATE, 0, -5) . '01-01';
				$comment = "AND c.sdate >= '$from' AND c.sdate < DATE(NOW() + INTERVAL 1 DAY)";
				break;
			case 'range':
				if ( isset($_SESSION['report_list_datetimefrom']) and isset($_SESSION['report_list_datetimeto']) ) {
					// sanitize from
					$from = @strtotime($_SESSION['report_list_datetimefrom']);
					if ( $from == -1 ) $from = false;
					if ( $from ) $from = date('Y-m-d', $from);
					// sanitize to
					$to = @strtotime($_SESSION['report_list_datetimeto']);
					if ( $to == -1 ) $to = false;
					if ( $to ) $to = date('Y-m-d', $to);
					// make condition
					if ( $from and $to ) {
						$comment = "AND c.sdate >= '$from' AND c.sdate < ('$to' + INTERVAL 1 DAY)";
					} else { // reset to a 30 day interval?
						//$comment = "AND c.sdate >= DATE(CURDATE() - INTERVAL 30 DAY) AND c.sdate < (NOW() + INTERVAL 1 DAY)";
					}
				}
				break;
		}
		if ( $comment != '' ) {
			$ary['conds'] .= " /* datetime: " . base64_encode($comment) . " */ ";
			//$_SESSION['report_list_datetime'] = $_POST['datetimefilter'];
			if ( $from ) $_SESSION['report_list_datetimefrom'] = $from;
			if ( $to   ) $_SESSION['report_list_datetimeto'] = $to;
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
			sectionid = 'report_list'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}


?>
