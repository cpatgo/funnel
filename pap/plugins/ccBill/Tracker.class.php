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
 *   http://www.postaffiliatepro.com/licenses/license
 *
 */

/**
 * @package PostAffiliatePro plugins
 */
class ccBill_Tracker extends Pap_Tracking_CallbackTracker {
    /**
     * @return ccBill_Tracker
     */
    public function getInstance() {
        $tracker = new ccBill_Tracker();
        $tracker->setTrackerName("ccBill");
        return $tracker;
    }

    private function getTransactionIdFromOrderId($orderId){
        $transaction = new Pap_Common_Transaction();
        if ($output = $this->findFirstRecordWithData($transaction, Pap_Db_Table_Transactions::ORDER_ID, $orderId)) {
            $this->debug('Parent transaction for refund found by orderId.');
            return $output->getId();
        }
        if ($output = $this->findFirstRecordWithData($transaction, Pap_Db_Table_Transactions::DATA2, $orderId)) {
            $this->debug('Parent transaction for refund found by data2.');
            return $output->getId();
        }

        throw new Gpf_Exception('Parent transaction for order id: '.$orderId.' not found.');
    }

    protected function refundChargeback() {
        $transaction = new Pap_Common_Transaction();
        $transaction->processRefundChargeback($this->getTransactionIdFromOrderId($this->getOrderID()), Pap_Db_Transaction::TYPE_REFUND, '',
                $this->getOrderID(), 0, true);
    }

    /* UserReactivation, NewSaleSuccess, NewSaleFailure, UpgradeSuccess, UpgradeFailure, UpSaleSuccess, UpSaleFailure, CrossSaleSuccess
     CrossSaleFailure, Cancellation, Expiration, BillingDateChange, CustomerDataUpdate, RenewalSuccess (Rebill), Renewal Failure (Declined Rebill)
    Chargeback, Refund, Void */

    public function checkStatus() {
        if ($this->isRefundChargeback()) {
            $debugMessage = '';
            if ($this->getSubscriptionID() != '') {
                $debugMessage = ' of transaction ' . $this->getSubscriptionID();
            }
            $this->debug('Transaction '.$this->getOrderID().' will be marked as a refund' . $debugMessage);
            try {
                $this->refundChargeback();
                $this->debug('Refund complete, ending processing.');
            }
            catch (Gpf_Exception $e) {
                $this->debug('Error occurred during transaction refund:' . $e->getMessage());
            }
            return false;
        }

        if (($this->getType() != "NewSaleSuccess") && ($this->getType() != "UpgradeSuccess") && ($this->getType() != "RenewalSuccess")
                && ($this->getType() != "UpSaleSuccess") && ($this->getType() != "CrossSaleSuccess")) {
            $this->debug('Ignoring this payment type: '.$this->getType());
            return false;
        }

        return true;
    }

    /**
     *  @return Pap_Tracking_Request
     */
    protected function getRequestObject() {
        return Pap_Contexts_Action::getContextInstance()->getRequestObject();
    }

    public function readRequestVariables() {
        $request = $this->getRequestObject();

        $_SERVER['REMOTE_ADDR'] = $request->getRequestParameter('ipAddress');
        $this->setType($request->getRequestParameter('eventType'));

        $cookie = stripslashes($request->getRequestParameter('PAP_COOKIE'));
        if ($cookie == '') {
            $cookie = stripslashes($request->getRequestParameter('X-PAP_COOKIE'));
        }
        $this->setCookie($cookie);

        $this->setTotalCost($request->getRequestParameter('subscriptionInitialPrice'));

        if (($this->getType() == 'RenewalSuccess') || ($this->getType() == 'Chargeback') || ($this->getType() == 'Refund') || ($this->getType() == 'Void')) {
            $this->setTotalCost($request->getRequestParameter('accountingAmount'));
        }

        $this->setTransactionID($request->getRequestParameter('transactionId'));
        $this->setSubscriptionID($request->getRequestParameter('subscriptionId'));
        $this->setProductID($request->getRequestParameter('productDesc'));
        $this->setEmail($request->getRequestParameter('consumerEmail'));
        $this->setCurrency($request->getRequestParameter('billedCurrency'));

        $this->setData1($request->getRequestParameter('consumerEmail'));
        $this->setData3($request->getRequestParameter('clientAccnum'));
        $this->setData4($request->getRequestParameter('clientSubacc'));

        if ($this->isRecurring()) {
            $this->setTotalCost($request->getRequestParameter('billedAmount'));
            $this->setData2($this->getTransactionID());
        }

        if ($this->isRefundChargeback()) {
            $this->setTotalCost($request->getRequestParameter('amount'));
            $this->setCurrency($request->getRequestParameter('currency'));
        }

        $this->readRequestAffiliateVariables($request);
    }

    public function isRecurring() {
        if (($this->getType() == 'RenewalSuccess') || ($this->existRecurringCommission($this->getTransactionID()))) {
            return true;
        }
        return false;
    }

    public function isRefundChargeback() {
        if (($this->getType() == "Chargeback") || ($this->getType() == "Refund") || ($this->getType() == "Void")) {
            return true;
        }
        return false;
    }
    
    protected function getRecurringTotalCost() {
        if (Gpf_Settings::get(ccBill_Config::RECURRING_TOTALCOST_FROM_NOTIFICATION) == Gpf::YES) {
            return $this->getTotalCost();
        }
        return null;
    }

    public function readRequestAffiliateVariables(Pap_Tracking_Request $request) {
        $this->setUserFirstName($request->getRequestParameter('firstName'));
        $this->setUserLastName($request->getRequestParameter('lastName'));
        $this->setUserEmail($request->getRequestParameter('consumerEmail'));
        $this->setUserCity($request->getRequestParameter('city'));
        $this->setUserAddress($request->getRequestParameter('address1'));
    }

    private function existRecurringCommission($orderId) {
        return Pap_Features_RecurringCommissions_Main::isExistRecurringRule($orderId);
    }

    protected function isAffiliateRegisterAllowed() {
        return (Gpf_Settings::get(ccBill_Config::REGISTER_AFFILIATE) == Gpf::YES);
    }

    public function getOrderID() {
        if ($this->isRecurring()) {
            return $this->getSubscriptionID();
        } else {
            return $this->getTransactionID();
        }
    }
}
?>
