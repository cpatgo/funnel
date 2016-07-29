<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @author Martin Pullmann
 *   @package PostAffiliatePro
 *   @since Version 2.0.0
 *
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 2.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.postaffiliatepro.com/licenses/license
 *
 */

/**
 * @package PostAffiliatePro
 */
class PremiumWebCartIPN_Config extends Gpf_Plugins_Config {
    const CUSTOM_FIELD_NUMBER = 'PWCIPNCustomSeparator';
    const REGISTER_AFFILIATE = 'PWCIPNRegisterAffiliate';
    const APPROVE_AFFILIATE = 'PWCIPNApproveAffiliate';
    const PROCESS_WHOLE_CART_AS_ONE_TRANSACTION = 'PWCIPNProcessWholeCartAsOneTransaction';
    const USE_SKU = 'PWCIPNUseSKU';
    const MERCHANT_ID = 'PWCIPNMerchantId';
    const API_SIGNATURE = 'PWCIPNAPISignature';
    const RECURRING_USE_ORDERID_AS_SUBSCRID = 'PWCIPN_orderId_as_subscrid';

    protected function initFields() {
        $this->addListBox($this->_('Custom field'), self::CUSTOM_FIELD_NUMBER, array('1'=>'custom1','2'=>'custom2','3'=>'custom3','4'=>'custom4','5'=>'custom5'), $this->_('Custom field which you are using in integrated links (see Premium Web Cart integration description).'));
        $this->addCheckBox($this->_('Register new affiliate with every occured event'), self::REGISTER_AFFILIATE, $this->_('When this checked, with every event new affiliate will be created from credentials that were set in Premium Web Cart submit form.'));
        $this->addCheckBox($this->_('Process whole cart as one transaction'), self::PROCESS_WHOLE_CART_AS_ONE_TRANSACTION, $this->_('Process all items in cart as one transaction (not per product).'));
        $this->addCheckBox($this->_('Approve affiliate after successfull payment'), self::APPROVE_AFFILIATE, $this->_('When this is checked, every pending affiliate will be approved after successfull payment.'));
        $this->addCheckBox($this->_('Use SKU for product ID'), self::USE_SKU, $this->_('Whether to use SKU for product IDs or not. By default, product name is set as product ID.'));
        $this->addCheckBox($this->_('Use oderd_id as subscription ID'), self::RECURRING_USE_ORDERID_AS_SUBSCRID, $this->_('If you want to use odred_id as subscription ID instead of profile_id for recurring payments.'));
        $this->addTextBox($this->_('Merchant ID'), self::MERCHANT_ID, $this->_('Premium Web Cart Merchant ID must be set.'));
        $this->addTextBox($this->_('API Signature'), self::API_SIGNATURE, $this->_('Premium Web Cart API signature must be set.'));
    }

    /**
     * @service plugin_config write
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function save(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        Gpf_Settings::set(self::CUSTOM_FIELD_NUMBER, $form->getFieldValue(self::CUSTOM_FIELD_NUMBER));
        Gpf_Settings::set(self::REGISTER_AFFILIATE, $form->getFieldValue(self::REGISTER_AFFILIATE));
        Gpf_Settings::set(self::APPROVE_AFFILIATE, $form->getFieldValue(self::APPROVE_AFFILIATE));
        Gpf_Settings::set(self::PROCESS_WHOLE_CART_AS_ONE_TRANSACTION, $form->getFieldValue(self::PROCESS_WHOLE_CART_AS_ONE_TRANSACTION));
        Gpf_Settings::set(self::USE_SKU, $form->getFieldValue(self::USE_SKU));
        Gpf_Settings::set(self::MERCHANT_ID, $form->getFieldValue(self::MERCHANT_ID));
        Gpf_Settings::set(self::API_SIGNATURE, $form->getFieldValue(self::API_SIGNATURE));
        Gpf_Settings::set(self::RECURRING_USE_ORDERID_AS_SUBSCRID, $form->getFieldValue(self::RECURRING_USE_ORDERID_AS_SUBSCRID));

        if (Gpf_Settings::get(self::USE_SKU) == Gpf::YES) {
            if ((Gpf_Settings::get(self::MERCHANT_ID) == '') || (Gpf_Settings::get(self::API_SIGNATURE) == '')) {
                $form->setErrorMessage($this->_('If you want to use SKUs then you have to set all field values! Otherwise the plugin won\'t work.'));
                return $form;
            }
        }

        $form->setInfoMessage($this->_('PremiumWebCartIPN settings saved'));
        return $form;
    }

    /**
     * @service plugin_config read
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function load(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        $form->addField(self::CUSTOM_FIELD_NUMBER, Gpf_Settings::get(self::CUSTOM_FIELD_NUMBER));
        $form->addField(self::REGISTER_AFFILIATE, Gpf_Settings::get(self::REGISTER_AFFILIATE));
        $form->addField(self::PROCESS_WHOLE_CART_AS_ONE_TRANSACTION, Gpf_Settings::get(self::PROCESS_WHOLE_CART_AS_ONE_TRANSACTION));
        $form->addField(self::APPROVE_AFFILIATE, Gpf_Settings::get(self::APPROVE_AFFILIATE));
        $form->addField(self::USE_SKU, Gpf_Settings::get(self::USE_SKU));
        $form->addField(self::MERCHANT_ID, Gpf_Settings::get(self::MERCHANT_ID));
        $form->addField(self::API_SIGNATURE, Gpf_Settings::get(self::API_SIGNATURE));
        $form->addField(self::RECURRING_USE_ORDERID_AS_SUBSCRID, Gpf_Settings::get(self::RECURRING_USE_ORDERID_AS_SUBSCRID));
        return $form;
    }
}

?>
