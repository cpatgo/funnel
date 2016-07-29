<?php
/**
 * Processes ISecure request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('ISecure')) {
    die('Internet Secure notification handling plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = ISecure_Tracker::getInstance();
$tracker->process();
?>
