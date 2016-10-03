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

    $membership_details['membership'] = sprintf('Special %0.2f', $membership_amount);

    if($pay_type === 'creditcard'):
        // Common setup for API credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($authorize_id);
        $merchantAuthentication->setTransactionKey($authorize_key);
        $refId = 'ref' . time();
        // Customer info 
        $customer = new AnetAPI\CustomerDataType();
        $customer->setId($orderid);
        $customer->setEmail($email);
        // Bill To
        $billto = new AnetAPI\CustomerAddressType();
        $billto->setFirstName($payment_fname);
        $billto->setLastName($payment_lname);
        $billto->setCompany($company);
        $billto->setAddress($address1);
        $billto->setCity($city);
        $billto->setState($state);
        $billto->setZip($zip);
        $billto->setCountry($country);
        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($ccnumber);
        $creditCard->setExpirationDate($ccexp);
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);
        // Order info
        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber($orderid);
        $order->setDescription($membership_details['membership']);
        //create a transaction
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType( "authCaptureTransaction"); 
        $transactionRequestType->setOrder($order);
        $transactionRequestType->setAmount($membership_amount);
        $transactionRequestType->setPayment($paymentOne);
        $transactionRequestType->setCustomer($customer);
        $transactionRequestType->setBillTo($billto);
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId( $refId);
        $request->setTransactionRequest( $transactionRequestType);
        $controller = new AnetController\CreateTransactionController($request);
        if ($environment === 'live') {
            $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);    
        }
        else {
            $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);    
        }

        if ($response != null)
        {
            $tresponse = $response->getTransactionResponse();
    
            if (($tresponse != null) && ($tresponse->getResponseCode()=="1") )   
            {
                //Setting payment error to 0 will tell submit.php to register the user
                $payment_error = 0;
            }
            else
            {
                //If the payment process encountered an error, save the transaction to authorize_ipn table then return error
                $payment_data = array(
                    'user_id' => 0,
                    'cc_fname' => $payment_fname,
                    'cc_lname' => $payment_lname,
                    'response' => $tresponse->getResponseCode(),
                    'responsetext' => json_encode($tresponse->getMessages()),
                    'authcode' => (!empty($tresponse->getAuthCode())) ? $tresponse->getAuthCode() : 0,
                    'transactionid' => $tresponse->getTransId(),
                    'avsresponse' => $tresponse->getAvsResultCode(),
                    'cvvresponse' => $tresponse->getCvvResultCode(),
                    'orderid' => $orderid,
                    'type' => 'authCaptureTransaction',
                    'response_code' => $tresponse->getResponseCode(),
                    'amount' => $membership_amount,
                    'payment_type' => 1,
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
?>