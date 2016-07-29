<?php
require_once("config.php");
include("function/functions.php");	
$epin = $_REQUEST['epin'];
$membership = (isset($_REQUEST['membership']) && !empty($_REQUEST['membership'])) ? trim(strtolower($_REQUEST['membership'])) : "";

$choosen_membership = array(
	'executive' => 1, 
	'leadership' => 2,
	'professional' => 3,
	'masters' => 4
);

// If membership is not in the list, return error.
if(!array_key_exists($membership, $choosen_membership)) die(json_encode(array('Invalid Membership')));

// Search the database for epin
$query = mysqli_query($GLOBALS["___mysqli_ston"], sprintf("select * from e_voucher where voucher = '%s' and mode = 1", $epin));
$epin_exist = mysqli_num_rows($query);

// If no epin is found in the database, return error.
if($epin_exist == 0) die(json_encode(array('Incorrect Epin')));

$row = mysqli_fetch_array($query);
$voucher_membership_id = $row['voucher_type'];

if($choosen_membership[$membership] == $voucher_membership_id):
	// If chosen membership is the same with the membership set for epin, return true
	echo false;
	exit;
else:
	// If chosen membership is not the same with the membership, notify the user that he will be assigned to the membership set to the epin.
	$choosen_membership = array_flip($choosen_membership);
	echo ucfirst($choosen_membership[$voucher_membership_id]);
	exit;
//	$choosen_membership = array_flip($choosen_membership);
//	die(json_encode(array(sprintf('e-Voucher is for %1$s membership.', ucfirst($choosen_membership[$voucher_membership_id])))));
endif;
?>