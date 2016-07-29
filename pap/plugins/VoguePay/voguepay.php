<?php
/**
 * Processes VoguePay request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('VoguePay')) {
    die('VoguePay integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = VoguePay_Tracker::getInstance();
$tracker->process();
?>
