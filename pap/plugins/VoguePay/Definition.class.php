<?php
/**
 *   @copyright Copyright (c) 2013 Quality Unit s.r.o.
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
class VoguePay_Definition extends Gpf_Plugins_Definition {

    public function __construct() {
        $this->codeName = 'VoguePay';
        $this->name = $this->_('VoguePay IPN handling');
        $this->description = $this->_('This plugin handles VoguePay IPN notifications (integration of Post Affiliate Pro with VoguePay)');
        $this->version = '1.0.0';

        $this->addRequirement('PapCore', '4.0.4.6');
    }
}
?>
