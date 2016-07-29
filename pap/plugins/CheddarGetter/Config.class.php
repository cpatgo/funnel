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
 * @package PostAffiliatePro plugins
 */
class CheddarGetter_Config extends Gpf_Plugins_Config {
    const COOKIE_FIELD = 'CheddarGetterCookieField';
    const REGISTER_AFFILIATE = 'CheddarGetterRegisterAffiliate';
    const DECLINE_AFFILIATE = 'CheddarGetterDeclineAffiliate';

    protected function initFields() {
        $this->addTextBox($this->_("Cookie field name"), self::COOKIE_FIELD, $this->_("Name of the field in customer meta data which you set for tracking cookie - see integration with CheddarGetter. By default we use 'pap_custom' name."));
        $this->addCheckBox($this->_("Create affiliate"), self::REGISTER_AFFILIATE, $this->_('There will be a new affiliate account created for the paying customer, when this option is checked.'));
        $this->addCheckBox($this->_("Decline affiliate"), self::DECLINE_AFFILIATE, $this->_('Affiliate account of the paying customer will be decline when the subscription is cancelled.'));
    }

    /**
     * @service plugin_config write
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function save(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        Gpf_Settings::set(self::COOKIE_FIELD, $form->getFieldValue(self::COOKIE_FIELD));
        Gpf_Settings::set(self::REGISTER_AFFILIATE, $form->getFieldValue(self::REGISTER_AFFILIATE));
        Gpf_Settings::set(self::DECLINE_AFFILIATE, $form->getFieldValue(self::DECLINE_AFFILIATE));

        $form->setInfoMessage($this->_('CheddarGetter settings saved'));
        return $form;
    }

    /**
     * @service plugin_config read
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function load(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        $form->addField(self::COOKIE_FIELD, Gpf_Settings::get(self::COOKIE_FIELD));
        $form->addField(self::REGISTER_AFFILIATE, Gpf_Settings::get(self::REGISTER_AFFILIATE));
        $form->addField(self::DECLINE_AFFILIATE, Gpf_Settings::get(self::DECLINE_AFFILIATE));
        return $form;
    }
}

?>
