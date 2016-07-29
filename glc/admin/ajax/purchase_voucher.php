<?php
session_start();
// This file will perform ajax requests for voucher purchase
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(dirname(dirname(__FILE__))).'/function/functions.php');

if(!isset($_POST)) printf('<script type="text/javascript">window.location="%s/glc/admin";</script>', GLC_URL);

parse_str($_POST['fields'], $fields);

if(!array_key_exists('select_voucher', $fields) || empty($fields['select_voucher'])) die(json_encode(array('type' => 'error', 'message' => 'Please select the voucher you want to purchase.')));
if(array_sum($fields['voucher_count']) < 1) die(json_encode(array('type' => 'error', 'message' => 'Please enter the number of voucher you want to purchase.')));

if(!empty($fields['select_voucher'])):
	$purchase_class = getInstance('Class_Purchase');
	$id = $_SESSION['dennisn_user_id'];
	$available_funds = get_available_funds($id);

	foreach ($fields['payment_method'] as $key => $value) {
		//Check if available funds is enough
		if($value == 'commission' && $fields['partial_amount'][$key] > $available_funds) die(json_encode(array('type' => 'error', 'message' => 'The amount you entered in commission is greated than your available fund.')));
	}

	$purchase_data = array(
		'user_id' => $id,
		'total' => array_sum($fields['partial_amount']),
		'date_created' => date('Y-m-d H:i:s'),
		'date_approved' => '0000-00-00 00:00:00'
	);
	$purchase_id = $purchase_class->insert_purchase($purchase_data);

	if((int)$purchase_id['type'] == 1):
		$purchase_id = $purchase_id['message'];
		//Insert purchase details	
		foreach ($fields['payment_method'] as $key => $value) {
			if($fields['partial_amount'][$key] < 1) continue;
			$status = ($value == 'commission') ? 1 : 0;
			$purchase_details = array(
				'purchase_id' => $purchase_id,
				'payment_method' => $key,
				'amount' => $fields['partial_amount'][$key],
				'status' => $status
			);	
			$purchase_class->insert_purchase_details($purchase_details);
		}

		//Get and insert purchased vouchers
		foreach ($fields['select_voucher'] as $key => $value) {
			if($fields['voucher_count'][$key] < 1) continue;
			$purchase_class->buy_vouchers($purchase_id, $key, $fields['voucher_count'][$key], $id);
		}

		die(json_encode(array('type' => 'success', 'message' => 'Your purchase has been submitted.')));
	endif;
endif;