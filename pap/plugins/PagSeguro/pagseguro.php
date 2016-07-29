<?php
/**
 * Processes PagSeguro request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('PagSeguro')) {
    die('Plugin PagSeguro is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());
	
$tracker = PagSeguro_Tracker::getInstance();
$tracker->process();
?>
