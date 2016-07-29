<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @author Maros Galik
 *   @package GwtPhpFramework
 *   @since Version 1.0.0
 *
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 1.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.postaffiliatepro.com/licenses/license
 *
 */

/**
 * @package GwtPhpFramework
 */
class GetResponseSignup_Config extends Gpf_Plugins_Config {
    const GETRESPONSE_API_KEY = 'getResponseApiKey';
    const GETRESPONSE_CAMPAIGN_NAME = 'getResponseCampaign';
    const CUSTOM_DATA_FIELDS = 'getResponseCustomDataFields';
    const INCLUDE_REFERRALID = 'getResponseIncludeRefIdPassword';
    const INCLUDE_PASSWORD = 'getResponseIncludePassword';
    const INCLUDE_PARENT_USER_ID = 'getResponseIncludeParentId';
    const CYCLE_DAY = 'getResponseCycleDay';
    const API_URL = 'http://api2.getresponse.com';
    const GETRESPONSE_360 = 'getResponse360';
    const GETRESPONSE_360_API_URL = 'getResponseApiUrl';
    
    protected function initFields() {
        $this->addTextBox($this->_("GetResponse API key"), self::GETRESPONSE_API_KEY, $this->_("Api key can be found after login to GetResponse in menu Account -> Edit settings in section Nr. 6 of this form"));
        $this->addTextBox($this->_("GetResponse campaign name"), self::GETRESPONSE_CAMPAIGN_NAME, $this->_("Campaign name defined in GetResponse account"));
        $this->addTextBox($this->_("Custom data fields"), self::CUSTOM_DATA_FIELDS, $this->_("Comma separated data1 - data25 fields which you want to fill in new getResponse contact."));
        $this->addCheckBox($this->_("Include affiliate referral id into custom data fields"), self::INCLUDE_REFERRALID, $this->_("If is checked, referral id will be added into customs fields as 'referralid'."));
        $this->addCheckBox($this->_("Include affiliate password into custom data fields"), self::INCLUDE_PASSWORD, $this->_("If is checked, password will be added into customs fields as 'affpassword'."));
        $this->addCheckBox($this->_("Include affiliate parent user Id into custom data fields"), self::INCLUDE_PARENT_USER_ID, $this->_("If is checked, parent user id will be added into customs fields as 'parentuserid'."));
        $this->addTextBox($this->_("cycle_day"), self::CYCLE_DAY, $this->_("Insert contact on a given day at the follow-up cycle. Value of 0 means the beginning of the cycle. Lack of this param means that a contact will not be inserted into cycle."));
        $this->addRadioBox('', self::GETRESPONSE_360, array(Gpf::NO => 'GetResponse', Gpf::YES => 'GetResponse360'));
        $this->addTextBox($this->_('GetResponse360 API URL'), self::GETRESPONSE_360_API_URL, $this->_('GetResponse360 users have unique URL and it will be provided to them by GetResponse Account Manager. If you are using common GetResponse (not GetResponse360) let this field empty.'));
    }
    
    /**
     * @service plugin_config write
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function save(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        $apiKey = $form->getFieldValue(self::GETRESPONSE_API_KEY);
        $campaignName = $form->getFieldValue(self::GETRESPONSE_CAMPAIGN_NAME);
        Gpf_Settings::set(self::GETRESPONSE_API_KEY, $apiKey);
        Gpf_Settings::set(self::GETRESPONSE_CAMPAIGN_NAME, $campaignName);
        Gpf_Settings::set(self::CUSTOM_DATA_FIELDS, $form->getFieldValue(self::CUSTOM_DATA_FIELDS));
        Gpf_Settings::set(self::INCLUDE_REFERRALID, $form->getFieldValue(self::INCLUDE_REFERRALID));
        Gpf_Settings::set(self::INCLUDE_PASSWORD, $form->getFieldValue(self::INCLUDE_PASSWORD));
        Gpf_Settings::set(self::INCLUDE_PARENT_USER_ID, $form->getFieldValue(self::INCLUDE_PARENT_USER_ID));
        Gpf_Settings::set(self::CYCLE_DAY, $form->getFieldValue(self::CYCLE_DAY));
        Gpf_Settings::set(self::GETRESPONSE_360, $form->getFieldValue(self::GETRESPONSE_360));
        Gpf_Settings::set(self::GETRESPONSE_360_API_URL, $form->getFieldValue(self::GETRESPONSE_360_API_URL));
        $form->setInfoMessage($this->_('GetResponseSignup plugin configuration saved'));
        return $form;
    }

    /**
     * @service plugin_config read
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function load(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        $form->addField(self::GETRESPONSE_API_KEY, Gpf_Settings::get(self::GETRESPONSE_API_KEY));
        $form->addField(self::GETRESPONSE_CAMPAIGN_NAME, Gpf_Settings::get(self::GETRESPONSE_CAMPAIGN_NAME));
        $form->addField(self::CUSTOM_DATA_FIELDS, Gpf_Settings::get(self::CUSTOM_DATA_FIELDS));
        $form->addField(self::INCLUDE_REFERRALID, Gpf_Settings::get(self::INCLUDE_REFERRALID));
        $form->addField(self::INCLUDE_PASSWORD, Gpf_Settings::get(self::INCLUDE_PASSWORD));
        $form->addField(self::INCLUDE_PARENT_USER_ID, Gpf_Settings::get(self::INCLUDE_PARENT_USER_ID));
        $form->addField(self::CYCLE_DAY, Gpf_Settings::get(self::CYCLE_DAY));
        $form->addField(self::GETRESPONSE_360, Gpf_Settings::get(self::GETRESPONSE_360));
        $form->addField(self::GETRESPONSE_360_API_URL, Gpf_Settings::get(self::GETRESPONSE_360_API_URL));
        return $form;
    }
}

?>
