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

class PremiumWebCartIPN_Definition extends Gpf_Plugins_Definition  {
    public function __construct() {
        $this->codeName = 'PremiumWebCartIPN';
        $this->name = $this->_('PremiumWebCart IPN handling');
        $this->description = $this->_('This plugin handles Premium Web Cart IPN notifications (integration of Post Affiliate with Premium Web Cart)');
        $this->version = '2.2.1';
        $this->configurationClassName = 'PremiumWebCartIPN_Config';

        $this->addRequirement('PapCore', '4.0.4.6');

        $this->addImplementation('Core.defineSettings', 'PremiumWebCartIPN_Main', 'initSettings');
    }
}
?>
