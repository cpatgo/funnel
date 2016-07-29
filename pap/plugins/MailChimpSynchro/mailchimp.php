<?php
/**
 * Processes MailChimp webhook event
 */

require_once '../../scripts/bootstrap.php';

if (!Gpf_Plugins_Engine::getInstance()->getConfiguration()->isPluginActive('MailChimpSynchro')) {
    die('MailChimp integration plugin is not active!');
}

Gpf_Session::create(new Pap_Tracking_ModuleBase());

$chimp = new MailChimpSynchro_Main();
$chimp->processWebhook();
?>
