<?php
/**
 *   @copyright Copyright (c) 2013 Quality Unit s.r.o.
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
class MemberMouse_Config extends Gpf_Plugins_Config {

    const CUSTOM_FIELD = 'MemberMouseCustomField';
    const CREATE_AFFILIATE = 'MemberMouseCreateAffiliate';
    const CHANGE_AFFILIATE_STATUS = 'MemberMouseChangeAffiliateStatus';
    const PER_PRODUCT_TRANSACTION = 'MemberMousePerProductTransaction';
    const PROCESS_REFUND = 'MemberMouseProcessRefund';
    const USE_COUPON = 'MemberMouseUseCoupon';

    protected function initFields() {
        $this->addTextBox($this->_("Custom field"), self::CUSTOM_FIELD, $this->_("Custom field ID - check the integration instructions for MemberMouse to see how to find out the field ID"));
        $this->addCheckBox($this->_("Create affiliate"), self::CREATE_AFFILIATE, $this->_('An affiliate account will be created based on member details received by MemberMouse.'));
        $this->addCheckBox($this->_("Change affiliate status"), self::CHANGE_AFFILIATE_STATUS, $this->_('The affiliate status will be changed based on status change received by MemberMouse.'));
        $this->addCheckBox($this->_("Per product tracking"), self::PER_PRODUCT_TRANSACTION, $this->_('When this option is checked, the system will use each item ordered as a separate order.'));
        $this->addCheckBox($this->_("Process refund"), self::PROCESS_REFUND, $this->_('Refunds will be processed so commissions could be refunded too.'));
        $this->addCheckBox($this->_("Use coupon"), self::USE_COUPON, $this->_('If there is a coupon in the order, it will be included in the sale tracker.'));
    }

    /**
     * @service plugin_config write
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function save(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        if ($form->getFieldValue(self::CUSTOM_FIELD) == '') {
            $form->setErrorMessage($this->_('You have to set the Custom field value.'));
            return $form;
        }
        
        Gpf_Settings::set(self::CUSTOM_FIELD, $form->getFieldValue(self::CUSTOM_FIELD));
        Gpf_Settings::set(self::CREATE_AFFILIATE, $form->getFieldValue(self::CREATE_AFFILIATE));
        Gpf_Settings::set(self::CHANGE_AFFILIATE_STATUS, $form->getFieldValue(self::CHANGE_AFFILIATE_STATUS));
        Gpf_Settings::set(self::PER_PRODUCT_TRANSACTION, $form->getFieldValue(self::PER_PRODUCT_TRANSACTION));
        Gpf_Settings::set(self::PROCESS_REFUND, $form->getFieldValue(self::PROCESS_REFUND));
        Gpf_Settings::set(self::USE_COUPON, $form->getFieldValue(self::USE_COUPON));
        
        $form->setInfoMessage($this->_('MemberMouse settings saved'));
        return $form;
    }

    /**
     * @service plugin_config read
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function load(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        $form->addField(self::CUSTOM_FIELD, Gpf_Settings::get(self::CUSTOM_FIELD));
        $form->addField(self::CREATE_AFFILIATE, Gpf_Settings::get(self::CREATE_AFFILIATE));
        $form->addField(self::CHANGE_AFFILIATE_STATUS, Gpf_Settings::get(self::CHANGE_AFFILIATE_STATUS));
        $form->addField(self::PER_PRODUCT_TRANSACTION, Gpf_Settings::get(self::PER_PRODUCT_TRANSACTION));
        $form->addField(self::PROCESS_REFUND, Gpf_Settings::get(self::PROCESS_REFUND));
        $form->addField(self::USE_COUPON, Gpf_Settings::get(self::USE_COUPON));
        return $form;
    }
}

?>
