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


$period    = intval(adesk_http_param("period"));
$from      = strval(adesk_http_param("from"));
$to        = strval(adesk_http_param("to"));


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

adesk_graph_prepare_dateline($series, $graph, $period, $from, $to);

$cond = "";

if ( $mode == 'report_list' ) {
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
	}

	if ( count($so->conds) > 1 ) {
		$f = $so->conds[1];
		// apply list filter
		$cond  = "AND sl.listid IN ( SELECT l.id FROM #list l WHERE 1 $f ) ";
	}
}


$rs   = adesk_sql_query("
	SELECT
		DATE_FORMAT(sl.sdate, '%m/%d') AS cdate,
		DATEDIFF('$to', sl.sdate) AS diff,
		COUNT(*) AS count
	FROM
		#subscriber_list sl
	WHERE
		sl.listid IN ('$liststr')
	AND DATE(sl.sdate) > '$from'
	AND sl.sdate < ('$to' + INTERVAL 1 DAY)
	$cond
	GROUP BY
		DATE(sl.sdate)
");

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

?>
