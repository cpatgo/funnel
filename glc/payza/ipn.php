<?php
require_once(dirname(dirname(__FILE__)).'/config.php');
/**
 * 
 * Sample IPN V2 Handler for Item Payments
 * 
 * The purpose of this code is to help you to understand how to process the Instant Payment Notification 
 * variables for a payment received through Payza's buttons and integrate it in your PHP site. The following
 * code will ONLY handle ITEM payments. For handling IPNs for SUBSCRIPTIONS, please refer to the appropriate
 * sample code file.
 *	
 * Put this code into the page which you have specified as Alert URL.
 * The conditional blocks provide you the logical placeholders to process the IPN variables. It is your responsibility
 * to write appropriate code as per your requirements.
 *	
 * If you have any questions about this script or any suggestions, please visit us at: dev.payza.com
 * 
 *
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY
 * OF ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT
 * LIMITED TO THE IMPLIED WARRANTIES OF FITNESS FOR A PARTICULAR PURPOSE.
 * 
 * @author Payza
 * @copyright 2011
 */
	//Check if the host is staging, dev or live
	if($server_host === 'glcdev.saepiosecurity.com' || $server_host === 'glcdev.local' || $server_host === 'local.glcdev.com'):
		$payza_url = 'https://sandbox.Payza.com/sandbox/IPN2.ashx';
	else:
		$payza_url = 'https://secure.payza.com/ipn2.ashx';
	endif;

	//The value is the url address of IPN V2 handler and the identifier of the token string 
	define("IPN_V2_HANDLER", $payza_url);
	define("TOKEN_IDENTIFIER", "token=");
	
	// get the token from Payza
	$token = urlencode($_POST['token']);

	//preappend the identifier string "token=" 
	$token = TOKEN_IDENTIFIER.$token;
	
	/**
	 * 
	 * Sends the URL encoded TOKEN string to the Payza's IPN handler
	 * using cURL and retrieves the response.
	 * 
	 * variable $response holds the response string from the Payza's IPN V2.
	 */
	$response = '';
	
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, IPN_V2_HANDLER);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $token);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	$response = curl_exec($ch);

	curl_close($ch);
	
	if(strlen($response) > 0)
	{
		if(urldecode($response) == "INVALID TOKEN")
		{
			//the token is not valid
		}
		else
		{
			$payza_ipn = getInstance('Class_Payza');

			//urldecode the received response from Payza's IPN V2
			$response = urldecode($response);
			
			//split the response string by the delimeter "&"
			$aps = explode("&", $response);
			
			//define an array to put the IPN information
			$info = array();

			// $myFile = "IPNRes.txt";
			// $fh = fopen($myFile,'a') or die("can't open the file");
			// fwrite($fh, "\n");

			foreach ($aps as $ap)
			{
				//put the IPN information into an associative array $info
				$ele = explode("=", $ap);
				$info[$ele[0]] = $ele[1];

				// fwrite($fh, "$ele[0] =\t");
				// fwrite($fh, "$ele[1]\t");
			}
			$result = $payza_ipn->insert_payza_ipn($info);
			// fclose($fh);

			if(trim($info['ap_transactiontype']) == 'masspay' && (int)$info['ap_returncode'] == 100):
				$mpcustom = explode('-', $info['ap_mpcustom']);
                if (isset($mpcustom[0]) && $mpcustom[0] > 0 && trim($mpcustom[0]) !== "" )
                {
                    $payza_ipn->update_paid_unpaid_user($mpcustom[0]);
                }
			endif;

			if(trim($info['ap_transactiontype']) == 'purchase' && trim($info['ap_status']) == 'Success'):
				$user_arr = json_decode($info['apc_1']);
				$data = array(
					'user_id' 	=> $user_arr->glc_temp_user_id,
					'payza'		=> array('id' => $result['message'], 'apc_1' => $user_arr)
				);
				//If the payment status is successful, approve the user, remove in temporary table and place the user in board
				$user = getInstance('Class_User');
				$user->payza_approve_user($data);
			endif;
		}
	}
	else
	{
		//something is wrong, no response is received from Payza
	}
?>