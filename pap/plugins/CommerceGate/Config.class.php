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
 *   http://www.qualityunit.com/licenses/license
 *
 */

/**
 * @package PostAffiliatePro
 */
class CommerceGate_Config extends Gpf_Plugins_Config {
    const CUSTOM_FIELD = 'CommerceGateCustomField';
    const REGISTER_AFFILIATE = 'CommerceGateRegisterAffiliate';
    const DECLINE_AFFILIATE = 'CommerceGateDeclineAffiliate';

    protected function initFields() {
        $this->addTextBox($this->_("Custom field number"), self::CUSTOM_FIELD, $this->_("Number of custom field you are using for integration. There are three ('op1', 'op2', 'op3') so enter the number of the one you are using. E.g. if you are using 'op1' then the value for this field is 1."));
        $this->addCheckBox($this->_("Register affiliates"), self::REGISTER_AFFILIATE, $this->_('When checked, there will be a new affiliate account created for buyer, based on order details.'));
        $this->addCheckBox($this->_("Decline affiliates"), self::DECLINE_AFFILIATE, $this->_('When checked, the affiliate account will be declined in case the customer is also an affiliate and the payment for membership was cancelled or refunded.'));
    }

    /**
     * @service plugin_config write
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function save(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        Gpf_Settings::set(self::CUSTOM_FIELD, $form->getFieldValue(self::CUSTOM_FIELD));
        Gpf_Settings::set(self::REGISTER_AFFILIATE, $form->getFieldValue(self::REGISTER_AFFILIATE));
        Gpf_Settings::set(self::DECLINE_AFFILIATE, $form->getFieldValue(self::DECLINE_AFFILIATE));
        $form->setInfoMessage($this->_('CommerceGate settings saved'));
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
        $form->addField(self::REGISTER_AFFILIATE, Gpf_Settings::get(self::REGISTER_AFFILIATE));
        $form->addField(self::DECLINE_AFFILIATE, Gpf_Settings::get(self::DECLINE_AFFILIATE));
        return $form;
    }
}
?>
