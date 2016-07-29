<?php
/**
 * Processes GoogleCheckout request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('PremiumWebCart')) {
    die('PremiumWebCart integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = PremiumWebCart_Tracker::getInstance();
$tracker->process();
?>
