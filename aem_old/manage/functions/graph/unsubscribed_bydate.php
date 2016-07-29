<?php
require_once awebdesk_classes("select.php");
	$admin = adesk_admin_get();
		$uid = $admin['id'];
		if($uid != 1 ) {
			$lists2 = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");
				 $liststr   = implode("','", $lists2);
					
				}
				else
				{
					$liststr   = implode("','", $admin["lists"]);
					 
				}

$period  = intval(adesk_http_param("period"));
$from    = strval(adesk_http_param("from"));
$to      = strval(adesk_http_param("to"));


$listid    = (int)adesk_http_param("id");
$mode      = (string)adesk_http_param("mode");
$filterid  = (int)adesk_http_param("filterid");

$range     = 'all';

$so = new adesk_Select();
if ( $mode == 'report_list' ) {
	if ( $filterid ) {
		$so = select_filter_comment_parse($so, $filterid, $mode);
		if ( isset($so->graphfrom)     ) $from   = $so->graphfrom;
		if ( isset($so->graphto)       ) $to     = $so->graphto;
		if ( isset($so->graphperiod)   ) $period = $so->graphperiod;
		if ( isset($so->graphmode)     ) $range  = $so->graphmode;
	}
}

$series = array();
$graph  = array();
//dbg($so, 1);dbg("$period; $from = $to", 1);

if ( $range == 'day' ) { // this never happens, range is hardcoded to 'all' above
	adesk_graph_prepare_timeline($series, $graph, $period, $from, $to);
} else {
	adesk_graph_prepare_dateline($series, $graph, $period, $from, $to);
}

$cond = "";

if ( $mode == 'report_list' ) {
	if ( $listid > 0 ) {
		//$cond .= "AND sl.listid IN ( SELECT u.userid FROM #user_group u WHERE u.groupid = '$listid' ) ";
		$liststr = $listid;
	}
	if ( count($so->conds) > 1 ) {
		$f = $so->conds[1];
		// apply list filter
		$cond  = "AND sl.listid IN ( SELECT l.id FROM #list l WHERE 1 $f ) ";
	}
}

$format = ( $range == 'day' ? '%H' : '%m/%d' );
$query = "
	SELECT
		DATE_FORMAT(sl.udate, '$format') AS cdate,
		DATEDIFF('$to', sl.udate) AS diff,
		COUNT(*) AS count
	FROM
		#subscriber_list sl
	WHERE
		sl.status = 2
	AND sl.listid IN ('$liststr')
	AND DATE(sl.udate) > '$from'
	AND sl.udate < ('$to' + INTERVAL 1 DAY)
	$cond
	GROUP BY
		DATE(sl.udate)
";
$rs   = adesk_sql_query($query);

$count = 0;
$total = intval(adesk_http_param("total")) > 0;

while ($row = adesk_sql_fetch_assoc($rs)) {
	if ($total)
		$count += $row["count"];
	else
		$count = $row["count"];

	$series[$row["diff"]] = $row["cdate"];
	$graph[$row["diff"]] += $count;
}

if ($total) {
	$xcount = 0;
	foreach ($graph as $key => $count) {
		if ($count > $xcount)
			$xcount = $count;
		$graph[$key] = $xcount;
	}
}

$smarty->assign("series", $series);
$smarty->assign("graph", $graph);

//dbg(adesk_prefix_replace($query), 1);dbg($series, 1);dbg(array_sum($graph), 1);dbg($graph);

?>
