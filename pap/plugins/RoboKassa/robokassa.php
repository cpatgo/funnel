<?php
/**
 * Processes RoboKassa request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('RoboKassa')) {
    die('RoboKassa integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = RoboKassa_Tracker::getInstance();
$tracker->process();
?>
