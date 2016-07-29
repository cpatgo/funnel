<?php
if(!empty($username)):

	$merchant_class = getInstance('Class_Merchant');
	$user_class = getInstance("Class_User");
	$payment_class = getInstance("Class_Payment");

	$payment_error = 1;
	$orderid   	= substr(microtime(true), 0, 10);
	$ccnumber	= post_var('cc_number');
	$cvv		= post_var('cc_ccv');
	$expireMM 	= post_var('expireMM');
	$expireYY 	= post_var('expireYY');
	$ccexp		= sprintf('%s%s',  $expireMM, date('y', strtotime(sprintf('%s-01-01', $expireYY))));

	$membership_details = $user_class->get_membership_by_membership($membership);
	$membership_details = $membership_details[0];

	$edata_class = getInstance("Class_Edata");
	$edata_class->setLogin($edata_username, $edata_password);

	//Set Billing Details
	$edata_class->setBilling($payment_fname, $payment_lname, $company, $address1, $address2, $city, $state, $zip, $country, $phone, $fax, $email, $website);
	//Set Shipping Details
	$edata_class->setShipping($payment_fname, $payment_lname, $company, $address1, $address2, $city, $state, $zip, $country, $email);
	//Set order details
	$edata_class->setOrder($orderid, $membership, 0, 0, $orderid, $_SERVER['SERVER_ADDR']);

	if($pay_type === 'creditcard'):
		//Credit card process
		$payment_response = $edata_class->doSale($membership_details['amount'], $ccnumber, $ccexp, $cvv);

	elseif($pay_type === 'echeck'):
		//Echeck process
	endif;

	if((int)$payment_response['response'] === 1 && (int)$payment_response['response_code'] === 100):
		$payment_error = 0;
	else:
		//Process will go here if the edata payment did not succeed
		if(!empty($payment_response) && is_array($payment_response)):
			//Process will go here if the response of edata is correct
			//Insert edata details to db
			$payment_data = array(
				'user_id' => (!empty($user_id)) ? $user_id : 0,
                'cc_fname' => $payment_fname,
                'cc_lname' => $payment_lname,
				'response' => $payment_response['response'],
				'responsetext' => $payment_response['responsetext'],
				'authcode' => (!empty($payment_response['authcode'])) ? $payment_response['authcode'] : 0,
				'transactionid' => $payment_response['transactionid'],
				'avsresponse' => $payment_response['avsresponse'],
				'cvvresponse' => $payment_response['cvvresponse'],
				'orderid' => $payment_response['orderid'],
				'type' => $payment_response['type'],
				'response_code' => $payment_response['response_code'],
				'date_created' => date('Y-m-d H:i:s')
			);
			$user_id = $payment_class->edata_ipn($payment_data);
			$result = array('result' => 'error', 'message' => sprintf('%s', $payment_response['responsetext'])); 
			die(json_encode($result));
		else:
			//Process will go here if the response of edata api is not correct
			$result = array('result' => 'error', 'message' => sprintf('%s', "There seems to be a problem with E-data processing. Your credit card was not charged. Please contact administrator."));
			die(json_encode($result));
		endif;
	endif;
endif;