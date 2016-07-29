<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @author Milos Jancovic
 *   @package PostAffiliatePro
 *   @since Version 1.0.0
 *
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 1.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.postaffiliatepro.com/licenses/license
 *
 */

/**
 * @package PostAffiliatePro
 */
class AutoResponsePlus_Definition extends Gpf_Plugins_Definition {

    public function __construct() {
        $this->codeName =  'AutoResponsePlus';
        $this->name = $this->_('Auto response plus (ARP)');
        $this->description = $this->_('Register your new affiliates automatically to AutoResponsePlus, affiliate data (full name, email address and IP address) are sending by email to your AutoResponse Plus') . '<br /><a href="'.Gpf_Application::getKnowledgeHelpUrl('798014-Auto-response-plus-plugin-configuration').'" target="_blank">'.$this->_('Read more in our Knowledge Base').'</a>';
        $this->configurationClassName = 'AutoResponsePlus_Config';
        $this->version = '1.0.1';
        $this->addRequirement('PapCore', '4.1.30.0');

        $this->addImplementation('Core.defineSettings', 'AutoResponsePlus_Main', 'initSettings');
        $this->addImplementation('PostAffiliate.signup.after', 'AutoResponsePlus_Main', 'sendMail');
    }
}
?>
