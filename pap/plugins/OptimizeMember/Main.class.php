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
 *   http://www.postaffiliatepro.com/licenses/license
 *
 */

/**
 * @package PostAffiliatePro plugins
 */
class OptimizeMember_Main extends Gpf_Plugins_Handler {

    /**
     * @return OptimizeMember_Main
     */
    public static function getHandlerInstance() {
        return new OptimizeMember_Main();
    }

    public function initSettings($context) {
        $context->addDbSetting(OptimizeMember_Config::SECRET_WORD, '');
        $context->addDbSetting(OptimizeMember_Config::REGISTER_AFFILIATE, Gpf::NO);
        $context->addDbSetting(OptimizeMember_Config::ONLY_MATCHED_RECURRENCE, Gpf::YES);
    }
}
