<?php
/**
 *   @copyright Copyright (c) 2013 Quality Unit s.r.o.
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
class MemberMouse_Tracker extends Pap_Tracking_CallbackTracker {
    // payment
    const PAYMENT_RECEIVED = 'mm_payment_received';
    const PAYMENT_REBILL = 'mm_payment_rebill';
    const REFUND_ISSUED = 'mm_refund_issued';
    
    // members
    const MEMBER_ADD = 'mm_member_add';
    const MEMBER_STATUS_CHANGE = 'mm_member_status_change';
    const MEMBER_DELETE = 'mm_member_delete';
    
    // member statuses
    const MEMBER_ACTIVE = 'Active';
    const MEMBER_CANCELED = 'Canceled';
    const MEMBER_PAUSED = 'Paused';
    const MEMBER_OVERDUE = 'Overdue';
    const MEMBER_LOCKED = 'Locked';
    const MEMBER_PENDING = 'Pending';
    const MEMBER_ERROR = 'Error';
    const MEMBER_EXPIRED = 'Expired';

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
     * @return MemberMouse_Tracker
     */
    public function getInstance() {
        $tracker = new MemberMouse_Tracker();
        $tracker->setTrackerName("MemberMouse");
        return $tracker;
    }

    public function checkStatus() {
        if (Gpf_Settings::get(MemberMouse_Config::CHANGE_AFFILIATE_STATUS) == Gpf::YES && ($this->getType() == self::MEMBER_STATUS_CHANGE || $this->getType() == self::MEMBER_DELETE)) {
            $this->changeAffiliateStatus();
            return false;
        }
        
        if ($this->getType() == self::MEMBER_ADD) {
            if ($this->isAffiliateRegisterAllowed()) {
                $saleTracker = new Pap_Tracking_ActionTracker();
                $this->setVisitorAndAccount($saleTracker, $this->getAffiliateID(), $this->getCampaignID(), $this->getCookie());
                $this->registerAffiliate();
            } else {
                $this->debug('Creating affiliate is not enabled.');
            }
            return false;
        }
        
        if ($this->getType() == self::REFUND_ISSUED) {
            if (Gpf_Settings::get(MemberMouse_Config::PROCESS_REFUND) != Gpf::YES) {
                $this->debug('Status "' . self::REFUND_ISSUED . '" received, but refund handling is not enabled.');
                return false;
            }
            
            $this->debug('Transaction ' . $this->getOrderID() . ' will be refunded.');
            try {
                $this->refundChargeback();
                $this->debug('Refund complete, ending processing.');
            } catch (Gpf_Exception $e) {
                $this->debug('Error occurred during transaction refund: ' . $e->getMessage());
            }
            
            return false;
        }
        
        if ($this->getType() == self::PAYMENT_RECEIVED || $this->getType() == self::PAYMENT_REBILL) {
            return true;
        }
        
        return false;
    }

    protected function refundChargeback() {
        $transaction = new Pap_Common_Transaction();
        $transaction->processRefundChargeback($this->getTransactionIdFromOrderId($this->getOrderID()), Pap_Db_Transaction::TYPE_REFUND, '', $this->getOrderID(), 0, true);
    }

    private function changeAffiliateStatus() {
        try {
            $affiliate = Pap_Affiliates_User::loadFromUsername($this->getEmail());
        } catch (Gpf_Exception $e) {
            $this->debug('Error occurred during status change of the affiliate ' . $this->getEmail() . '. Exception: ' . $e->getMessage());
            return false;
        }
        
        if ($this->getType == self::MEMBER_STATUS_CHANGE) {
            if ($this->getPaymentStatus() == self::MEMBER_ACTIVE) {
                $affiliate->setStatus(Pap_Common_Constants::STATUS_APPROVED);
                $this->debug('Status is being changed to APPROVED.');
            }
            if ($this->getPaymentStatus() == self::MEMBER_PAUSED || $this->getPaymentStatus() == self::MEMBER_LOCKED || $this->getPaymentStatus() == self::MEMBER_PENDING) {
                $affiliate->setStatus(Pap_Common_Constants::STATUS_PENDING);
                $this->debug('Status is being changed to PENDING.');
            }
            if ($this->getPaymentStatus() == self::MEMBER_CANCELED || $this->getPaymentStatus() == self::MEMBER_OVERDUE || $this->getPaymentStatus() == self::MEMBER_EXPIRED) {
                $affiliate->setStatus(Pap_Common_Constants::STATUS_DECLINED);
                $this->debug('Status is being changed to DECLINED.');
            }
        }
        
        if ($this->getType == self::MEMBER_DELETE) {
            $affiliate->setStatus(Pap_Common_Constants::STATUS_DECLINED);
            $this->debug('Status is being changed to DECLINED.');
        }
        
        $affiliate->update(array(
                Gpf_Db_Table_Users::STATUS 
        ));
        $this->debug('Status of affiliate ' . $this->getEmail() . ' has been changed.');
    }

    /**
     *  @return Pap_Tracking_Request
     */
    protected function getRequestObject() {
        return Pap_Contexts_Action::getContextInstance()->getRequestObject();
    }

    public function readRequestVariables() {
        $request = $this->getRequestObject();
        $this->debug('  Data received: ' . print_r($_REQUEST, true));
        
        $this->setType($request->getRequestParameter('event_type'));

        $cookieValue = stripslashes($request->getRequestParameter('cf_' . Gpf_Settings::get(MemberMouse_Config::CUSTOM_FIELD)));
        $this->setCookie($cookieValue);

        // if it is member notification, read member details
        if (($this->getType() == self::MEMBER_ADD) || ($this->getType() == self::MEMBER_STATUS_CHANGE) || ($this->getType() == self::MEMBER_DELETE)) {
            $this->readRequestAffiliateVariables($request);
            return;
        }

        if ($request->getRequestParameter('order_ip_address') != null) {
            $_SERVER["REMOTE_ADDR"] = $request->getRequestParameter('order_ip_address');
        }
        
        $coupons = $request->getRequestParameter('order_coupons');
        if ((Gpf_Settings::get(MemberMouse_Config::USE_COUPON) == Gpf::YES) && (!empty($coupons))) {
            $coupons = json_decode(stripslashes($coupons));
            foreach ($coupons as $coupon) {
                $this->setCoupon($coupon->code);
                break; // we can only work with one coupon
            }
        }

        $discount = 0;
        if ($request->getRequestParameter('order_discount')) {
            $discount = $request->getRequestParameter('order_discount');
        }
        $this->setTotalCost($request->getRequestParameter('order_subtotal') - $discount);

        $this->setTransactionID($request->getRequestParameter('order_number'));
        if ($this->getType() == self::PAYMENT_REBILL) {
            $this->setTransactionID($request->getRequestParameter('order_transaction_id'));
        }

        $this->setSubscriptionID($request->getRequestParameter('order_number'));
        $this->setEmail($request->getRequestParameter('email'));
        $this->setData1($this->getEmail());
        $this->setData2($this->getSubscriptionID());
    }

    public function readRequestAffiliateVariables(Pap_Tracking_Request $request) {
        if ((Gpf_Settings::get(MemberMouse_Config::CREATE_AFFILIATE) == Gpf::YES) && ($this->getType() == self::MEMBER_ADD)) {
            $this->setUserFirstName($request->getRequestParameter('first_name'));
            $this->setUserLastName($request->getRequestParameter('last_name'));
            $this->setUserEmail($request->getRequestParameter('email'));
            $this->setUserCity($request->getRequestParameter('billing_city'));
            $this->setUserAddress($request->getRequestParameter('billing_address'));
            $this->setUserData(1, ' ');
            $this->setUserData(2, ' ');
            $this->setUserData(5, $request->getRequestParameter('billing_state'));
            $this->setUserData(6, $this->getCountryCode($request->getRequestParameter('billing_country')));
        }
        
        if ((Gpf_Settings::get(MemberMouse_Config::CHANGE_AFFILIATE_STATUS) == Gpf::YES) && ($this->getType() == self::MEMBER_STATUS_CHANGE || $this->getType() == self::MEMBER_DELETE)) {
            $this->setPaymentStatus($request->getRequestParameter('status_name'));
            $this->setEmail($request->getRequestParameter('email'));
        }
    }

    private function getCountryCode($countryName) {
        $countries = Gpf_Country_Countries::getEncodedCountries();
        foreach ($countries as $country) {
            if (trim($country->get('1'), "##") == $countryName) {
                return $country->get('0');
            }
        }
        return 'O1';
    }

    public function isRecurring() {
        if ($this->getType() == self::PAYMENT_REBILL || $this->getType() == self::REFUND_ISSUED) { // so we get a correct order ID (subscription ID in this case)
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
        return ((Gpf_Settings::get(MemberMouse_Config::CREATE_AFFILIATE) == Gpf::YES) && ($this->getType() == self::MEMBER_ADD));
    }

    protected function prepareSales(Pap_Tracking_ActionTracker $saleTracker) {
        if (Gpf_Settings::get(MemberMouse_Config::PER_PRODUCT_TRANSACTION) == GPF::YES) {
            $this->prepareSeparateCartItems($saleTracker);
        } else {
            parent::prepareSales($saleTracker);
        }
    }

    private function prepareSeparateCartItems(Pap_Tracking_ActionTracker $saleTracker) {
        $request = $this->getRequestObject();
        $products = json_decode(stripslashes($request->getRequestParameter('order_products')));
        
        $i = 1;
        foreach ($products as $product) {
            $sale = $saleTracker->createSale();
            
            $sale->setTotalCost($product->total);
            if ($product->is_recurring && $this->isRecurring()) {
                $sale->setTotalCost($product->recurring_amount);
            }
            $sale->setOrderID($this->getOrderID() . '(' . $i . ')');
            $sale->setProductID($product->sku);
            $sale->setData1($this->getData1());
            $sale->setData2($this->getData2());
            $sale->setData3($this->getData3());
            $sale->setData4($this->getData4());
            $sale->setData5($this->getData5());
            $sale->setCoupon($this->getCouponCode());
            
            if ($this->getAffiliateID() != '') {
                $sale->setAffiliateID($this->getAffiliateID());
            }
            if ($this->getCampaignID() != '') {
                $sale->setCampaignID($this->getCampaignID());
            }
            
            $this->setVisitorAndAccount($saleTracker, $this->getAffiliateID(), $this->getCampaignID(), $this->getCookie());
            $i++;
        }
    }
}
?>
