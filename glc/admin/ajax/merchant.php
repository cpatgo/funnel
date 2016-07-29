<?php
// This file will perform ajax requests for Merchant
if(!isset($_POST)) printf('<script type="text/javascript">window.location="%s/glc/admin";</script>', GLC_URL);
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
$action = (isset($_POST['action'])) ? $_POST['action'] : '';
$merchant_class = getInstance('Class_Merchant');
$merchants = $merchant_class->get_all();

if($action === 'get_merchant_data'):
	$mid = $_POST['mid'];
	$payment_methods = $merchant_class->get_payment_methods($mid);
	$merchant_packages = $merchant_class->get_packages($mid);
	echo json_encode(array('type' => 'success', 'message' => array('methods' => $payment_methods, 'packages' => $merchant_packages)));
    die();
endif;

if($action === 'update_merchant_settings'):
	parse_str($_POST['fields'], $fields);
	
	//Update merchant environment
	glc_update_option('default_merchant_environment', $fields['environment']);

	//Update merchant status
	foreach ($fields['enabledisable'] as $key => $value) {
		$merchant_class->update_merchant_status($key, $value[0]);
	}

	//Update merchant payment methods
	foreach ($fields['merchant_payment_methods'] as $key => $value) {
		$merchant_class->update_selected_methods($key, $value);
	}

	//Update merchant packages
	foreach ($fields['merchant_packages'] as $key => $value) {
		$merchant_class->update_selected_packages($key, $value);
	}

	echo json_encode(array('type' => 'success', 'message' => 'Successfully updated merchant settings.'));
	die();
endif;