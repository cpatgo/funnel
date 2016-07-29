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
class LimeLight_Config extends Gpf_Plugins_Config {

    const REGISTER_AFFILIATE = 'LimeLightRegisterAffiliate';
    const DECLINE_AFFILIATE = 'LimeLightDeclineAffiliate';

    protected function initFields() {
        $this->addCheckBox($this->_("Register affiliate"), self::REGISTER_AFFILIATE, $this->_('This will create an affiliate account for a customer, based on the order details.'));
        $this->addCheckBox($this->_("Decline affiliate"), self::DECLINE_AFFILIATE, $this->_('This will decline an affiliate account of the customer, identified by customer email address.'));
    }

    /**
     * @service plugin_config write
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function save(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        Gpf_Settings::set(self::REGISTER_AFFILIATE, $form->getFieldValue(self::REGISTER_AFFILIATE));
        Gpf_Settings::set(self::DECLINE_AFFILIATE, $form->getFieldValue(self::DECLINE_AFFILIATE));
        
        $form->setInfoMessage($this->_('LimeLight settings saved'));
        return $form;
    }

    /**
     * @service plugin_config read
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function load(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        $form->addField(self::REGISTER_AFFILIATE, Gpf_Settings::get(self::REGISTER_AFFILIATE));
        $form->addField(self::DECLINE_AFFILIATE, Gpf_Settings::get(self::DECLINE_AFFILIATE));
        
        return $form;
    }
}
?>
