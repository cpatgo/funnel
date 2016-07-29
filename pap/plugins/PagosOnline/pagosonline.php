<?php
/**
 * Processes PagosOnline request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('PagosOnline')) {
    die('PagosOnline integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = PagosOnline_Tracker::getInstance();
$tracker->process();
?>
