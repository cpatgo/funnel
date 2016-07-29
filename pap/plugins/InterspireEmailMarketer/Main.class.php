<?php
/**
 *   @copyright Copyright (c) 2014 Quality Unit s.r.o.
 *   @author Matej Kendera
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
class InterspireEmailMarketer_Main extends Gpf_Plugins_Handler {

    private $client;
    private $apiKey;

    private $isSubscribed;

    /**
     * @var Gpf_Log_Logger
     */
    private $logger = null;

    const INTERSPIRE_EMAIL_MARKETER_CONTACT_EMAIL = 'interspireEmailMarketerContactEmail';


    public function initSettings(Gpf_Settings_Gpf $context) {
        $context->addDbSetting(InterspireEmailMarketer_Config::XML_PATH, '');
        $context->addDbSetting(InterspireEmailMarketer_Config::USERNAME, '');
        $context->addDbSetting(InterspireEmailMarketer_Config::USERTOKEN, '');
        $context->addDbSetting(InterspireEmailMarketer_Config::MAILING_LIST, '1');
        $context->addDbSetting(InterspireEmailMarketer_Config::NAME_FIELD_ID, '1');
    }

    /**
     * @return InterspireEmailMarketer_Main
     */
    public static function getHandlerInstance() {
        return new InterspireEmailMarketer_Main();
    }

    public static function checkRequiredSettings() {
        if (!strlen(Gpf_Settings::get(InterspireEmailMarketer_Config::XML_PATH))) {
            throw new Gpf_Exception('Interspire Email Marketer XML Path not defined. Please edit XML Path in Interspire Email Marketer plugin configuration!');
        }
        if (!strlen(Gpf_Settings::get(InterspireEmailMarketer_Config::USERNAME))) {
            throw new Gpf_Exception('Interspire Email Marketer username not defined. Please edit username in Interspire Email Marketer plugin configuration');
        }
        if (!strlen(Gpf_Settings::get(InterspireEmailMarketer_Config::USERTOKEN))) {
            throw new Gpf_Exception('Interspire Email Marketer usertoken not defined. Please edit usertoken in Interspire Email Marketer plugin configuration');
        }
    }

    public function userStatusChanged(Gpf_Plugins_ValueContext $context) {
    	try {
    		self::checkRequiredSettings();
    	} catch (Gpf_Exception $e) {
    		$this->log('InterspireEmailMarketer plugin STOPPED: '.$e->getMessage(), Gpf_Log::CRITICAL);
    		return;
    	}
        $data = $context->get();
        $user = $data[0];
        $newStatus = $data[1];
        $this->log('InterspireEmailMarketer - userStatusChanged started, status:' . $newStatus);
        $oldEmail = $this->loadContactEmail($user);
        if($newStatus == Pap_Common_Constants::STATUS_APPROVED && !$this->isSubscribed) {
            $this->signupToEmailMarketer($user);
            return;
        }
        if($this->isSubscribed) {
            $this->deleteContact($user, $oldEmail);
        }
    }

    public function changeEmail(Pap_Common_User $user) {
    	try {
    		self::checkRequiredSettings();
    	} catch (Gpf_Exception $e) {
    		$this->log('InterspireEmailMarketer plugin STOPPED: '.$e->getMessage(), Gpf_Log::CRITICAL);
    		return;
    	}
        $this->log('InterspireEmailMarketer - changeEmail started');
        $oldEmail = $this->loadContactEmail($user);
        if(!$this->isSubscribed) {
            if($user->getStatus() == Pap_Common_Constants::STATUS_APPROVED) {
                $this->signupToEmailMarketer($user);
            }
            return;
        }
        $this->log('InterspireEmailMarketer - Old email found: ' . $oldEmail);
        if($oldEmail == $user->getEmail() || $oldEmail == '') {
            return;
        }
        $this->deleteContact($user, $oldEmail);
        $this->signupToEmailMarketer($user);
    }

    public function changeEmailAuthUser(Gpf_Db_AuthUser $authUser) {
    	try {
    		self::checkRequiredSettings();
    	} catch (Gpf_Exception $e) {
    		$this->log('InterspireEmailMarketer plugin STOPPED: '.$e->getMessage(), Gpf_Log::CRITICAL);
    		return;
    	}
        try {
            $affiliateUser = Pap_Affiliates_User::loadFromUsername($authUser->getUsername());
        } catch (Gpf_Exception $e) {
            return;
        }
        $this->changeEmail($affiliateUser);
    }

    public function userDeleted(Pap_Common_User $user) {
    	try {
    		self::checkRequiredSettings();
    	} catch (Gpf_Exception $e) {
    		$this->log('InterspireEmailMarketer plugin STOPPED: '.$e->getMessage(), Gpf_Log::CRITICAL);
    		return;
    	}
        $this->log('InterspireEmailMarketer - userDeleted start.');
        $this->isSubscribed = true;
        $this->deleteContact($user, $user->getEmail());
    }

    public function signupToEmailMarketer(Pap_Common_User $user) {
    	
    	try {
    		self::checkRequiredSettings();
    	} catch (Gpf_Exception $e) {
    		$this->log('InterspireEmailMarketer plugin STOPPED: '.$e->getMessage(), Gpf_Log::CRITICAL);
    		return;
    	}
		
        $this->log('InterspireEmailMarketer - Signup started');
        $this->loadContactEmail($user);
        if($this->isSubscribed) {
            $this->log('InterspireEmailMarketer - user has been already saved.');
            return;
        }
        if($user->getAccountUserId() == '') {
            $this->log('InterspireEmailMarketer - user has not been saved yet, returning');
            return;
        }
        $this->addSubscriberToList($user);
        $this->storeContactEmail($user);
        $this->log('InterspireEmailMarketer - Signup end');
    }

    private function storeContactEmail(Pap_Common_User $user) {
        $userAttr = $this->getUserAttributeObject();
        $userAttr->setAccountUserId($user->getAccountUserId());
        $userAttr->setName(self::INTERSPIRE_EMAIL_MARKETER_CONTACT_EMAIL);
        $userAttr->setValue($user->getEmail());
        $userAttr->insert();
    }

    private function deleteContact(Pap_common_user $user, $oldEmail) {
        if(!$this->isSubscribed) {
            return;
        }
        $this->log('InterspireEmailMarketer - deleteContact');
        $this->deleteSubscriber($oldEmail);

        try {
        $userAttr = new Gpf_Db_UserAttribute();
        $userAttr->setName(self::INTERSPIRE_EMAIL_MARKETER_CONTACT_EMAIL);
        $userAttr->setAccountUserId($user->getAccountUserId());
        $userAttr->loadFromData();
        $userAttr->delete();
        } catch (Gpf_Exception $e) {

        }
    }

    protected function getUserAttributeObject() {
        return new Gpf_Db_UserAttribute();
    }

    private function loadContactEmail(Pap_Common_User $user) {
        $this->log('InterspireEmailMarketer - loadContactEmail from DB');
        $userAttr = $this->getUserAttributeObject();
        $userAttr->setAccountUserId($user->getAccountUserId());
        $userAttr->setName(self::INTERSPIRE_EMAIL_MARKETER_CONTACT_EMAIL);
        $this->isSubscribed = true;
        try {
            $userAttr->loadFromData();
        } catch (Gpf_Exception $e) {
            $this->log('InterspireEmailMarketer - contact not found in DB');
            $this->isSubscribed = false;
            return;
        }

        return $userAttr->getValue();
    }

    private function addSubscriberToList(Pap_Common_User $user) {

        $xml = '<xmlrequest>
<username>'.$this->getUsername().'</username>
<usertoken>'.$this->getUsertoken().'</usertoken>
<requesttype>subscribers</requesttype>
<requestmethod>AddSubscriberToList</requestmethod>
<details>
<emailaddress>'.$user->getEmail().'</emailaddress>
<mailinglist>'.Gpf_Settings::get(InterspireEmailMarketer_Config::MAILING_LIST).'</mailinglist>
<format>html</format>
<confirmed>yes</confirmed>
<customfields>
<item>
<fieldid>'.Gpf_Settings::get(InterspireEmailMarketer_Config::NAME_FIELD_ID).'</fieldid>
<value>'.$user->getFirstName().' '.$user->getLastName().'</value>
</item>
</customfields>
</details>
</xmlrequest>';

        $this->callFunction($xml);

        $this->log('InterspireEmailMarketer - Affiliate added');
    }

    private function deleteSubscriber($email) {

        $xml = '<xmlrequest>
<username>'.$this->getUsername().'</username>
<usertoken>'.$this->getUsertoken().'</usertoken>
<requesttype>subscribers</requesttype>
<requestmethod>DeleteSubscriber</requestmethod>
<details>
<emailaddress>'.$email.'</emailaddress>
<listid>'.Gpf_Settings::get(InterspireEmailMarketer_Config::MAILING_LIST).'</listid>
</details>
</xmlrequest>';

        $this->callFunction($xml);
    }

    private function callFunction($xml) {
        $this->log('InterspireEmailMarketer - sending xml request: ' . $xml);

        $client = new Gpf_Net_Http_Client();
        $request = new Gpf_Net_Http_Request();
        $request->setMethod('POST');
        $request->setUrl(Gpf_Settings::get(InterspireEmailMarketer_Config::XML_PATH));
        $request->setBody($xml);
        $result = $client->execute($request);

        if($result->getBody() === false) {
           $this->log('InterspireEmailMarketer Exception - Error performing request.', Gpf_Log::ERROR);
        } else {
          $xml_doc = @simplexml_load_string($result->getBody());
          if ($xml_doc != null) {
              if ($xml_doc->status == 'SUCCESS') {
                  $this->log('InterspireEmailMarketer - success. Data is: ' . $xml_doc->data);
              } else {
                  $this->log('InterspireEmailMarketer Error is: ' . $xml_doc->errormessage, Gpf_Log::ERROR);
              }
          } else {
              $this->log('InterspireEmailMarketer Error, no data loaded.', Gpf_Log::ERROR);
          }
        }
    }

    private function getUsername() {
        return Gpf_Settings::get(InterspireEmailMarketer_Config::USERNAME);
    }

    private function getUsertoken() {
        return Gpf_Settings::get(InterspireEmailMarketer_Config::USERTOKEN);
    }

	/**
     * @return Gpf_Log_Logger
     */
    private function getLogger() {
        if ($this->logger === null) {
            $this->logger = Pap_Logger::create(Pap_Common_Constants::TYPE_SIGNUP);
        }
        return $this->logger;
    }

    private function log($message, $logLevel = Gpf_Log::DEBUG) {
        $this->getLogger()->log($message, $logLevel);
    }
}
?>
