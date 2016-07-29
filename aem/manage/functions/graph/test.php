<?php

$rs   = adesk_sql_query("SELECT DATE(cdate) AS cdate, COUNT(*) AS count FROM #subscriber GROUP BY DATE(cdate)");
$series = array();
$graph  = array();

while ($row = adesk_sql_fetch_assoc($rs)) {
	$series[] = $row["cdate"];
	$graph[]  = $row["count"];
}

$smarty->assign("series", $series);
$smarty->assign("graph", $graph);

?>
