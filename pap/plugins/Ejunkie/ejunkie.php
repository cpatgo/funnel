<?php
/**
 * Processes E-junkie request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('Ejunkie')) {
    die('Ejunkie integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = Ejunkie_Tracker::getInstance();
$tracker->process();
?>
