<?php
/**
 * Processes TrialPay request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('TrialPay')) {
    die('TrialPay integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = TrialPay_Tracker::getInstance();
$tracker->process();
?>
