<?php
/**
 * Processes AlertPay request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('AlertPay')) {
    die('AlertPay integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = AlertPay_Tracker::getInstance();
$tracker->process();
?>
