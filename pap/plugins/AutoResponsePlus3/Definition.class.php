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
 * @package PostAffiliatePro
 */
class AutoResponsePlus3_Definition extends Gpf_Plugins_Definition {

    public function __construct() {
        $this->codeName =  'AutoResponsePlus3';
        $this->name = $this->_('Auto Response Plus 3 (ARP3)');
        $this->description = $this->_('Automatically add your affiliates to an autoresponder of your AutoResponsePlus3. The plugin uses direct request of AutoResponsePlus3 - all the data your affiliate filled out in the signup form will be sent to AutoResponsePlus3 too.') . '<br />' .
                $this->_('There are two custom values sent to your autoresponder: \'custom_referralID\' (for referral ID of the affiliate) and \'custom_parentAffiliateID\' (affiliate ID of parent affiliate).');
        $this->configurationClassName = 'AutoResponsePlus3_Config';
        $this->version = '1.0.0';
        $this->addRequirement('PapCore', '4.1.30.0');

        $this->addImplementation('Core.defineSettings', 'AutoResponsePlus3_Main', 'initSettings');
        $this->addImplementation('PostAffiliate.signup.after', 'AutoResponsePlus3_Main', 'sendFormData');
    }
}
?>
