<?php
/**
 * Processes 2Checkout request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('2Checkout')) {
    die('2Checkout integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = TwoCheckout_Tracker::getInstance();
$tracker->process();
