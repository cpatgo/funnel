<?php
// This file will perform ajax requests for Payza
if(!isset($_POST)) printf('<script type="text/javascript">window.location="%s/glc/admin";</script>', GLC_URL);
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
$action = (isset($_POST['action'])) ? $_POST['action'] : '';

// Ajax request to show batch transactions of payza 
if($action === 'show_batch_transactions'):
	$batch_no = $_POST['batch_no'];
	$payza = getInstance('Class_Payza');
	$transactions = $payza->get_batch_transaction($batch_no);
	echo json_encode($transactions);
    die();
endif;

if($action === 'submit_mass_payment'):
	parse_str($_POST['fields'], $fields);
	$payza_email = $fields['payza_admin_account'];
	$payza_password = $fields['payza_admin_password'];
	$pay_ids = $fields['ids'];

	$payza = getInstance('Class_Payza');
	$masspay = getInstance('Payza_Masspay');
	$masspay->setUsername($payza_email);
	$masspay->setPassword($payza_password);

	$data = array(); $total_amount = 0;
	foreach ($pay_ids as $key => $value) {
		$user = $payza->get_user_via_paid_unpaid_id($value); 
		$user = $user[0];
		$data[] = array('receiver' => $user['payza_account'], 'amount' => $user['amount'], 'note' => 'Commission', 'mpcustom' => sprintf("%s-%s", $value, $user['id_user']));
		$total_amount += $user['amount'];
	}

	// CHECK PAYZA BALANCE
	$masspay->checkPayzaBalance();
	$check_balance = $masspay->send();
	parse_str($check_balance, $balance);

	if((int)$balance['RETURNCODE'] !== 100) die(json_encode($balance));
	// Return error if fund is not enough
	if((int)$balance['AVAILABLEBALANCE_1'] < $total_amount) die(json_encode(array('RETURNCODE' => 999, 'DESCRIPTION' => sprintf("Payza balance is not enough to complete the mass payment.\nCurrent Balance: %s", number_format($balance['AVAILABLEBALANCE_1'], 2)))));
	// END OF CHECK PAYZA BALANCE

	$masspay->buildPostVariables($data);
	$response = $masspay->send();

	parse_str($response, $response);
	echo json_encode($response);
	die();
endif;