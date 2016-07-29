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
 *   http://www.qualityunit.com/licenses/license
 *
 */

/**
 * @package PostAffiliatePro plugins
 */
class CommerceGate_Tracker extends Pap_Tracking_CallbackTracker {

    const TRANS_TYPE_SALE = 'SALE';
    const TRANS_TYPE_REBILL = 'REBILL';
    const TRANS_TYPE_UPSELL = 'UPSELL';
    const TRANS_TYPE_REFUND = 'REFUND';
    const TRANS_TYPE_CHARGEBACK = 'CHARGEBACK';
    const TRANS_TYPE_CANCELMEMBERSHIP = 'CANCELMEMBERSHIP';

    private $parentTransId;

    /**
     * @return CommerceGate_Tracker
     */
    public function getInstance() {
        $tracker = new CommerceGate_Tracker();
        $tracker->setTrackerName("CommerceGate");
        return $tracker;
    }

    private function getParentTransId() {
        return $this->parentTransId;
    }

    private function setParentTransId($value) {
        $this->parentTransId = $value;
    }

    private function refundChargeback() {
        $transaction = new Pap_Common_Transaction();
        $transaction->processRefundChargeback($this->getOrderID(), Pap_Db_Transaction::TYPE_REFUND, '', $this->getOrderID(), 0, true);
    }

    public function checkStatus() {
        $this->sendBackApproval(); // send success message

        if (Gpf_Settings::get(CommerceGate_Config::DECLINE_AFFILIATE) == Gpf::YES) {
            $this->declineAffiliate();
        }

        if ($this->getPaymentStatus() == "Failed") { // in case of wrong XML
            return false;
        }

        if (($this->getType() == self::TRANS_TYPE_REFUND) || ($this->getType() == self::TRANS_TYPE_CHARGEBACK)) {
            $this->debug('Transaction '.$this->getOrderID().' will be marked as refunded.');
            try {
                $this->refundChargeback();
                $this->debug('Refund completed, ending.');
            } catch (Gpf_Exception $e) {
                $this->debug('Error occurred during transaction refund:'.$e->getMessage());
            }
            return false;
        }

        // check payment status
        if (($this->getType() == self::TRANS_TYPE_SALE) || ($this->getType() == self::TRANS_TYPE_REBILL) || ($this->getType() == self::TRANS_TYPE_UPSELL)) {
            return true;
        }

        return false;
    }

    private function declineAffiliate() {
        if (($this->getType() == self::TRANS_TYPE_REFUND) || ($this->getType() == self::TRANS_TYPE_CHARGEBACK) || ($this->getType() == self::TRANS_TYPE_CANCELMEMBERSHIP)) {
            try {
                $affiliate = new Pap_Affiliates_User();
                $affiliate = $affiliate->loadFromUsername($_POST['payer_email']);

                if ($affiliate->getStatus() != Pap_Common_Constants::STATUS_DECLINED) {
                    $affiliate->setStatus(Pap_Common_Constants::STATUS_DECLINED);
                    $affiliate->update(array(Gpf_Db_Table_Users::STATUS));
                    $this->debug('Affiliate with username = '.$this->getEmail().' has been declined after status '.$this->getType());
                }
            } catch (Gpf_Exception $e) {
                $this->debug('Error occurred during declining the affiliate '.$this->getEmail().'. Exception: '. $e->getMessage());
            }
        }
    }

    private function sendBackApproval() {
        echo "SUCCESS";
        return;
    }

    /**
     *  @return Pap_Tracking_Request
     */
    private function getXMLObject() {
        $input = stripslashes($_POST['message']);

        try {
            $xml = new SimpleXMLElement($input);
            return $xml;
        }
        catch (Exception $e) {
            $this->setPaymentStatus("Failed");
            $this->debug('Wrong XML format.');
            return false;
        }
    }

    public function readRequestVariables() {
        $xml = $this->getXMLObject();
        $this->debug(' received data: '.print_r($xml,true));

        if ($xml === false) return;

        $_SERVER["REMOTE_ADDR"] = (string)$xml->Ip;

        $custom = 'op'.Gpf_Settings::get(CommerceGate_Config::CUSTOM_FIELD);
        $this->setCookie((string)$xml->$custom);

        $this->setType((string)$xml->TransactionType);
        $this->setTotalCost((int)$xml->Amount / 100);
        $this->setTransactionID((string)$xml->TransactionID);
        $this->setProductID((string)$xml->OfferId);
        $this->setEmail((string)$xml->Email);
        $this->setData1((string)$xml->Email);
        $this->setParentTransId((string)$xml->TransactionReferenceID);
        $this->setCurrency((string)$xml->Currency);

        if ($this->isAffiliateRegisterAllowed()) {
            $this->readRequestAffiliateVariables($xml);
        }

        if ($this->isRecurring()) {
            $this->setData2($this->getParentTransId());
        }
    }

    public function isRecurring() {
        if ($this->getType() == self::TRANS_TYPE_REBILL) {
            return true;
        }
        return false;
    }

    private function readRequestAffiliateVariables($xml) {
        $name = (string)$xml->CardHolder;
        $this->setUserFirstName(substr($name,0,strpos($name," ")));
        $this->setUserLastName(substr($name,strpos($name," ")+1));
        $this->setUserEmail((string)$xml->Email);
    }

    public function getOrderID() {
        return $this->getTransactionID();
    }

    protected function isAffiliateRegisterAllowed() {
        return (Gpf_Settings::get(CommerceGate_Config::REGISTER_AFFILIATE) == Gpf::YES);
    }
}
?>
