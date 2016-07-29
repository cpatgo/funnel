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
 *   http://www.qualityunit.com/licenses/license
 *
 */

class Stripe_Definition extends Gpf_Plugins_Definition  {
    public function __construct() {
        $this->codeName = 'Stripe';
        $this->name = $this->_('Stripe webhook handling');
        $this->description = $this->_('This plugin handles Stripe webhooks');
        $this->version = '1.1.2';
        $this->configurationClassName = 'Stripe_Config';

        $this->addRequirement('PapCore', '4.0.4.6');

        $this->addImplementation('Core.defineSettings', 'Stripe_Main', 'initSettings');
    }
}
