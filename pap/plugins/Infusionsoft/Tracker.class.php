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
class Infusionsoft_Tracker extends Pap_Tracking_CallbackTracker {

    private $xml = '';
    private $xmlerror = '';

    /**
     * @return Infusionsoft_Tracker
     */
    public function getInstance() {
        $tracker = new Infusionsoft_Tracker();
        $tracker->setTrackerName("Infusionsoft");
        return $tracker;
    }

    public function checkStatus() {
        return true;
    }

    private function sendXML($xml_data, $url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: text/xml' 
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        
        if (!$response) {
            $this->xmlerror = $error;
            return false;
        }
        
        $this->xml = $response;
        return true;
    }

    private function constructXml($encryptedKey, $customerId) {
        return "<?xml version='1.0' encoding='UTF-8'?" . '>
<methodCall>
<methodName>DataService.query</methodName>
<params>
<param>
<value><string>' . $encryptedKey . '</string></value>
</param>
<param>
<value><string>Invoice</string></value>
</param>
<param>
<value><int>1</int></value>
</param>
<param>
<value><int>0</int></value>
</param>
<param>
<value><struct>
<member><name>ContactId</name>
<value><string>' . $customerId . '</string></value>
</member>
</struct></value>
</param>
<param>
<value><array>
<data>
<value><string>Id</string></value>
<value><string>InvoiceTotal</string></value>
<value><string>ProductSold</string></value>
</data>
</array></value>
</param>
</params>
</methodCall>';
    }

    /**
     *  @return Pap_Tracking_Request
     */
    protected function getRequestObject() {
        return Pap_Contexts_Action::getContextInstance()->getRequestObject();
    }

    public function readRequestVariables() {
        $request = $this->getRequestObject();
        $this->debug(' Data received: ' . print_r($request, true));
        
        $customerId = stripslashes($request->getRequestParameter('custId'));
        $this->setCookie($request->getRequestParameter('visitorId'));
        
        $encryptedKey = Gpf_Settings::get(Infusionsoft_Config::API_KEY);
        $subdomain = Gpf_Settings::get(Infusionsoft_Config::SUBDOMAIN);
        $requestURL = 'https://' . $subdomain . '.infusionsoft.com/api/xmlrpc';
        
        if (empty($encryptedKey) || empty($subdomain)) {
            $this->debug(' Some of mandatory API values are missing, please check your plugin settings!');
            return false;
        }
        
        if (empty($customerId)) {
            $this->debug(' Customer ID not in the request, ending.');
            return false;
        }
        
        $requestXml = $this->constructXml($encryptedKey, $customerId);
        
        $this->debug(' Sending XML request');
        if ($this->sendXML($requestXml, $requestURL)) {
            $response = new SimpleXMLElement($this->xml);
        } else {
            $this->debug(' An error occurred when communicating with the remote server: ' . $this->xmlerror);
            return false;
        }
        if ($response->fault->value != null) {
            $this->debug(' An error occurred when communicating with the remote server: ' . $response->fault->value->struct->member[1]->value->asXML());
            return;
        }
        
        
        $responseObj = new SimpleXMLElement($this->parseObjToNiceXml($response));
        $this->debug(' XML response received: '.print_r($responseObj, true));
        
        foreach ($responseObj->member as $item) {
            if ($item->name == 'ProductSold') {
                $this->setProductID((string) $item->value);
            }
            if ($item->name == 'InvoiceTotal') {
                $this->setTotalCost((string) $item->value);
            }
            if ($item->name == 'Id') {
                $this->setTransactionID((string) $item->value);
            }
        }
        
        $this->setEmail($request->getRequestParameter('email'));
        $this->setData1($request->getRequestParameter('email'));
    }
    
    private function parseObjToNiceXml($obj) {
        $content = $obj->params->param->value->array->data->value->struct;
        $responseXml = '';
        if ($content == null || $content->member == null) {
            $this->debug(' No items available');
            return '<members></members>';
        }
        
        foreach ($content->member as $item) {
            $responseXml .= $item->asXML();
        }
        $string = strip_tags($responseXml, '<member><name><value>');
        return '<members>'.$string.'</members>';
    }

    public function getOrderID() {
        return $this->getTransactionID();
    }
}
?>
