<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @author Maros Fric
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
class UltraCart_Tracker extends Pap_Tracking_CallbackTracker {

    const REFUNDED = 'Refunded';
    const PROCESSED = 'Processed';
    
    const TYPE_REBILL = 'rebill';

    protected $xml;

    /**
     * @return UltraCart_Tracker
     */
    public function getInstance() {
        $tracker = new UltraCart_Tracker();
        $tracker->setTrackerName('UltraCart');
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
    
    protected function refundChargeback() {
        $transaction = new Pap_Common_Transaction();
        $transactionId = $this->getTransactionIdFromOrderId($this->getOrderID());

        if (empty($transactionId)) {
            return;
        }
        $refundResult = $transaction->processRefundChargeback($transactionId, Pap_Db_Transaction::TYPE_REFUND, '', $this->getOrderID(), 0, true, $this->getTotalCost());
        if (!$refundResult) {
            $this->debug('Unable to process Refund.');
            return;
        }
        // when refunded, create a new transaction for partial refund
        $oldTransaction = new Pap_Common_Transaction();
        $oldTransaction->setId($transactionId);
        $oldTransaction->load();

        $difference = $oldTransaction->getTotalCost() - $this->getTotalCost();
        $this->debug('Old totalcost ('.$oldTransaction->getTotalCost().') - ('.$this->getTotalCost().') = '.$difference);

        if ($difference > 0 ) {
            // this is a partial refund, create a commission for the rest
            $this->debug('Creating a new transaction for the partial refund.');
            $this->insertTransactionsMultitier($oldTransaction, $difference);
        }
    }
    
    private function insertTransactionsMultitier(Pap_Common_Transaction $oldTransaction, $difference, $newTransactionParentId = '') {
        $newTransaction = clone $oldTransaction;
        $newTransaction->setOrderId($oldTransaction->getOrderId().'_');
        $newTransaction->setTotalCost($difference);
        $newTransaction->setPersistent(false);
        $newTransaction->generateNewTransactionId();
        $commission = $this->getCommissionForTransaction($oldTransaction);
        $newTransaction->recompute($commission);
        $newTransaction->setParentTransactionId($newTransactionParentId);
        $newTransaction->insert();
        
        $childTransactions = $oldTransaction->getTransactionsByParent(true, $oldTransaction->getId(), $oldTransaction->getType());
        foreach ($childTransactions as $childTransaction) {
            $this->insertTransactionsMultitier($childTransaction, $difference, $newTransaction->getId());
        }
    }

    /**
     * @param Pap_Db_Transaction $transaction
     * @return Pap_Db_Commission
     */
    private function getCommissionForTransaction(Pap_Db_Transaction $transaction) {
        $commission = new Pap_Db_Commission();
        $commission->setCommissionTypeId($transaction->getCommissionTypeId());
        $commission->setGroupId($transaction->getCommissionGroupId());
        $commission->setTier($transaction->getTier());
        $commission->setSubtype(Pap_Db_Table_Commissions::SUBTYPE_NORMAL);
        try {
            $commission->loadFromData(array(Pap_Db_Table_Commissions::TYPE_ID, Pap_Db_Table_Commissions::GROUP_ID, Pap_Db_Table_Commissions::TIER, Pap_Db_Table_Commissions::SUBTYPE));
        } catch (Gpf_Exception $e) {
            $userInGroup = Pap_Db_Table_UserInCommissionGroup::getInstance()->getUserCommissionGroup($transaction->getUserId(), $transaction->getCampaignId());
            $commission->setGroupId($userInGroup->getCommissionGroupId());
            try {
                $commission->loadFromData(array(Pap_Db_Table_Commissions::TYPE_ID, Pap_Db_Table_Commissions::GROUP_ID, Pap_Db_Table_Commissions::SUBTYPE, Pap_Db_Table_Commissions::TIER));
            } catch (Gpf_Exception $e) {
                throw new Gpf_Exception($this->_('Unable to find commision for transaction id=' . $transaction->getId()));
            }
        }

        return $commission;
    }

    public function checkStatus() {
        if ($this->getPaymentStatus() == self::REFUNDED) {
            $this->debug('Transaction '.$this->getOrderID().' will be marked as refunded.');
            try {
                $this->refundChargeback();
                $this->debug('Refund complete, ending...');
            } catch (Gpf_Exception $e) {
                $this->debug('Error occurred during transaction refund: '.$e->getMessage());
            }
            return false;
        }

        if ($this->getPaymentStatus() != self::PROCESSED) {  // Declined, Unprocessed
            $this->debug('STOPPING, Payment status is not Processed. Status: '.$this->getPaymentStatus().'. Transaction: '.$this->getOrderID().', payer email: '.$this->getEmail());
            return false;
        }
        
        $this->debug('Payment successful.');
        return true;
    }

    protected function readXmlData() {
        $post_data = file_get_contents('php://input');
        return $post_data;
    }

    protected function outputError() {
        echo '99';
    }

    protected function outputSuccess() {
        echo '200';
    }

    private function computeTotalCost(SimpleXMLElement $xml) {        
        if ($this->getPaymentStatus() == self::REFUNDED) {
            return (string)$xml->order->total_refunded;
        }
        if (Gpf_Settings::get(UltraCart_Config::SHIPPING_HANDLING_SUBSTRACT) == Gpf::YES) {
            return (string)$xml->order->subtotal;
        }
        return (string)$xml->order->total;
    }

    public function readRequestVariables() {
        $input = $this->readXmlData();
        $this->debug('Input get: '.$input);
        try {
            @$xml = new SimpleXMLElement($input);
            $this->xml = $xml;
        } catch (Exception $e) {
            $this->setPaymentStatus('Failed');
            $this->debug('Wrong XML format.');
            $this->outputError();
            return;
        }
        if (is_null($xml->order) || is_null($xml->order->order_id)) {
            $this->setPaymentStatus("Failed");
            $this->error('Missing order id parameter.');
            $this->outputError();
            return;
        }

        $this->setIpAddress((string)$xml->order->customer_ip_address);

        // assign posted variables to local variables
        $customField = 'custom_field_'.Gpf_Settings::get(UltraCart_Config::CUSTOM_FIELD_NUMBER);
        $this->debug('Custom field number: '.Gpf_Settings::get(UltraCart_Config::CUSTOM_FIELD_NUMBER));
        $cookieValue = (string)$xml->order->$customField;
        
        $this->setCookie($cookieValue);

        if ((string)$xml->order->coupon->coupon_code != '') {
            $this->setCoupon((string)$xml->order->coupon->coupon_code);
        }

        $this->setPaymentStatus((string)$xml->order->payment_status);
        $this->setTotalCost($this->computeTotalCost($xml));
        $this->setCurrency((string)$xml->order->currency_code);
        $this->setTransactionID((string)$xml->order->order_id);

        if ((string)$xml->order->auto_order_original_order_id != '') {
            $auto_order_original_order_id = (string)$xml->order->auto_order_original_order_id;
            if ($auto_order_original_order_id != $this->getTransactionID()) {
                $this->setType(self::TYPE_REBILL);
                $this->setSubscriptionID($auto_order_original_order_id);
            }
        }
        
        if ((string)$xml->order->item->item_id != '') {
            $this->setProductID((string)$xml->order->item->item_id);
        }

        if ((string)$xml->order->email != '') {
            $this->setEmail((string)$xml->order->email);
            $this->setData1($this->getEmail());
        }

        if ($this->isAffiliateRegisterAllowed()) {
            $this->readRequestAffiliateVariables($xml->order);
        }

        $this->outputSuccess();
    }

    public function readRequestAffiliateVariables($xml) {
        $this->setUserFirstName((string)$xml->bill_to_first_name);
        $this->setUserLastName((string)$xml->bill_to_last_name);
        $this->setUserEmail((string)$xml->email);
        $this->setUserCity((string)$xml->bill_to_city);
        $this->setUserAddress((string)$xml->bill_to_address1);
    }

    protected function isAffiliateRegisterAllowed() {
        return (Gpf_Settings::get(UltraCart_Config::REGISTER_AFFILIATE) == Gpf::YES);
    }

    protected function prepareSales(Pap_Tracking_ActionTracker $saleTracker) {
        if (Gpf_Settings::get(UltraCart_Config::WHOLE_CART_AS_ONE) == GPF::YES) {
            parent::prepareSales($saleTracker);
        } else {
            $this->prepareSeparateCartItems($saleTracker);
        }
    }

    private function prepareSeparateCartItems(Pap_Tracking_ActionTracker $saleTracker) {
        $items = $this->xml->order->item;
        if (is_null($items)) {
            return;
        }

        $i = 1;
        foreach($items as $item) {
            $sale = $saleTracker->createSale();
            $sale->setTotalCost((string)$item->total_cost_with_discount);
            $sale->setOrderID($this->getOrderID().'('.$i.')');
            $sale->setProductID((string)$item->item_id);
            $sale->setData1($this->getData1());
            $sale->setData2($this->getData2());
            $sale->setData3($this->getData3());
            $sale->setData4($this->getData4());
            $sale->setData5($this->getData5());
            $sale->setCoupon($this->getCouponCode());
            $sale->setCurrency($this->getCurrency());
            $sale->setChannelId($this->getChannelId());
            if ($this->getStatus()!='') {
                $sale->setStatus($this->getStatus());
            }
            if($this->getAffiliateID() != '' && $this->getCampaignID() != '') {
                $sale->setAffiliateID($this->getAffiliateID());
                $sale->setCampaignID($this->getCampaignID());
            }

            $this->setVisitorAndAccount($saleTracker, $this->getAffiliateID(), $this->getCampaignID(), $this->getCookie());
            $i++;
        }
    }

    public function getOrderID() {
    	if($this->isRecurring()) {    		
    		return $this->getSubscriptionID();
    	} else {
    		return $this->getTransactionID();
    	}
    }
    
    public function isRecurring() {
    	if($this->getType() == self::TYPE_REBILL) {
    		return true;
    	} 	
    	return false;
    }
}
?>
