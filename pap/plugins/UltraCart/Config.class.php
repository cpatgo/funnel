<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @author Michal Bebjak
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
class UltraCart_Config extends Gpf_Plugins_Config {
    const CUSTOM_FIELD_NUMBER = 'UltraCartCustomFieldNumber';
    const SHIPPING_HANDLING_SUBSTRACT = 'UltraCartShippingHandlingSubstract';
    const WHOLE_CART_AS_ONE = 'UltraCartWholeCartAsOneTransaction';
    const REGISTER_AFFILIATE = 'UltraCartRegisterAffiliate';
    
    protected function initFields() {
        $this->addListBox($this->_("Custom field number (1-5)"), self::CUSTOM_FIELD_NUMBER, array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'), $this->_("Custom field number that can be used by %s", Gpf_Settings::get(Pap_Settings::BRANDING_QUALITYUNIT_PAP)) . ".");        
        $this->addCheckBox($this->_("Substract shipping and handling from total cost"), self::SHIPPING_HANDLING_SUBSTRACT, $this->_("Subtotal value will be used as total cost. Subtotal cost is total cost value without shipping and handling cost"));
        $this->addCheckBox($this->_("Process whole cart as one transaction"), self::WHOLE_CART_AS_ONE, $this->_("If this is checked, the order total (subtotal) will be used to creat a commission. If not checked, each item of the shopping cart will be used to create a separate commission."));
        $this->addCheckBox($this->_("Register affiliate"), self::REGISTER_AFFILIATE, $this->_("If this is checked, there will be an affiliate account created for the ordering customer. Details from the order will be used."));
    }
    
    /**
     * @service plugin_config write
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function save(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        $customFieldNumber = (integer)$form->getFieldValue(self::CUSTOM_FIELD_NUMBER);
        if ($customFieldNumber < 1 || $customFieldNumber > 5) {
            $form->setFieldError(self::CUSTOM_FIELD_NUMBER, $this->_('Custom field number must be from range 1-5.'));
            return $form;
        }
        Gpf_Settings::set(self::CUSTOM_FIELD_NUMBER, $customFieldNumber);
        Gpf_Settings::set(self::SHIPPING_HANDLING_SUBSTRACT, $form->getFieldValue(self::SHIPPING_HANDLING_SUBSTRACT));
        Gpf_Settings::set(self::WHOLE_CART_AS_ONE, $form->getFieldValue(self::WHOLE_CART_AS_ONE));
        Gpf_Settings::set(self::REGISTER_AFFILIATE, $form->getFieldValue(self::REGISTER_AFFILIATE));
        $form->setInfoMessage($this->_('UltraCart plugin configuration saved'));
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
        $form->addField(self::SHIPPING_HANDLING_SUBSTRACT, Gpf_Settings::get(self::SHIPPING_HANDLING_SUBSTRACT));
        $form->addField(self::WHOLE_CART_AS_ONE, Gpf_Settings::get(self::WHOLE_CART_AS_ONE));
        $form->addField(self::REGISTER_AFFILIATE, Gpf_Settings::get(self::REGISTER_AFFILIATE));
        return $form;
    }
}
