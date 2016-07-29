<?php
/**
 *   @copyright Copyright (c) 2012 Quality Unit s.r.o.
 *   @author Martin Pullmann
 *   @package PostAffiliatePro
 *
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 1.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.postaffiliatepro.com/licenses/license
 */

class MailChimpSynchro_Definition extends Gpf_Plugins_Definition  {

    public function __construct() {
        $this->codeName = 'MailChimpSynchro';
        $this->name = $this->_('MailChimp user synchronization');
        $this->description = $this->_('This plugin creates a new customer (or updates an existing) in a MailChimp list based on affiliate data.');
        $this->version = '1.2.2';
        $this->configurationClassName = 'MailChimpSynchro_Config';

        $this->addRequirement('PapCore', '4.5.54.2');

        $this->addImplementation('Core.defineSettings', 'MailChimpSynchro_Main', 'initSettings');
        $this->addImplementation('PostAffiliate.User.beforeSave', 'MailChimpSynchro_Main', 'onUserSave');
        $this->addImplementation('PostAffiliate.affiliate.firsttimeApproved', 'MailChimpSynchro_Main', 'firstTimeApprovedAffiliate');
    }
}
?>
