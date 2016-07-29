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
class BigCommerceAPI_Definition extends Gpf_Plugins_Definition {

    public function __construct() {
        $this->codeName = 'BigCommerceAPI';
        $this->name = $this->_('BigCommerce API');
        $this->description = $this->_('This plugin integrates Post Affiliate Pro with BigCommerce API');
        $this->version = '1.0.0';
        $this->configurationClassName = 'BigCommerceAPI_Config';
        
        $this->addRequirement('PapCore', '4.0.4.6');
        
        $this->addImplementation('Core.defineSettings', 'BigCommerceAPI_Main', 'initSettings');
    }
}
?>
