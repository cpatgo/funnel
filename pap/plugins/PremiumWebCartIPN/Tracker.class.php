<?php
/**
 *   @copyright Copyright (c) 2012 Quality Unit s.r.o.
 *   @author Martin Pullmann
 *   @package PostAffiliatePro
 *   @since Version 2.0.0
 *
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 2.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.postaffiliatepro.com/licenses/license
 *
 */

/**
 * @package PostAffiliatePro plugins
 */
class PremiumWebCartIPN_Tracker extends Pap_Tracking_CallbackTracker {

    private $xml;
    const STATUS_REFUNDED = 'refund';
    const STATUS_PAID_RECUR = 'subscription_payment';
    const STATUS_PAID = 'instant_payment';

    /**
     * @return PremiumWebCartIPN_Tracker
     */
    public function getInstance() {
        $tracker = new PremiumWebCartIPN_Tracker();
        $tracker->setTrackerName("PremiumWebCartIPN");
        return $tracker;
    }

    private function refundChargeback() {
        $transaction = new Pap_Common_Transaction();
        $transaction->processRefundChargeback($this->getOrderID(), Pap_Db_Transaction::TYPE_REFUND, '', $this->getOrderID(), 0, true);
    }

    public function checkStatus() {  // status value can only be "success"
        $status = false;
        switch ($this->getType()) {
            case self::STATUS_PAID : // payment
            case self::STATUS_PAID_RECUR : // recurrence
                $status = true;
                break;
            case self::STATUS_REFUNDED : // refund
                $this->debug(' Transaction '.$this->getOrderID().' will be refunded.');
                $this->refundChargeback();
                break;
            default:
                $this->debug(' Ignoring this type: '.$this->getType());
        }
    
        return $status;
    }

    /**
     *  @return Pap_Tracking_Request
     */
    protected function getRequestObject() {
        return Pap_Contexts_Action::getContextInstance()->getRequestObject();
    }

    protected function readXmlData($orderId) {
        $request = new Gpf_Net_Http_Request();
        $request->setMethod('POST');
        $request->setUrl('https://www.secureinfossl.com/api/getOrderInfo.html');
        $request->setBody('merchantid=' . urlencode(Gpf_Settings::get(PremiumWebCartIPN_Config::MERCHANT_ID))
                . '&signature=' . urlencode(Gpf_Settings::get(PremiumWebCartIPN_Config::API_SIGNATURE))
                . '&orderid='.$orderId);

        $client = new Gpf_Net_Http_Client();
        $input = $client->execute($request)->getBody();
        $this->debug("Input get: " . $input);

        try {
            $xml = new SimpleXMLElement($input);
        } catch (Exception $e) {
            $this->setPaymentStatus("Failed");
            $this->debug('Wrong XML format.');
            return false;
        }

        $this->xml = $xml;
        return true;
    }

    public function readRequestVariables() {
        $this->debug('Request data: ' . print_r($_POST, true));
        $request = $this->getRequestObject();
        $this->setCookie(stripslashes($request->getPostParam('custom'.Gpf_Settings::get(PremiumWebCartIPN_Config::CUSTOM_FIELD_NUMBER))));
        $this->setType($request->getPostParam('txn_type'));
        $this->setPaymentStatus($request->getPostParam('txn_status'));
        $this->setCurrency($request->getPostParam('currency'));
        $this->setEmail($request->getPostParam('email'));
        $this->setData1($this->getEmail());

        $this->readRequestAffiliateVariables($request);

        $this->setTransactionID($request->getPostParam('order_id'));
        if ($this->isRecurring()) {
            $this->setData1($this->getTransactionID());
        }

        if (Gpf_Settings::get(PremiumWebCartIPN_Config::PROCESS_WHOLE_CART_AS_ONE_TRANSACTION) == GPF::NO) {
            $this->xml = null;
            if (Gpf_Settings::get(PremiumWebCartIPN_Config::USE_SKU) == Gpf::YES) {
                if ($this->readXmlData($request->getPostParam('order_unique_id'))) {
                    $this->debug('We will work with XML.'); // skip reading POST variables
                    $this->debug('Visitor ID: '.$request->getPostParam('custom'.Gpf_Settings::get(PremiumWebCartIPN_Config::CUSTOM_FIELD_NUMBER)));
                    return;
                }
            }
        }

        $this->setTotalCost($request->getPostParam('total_amount'));

        if ($this->isRecurring() && Gpf_Settings::get(PremiumWebCartIPN_Config::RECURRING_USE_ORDERID_AS_SUBSCRID) == Gpf::YES) {
            $this->setSubscriptionID($this->getTransactionID());
        } else {
            if ($request->getPostParam('profile_id') != '') {
                $this->setSubscriptionID($request->getPostParam('profile_id'));
            } else {
                $this->setSubscriptionID($request->getPostParam('order_unique_id'));
            }
        }
        $this->setProductID($request->getPostParam('item_name'));
    }

    public function readRequestAffiliateVariables(Pap_Tracking_Request $request) {
        $this->setUserFirstName($request->getPostParam('first_name'));
        $this->setUserLastName($request->getPostParam('last_name'));
        $this->setUserEmail($request->getPostParam('email'));
        $this->setUserCity($request->getPostParam('city'));
        $this->setUserAddress($request->getPostParam('address_1'));
    }

    public function isRecurring() {
        if ($this->getType() == self::STATUS_PAID_RECUR) {
            return true;
        }
        return false;
    }

    protected function getRecurringTotalCost() {
        return $this->getTotalCost();
    }

