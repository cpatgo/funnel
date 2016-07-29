<?php
/**
 * Processes ccBill request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('ccBill')) {
    die('ccBill integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = ccBill_Tracker::getInstance();
$tracker->process();
?>
