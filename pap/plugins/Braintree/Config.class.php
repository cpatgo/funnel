<?php
/**
 *   @copyright Copyright (c) 2016 Quality Unit s.r.o.
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
class Braintree_Config extends Gpf_Plugins_Config {

    const MERCHANT_ID = 'BraintreeMerchantID';
    const PUBLIC_KEY = 'BraintreePublicKey';
    const PRIVATE_KEY = 'BraintreePrivateKey';
    const CUSTOM_FIELD_NAME = 'BraintreeCustomFieldName';
    const ENVIRONMENT = 'BraintreeEnvironment';
    const CREATE_AFFILIATE = 'BraintreeCreateAffiliate';

    protected function initFields() {
        $this->addTextBox($this->_('Merchant ID'), self::MERCHANT_ID, $this->_('You can find your keys in your Braintree control panel, in Account> My user> API Keys, Tokenization Keys, Encryption Keys section'));
        $this->addTextBox($this->_('Public Key'), self::PUBLIC_KEY, $this->_('You can find your keys in your Braintree control panel, in Account> My user> API Keys, Tokenization Keys, Encryption Keys section'));
        $this->addTextBox($this->_('Private Key'), self::PRIVATE_KEY, $this->_('You can find your keys in your Braintree control panel, in Account> My user> API Keys, Tokenization Keys, Encryption Keys section'));
        $this->addTextBox($this->_('Custom field name'), self::CUSTOM_FIELD_NAME, $this->_('Set by the integration method - the first step. This is set to "visitorid" by default, so if you have to use something else for some reason, you can change it here.'));
        $select = array(
                '1' => 'Sandbox',
                '2' => 'Live'
        );
        $this->addListBox($this->_('Environment'), self::ENVIRONMENT, $select);
        $this->addCheckBox($this->_('Create affiliate'), self::CREATE_AFFILIATE, $this->_('When enabled, the plugin will create an affiliate account automatically using payer details.'));
    }

    /**
     * @service plugin_config write
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function save(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);

        if ($form->getFieldValue(self::CUSTOM_FIELD_NAME) == '') {
            $form->setFieldError(self::CUSTOM_FIELD_NAME, $this->_('Custom field name cannot be empty! You can use the default value "visitorid"'));
            return $form;
        }

        Gpf_Settings::set(self::MERCHANT_ID, $form->getFieldValue(self::MERCHANT_ID));
        Gpf_Settings::set(self::PUBLIC_KEY, $form->getFieldValue(self::PUBLIC_KEY));
        Gpf_Settings::set(self::PRIVATE_KEY, $form->getFieldValue(self::PRIVATE_KEY));
        Gpf_Settings::set(self::ENVIRONMENT, $form->getFieldValue(self::ENVIRONMENT));
        Gpf_Settings::set(self::CREATE_AFFILIATE, $form->getFieldValue(self::CREATE_AFFILIATE));


        Gpf_Settings::set(self::CUSTOM_FIELD_NAME, $form->getFieldValue(self::CUSTOM_FIELD_NAME));

        $form->setInfoMessage($this->_('Settings saved'));
        return $form;
    }

    /**
     * @service plugin_config read
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function load(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        $form->addField(self::MERCHANT_ID, Gpf_Settings::get(self::MERCHANT_ID));
        $form->addField(self::PUBLIC_KEY, Gpf_Settings::get(self::PUBLIC_KEY));
        $form->addField(self::PRIVATE_KEY, Gpf_Settings::get(self::PRIVATE_KEY));
        $form->addField(self::ENVIRONMENT, Gpf_Settings::get(self::ENVIRONMENT));
        $form->addField(self::CUSTOM_FIELD_NAME, Gpf_Settings::get(self::CUSTOM_FIELD_NAME));
        $form->addField(self::CREATE_AFFILIATE, Gpf_Settings::get(self::CREATE_AFFILIATE));
        return $form;
    }
}
