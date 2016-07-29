<?php
/**
 * Processes MemberMouse request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('MemberMouse')) {
    die('Plugin MemberMouse is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = MemberMouse_Tracker::getInstance();
$tracker->process();
?>
