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
class s2Member_Tracker extends Pap_Tracking_CallbackTracker {

    const REQUEST_TYPE_SIGNUP = 'signup';
    const REQUEST_TYPE_ORDER = 'order';
    const REQUEST_TYPE_REFUND = 'refund';

    protected $userPass;
    protected $secret;
    protected $refid;
    protected $parentid;

    public function setSecret($secret) {
        $this->secret = $secret;
    }

    public function getSecret() {
        return $this->secret;
    }

    /**
     * @return s2Member_Tracker
     */
    public function getInstance() {
        $tracker = new s2Member_Tracker();
        $tracker->setTrackerName("s2Member");
        return $tracker;
    }

    private function refundChargeback() {
        $transaction = new Pap_Common_Transaction();
        $transaction->processRefundChargeback($this->getTransactionIdFromOrderId($this->getOrderID()), Pap_Db_Transaction::TYPE_REFUND, '',
                $this->getOrderID(), 0, true);
    }
    
    private function getTransactionIdFromOrderId($orderId){
        $transaction = new Pap_Common_Transaction();
        if ($output = $this->findFirstRecordWithData($transaction, Pap_Db_Table_Transactions::ORDER_ID, $orderId)) {
            $this->debug('Parent transaction for refund found by orderId.');
            return $output->getId();
        }
    
        throw new Gpf_Exception('Parent transaction for order id: ' . $orderId . ' not found.');
    }
    
    public function checkStatus() {
        $this->debug(' Type of transaction: '.$this->getType());

        if ($this->getType() == s2Member_Tracker::REQUEST_TYPE_REFUND)  {
            $this->debug('Transaction '.$this->getOrderID().' will be marked as a refund');
            try {
                $this->refundChargeback();
                $this->debug('Refund completed, ending.');
            } catch (Gpf_Exception $e) {
                $this->debug('Error occurred during transaction refund: ' . $e->getMessage());
            }

            return false;
        }
        
        if (Gpf_Settings::get(s2Member_Config::SECRET_WORD) == '') {
            $this->debug(' Secret word is missing! Configure it in your plugin configuration section first!');
            return false;
        }

        if (Gpf_Settings::get(s2Member_Config::SECRET_WORD)!= $this->getSecret()) {
            $this->debug(' Secret word received does not match the one from settings! Probably a fraud, stopping!');
            return false;
        }

        if (($this->getType() != s2Member_Tracker::REQUEST_TYPE_SIGNUP) && ($this->getType() != s2Member_Tracker::REQUEST_TYPE_ORDER)) {
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
        $this->debug("  Data received: ".print_r($_REQUEST,true));

        $request = $this->getRequestObject();

        // fix IP
        if ($request->getRequestParameter('user_ip') != '') {
            $_SERVER["REMOTE_ADDR"] = $request->getRequestParameter('user_ip');
        }

        if ($request->getRequestParameter('secret') != '') {
            $this->setSecret($request->getRequestParameter('secret'));
        }

        $this->setType($request->getRequestParameter('type'));
        $this->setCookie($request->getRequestParameter('visitorID'));

        $saleTracker = new Pap_Tracking_ActionTracker();
        $this->setVisitorAndAccount($saleTracker, '', '', $this->getCookie());
        $this->setAffiliateID($this->computeAffiliateId($this->getVisitorID(), $this->getAccountID()));

        if ($request->getRequestParameter('fname') != '') {
            $this->setUserFirstName($request->getRequestParameter('fname'));
        }
        if ($request->getRequestParameter('lname') != '')  {
            $this->setUserLastName($request->getRequestParameter('lname'));
        }
        if ($request->getRequestParameter('email') != '') {
            $this->setUserEmail($request->getRequestParameter('email'));
            $this->setData1($request->getRequestParameter('email'));
        }

        if ($request->getRequestParameter('order') != '') {
            $this->setTransactionID($request->getRequestParameter('order'));
            $this->setTotalCost($request->getRequestParameter('total'));
        }
    }

    public function registerCommission() {
        if ($this->getType() == s2Member_Tracker::REQUEST_TYPE_SIGNUP) {
            // no commission registration needed here
            return true;
        } elseif ($this->getType() == s2Member_Tracker::REQUEST_TYPE_ORDER) {
            $this->debug('Registering a recurring commission, params TotalCost='.$this->getTotalCost().'; OrderID='.$this->getOrderID());

            $recurringCommission = new Pap_Features_RecurringCommissions_RecurringCommissionsForm();
            try {
                if ($this->getTotalCost()) {
                    $recurringCommission->createCommissionsNoRpc($this->getOrderID(), null, $this->getTotalCost());
                } else {
                    $recurringCommission->createCommissionsNoRpc($this->getOrderID());
                }
            } catch (Exception $e) {
                $this->debug("Can not process recurring commission: ".$e->getMessage());
            }
        } elseif ($this->getType() == s2Member_Tracker::REQUEST_TYPE_REFUND) {
        	// nothing to do here
        }
    }

    public function getOrderID() {
        return $this->getTransactionID();
    }

    protected function isAffiliateRegisterAllowed() {
        return Gpf_Settings::get(s2Member_Config::REGISTER_AFFILIATE) == Gpf::YES;
    }
}
?>
