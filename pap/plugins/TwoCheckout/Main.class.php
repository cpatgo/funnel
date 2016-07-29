<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @author Juraj Simon
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
class TwoCheckout_Main extends Gpf_Plugins_Handler {

    /**
     * @return TwoCheckout_Main
     */
    public static function getHandlerInstance() {
        return new TwoCheckout_Main();
    }
    
    public function initSettings($context) {
        $context->addDbSetting(TwoCheckout_Config::PROCESS_WHOLE_CART_AS_ONE_TRANSACTION, Gpf::YES);
        $context->addDbSetting(TwoCheckout_Config::REGISTER_AFFILIATE, Gpf::NO);
        $context->addDbSetting(TwoCheckout_Config::DECLINE_AFFILIATE, Gpf::NO);
        $context->addDbSetting(TwoCheckout_Config::CUSTOM_SEPARATOR, '||');
        $context->addDbSetting(TwoCheckout_Config::COUPON_TRACKING, Gpf::NO);
        $context->addDbSetting(TwoCheckout_Config::API_USERNAME, '');
        $context->addDbSetting(TwoCheckout_Config::API_PASSWORD, '');
    }
}
