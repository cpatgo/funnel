<?php
/**
 *   @copyright Copyright (c) 2009 Quality Unit s.r.o.
 *   @author Maros Galik
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
 * @package GwtPhpFramework
 */
class BusinessCatalyst_Config extends Gpf_Plugins_Config {
    const LOGIN = 'BusinessCatalystLogin';
    const PASSWORD = 'BusinessCatalystPassword';
    const SITE_ID = 'BusinessCatalystSiteId';
    const PAP_CUSTOM_FIELD_NAME = 'BusinessCatalystCustomFieldName';
    const BC_DOMAIN_NAME = 'BusinessCatalystDomainName';
    const BC_LAST_CHECK = 'BusinessCatalystLastCheck';
    const BC_LAST_ENTITY_ID = 'BusinessCatalystLastEntityId';
    const BC_PER_PRODUCT = 'BusinessCatalystPerProductTracking';

    protected function initFields() {
        $this->addTextBox($this->_('BC Login'), self::LOGIN);
        $this->addPasswordTextBox($this->_('BC Password'), self::PASSWORD);
        $this->addTextBox($this->_('BC domain'), self::BC_DOMAIN_NAME, $this->_('Domain of your site in Business Catalyst. E.g. https://yourdomainname.worldsecuresystems.com/'));
        $this->addTextBox($this->_('BC API Site ID'), self::SITE_ID, $this->_('You can find this Site Id in Business Catalyst Admin panel - Api Integration'));
        $this->addTextBox($this->_('PAP Custom field name in BC'), self::PAP_CUSTOM_FIELD_NAME, $this->_('PAP custom field\'s name that you setup in Business Catalyst Admin Panel.'));
        $this->addTextBox($this->_('Last synchronization date'), self::BC_LAST_CHECK, $this->_('Do not change this unless you are migrating to another Business Catalyst account. If you are migrating set this to date from when you want to start synchronization.'));
        $this->addTextBox($this->_('Last processed entity Id'), self::BC_LAST_ENTITY_ID, $this->_('Do not change this unless you are migrating to another Business Catalyst account. If you are migrating set this to 0.'));
        $this->addCheckBox($this->_('Per product tracking'), self::BC_PER_PRODUCT, $this->_('When enabled, there will be a separate commission created per product ordered.'));
    }

    /**
     * @service plugin_config write
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function save(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);

        $form->addValidator(new Gpf_Rpc_Form_Validator_RegExpValidator('/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/',
        $this->_('Wrong DateTime format. Use: YYYY-MM-DD HH:MM:SS !')), self::BC_LAST_CHECK);
        if ($form->validate()) {
            Gpf_Settings::set(self::LOGIN, $form->getFieldValue(self::LOGIN));
            Gpf_Settings::set(self::PASSWORD, $form->getFieldValue(self::PASSWORD));
            Gpf_Settings::set(self::SITE_ID, $form->getFieldValue(self::SITE_ID));
            Gpf_Settings::set(self::PAP_CUSTOM_FIELD_NAME, $form->getFieldValue(self::PAP_CUSTOM_FIELD_NAME));
            Gpf_Settings::set(self::BC_DOMAIN_NAME, $form->getFieldValue(self::BC_DOMAIN_NAME));
            Gpf_Settings::set(self::BC_LAST_CHECK, self::getBcDateFormat($form->getFieldValue(self::BC_LAST_CHECK)));
            Gpf_Settings::set(self::BC_LAST_ENTITY_ID, $form->getFieldValue(self::BC_LAST_ENTITY_ID));
            Gpf_Settings::set(self::BC_PER_PRODUCT, $form->getFieldValue(self::BC_PER_PRODUCT));
            $form->setInfoMessage($this->_('Business Catalyst plugin settings saved'));
        }

        return $form;
    }

    /**
     * @service plugin_config read
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function load(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        $form->addField(self::LOGIN, Gpf_Settings::get(self::LOGIN));
        $form->addField(self::PASSWORD, Gpf_Settings::get(self::PASSWORD));
        $form->addField(self::SITE_ID, Gpf_Settings::get(self::SITE_ID));
        $form->addField(self::PAP_CUSTOM_FIELD_NAME, Gpf_Settings::get(self::PAP_CUSTOM_FIELD_NAME));
        $form->addField(self::BC_DOMAIN_NAME, Gpf_Settings::get(self::BC_DOMAIN_NAME));
        $form->addField(self::BC_LAST_CHECK, self::getPapDateFormat(Gpf_Settings::get(self::BC_LAST_CHECK)));
        $form->addField(self::BC_LAST_ENTITY_ID, Gpf_Settings::get(self::BC_LAST_ENTITY_ID));
        $form->addField(self::BC_PER_PRODUCT, Gpf_Settings::get(self::BC_PER_PRODUCT));
        return $form;
    }

    public static function getBcDateFormat($date) {
        return str_replace(' ', 'T', $date);
    }

    public static function getPapDateFormat($date) {
        return str_replace('T', ' ', $date);
    }
}

?>
