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
 *   http://www.postaffiliatepro.com/licenses/license
 *
 */

/**
 * @package PostAffiliatePro plugins
 */
class BigCommerceAPI_Config extends Gpf_Plugins_Config {

    const API_USERNAME = 'BigCommerceAPIUsername';
    const API_PATH = 'BigCommerceAPIPath';
    const API_TOKEN = 'BigCommerceAPIToken';
    const PER_PRODUCT = 'BigCommercePerProduct';

    protected function initFields() {
        $this->addTextBox($this->_('API Username'), self::API_USERNAME, $this->_('All your API credentials can be found in your BigCommerce admin - Advanced Settings  > Legacy API Account > edit an account'));
        $this->addTextBox($this->_('API Path'), self::API_PATH, $this->_('All your API credentials can be found in your BigCommerce admin - Advanced Settings > Legacy API Account > edit an account'));
        $this->addTextBox($this->_('API Token'), self::API_TOKEN, $this->_('All your API credentials can be found in your BigCommerce admin - Advanced Settings > Legacy API Account > edit an account'));
        $this->addCheckBox($this->_('Per product tracking'), self::PER_PRODUCT, $this->_('If you want to track each product separately (per product tracking), this option has to be selected'));
    }

    /**
     * @service plugin_config write
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function save(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        Gpf_Settings::set(self::API_USERNAME, $form->getFieldValue(self::API_USERNAME));
        Gpf_Settings::set(self::API_PATH, $form->getFieldValue(self::API_PATH));
        Gpf_Settings::set(self::API_TOKEN, $form->getFieldValue(self::API_TOKEN));
        Gpf_Settings::set(self::PER_PRODUCT, $form->getFieldValue(self::PER_PRODUCT));
        $form->setInfoMessage($this->_('BigCommerce API settings saved'));
        return $form;
    }

    /**
     * @service plugin_config read
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function load(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        $form->addField(self::API_USERNAME, Gpf_Settings::get(self::API_USERNAME));
        $form->addField(self::API_PATH, Gpf_Settings::get(self::API_PATH));
        $form->addField(self::API_TOKEN, Gpf_Settings::get(self::API_TOKEN));
        $form->addField(self::PER_PRODUCT, Gpf_Settings::get(self::PER_PRODUCT));
        return $form;
    }
}

?>
