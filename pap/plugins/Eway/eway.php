<?php
/**
 * Processes Eway request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('Eway')) {
    die('eWAY integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = Eway_Tracker::getInstance();
$tracker->process();
?>
