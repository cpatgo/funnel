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
 *   http://www.qualityunit.com/licenses/license
 *
 */

/**
 * @package PostAffiliatePro plugins
 */
class CommerceGate_Definition extends Gpf_Plugins_Definition  {
    public function __construct() {
        $this->codeName = 'CommerceGate';
        $this->name = $this->_('CommerceGate');
        $this->description = $this->_('This plugin handles CommerceGate callback notifications (integration of Post Affiliate Pro with CommerceGate)');
        $this->version = '1.0.0';
        $this->configurationClassName = 'CommerceGate_Config';

        $this->addRequirement('PapCore', '4.0.4.6');

        $this->addImplementation('Core.defineSettings', 'CommerceGate_Main', 'initSettings');
    }
}
?>
