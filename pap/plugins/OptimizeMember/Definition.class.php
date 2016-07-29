<?php
/**
 *   @copyright Copyright (c) 2015 Quality Unit s.r.o.
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

class OptimizeMember_Definition extends Gpf_Plugins_Definition  {
    public function __construct() {
        $this->codeName = 'OptimizeMember';
        $this->name = $this->_('OptimizeMember notification handling');
        $this->description = $this->_('This plugin handles notifications from your OptimizeMember WordPress plugin. Needs further configuration in your WordPress, please see the integration guide for OptimizeMember found in the Sale Tracking Integration section of your merchant panel.');
        $this->version = '1.1.0';
        $this->configurationClassName = 'OptimizeMember_Config';

        $this->addRequirement('PapCore', '4.0.4.6');
        $this->addRequirement('RecurringCommissions', '1.0.0');

        $this->addImplementation('Core.defineSettings', 'OptimizeMember_Main', 'initSettings');
    }
}
