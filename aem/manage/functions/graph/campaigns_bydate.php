<?php

$groupid   = (int)adesk_http_param("id");
$mode      = (string)adesk_http_param("mode");
$filterid  = (int)adesk_http_param("filterid");

$period    = 0;
$from      = '';
$to        = '';
$range     = 'all';

$whitelist = array('report_group', 'report_user');

if ( !in_array($mode, $whitelist) ) {
	die('Improper usage.');
}

$so = new adesk_Select();
if ( $filterid ) {
	$so = select_filter_comment_parse($so, $filterid, $mode);
	if ( isset($so->graphfrom)     ) $from   = $so->graphfrom;
	if ( isset($so->graphto)       ) $to     = $so->graphto;
	if ( isset($so->graphperiod)   ) $period = $so->graphperiod;
	if ( isset($so->graphmode)     ) $range  = $so->graphmode;
}

$series = array();
$graph  = array();
//dbg("$period; $from = $to", 1);

adesk_graph_prepare_dateline($series, $graph, $period, $from, $to);

$cond = "";
if ( $groupid > 0 ) {
	$cond .= "AND c.userid IN ( SELECT u.userid FROM #user_group u WHERE u.groupid = '$groupid' ) ";
}
if ( count($so->conds) > 1 ) {
	$f = $so->conds[1];
	if ( $groupid > 0 ) {
		// apply user filter (cancel the previous filter)
		$cond  = "AND c.userid IN ( SELECT u.userid FROM #user_group u, #group g WHERE u.groupid = '$groupid' AND u.groupid = g.id $f ) ";
	} else {
		// apply group filter
		$cond .= "AND c.userid IN ( SELECT u.userid FROM #user_group u, #group g WHERE u.groupid = g.id $f ) ";
	}
}

$query = "
	SELECT
		DATE_FORMAT(c.sdate, '%m/%d') AS sdate,
		DATEDIFF('$to', sdate) AS diff,
		COUNT(*) AS count
	FROM
		#campaign c
	WHERE
		DATE(c.sdate) > '$from'
	AND
		c.sdate <  ('$to' + INTERVAL 1 DAY)
	$cond
	GROUP BY
		DATE(c.sdate)
";
$rs = adesk_sql_query($query);

while ($row = adesk_sql_fetch_assoc($rs)) {
	$series[$row["diff"]]  = $row["sdate"];
	$graph[$row["diff"]]  += $row["count"]; // "+" is here cuz we don't group by DATE(c.sdate)
}

$smarty->assign("series", $series);
$smarty->assign("graph", $graph);

//dbg(adesk_prefix_replace($query), 1);dbg($series, 1);dbg(array_sum($graph), 1);dbg($graph);

?>
