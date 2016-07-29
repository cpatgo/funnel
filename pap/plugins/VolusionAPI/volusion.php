<?php
/**
 * Processes VolusionAPI request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('VolusionAPI')) {
    die('Volusion API integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = VolusionAPI_Tracker::getInstance();
$tracker->process();
?>
