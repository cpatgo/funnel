<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
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
class TwoCheckout_Tracker extends Pap_Tracking_CallbackTracker {

    /**
     *
     * @var Pap_Tracking_Request
     */
    private $request;
    /**
     * @return TwoCheckout_Tracker
     */

    private $notificationType;
    public function getInstance() {
        $tracker = new TwoCheckout_Tracker();
        $tracker->setTrackerName("TwoCheckout");
        return $tracker;
    }

    protected function registerCommission() {
        $this->debug("Start registering sales TwoCheckout");
        if ($this->isRefund()) {
            $this->makeRefund();
            return;
        }
        if (Gpf_Settings::get(TwoCheckout_Config::PROCESS_WHOLE_CART_AS_ONE_TRANSACTION) == Gpf::YES) {
            $this->processWholeCartAsOneTransaction();
        } else {
            $this->processEachItemInCartSeparatly();
        }
    }

    protected function processSubscriptionPayment() {
        $this->debug('2checkout INS plugin: processSubscriptionPayment: '.$this->notificationType);
        if ($this->notificationType == 'RECURRING_STOPPED' || $this->notificationType == 'RECURRING_COMPLETE') {
            $this->removeRecurringCommission();
            return;
        }
        parent::processSubscriptionPayment();
    }

    private function removeRecurringCommission() {
        if (Gpf_Settings::get(TwoCheckout_Config::DECLINE_AFFILIATE) == Gpf::YES) {
            $this->declineAffiliate();
        }

        $this->debug('2checkout INS plugin: Removing recurring commisions with orderId: ' . $this->getSubscriptionID());
        $commissions = Pap_Features_RecurringCommissions_Main::getRecurringSelect($this->getSubscriptionID())->getAllRows();
        $recurringCommissions = new Pap_Features_RecurringCommissions_RecurringCommission();

        foreach ($recurringCommissions->loadCollectionFromRecordset($commissions) as $recurringCommission) {
            $recurringCommission->delete();
        }
    }

    private function makeRefund() {
        if (Gpf_Settings::get(TwoCheckout_Config::DECLINE_AFFILIATE) == Gpf::YES) {
            $this->debug('2Checkout: Declining affiliate.');
            $this->declineAffiliate();
        }

        $this->debug('2checkout refund started');
        $transaction = new Pap_Db_Transaction();
        $transaction->setOrderId($this->getSubscriptionID());
        try{
            $collection = $transaction->loadCollection(array(Pap_Db_Table_Transactions::ORDER_ID));
        } catch (Gpf_Exception $e) {
            $this->debug('2checkout refund failed - Error in loading transactions: '.$e->getMessage());
            return;
        }

        if($collection->getSize() == 0) {
            $this->debug('2checkout refund failed: No transactions with order id: '.$this->getSubscriptionID());
            return;
        }

        foreach($collection as $transactionDb) {
            $transaction = new Pap_Common_Transaction();
            $transaction->processRefundChargeback($transactionDb->getId(), Pap_Db_Transaction::TYPE_REFUND, '', $this->getOrderID(), 0, true);
            $this->debug('2checkout refunded transaction with id '.$transactionDb->getId());
        }
    }

    private function declineAffiliate() {
        try {
            $affiliate = new Pap_Affiliates_User();
            $affiliate = $affiliate->loadFromUsername($_POST['customer_email']);

            if ($affiliate->getStatus() != Pap_Common_Constants::STATUS_DECLINED) {
                $affiliate->setStatus(Pap_Common_Constants::STATUS_DECLINED);
                $affiliate->update(array(Gpf_Db_Table_Users::STATUS));
                $this->debug('Affiliate with username = '.$_POST['customer_email'].' has been declined after status '.$this->notificationType);
            }
        } catch (Gpf_Exception $e) {
            $this->debug('Error occurred during declining the affiliate '.$_POST['payer_email'].' [status '.$this->notificationType.' received]. Exception: '. $e->getMessage());
        }
    }

    private function processEachItemInCartSeparatly() {
        for($i = 1; $i <= $this->request->getPostParam('item_count'); $i++) {
            $this->setTotalCost($this->request->getPostParam('item_usd_amount_' . $i));
            $this->setProductID($this->request->getPostParam('item_id_' . $i));
            parent::registerCommission();
        }
    }

    private function processWholeCartAsOneTransaction() {
        $this->debug('TwoCheckout - Process whole cart as one transaction');

        $productId = '';
        $totalUsd = 0;
        for($i = 1; $i <= $this->request->getPostParam('item_count'); $i++) {
            $productId .= (string)$this->request->getPostParam('item_id_'.$i) . ', ';
            $totalUsd += (float)$this->request->getPostParam('item_usd_amount_'.$i);
        }

        $this->setTotalCost($totalUsd);
        $this->setProductID($productId);
        parent::registerCommission();
    }

