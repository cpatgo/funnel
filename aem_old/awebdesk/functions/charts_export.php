<?php

@session_start();

require_once dirname(__FILE__) . '/base.php';
require_once dirname(__FILE__) . '/charts.php';

if (isset($_GET["hash"]) && isset($_SESSION['adesk_chart_' . $_GET["hash"]]))
	adesk_charts_send($_SESSION['adesk_chart_' . $_GET["hash"]]);
elseif (isset($_SESSION["adesk_chart_export"]))
	adesk_charts_send($_SESSION["adesk_chart_export"]);

?>
