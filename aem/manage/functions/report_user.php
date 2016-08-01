<?php

require_once awebdesk_classes("select.php");

function report_user_select_query(&$so, $gid = 0) {
	require_once(awebdesk_functions('user.php'));
	if ( !is_object($so) ) $so = new adesk_Select();
	if ( !isset($so->subqueryconds) ) $so->subqueryconds = '';
	if ( !isset($so->subqueryavg)   ) $so->subqueryavg = 'ROUND( ( UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(u.sdate) ) / 60 / 60 / 24 )';
	if ( $gid > 0 ) {
		$list = adesk_sql_select_list("
			SELECT
				u.absid
			FROM
				#user u,
				#user_group g
			WHERE
				g.userid = u.id
			AND
				g.groupid = '$gid'
		");
		if ( count($list) > 0 ) {
			$str = implode("', '", $list);
			$so->push("AND id IN ('$str')");
		}
	}
	return adesk_user_select_query($so);
}

function report_user_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND id = '$id'");

	return adesk_user_select_row($id);
}

function report_user_select_array($so = null, $ids = null, $groupid = 0, $filter = 0) {
	// select object
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	// passed ids to filter to
	if ($ids !== null) {
		$tmp = array_map("intval", explode(",", $ids));
		$ids = implode("','", $tmp);
		$so->push("AND id IN ('$ids')");
	}

	// any filters used
	$filter = intval($filter);
	if ($filter > 0) {
		$so = select_filter_comment_parse($so, $filter, 'report_user');
	}

	// fetch the rows
	//dbg(adesk_prefix_replace(report_user_select_query($so, $groupid)));
	# Specifying $GLOBALS['db_link'] in mysql_query, or using adesk_sql_query, for some reason
	# breaks the UTF-8 encoding of the connection.  It's really bizarre, but absolutely
	# repeatable.  It only happens here, that I can tell.
	$rs   = mysql_query(report_user_select_query($so, $groupid));
	$rows = array();
	while ($row = adesk_sql_fetch_assoc($rs)) {
		$row['campaigns'] = (int)adesk_sql_select_one("
			SELECT
				COUNT(c.id)
			FROM
				#campaign c
			WHERE
				c.userid = '$row[id]'
			AND
				c.status != 0
			{$so->subqueryconds}
		");
		$row['emails'] = (int)adesk_sql_select_one("
			SELECT
				SUM(c.total_amt)
			FROM
				#campaign c
			WHERE
				c.userid = '$row[id]'
			AND
				c.status != 0
			{$so->subqueryconds}
		");
		$row['epd'] = (float)adesk_sql_select_one($q = "
			SELECT
				SUM(c.total_amt) / {$so->subqueryavg}
			FROM
				#campaign c,
				#user u
			WHERE
				c.userid = '$row[id]'
			AND
				u.id = '$row[id]'
			AND
				c.status != 0
			{$so->subqueryconds}
		");

		$rows[] = $row;
	}
	return $rows;
}

function report_user_select_array_paginator($id, $sort, $offset, $limit, $filter, $gid = 0) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$so = select_filter_comment_parse($so, $filter, 'report_user');
	}

	$so->count();
	$total = (int)adesk_sql_select_one(report_user_select_query($so, $gid));

	switch ($sort) {
		default:
		case "01":
			$so->orderby("username"); break;
		case "01D":
			$so->orderby("username DESC"); break;/*
		case "02":
			$so->orderby("campaigns"); break;
		case "02D":
			$so->orderby("campaigns DESC"); break;
		case "03":
			$so->orderby("emails"); break;
		case "03D":
			$so->orderby("emails DESC"); break;
		case "04":
			$so->orderby("epd"); break;
		case "04D":
			$so->orderby("epd DESC"); break;*/
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = report_user_select_array($so, null, $gid);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function report_user_filter_post() {

	$whitelist = array("username", "first_name", "last_name", "email");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "report_user",
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
	if ( isset($_POST['listid']) ) {
		if ( defined('AWEBVIEW') ) {
			$_SESSION['nlp'] = $_POST['listid'];
		} else {
			$_SESSION['nla'] = $_POST['listid'];
		}
	}
	$nl = null;
	if ( isset($_SESSION['nlp']) and defined('AWEBVIEW') ) {
		$nl = $_SESSION['nlp'];
	} elseif ( isset($_SESSION['nla']) ) {
		$nl = $_SESSION['nla'];
	}
	if ( $nl ) {
		$ids = array();
		if ( is_array($nl) ) {
			if ( count($nl) > 0 ) {
				$ids = implode("', '", array_map('intval', $nl));
				//$ary['conds'] .= "AND l.listid IN ('$ids') ";
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
				$ids = array($listid);
				//$ary['conds'] .= "AND l.listid = '$listid' ";
			} else {
				if ( defined('AWEBVIEW') ) {
					unset($_SESSION['nlp']);
				} else {
					unset($_SESSION['nla']);
				}
			}
		}
		if ( count($ids) > 0 ) {
			if (!is_array($ids))
				$ids = array($ids);
			$str = implode("', '", $ids);
			$list = adesk_sql_select_list("
				SELECT
					u.absid
				FROM
					#user_p p,
					#user u
				WHERE
					p.listid IN ('$str')
				AND
					p.userid = u.id
			");
			if ( count($list) > 0 ) {
				$str = implode("', '", $list);
				$ary['conds'] .= "AND id IN ('$str') ";
			}
		}
	}
	if ( isset($_POST['datetimefilter'])  ) {
		$_SESSION['report_user_datetime'] = $_POST['datetimefilter'];
	}
	if ( isset($_POST['from'])  ) {
		$_SESSION['report_user_datetimefrom'] = $_POST['from'];
	}
	if ( isset($_POST['to'])  ) {
		$_SESSION['report_user_datetimeto'] = $_POST['to'];
	}
	if ( isset($_SESSION['report_user_datetime']) and $_SESSION['report_user_datetime'] != 'all' ) {
		$comment = '';
		$from = '';
		$to = '';
		switch ( $_SESSION['report_user_datetime'] ) {
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
				if ( isset($_SESSION['report_user_datetimefrom']) and isset($_SESSION['report_user_datetimeto']) ) {
					// sanitize from
					$from = @strtotime($_SESSION['report_user_datetimefrom']);
					if ( $from == -1 ) $from = false;
					if ( $from ) $from = date('Y-m-d', $from);
					// sanitize to
					$to = @strtotime($_SESSION['report_user_datetimeto']);
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
			//$_SESSION['report_user_datetime'] = $_POST['datetimefilter'];
			if ( $from ) $_SESSION['report_user_datetimefrom'] = $from;
			if ( $to   ) $_SESSION['report_user_datetimeto'] = $to;
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
			sectionid = 'report_user'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

?>
