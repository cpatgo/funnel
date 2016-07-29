<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @author Milos Jancovic  (created by Rick Braddy / WinningWare.com for PostAffiliatePro)
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
 * @package PostAffiliatePro
 */
class AWeber_Config extends Gpf_Plugins_Config {    
    
    const AUTORESPONDER_ADDRESS = 'aweber_autoresponder_address';
    const AUTORESPONDER_ADD_REFID = 'aweber_autoresponder_add_refid';
    const AUTORESPONDER_REFID_FIELDNAME = 'aweber_autoresponder_refid_fieldname';
    
    protected function initFields() {
        $this->addTextBox($this->_('Autoresponder'), self::AUTORESPONDER_ADDRESS, $this->_('AWeber autoresponder\'s subscription address; mylist@aweber.com. More subscription addresses can be comma separated.'));
        $this->addCheckBox($this->_('Send Referral ID'), self::AUTORESPONDER_ADD_REFID, $this->_('If this is checked also the affiliate\'s Referral ID is sent to AWeber'));
        $this->addTextBox($this->_('Referral ID field name'), self::AUTORESPONDER_REFID_FIELDNAME, $this->_('If \'Send Referral ID\' is enabled, this field name is used for it.'));
    }
    
    /**
     * @service plugin_config write
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function save(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        Gpf_Settings::set(self::AUTORESPONDER_ADD_REFID, $form->getFieldValue(self::AUTORESPONDER_ADD_REFID));
        Gpf_Settings::set(self::AUTORESPONDER_REFID_FIELDNAME, $form->getFieldValue(self::AUTORESPONDER_REFID_FIELDNAME));
        Gpf_Settings::set(self::AUTORESPONDER_ADDRESS, $form->getFieldValue(self::AUTORESPONDER_ADDRESS));
        $form->setInfoMessage($this->_('AWeber saved'));
        return $form;
    }

    /**
     * @service plugin_config read
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function load(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        $form->addField(self::AUTORESPONDER_ADDRESS, Gpf_Settings::get(self::AUTORESPONDER_ADDRESS));
        $form->addField(self::AUTORESPONDER_ADD_REFID, Gpf_Settings::get(self::AUTORESPONDER_ADD_REFID));
        $form->addField(self::AUTORESPONDER_REFID_FIELDNAME, Gpf_Settings::get(self::AUTORESPONDER_REFID_FIELDNAME));
        return $form;
    }
}

?>
