<?php

function adesk_graph_prepare_dateline(&$series, &$graph, &$period, &$from, &$to) {
	if ($period == 0)
		$period = 30;

	if ($to == "" && $from == "") {
		$row  = adesk_sql_select_row("SELECT DATE(NOW()) AS t, DATE(NOW() - INTERVAL $period DAY) AS f");
		$to   = $row["t"];
		$from = $row["f"];
	} else {
		if ( $to != '' ) {
			$to = @strtotime($to);
			$to = ( !$to or $to == -1 ) ? '' : date('Y-m-d', $to);
		}
		if ($to == "")
			$to = adesk_sql_select_one("SELECT DATE(NOW() + INTERVAL 1 DAY)");
		if ( $from != '' ) {
			$from = @strtotime($from);
			$from = ( !$from or $from == -1 ) ? '' : date('Y-m-d', $from);
		}
		if ($from == "")
			$from = adesk_sql_select_one("SELECT DATE('$to' - INTERVAL $period DAY)");
		$period = round( ( strtotime($to) - strtotime($from) ) / 60 / 60 / 24 ) + 1;
	}

	for ($i = $period - 1; $i >= 0; $i--) {
		$d          = strtotime($to) - (86400 * $i);
		$graph[$i]  = 0;
		$series[$i] = date("m/d", $d);
	}
}

function adesk_graph_prepare_timeline(&$series, &$graph, &$period, &$from, &$to) {
	if ($period == 0)
		$period = 24;

	if ($to != 'now')
		$to = 23;

	if ($from == "") {
		$from = adesk_CURRENTTIME;
	} else {
		$from = @strtotime($from);
		$from = ( !$from or $from == -1 ) ? adesk_CURRENTTIME : date('H:i:s', $from);
	}

	if ( $to == 'now' ) {
		// if from is provided, then do today
		$period = (int)substr(adesk_CURRENTTIME, 0, 2) + 1;
	}

	for ($i = $period - 1; $i >= 0; $i--) {
		$d          = $to - $i;
		$graph[$i]  = 0;
		$series[$i] = str_pad($d, 2, 0, STR_PAD_LEFT);
	}
}

?>
