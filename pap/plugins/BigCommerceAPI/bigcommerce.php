<?php
/**
 * Processes BigCommerceAPI request
 */
require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('BigCommerceAPI')) {
    die('Plugin BigCommerce API is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = BigCommerceAPI_Tracker::getInstance();
$tracker->process();
?>
