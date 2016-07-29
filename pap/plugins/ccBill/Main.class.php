<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @author Matej Kendera
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
class ccBill_Main extends Gpf_Plugins_Handler {

    /**
     * @return ccBill_Main
     */
    public static function getHandlerInstance() {
        return new ccBill_Main();
    }
    
    public function initSettings($context) {
        $context->addDbSetting(ccBill_Config::REGISTER_AFFILIATE, '');
        $context->addDbSetting(ccBill_Config::RECURRING_TOTALCOST_FROM_NOTIFICATION, '');
    }
}
?>
