<?php
/**
 * Processes Bluepay request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('Bluepay')) {
    die('Bluepay integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = Bluepay_Tracker::getInstance();
$tracker->process();
?>
