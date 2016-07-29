<?php
/**
 * BusinessCatalyst Notification request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('BusinessCatalyst')) {
    die('BusinessCatalyst integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$bc = new BusinessCatalyst_RetrieveOrders();
$bc->retrieve(); 

?>
