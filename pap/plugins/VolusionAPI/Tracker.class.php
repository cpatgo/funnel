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
class VolusionAPI_Tracker extends Pap_Tracking_CallbackTracker {
    const ORDERURL = 'net/WebService.aspx?Login=%l%&EncryptedPassword=%p%&EDI_Name=Generic\Orders&SELECT_Columns=o.PaymentAmount,o.OrderID,o.Custom_Field_%c%,o.SalesTax1,o.SalesTax2,o.SalesTax3,o.TotalShippingCost,od.CouponCode,od.DiscountValue,od.ProductID,od.ProductPrice,od.Quantity&WHERE_Column=o.OrderID&WHERE_Value=';
    const CUSTOMERURL = 'net/WebService.aspx?Login=%l%&EncryptedPassword=%p%&EDI_Name=Generic\Customers&SELECT_Columns=CustomerID,BillingAddress1,City,EmailAddress,FirstName,LastName&WHERE_Column=CustomerID&WHERE_Value=';

    private $xml;
    /**
     * @return VolusionAPI_Tracker
     */
    public function getInstance() {
        $tracker = new VolusionAPI_Tracker();
        $tracker->setTrackerName("VolusionAPI");
        return $tracker;
    }

    public function checkStatus() {
        if ($this->getPaymentStatus() == 'Failed') {
            return false;
        }
        return true;
    }

    protected function getRequestObject() {
        return Pap_Contexts_Action::getContextInstance()->getRequestObject();
    }

    public function readRequestVariables() {
        $request = $this->getRequestObject();

        $this->setTransactionID($request->getRequestParameter('orderID'));

        if ($this->getOrderID() == '') {
            $this->setPaymentStatus('Failed');
            return; // no data to process
        }

        $url = str_replace("%l%",Gpf_Settings::get(VolusionAPI_Config::LOGIN),self::ORDERURL);
        $url = str_replace("%p%",Gpf_Settings::get(VolusionAPI_Config::PASS),$url);
        $url = str_replace("%c%",Gpf_Settings::get(VolusionAPI_Config::CUSTOM_NUMBER),$url);
        $url = Gpf_Settings::get(VolusionAPI_Config::VOLUSION_URL).$url.$this->getOrderID();

        $this->debug("Volusion API: Calling order URL ".$url);
        $input = file_get_contents($url);
        $this->debug("Volusion API: order XML: ".$input);

        try {
            $xml = new SimpleXMLElement($input);
            $this->xml = $xml;
        } catch (Exception $e) {
            $this->setPaymentStatus('Failed');
            $this->debug("Volusion API: order XML has a wrong format");
            return;
        }

        if ($xml->Orders->OrderDetails == '') {
            $this->setPaymentStatus('Failed');
            return;
        }
        $this->setCookie((string)$xml->Orders->{"Custom_Field_".Gpf_Settings::get(VolusionAPI_Config::CUSTOM_NUMBER)});

        $this->setTotalCost($this->adjustTotalCost($xml->Orders));

        $prodID = "";
        foreach ($xml->Orders->OrderDetails as $child) {
            if ($child->CouponCode == "") {
                $prodID .= (string)$child->ProductCode.'; ';
            } elseif (Gpf_Settings::get(VolusionAPI_Config::USE_COUPON) == Gpf::YES) { // use coupon?
                $this->setCoupon((string)$child->CouponCode);
            }
        }

        if ($prodID != "") {
            $this->setProductID(substr($prodID,0,-2));
        }

        $this->setEmail($request->getPostParam('payer_email'));

        if ($this->isAffiliateRegisterAllowed()) $this->readRequestAffiliateVariables((string)$xml->Orders->CustomerID);
    }

    public function readRequestAffiliateVariables($customerID) {
        $url = str_replace("%l%",Gpf_Settings::get(VolusionAPI_Config::LOGIN),self::CUSTOMERURL);
        $url = str_replace("%p%",Gpf_Settings::get(VolusionAPI_Config::PASS),$url);
        $url = Gpf_Settings::get(VolusionAPI_Config::VOLUSION_URL).$url.$customerID;

        $this->debug("Volusion API: Calling customer URL ".$url);
        $input = file_get_contents($url);
        $this->debug("Volusion API: customer XML: ".$input);

        try {
            $xml = new SimpleXMLElement($input);
            $this->xml = $xml;
        } catch (Exception $e) {
            $this->debug("Volusion API: customer XML has a wrong format");
            return;
        }

        $this->setUserFirstName((string)$xml->Customers->FirstName);
        $this->setUserLastName((string)$xml->Customers->LastName);
        $this->setUserEmail((string)$xml->Customers->EmailAddress);
        $this->setUserCity((string)$xml->Customers->City);
        $this->setUserAddress((string)$xml->Customers->BillingAddress1);
    }

    public function getOrderID() {
        return $this->getTransactionID();
    }

    protected function isAffiliateRegisterAllowed() {
        return (Gpf_Settings::get(VolusionAPI_Config::REGISTER_AFFILIATE) == Gpf::YES);
    }

    protected function prepareSales(Pap_Tracking_ActionTracker $saleTracker) {
        if (Gpf_Settings::get(VolusionAPI_Config::PER_PRODUCT) == GPF::YES) {
            $this->prepareSeparateCartItems($saleTracker);
        } else {
            parent::prepareSales($saleTracker);
        }
    }

    private function prepareSeparateCartItems(Pap_Tracking_ActionTracker $saleTracker) {
        $xml = $this->xml;

        $i = 1;
        foreach ($xml->Orders->OrderDetails as $child) {
            if ($child->CouponCode == "") {
                $sale = $saleTracker->createSale();
                $sale->setTotalCost((float)$child->ProductPrice*(float)$child->Quantity);
                $sale->setOrderID($this->getOrderID()."_".$i);
                $sale->setProductID((string)$child->ProductCode);
                $sale->setData1($this->getData1());
                $sale->setData2($this->getData2());
                $sale->setData3($this->getData3());
                $sale->setData4($this->getData4());
                $sale->setData5($this->getData5());
                $sale->setChannelId($this->getChannelId());

                if($this->getAffiliateID() != '' && $this->getCampaignID() != '') {
                    $sale->setAffiliateID($this->getAffiliateID());
                    $sale->setCampaignID($this->getCampaignID());
                }

                $this->setVisitorAndAccount($saleTracker, $this->getAffiliateID(), $this->getCampaignID(), $this->getCookie());
                $i++;
            }
        }
    }

    private function adjustTotalCost($xml) {
        $totalCost = (float)$xml->PaymentAmount;
        $this->debug('Original totalcost: '.$totalCost);

        if (Gpf_Settings::get(VolusionAPI_Config::REDUCE_TAX)==Gpf::YES) {
            $tax = (float)$xml->SalesTax1 + (float)$xml->SalesTax2 + (float)$xml->SalesTax3;
            $totalCost = $totalCost-$tax;
            $this->debug('Discounting tax ('.$tax.') from totalcost.');
        }

        if (Gpf_Settings::get(VolusionAPI_Config::REDUCE_SHIPPING)==Gpf::YES) {
            $totalCost = $totalCost - (float)$xml->TotalShippingCost;
            $this->debug('Discounting shipping ('.(float)$xml->TotalShippingCost.') from totalcost.');
        }

        $this->debug('Totalcost after discounts: '.$totalCost);
        return $totalCost;
    }
}
?>
