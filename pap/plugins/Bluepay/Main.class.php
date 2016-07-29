<?php
/**
 *   @copyright Copyright (c) 2014 Quality Unit s.r.o.
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
class Bluepay_Main extends Gpf_Plugins_Handler {

    /**
     * @return Paymate_Main
     */
    public static function getHandlerInstance() {
        return new Bluepay_Main();
    }
    
    public function initSettings($context) {
        $context->addDbSetting(Bluepay_Config::CUSTOM_ID, '1');
        //$context->addDbSetting(Bluepay_Config::MERCHANT_SECRET_KEY, '');
        $context->addDbSetting(Bluepay_Config::CREATE_AFFILIATE, '');
        $context->addDbSetting(Bluepay_Config::CUSTOM_SEPARATOR, '||');
    }
}
?>
