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
class VolusionAPI_Main extends Gpf_Plugins_Handler {

    /**
     * @return VolusionAPI_Main
     */
    public static function getHandlerInstance() {
        return new VolusionAPI_Main();
    }

    public function initSettings($context) {
        $context->addDbSetting(VolusionAPI_Config::VOLUSION_URL, '');
        $context->addDbSetting(VolusionAPI_Config::LOGIN, '');
        $context->addDbSetting(VolusionAPI_Config::PASS, '');
        $context->addDbSetting(VolusionAPI_Config::CUSTOM_NUMBER, '');
        $context->addDbSetting(VolusionAPI_Config::USE_COUPON, '');
        $context->addDbSetting(VolusionAPI_Config::PER_PRODUCT, '');
        $context->addDbSetting(VolusionAPI_Config::REGISTER_AFFILIATE, '');
        $context->addDbSetting(VolusionAPI_Config::REDUCE_TAX, '');
        $context->addDbSetting(VolusionAPI_Config::REDUCE_SHIPPING, '');
    }
}
?>
