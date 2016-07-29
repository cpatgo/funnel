<?php
/**
 *   @copyright Copyright (c) 2013 Quality Unit s.r.o.
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
class VoguePay_Tracker extends Pap_Tracking_CallbackTracker {

    /**
     * @return VoguePay_Tracker
     */
    public function getInstance() {
        $tracker = new VoguePay_Tracker();
        $tracker->setTrackerName('VoguePay');
        return $tracker;
    }

    public function checkStatus() {
        // check payment status
        if ($this->getPaymentStatus() != 'Approved') {
            $this->debug('Payment status is NOT APPROVED. Transaction: ' . $this->getTransactionID() . ', payer email: ' . $this->getEmail() . ', status: ' . $this->getPaymentStatus());
            return false;
        }

        return true;
    }

    /**
     *  @return Pap_Tracking_Request
     */
    protected function getRequestObject() {
        return Pap_Contexts_Action::getContextInstance()->getRequestObject();
    }

    public function readRequestVariables() {
        $request = $this->getRequestObject();

        $this->setCookie(stripslashes($request->getRequestParameter('pap_custom')));
        $transid = $request->getRequestParameter('transaction_id');

        $this->debug(' Cookies received: ' . $request->getRequestParameter('pap_custom') . ', trasnaction id: ' . $transid);

        if (empty($transid)) {
            $this->debug(' Params missing, ENDING.');
            return false;
        }

        $json = file_get_contents('https://voguepay.com/?v_transaction_id=' . $transid . '&type=json');
        $transaction = json_decode($json, true);

        $this->debug(' REST API for transaction ' . $request->getRequestParameter('transaction_id') . ' received: ' . print_r($transaction, true));

        $this->setTotalCost($transaction['total_credited_to_merchant']);

        $this->setProductID($transaction['memo']);
        $this->setPaymentStatus($transaction['status']);
        $this->setTransactionID($transaction['transaction_id']);
        $this->setEmail($transaction['email']);
        $this->setData1($transaction['email']);
    }

    public function getOrderID() {
        return $this->getTransactionID();
    }
}
?>
