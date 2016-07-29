<?php
/**
 *   @copyright Copyright (c) 2009 Quality Unit s.r.o.
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
class PaySiteCash_Tracker extends Pap_Tracking_CallbackTracker {
    
    const PAYMENT_STATUS_OK = 'ok';
    const PAYMENT_STATUS_REFUND = 'refund';
    const PAYMENT_STATUS_CHARGEBACK = 'chargeback';

    /**
     * @return Paymate_Tracker
     */
    public function getInstance() {
        $tracker = new PaySiteCash_Tracker();
        $tracker->setTrackerName("PaySiteCash");
        return $tracker;
    }

    private function getTransactionIdFromOrderId($orderId){
        $transaction = new Pap_Common_Transaction();
        try {
            $result = $transaction->getFirstRecordWith(Pap_Db_Table_Transactions::ORDER_ID, $orderId, array(Pap_Common_Constants::STATUS_APPROVED, Pap_Common_Constants::STATUS_PENDING));
            $this->debug('Parent transaction for refund found.');
            return $result->getId();
        }
        catch (Gpf_DbEngine_NoRowException $e) {
            $this->debug('Error occurred: '.$e->getMessage());
            return false;
        }
    }

    protected function refundChargeback($type = Pap_Db_Transaction::TYPE_REFUND) {
        $transaction = new Pap_Common_Transaction();
        $transaction->processRefundChargeback($this->getTransactionIdFromOrderId($this->getOrderID()), $type, '', $this->getOrderID(), 0, true);
        $this->debug('The refund transaction has been created');
    }

    public function checkStatus() {
        if ($this->getPaymentStatus() == self::PAYMENT_STATUS_OK) {
            return true;
        }

        if ($this->getPaymentStatus() == self::PAYMENT_STATUS_REFUND) {
            $this->debug('Transaction '.$this->getOrderID().' is a refund.');
            $this->refundChargeback();
            return false;
        }
        
        if ($this->getPaymentStatus() == self::PAYMENT_STATUS_CHARGEBACK) {
            $this->debug('Transaction '.$this->getOrderID().' is a chargeback.');
            $this->refundChargeback(Pap_Db_Transaction::TYPE_CHARGE_BACK);
            return false;
        }

        return false;
    }

    protected function getRequestObject() {
        $request = Pap_Contexts_Action::getContextInstance()->getRequestObject();
        $this->debug('Data received: '.print_r($request,true));

        return $request;
    }

    public function readRequestVariables() {
        $request = $this->getRequestObject();
        $_SERVER["REMOTE_ADDR"] = $request->getRequestParameter('ip');

        $cookieValue = stripslashes($request->getRequestParameter('divers'));
        $this->setCookie($cookieValue);

        $this->setPaymentStatus($request->getRequestParameter('etat'));

        $this->setProductID($request->getRequestParameter('site'));
        $this->setTotalCost($request->getRequestParameter('montant_sent'));
        $this->setTransactionID($request->getRequestParameter('id_trans'));
    }

    public function isRecurring() {
        return false;
    }

    public function getOrderID() {
        return $this->getTransactionID();
    }
}
?>
