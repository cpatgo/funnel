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
class Recurly_Tracker extends Pap_Tracking_CallbackTracker {

    const NEWPAYMENT = 'successful_payment_notification';

    /**
     * @return Recurly_Tracker
     */
    public function getInstance() {
        $tracker = new Recurly_Tracker();
        $tracker->setTrackerName("Recurly");
        return $tracker;
    }

    public function checkStatus() {
        $this->debug("Checking type '".$this->getType()."'");
        if (($this->getType() != self::NEWPAYMENT)) {
            $this->debug('Not payment_notification: ' . $this->getType());
            return false;
        }
        return true;
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

        if (Gpf_Settings::get(Recurly_Config::RESEND_URL) != "") {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, Gpf_Settings::get(Recurly_Config::RESEND_URL));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            //curl_setopt($ch, CURLOPT_USERAGENT, $defined_vars['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $input);
            curl_exec($ch);

            /*
             $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, Gpf_Settings::get(Recurly_Config::RESEND_URL));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
            curl_exec($ch);
            */
        }

        $this->debug("Input get: " . $input);
        try {
            $xml = new SimpleXMLElement($input);
        } catch (Exception $e) {
            $this->setPaymentStatus("Failed");
            $this->debug('Wrong XML format!');
            return false;
        }

        // read last tag to find out what kind of request this is, e.g. </new_subscription_notification>
        $status = strrpos($input,"</");
        $status = substr($input,$status+2,strlen($input)-1);
        $status = substr($status,0,strrpos($status,">"));

        $this->setType($status);

        if ($this->getType() != self::NEWPAYMENT) {
            return;
        }

        $this->setTotalCost((string)$xml->{"transaction"}->{"amount_in_cents"}/100);
        $this->setProductID((string)$xml->{"transaction"}->{"plan_code"});
        $this->setTransactionID((string)$xml->{"transaction"}->{"invoice_number"});
        $this->setSubscriptionID((string)$xml->{"transaction"}->{"subscription_id"});
        $this->setData1((string)$xml->{"account"}->{"account_code"}); // email
        $this->setData2((string)$xml->{"transaction"}->{"subscription_id"});

        $transaction = $this->getTransactionBy(Pap_Db_Table_Transactions::DATA1, $this->getData1()); // search for an email in Data1
        if ($transaction == null) {
            $transaction = $this->getTransactionBy(Pap_Db_Table_Transactions::ORDER_ID, $this->getData1()); // search for an email in order ID
            if ($transaction == null) {
                $transaction = $this->getTransactionBy(Pap_Db_Table_Transactions::DATA2, $this->getData2()); // search for subscription ID in Data2
                if ($transaction == null) {
                    $this->debug('Original transaction not found.');
                    return;
                }
            }
        }
        $this->setAccountId($transaction->getAccountId());
        $this->setAffiliateID($transaction->getUserId());
        $this->setCampaignID($transaction->getCampaignId());
        $this->setBannerID($transaction->getBannerId());
    }

    private function getTransactionBy($operand, $secondOperand) {
        $status = array(Pap_Common_Constants::STATUS_APPROVED, Pap_Common_Constants::STATUS_PENDING);
        $types = array(Pap_Common_Constants::TYPE_SALE, Pap_Common_Constants::TYPE_ACTION, Pap_Common_Constants::TYPE_LEAD);

        $select = new Gpf_SqlBuilder_SelectBuilder();
        $select->select->addAll(Pap_Db_Table_Transactions::getInstance());
        $select->from->add(Pap_Db_Table_Transactions::getName());
        $select->where->add($operand, "=", $secondOperand);
        $select->where->add(Pap_Db_Table_Transactions::R_TYPE, "IN", $types);
        $select->where->add(Pap_Db_Table_Transactions::R_STATUS, "IN", $status);
        $select->limit->set(0, 1);
        $select->orderBy->add(Pap_Db_Table_Transactions::DATE_INSERTED, false);
        $transaction = new Pap_Common_Transaction();

        try {
            $transaction->fillFromSelect($select);
            return $transaction;
        } catch (Gpf_DbEngine_NoRowException $e) {
            $this->debug('No transaction found for ' . $operand . ': ' . $secondOperand);
            return null;
        }
    }

    public function getOrderID() {
        return $this->getTransactionID();
    }
}
?>
