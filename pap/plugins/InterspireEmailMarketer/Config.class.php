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
class InterspireEmailMarketer_Config extends Gpf_Plugins_Config {
    const XML_PATH = 'interspireEmailMarketerXMLPath';
    const USERNAME = 'interspireEmailMarketerUsername';
    const USERTOKEN = 'interspireEmailMarketerUsertoken';
    const MAILING_LIST = 'interspireEmailMarketerMailingList';
    const NAME_FIELD_ID = 'interspireEmailMarketerNameFieldId';
    
    protected function initFields() {
        $this->addTextBox($this->_('XML Path'), self::XML_PATH, $this->_('You can find in the ‘User Accounts -> Edit User’ section of Interspire Email Marketer under the ‘User Permissions’ tab. Make sure that you have ‘Enable the XML API’ checked and saved. The XML Path will look similar to the following: http://www.yourdomain.com/IEM/xml.php'));
        $this->addTextBox($this->_('username'), self::USERNAME, $this->_("Can be found in this same section under the title of ‘XML Username’."));
        $this->addTextBox($this->_('usertoken'), self::USERTOKEN, $this->_("Can be found in this same section under the title of ‘XML Token’."));
        $this->addTextBox($this->_('Mailinig list Id'), self::MAILING_LIST, $this->_('Id of mailing list where will be user registered.'));
        $this->addTextBox($this->_('ID of "Name" custom field'), self::NAME_FIELD_ID, $this->_('Id of "Name" custom field in InterspireEmailMarketer, by default it is "1".'));
    }
    
    /**
     * @service plugin_config write
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function save(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        Gpf_Settings::set(self::XML_PATH, $form->getFieldValue(self::XML_PATH));
        Gpf_Settings::set(self::USERNAME, $form->getFieldValue(self::USERNAME));
        Gpf_Settings::set(self::USERTOKEN, $form->getFieldValue(self::USERTOKEN));
        Gpf_Settings::set(self::MAILING_LIST, $form->getFieldValue(self::MAILING_LIST));
        Gpf_Settings::set(self::NAME_FIELD_ID, $form->getFieldValue(self::NAME_FIELD_ID));
        $form->setInfoMessage($this->_('Interspire Email Marketer plugin configuration saved'));
        return $form;
    }

    /**
     * @service plugin_config read
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function load(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        $form->addField(self::XML_PATH, Gpf_Settings::get(self::XML_PATH));
        $form->addField(self::USERNAME, Gpf_Settings::get(self::USERNAME));
        $form->addField(self::USERTOKEN, Gpf_Settings::get(self::USERTOKEN));
        $form->addField(self::MAILING_LIST, Gpf_Settings::get(self::MAILING_LIST));
        $form->addField(self::NAME_FIELD_ID, Gpf_Settings::get(self::NAME_FIELD_ID));
        return $form;
    }
}

?>
