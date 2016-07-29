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
 *   http://www.postaffiliatepro.com/licenses/license
 *
 */

/**
 * @package PostAffiliatePro plugins
 */
class BigCommerceAPI_Tracker extends Pap_Tracking_CallbackTracker {

    protected $xml = '';
    protected $xmlerror = '';

    /**
     * @return BigCommerceAPI_Tracker
     */
    public function getInstance() {
        $tracker = new BigCommerceAPI_Tracker();
        $tracker->setTrackerName("BigCommerceAPI");
        return $tracker;
    }

    public function checkStatus() {
        if ($this->getPaymentStatus() == 'OK') {
            return true;
        }
        $this->debug(' Invalid status received: ' . $this->getPaymentStatus());
        return false;
    }

    protected function computeTotalCost(SimpleXMLElement $product) {
        $totalCost = ((float) $product->base_price * (float) $product->quantity);
        $discount = 0;
        if (isset($product->applied_discounts->discount)) {
            $discount = (float) $product->applied_discounts->discount->amount;
        }
        return ($totalCost - $discount);
    }

    /**
     *  @return Pap_Tracking_Request
     */
    protected function getRequestObject() {
        return Pap_Contexts_Action::getContextInstance()->getRequestObject();
    }

    protected function sendXML($url, $authentication) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $authentication);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/xml','Content-Type: application/xml'));//DEPRECATED
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if (!$response) {
            $this->xmlerror = $error;
            return false;
        }

        $response = str_replace('<![CDATA[', '', $response);
        $response = str_replace(']]>', '', $response);
        $this->xml = $response;
        return true;
    }

    public function readRequestVariables() {
        $request = $this->getRequestObject();
        $orderId = $request->getRequestParameter('orderId');
        $cookie = $request->getRequestParameter('visitorId');

        $this->debug(" order ID received: $orderId");

        if (Gpf_Settings::get(BigCommerceAPI_Config::API_PATH) == '') {
            $this->debug(' Please configure your plugin first! The API Path is missing!');
            $this->setPaymentStatus('FAILED');
            return false;
        }

        if (empty($orderId) || empty($cookie)) {
            $this->setPaymentStatus('EMPTY');
            return false;
        }
        $this->setPaymentStatus('OK');

        $this->setCookie($cookie);
        $this->setTransactionID($orderId);
        $this->setData1($request->getRequestParameter('email'));

        $url = Gpf_Settings::get(BigCommerceAPI_Config::API_PATH) . 'orders/' . $orderId . '/products';
        $authentication = Gpf_Settings::get(BigCommerceAPI_Config::API_USERNAME) . ':' . Gpf_Settings::get(BigCommerceAPI_Config::API_TOKEN);

        $this->debug(" Sending an XML request to $url");
        if ($this->sendXML($url, $authentication)) {
            $this->debug(' XML response received: ' . $this->xml);
            $this->xml = preg_replace_callback("/&[^; ]{0,6}.?/", create_function('$matches', "return ((substr(\$matches[0],-1) == ';') ? \$matches[0] : '&amp;'.substr(\$matches[0],1));" ), $this->xml);
            try {
                @$response = new SimpleXMLElement($this->xml);
            } catch (Exception $e) {
                $this->error(' Decoding xml error: '.$e->getMessage().', loaded xml: ' . $this->xml);
                $this->setPaymentStatus('FAILED');
                return false;
            }
        } else {
            $this->error(' An error occurred when communicating with the remote server: ' . $this->xmlerror);
            $this->setPaymentStatus('FAILED');
            return false;
        }
        if (isset($response->error)) {
            $this->error(' An error occurred when communicating with the remote server: ' . $response->error->message);
            $this->setPaymentStatus('FAILED');
        }
    }

    public function getOrderID() {
        return $this->getTransactionID();
    }

    protected function prepareSales(Pap_Tracking_ActionTracker $saleTracker) {
        if (Gpf_Settings::get(BigCommerceAPI_Config::PER_PRODUCT) == Gpf::YES) {
            $this->prepareSeparateCartItems($saleTracker);
        } else {
            $total = 0;
            $productIds = '';
            if (!($XMLObj = $this->getXMLObject())) {
                return;
            }
            foreach ($XMLObj as $product) {
                $total += $this->computeTotalCost($product);
                $productIds .= (string) $product->sku . '; ';
            }
            $sale = $saleTracker->createSale();
            $sale->setTotalCost($total);
            $sale->setOrderID($this->getOrderID());
            $sale->setProductID(rtrim(trim($productIds), ';'));
            $sale->setData1($this->getData1());

            $this->setVisitorAndAccount($saleTracker, $this->getAffiliateID(), $this->getCampaignID(), $this->getCookie());
        }
    }

    protected function prepareSeparateCartItems(Pap_Tracking_ActionTracker $saleTracker) {
        $i = 1;
        if (!($XMLObj = $this->getXMLObject())) {
            return;
        }
        foreach ($XMLObj as $product) {
            $sale = $saleTracker->createSale();
            $sale->setTotalCost($this->computeTotalCost($product));
            $sale->setOrderID($this->getOrderID() . '(' . $i . ')');
            $sale->setProductID((string) $product->sku);
            $sale->setData1($this->getData1());

            $this->setVisitorAndAccount($saleTracker, $this->getAffiliateID(), $this->getCampaignID(), $this->getCookie());
            $i++;
        }
    }

    protected function getXMLObject() {
        try {
            @$XMLObj = new SimpleXMLElement($this->xml);
        } catch (Exception $e) {
            return false;
        }
        return $XMLObj;
    }
}
