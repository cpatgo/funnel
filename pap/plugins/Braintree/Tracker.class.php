<?php
/**
 *   @copyright Copyright (c) 2016 Quality Unit s.r.o.
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
 * @package PostAffiliatePro plugins
 */
class Braintree_Tracker extends Pap_Tracking_CallbackTracker {

    /**
     * @return Braintree_Tracker
     */
    public function getInstance() {
        $tracker = new Braintree_Tracker();
        $tracker->setTrackerName('Braintree');
        return $tracker;
    }

    private function initBraintree() {
        $env = Gpf_Settings::get(Braintree_Config::ENVIRONMENT);
        Braintree_Configuration::environment(($env == '1') ? 'sandbox' : 'production');
        Braintree_Configuration::merchantId(Gpf_Settings::get(Braintree_Config::MERCHANT_ID));
        Braintree_Configuration::publicKey(Gpf_Settings::get(Braintree_Config::PUBLIC_KEY));
        Braintree_Configuration::privateKey(Gpf_Settings::get(Braintree_Config::PRIVATE_KEY));
    }

    private function getTransactionIdFromOrderId($orderId) {
        $transaction = new Pap_Common_Transaction();
        if ($output = $this->findFirstRecordWithData($transaction, Pap_Db_Table_Transactions::ORDER_ID, $orderId)) {
            $this->debug('Parent transaction found by orderId.');
            return $output->getId();
        }
        if ($output = $this->findFirstRecordWithData($transaction, Pap_Db_Table_Transactions::DATA2, $orderId)) {
            $this->debug('Parent transaction found by data2.');
            return $output->getId();
        }

        throw new Gpf_Exception('Parent transaction for order id: ' . $orderId . ' not found.');
    }

    protected function refundChargeback() {
        $transaction = new Pap_Common_Transaction();
        $transaction->processRefundChargeback($this->getTransactionIdFromOrderId($this->getOrderID()), Pap_Db_Transaction::TYPE_REFUND, '', $this->getOrderID(), 0, true);
    }

    private function changeTransactionStatus($orderId, $status) {
        $transaction = new Pap_Db_Transaction();
        $transaction->setOrderId($orderId);
        try {
            $transaction->load();
            $transaction->setStatus($status);
            $transaction->save();
        } catch (Gpf_DbEngine_NoRowException $e) {
            $this->debug('Cound not load transaction: ' . $e->getMessage());
            return false;
        }
    }

    public function checkStatus() {
        if ($this->getType() == Braintree_WebhookNotification::DISPUTE_LOST) {
            $this->debug('Transaction ' . $this->getOrderID() . ' will be refunded');
            try {
                $this->refundChargeback();
                $this->debug('Refund completed, ending process.');
            } catch (Gpf_Exception $e) {
                $this->debug('Error occurred during transaction refund:' . $e->getMessage());
            }
            return false;
        }

        if (($this->getType() == Braintree_WebhookNotification::DISPUTE_OPENED) && ($this->getType() == Braintree_WebhookNotification::DISPUTE_OPENED)) {
            $this->changeTransactionStatus($this->getOrderID(), 'P');
        }

        if (($this->getType() == Braintree_WebhookNotification::DISPUTE_WON) && ($this->getType() == Braintree_WebhookNotification::SUBSCRIPTION_CHARGED_SUCCESSFULLY)) {
            $this->changeTransactionStatus($this->getOrderID(), 'A');
        }

        if ($this->getType() == Braintree_WebhookNotification::SUBSCRIPTION_CANCELED) {
            $this->changeTransactionStatus($this->getOrderID(), 'D');
        }

        // check transaction type
        if ($this->getType() == Braintree_WebhookNotification::SUBSCRIPTION_CHARGED_SUCCESSFULLY) {
            return true;
        }

        $this->debug('Ignoring type "' . $this->getType() . '"');
        return false;
    }

