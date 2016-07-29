<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @author Matej Kendera
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
class ccBill_Config extends Gpf_Plugins_Config {
    const REGISTER_AFFILIATE = 'ccBillRegisterAffiliate';
    const RECURRING_TOTALCOST_FROM_NOTIFICATION = 'ccBillRecurringTotalCostFromNotification';
    
    protected function initFields() {
        $this->addCheckBox($this->_("Register new affiliate with every occured event"), self::REGISTER_AFFILIATE, $this->_('When this checked, with every event new affiliate will be created from credentials that were set in ccBill submit form.'));
        $this->addCheckBox($this->_("Use recurring total cost from notification"), self::RECURRING_TOTALCOST_FROM_NOTIFICATION, $this->_('When this is checked, total cost from ipn notification for recurring commission is used insted of total cost from the initial payment.'));
    }
    
    /**
     * @service plugin_config write
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function save(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        Gpf_Settings::set(self::REGISTER_AFFILIATE, $form->getFieldValue(self::REGISTER_AFFILIATE));
        Gpf_Settings::set(self::RECURRING_TOTALCOST_FROM_NOTIFICATION, $form->getFieldValue(self::RECURRING_TOTALCOST_FROM_NOTIFICATION));
        $form->setInfoMessage($this->_('ccBill settings saved'));
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
        $form->addField(self::RECURRING_TOTALCOST_FROM_NOTIFICATION, Gpf_Settings::get(self::RECURRING_TOTALCOST_FROM_NOTIFICATION));
        return $form;
    }
}

?>
