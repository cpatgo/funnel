<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @author Milos Jancovic    (created by Rick Braddy / WinningWare.com for PostAffiliatePro)
 *   @package PostAffiliatePro
 *   @since Version 1.0.1
 *   $Id: ActionParser.class.php 16620 2008-03-21 09:21:07Z aharsani $
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
class AWeber_Mail extends Pap_Mail_Template {

    const AFF_NAME = 'aff_name';
    const AFF_EMAIL = 'aff_email';
    const AFF_REFERRALID = 'aff_refid';

    /**
     * @var Pap_Common_User
     */
    private $user;

    public function __construct() {
        parent::__construct();
        $this->isHtmlMail = false;
    }

    protected function loadTemplate() {
        $this->mailTemplate = new Gpf_Db_MailTemplate();
        $this->mailTemplate->setSubject('premiumwebcart');  // uses the built-in AWeber "Premium Web Cart" email parser, which must be enabled for target autoresponder
        $this->mailTemplate->setBodyText($this->getBody());
    }

    protected function initTemplateVariables() {
        parent::initTemplateVariables();
        $this->addVariable(self::AFF_NAME, self::AFF_NAME);
        $this->addVariable(self::AFF_EMAIL, self::AFF_EMAIL);
        $this->addVariable(self::AFF_REFERRALID, self::AFF_REFERRALID);
    }

    protected function setVariableValues() {
        parent::setVariableValues();
        $this->setVariable(self::AFF_NAME, $this->user->getFirstName());
        $this->setVariable(self::AFF_EMAIL, $this->user->getEmail());
        $this->setVariable(self::AFF_REFERRALID, $this->user->getRefId());
    }

    public function setUser(Pap_Common_User $user) {
        $this->setAuthUser($user->getAuthUser());
        $this->user = $user;
    }

    private function getBody() {
        return 'Registering new affiliate ' . "\n\n" .
		'Name: ' . '{$'.self::AFF_NAME.'}' . "\n" .
        'Email: ' . '{$'.self::AFF_EMAIL.'}' . "\n" . ($this->isEnabledRefid() ? $this->getTemplateRefidLine() : "\n" );
    }

    private function isEnabledRefid() {
    	return Gpf_Settings::get(AWeber_Config::AUTORESPONDER_ADD_REFID) == Gpf::YES;
    }

    private function getTemplateRefidLine() {
    	return Gpf_Settings::get(AWeber_Config::AUTORESPONDER_REFID_FIELDNAME).': ' . '{$'.self::AFF_REFERRALID.'}' . "\n\n";
    }
}
