<?php
/**
 *   @copyright Copyright (c) 2016 Quality Unit s.r.o.
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
class Braintree_Definition extends Gpf_Plugins_Definition {

    public function __construct() {
        $this->codeName = 'Braintree';
        $this->name = $this->_('Braintree webhook handling');
        $this->description = $this->_('This plugin handles Braintree webhooks');
        $this->version = '1.0.0';
        $this->configurationClassName = 'Braintree_Config';

        $this->addRequirement('PapCore', '5.4.0.0');

        $this->addImplementation('Core.defineSettings', 'Braintree_Main', 'initSettings');
    }
}
