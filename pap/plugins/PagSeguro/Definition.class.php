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

class PagSeguro_Definition extends Gpf_Plugins_Definition  {
    public function __construct() {
        $this->codeName = 'PagSeguro';
        $this->name = $this->_('PagSeguro');
        $this->description = $this->_('This plugin handles PagSeguro IPN notifications (integration of Post Affiliate Pro with PagSeguro)');
        $this->version = '1.0.0';
        $this->configurationClassName = 'PagSeguro_Config';
        
        $this->addRequirement('PapCore', '4.0.4.6');
        
        $this->addImplementation('Core.defineSettings', 'PagSeguro_Main', 'initSettings');
    }
}
?>
