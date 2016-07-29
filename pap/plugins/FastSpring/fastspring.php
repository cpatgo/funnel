<?php
/**
 * Processes PayPal request
 */

//require_once $_SERVER["PROJECT_SOURCE_PATH"].'/scripts/bootstrap.php';
require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('FastSpring')) {
    die('Plugin FastSpring is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = FastSpring_Tracker::getInstance();
$tracker->process();
?>
