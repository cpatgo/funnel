<?php
/**
 * Processes PayPal request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('PayPal')) {
    die('Plugin PayPal is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = PayPal_Tracker::getInstance();
$tracker->process();
?>
