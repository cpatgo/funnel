<?php
/**
 * Processes PayPal request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('WorldPay')) {
    die('WorldPay callback handling plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = WorldPay_Tracker::getInstance();
$tracker->process();
?>
