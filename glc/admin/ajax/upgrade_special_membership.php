<?php
require_once(dirname(dirname(dirname(__FILE__))) . "/authorize/vendor/autoload.php");
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

// This file will perform ajax requests for getting user in the database
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
if(!isset($_POST)) printf('<script type="text/javascript">window.location="%s/glc/admin";</script>', GLC_URL);

//Ajax request
parse_str($_POST['fields'], $fields);
$user_id = $fields['user_id'];
$user_class = getInstance('Class_User');
$membership_class = getInstance('Class_Membership');
$payment_class = getInstance('Class_Payment');
$merchant_class = getInstance('Class_Merchant');
$email_class = getInstance('Class_Email');
$payment_error = 1;
$payment_method = $fields['payment_method'];
$user = $user_class->get_user($user_id);
$user_membership = $user_class->user_membership($user_id);
$user = $user[0];
$user_membership = $user_membership[0];
$transaction_id = "";

$amount = glc_option('aem_special_registration');
$membership = sprintf('Special %0.2f', $membership_amount);

// Required variables for payment
if($payment_method === 'creditcard'):
    $merchant = $merchant_class->get_one($fields['cc_merchant_id']);
    $merchant = $merchant[0];
	$default_merchant = $merchant_class->get_default_merchant_provider();
	$default_environment = $merchant_class->get_default_merchant_environment();
	$environment = $default_environment[0]['option_value'];

	$default_merchant_settings = $merchant_class->get_selected_merchant_settings($merchant['id'], $default_environment[0]['option_value']);

	$merchant_setting = array();
	foreach($default_merchant_settings as $setting) {
	    $merchant_setting[$setting['setting_name']] = $setting['setting_value'];    
	}
	$authorize_id = $merchant_setting['authorize_id'];
	$authorize_key = $merchant_setting['authorize_key'];

	define("AUTHORIZENET_LOG_FILE", "phplog");
    $orderid    = substr(microtime(true), 0, 10);
    $ccnumber   = str_replace(' ', '', $fields['cc_number']);
    $cvv        = $fields['cc_ccv'];
    $expireMM   = $fields['expireMM'];
    $expireYY   = $fields['expireYY'];
    $ccexp      = sprintf('%s-%s', $expireYY, $expireMM);

    if($merchant['slug'] === 'authorize_net' || $merchant['slug'] === 'authorize_net_2'):
        // Common setup for API credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($authorize_id);
        $merchantAuthentication->setTransactionKey($authorize_key);
        $refId = 'ref' . time();
        // Customer info 
        $customer = new AnetAPI\CustomerDataType();
        $customer->setId($orderid);
        $customer->setEmail($user['email']);
        // Bill To
        $billto = new AnetAPI\CustomerAddressType();
        $billto->setFirstName($fields['payment_f_name']);
        $billto->setLastName($fields['payment_l_name']);
        $billto->setCompany($fields['company_account_name']);
        $billto->setAddress(sprintf('%s %s', $fields['address_1'], (isset($fields['address_2'])) ? $fields['address_2'] : ''));
        $billto->setCity($fields['city']);
        $billto->setState($fields['us_state']);
        $billto->setZip($fields['zip']);
        $billto->setCountry($user['country']);
        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($ccnumber);
        $creditCard->setExpirationDate($ccexp);
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);
        // Order info
        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber($orderid);
        $order->setDescription($membership);
        //create a transaction
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType( "authCaptureTransaction"); 
        $transactionRequestType->setOrder($order);
        $transactionRequestType->setAmount($amount);
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
                $payment_data = array(
                  'user_id' => $user_id,
                  'cc_fname' => $fields['payment_f_name'],  
                  'cc_lname' => $fields['payment_l_name'],
                  'response' => $tresponse->getResponseCode(),
                  'responsetext' => json_encode($tresponse->getMessages()),
                  'authcode' => (!empty($tresponse->getAuthCode())) ? $tresponse->getAuthCode() : 0,
                  'transactionid' => $tresponse->getTransId(),
                  'avsresponse' => $tresponse->getAvsResultCode(),
                  'cvvresponse' => $tresponse->getCvvResultCode(),
                  'orderid' => $orderid,
                  'type' => 'authCaptureTransaction',
                  'response_code' => $tresponse->getResponseCode(),
                  'amount' => $amount,
                  'payment_type' => 2,
                  'date_created' => date('Y-m-d H:i:s')
                );
                $payment_id = $payment_class->authorize_ipn($payment_data);
                $transaction_id = $payment_data['transactionid'];

                //Insert payment details to payments table
                $payment_data = array(
                    'user_id'           => $user_id,
                    'payment_method'    => $merchant['slug'],
                    'payment_type'      => 'creditcard',
                    'amount'            => $amount,
                    'date_created'      => date('Y-m-d H:i:s')
                );
                $payment_class->insert_payment($payment_data);
                $payment_error = 0;
            }
            else
            {
                //If the payment process encountered an error, save the transaction to authorize_ipn table then return error
                $payment_data = array(
                    'user_id' => $user_id,
                    'cc_fname' => $fields['payment_f_name'],
                    'cc_lname' => $fields['payment_l_name'],
                    'response' => $tresponse->getResponseCode(),
                    'responsetext' => json_encode($tresponse->getMessages()),
                    'authcode' => (!empty($tresponse->getAuthCode())) ? $tresponse->getAuthCode() : 0,
                    'transactionid' => $tresponse->getTransId(),
                    'avsresponse' => $tresponse->getAvsResultCode(),
                    'cvvresponse' => $tresponse->getCvvResultCode(),
                    'orderid' => $orderid,
                    'type' => 'authCaptureTransaction',
                    'response_code' => $tresponse->getResponseCode(),
                    'amount' => $amount,
                    'payment_type' => 2,
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
    else:
    	$result = array('result' => 'error', 'message' => sprintf('Payment method is not available right now. Please contact administrator.'));
		die(json_encode($result));
	endif;
elseif($payment_method === 'echeck'):
    $checknum   = $fields['checknum'];
    $routingnum = $fields['routingnum'];
    $accountnum = $fields['accountnum'];
    $mobile     = preg_replace('/[^A-Za-z0-9]/', '', $fields['phone']);

    $echeck_class = getInstance("Class_Echeck");
    $echeck_class->setLogin($echeck_username, $echeck_password);

    //Set Billing Details
    $echeck_class->setCustomer($fields['payment_f_name'], $fields['payment_l_name'], $fields['address_1'], $fields['address_2'], $fields['city'], $fields['us_state'], $fields['zip'], $mobile, $user['email']);
    //Set order details
    $echeck_class->setOrder($user_id, $membership, $amount);
    //Process order
    $payment_response = $echeck_class->doSale($checknum, $routingnum, $accountnum);

    if($payment_response->checkstatus == 'Accepted'):
        $payment_class = getInstance('Class_Payment');
        $data = array(
            'customername'      => $payment_response->customername,
            'customeraddress1'  => $payment_response->customeraddress1,
            'customeraddress2'  => $payment_response->customeraddress2,
            'customercity'      => $payment_response->customercity,
            'customerstate'     => $payment_response->customerstate,
            'customerzip'       => $payment_response->customerzip,
            'customerphone'     => $payment_response->customerphone,
            'customeremail'     => $payment_response->customeremail,
            'product'           => $payment_response->product,
            'amount'            => $payment_response->amount,
            'checkstatus'       => $payment_response->checkstatus,
            'statusmsg'         => $payment_response->statusmsg,
            'customerid'        => $user_id,
            'transactionid'     => $payment_response->transactionid,
            'payment_type'      => 2,
            'date_created'      => date('Y-m-d H:i:s')
        );
        $save_echeck_payment = $payment_class->echeck_ipn($data);
        $transaction_id = $data['transactionid'];

        //Insert payment details to payments table
        $payment_data = array(
            'user_id'           => $user_id,
            'payment_method'    => 'xpressdrafts',
            'payment_type'      => 'echeck',
            'amount'            => $payment_response->amount,
            'date_created'      => date('Y-m-d H:i:s')
        );
        $payment_class->insert_payment($payment_data);
        $payment_error = 0;
    else:
        //Process will go here if the edata payment did not succeed
        if(!empty($payment_response)):
            $error = json_decode($payment_response->statusmsg);
            if(is_object($error) || is_array($error)):
                $error = (array)$error->Invalid;
                foreach ($error as $key => $value) {
                    foreach ($value as $ekey => $evalue) {
                        $errormsg .= sprintf("INVALID %s\n", htmlentities($evalue));    
                    }
                }
            else:
                $errormsg = htmlentities($payment_response->statusmsg);
            endif;

            //Process will go here if the response of edata is correct
            //Insert edata details to db
            $data = array(
                'customername'      => $payment_response->customername,
                'customeraddress1'  => $payment_response->customeraddress1,
                'customeraddress2'  => $payment_response->customeraddress2,
                'customercity'      => $payment_response->customercity,
                'customerstate'     => $payment_response->customerstate,
                'customerzip'       => $payment_response->customerzip,
                'customerphone'     => $payment_response->customerphone,
                'customeremail'     => $payment_response->customeremail,
                'product'           => $payment_response->product,
                'amount'            => $payment_response->amount,
                'checkstatus'       => $payment_response->checkstatus,
                'statusmsg'         => $errormsg,
                'customerid'        => $payment_response->customerid,
                'transactionid'     => $payment_response->transactionid,
                'payment_type'      => 2,
                'date_created'      => date('Y-m-d H:i:s')
            );
            $save_echeck_payment = $payment_class->echeck_ipn($data);
            $result = array('result' => 'error', 'message' => sprintf('%s', $errormsg)); 
            die(json_encode($result));
        else:
            //Process will go here if the response of edata api is not correct
            $result = array('result' => 'error', 'message' => sprintf('%s', "There seems to be a problem with E-check processing. Please contact administrator."));
            die(json_encode($result));
        endif;
    endif;
endif;

if((int)$payment_error !== 1):
    $requested_date = date('Y-m-d H:i'); 
    $upgraded_date = '0000-00-00 00:00';
    $status = 0;

    if(2 < 5):
        //Automatically upgrade user
        $upgraded_date = date('Y-m-d H:i'); 
        $status = 1;
        $membership_class->upgrade_special_membership($user_id, 2);
        $email_class->upgrade_membership($user, 'Free', 'Professional');
    endif;

	$data = array(
		'user_id' => $user_id,
		'current_membership' 	=> 1,
		'upgrade_membership' 	=> 2,
		'requested_date' 		=> $requested_date,
		'upgraded_date' 		=> $upgraded_date,
        'payment_method'        => $payment_data['payment_method'],
        'transaction_id'        => $transaction_id,
		'status'				=> $status
	);
	$membership_class->insert_upgrade_membership($data);

    $msg = 'Your account has been successfully upgraded and we have sent you a reciept. Please check your junk or spam mail folder. Click <a href="/myhub">HERE</a> to return to your GLC Dashboard. Thank you.';
    if($status == 0) $msg = sprintf('Your account will be upgraded after we verify your payment. Thank you. Return to <a href="/myhub">GLC Hub</a>.');

	$result = array('result' => 'success', 'message' => $msg);
	die(json_encode($result));
endif;

$result = array('result' => 'error', 'message' => sprintf('There is a problem with the upgrade transaction. Please contact administrator.'));
die(json_encode($result));