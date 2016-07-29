<?php
/**
 * Processes Infusionsoft request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('Infusionsoft')) {
    die('Infusionsoft API plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = Infusionsoft_Tracker::getInstance();
$tracker->process();
?>
