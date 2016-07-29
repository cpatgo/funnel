<?php
/**
 * 
 * Sample IPN Handler for MassPay payments
 * 
 * The purpose of this code is to help you to understand how to process the Instant Payment Notification 
 * variables for a MassPay payment and integrate it in your PHP site. The following
 * code will ONLY handle MASSPAY payments. For handling IPNs for ITEMS or SUBSCRIPTION, please refer to the appropriate
 * sample code files.
 *	
 * Put this code into the page which you have specified as Alert URL.
 * The variables being read from the $_POST object in the code below are pre-defined IPN variables and the
 * the conditional blocks provide you the logical placeholders to process the IPN variables. It is your responsibility
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
 * @copyright 2010
 */


	//The value is the Security Code generated from the IPN section of your Payza account. Please change it to yours.
	define("IPN_SECURITY_CODE", "xxxxxxxxxxxxxxxx");
	define("MY_MERCHANT_EMAIL", "name@example.com");

	//Setting information about the transaction
	$receivedSecurityCode = $_POST['ap_securitycode'];
	$senderEmailAddress = $_POST['ap_merchant'];
    $receiverEmailAddress = $_POST['ap_receiveremail'];
	$testModeStatus = $_POST['ap_test'];	 
	$transactionReferenceNumber = $_POST['ap_referencenumber'];
	$currency = $_POST['ap_currency']; 		
	$paymentAmount = $_POST['ap_amount'];
    $transactionType = $_POST['ap_transactiontype'];	
	$transactionDate= $_POST['ap_transactiondate'];
	$myCustomField= $_POST['ap_mpcustom'];
	
	//Setting the information about the MassPay from the IPN post variables
    $batchNumber = $_POST['ap_batchnumber'];
	$apiReturnCode = $_POST['ap_returncode'];
	$apiReturnCodeDescription = $_POST['ap_returncodedescription'];


	if ($receivedMerchantEmailAddress != MY_MERCHANT_EMAIL) {
		// The data was not meant for the business profile under this email address.
		// Take appropriate action 
	}
	else {	
		//Check if the security code matches
		if ($receivedSecurityCode != IPN_SECURITY_CODE) {
  		    // The data is NOT sent by Payza.
			// Take appropriate action.
		}
		else {
            if ($transactionType == "masspay") {
				if ($apiReturnCode == "100") {
					if ($testModeStatus == "1") {
						// Since Test Mode is ON, no transaction reference number will be returned.
						// Your site is currently being integrated with Payza IPN for TESTING PURPOSES
						// ONLY. Don't store any information in your production database and 
						// DON'T process this transaction as a real MassPay payment.				
					}
					else {
						// This REAL transaction is complete and the amount was paid successfully to the recipient.
						// Check that there is a TRANSACTION REFERENCE NUMBER that was returned for this payment.
					}
				}
				else {
					// The transaction did not complete. 
                    // Check the return code and its description for more information.
				}
			}
			else {
				// The transaction type is not "masspay", take appropriate action.
			}
		}
	}
?>