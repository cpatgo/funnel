<?php

$campaignid = intval(adesk_http_param("campaignid"));
$messageid  = intval(adesk_http_param("messageid"));
$listid     = intval(adesk_http_param("listid"));
$period  = intval(adesk_http_param("period"));
$from    = strval(adesk_http_param("from"));
$to      = strval(adesk_http_param("to"));
$timeline_period  = 24;
$timeline_from    = '00:00:00';
$timeline_to      = 23;

$series = array();
$graph  = array();

//if ( !$from ) $from = date('Y-m-d H:i:s', 0);
//if ( !$to ) $to = adesk_CURRENTDATE;

adesk_graph_prepare_timeline($series, $graph, $timeline_period, $timeline_from, $timeline_to);

$cond = $subcond = "";
if ($messageid > 0)
	$subcond .= "AND l.messageid = '$messageid' ";
//else
//	$subcond .= "AND l.messageid = '0' ";

if ($campaignid > 0)
	$subcond .= "AND l.campaignid = '$campaignid' ";

$listarr = array();
if ( adesk_admin_ismain() ) {
	if ( $listid ) $listarr[] = $listid;
} else {
	$admin = adesk_admin_get();
	if ( $listid ) {
		if ( isset($admin['lists'][$listid]) ) {
			$listarr[] = $listid;
		} else {
			$listarr = array(0);
		}
	} else {
		$listarr = $admin['lists'];
	}
}
if ( $listarr ) {
	$liststr = implode("', '", $listarr);
	$cond .= "AND ( SELECT COUNT(*) FROM #subscriber_list sl WHERE ld.subscriberid = sl.subscriberid AND sl.listid IN ('$liststr') ) > 0 ";
}

if ( $from ) {
	$cond .= "AND DATE(ld.tstamp) > '$from' ";
}

if ( $to ) {
	$cond .= "AND ld.tstamp <  ('$to' + INTERVAL 1 DAY) ";
}

$query = "
	SELECT
		DATE_FORMAT(ld.tstamp, '%H') AS tstamp,
		23 - HOUR(tstamp) AS diff,
		COUNT(*) AS count
	FROM
		#link_data ld
	WHERE
		ld.linkid IN
		(
			SELECT
				id
			FROM
				#link l
			WHERE
				l.link != 'open'
			AND l.tracked = 1
			AND l.campaignid = '$campaignid'
			$subcond
		)
		$cond
	GROUP BY
		HOUR(ld.tstamp)
";
$rs = adesk_sql_query($query) or die(adesk_sql_error());

while ($row = adesk_sql_fetch_assoc($rs)) {
	$series[$row["diff"]] = $row["tstamp"];
	$graph[$row["diff"]] += $row["count"]; // "+" is here cuz we don't group by DATE(ld.tstamp)
}
//dbg(adesk_prefix_replace($query),1);dbg($series,1);dbg($graph);
$smarty->assign("series", $series);
$smarty->assign("graph", $graph);

?>
