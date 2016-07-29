<?php
/**
 *   @copyright Copyright (c) 2014 Quality Unit s.r.o.
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
class PagSeguro_Tracker extends Pap_Tracking_CallbackTracker {
    const LIVE = 'ws.pagseguro.uol.com.br';
    const SANDBOX = 'ws.sandbox.pagseguro.uol.com.br';

    protected $xml;

    private function getTransactionIdFromOrderId($orderId){
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
     * @return PagSeguro_Tracker
     */
    public function getInstance() {
        $tracker = new PagSeguro_Tracker();
        $tracker->setTrackerName('PagSeguro');
        return $tracker;
    }

    private function refundChargeback() {
        $transaction = new Pap_Common_Transaction();
        $transaction->processRefundChargeback($this->getTransactionIdFromOrderId($this->getOrderID()), Pap_Db_Transaction::TYPE_REFUND, '',
                $this->getOrderID(), 0, true);
    }

    public function checkStatus() {
        if (($this->getPaymentStatus() == '6') || ($this->getPaymentStatus() == '6')) {
            $this->debug('Transaction '.$this->getOrderID().' will be marked as a refund');
            try {
                $this->refundChargeback();
                $this->debug('Refund complete, ending...');
            }
            catch (Gpf_Exception $e) {
                $this->debug('Error ocurred during transaction refund:'.$e->getMessage());
            }
            return false;
        }

        // check payment status
        if ($this->getPaymentStatus() != '3') {
            $this->debug('Payment status is invalid. Transaction: '.$this->getTransactionID().', payer email: '.$this->getEmail().', status: '.$this->getPaymentStatus());
            return false;
        }

        return true;
    }

    /**
     *  @return Pap_Tracking_Request
     */
    private function getRequestObject() {
        return Pap_Contexts_Action::getContextInstance()->getRequestObject();
    }
    
    private function readXmlData($url) {
        $this->debug("Sending request to $url");
        $post_data = @file_get_contents($url);
        if (get_magic_quotes_gpc()) {
            $post_data = stripslashes($post_data);
        }
        return $post_data;
    }

    public function readRequestVariables() {
        $request = $this->getRequestObject();
        $this->debug(' Data received: '.print_r($request, true));
        
        $notificationCode = $request->getRequestParameter('notificationCode');
        $token = Gpf_Settings::get(PagSeguro_Config::TOKEN);
        $email = Gpf_Settings::get(PagSeguro_Config::EMAIL);
        $url = 'https://'.((Gpf_Settings::get(PagSeguro_Config::TEST_MODE) == Gpf::YES)? self::SANDBOX : self::LIVE).'/v2/transactions/notifications/'.$notificationCode.'?email='.$email.'&token='.$token;

        if (empty($notificationCode)) {
            $this->debug('No data received, ending.');
            return;
        }

        $input = $this->readXmlData($url);
        if ($input == '') {
          $this->debug('No response received, ending.');
          return;
        }
        
        try {
            $xml = new SimpleXMLElement($input);
            $this->xml = $xml;
            $this->debug(' XML response: '.print_r($xml, true));
        }
        catch (Exception $e) {
            $this->setPaymentStatus('Failed');
            $this->debug('Wrong XML format.');
            return;
        }
        
        $cookieValue = (string)$xml->reference;
        if ($request->getRequestParameter('pap_custom') != '') {
            $cookieValue = stripslashes($request->getRequestParameter('pap_custom'));
        }

        $this->setPaymentStatus((string)$xml->status);
        $this->setCookie($cookieValue);
        $this->setTotalCost((string)$xml->netAmount);
        $this->setTransactionID((string)$xml->code);
        $this->setProductID((string)$xml->items->item->description);
        $this->setType((string)$xml->type);

        $this->setEmail((string)$xml->sender->email);
        $this->setData1($this->getEmail());
        $this->setData2((string)$xml->code);

        $this->readRequestAffiliateVariables($xml);
    }

    public function readRequestAffiliateVariables($xml) {
        $name = explode(' ',(string)$xml->sender->name);
        $this->setUserFirstName($name[0]);
        $this->setUserLastName($name[1]);
        $this->setUserEmail((string)$xml->sender->email);
        $this->setUserCity((string)$xml->shipping->address->city);
        $this->setUserAddress((string)$xml->shipping->address->street.' '.(string)$xml->shipping->address->number);
    }

    public function getOrderID() {
        return $this->getTransactionID();
    }

    protected function isAffiliateRegisterAllowed() {
        return (Gpf_Settings::get(PagSeguro_Config::REGISTER_AFFILIATE) == Gpf::YES);
    }

    protected function prepareSales(Pap_Tracking_ActionTracker $saleTracker) {
        if (Gpf_Settings::get(PagSeguro_Config::PROCESS_CART_PER_ITEM) == GPF::YES) {
            $this->prepareSeparateCartItems($saleTracker);
        }
        else {
            parent::prepareSales($saleTracker);
        }
    }

    private function prepareSeparateCartItems(Pap_Tracking_ActionTracker $saleTracker) {
        $i = 1;
        foreach ($this->xml->items as $item) {
            $sale = $saleTracker->createSale();
            $sale->setTotalCost((int)$item->item->quantity * (int)$item->item->amount);
            $sale->setOrderID($this->getOrderID()."($i)");
            $sale->setProductID((string)$item->item->description);
            $sale->setData1($this->getData1());
            $sale->setData2($this->getData2());
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
}
?>
