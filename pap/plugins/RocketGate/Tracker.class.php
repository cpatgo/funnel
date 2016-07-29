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
class RocketGate_Tracker extends Pap_Tracking_CallbackTracker {
    const PURCHASE = 'HostedPage';
    const RECURRING = 'RecurringBilling';
    const REFUND = 'VoidCredit';

    /**
     * @return RocketGate_Tracker
     */
    public function getInstance() {
        $tracker = new RocketGate_Tracker();
        $tracker->setTrackerName("RocketGate");
        return $tracker;
    }

    protected function registerCommission() {
        $this->debug("Start registering sales");
        if ($this->getType() == self::PURCHASE) {
            $this->processWholeCartAsOneTransaction();
        }
        else {
            $this->debug("Sale status received: ".$this->getType());
        }
    }

    private function processWholeCartAsOneTransaction() {
        $saleTracker = new Pap_Tracking_ActionTracker();
        $sale = $saleTracker->createSale();
        $sale->setOrderId($this->getOrderID());
        $sale->setTotalCost($this->getTotalCost());
        $sale->setProductId($this->getProductID());
        $cookie = $this->parseCookie(stripslashes($this->getCookie()));
        $this->setVisitorAndAccount($saleTracker, $this->getAffiliateID(), $this->getCampaignID(), $cookie);
        $this->registerAllSales($saleTracker);
    }

    /**
     *
     * @param $orderId
     * @param $includeRefund
     * @return Gpf_DbEngine_Row_Collection<Pap_Common_Transaction>
     */
    private function getAllTransactionIdsWithOrderId($orderId, $includeRefund){
        $status = array(Pap_Common_Constants::STATUS_APPROVED, Pap_Common_Constants::STATUS_PENDING);
        $types = array(Pap_Common_Constants::TYPE_SALE, Pap_Common_Constants::TYPE_ACTION, Pap_Common_Constants::TYPE_LEAD);
        if ($includeRefund == true) {
            $types[] = Pap_Common_Constants::TYPE_REFUND;
        }

        $select = new Gpf_SqlBuilder_SelectBuilder();
         
        $select->select->addAll(Pap_Db_Table_Transactions::getInstance());
        $select->from->add(Pap_Db_Table_Transactions::getName());
        $select->where->add(Pap_Db_Table_Transactions::ORDER_ID, "=", $orderId);

        $select->where->add(Pap_Db_Table_Transactions::R_TYPE, "IN", $types);
        $select->where->add(Pap_Db_Table_Transactions::R_STATUS, "IN", $status);

        $transaction = new Pap_Common_Transaction();
        return $transaction->loadCollectionFromRecordset($select->getAllRows());
    }

    protected function refundChargeback() {
        $this->debug('Starting refund of transaction with order ID: '.$this->getOrderID());
        $transactions = $this->getAllTransactionIdsWithOrderId($this->getOrderID(), false);
        foreach ($transactions as $parentTransaction) {
            $this->debug('Refunding transaction with id=' . $parentTransaction->getId());
            $parentTransaction->refundChargeback(Pap_Db_Transaction::TYPE_REFUND, '', $this->getOrderID());
        }
        $this->debug('Refund finished');
    }

    protected function sendBackAck() {
        $this->debug("  Sending back OK status (0)");
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?"."><Response><results>0</results></Response>";
    }

    public function isRecurring() {
        if ($this->getType() == self::RECURRING) {
            return true;
        }
        return false;
    }

    public function checkStatus() {
        $this->debug('Status received: '.$this->getType());
        $this->sendBackAck();
        if (($this->getType() == self::PURCHASE)) {
            $this->debug('New order notification, continue to register order...');
            return true;
        }
        if (($this->getType() == self::RECURRING)) {
            $this->debug('Recurring order notification, continue to process order...');
            return true;
        }
        if (($this->getType() == self::REFUND)) {
            $this->refundChargeback();
            return false;
        }
        return false;
    }

    protected function readXmlData() {
        $post_data = file_get_contents('php://input');
        if (get_magic_quotes_gpc()) {
            $post_data = stripslashes($post_data);
        }
        return $post_data;
    }

    protected function getXmlElementByName($name, $elements) {
        foreach ($elements as $element) {
            if ($element->getName() == $name) {
                return $element;
            }
        }
        return false;
    }

    public function readRequestVariables() {
        $input = $this->readXmlData();

        $this->debug("Input get: " . $input);
        try {
            $xml = new SimpleXMLElement($input);
        } catch (Exception $e) {
            $this->setPaymentStatus("Failed");
            $this->debug('Wrong XML format.');
            return false;
        }

        // read last tag to find out what kind of request this is, e.g. </VoidCredit>
        $status = strrpos($input,"</");
        $status = substr($input,$status+2,strlen($input)-1);
        $status = substr($status,0,strrpos($status,">"));

        $this->setType($status);

        $this->setCookie((string)$this->getXmlElementByName('mp',$xml));
        $this->setTotalCost($this->getXmlElementByName('settledAmount',$xml));
        $this->setTransactionID((string)$this->getXmlElementByName('transactID',$xml));
        $this->setSubscriptionID((string)$this->getXmlElementByName('invoideID',$xml));
        $this->setProductID($this->getXmlElementByName('customerFirstName',$xml)." ".$this->getXmlElementByName('customerLastName',$xml));
        $this->setCurrency($this->getXmlElementByName('settledCurrency',$xml));
    }

    public function getOrderID() {
        return $this->getTransactionID();
    }
}
?>
