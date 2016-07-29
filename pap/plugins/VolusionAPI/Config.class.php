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
 *   http://www.postaffiliatepro.com/licenses/license
 *
 */

/**
 * @package GwtPhpFramework
 */
class VolusionAPI_Config extends Gpf_Plugins_Config {
    const LOGIN = 'VolusionAPILogin';
    const PASS = 'VolusionAPIPass';
    const CUSTOM_NUMBER = 'VolusionAPICustomField';
    const VOLUSION_URL = 'VolusionAPIURL';
    const REGISTER_AFFILIATE = 'VolusionAPIRegisterAffiliate';
    const USE_COUPON = 'VolusionAPIUseCoupon';
    const REDUCE_TAX = 'VolusionAPITax';
    const REDUCE_SHIPPING = 'VolusionAPIShipping';
    const PER_PRODUCT = 'VolusionAPIPerProduct';

    protected function initFields() {
        $this->addTextBox($this->_("Volusion URL *"), self::VOLUSION_URL, $this->_("URL of your Volusion installation, with trailing slash, e.g. http://store.yoursite.com/"));
        $this->addTextBox($this->_("API login *"), self::LOGIN, $this->_("See Volusion documentation to find out where to get API login"));
        $this->addTextBox($this->_("API password *"), self::PASS, $this->_("See Volusion documentation to find out where to get API password"));
        $this->addTextBox($this->_("Custom field alias *"), self::CUSTOM_NUMBER, $this->_("In Volusion you can use 5 different custom fields. The alias of each defines how it is called in XML. If you are following the integration guide, this should be set to 'v'."));
        $this->addCheckBox($this->_("Use coupons"), self::USE_COUPON, $this->_('If checked, the plugin will search for coupon used during each order and will use it.'));
        $this->addCheckBox($this->_("Per product order"), self::PER_PRODUCT, $this->_('If checked, there will be a separate commission created for each product in order.'));
        $this->addCheckBox($this->_("Create affiliate on sale"), self::REGISTER_AFFILIATE, $this->_('If checked, a new affiliate will be created based on customer details entered during every order.'));
        $this->addCheckBox($this->_("Reduce tax"), self::REDUCE_TAX, $this->_('Commission will be computed from total cost value minus tax.'));
        $this->addCheckBox($this->_("Reduce shipping"), self::REDUCE_SHIPPING, $this->_('Commission will be computed from total cost value minus shipping cost.'));
    }

    /**
     * @service plugin_config write
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function save(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        Gpf_Settings::set(self::VOLUSION_URL, $form->getFieldValue(self::VOLUSION_URL));
        Gpf_Settings::set(self::LOGIN, $form->getFieldValue(self::LOGIN));
        Gpf_Settings::set(self::PASS, $form->getFieldValue(self::PASS));
        Gpf_Settings::set(self::CUSTOM_NUMBER, $form->getFieldValue(self::CUSTOM_NUMBER));
        Gpf_Settings::set(self::USE_COUPON, $form->getFieldValue(self::USE_COUPON));
        Gpf_Settings::set(self::PER_PRODUCT, $form->getFieldValue(self::PER_PRODUCT));
        Gpf_Settings::set(self::REGISTER_AFFILIATE, $form->getFieldValue(self::REGISTER_AFFILIATE));
        Gpf_Settings::set(self::REDUCE_TAX, $form->getFieldValue(self::REDUCE_TAX));
        Gpf_Settings::set(self::REDUCE_SHIPPING, $form->getFieldValue(self::REDUCE_SHIPPING));
        if ((Gpf_Settings::get(self::VOLUSION_URL) == '') || (Gpf_Settings::get(self::LOGIN) == '') || (Gpf_Settings::get(self::PASS) == '') || (Gpf_Settings::get(self::CUSTOM_NUMBER) == '')) {
            $form->setErrorMessage($this->_('You have to set all fields marked with asterisk (*)! Otherwise the plugin won\'t work.'));
        }
        $form->setInfoMessage($this->_('Volusion plugin settings saved'));
        return $form;
    }

    /**
     * @service plugin_config read
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function load(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        $form->addField(self::VOLUSION_URL, Gpf_Settings::get(self::VOLUSION_URL));
        $form->addField(self::LOGIN, Gpf_Settings::get(self::LOGIN));
        $form->addField(self::PASS, Gpf_Settings::get(self::PASS));
        $form->addField(self::CUSTOM_NUMBER, Gpf_Settings::get(self::CUSTOM_NUMBER));
        $form->addField(self::USE_COUPON, Gpf_Settings::get(self::USE_COUPON));
        $form->addField(self::PER_PRODUCT, Gpf_Settings::get(self::PER_PRODUCT));
        $form->addField(self::REGISTER_AFFILIATE, Gpf_Settings::get(self::REGISTER_AFFILIATE));
        $form->addField(self::REDUCE_TAX, Gpf_Settings::get(self::REDUCE_TAX));
        $form->addField(self::REDUCE_SHIPPING, Gpf_Settings::get(self::REDUCE_SHIPPING));
        return $form;
    }
}

?>
