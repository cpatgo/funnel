<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @author Ladislav Acs
 *   @package PostAffiliatePro
 *   @since Version 1.0.0
 *
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 1.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.postaffiliatepro.com/licenses/license
 *
 */

class FastSpring_Definition extends Gpf_Plugins_Definition  {
    public function __construct() {
        $this->codeName = 'FastSpring';
        $this->name = $this->_('FastSpring');
        $this->description = $this->_('This plugin handles the custom order item notificatoins posted by FastSpring (even regarding installments) to Post Affiliate Pro');
        $this->version = '1.0.0';

        $this->addRequirement('PapCore', '4.0.4.6');
    }
}
?>