    protected function processSubscriptionPayment() {
        $this->debug("Start registering recurring payment / subscription");

        $form = new Pap_Features_RecurringCommissions_RecurringCommissionsForm();
        try {
            if (Gpf_Settings::get(PremiumWebCartIPN_Config::PROCESS_WHOLE_CART_AS_ONE_TRANSACTION) == GPF::YES) {
                $this->debug('Creating recurring commission for orderid: ' . $this->getSubscriptionID());
                $form->createCommissionsNoRpc($this->getSubscriptionID(), null, $this->getRecurringTotalCost());
            } else {
                $request = $this->getRequestObject();
                $i=1;
                while ($request->getPostParam('amount'.$i) != '') {
                    $this->debug('Creating separate recurring commission for orderid: ' . $this->getSubscriptionID().'('.$i.')');
                    $form->createCommissionsNoRpc($this->getSubscriptionID().'('.$i.')', null, $this->getRecurringTotalCost());
                    $i++;
                }
            }
            $this->debug("Recurring commission processed.");
        } catch (Gpf_Exception $e) {
            $this->debug("Error occurred during launching recurring commission: " . $e->getMessage());
            $this->debug("Registering new recurring commission.");
            $this->findPaymentBySubscriptionID();
            $this->registerCommission();
        }
        $this->debug("End registering recurring payment / subscription");
    }

    protected function processAccountIdAndVisitorId(Pap_Tracking_ActionTracker $saleTracker, $cookie) {
        parent::processAccountIdAndVisitorId($saleTracker, $cookie);
        if (Gpf_Settings::get(PremiumWebCartIPN_Config::APPROVE_AFFILIATE) == Gpf::YES) {
            $this->debug('Automatic approval of affiliates with sale is enabled');
            $userId = $this->computeAffiliateId($saleTracker->getVisitorId(), $saleTracker->getAccountId());
            try {
                $affiliate = new Pap_Common_User();
                $affiliate->setId($userId);
                $affiliate->load();
                if ($affiliate->getStatus() == Pap_Common_Constants::STATUS_PENDING) {
                    $affiliate->setStatus(Pap_Common_Constants::STATUS_APPROVED);
                    $affiliate->update();
                }
            } catch (Gpf_Exception $e) {
                $this->debug('Error occurred during approving the affiliate with id=' . $userId);
            }
        }
    }

    public function getOrderID() {
        if($this->isRecurring()) {
            return $this->getSubscriptionID();
        } else {
            return $this->getTransactionID();
        }
    }

    protected function isAffiliateRegisterAllowed() {
        return (Gpf_Settings::get(PremiumWebCartIPN_Config::REGISTER_AFFILIATE) == Gpf::YES);
    }

    protected function prepareSales(Pap_Tracking_ActionTracker $saleTracker) {
        if (Gpf_Settings::get(PremiumWebCartIPN_Config::PROCESS_WHOLE_CART_AS_ONE_TRANSACTION) == GPF::YES) {
            parent::prepareSales($saleTracker);
        } else {
            $this->prepareSeparateCartItems($saleTracker);
        }
    }

    private function prepareSeparateCartItems(Pap_Tracking_ActionTracker $saleTracker) {
        if ($this->xml == null) {
            $this->debug('Preparing separate cart items from request');
            $request = $this->getRequestObject();

            $i=1;
            while ($request->getPostParam('amount'.$i) != '') {
                $sale = $saleTracker->createSale();
                $sale->setTotalCost($request->getPostParam('amount'.$i)*$request->getPostParam('quantity'.$i));
                $sale->setOrderID($this->getOrderID()."(".$i.")");
                $sale->setProductID($request->getPostParam('item'.$i));
                $sale->setData1($this->getData1());
                $sale->setData2($this->getData2());
                $sale->setData3($this->getData3());
                $sale->setData4($this->getData4());
                $sale->setData5($this->getData5());
                $sale->setCurrency($this->getCurrency());

                if($this->getAffiliateID() != '' && $this->getCampaignID() != '') {
                    $sale->setAffiliateID($this->getAffiliateID());
                    $sale->setCampaignID($this->getCampaignID());
                }

                $this->setVisitorAndAccount($saleTracker, $this->getAffiliateID(), $this->getCampaignID(), $this->getCookie());
                $i++;
            }
        } else { // parse XML here
            $this->debug('Preparing separate cart items from XML:');
            $order = $this->xml->order[0];

            $i = 1;
            foreach ($order->products[0] as $item) {
                if ($item->getName() != 'product') {
                    continue;
                }
                $sale = $saleTracker->createSale();
                $sale->setProductId((string)$item->sku);
                $sale->setTotalCost((string)$item->totalprice);
                $sale->setData1((string)$this->xml->order[0]->customer[0]->email);
                $sale->setData2((string)$item->quantity);
                $sale->setData3((string)$this->xml->order[0]->customer[0]->firstname . ' ' . (string)$this->xml->order[0]->customer[0]->lastname);
                $sale->setData4((string)$this->xml->order[0]->customer[0]->customerid);
                $sale->setOrderId((string)$order->orderuniqueid."(".$i.")");

                if($this->getAffiliateID() != '' && $this->getCampaignID() != '') {
                    $sale->setAffiliateID($this->getAffiliateID());
                    $sale->setCampaignID($this->getCampaignID());
                }

                $this->setVisitorAndAccount($saleTracker, $this->getAffiliateID(), $this->getCampaignID(), $this->getCookie());

                $this->debug("Item $i: Total Cost: ".(string)$item->totalprice.'; SKU: '.(string)$item->sku.';');
                $i++;
            }
            $this->registerAllSales($saleTracker);
        }
    }
}
?>
