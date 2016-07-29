<?php
/**
 *   @copyright Copyright (c) 2015 Quality Unit s.r.o.
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
class Stripe_Config extends Gpf_Plugins_Config {
    const API_KEY = 'StripeApiKey';
    const CUSTOM_SEPARATOR = 'StripeCustomSeparator';
    const TRACK_CHARGE_EVENT = 'StripeTrackChargeEvent';

    protected function initFields() {
        $this->addTextBox($this->_('Secret API Key'), self::API_KEY, $this->_("You can find your secret API key in your Stripe admin panel, in https://manage.stripe.com/account"));
        $this->addTextBox($this->_('Custom separator'), self::CUSTOM_SEPARATOR, $this->_("Custom separator should be set only in case you are already using customer 'description' field for something else than for integration with Post Affiliate Pro."));
        $this->addCheckBox($this->_('Track \'Charge\' event'), self::TRACK_CHARGE_EVENT, $this->_('By default, the plugin tracks commissions when \'invoice.payment_succeeded\' event is received. The checkbox will make the plugin to listen to \'charge.succeeded\' which is useful when you (your site/plugin) do not use invoices.'));
    }

    /**
     * @service plugin_config write
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function save(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);

        if (substr($form->getFieldValue(self::API_KEY), 0, 3) != 'sk_') {
            $form->setFieldError(self::API_KEY, $this->_('You did not use secret API key.'));
            return $form;
        }

        Gpf_Settings::set(self::CUSTOM_SEPARATOR, $form->getFieldValue(self::CUSTOM_SEPARATOR));
        Gpf_Settings::set(self::API_KEY, $form->getFieldValue(self::API_KEY));
        Gpf_Settings::set(self::TRACK_CHARGE_EVENT, $form->getFieldValue(self::TRACK_CHARGE_EVENT));
        $form->setInfoMessage($this->_('Stripe settings saved'));
        return $form;
    }

    /**
     * @service plugin_config read
     * @param Gpf_Rpc_Params $params
     * @return Gpf_Rpc_Form
     */
    public function load(Gpf_Rpc_Params $params) {
        $form = new Gpf_Rpc_Form($params);
        $form->addField(self::CUSTOM_SEPARATOR, Gpf_Settings::get(self::CUSTOM_SEPARATOR));
        $form->addField(self::API_KEY, Gpf_Settings::get(self::API_KEY));
        $form->addField(self::TRACK_CHARGE_EVENT, Gpf_Settings::get(self::TRACK_CHARGE_EVENT));
        return $form;
    }
}
