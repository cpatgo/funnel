<?php
/**
 * Processes Authorize.net request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('Authorize.net')) {
    die('Authorize.net integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = AuthorizeNet_Tracker::getInstance();
$tracker->process();
?>
