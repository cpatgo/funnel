<?php
/**
 *   @copyright Copyright (c) 2014 Quality Unit s.r.o.
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
class PagSeguro_Config extends Gpf_Plugins_Config {
    const EMAIL = 'PagSeguroEmail';
    const TOKEN = 'PagSeguroToken';
    const REGISTER_AFFILIATE = 'PagSeguroRegisterAffiliate';
    const PROCESS_CART_PER_ITEM = 'PagSeguroProcessCartPerItem';
    const TEST_MODE = 'PagSeguroTestMode';
    
    protected function initFields() {
        $this->addTextBox($this->_("PagSeguro Email"), self::EMAIL, $this->_("An email address that you use to login to your PagSeguro admin."));
        $this->addTextBox($this->_("API Token"), self::TOKEN, $this->_("You can get it from \"Integrações\"> \"Token de segurança\" in your PagSeguro admin."));
        $this->addCheckBox($this->_("Register affiliate"), self::REGISTER_AFFILIATE, $this->_("When checked, the plugin will use customer details from the transaction to create an affiliate account."));
        $this->addCheckBox($this->_("Process cart per item"), self::PROCESS_CART_PER_ITEM, $this->_("Each item of the cart will be considered as a separate order. This is useful for 'per product' tracking."));
        $this->addCheckBox($this->_("Test mode"), self::TEST_MODE, $this->_("In case you are using the PagSeguro Sandbox, this option should be checked. Do not forget to change also email and API token to the test user."));
    }
    
    /**
     * @service plugin_config write
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function save(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        Gpf_Settings::set(self::EMAIL, $form->getFieldValue(self::EMAIL));
        Gpf_Settings::set(self::TOKEN, $form->getFieldValue(self::TOKEN));
        Gpf_Settings::set(self::REGISTER_AFFILIATE, $form->getFieldValue(self::REGISTER_AFFILIATE));
        Gpf_Settings::set(self::PROCESS_CART_PER_ITEM, $form->getFieldValue(self::PROCESS_CART_PER_ITEM));
        Gpf_Settings::set(self::TEST_MODE, $form->getFieldValue(self::TEST_MODE));
        
        if (($form->getFieldValue(self::EMAIL) == '') || ($form->getFieldValue(self::TOKEN) == '')) {
          $form->setErrorMessage($this->_('You have to enter an API token value and an email!'));
          return $form;
        }
        $form->setInfoMessage($this->_('PagSeguro settings saved'));
        return $form;
    }

    /**
     * @service plugin_config read
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function load(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        $form->addField(self::EMAIL, Gpf_Settings::get(self::EMAIL));
        $form->addField(self::TOKEN, Gpf_Settings::get(self::TOKEN));
        $form->addField(self::REGISTER_AFFILIATE, Gpf_Settings::get(self::REGISTER_AFFILIATE));
        $form->addField(self::PROCESS_CART_PER_ITEM, Gpf_Settings::get(self::PROCESS_CART_PER_ITEM));
        $form->addField(self::TEST_MODE, Gpf_Settings::get(self::TEST_MODE));
        return $form;
    }
}
?>
