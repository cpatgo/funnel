<?php

/*
error messages:
API_DISABLED_FOR_ORG: API is not enabled for this Organization or Partner
INVALID_LOGIN: Invalid username, password, security token; or user locked out.
*/

function adesk_salesforce_connect($post) {
	require_once(awebdesk_classes('salesforce/soapclient/SforcePartnerClient.php'));
	$connection = new SforcePartnerClient();
	$client = $connection->createConnection( awebdesk_classes('salesforce/soapclient/partner.wsdl.xml') );
	try {
		$login = $connection->login($post['salesforce_username'], $post['salesforce_password'] . $post['salesforce_token']);
		if ($login->passwordExpired) {
			return array( 'message' => _a('Your Salesforce password has expired. Please login to Salesforce and update it.') );
		}
	} catch ( Exception $e ) {
		$msg = $e->getMessage();
		if ( adesk_str_instr('API_DISABLED_FOR_ORG', $msg) ) {
			$msg = _a("API is not enabled for this SalesForce account. Please login into SalesForce to turn it on.");
		} elseif ( adesk_str_instr('INVALID_LOGIN', $msg) ) {
			$msg = _a("Please check your login info: username, password and token. One of those is not correct.");
		}
		return array('message'=> $msg);
	}
	return $connection;
}

?>