<?php
/**
 * Processes PremiumWebCartIPN request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('PremiumWebCartIPN')) {
    die('Plugin PremiumWebCartIPN is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = PremiumWebCartIPN_Tracker::getInstance();
$tracker->process();
?>
