<?php

/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @author Maros Fric
 *   @package PostAffiliatePro
 *   @since Version 1.0.0
 *
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 1.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.postaffiliatepro.com/licenses/license
 *
 */

class AlertPay_Definition extends Gpf_Plugins_Definition  {
    const CODE = 'AlertPay';
    public function __construct() {
        $this->codeName = self::CODE;
        $this->name = $this->_('AlertPay (Payza) integration plugin');
        $this->description = $this->_('This plugin handles AlertPay (Payza) IPN notifications (integration of Post Affiliate Pro with AlertPay). Do not forget to configure this plugin after activation.');
        $this->version = '1.1.0';
        $this->configurationClassName = 'AlertPay_Config';
        
        $this->addRequirement('PapCore', '4.0.4.6');
        
        $this->addImplementation('Core.defineSettings', 'AlertPay_Main', 'initSettings');
    }
}
?>
