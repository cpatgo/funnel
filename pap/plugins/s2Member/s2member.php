<?php
/**
 * Processes s2Member request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('s2Member')) {
    die('s2Member integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = s2Member_Tracker::getInstance();
$tracker->process();
?>
