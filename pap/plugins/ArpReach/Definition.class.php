<?php
/**
 *   @copyright Copyright (c) 2013 Quality Unit s.r.o.
 *   @author Martin Pulllmann
 *   @package PostAffiliatePro
 *   @since Version 1.0.0
 *
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 1.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.qualityunit.com/licenses/license
 *
 */

/**
 * @package PostAffiliatePro
 */
class ArpReach_Definition extends Gpf_Plugins_Definition {

    public function __construct() {
        $this->codeName =  'ArpReach';
        $this->name = $this->_('ArpReach');
        $this->description = $this->_('Registers your new affiliates automatically to an ArpReach form');
        $this->configurationClassName = 'ArpReach_Config';
        $this->version = '1.1.1';
        $this->addRequirement('PapCore', '4.1.30.0');

        $this->addImplementation('Core.defineSettings', 'ArpReach_Main', 'initSettings');
        $this->addImplementation('PostAffiliate.signup.after', 'ArpReach_Main', 'sendMail');
    }
}
?>
