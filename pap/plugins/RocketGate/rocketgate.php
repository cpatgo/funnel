<?php
/**
 * Processes RocketGate request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('RocketGate')) {
    die('RocketGate integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = RocketGate_Tracker::getInstance();
$tracker->process();
?>
