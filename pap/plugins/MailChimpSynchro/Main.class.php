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

class MailChimpSynchro_Main extends Gpf_Plugins_Handler {

    public function __construct() {
    }

    /**
     * @return MailChimpSynchro_Main
     */
    public static function getHandlerInstance() {
        return new MailChimpSynchro_Main();
    }

    public function initSettings(Gpf_Settings_Gpf $context) {
        $context->addDbSetting(MailChimpSynchro_Config::API_KEY, '');
        $context->addDbSetting(MailChimpSynchro_Config::SECRET_KEY, '');
        $context->addDbSetting(MailChimpSynchro_Config::LIST_ID, '');
        $context->addDbSetting(MailChimpSynchro_Config::SECURE, Gpf::NO);
        $context->addDbSetting(MailChimpSynchro_Config::ADD_NEW, Gpf::NO);
        $context->addDbSetting(MailChimpSynchro_Config::DOUBLE_OPTIN, Gpf::YES);
    }

    private function prepareUserInfo(Pap_Common_User $user) {
        if (!empty($_GET['secretKey'])) {
            return; // skip recurrent change when we are processing webhook
        }

        try {
            $userFromDb = Pap_Affiliates_User::loadFromId($user->getId());
            if ($userFromDb->getEmail() != $user->getEmail()) {
                $merge_vars = array('EMAIL'=>$userFromDb->getEmail(), 'NEW-EMAIL'=>$user->getEmail(), 'FNAME'=>$user->getFirstName(), 'LNAME'=>$user->getLastName());
            } else { //email did not change
                $merge_vars = array('EMAIL'=>$user->getEmail(), 'FNAME'=>$user->getFirstName(), 'LNAME'=>$user->getLastName());
            }

            // update user
            $this->synchro($user, $merge_vars);
        } catch (Gpf_Exception $e) {
            $this->addNewAffiliate($user);
        }
    }

    public function onUserSave(Pap_Common_User $user) {
        if ($this->isAddAllAffiliates() || $this->isOnlyUserSynchro()) {
            $this->prepareUserInfo($user);
        }
    }

    public function firstTimeApprovedAffiliate(Pap_Common_User $user) {
        if ($this->isAddOnlyOnApprove()) {
            $this->prepareUserInfo($user);
        }
    }

    public function synchro($user, $merge_vars) {
        Gpf_Log::info('MailChimp: Starting update of MailChimp user...');

        require_once 'MCAPI.class.php';
        $api = new MCAPI($this->getApiKey(), $this->useSecure());
        foreach ($this->getListId() as $id) {
            $vals = $api->listSubscribe($id, $user->getEmail(), $merge_vars, 'html', Gpf_Settings::get(MailChimpSynchro_Config::DOUBLE_OPTIN) == Gpf::YES);

            if ($api->errorCode){
                Gpf_Log::info('MailChimp: Update failed! code:'.$api->errorCode.'; msg:'.$api->errorMessage);
            } else {
                Gpf_Log::info('MailChimp: User data synced with MailChimp');
            }
        }
    }

    public function addNewAffiliate(Pap_Common_User $user) {
        if (!$this->addNew()) {
            Gpf_Log::info('MailChimp: Creation of new users into MailChimp is not enabled, skipping...');
            return;
        }

        Gpf_Log::info('MailChimp: Starting creation of MailChimp user...');

        require_once 'MCAPI.class.php';
        $api = new MCAPI($this->getApiKey(), $this->useSecure());
        $merge_vars = array('EMAIL'=>$user->getEmail(), 'FNAME'=>$user->getFirstName(), 'LNAME'=>$user->getLastName());
        foreach ($this->getListId() as $id) {
            $vals = $api->listSubscribe($id, $user->getEmail(), $merge_vars, 'html', Gpf_Settings::get(MailChimpSynchro_Config::DOUBLE_OPTIN) == Gpf::YES);

            if ($api->errorCode){
                Gpf_Log::info('MailChimp: Creation failed! code:'.$api->errorCode.'; msg:'.$api->errorMessage);
            } else {
                Gpf_Log::info('MailChimp: User data synced with MailChimp');
            }
        }
    }

    public function processWebhook() {
        // authorize
        if (empty($_GET['secretKey']) || ($_GET['secretKey'] != Gpf_Settings::get(MailChimpSynchro_Config::SECRET_KEY))) {
            Gpf_Log::info('MailChimp webhook: Unauthorized webhook call... Terminating the process.');
            exit;
        }

        // check type
        $type = $_POST['type'] ;
        if (($type != 'profile') && ($type != 'upemail')) {
            Gpf_Log::info('MailChimp webhook: Unsupported type ('.$type.'), skipping.');
            exit;
        }

        // prepare user data
        $user = new Pap_Common_User();
        if ($type == 'upemail') {
            $email = 'old_email';
        } else {
            $email = 'email';
        }
        Gpf_Log::info('MailChimp webhook: Trying to update user '.$_POST['data'][$email].'');

        // try to load the user
        try {
            $userFromDb = Pap_Affiliates_User::loadFromUsername($_POST['data'][$email]);
            $user->setId($userFromDb->getId());
            $user->load();
        } catch (Gpf_Exception $e) {
            Gpf_Log::info('MailChimp webhook: There was an error while loading user: '.$e->getMessage());
            exit;
        }

        // update and then save user changes
        if ($type == 'upemail') {
            $user->setEmail($_POST['data']['new_email']);
        } else {
            $user->setFirstName($_POST['data']['merges']['FNAME']);
            $user->setLastName($_POST['data']['merges']['LNAME']);
        }

        try {
            $user->save();
            Gpf_Log::info('MailChimp webhook: The user changes were applied successfully.');
        } catch (Gpf_Exception $e) {
            Gpf_Log::info('MailChimp webhook: The user changes were NOT applied, there was an error: '.$e->getMessage());
            exit;
        }
    }

    private function useSecure() {
        if (Gpf_Settings::get(MailChimpSynchro_Config::SECURE) == Gpf::YES) {
            return true;
        }
        return false;
    }

    private function getApiKey() {
        return Gpf_Settings::get(MailChimpSynchro_Config::API_KEY);
    }

    private function getListId() { // returns an array with ID(s)
        return explode(';', Gpf_Settings::get(MailChimpSynchro_Config::LIST_ID));
    }

    private function addNew() {
        if ($this->isAddAllAffiliates() || $this->isAddOnlyOnApprove()) {
            return true;
        }
        return false;
    }

    private function isOnlyUserSynchro() {
        if (Gpf_Settings::get(MailChimpSynchro_Config::ADD_NEW) == Gpf::NO) {
            return true;
        }
        return false;
    }

    private function isAddAllAffiliates() {
        if (Gpf_Settings::get(MailChimpSynchro_Config::ADD_NEW) == Gpf::YES) {
            return true;
        }
        return false;
    }

    private function isAddOnlyOnApprove() {
        if (Gpf_Settings::get(MailChimpSynchro_Config::ADD_NEW) == 'A') {
            return true;
        }
        return false;
    }
}
?>
