<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @author Maros Fric
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
 * @package PostAffiliatePro plugins
 */
class AlertPay_Tracker extends Pap_Tracking_CallbackTracker {

    private $testMode;
    private $securityCode;
    private $purchaseType;
    private $ipnv2 = false;

    /**
     * @return AlertPay_Tracker
     */
    public function getInstance() {
        $tracker = new AlertPay_Tracker();
        $tracker->setTrackerName('AlertPay');
        return $tracker;
    }

    public function checkStatus() {
        if ($this->ipnv2 == false) { // verify by security code
            if ($this->securityCode != Gpf_Settings::get(AlertPay_Config::SECURITY_CODE)) {
                $this->debug('Invalid security code. Received security code: '.$this->securityCode. ' Transaction: '.$this->getTransactionID().', payer email: '.$this->getEmail());
                return false;
            }
        }

        if (Gpf_Settings::get(AlertPay_Config::DECLINE_AFFILIATE) == Gpf::YES) {
            if ((strtolower($this->getPaymentStatus()) == 'subscription-canceled') || (strtolower($this->getPaymentStatus()) == 'subscription-expired')) {
                $this->declineAffiliate($this->getEmail());
            }
        }

        if ($this->testMode == '1' && Gpf_Settings::get(AlertPay_Config::ALLOW_TEST_SALES) != Gpf::YES) {
            $this->debug('Test sales are not registered. If you want to register test sales, turn it on in plugin configuration. Transaction: '.$this->getTransactionID().', payer email: '.$this->getEmail());
            return false;
        }

        if (($this->getPaymentStatus() == 'Success') || ($this->getPaymentStatus() == 'Subscription-Payment-Success')) {
            return true;
        }

        $this->debug('Payment status is not Success! Transaction: '.$this->getTransactionID().', status: '.$this->getPaymentStatus().', payer email: '.$this->getEmail());
        return false;
    }

    private function declineAffiliate($username) {
        try {
            $affiliate = new Pap_Affiliates_User();
            $affiliate = $affiliate->loadFromUsername($username);

            if ($affiliate->getStatus() != Pap_Common_Constants::STATUS_DECLINED) {
                $affiliate->setStatus(Pap_Common_Constants::STATUS_DECLINED);
                $affiliate->update(array(Gpf_Db_Table_Users::STATUS));
                $this->debug("Affiliate with username $username has been declined after their subscription was cancelled.");
            }
        } catch (Gpf_Exception $e) {
            $this->debug('Error occurred during declining the affiliate '.$username.'. Exception: '. $e->getMessage());
        }
    }

    private function getIPNDataByToken($token) {
        $response = '';
        $this->debug("Token received: $token");

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://secure.payza.com/ipn2.ashx');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'token='.$token);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if (!$response) {
            $this->debug("An error occurred while retrieving data from Payza: $error");
            return false;
        }

        $response = urldecode($response);
        if($response == 'INVALID TOKEN') {
            $this->debug("An error occurred while retrieving data from Payza: INVALID TOKEN");
            return false;
        }

        $info = array();
        $pairs = explode("&", $response);
        foreach ($pairs as $pair) {
            $row = explode("=", $pair);
            $info[$row[0]] = $row[1];
        }

        $this->debug('Data received from IPNv2: '.print_r($info,true));

        $this->testMode = $info['ap_test'];
        $this->ipnv2 = true;
        $this->purchaseType = $info['ap_purchasetype'];

        // assign posted variables to local variables
        $cookieValue = $info['apc_'.Gpf_Settings::get(AlertPay_Config::CUSTOM_FIELD_NUMBER)];
        $this->setCookie($cookieValue);
        $this->setTotalCost($info['ap_totalamount']);
        $this->setTransactionID($info['ap_referencenumber']);
        $this->setEmail($info['ap_custemailaddress']);
        $this->setProductID($info['ap_itemname']);
        $this->setPaymentStatus($info['ap_status']);
        $this->setCurrency($info['ap_currency']);
        $this->setData1($info['ap_custemailaddress']);

        $this->readRequestAffiliateVariables($info);
    }

    /**
     *  @return Pap_Tracking_Request
     */
    protected function getRequestObject() {
        return Pap_Contexts_Action::getContextInstance()->getRequestObject();
    }

    public function readRequestVariables() {
        $request = $this->getRequestObject();

        if ($request->getRequestParameter('token') != '') { // IPNv2
            $this->getIPNDataByToken($request->getRequestParameter('token'));
            return;
        }

        $this->debug('Data received: '.print_r($request,true));
        	
        $this->testMode = $request->getPostParam('ap_test');
        $this->securityCode = $request->getPostParam('ap_securitycode');
        $this->purchaseType = $request->getPostParam('ap_purchasetype');
        	
        // assign posted variables to local variables
        $cookieValue = stripslashes($request->getPostParam('apc_'.Gpf_Settings::get(AlertPay_Config::CUSTOM_FIELD_NUMBER)));
        $this->setCookie($cookieValue);
        $this->setTotalCost($request->getPostParam('ap_totalamount'));
        $this->setTransactionID($request->getPostParam('ap_referencenumber'));
        $this->setEmail($request->getPostParam('ap_custemailaddress'));
        $this->setProductID($request->getPostParam('ap_itemname'));
        $this->setPaymentStatus($request->getPostParam('ap_status'));
        $this->setCurrency($request->getPostParam('ap_currency'));
        $this->setData1($request->getPostParam('ap_custemailaddress'));

        $affiliateData = array();
        $affiliateData['ap_custfirstname'] = $request->getPostParam('ap_custfirstname');
        $affiliateData['ap_custlastname'] = $request->getPostParam('ap_custlastname');
        $affiliateData['ap_custemailaddress'] = $request->getPostParam('ap_custemailaddress');
        $affiliateData['ap_custcity'] = $request->getPostParam('ap_custcity');
        $affiliateData['ap_custaddress'] = $request->getPostParam('ap_custaddress');
        $this->readRequestAffiliateVariables($affiliateData);
    }

    public function readRequestAffiliateVariables($affiliateData) {
        $this->setUserFirstName($affiliateData['ap_custfirstname']);
        $this->setUserLastName($affiliateData['ap_custlastname']);
        $this->setUserEmail($affiliateData['ap_custemailaddress']);
        $this->setUserCity($affiliateData['ap_custcity']);
        $this->setUserAddress($affiliateData['ap_custaddress']);
    }

    protected function isAffiliateRegisterAllowed() {
        return (Gpf_Settings::get(AlertPay_Config::CREATE_AFFILIATE) == Gpf::YES);
    }

    public function isRecurring() {
        return $this->purchaseType == 'Subscription' &&
        (Gpf_Settings::get(AlertPay_Config::DIFF_RECURRING_COMMISSIONS) == Gpf::YES);
    }

    public function getOrderID() {
        return $this->getTransactionID();
    }

    protected function processSubscriptionPayment() {
        $this->debug('Start registering recurring payment / subscription');

        $recurringComm = new Pap_Features_RecurringCommissions_RecurringCommissionsForm();
        $recurringComm->createCommissionsNoRpc($this->getOrderID());

        $this->debug('End registering recurring payment / subscription');
    }
}
?>