    private function sendRequest($url, $query, $authorization) {
        $this->debug('2checkout INS plugin: Sending request to '.$url.$query);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $authorization);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json'));//,'Content-Type: application/xml'
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url.$query);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
        $responseObject = json_decode($response);
        if ($responseObject->response_code == 'OK') {
          if (isset($responseObject->sale)) {
            foreach ($responseObject->sale->invoices[0]->lineitems as $item) {
              if ($item->type == 'coupon') {
                return $item->product_name;
              }
            }
            return false;
          } else {
            $this->debug('2checkout INS plugin: No sale data in response: '.print_r($responseObject,true));
          }
        } else {
            $this->debug('2checkout INS plugin: Response not OK');
            return false;
        }
    }

    private function checkCouponWithAPI($saleId, $invoiceId) {
        $url = 'https://www.2checkout.com/api/sales/detail_sale';
        $query = '?sale_id=' . $saleId . '&invoice_id=' . $invoiceId;
        $authorization = Gpf_Settings::get(TwoCheckout_Config::API_USERNAME).':'.Gpf_Settings::get(TwoCheckout_Config::API_PASSWORD);
        $result = $this->sendRequest($url, $query, $authorization);

        if (!$result) {
          return false;
        }

        $this->setCoupon($result);
        $this->debug('2checkout INS plugin: Coupon "'.$result.'" has been set.');
        return true;
    }

    public function getOrderID() {
        return $this->request->getPostParam('sale_id');
    }

    public function checkStatus() {
        return $this->getPaymentStatus();
    }

    private function isRefund() {
        return $this->request->getPostParam('message_type') == 'REFUND_ISSUED';
    }

    public function isRecurring() {
        if(
        $this->notificationType == 'RECURRING_INSTALLMENT_SUCCESS'
        || $this->notificationType == 'RECURRING_STOPPED'
        || $this->notificationType == 'RECURRING_COMPLETE') {
            return true;
        }
        return false;
    }

    /**
     *  @return Pap_Tracking_Request
     */
    protected function getRequestObject() {
        return Pap_Contexts_Action::getContextInstance()->getRequestObject();
    }

    public function readRequestVariables() {
        $this->debug('2checkout INS plugin: $_POST array:' . print_r($_POST, true));
        $this->request = $this->getRequestObject();

        $PAPvisitorId = $this->request->getPostParam('vendor_order_id');

        $separator = Gpf_Settings::get(TwoCheckout_Config::CUSTOM_SEPARATOR);
        if (!empty($separator)) {
            $parts = explode($separator, $PAPvisitorId);
            $PAPvisitorId = array_pop($parts);
        }

        $this->debug('2checkout INS plugin: PapVisitorId: ' . $PAPvisitorId);
        $this->setCookie($PAPvisitorId);
        $this->resolveStatus();
        $this->setSubscriptionID($this->request->getPostParam('sale_id'));

        $this->readRequestAffiliateVariables($this->request);

        if (Gpf_Settings::get(TwoCheckout_Config::COUPON_TRACKING) == Gpf::YES) {
            $this->debug('2checkout INS plugin: Coupon tracking is enabled');
            if ((Gpf_Settings::get(TwoCheckout_Config::API_USERNAME) == '') || (Gpf_Settings::get(TwoCheckout_Config::API_PASSWORD) == '')) {
                $this->debug('2checkout INS plugin: API credentials are not complete, continuing without coupon.');
                return;
            }
            $this->checkCouponWithAPI($this->request->getPostParam('sale_id'), $this->request->getPostParam('invoice_id'));
        }
    }

    protected function isAffiliateRegisterAllowed() {
        return ((!$this->isRefund()) && (Gpf_Settings::get(TwoCheckout_Config::REGISTER_AFFILIATE) == Gpf::YES));
    }

    private function readRequestAffiliateVariables(Pap_Tracking_Request $request) {
        $this->setUserFirstName($request->getRequestParameter('customer_first_name'));
        $this->setUserLastName($request->getRequestParameter('customer_last_name'));
        $this->setUserEmail($request->getRequestParameter('customer_email'));
        $this->setUserCity($request->getRequestParameter('bill_city'));
        $this->setUserAddress($request->getRequestParameter('bill_street_address'));
    }

    private function resolveStatus() {
        $messageType = trim($this->request->getPostParam('message_type'));
        $this->debug('2checkout INS plugin: resolveStatus: '.$messageType);
        $this->notificationType = $messageType;
        switch ($messageType) {
            case 'ORDER_CREATED':
            case 'REFUND_ISSUED':
            case 'RECURRING_INSTALLMENT_SUCCESS':
            case 'RECURRING_STOPPED':
            case 'RECURRING_COMPLETE':
                $this->setPaymentStatus(true);
                return;
        }
        $this->setPaymentStatus(false);
        $this->debug('2checkout INS plugin: Message type not supported: ' . $messageType);
    }
}
