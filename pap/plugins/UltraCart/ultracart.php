<?php
/**
 * Processes UltraCart request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('UltraCart')) {
    die('UltraCart integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = UltraCart_Tracker::getInstance();
$tracker->process();
?>
