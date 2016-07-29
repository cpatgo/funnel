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
class LimeLight_Tracker extends Pap_Tracking_CallbackTracker {

    private function getTransactionIdFromOrderId($orderId) {
        $transaction = new Pap_Common_Transaction();
        if ($output = $this->findFirstRecordWithData($transaction, Pap_Db_Table_Transactions::ORDER_ID, $orderId)) {
            $this->debug('Parent transaction for refund found by orderId.');
            return $output->getId();
        }
        if ($output = $this->findFirstRecordWithData($transaction, Pap_Db_Table_Transactions::DATA2, $orderId)) {
            $this->debug('Parent transaction for refund found by data2.');
            return $output->getId();
        }
        
        throw new Gpf_Exception('Parent transaction for order id: ' . $orderId . ' not found.');
    }

    /**
     * @return LimeLight_Tracker
     */
    public function getInstance() {
        $tracker = new LimeLight_Tracker();
        $tracker->setTrackerName("LimeLight");
        return $tracker;
    }

    protected function refundChargeback() {
        $transaction = new Pap_Common_Transaction();
        $transaction->processRefundChargeback($this->getTransactionIdFromOrderId($this->getOrderID()), Pap_Db_Transaction::TYPE_REFUND, '', $this->getOrderID(), 0, true);
    }

    public function checkStatus() {
        if (Gpf_Settings::get(LimeLight_Config::DECLINE_AFFILIATE) == Gpf::YES) {
            $this->declineAffiliate();
        }
        
        if ($this->getType() == 'refund') {
            $this->debug('Transaction ' . $this->getOrderID() . ' will be marked as refund of transaction ' . $this->getOrderID());
            try {
                $this->refundChargeback();
                $this->debug('Refund complete, ending processing.');
            } catch (Gpf_Exception $e) {
                $this->debug('Error occurred during transaction refund:' . $e->getMessage());
            }
            return false;
        }
        
        if ($this->getPaymentStatus() == '0') {
            $this->debug('Declined transaction, end processing.');
            return false;
        }
        
        if (($this->getType() == 'initial') || ($this->getType() == 'recurring')) {
            return true;
        }
        
        $this->debug('Not successful state (' . $this->getType() . '), end processing.');
        return false;
    }

    private function declineAffiliate() {
        if (($this->getType() == 'refund') || ($this->getType() == 'cancel')) {
            try {
                $affiliate = new Pap_Affiliates_User();
                $affiliate = $affiliate->loadFromUsername($this->getEmail());
                
                if ($affiliate->getStatus() != Pap_Common_Constants::STATUS_APPROVED) {
                    $affiliate->setStatus(Pap_Common_Constants::STATUS_DECLINED);
                    $affiliate->update(array(Gpf_Db_Table_Users::STATUS));
                    $this->debug('Affiliate with username = ' . $this->getEmail() . ' has been declined after status ' . $this->getType());
                }
            } catch (Gpf_Exception $e) {
                $this->debug('Error occurred during declining the affiliate ' . $this->getEmail() . ' [status ' . $this->getType() . ' received]. Exception: ' . $e->getMessage());
            }
        }
    }

    /**
     *  @return Pap_Tracking_Request
     */
    protected function getRequestObject() {
        return Pap_Contexts_Action::getContextInstance()->getRequestObject();
    }

    public function readRequestVariables() {
        $request = $this->getRequestObject();
        
        $this->debug('Data received: ' . print_r($request, true));
                
        // fix IP
        $_SERVER["REMOTE_ADDR"] = $request->getRequestParameter('i');
        
        $f = '';
        if ($request->getRequestParameter('f')) {
            $f = 'test_';
        }
        
        $this->setCookie($request->getRequestParameter('a'));
        $this->setTotalCost($request->getRequestParameter('t'));
        $this->setTransactionID($f . $request->getRequestParameter('o'));
        $this->setSubscriptionID($f . $request->getRequestParameter('d2'));
        $this->setProductID($request->getRequestParameter('pr'));
        
        $this->setType($request->getRequestParameter('p')); // initial, cancel, refund
        $this->setPaymentStatus($request->getRequestParameter('s')); // 1 or 0
        

        $this->setEmail($request->getRequestParameter('d1'));
        $this->setCurrency($request->getRequestParameter('c'));
        $this->setData1($request->getRequestParameter('d1'));
        $this->setData2($this->getSubscriptionID());
        
        $this->readRequestAffiliateVariables($request);
    }

    public function readRequestAffiliateVariables(Pap_Tracking_Request $request) {
        $this->setUserFirstName($request->getRequestParameter('fn'));
        $this->setUserLastName($request->getRequestParameter('ln'));
        $this->setUserEmail($request->getRequestParameter('d1'));
        $this->setUserCity($request->getRequestParameter('sc'));
        $this->setUserAddress($request->getRequestParameter('sa'));
    }

    public function isRecurring() {
        if ($this->getType() == 'recurring') {
            return true;
        }
        return false;
    }

    public function getOrderID() {
        if ($this->isRecurring()) {
            return $this->getSubscriptionID();
        } else {
            return $this->getTransactionID();
        }
    }

    protected function isAffiliateRegisterAllowed() {
        return (Gpf_Settings::get(LimeLight_Config::REGISTER_AFFILIATE) == Gpf::YES);
    }
}
?>
