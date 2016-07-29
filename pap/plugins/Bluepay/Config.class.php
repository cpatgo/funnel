<?php
/**
 *   @copyright Copyright (c) 2014 Quality Unit s.r.o.
 *   @author Juraj Simon
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
class Bluepay_Config extends Gpf_Plugins_Config {
    const CUSTOM_ID = 'BluepayHtmlCookieVariable';
    const CUSTOM_SEPARATOR = 'BluepayCustomSeparator';
    //const MERCHANT_SECRET_KEY = 'BluepayMerchantSecret';
    const CREATE_AFFILIATE = 'BluepayCreateAffiliate';
    
    protected function initFields() {
        $this->addTextBox($this->_('Custom field ID'), self::CUSTOM_ID, $this->_('BluePay lets us to use two custom fields - CUSTOM_ID and CUSTOM_ID2. Set just the number of field here (1 or 2).'));
        $this->addTextBox($this->_('Custom value separator'), self::CUSTOM_SEPARATOR, $this->_('Custom value separator must be set only in case you already use custom_ID or custom_ID2 fields for something else and you have to separate your value with the tracking value (visitor ID).'));
        //$this->addTextBox($this->_('Merchant\'s secret key'), self::MERCHANT_SECRET_KEY, $this->_('Merchant\'s secret key from your BluePay.'));
        $this->addCheckBox($this->_('Create affiliate'), self::CREATE_AFFILIATE, $this->_('When this checked, a new affiliate account will be created for the ordering customer, based on credentials sent from BleaPay submit form.'));
    }
    
    /**
     * @service plugin_config write
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function save(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        Gpf_Settings::set(self::CUSTOM_SEPARATOR, $form->getFieldValue(self::CUSTOM_SEPARATOR));
        //Gpf_Settings::set(self::MERCHANT_SECRET_KEY, $form->getFieldValue(self::MERCHANT_SECRET_KEY));
        Gpf_Settings::set(self::CREATE_AFFILIATE, $form->getFieldValue(self::CREATE_AFFILIATE));
        Gpf_Settings::set(self::CUSTOM_ID, $form->getFieldValue(self::CUSTOM_ID));
        
        if (!is_numeric(Gpf_Settings::get(self::CUSTOM_ID))) {
            $form->setErrorMessage($this->_('The custom field ID must be a number. Supported values are 1 or 2.'));
            return $form;
        }
        
        $form->setInfoMessage($this->_('Configuration saved'));
        return $form;
    }

    /**
     * @service plugin_config read
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function load(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        $form->addField(self::CUSTOM_SEPARATOR, Gpf_Settings::get(self::CUSTOM_SEPARATOR));
        $form->addField(self::CUSTOM_ID, Gpf_Settings::get(self::CUSTOM_ID));
        //$form->addField(self::MERCHANT_SECRET_KEY, Gpf_Settings::get(self::MERCHANT_SECRET_KEY));
        $form->addField(self::CREATE_AFFILIATE, Gpf_Settings::get(self::CREATE_AFFILIATE));
        return $form;
    }
}

?>
