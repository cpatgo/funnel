<?php
/**
 * Processes MoneyBookers request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('MoneyBookers_Definition')) {
    die('MoneyBookers integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = MoneyBookers_Tracker::getInstance();
$tracker->process();
?>
