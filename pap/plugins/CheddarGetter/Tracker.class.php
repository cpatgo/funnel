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
class CheddarGetter_Tracker extends Pap_Tracking_CallbackTracker {

    const SUBSCR_NEW = 'newSubscription';
    const SUBSCR_CHANGED = 'subscriptionChanged';
    const SUBSCR_CANCELED = 'subscriptionCanceled';
    const SUBSCR_REACTIV = 'subscriptionReactivated';
    const CUST_DEL = 'customerDeleted';
    const TRANS = 'transaction';

    /**
     * @return CheddarGetter_Tracker
     */
    public function getInstance() {
        $tracker = new CheddarGetter_Tracker();
        $tracker->setTrackerName("CheddarGetter");
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

    /**
     * @param Pap_Common_Transaction $transaction
     * @param String $data
     * @param String $orderId
     * @return String|boolean
     */
    private function findFirstRecordWithData($transaction, $data, $orderId) {
        try {
            $result = $transaction->getFirstRecordWith($data, $orderId, array(Pap_Common_Constants::STATUS_APPROVED, Pap_Common_Constants::STATUS_PENDING));
        } catch (Gpf_DbEngine_NoRowException $e) {
            return false;
        }
        return $result;
    }

    protected function refundChargeback() {
        $transaction = new Pap_Common_Transaction();
        $transaction->processRefundChargeback($this->getTransactionIdFromOrderId($this->getData2()), Pap_Db_Transaction::TYPE_REFUND, '',
                $this->getOrderID(), 0, true);
    }

    public function checkStatus() {
        echo '200'; // respond to the cheddar server that we received data

        if (Gpf_Settings::get(CheddarGetter_Config::DECLINE_AFFILIATE) == Gpf::YES) {
            $this->declineAffiliate();
        }

        if ($this->getTotalCost() < 0) { // refund
            $this->debug('Transaction '.$this->getOrderID().' will be marked as a refund.');
            try {
                $this->refundChargeback();
                $this->debug('Refund complete, ending processing.');
            } catch (Gpf_Exception $e) {
                $this->debug('Error occurred during transaction refund:'.$e->getMessage());
            }
            return false;
        }

        // check payment status
        if ($this->getPaymentStatus() != "approved") { // approved, declined, error, voided
            $this->debug('Payment was not approved! Transaction ('.$this->getType().'): '.$this->getTransactionID().', payer email: '.$this->getEmail().', status: '.$this->getPaymentStatus());
            return false;
        }

        // check transaction type
        if ($this->getType() != self::TRANS) {
            $this->debug("Ignoring type '".$this->getType()."', no commission needed.");
            return false;
        }

        return true;
    }

    private function declineAffiliate() {
        if ($this->getType() == self::SUBSCR_CANCELED) {
            try {
                $affiliate = new Pap_Affiliates_User();
                $affiliate = $affiliate->loadFromUsername($_POST['customer']['email']);

                if ($affiliate->getStatus() != Pap_Common_Constants::STATUS_APPROVED) {
                    $affiliate->setStatus(Pap_Common_Constants::STATUS_DECLINED);
                    $affiliate->update(array(Gpf_Db_Table_Users::STATUS));
                    $this->debug('Affiliate with username = '.$_POST['customer']['email'].' has been declined after status '.$this->getType());
                }
            } catch (Gpf_Exception $e) {
                $this->debug('Error occurred during declining the affiliate '.$_POST['customer']['email'].' [status '.$this->getType().' received]. Exception: '. $e->getMessage());
            }
        }
    }

    public function getCookieValue($metaData) {
        foreach ($metaData as $meta) {
            if ($meta['name'] == Gpf_Settings::get(CheddarGetter_Config::COOKIE_FIELD))
                return $meta['value'];
        }
        return '';
    }

    public function readRequestVariables() {
        $this->debug(' CheddarGetter POST: '.print_r($_POST, true));

        $cookie = $this->getCookieValue($_POST['customer']['metaData']);
        $this->setCookie($cookie);
        $this->setType($_POST['activityType']);
        $this->setEmail($_POST['customer']['email']);

        if (isset($_POST['subscription']['invoice']['transaction'])) {
            $this->readTransactionVariables();
        } else {
            $this->debug(' CheddarGetter no payment details -> no transactions creation');
        }

        if (Gpf_Settings::get(CheddarGetter_Config::REGISTER_AFFILIATE) == Gpf::YES) {
            $this->readRequestAffiliateVariables();
        }
    }
    
    public function process() {
        parent::process();
        if (!$this->checkStatus() && $this->isAffiliateRegisterAllowed()) {
            $this->registerAffiliate();
        }
    }
    
    private function readTransactionVariables() {
        $this->setTotalCost($_POST['subscription']['invoice']['transaction']['amount']);
        $this->setTransactionID($_POST['subscription']['invoice']['invoiceNumber']);
        $this->setProductID($_POST['subscription']['plan']['code']);
        $this->setPaymentStatus($_POST['subscription']['invoice']['transaction']['response']);
        $this->setData1($_POST['customer']['email']);
        $this->setData2($_POST['subscription']['id']);
    }

    public function readRequestAffiliateVariables() {
        $this->setUserFirstName($_POST['customer']['firstName']);
        $this->setUserLastName($_POST['customer']['lastName']);
        $this->setUserEmail($_POST['customer']['email']);
        $this->setUserCity($_POST['subscription']['ccCity']);
        $this->setUserAddress($_POST['subscription']['ccAddress']);
    }

    public function isRecurring() {
        return false;
    }

    public function getOrderID() {
        return $this->getTransactionID();
    }

    protected function isAffiliateRegisterAllowed() {
        return (Gpf_Settings::get(CheddarGetter_Config::REGISTER_AFFILIATE) == Gpf::YES);
    }
}
?>
