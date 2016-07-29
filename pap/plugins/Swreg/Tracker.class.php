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
class Swreg_Tracker extends Pap_Tracking_CallbackTracker {

    /**
     * @return Swreg_Tracker
     */
    public function getInstance() {
        $tracker = new Swreg_Tracker();
        $tracker->setTrackerName("Swreg");
        return $tracker;
    }

    private function getTransactionIdFromOrderId($orderId){
        $transaction = new Pap_Common_Transaction();
        try {
            $output = $transaction->getFirstRecordWith(Pap_Db_Table_Transactions::ORDER_ID, $orderId, array(Pap_Common_Constants::STATUS_APPROVED, Pap_Common_Constants::STATUS_PENDING));
            $this->debug('Parent transaction for refund found by orderId.');
            return $output->getId();
        } catch (Gpf_DbEngine_NoRowException $e) {
            return false;
        }
        return $output->getId();
    }

    protected function refundChargeback() {
        $transaction = new Pap_Common_Transaction();
        $transactionId = '';
        $transactionId = $this->getTransactionIdFromOrderId($this->getOrderID());
        if ($transactionId != false) {
            $transaction->processRefundChargeback($transactionId, Pap_Db_Transaction::TYPE_REFUND, '', $this->getOrderID(), 0, true);
        } else {
            for ($i = 1; $i <= 20; $i++) {
                $transactionId = $this->getTransactionIdFromOrderId($this->getOrderID().'-'.$i);
                if ($transactionId != false) {
                    $transaction->processRefundChargeback($transactionId, Pap_Db_Transaction::TYPE_REFUND, '', $this->getOrderID().'-'.$i, 0, true);
                }
            }
        }
    }

    public function checkStatus() {
        if ($this->getOrderID() == '') {
            return false;
        }

        if ($this->getPaymentStatus() == "refunded") {
            $this->debug('Transaction '.$this->getOrderID().' will be marked as refund');
            try {
                $this->refundChargeback();
                $this->debug('Refund completed.');
            } catch (Gpf_Exception $e) {
                $this->debug('Error occurred during transaction refund:' . $e->getMessage());
            }
            return false;
        }
        if ($this->getPaymentStatus() == "new_order") {
            $this->findAndApprove($this->getOrderID());
            return false;
        }
        return false;
    }

    protected function getTransactionsList($orderID) {
        $types = array(Pap_Common_Constants::TYPE_SALE, Pap_Common_Constants::TYPE_ACTION, Pap_Common_Constants::TYPE_LEAD, Pap_Common_Constants::TYPE_RECURRING);

        $select = new Gpf_SqlBuilder_SelectBuilder();

        $select->select->addAll(Pap_Db_Table_Transactions::getInstance(), 't');
        $select->from->add(Pap_Db_Table_Transactions::getName(), 't');
        $select->where->add(Pap_Db_Table_Transactions::R_TYPE, "IN", $types);
        $select->where->add(Pap_Db_Table_Transactions::R_STATUS, "=", Pap_Common_Constants::STATUS_PENDING);
        $select->where->add(Pap_Db_Table_Transactions::ORDER_ID, 'LIKE', '%'.$orderID.'%');
        $transaction = new Pap_Common_Transaction();
        return $transaction->loadCollectionFromRecordset($select->getAllRows());
    }

    protected function findAndApprove($orderID) {
        $transactions = $this->getTransactionsList($orderID);
        $this->debug('Found '.$transactions->getSize().' transactions for order '.$orderID.'to be approved...');

        $commTypeAttr = Pap_Db_Table_CommissionTypeAttributes::getInstance();
        foreach ($transactions as $transaction) {
            $transaction->setStatus(Pap_Common_Constants::STATUS_APPROVED);
            $transaction->save();
            $this->debug('Transacton id: ' . $transaction->getId() . ' has been approved.');
        }
    }

    /**
     *  @return Pap_Tracking_Request
     */
    protected function getRequestObject() {
        $post_data = file_get_contents('php://input');
        if (get_magic_quotes_gpc()) {
            $post_data = stripslashes($post_data);
        }
        return json_decode($post_data);
    }

    public function readRequestVariables() {
        $request = $this->getRequestObject();
        $this->debug('Status received: '.$_GET['type'].'; Data received: '.print_r($request,true));

        $this->setTransactionID($request->order_no);
        $this->setPaymentStatus($_GET['type']);
    }

    public function isRecurring() {
        return false;
    }

    public function getOrderID() {
        return $this->getTransactionID();
    }
}
?>
