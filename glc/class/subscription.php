<?php
require_once(dirname(dirname(__FILE__)) . "/authorize/vendor/autoload.php");
require_once(dirname(dirname(__FILE__)) . '/config.php');
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

$merchant_class = getInstance('Class_Merchant');

$default_merchant = $merchant_class->get_default_merchant_provider();

$default_environment = $merchant_class->get_default_merchant_environment();

$environment = $default_environment[0]['option_value'];
// die($environment);

$default_merchant_settings = $merchant_class->get_selected_merchant_settings((int)$default_merchant[0]['option_value'], $default_environment[0]['option_value']);

$merchant_setting = array();
foreach($default_merchant_settings as $setting) {
    $merchant_setting[$setting['setting_name']] = $setting['setting_value'];    
}
// var_dump($merchant_setting);
// var_dump($default_environment);
// var_dump($default_merchant);
$authorize_id = $merchant_setting['authorize_id'];
$authorize_key = $merchant_setting['authorize_key'];


// echo $authorize_id; 
// echo '<br />';
// echo $authorize_key;
// die();
define("AUTHORIZENET_LOG_FILE", "phplog");

if(!empty($username)):

    $merchant_class = getInstance('Class_Merchant');
    $user_class = getInstance("Class_User");
    $payment_class = getInstance("Class_Payment");
    $user_id = '';
    $payment_error = 1;
    $orderid    = substr(microtime(true), 0, 10);
    $ccnumber   = str_replace(' ', '', post_var('cc_number'));
    $cvv        = post_var('cc_ccv');
    $expireMM   = post_var('expireMM');
    $expireYY   = post_var('expireYY');
    $ccexp      = sprintf('%s-%s', $expireYY, $expireMM);
    $membership_details = $user_class->get_membership_by_membership($membership);
    $membership_details = $membership_details[0];
    if($pay_type === 'creditcard'):

        // Common Set Up for API Credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($authorize_id);
        $merchantAuthentication->setTransactionKey($authorize_key);
        
        $refId = 'ref' . time();

        // Subscription Type Info
        $subscription = new AnetAPI\ARBSubscriptionType();
        $subscription->setName("$39 Monthly Pricing");

        $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
        $interval->setLength(1);
        $interval->setUnit("months");

        $paymentSchedule = new AnetAPI\PaymentScheduleType();
        $paymentSchedule->setInterval($interval);
        $paymentSchedule->setStartDate(new DateTime(date('Y-m-d')));
        $paymentSchedule->setTotalOccurrences("9999");

        $subscription->setPaymentSchedule($paymentSchedule);
        $subscription->setAmount($membership_details['amount']);
        
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($ccnumber);
        $creditCard->setExpirationDate($ccexp);

        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);

        $subscription->setPayment($payment);

        $billto = new AnetAPI\NameAndAddressType();
        $billto->setFirstName($payment_fname);
        $billto->setLastName($payment_lname);
        $billto->setCompany($company);
        $billto->setAddress($address1);
        $billto->setCity($city);
        $billto->setState($state);
        $billto->setZip($zip);
        $billto->setCountry($country);

        $subscription->setBillTo($billto);

        $request = new AnetAPI\ARBCreateSubscriptionRequest();
        $request->setmerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscription($subscription);
        $controller = new AnetController\ARBCreateSubscriptionController($request);


        if ($environment === 'live') {
            $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);    
        }
        else {
            $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);    
        }

        if ($response != null)
        {
            if (($tresponse != null) && ($tresponse->getMessages()->getResultCode() == "Ok") )
            {
                //Setting payment error to 0 will tell submit.php to register the user
                $payment_error = 0;
            }
            else
            {
                //If the payment process encountered an error, save the transaction to authorize_ipn table then return error

                $errorMessages = $tresponse->getMessages()->getMessage();
                $payment_data = array(
                    'user_id' => 0,
                    'cc_fname' => $payment_fname,
                    'cc_lname' => $payment_lname,
                    'response' => $errorMessages[0]->getCode(),
                    'responsetext' => $errorMessages[0]->getText(),
                    'authcode' => $errorMessages[0]->getCode(),
                    'transactionid' => '',
                    'avsresponse' => 0,
                    'cvvresponse' => 0,
                    'orderid' => $orderid,
                    'type' => 'subscription',
                    'response_code' => $errorMessages[0]->getCode(),
                    'amount' => $membership_details['amount'],
                    'payment_type' => 3,
                    'date_created' => date('Y-m-d H:i:s')
                );
                $payment_id = $payment_class->authorize_ipn($payment_data);
                $result = array('result' => 'error', 'message' => sprintf('Charge Credit Card ERROR :  Invalid response')); 
                die(json_encode($result));
            }
        } 
        else
        {
            $result = array('result' => 'error', 'message' => sprintf('Charge Credit card Null response returned')); 
            die(json_encode($result));
        }
    endif;
endif;