<?php
/**
 * Processes CheddarGetter request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('CheddarGetter')) {
    die('Plugin CheddarGetter is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = CheddarGetter_Tracker::getInstance();
$tracker->process();
?>
