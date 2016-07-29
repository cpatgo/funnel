<?php
/**
 * Processes LimeLight request
 */
require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('LimeLight')) {
    die('Plugin LimeLight is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = LimeLight_Tracker::getInstance();
$tracker->process();
?>
