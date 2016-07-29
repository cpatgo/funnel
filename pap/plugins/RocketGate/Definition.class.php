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

class RocketGate_Definition extends Gpf_Plugins_Definition  {
    public function __construct() {
        $this->codeName = 'RocketGate';
        $this->name = $this->_('RocketGate postback handling');
        $this->description = $this->_('This plugin handles RocketGate postback notifications (integration of Post Affiliate with RocketGate)');
        $this->version = '1.0.0';
        $this->configurationClassName = 'RocketGate_Config';

        $this->addRequirement('PapCore', '4.2.3.2');

        $this->addImplementation('Core.defineSettings', 'RocketGate_Main', 'initSettings');
    }
}
?>
