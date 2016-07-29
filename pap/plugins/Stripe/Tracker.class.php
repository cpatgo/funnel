<?php
/**
 *   @copyright Copyright (c) 2015 Quality Unit s.r.o.
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
class Stripe_Tracker extends Pap_Tracking_CallbackTracker {
    const TRANSACTION_TYPE_REFUND = 'charge.refunded';
    const TRANSACTION_TYPE_APPROVED = 'charge.succeeded';
    const TRANSACTION_TYPE_INVOICE_APPROVED = 'invoice.payment_succeeded';

    /**
     * @return Stripe_Tracker
     */
    public function getInstance() {
        $tracker = new Stripe_Tracker();
        $tracker->setTrackerName("Stripe");
        return $tracker;
    }

    private function getTransactionIdFromOrderId($orderId){
        $transaction = new Pap_Common_Transaction();
        if ($output = $this->findFirstRecordWithData($transaction, Pap_Db_Table_Transactions::ORDER_ID, $orderId)) {
            $this->debug('Parent transaction for refund found by orderId.');
            return $output->getId();
        }

        throw new Gpf_Exception('Parent transaction for order id: ' . $orderId . ' not found.');
    }

    protected function refundChargeback() {
        $transaction = new Pap_Common_Transaction();
        $transaction->processRefundChargeback($this->getTransactionIdFromOrderId($this->getOrderID()), Pap_Db_Transaction::TYPE_REFUND, '', $this->getOrderID(), 0, true);
    }

    public function checkStatus() {
        $this->sendBackVerification();

        if ($this->getType() == self::TRANSACTION_TYPE_REFUND) {
            $this->debug('Transaction '.$this->getOrderID().' will be refunded');
            try {
                $this->refundChargeback();
                $this->debug('Refund completed, ending process.');
            } catch (Gpf_Exception $e) {
                $this->debug('Error occurred during transaction refund:' . $e->getMessage());
            }
            return false;
        }

        // check transaction type
        if (($this->getType() != self::TRANSACTION_TYPE_APPROVED) && ($this->getType() != self::TRANSACTION_TYPE_INVOICE_APPROVED)) {
            $this->debug("Ignoring type '".$this->getType()."'");
            return false;
        }

        return true;
    }

    protected function sendBackVerification() {
        return "200";
    }

    /**
     *  @return Pap_Tracking_Request
     */
    protected function getRequestObject() {
        $response = @file_get_contents('php://input');
        $response_object = json_decode($response);

        $this->debug(' callback data: '.print_r($response_object,true));
        return $response_object;
    }

    public function readRequestVariables() {
        $request = $this->getRequestObject();
        if (empty($request)) {
            return;
        }

        $this->setType((string)$request->type);

        if (Gpf_Settings::get(Stripe_Config::TRACK_CHARGE_EVENT) == Gpf::YES) {
            if (($this->getType() != self::TRANSACTION_TYPE_APPROVED) && ($this->getType() != self::TRANSACTION_TYPE_REFUND)) {
                return;
            }
        } else {
            if (($this->getType() != self::TRANSACTION_TYPE_INVOICE_APPROVED) && ($this->getType() != self::TRANSACTION_TYPE_REFUND)) {
                return;
            }
        }

        $request = $request->data->object;

        $visitorID = $this->getCustomerDescription($request->customer);
        if (!$visitorID) {
            //return;
            // let's try Lifetime Cookies
        }

        $cookieValue = $visitorID;

        try {
            $customSeparator = Gpf_Settings::get(Stripe_Config::CUSTOM_SEPARATOR);
            if ($customSeparator != '') {
                $explodedCookieValue = explode($customSeparator, $cookieValue, 2);
                if (count($explodedCookieValue) == 2) {
                    $cookieValue = $explodedCookieValue[1];
                }
            }
        } catch (Gpf_Exception $e) {
        }

        $this->setCookie($cookieValue);
        $discount = @$request->discount;
        if (!empty($discount)) {
            $coupon = (string)$request->discount->coupon->id;
            if (!empty($coupon)) {
                if (strpos($coupon,"_") !== false) {
                    $this->setCoupon(substr($coupon,0,strpos($coupon,"_")));
                } else {
                    $this->setCoupon($coupon);
                }
            }
        }

        $this->setTransactionID((string)$request->id);

        $subscriptionId = '';
        $lines = @$request->lines;
        $productId = '';
        if (!empty($lines)) {
            $subscriptionId = (string)$lines->data[0]->id;
            $this->setSubscriptionID($subscriptionId);
            $this->setData2($subscriptionId);

            // check if exists, if not, create a new transaction with subscription ID instead of invoice ID
            if (!$this->checkIfTransactionExists($subscriptionId)) {
                $this->setTransactionID($subscriptionId);
            }
            if ($lines->data[0]->plan != '' && $lines->data[0]->plan->name != '') {
                $productId = (string)$lines->data[0]->plan->name;
            }
        }

        $currency = (string)$request->currency;
        $this->setCurrency(strtoupper($currency));

        if ($this->getType() == self::TRANSACTION_TYPE_REFUND) {
            $this->setTotalCost($this->adjustTotalCost((float)$request->amount_refunded));
            $this->setTransactionID((string)$request->invoice);
        }
        if ($this->getType() == self::TRANSACTION_TYPE_APPROVED) {
            $this->setTotalCost($this->adjustTotalCost((float)$request->amount));
            $this->setProductID((string)$request->description);
        }
        if ($this->getType() == self::TRANSACTION_TYPE_INVOICE_APPROVED) {
            $this->setTotalCost($this->adjustTotalCost((float)$request->amount_due));
            if ($productId) {
                $this->setProductID($productId);
            }
        }
    }

    private function adjustTotalCost($total) {
        // zero-decimal currencies do not need division by 100
        // official source: https://support.stripe.com/questions/which-zero-decimal-currencies-does-stripe-support
        $zeroDecimals = array('BIF','CLP','DJF','GNF','JPY','KMF','KRW','MGA','PYG','RWF','VND','VUV','XAF','XOF','XPF');
        if (!in_array($this->getCurrency(),$zeroDecimals)) {
            return (float)$total/100;
        }
        return $total;
    }

    /**
     * @param String $orderId
     * @return String|boolean
     */
    protected function checkIfTransactionExists($orderId) {
        $transaction = new Pap_Common_Transaction();
        try {
            $result = $transaction->getFirstRecordWith(Pap_Db_Table_Transactions::ORDER_ID, $orderId, array(Pap_Common_Constants::STATUS_APPROVED, Pap_Common_Constants::STATUS_PENDING));
            return $result->getId();
        } catch (Gpf_DbEngine_NoRowException $e) {
            return false;
        }
    }

    protected function getCustomerDescription($cust_id) {
        $this->debug(' Loading customer '.$cust_id);
        $key = Gpf_Settings::get(Stripe_Config::API_KEY);
        require_once('lib/Stripe.php');

        try {
            $customer = Stripe_Customer::retrieve($cust_id, $key);
            $customer = json_decode($customer);

            $this->debug(' Customer found: '.print_r($customer,true));

            $this->setEmail($customer->email);
            $this->setData1($customer->email);

            return $customer->description;
        } catch (Stripe_InvalidRequestError $e) {
            $this->error('Error loading customer (InvalidRequest): '.$e->json_body['error']['message']);
            return false;
        } catch (Stripe_AuthenticationError $e) {
            $this->error('Error loading customer (AuthenticationError): '.$e->json_body['error']['message']);
            return false;
        } catch (Stripe_Error $e) {
            $this->error('Error loading customer: '.$e->json_body['error']['message']);
            return false;
        }
    }

    public function getOrderID() {
        return $this->getTransactionID();
    }

    protected function getRecurringTotalCost() {
        return $this->getTotalCost();
    }

    public function isRecurring() {
        return $this->existRecurringCommission($this->getData2());
    }

    private function existRecurringCommission($orderId) {
        if (empty($orderId)) {
            return false;
        }

        return Pap_Features_RecurringCommissions_Main::isExistRecurringRule($orderId);
    }
}