    /**
     *  @return Braintree_Subscription
     */
    protected function getRequestObject() {
        if (isset($_POST['bt_signature']) && isset($_POST['bt_payload'])) {
            try {
                $webhookNotification = Braintree_WebhookNotification::parse($_POST['bt_signature'], $_POST['bt_payload']);
                $this->setType((string) $webhookNotification->kind);
            } catch (Braintree_Exception_InvalidSignature $e) {
                $this->debug('An error occurred: ' . $e->getMessage());
                return null;
            }

            $message = 'Webhook Received ' . $webhookNotification->timestamp->format('Y-m-d H:i:s') . ';' . 'Kind: ' . $webhookNotification->kind;

            $this->debug($message);
            $this->debug(print_r($webhookNotification->subscription, true));
            return $webhookNotification->subscription;
        }
        
        return null;
    }

    public function readRequestVariables() {
        $this->initBraintree();

        $request = $this->getRequestObject();
        if (empty($request)) {
            return;
        }

        $this->setTransactionID((string) $request->id);

        if ($this->getType() != Braintree_WebhookNotification::SUBSCRIPTION_CHARGED_SUCCESSFULLY) {
            return;
        }

        $this->setProductID((string) $request->planId);
        $this->setTotalCost($this->adjustTotalCost((float) $request->price));
        $this->setData1($request->transactions[0]->customerDetails->id);
        $this->setData2($request->transactions[0]->id);

        $this->setCookie($request->transactions[0]->customFields[(Gpf_Settings::get(Braintree_Config::CUSTOM_FIELD_NAME))]);

        $discount = @$request->transactions[0]->discounts[0];
        if (!empty($discount)) {
            $this->setCoupon($discount->id);
        }

        $this->setCurrency($request->transactions[0]->currencyIsoCode);

        if ($this->isAffiliateRegisterAllowed()) {
            $this->readRequestAffiliateVariables($request->transactions[0]);
        }
    }

    public function readRequestAffiliateVariables($transaction) {
        $this->setUserFirstName($transaction->customerDetails->firstName);
        $this->setUserLastName($transaction->customerDetails->lastName);
        $this->setUserEmail($transaction->customerDetails->email);
        $this->setUserCity($transaction->shippingDetails->locality);
        $this->setUserAddress($transaction->shippingDetails->streetAddress);
    }

    private function adjustTotalCost($total) {
        /* zero-decimal currencies need division by 100
         // official source: https://developers.braintreepayments.com/reference/general/currencies
         $zeroDecimals = array('BIF','DJF','GNF','JPY','KMF','KRW','LAK','PYG','RWF','VND','VUV','XAF','XOF','XPF');
         if (!in_array($this->getCurrency(),$zeroDecimals)) {
         return (float)$total/100;
         }*/
        return $total;
    }

    /**
     * @param String $orderId
     * @return String|boolean
     */
    protected function checkIfTransactionExists($orderId) {
        $transaction = new Pap_Common_Transaction();
        try {
            $result = $transaction->getFirstRecordWith(Pap_Db_Table_Transactions::ORDER_ID, $orderId, array(
                    Pap_Common_Constants::STATUS_APPROVED,
                    Pap_Common_Constants::STATUS_PENDING 
            ));
            return $result->getId();
        } catch (Gpf_DbEngine_NoRowException $e) {
            return false;
        }
    }

    protected function isAffiliateRegisterAllowed() {
        return (Gpf_Settings::get(Braintree_Config::CREATE_AFFILIATE) == Gpf::YES);
    }

    public function registerCommission() {
        if ($this->checkIfTransactionExists($this->getOrderID())) { // simulate recurrence
            $subscriptionId = $this->getOrderID();
            $this->setOrderID($this->getData2());
            $this->setData2($this->getOrderID());
        }
        parent::registerCommission();
    }

    public function getOrderID() {
        return $this->getTransactionID();
    }

    protected function getRecurringTotalCost() {
        return $this->getTotalCost();
    }

    public function isRecurring() {
        return $this->existRecurringCommission($this->getOrderID());
    }

    private function existRecurringCommission($orderId) {
        if (empty($orderId)) {
            return false;
        }
        return Pap_Features_RecurringCommissions_Main::isExistRecurringRule($orderId);
    }
}
