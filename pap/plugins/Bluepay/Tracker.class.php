<?php
/**
 *   @copyright Copyright (c) 2014 Quality Unit s.r.o.
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
class Bluepay_Tracker extends Pap_Tracking_CallbackTracker {
    const TRANS_TYPE_AUTH = 'AUTH';
    const TRANS_TYPE_CAPTURE = 'CAPTURE';
    const TRANS_TYPE_CREDIT = 'CREDIT';
    const TRANS_TYPE_REFUND = 'REFUND';
    const TRANS_TYPE_SALE = 'SALE';
    const TRANS_TYPE_VOID = 'VOID';
    
    /**
     * @return Paymate_Tracker
     */
    public function getInstance() {
        $tracker = new Bluepay_Tracker();
        $tracker->setTrackerName('Bluepay');
        return $tracker;
    }
    
    private function getTransactionIdFromOrderId($orderId){
        $transaction = new Pap_Common_Transaction();
        if ($output = $this->findFirstRecordWithData($transaction, Pap_Db_Table_Transactions::ORDER_ID, $orderId)) {
            $this->debug('Parent transaction for the refund found by orderId.');
            return $output->getId();
        }

        throw new Gpf_Exception('Parent transaction for order id: '.$orderId.' not found.');
    }
    
    protected function refundChargeback() {
        $transaction = new Pap_Common_Transaction();
        $transaction->processRefundChargeback($this->getTransactionIdFromOrderId($this->getOrderID()), Pap_Db_Transaction::TYPE_REFUND, '',
            $this->getOrderID(), 0, true);
    }

    public function checkStatus() {
        if ($this->getPaymentStatus() != '1') {
            return false;
        }
        
        if (($this->getType() == self::TRANS_TYPE_REFUND) || ($this->getType() == self::TRANS_TYPE_VOID)) {
            $this->debug('Transaction '.$this->getOrderID().' will be marked as a refund.');
            $this->refundChargeback();
            return false;
        }
        
        if (($this->getType() == self::TRANS_TYPE_SALE) || ($this->getType() == self::TRANS_TYPE_CREDIT) || ($this->getType() == self::TRANS_TYPE_CAPTURE)) {
            return true;
        }
        
        return false;
    }

    protected function getRequestObject() {
        return Pap_Contexts_Action::getContextInstance()->getRequestObject();
    }

    public function readRequestVariables() {
        $request = $this->getRequestObject();
        $this->debug('Notification data received: '.print_r($request, true));
        
        $customIdField = 'custom_id'.Gpf_Settings::get(Bluepay_Config::CUSTOM_ID);
        $this->debug('Custom field which we will work with: '.$customIdField);
        
        $cookieValue = $request->getRequestParameter($customIdField);
        
        try {
            $customSeparator = Gpf_Settings::get(Bluepay_Config::CUSTOM_SEPARATOR);
            if ($customSeparator != '') {
                $explodedCookieValue = explode($customSeparator, $cookieValue, 2);
                if (count($explodedCookieValue) == 2) {
                    $cookieValue = $explodedCookieValue[1];
                }
            }
        } catch (Gpf_Exception $e) {
        }
        
        $this->setCookie($cookieValue);
        $this->setTotalCost($request->getRequestParameter('amount'));
        $this->setTransactionID($request->getRequestParameter('trans_id'));
        $this->setSubscriptionID($request->getRequestParameter('master_id'));
        $this->setPaymentStatus($request->getRequestParameter('trans_status')); // '1' for approved, '0' for declined, 'E' for error.
        $this->setType($request->getRequestParameter('trans_type')); // 'AUTH', 'CAPTURE', 'CREDIT', 'REFUND', 'SALE', 'VOID'
        
        $this->setEmail($request->getRequestParameter('email'));
        $this->setData1($request->getRequestParameter('email'));
        $this->setData2($request->getRequestParameter('invoice_id'));
        
        if (Gpf_Settings::get(Bluepay_Config::CREATE_AFFILIATE) == Gpf::YES) {
            $this->readRequestAffiliateVariables($request);
        }
        
        echo '200'; // send a confirmation message that we've received data successfully
    }
    
    public function readRequestAffiliateVariables(Pap_Tracking_Request $request) {
        $this->setUserFirstName($request->getRequestParameter('name1'));
        $this->setUserLastName($request->getRequestParameter('name2'));
        $this->setUserEmail($request->getRequestParameter('email'));
        $this->setUserCity($request->getRequestParameter('city'));
        $this->setUserAddress($request->getRequestParameter('addr1').$request->getRequestParameter('addr2'));
    }
    
    protected function isAffiliateRegisterAllowed() {
        return (Gpf_Settings::get(Bluepay_Config::CREATE_AFFILIATE) == Gpf::YES);
    }

    public function isRecurring() {
        if ($this->getType() == self::TRANS_TYPE_CAPTURE) {
            return true;
        }
        return false;
    }

    public function getOrderID() {
        if ($this->isRecurring()) {
            return $this->getSubscriptionID();
        }
        return $this->getTransactionID();
    }
}
?>
