<?php
/**
 *   @copyright Copyright (c) 2016 Quality Unit s.r.o.
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
 * @package PostAffiliatePro plugins
 */
class Braintree_Main extends Gpf_Plugins_Handler {

    /**
     * @return Braintree_Main
     */
    public static function getHandlerInstance() {
        return new Braintree_Main();
    }

    public function initSettings($context) {
        $context->addDbSetting(Braintree_Config::MERCHANT_ID, '');
        $context->addDbSetting(Braintree_Config::PRIVATE_KEY, '');
        $context->addDbSetting(Braintree_Config::PUBLIC_KEY, '');
        $context->addDbSetting(Braintree_Config::CUSTOM_FIELD_NAME, 'visitorid');
        $context->addDbSetting(Braintree_Config::ENVIRONMENT, '2');
        $context->addDbSetting(Braintree_Config::CREATE_AFFILIATE, '');
    }
}
