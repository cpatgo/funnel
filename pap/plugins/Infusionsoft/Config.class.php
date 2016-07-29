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

/**
 * @package PostAffiliatePro plugins
 */
class Infusionsoft_Config extends Gpf_Plugins_Config {

    const API_KEY = 'InfusionsoftAPIKey';
    const SUBDOMAIN = 'InfusionsoftSubdomain';

    protected function initFields() {
        $this->addTextBox($this->_("API Key"), self::API_KEY, $this->_("See the integration method to find out where to get the API Encrypted key"));
        $this->addTextBox($this->_("Account name"), self::SUBDOMAIN, $this->_("Your Infusionsoft account name (subdomain), found in Your Accounts section right after you login to your Infusionsoft."));
    }

    /**
     * @service plugin_config write
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function save(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        Gpf_Settings::set(self::API_KEY, $form->getFieldValue(self::API_KEY));
        Gpf_Settings::set(self::SUBDOMAIN, $form->getFieldValue(self::SUBDOMAIN));
        $form->setInfoMessage($this->_('Infusionsoft settings saved'));
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
        $form->addField(self::SUBDOMAIN, Gpf_Settings::get(self::SUBDOMAIN));
        return $form;
    }
}

?>
