<?php
/**
 * Processes PaySiteCash request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('PaySiteCash')) {
    die('PaySiteCash integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = PaySiteCash_Tracker::getInstance();
$tracker->process();
?>
