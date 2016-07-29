<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
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
class TwoCheckout_Config extends Gpf_Plugins_Config {
    const CUSTOM_SEPARATOR = 'TwoCheckoutCustomSeparator';
    const PROCESS_WHOLE_CART_AS_ONE_TRANSACTION = 'TwoCheckoutProcessWholeCartAsOneTransaction';
    const REGISTER_AFFILIATE = 'TwoCheckoutRegisterAffiliate';
    const DECLINE_AFFILIATE = 'TwoCheckoutDeclineAffiliate';
    const COUPON_TRACKING = 'TwoCheckoutTrackCoupons';
    const API_USERNAME = 'TwoCheckoutAPIUsername';
    const API_PASSWORD = 'TwoCheckoutAPIPass';
    
    protected function initFields() {
        $this->addCheckBox($this->_('Process whole cart as one transaction'), self::PROCESS_WHOLE_CART_AS_ONE_TRANSACTION, $this->_('Process all items in cart as one big transaction.'));
        $this->addCheckBox($this->_('Register affiliate'), self::REGISTER_AFFILIATE, $this->_('Create an affiliate account for customers from orders automatically.'));
        $this->addCheckBox($this->_('Decline affiliate'), self::DECLINE_AFFILIATE, $this->_('If the payer is also an affiliate, decline the account when recurring payment was stopped, cancelled or refunded.'));
        $this->addTextBox($this->_('Custom value separator'), self::CUSTOM_SEPARATOR, $this->_('Custom value separator must be set.'));
        $this->addCheckBox($this->_('Track coupons'), self::COUPON_TRACKING, $this->_('For coupon tracking, you have to set also 2CO API username and password.'));
        $this->addTextBox($this->_('API username'), self::API_USERNAME, $this->_('Your 2CO API username. <a href="https://www.2checkout.com/documentation/api/" target="_parent">More info</a>'));
        $this->addTextBox($this->_('API password'), self::API_PASSWORD, $this->_('Your 2CO API password. <a href="https://www.2checkout.com/documentation/api/" target="_parent">More info</a>'));
    }
    
    /**
     * @service plugin_config write
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function save(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        Gpf_Settings::set(self::PROCESS_WHOLE_CART_AS_ONE_TRANSACTION, $form->getFieldValue(self::PROCESS_WHOLE_CART_AS_ONE_TRANSACTION));
        Gpf_Settings::set(self::CUSTOM_SEPARATOR, $form->getFieldValue(self::CUSTOM_SEPARATOR));
        Gpf_Settings::set(self::REGISTER_AFFILIATE, $form->getFieldValue(self::REGISTER_AFFILIATE));
        Gpf_Settings::set(self::DECLINE_AFFILIATE, $form->getFieldValue(self::DECLINE_AFFILIATE));
        Gpf_Settings::set(self::COUPON_TRACKING, $form->getFieldValue(self::COUPON_TRACKING));
        Gpf_Settings::set(self::API_USERNAME, $form->getFieldValue(self::API_USERNAME));
        Gpf_Settings::set(self::API_PASSWORD, $form->getFieldValue(self::API_PASSWORD));

        if ($form->getFieldValue(self::COUPON_TRACKING) == Gpf::YES &&
            ( ($form->getFieldValue(self::API_USERNAME) == '') || ($form->getFieldValue(self::API_PASSWORD) == '') )) {
            $form->setErrorMessage($this->_('You have to set your API credentials to track coupons!'));
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
        $form->addField(self::PROCESS_WHOLE_CART_AS_ONE_TRANSACTION, Gpf_Settings::get(self::PROCESS_WHOLE_CART_AS_ONE_TRANSACTION));
        $form->addField(self::CUSTOM_SEPARATOR, Gpf_Settings::get(self::CUSTOM_SEPARATOR));
        $form->addField(self::REGISTER_AFFILIATE, Gpf_Settings::get(self::REGISTER_AFFILIATE));
        $form->addField(self::DECLINE_AFFILIATE, Gpf_Settings::get(self::DECLINE_AFFILIATE));
        $form->addField(self::COUPON_TRACKING, Gpf_Settings::get(self::COUPON_TRACKING));
        $form->addField(self::API_USERNAME, Gpf_Settings::get(self::API_USERNAME));
        $form->addField(self::API_PASSWORD, Gpf_Settings::get(self::API_PASSWORD));
        return $form;
    }
}
