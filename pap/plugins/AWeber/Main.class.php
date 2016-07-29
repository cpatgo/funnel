<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @author Milos Jancovic    (created by Rick Braddy / WinningWare.com for PostAffiliatePro)
 *   @package PostAffiliatePro
 *   @since Version 1.0.0
 *
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 1.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.postaffiliatepro.com/licenses/license
 *
 */

/**
 * @package PostAffiliatePro
 */
class AWeber_Main extends Gpf_Plugins_Handler {
    
    public static function getHandlerInstance() {
        return new AWeber_Main();
    }
    
    public function initSettings($context) {
        $context->addDbSetting(AWeber_Config::AUTORESPONDER_ADDRESS, 'mylistname@aweber.com');
        $context->addDbSetting(AWeber_Config::AUTORESPONDER_ADD_REFID, Gpf::NO);
        $context->addDbSetting(AWeber_Config::AUTORESPONDER_REFID_FIELDNAME, 'ad_tracking');
    }
    
    public function sendMail(Pap_Contexts_Signup $context) {        
        $mail = new AWeber_Mail();
        $mail->setUser($context->getUserObject());
        $recipients = explode(';', str_replace(',', ';', Gpf_Settings::get(AWeber_Config::AUTORESPONDER_ADDRESS)));
        foreach ($recipients as $recipient) {
            $mail->addRecipient(trim($recipient));
        }
        $mail->sendNow();
    }
}
?>
