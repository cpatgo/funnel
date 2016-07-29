<?php
/**
 * Processes Swreg request
 */

require_once '../../scripts/bootstrap.php';

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$tracker = Swreg_Tracker::getInstance();
$tracker->process();
?>
