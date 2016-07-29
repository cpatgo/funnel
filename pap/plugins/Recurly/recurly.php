<?php
/**
 * Processes Recurly request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('Recurly')) {
    die('Recurly integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = Recurly_Tracker::getInstance();
$tracker->process();
?>
