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
 *   http://www.qualityunit.com/licenses/license
 *
 */

/**
 * @package PostAffiliatePro plugins
 */
class PagSeguro_Main extends Gpf_Plugins_Handler {

    /**
     * @return PagSeguro_Main
     */
    public static function getHandlerInstance() {
        return new PagSeguro_Main();
    }
    
    public function initSettings($context) {
        $context->addDbSetting(PagSeguro_Config::EMAIL, '');
        $context->addDbSetting(PagSeguro_Config::TOKEN, '');
        $context->addDbSetting(PagSeguro_Config::REGISTER_AFFILIATE, '');
        $context->addDbSetting(PagSeguro_Config::PROCESS_CART_PER_ITEM, '');
        $context->addDbSetting(PagSeguro_Config::TEST_MODE, '');
    }
}
?>
