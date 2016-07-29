<?php 
if(!isset($_POST)) return false;
require_once(dirname(dirname(__FILE__)) . '/config.php');

$payment_class = getInstance('Class_Payment');

$data = array(
	'customername' 		=> $_POST['customername'],
	'customeraddres1' 	=> $_POST['customeraddres1'],
	'customeraddres2' 	=> $_POST['customeraddres2'],
	'customercity' 		=> $_POST['customercity'],
	'customerstate' 	=> $_POST['customerstate'],
	'customerzip' 		=> $_POST['customerzip'],
	'customerphone' 	=> $_POST['customerphone'],
	'customeremail' 	=> $_POST['customeremail'],
	'product' 			=> $_POST['product'],
	'amount' 			=> $_POST['amount'],
	'checkstatus' 		=> $_POST['checkstatus'],
	'statusmsg' 		=> $_POST['statusmsg'],
	'customerid' 		=> $_POST['customerid'],
	'transactionid' 	=> $_POST['transactionid']
);
$save_echeck_payment = $payment_class->echeck_ipn($data);
?>