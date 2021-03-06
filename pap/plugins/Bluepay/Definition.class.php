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

class Bluepay_Definition extends Gpf_Plugins_Definition  {
    public function __construct() {
        $this->codeName = 'Bluepay';
        $this->name = $this->_('Bluepay');
        $this->description = $this->_('This plugin handles BluePay integration with Post Affiliate Pro');
        $this->version = '1.1.0';
        $this->configurationClassName = 'Bluepay_Config';
        
        $this->addRequirement('PapCore', '4.5.40.4');
        
        $this->addImplementation('Core.defineSettings', 'Bluepay_Main', 'initSettings');
    }
}
?>
