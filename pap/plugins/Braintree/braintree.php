<?php
/**
 * Processes Braintree request
 */
require_once '../../scripts/bootstrap.php';
require_once 'Braintree.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('Braintree')) {
    die('Braintree webhook handling plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = Braintree_Tracker::getInstance();
$tracker->process();
