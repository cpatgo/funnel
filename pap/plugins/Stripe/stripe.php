<?php
/**
 * Processes Stripe request
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('Stripe')) {
    die('Stripe webhook handling plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = Stripe_Tracker::getInstance();
$tracker->process();
?>
