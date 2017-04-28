<?php

$action = (string)adesk_http_param('g');

if ( $action == '' ) {
	exit;
} elseif ( !isset($allowed[$action]) || !$allowed[$action] ) {
	exit;
}

$actionFile = adesk_admin("functions/graph/$action.php");

if (!file_exists($actionFile)) {
	exit;
}

# Set up the default environment for the chart.
require_once awebdesk_classes("smarty.php");
require_once awebdesk_functions("smarty.php");
require_once awebdesk_functions("graph.php");
$smarty = new adesk_Smarty("admin");

require_once($actionFile);

if (!headers_sent())
	header("Pragma: no-cache");

$smarty->sendCTHeader("Content-Type: text/xml");
$smarty->display(adesk_admin("templates/graph/$action.xml"));

?>
