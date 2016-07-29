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
class ArpReach_Config extends Gpf_Plugins_Config {
    const SITE_URL = 'ArpReachSiteUrl';
    const FORM_ID = 'ArpReachFormId';
    const AFF_TAG = 'ArpReachAffTag';
    const REF_TAG = 'ArpReachRefTag';
    const PARENT_TAG = 'ArpReachParentTag';
    
    protected function initFields() {
        $this->addTextBox($this->_('Site URL'), self::SITE_URL, $this->_('If your ArpReach is installed at http://yoursite.com/arp/ then that is the URL of this field.'));
        $this->addTextBox($this->_('Form ID'), self::FORM_ID, $this->_('ID of the form you want affiliates to be added to automatically.'));
        $this->addTextBox($this->_('Tag for Affiliate ID'), self::AFF_TAG, $this->_('The tag of the custom field you created in ArpReach for affiliate ID. This field is optional, if nothing is set, the affiliate ID won\'t be sent to ArpReach'));
        $this->addTextBox($this->_('Tag for Refferal ID'), self::REF_TAG, $this->_('The tag of the custom field you created in ArpReach for referral ID. This field is optional, if nothing is set, the referral ID won\'t be sent to ArpReach'));
        $this->addTextBox($this->_('Tag for Parent ID'), self::PARENT_TAG, $this->_('The tag of the custom field you created in ArpReach for parent affiliate ID. This field is optional, if nothing is set, the affiliate ID won\'t be sent to ArpReach'));
    }
    
    /**
     * @service plugin_config write
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function save(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        if (($form->getFieldValue(self::SITE_URL) == '') || (strpos($form->getFieldValue(self::SITE_URL), 'http') === false) ) {
            $form->setErrorMessage($this->_('The URL is missing or has a wrong format!'));
            return $form;
        }
        
        Gpf_Settings::set(self::SITE_URL, $form->getFieldValue(self::SITE_URL));
        Gpf_Settings::set(self::FORM_ID, $form->getFieldValue(self::FORM_ID));
        Gpf_Settings::set(self::AFF_TAG, $form->getFieldValue(self::AFF_TAG));
        Gpf_Settings::set(self::REF_TAG, $form->getFieldValue(self::REF_TAG));
        Gpf_Settings::set(self::PARENT_TAG, $form->getFieldValue(self::PARENT_TAG));

        $form->setInfoMessage($this->_('ArpReach plugin saved'));
        return $form;
    }

    /**
     * @service plugin_config read
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function load(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        $form->addField(self::SITE_URL, Gpf_Settings::get(self::SITE_URL));
        $form->addField(self::FORM_ID, Gpf_Settings::get(self::FORM_ID));
        $form->addField(self::AFF_TAG, Gpf_Settings::get(self::AFF_TAG));
        $form->addField(self::REF_TAG, Gpf_Settings::get(self::REF_TAG));
        $form->addField(self::PARENT_TAG, Gpf_Settings::get(self::PARENT_TAG));

        return $form;
    }
}
?>
