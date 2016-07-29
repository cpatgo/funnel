<?php
/**
 * Processes OptimizeMember request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('OptimizeMember')) {
    die('OptimizeMember integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = OptimizeMember_Tracker::getInstance();
$tracker->process();
