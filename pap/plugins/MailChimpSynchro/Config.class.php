<?php
/**
 *   @copyright Copyright (c) 2012 Quality Unit s.r.o.
 *   @author Martin Pullmann
 *   @package PostAffiliatePro
 *
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 1.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.postaffiliatepro.com/licenses/license
 */

class MailChimpSynchro_Config extends Gpf_Plugins_Config {

    const API_KEY = 'MailChimpApiKey';
    const SECRET_KEY = 'MailChimpSecretKey';
    const LIST_ID = 'MailChimpListId';
    const SECURE = 'MailChimpSecure';
    const ADD_NEW = 'MailChimpAddNew';
    const DOUBLE_OPTIN = 'MailChimpDoubleOpt';

    protected function initFields() {
        $this->addTextBox($this->_('MailChimp API key'), self::API_KEY, $this->_('You have to enter a valid API Key so the system can communicate with MailChimp. Get a key at <a target="_parent" href="http://admin.mailchimp.com/account/api">http://admin.mailchimp.com/account/api<a/>'));
        $this->addTextBox($this->_('Secret key for webhooks'), self::SECRET_KEY, $this->_('The secret key will be used to identify a valid data sent from your MailChimp webhook. The key can be any string, e.g. MyPass007'));
        $this->addTextBox($this->_('List ID'), self::LIST_ID, $this->_('ID of the list you want an affiliate to be added to (updated in). In case you want to use more lists, separate it with semicolon. <a target="_parent" href="http://kb.mailchimp.com/article/how-can-i-find-my-list-id">How to find my list ID?</a>'));
        $this->addCheckBox($this->_('Secure connection'), self::SECURE, $this->_('Whether or not a secure connection should be used for communication with MailChimp.'));
        $values = array(
                Gpf::NO => $this->_('Only update existsing users'),
                Gpf::YES => $this->_('Add all new affiliates'),
                Pap_Common_Constants::STATUS_APPROVED => $this->_('Add only approved affiliates')
        );
        $this->addRadioBox($this->_('Add affiliates to MailChimp'), self::ADD_NEW, $values, $this->_('Choose if all or only approved affiliates will be added into your MailChimp list.'));
        $this->addCheckBox($this->_('Double opt-in'), self::DOUBLE_OPTIN, $this->_('By default double opt-in confirmation message is sent.'));
    }

    /**
     * @service plugin_config write
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function save(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);

        Gpf_Settings::set(self::API_KEY, $form->getFieldValue(self::API_KEY));
        Gpf_Settings::set(self::SECRET_KEY, $form->getFieldValue(self::SECRET_KEY));
        Gpf_Settings::set(self::LIST_ID, $form->getFieldValue(self::LIST_ID));
        Gpf_Settings::set(self::SECURE, $form->getFieldValue(self::SECURE));
        Gpf_Settings::set(self::ADD_NEW, $form->getFieldValue(self::ADD_NEW));
        Gpf_Settings::set(self::DOUBLE_OPTIN, $form->getFieldValue(self::DOUBLE_OPTIN));

        if ((Gpf_Settings::get(self::API_KEY) == '') || (Gpf_Settings::get(self::LIST_ID) == '')) {
            $form->setErrorMessage($this->_('You have to set all field values! Otherwise the plugin won\'t work.'));
            return $form;
        }

        if (Gpf_Settings::get(self::SECRET_KEY) == '') {
            $form->setErrorMessage($this->_('Please enter a nonempty string into Secret key field. For security reasond, this is required.'));
            return $form;
        }

        $form->setInfoMessage($this->_('Settings saved'));
        return $form;
    }

    /**
     * @service plugin_config read
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function load(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);

        $form->addField(self::API_KEY, Gpf_Settings::get(self::API_KEY));
        $form->addField(self::SECRET_KEY, Gpf_Settings::get(self::SECRET_KEY));
        $form->addField(self::LIST_ID, Gpf_Settings::get(self::LIST_ID));
        $form->addField(self::SECURE, Gpf_Settings::get(self::SECURE));
        $form->addField(self::ADD_NEW, Gpf_Settings::get(self::ADD_NEW));
        $form->addField(self::DOUBLE_OPTIN, Gpf_Settings::get(self::DOUBLE_OPTIN));

        return $form;
    }
}
?>
