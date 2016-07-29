<?php
/**
 * Processes PayPal request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('Paymate')) {
    die('Paymate Express integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = Paymate_Tracker::getInstance();
$tracker->process();
?>
