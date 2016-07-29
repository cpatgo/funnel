<?php
/**
 *   @copyright Copyright (c) 2013 Quality Unit s.r.o.
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
class MemberMouse_Main extends Gpf_Plugins_Handler {

    /**
     * @return MemberMouse_Main
     */
    public static function getHandlerInstance() {
        return new MemberMouse_Main();
    }

    public function initSettings($context) {
        $context->addDbSetting(MemberMouse_Config::CUSTOM_FIELD, '');
        $context->addDbSetting(MemberMouse_Config::CREATE_AFFILIATE, '');
        $context->addDbSetting(MemberMouse_Config::CHANGE_AFFILIATE_STATUS, '');
        $context->addDbSetting(MemberMouse_Config::PER_PRODUCT_TRANSACTION, '');
        $context->addDbSetting(MemberMouse_Config::PROCESS_REFUND, '');
        $context->addDbSetting(MemberMouse_Config::USE_COUPON, '');
    }
}
?>
