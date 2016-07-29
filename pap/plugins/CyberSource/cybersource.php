<?php
/**
 * Processes Netbilling request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('CyberSource')) {
    die('CyberSource integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = CyberSource_Tracker::getInstance();
$tracker->process();
?>
