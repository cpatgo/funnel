<?php
/**
 *   @copyright Copyright (c) 2009 Quality Unit s.r.o.
 *   @author Juraj Simon
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
class AuthorizeNet_Tracker extends Pap_Tracking_CallbackTracker {

    private $subscriptionPaynum;
    private $invoiceNumber;
    private $type;

    protected function getPaymentType() {
        return $this->type;
    }

    protected function setPaymentType($type) {
        $this->type = $type;
    }

    protected function getSubscriptionPaynum() {
        return $this->subscriptionPaynum;
    }

    protected function setSubscriptionPaynum($paynum) {
        $this->subscriptionPaynum = $paynum;
    }

    protected function getInvoiceNumber() {
        return $this->invoiceNumber;
    }

    protected function setInvoiceNumber($invoicenumber) {
        $this->invoiceNumber = $invoicenumber;
    }

    protected function discountFromTotalcost($totalcost, $value) {
        if (($value != '') && (is_numeric($value))) {
            return $totalcost - $value;
        }
        return $totalcost;
    }

    private function adjustTotalCost($originalTotalCost, Pap_Tracking_Request $request) {
        $totalCost = $originalTotalCost;
        $this->debug('Original totalcost: ' . $totalCost);
        
        if (Gpf_Settings::get(AuthorizeNet_Config::DISCOUNT_TAX) == Gpf::YES) {
            $totalCost = $this->discountFromTotalcost($totalCost, $request->getPostParam('x_tax'));
            $this->debug('Discounting tax (' . $request->getPostParam('x_tax') . ') from totalcost.');
        }
        if (Gpf_Settings::get(AuthorizeNet_Config::DUTY_TAX) == Gpf::YES) {
            $totalCost = $this->discountFromTotalcost($totalCost, $request->getPostParam('x_duty'));
            $this->debug('Discounting duty tax (' . $request->getPostParam('x_duty') . ') from totalcost.');
        }
        if (Gpf_Settings::get(AuthorizeNet_Config::FREIGHT_TAX) == Gpf::YES) {
            $totalCost = $this->discountFromTotalcost($totalCost, $request->getPostParam('x_freight'));
            $this->debug('Discounting freight tax (' . $request->getPostParam('x_freight') . ') from totalcost.');
        }
        $this->debug('Totalcost after discounts: ' . $totalCost);
        return $totalCost;
    }

    /**
     *
     * @return Pap_Common_Transaction
     */
    protected function getParentTransaction($subscriptionId) {
        $select = new Gpf_SqlBuilder_SelectBuilder();
        
        $select->select->addAll(Pap_Db_Table_Transactions::getInstance());
        $select->from->add(Pap_Db_Table_Transactions::getName());
        $select->where->add(Pap_Db_Table_Transactions::DATA5, "=", $subscriptionId);
        
        $select->where->add(Pap_Db_Table_Transactions::R_TYPE, "IN", array(
                Pap_Common_Constants::TYPE_SALE,
                Pap_Common_Constants::TYPE_ACTION,
                Pap_Common_Constants::TYPE_LEAD 
        ));
        $select->where->add(Pap_Db_Table_Transactions::TIER, "=", "1");
        
        $select->limit->set(0, 1);
        $t = new Pap_Common_Transaction();
        $t->fillFromRecord($select->getOneRow());
        
        return $t;
    }

    protected function getTransactionObject($subscriptionId) {
        //we matching for invoice number not subscription id!
        // filter out empty or nulled ones
        if ($this->getInvoiceNumber() == '') {
            return null;
        }
        $this->debug('Looking for transaction with invoice number: ' . $this->getInvoiceNumber());
        return $this->getParentTransaction($this->getInvoiceNumber());
    }

    protected function checkSubsriptionId() {
        return false;
    }

    /**
     * @return AuthorizeNet_Tracker
     */
    public function getInstance() {
        $tracker = new AuthorizeNet_Tracker();
        $tracker->setTrackerName("AuthorizeNet");
        return $tracker;
    }

    public function checkStatus() {
        $code = $this->getPaymentStatus();
        
        if ($code != 1) {
            $this->debug('Transaction failed');
            return false;
        }
        
        if ($this->getPaymentType() == 'credit') { // refund?
            $this->debug('Transaction ' . $this->getOrderID() . ' will be marked as a refund of transaction ' . $this->getData4());
            try {
                $this->refundChargeback();
                $this->debug('Refund completed, ending processing.');
            } catch (Gpf_Exception $e) {
                $this->debug('Error occurred during transaction register:' . $e->getMessage());
            }
            return false;
        }
        
        return true;
    }

    protected function refundChargeback() {
        $transaction = new Pap_Common_Transaction();
        $transaction->processRefundChargeback($this->getTransactionIdFromOrderId($this->getData4()), Pap_Db_Transaction::TYPE_REFUND, '', $this->getOrderID(), 0, true);
    }

    private function getTransactionIdFromOrderId($orderId) {
        $transaction = new Pap_Common_Transaction();
        try {
            $output = $transaction->getFirstRecordWith(Pap_Db_Table_Transactions::ORDER_ID, $orderId, array(
                    Pap_Common_Constants::STATUS_APPROVED,
                    Pap_Common_Constants::STATUS_PENDING 
            ));
            $this->debug('Parent transaction for refund found by orderId.');
        } catch (Gpf_DbEngine_NoRowException $e) {
            $output = $transaction->getFirstRecordWith(Pap_Db_Table_Transactions::DATA4, $orderId, array(
                    Pap_Common_Constants::STATUS_APPROVED,
                    Pap_Common_Constants::STATUS_PENDING 
            ));
            $this->debug('Parent transaction for refund found by data1.');
        }
        return $output->getId();
    }

    protected function getRequestObject() {
        return Pap_Contexts_Action::getContextInstance()->getRequestObject();
    }

    public function readRequestVariables() {
        $this->debug('Request data: ' . print_r($_POST, true));
        $request = $this->getRequestObject();
        
        $cookieValue = $request->getPostParam(Gpf_Settings::get(AuthorizeNet_Config::PARAM_NAME));
        $descValue = $request->getPostParam('x_description');
        
        if ($cookieValue == '') {
            $cookieValue = $request->getPostParam('x_description');
        }
        
        try {
            $customSeparator = Gpf_Settings::get(AuthorizeNet_Config::SEPARATOR);
            if ($customSeparator != '') {
                $explodedCookieValue = explode($customSeparator, $cookieValue, 2);
                if (count($explodedCookieValue) == 2) {
                    $cookieValue = $explodedCookieValue[1];
                    $descValue = $explodedCookieValue[0];
                }
            }
        } catch (Gpf_Exception $e) {
        }
        
        $this->setProductID($descValue);
        $this->setCookie($cookieValue);
        
        $this->setTotalCost($this->adjustTotalCost($request->getPostParam('x_amount'), $request));
        $this->setEmail($request->getPostParam('x_email'));
        $this->setTransactionID($request->getPostParam('x_trans_id'));
        $this->setPaymentStatus($request->getPostParam('x_response_code'));
        $this->setPaymentType($request->getPostParam('x_type'));
        
        $this->setSubscriptionId(@$request->getPostParam('x_subscription_id'));
        $this->setSubscriptionPaynum(@$request->getPostParam('x_subscription_paynum'));
        $this->setData1($request->getPostParam('x_email'));
        $this->setData4($request->getPostParam('x_invoice_num'));
        if ($this->getData4() == '') {
            $this->setData4($this->getSubscriptionId());
        }
        if ($this->getSubscriptionId() != '' && $this->getSubscriptionPaynum() != '') {
            $this->debug('Recurring payment, saving invoice number (or subscription_id) (' . $this->getData4() . ') to data 4, and recurring number (' . $request->getPostParam('x_subscription_paynum') . ') into data 3.');
            $this->setData3($this->getSubscriptionPaynum());
            $this->setInvoiceNumber($this->getData4());
        } else {
            $this->debug('New payment saving invoice number (or subscription_id) (' . $this->getData4() . ') to data5.');
            $this->setData5($this->getData4());
        }
    }

    protected function registerCommission() {
        if ($this->getPaymentType() == 'credit') {
            $this->setTotalCost($this->getTotalCost() * -1);
            $this->debug('This is a refund, we are creating a negative commission.');
        }
        parent::registerCommission();
    }

    public function isRecurring() {
        if ($this->getSubscriptionId() != '' && $this->getSubscriptionPaynum() != '') {
            return true;
        }
        return false;
    }

    public function getOrderID() {
        return $this->getTransactionID();
    }
}
?>
