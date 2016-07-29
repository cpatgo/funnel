<?php
/**
 * Processes Netbilling request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('Netbilling')) {
    die('Netbilling integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = Netbilling_Tracker::getInstance();
$tracker->process();
?>
