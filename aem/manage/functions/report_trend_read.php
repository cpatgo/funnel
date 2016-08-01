<?php

require_once awebdesk_classes("select.php");

function report_trend_read_select_query(&$so, $listid = 0) {
	/*
	if ( $listid = (int)$listid ) {
		$cond = "AND p.listid = '$listid'";
		if ( !in_array($cond, $so->conds) ) $so->push($cond);
	}
	*/
	$admin = adesk_admin_get();
	$uid = 1;
	if ( $admin['id'] != 1 ) {
		$lists = implode("', '", $admin["lists"]);
		$so->push("AND p.listid IN ('$lists')");
		$uid = $admin['id'];
	}
	if ( !isset($so->subqueryconds) ) $so->subqueryconds = '';
	$subqueryconds = str_replace('d.tstamp', 'xd.tstamp', $so->subqueryconds);
	if ( $listid ) {
		$query = "
			SELECT
				c.id,
				c.name,
				c.sdate AS tstamp,
				c.total_amt,
				c.opens,
				c.uniqueopens,
				NULL AS besthour,
				NULL AS bestweek
#				NULL AS worsthour,
#				NULL AS worstweek
			FROM
				#campaign c,
				#campaign_list l,
				#user_p p
			WHERE
			[...]
			AND
				c.status NOT IN (0)    # not: draft
			AND
				c.sdate < NOW()        # sent in past
			AND
				c.total_amt > 0        # sent to someone
			AND
				l.listid = '$listid'
			AND
				l.listid = p.listid
			AND
				c.id = l.campaignid
			GROUP BY
				c.id                   # ensure we only get one per list
		";
	} else {
		$query = "
			SELECT
				l.id,
				l.name,
				l.cdate AS tstamp,
#				0 AS total_amt,
#				0 AS opens,
				( SELECT COUNT(xd.id) FROM #link_data xd, #link xl, #campaign_list xc WHERE xl.link = 'open' AND xl.tracked = 1 AND xl.messageid = 0 AND xl.campaignid = xc.campaignid AND xc.listid = l.id AND xl.id = xd.linkid $subqueryconds ) AS uniqueopens,
				NULL AS besthour,
				NULL AS bestweek
#				NULL AS worsthour,
#				NULL AS worstweek
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
	}
	return $so->query($query);
}
/*
function report_trend_read_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND l.id = '$id'");

	return adesk_sql_select_row(report_trend_read_select_query($so));
}
*/
function report_trend_read_select_array($so = null, $listid = 0, $filter = 0) {
	// select object
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	$listid = (int)$listid;

	// any filters used
	$filter = intval($filter);
	if ($filter > 0) {
		$so = select_filter_comment_parse($so, $filter, 'report_trend_read');
	}

	$series = array(
		2 => _a("Monday"),
		3 => _a("Tuesday"),
		4 => _a("Wednesday"),
		5 => _a("Thursday"),
		6 => _a("Friday"),
		7 => _a("Saturday"),
		1 => _a("Sunday"),
	);

	// fetch the rows
	//dbg(adesk_prefix_replace(report_trend_read_select_query($so, $listid)));
	$rows = adesk_sql_select_array(report_trend_read_select_query($so, $listid));
	foreach ( $rows as $k => $v ) {
		// calculate best/worst hours/weekdays
		if ( $listid ) {
			// per campaign
			$found = adesk_sql_select_row("
				SELECT
					HOUR(d.tstamp) AS `besthour`,
					COUNT(*) AS `besthourhits`
				FROM
					#link l,
					#link_data d
				WHERE
					l.campaignid = '$v[id]'
				AND
					l.messageid = 0
				AND
					l.link = 'open'
				AND
					l.tracked = 1
				AND
					l.id = d.linkid
				{$so->subqueryconds}
				GROUP BY
					HOUR(d.tstamp)
				ORDER BY
					besthourhits DESC
				LIMIT 0, 1
			");
			if ( $found ) {
				$rows[$k]['besthour'] = $found['besthour'];
				$rows[$k]['besthourhits'] = $found['besthourhits'];
			}
			/*
			$found = adesk_sql_select_row("
				SELECT
					HOUR(d.tstamp) AS `worsthour`,
					COUNT(*) AS `worsthourhits`
				FROM
					#link l,
					#link_data d
				WHERE
					l.campaignid = '$v[id]'
				AND
					l.messageid = 0
				AND
					l.link = 'open'
				AND
					l.tracked = 1
				AND
					l.id = d.linkid
				{$so->subqueryconds}
				GROUP BY
					HOUR(d.tstamp)
				ORDER BY
					worsthourhits ASC
				LIMIT 0, 1
			");
			if ( $found ) {
				$rows[$k]['worsthour'] = $found['worsthour'];
				$rows[$k]['worsthourhits'] = $found['worsthourhits'];
			}
			*/
			$found = adesk_sql_select_row("
				SELECT
					DATE_FORMAT(d.tstamp, '%w') + 1 AS `bestweek`,
					COUNT(*) AS `bestweekhits`
				FROM
					#link l,
					#link_data d
				WHERE
					l.campaignid = '$v[id]'
				AND
					l.messageid = 0
				AND
					l.link = 'open'
				AND
					l.tracked = 1
				AND
					l.id = d.linkid
				{$so->subqueryconds}
				GROUP BY
					DAYOFWEEK(d.tstamp)
				ORDER BY
					bestweekhits DESC
				LIMIT 0, 1
			");
			if ( $found ) {
				$found['bestweek'] = ( $found['bestweek'] ? $found['bestweek'] : 7 );
				$rows[$k]['bestweek'] = $found['bestweek'];
				$rows[$k]['bestweeklabel'] = $series[$found['bestweek']];
				$rows[$k]['bestweekhits'] = $found['bestweekhits'];
			}
			/*
			$found = adesk_sql_select_row("
				SELECT
					DATE_FORMAT(d.tstamp, '%w') + 1 AS `worstweek`,
					COUNT(*) AS `worstweekhits`
				FROM
					#link l,
					#link_data d
				WHERE
					l.campaignid = '$v[id]'
				AND
					l.messageid = 0
				AND
					l.link = 'open'
				AND
					l.tracked = 1
				AND
					l.id = d.linkid
				{$so->subqueryconds}
				GROUP BY
					DAYOFWEEK(d.tstamp)
				ORDER BY
					worstweekhits ASC
				LIMIT 0, 1
			");
			if ( $found ) {
				$found['worstweek'] = ( $found['worstweek'] ? $found['worstweek'] - 1 : 6 );
				$rows[$k]['worstweek'] = $found['worstweek'];
				$rows[$k]['worstweeklabel'] = $series[$found['worstweek']];
				$rows[$k]['worstweekhits'] = $found['worstweekhits'];
			}
			*/
		} else {
			// per list
			$found = adesk_sql_select_row("
				SELECT
					HOUR(d.tstamp) AS `besthour`,
					COUNT(*) AS `besthourhits`
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
					c.campaignid = l.campaignid
				AND
					l.id = d.linkid
				{$so->subqueryconds}
				GROUP BY
					HOUR(d.tstamp)
				ORDER BY
					besthourhits DESC
				LIMIT 0, 1
			");
			if ( $found ) {
				$rows[$k]['besthour'] = $found['besthour'];
				$rows[$k]['besthourhits'] = $found['besthourhits'];
			}
			/*
			$found = adesk_sql_select_row("
				SELECT
					HOUR(d.tstamp) AS `worsthour`,
					COUNT(*) AS `worsthourhits`
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
					c.campaignid = l.campaignid
				AND
					l.id = d.linkid
				{$so->subqueryconds}
				GROUP BY
					HOUR(d.tstamp)
				ORDER BY
					worsthourhits ASC
				LIMIT 0, 1
			");
			if ( $found ) {
				$rows[$k]['worsthour'] = $found['worsthour'];
				$rows[$k]['worsthourhits'] = $found['worsthourhits'];
			}
			*/
			$found = adesk_sql_select_row("
				SELECT
					DATE_FORMAT(d.tstamp, '%w') + 1 AS `bestweek`,
					COUNT(*) AS `bestweekhits`
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
					c.campaignid = l.campaignid
				AND
					l.id = d.linkid
				{$so->subqueryconds}
				GROUP BY
					DAYOFWEEK(d.tstamp)
				ORDER BY
					bestweekhits DESC
				LIMIT 0, 1
			");
			if ( $found ) {
				$found['bestweek'] = ( $found['bestweek'] ? $found['bestweek'] : 7 );
				$rows[$k]['bestweek'] = $found['bestweek'];
				$rows[$k]['bestweeklabel'] = $series[$found['bestweek']];
				$rows[$k]['bestweekhits'] = $found['bestweekhits'];
			}
			/*
			$found = adesk_sql_select_row("
				SELECT
					DAYOFWEEK(d.tstamp) AS `worstweekday`,
					DATE_FORMAT(d.tstamp, '%w') + 1 AS `worstweek`,
					COUNT(*) AS `worstweekhits`
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
					c.campaignid = l.campaignid
				AND
					l.id = d.linkid
				{$so->subqueryconds}
				GROUP BY
					DAYOFWEEK(d.tstamp)
				ORDER BY
					worstweekhits ASC
				LIMIT 0, 1
			");
			if ( $found ) {
				$found['worstweekday'] = ( $found['worstweekday'] ? $found['worstweekday'] - 1 : 6 );
				$rows[$k]['worstweek'] = $found['worstweek'];
				$rows[$k]['worstweeklabel'] = $series[$found['worstweek']];
				$rows[$k]['worstweekhits'] = $found['worstweekhits'];
			}
			*/
		}
	}
	return $rows;
}

function report_trend_read_select_array_paginator($id, $sort, $offset, $limit, $filter, $listid = 0) {
	$so = new adesk_Select;
	$so->subqueryconds = '';

	$filter = intval($filter);
	if ($filter > 0) {
		$so = select_filter_comment_parse($so, $filter, 'report_trend_read');
	}

	$so->count();
	$total = (int)adesk_sql_select_one(report_trend_read_select_query($so, $listid));

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
			$so->orderby("besthour"); break;
		case "03D":
			$so->orderby("besthour DESC"); break;
		case "04":
			$so->orderby("bestweek"); break;
		case "04D":
			$so->orderby("bestweek DESC"); break;
		case "05":
			$so->orderby("worsthour"); break;
		case "05D":
			$so->orderby("worsthour DESC"); break;
		case "06":
			$so->orderby("worstweek"); break;
		case "06D":
			$so->orderby("worstweek DESC"); break;
		case "07":
			$so->orderby("uniqueopens"); break;
		case "07D":
			$so->orderby("uniqueopens DESC"); break;
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = report_trend_read_select_array($so, $listid);//dbg($rows);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function report_trend_read_filter_post() {
	$whitelist = array("l.name", "l.stringid", "l.analytics_source", "l.twitter_user", "l.to_name");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "report_trend_read",
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
				$ary['conds'] .= "AND p.listid IN ('$ids') ";
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
				$ary['conds'] .= "AND p.listid = '$listid' ";
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
		$_SESSION['report_trend_read_datetime'] = $_POST['datetimefilter'];
	}
	if ( isset($_POST['from'])  ) {
		$_SESSION['report_trend_read_datetimeto'] = $_POST['from'];
	}
	if ( isset($_POST['to'])  ) {
		$_SESSION['report_trend_read_datetimeto'] = $_POST['to'];
	}
	if ( isset($_SESSION['report_trend_read_datetime']) and $_SESSION['report_trend_read_datetime'] != 'all' ) {
		$comment = '';
		$from = '';
		$to = '';
		switch ( $_SESSION['report_trend_read_datetime'] ) {
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
				if ( isset($_SESSION['report_trend_read_datetimefrom']) and isset($_SESSION['report_trend_read_datetimeto']) ) {
					// sanitize from
					$from = @strtotime($_SESSION['report_trend_read_datetimefrom']);
					if ( $from == -1 ) $from = false;
					if ( $from ) $from = date('Y-m-d', $from);
					// sanitize to
					$to = @strtotime($_SESSION['report_trend_read_datetimeto']);
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
			//$_SESSION['report_trend_read_datetime'] = $_POST['datetimefilter'];
			if ( $from ) $_SESSION['report_trend_read_datetimefrom'] = $from;
			if ( $to   ) $_SESSION['report_trend_read_datetimeto'] = $to;
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
			sectionid = 'report_trend_read'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

?>
