<?php

require_once awebdesk_classes("select.php");

function report_trend_client_select_query(&$so) {
	/*
	if ( $listid = (int)$listid ) {
		$cond = "AND l.listid = '$listid'";
		if ( !in_array($cond, $so->conds) ) $so->push($cond);
	}
	*/
	$admin = adesk_admin_get();
	$uid = 1;
	if ( $admin['id'] != 1 ) {
		$lists = implode("', '", $admin["lists"]);
		$so->push("AND l.id IN ('$lists')");
		$uid = $admin['id'];
	}
	if ( !isset($so->subqueryconds) ) $so->subqueryconds = '';
	$subqueryconds = str_replace('d.tstamp', 'xd.tstamp', $so->subqueryconds);
	$query = "
		SELECT
			l.id,
			l.name,
			l.cdate AS tstamp,
#			0 AS total_amt,
#			0 AS opens,
			( SELECT COUNT(xd.id) FROM #link_data xd, #link xl, #campaign_list xc WHERE xl.link = 'open' AND xl.tracked = 1 AND xl.messageid = 0 AND ( xd.ua != '' OR xd.uasrc != '' ) AND xl.campaignid = xc.campaignid AND xc.listid = l.id AND xl.id = xd.linkid $subqueryconds ) AS uniqueopens,
			NULL AS bestclient,
			NULL AS bestclientua,
			NULL AS bestclienthits
		FROM
			#list l,
			#user_p p
		WHERE
		[...]
		AND
			p.userid = '$uid'
		AND
			p.listid = l.id
	";
	return $so->query($query);
}
/*
function report_trend_client_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND l.id = '$id'");

	return adesk_sql_select_row(report_trend_client_select_query($so));
}
*/
function report_trend_client_select_array($so = null, $filter = 0) {
	// select object
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	// any filters used
	$filter = intval($filter);
	if ($filter > 0) {
		$so = select_filter_comment_parse($so, $filter, 'report_trend_client');
	}

	// fetch the rows
	//dbg(adesk_prefix_replace(report_trend_client_select_query($so)));
	$rows = adesk_sql_select_array(report_trend_client_select_query($so));
	foreach ( $rows as $k => $v ) {
		// calculate best client
		// per list
		$found = adesk_sql_select_row("
			SELECT
				d.ua AS `bestclient`,
				d.uasrc AS `bestclientua`,
				COUNT(*) AS `bestclienthits`
			FROM
				#campaign_list c,
				#link l,
				#link_data d
			WHERE
				c.listid = '$v[id]'
			AND
				l.messageid = 0
			AND
				l.link = 'open'
			AND
				l.tracked = 1
			AND
				( d.ua != '' OR d.uasrc != '' )
			AND
				c.campaignid = l.campaignid
			AND
				l.id = d.linkid
			{$so->subqueryconds}
			GROUP BY
				d.ua #, d.uasrc
			ORDER BY
				bestclienthits DESC
			LIMIT 0, 1
		");
		if ( $found ) {
			$rows[$k]['bestclient'] = $found['bestclient'];
			$rows[$k]['bestclientua'] = $found['bestclientua'];
			$rows[$k]['bestclienthits'] = (int)$found['bestclienthits'];
		}
	}
	return $rows;
}

function report_trend_client_select_array_paginator($id, $sort, $offset, $limit, $filter) {
	$so = new adesk_Select;
	$so->subqueryconds = '';

	$filter = intval($filter);
	if ($filter > 0) {
		$so = select_filter_comment_parse($so, $filter, 'report_trend_client');
	}

	$so->count();
	$total = (int)adesk_sql_select_one(report_trend_client_select_query($so));

	switch ($sort) {
		default:
		case "01":
			$so->orderby("name"); break;
		case "01D":
			$so->orderby("name DESC"); break;
		case "02":
			$so->orderby("tstamp"); break;
		case "02D":
			$so->orderby("tstamp DESC"); break;
		case "03":
			$so->orderby("bestclient"); break;
		case "03D":
			$so->orderby("bestclient DESC"); break;
		case "07":
			$so->orderby("uniqueopens"); break;
		case "07D":
			$so->orderby("uniqueopens DESC"); break;
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = report_trend_client_select_array($so);//dbg($rows);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function report_trend_client_filter_post() {
	$whitelist = array("l.name", "l.stringid", "l.analytics_source", "l.twitter_user", "l.to_name");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "report_trend_client",
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
		$_SESSION['report_trend_client_datetime'] = $_POST['datetimefilter'];
	}
	if ( isset($_POST['from'])  ) {
		$_SESSION['report_trend_client_datetimeto'] = $_POST['from'];
	}
	if ( isset($_POST['to'])  ) {
		$_SESSION['report_trend_client_datetimeto'] = $_POST['to'];
	}
	if ( isset($_SESSION['report_trend_client_datetime']) and $_SESSION['report_trend_client_datetime'] != 'all' ) {
		$comment = '';
		$from = '';
		$to = '';
		switch ( $_SESSION['report_trend_client_datetime'] ) {
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
				if ( isset($_SESSION['report_trend_client_datetimefrom']) and isset($_SESSION['report_trend_client_datetimeto']) ) {
					// sanitize from
					$from = @strtotime($_SESSION['report_trend_client_datetimefrom']);
					if ( $from == -1 ) $from = false;
					if ( $from ) $from = date('Y-m-d', $from);
					// sanitize to
					$to = @strtotime($_SESSION['report_trend_client_datetimeto']);
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
			$ary['conds'] .= " /* datetime: " . base64_encode($comment) . " */ ";
			//$_SESSION['report_trend_client_datetime'] = $_POST['datetimefilter'];
			if ( $from ) $_SESSION['report_trend_client_datetimefrom'] = $from;
			if ( $to   ) $_SESSION['report_trend_client_datetimeto'] = $to;
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
			sectionid = 'report_trend_client'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

?>
