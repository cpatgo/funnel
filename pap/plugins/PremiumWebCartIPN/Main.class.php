<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @author Martin Pullmann
 *   @package PostAffiliatePro
 *   @since Version 2.0.0
 *
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 2.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.postaffiliatepro.com/licenses/license
 *
 */

/**
 * @package PostAffiliatePro plugins
 */
class PremiumWebCartIPN_Main extends Gpf_Plugins_Handler {

    /**
     * @return PremiumWebCartIPN_Main
     */
    public static function getHandlerInstance() {
        return new PremiumWebCartIPN_Main();
    }

    public function initSettings($context) {
        $context->addDbSetting(PremiumWebCartIPN_Config::CUSTOM_FIELD_NUMBER, '1');
        $context->addDbSetting(PremiumWebCartIPN_Config::REGISTER_AFFILIATE, '');
        $context->addDbSetting(PremiumWebCartIPN_Config::APPROVE_AFFILIATE, '');
        $context->addDbSetting(PremiumWebCartIPN_Config::PROCESS_WHOLE_CART_AS_ONE_TRANSACTION, Gpf::YES);
        $context->addDbSetting(PremiumWebCartIPN_Config::USE_SKU, '');
        $context->addDbSetting(PremiumWebCartIPN_Config::MERCHANT_ID, '');
        $context->addDbSetting(PremiumWebCartIPN_Config::API_SIGNATURE, '');
        $context->addDbSetting(PremiumWebCartIPN_Config::RECURRING_USE_ORDERID_AS_SUBSCRID, '');
    }
}
?>
