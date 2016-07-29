<?php
/**
 *   @copyright Copyright (c) 2013 Quality Unit s.r.o.
 *   @author Martin Pulllmann
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
class ArpReach_Main extends Gpf_Plugins_Handler {
    
    public static function getHandlerInstance() {
        return new ArpReach_Main();
    }
    
    public function initSettings($context) {
        $context->addDbSetting(ArpReach_Config::SITE_URL, '');
        $context->addDbSetting(ArpReach_Config::FORM_ID, '');
        $context->addDbSetting(ArpReach_Config::AFF_TAG, '');
        $context->addDbSetting(ArpReach_Config::REF_TAG, '');
        $context->addDbSetting(ArpReach_Config::PARENT_TAG, '');
    }
    
    public function sendMail(Pap_Contexts_Signup $context) {
        $siteUrl = Gpf_Settings::get(ArpReach_Config::SITE_URL);
        $formId = Gpf_Settings::get(ArpReach_Config::FORM_ID);
        $newUser = $context->getUserObject();
        
        if (($siteUrl == '') || ($formId == '')) {
            $context->error('ArpReach: Please configure the ArpReach plugin! Some mandatory data are missing.');
            return false;
        }
        
        if (substr($siteUrl, -1) != '/') {
            $siteUrl .= '/';
        }
        $url = $siteUrl."a.php/sub/1/$formId";

        $postFields = array(
          'email_address' => $newUser->getEmail(),
          'first_name' => $newUser->getFirstName(),
          'last_name' => $newUser->getLastName(),
        );
        
        $afftag = Gpf_Settings::get(ArpReach_Config::AFF_TAG);
        if ($afftag != '') $postFields['custom_'.$afftag] = $newUser->getId();
        
        $reftag = Gpf_Settings::get(ArpReach_Config::REF_TAG);
        if ($reftag != '') $postFields['custom_'.$reftag] = $newUser->getRefId();
        
        $parenttag = Gpf_Settings::get(ArpReach_Config::PARENT_TAG);
        if ($parenttag != '') $postFields['custom_'.$parenttag] = $newUser->getParentUserId();
        
        $context->debug('ArpReach: Sending affiliate data to ArpReach URL: '.$url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, 'ARPR');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        $response = curl_exec($ch);
        curl_close($ch);
        $context->debug('ArpReach: cURL response after sending affiliate data: '.$response);
    }
}
?>
