<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @author Ladislav Acs
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
class FastSpring_Tracker extends Pap_Tracking_CallbackTracker {

    public function checkStatus() {
        if ($this->getPaymentStatus() == Gpf::NO) {
            return false;
        }
        return true;
    }

    /**
     * @param String $subscriptionReference
     * @throws Gpf_Exception
     * @return Pap_Common_Transaction|boolean
     */
    private function getTransactionIdFromSubscriptionReference($subscriptionReference){
        $transaction = new Pap_Common_Transaction();
        if ($output = $this->findFirstRecordWithData($transaction, Pap_Db_Table_Transactions::DATA2, $subscriptionReference)) {
            $this->debug('Initial transaction found based on subscription reference: '.$subscriptionReference);
            return $output;
        }

        throw new Gpf_Exception('Parent transaction for subscription reference: ' . $subscriptionReference . ' not found.');
    }

    /**
     * @return FastSpring_Tracker
     */
    public function getInstance() {
        $tracker = new FastSpring_Tracker();
        $tracker->setTrackerName("FastSpring");
        return $tracker;
    }

    /**
     *  @return Pap_Tracking_Request
     */
    protected function getRequestObject() {
        return Pap_Contexts_Action::getContextInstance()->getRequestObject();
    }

    public function readRequestVariables() {
        $request = $this->getRequestObject();
        $this->debug('Request data received: '.print_r($request, true));
        
        if (($request->getPostParam('isRebill') == "true") && ($request->getPostParam('SubscriptionReference') != '') && ($request->getPostParam('OrderItemProductName') != '')) {
            $SubscriptionReference = stripslashes($request->getPostParam('SubscriptionReference'));
            $OrderID = $request->getPostParam('OrderID');
            $this->debug('Recurring order, subscription reference: '.$SubscriptionReference);
            try {
                $initialTransaction = $this->getTransactionIdFromSubscriptionReference($SubscriptionReference);
                $initialTransactionId = $initialTransaction->getId();
                $initialTransactionAffiliateId = $initialTransaction->getUserId();
                $initialTransactionCampaignId = $initialTransaction->getCampaignId();
            } catch (Gpf_Exception $e) {
                $this->debug($e->getMessage());
                $this->setPaymentStatus(Gpf::NO);
                return;
            }
            $this->setAffiliateID($initialTransactionAffiliateId);
            $this->setCampaignID($initialTransactionCampaignId);
            $this->setTransactionID($SubscriptionReference." - ". $OrderID);
            $this->setData2($initialTransactionId);
        } elseif (($request->getPostParam('isRebill') == "false") && $request->getPostParam('OrderItemProductName') != '') {
            $PAPVisitorId = $request->getPostParam('OrderReferrer');
            $this->debug('New general order, PAPVisitorId: '.$PAPVisitorId);

            $this->setCookie($PAPVisitorId);

            $this->setTransactionID($request->getPostParam('OrderID')."_".$request->getPostParam('OrderItemProductName'));

            if ($request->getPostParam('SubscriptionReference') != '') {
                $this->setData2($request->getPostParam('SubscriptionReference'));
            }
        } else {
            $this->setPaymentStatus(Gpf::NO);
            return;
        }

        $this->setTotalCost($request->getPostParam('OrderItemTotal'));
        $this->setProductID($request->getPostParam('OrderItemProductName'));
        $this->setCurrency($request->getPostParam('OrderItemCurrency'));

        $this->setData1($request->getPostParam('CustomerEmail'));
    }

    public function getOrderID() {
        return $this->getTransactionID();
    }
}
?>
