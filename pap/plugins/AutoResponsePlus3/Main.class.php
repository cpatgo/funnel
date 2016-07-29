<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @author Martin Pullmann
 *   @package PostAffiliatePro
 *   @since Version 1.0.0
 *
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 1.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.qualityunit.com/licenses/license
 *
 */

/**
 * @package PostAffiliatePro
 */
class AutoResponsePlus3_Main extends Gpf_Plugins_Handler {

    public static function getHandlerInstance() {
        return new AutoResponsePlus3_Main();
    }

    public function initSettings($context) {
        $context->addDbSetting(AutoResponsePlus3_Config::ARP_URL, '');
        $context->addDbSetting(AutoResponsePlus3_Config::ARP_FORM_ID, '');
    }

    public function sendFormData(Pap_Contexts_Signup $context) {
        $url = Gpf_Settings::get(AutoResponsePlus3_Config::ARP_URL);
        $id = Gpf_Settings::get(AutoResponsePlus3_Config::ARP_FORM_ID);

        if (empty($url) || empty($id)) {
            Gpf_Log::error('AutoResponsePlus3: Plugin is missing a configuration! Please configure it first! Ending process...');
            return false;
        }

        Gpf_Log::info('AutoResponsePlus3: Plugin started.');
        $user = $context->getUserObject();
        $postData = 'id='.$id.'&subscription_type=E';

        $postData .= '&first_name='.urlencode ($user->getFirstName());
        $postData .= '&last_name='.urlencode ($user->getLastName());
        $postData .= '&full_name='.urlencode ($user->getFirstName().' '.$user->getLastName());
        $postData .= '&email='.urlencode ($user->getEmail());
        $postData .= '&custom_referralID='.urlencode ($user->getRefId());
        if ($user->getParentUserId() != '') {
            $postData .= '&custom_parentAffiliateID='.$user->getParentUserId();
        }

        Gpf_Log::info('AutoResponsePlus3: Data for the request have been gathered, sending...');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, "ARPAgent");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);

        Gpf_Log::info('AutoResponsePlus3: All data sent successfully. Plugin finished.');
        return true;
    }
}
?>
