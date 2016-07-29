<?php
/**
 * Processes Setcom request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('Setcom')) {
    die('Setcom integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = Setcom_Tracker::getInstance();
$tracker->process();
?>
