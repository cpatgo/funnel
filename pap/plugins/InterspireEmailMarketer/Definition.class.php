<?php
/**
 *   @copyright Copyright (c) 2014 Quality Unit s.r.o.
 *   @author Matej Kendera
 *   @package GwtPhpFramework
 *   @since Version 1.0.0
 *
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 1.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.postaffiliatepro.com/licenses/license
 *
 */

/**
 * @package GwtPhpFramework
 */
class InterspireEmailMarketer_Definition extends Gpf_Plugins_Definition {

    public function __construct() {
        $this->codeName =  'InterspireEmailMarketer';
        $this->name = $this->_('Interspire Email Marketer signup');
        $this->description = $this->_('After signup of affiliate to Post Affiliate Pro, this plugin will register user also in Interspire Email Marketer service. Plugins requires you to enter XML Path, username and usertoken. Visit Interspire Email Marketer here at %s', '<a href="http://www.interspire.com/emailmarketer" target="_blank">http://www.interspire.com/emailmarketer</a>');
        $this->version = '1.1.1';
        $this->help = '';
        $this->configurationClassName = 'InterspireEmailMarketer_Config';

        $this->addRequirement('PapCore', '4.1.4.6');

        $this->addImplementation('Core.defineSettings', 'InterspireEmailMarketer_Main', 'initSettings');
        $this->addImplementation('PostAffiliate.affiliate.userStatusChanged', 'InterspireEmailMarketer_Main', 'userStatusChanged');
        $this->addImplementation('PostAffiliate.User.afterDelete', 'InterspireEmailMarketer_Main', 'userDeleted');
        $this->addImplementation('PostAffiliate.affiliate.firsttimeApproved', 'InterspireEmailMarketer_Main', 'signupToEmailMarketer');
        $this->addImplementation('PostAffiliate.User.afterSave', 'InterspireEmailMarketer_Main', 'changeEmail');
        $this->addImplementation('Gpf_Auth_UserForm.save', 'InterspireEmailMarketer_Main', 'changeEmailAuthUser');
    }
}
?>
