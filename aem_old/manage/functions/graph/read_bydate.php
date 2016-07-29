<?php

$campaignid = intval(adesk_http_param("id"));
$messageid = intval(adesk_http_param("messageid"));
$period  = intval(adesk_http_param("period"));
$from    = strval(adesk_http_param("from"));
$to      = strval(adesk_http_param("to"));

$series = array();
$graph  = array();

adesk_graph_prepare_dateline($series, $graph, $period, $from, $to);

$cond = "";
if ($messageid > 0)
	$cond = "AND l.messageid = '$messageid'";
else
	$cond = "AND l.messageid = '0'";

$rs = adesk_sql_query("
	SELECT
		DATE_FORMAT(ld.tstamp, '%m/%d') AS tstamp,
		DATEDIFF('$to', tstamp) AS diff,
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
				l.link = 'open'
			AND l.tracked = 1
			AND l.campaignid = '$campaignid'
			$cond
		)
		AND DATE(ld.tstamp) > '$from'
		AND ld.tstamp < ('$to' + INTERVAL 1 DAY)
	GROUP BY
		DATE(ld.tstamp)
") or die(adesk_sql_error());

while ($row = adesk_sql_fetch_assoc($rs)) {
	$series[$row["diff"]] = $row["tstamp"];
	$graph[$row["diff"]] += $row["count"]; // "+" is here cuz we don't group by DATE(ld.tstamp)
}

$smarty->assign("series", $series);
$smarty->assign("graph", $graph);

?>
