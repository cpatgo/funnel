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

class s2Member_Definition extends Gpf_Plugins_Definition  {
    public function __construct() {
        $this->codeName = 's2Member';
        $this->name = $this->_('s2Member user integration');
        $this->description = $this->_('This plugin handles integration with your s2Member WordPress plugin payments and user registrations. Needs further settigns in your WordPress, please see the integration guide for s2Member found in the Sale Tracking Integration section of your merchant panel.');
        $this->version = '1.1.3';
        $this->configurationClassName = 's2Member_Config';

        $this->addRequirement('PapCore', '4.0.4.6');

        $this->addImplementation('Core.defineSettings', 's2Member_Main', 'initSettings');
    }
}
?>
