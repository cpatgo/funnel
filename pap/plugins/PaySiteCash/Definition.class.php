<?php
/**
 *   @copyright Copyright (c) 2009 Quality Unit s.r.o.
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

class PaySiteCash_Definition extends Gpf_Plugins_Definition  {
    public function __construct() {
        $this->codeName = 'PaySiteCash';
        $this->name = $this->_('PaySiteCash');
        $this->description = $this->_('This plugin handles PaySiteCash integration with Post Affiliate Pro');
        $this->version = '1.0.0';

        $this->addRequirement('PapCore', '4.5.40.4');
    }
}
?>
