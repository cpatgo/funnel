<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
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

class VolusionAPI_Definition extends Gpf_Plugins_Definition  {
    public function __construct() {
        $this->codeName = 'VolusionAPI';
        $this->name = $this->_('Volusion API');
        $this->description = $this->_('This plugin uses Volusion API to gather info about orders and customer (integration of Post Affiliate with Volusion). The plugin only works after proper configuration.');
        $this->version = '2.0.0';
        $this->configurationClassName = 'VolusionAPI_Config';

        $this->addRequirement('PapCore', '4.2.3.2');

        $this->addImplementation('Core.defineSettings', 'VolusionAPI_Main', 'initSettings');
    }
}
?>
