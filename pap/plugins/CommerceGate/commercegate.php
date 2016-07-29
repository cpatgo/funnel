<?php
/**
 * Processes CommerceGate request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('CommerceGate')) {
    die('CommerceGate integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = CommerceGate_Tracker::getInstance();
$tracker->process();
?>
