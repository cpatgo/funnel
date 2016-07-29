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
 *   http://www.postaffiliatepro.com/licenses/license
 *
 */

/**
 * @package PostAffiliatePro plugins
 */
class Infusionsoft_Main extends Gpf_Plugins_Handler {

    /**
     * @return Infusionsoft_Main
     */
    public static function getHandlerInstance() {
        return new Infusionsoft_Main();
    }

    public function initSettings($context) {
        $context->addDbSetting(Infusionsoft_Config::API_KEY, '');
        $context->addDbSetting(Infusionsoft_Config::SUBDOMAIN, '');
    }
}
?>
