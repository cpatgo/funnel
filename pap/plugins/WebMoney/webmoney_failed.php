<?php
/**
 * Processes WebMoney Failed request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('WebMoney_Definition')) {
    die('WebMoney integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = WebMoney_Tracker::getInstance();
$tracker->finishTransaction(Pap_Common_Constants::STATUS_DECLINED);
?>
