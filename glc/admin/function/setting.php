<?php
// select from setting table

$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from setting ");
while($row = mysqli_fetch_array($query))
{
	//messages
	
	$welcome_message = $row['welcome_message'];
	$forget_password_message = $row['forget_password_message'];
	$payout_generate_message = $row['payout_generate_message'];
	$email_welcome_message = $row['email_welcome_message'];
	$direct_member_message = $row['direct_member_message'];
	$payment_request_message = $row['payment_request_message'];
	$payment_transfer_message = $row['payment_transfer_message'];
	$epin_generate_message = $row['epin_generate_message'];
	$user_pin_generate_message = $row['user_pin_generate_message'];
	$admin_alert_on_join_message = $row['member_to_member_message'];
	
	// income
	$setting_board_name[1] = $row['first_board_name'];
	$board_income[1][1] = $row['first_board_income_1'];
	$board_income[1][2] = $row['first_board_income_2'];
	$board_point[1] = $row['first_board_point'];
	
	$setting_board_name[2] = $row['second_board_name'];
	$board_income[2][1] = $row['second_board_income_1'];
	$board_income[2][2] = $row['second_board_income_2'];
	$board_point[2] = $row['second_board_point'];
	
	$setting_board_name[3] = $row['third_board_name'];
	$board_income[3][1] = $row['third_board_income_1'];
	$board_income[3][2] = $row['third_board_income_2'];
	$board_point[3] = $row['third_board_point'];
	
	$setting_board_name[4] = $row['fourth_board_name'];
	$board_income[4][1] = $row['fourth_board_income_1'];
	$board_income[4][2] = $row['fourth_board_income_2'];
	$board_point[4] = $row['fourth_board_point'];
	
	$setting_board_name[5] = $row['five_board_name'];
	$board_income[5][1] = $row['five_board_income_1'];
	$board_income[5][2] = $row['five_board_income_2'];
	$board_point[5] = $row['five_board_point'];
	
	$setting_board_name[6] = $row['six_board_name'];
	$board_income[6][1] = $row['six_board_income_1'];
	$board_income[6][2] = $row['six_board_income_2'];
	$board_point[6] = $row['six_board_point'];
	
	$setting_admin_tax = $row['admin_tax'];
	$setting_withdrawal_tax = $row['withdrawal_tax'];
	$setting_reg_voucher[1] = $row['reg_voucher_1'];
	$setting_reg_voucher[2] = $row['reg_voucher_2'];
	$setting_reg_voucher[3] = $row['reg_voucher_3'];
	$setting_reg_voucher[4] = $row['reg_voucher_4'];
	$setting_min_withdrawal = $row['min_withdrawal'];
	
	//user pin cost 	 	 	 	
	$epin_fees = $row['pin_cost'];
	$setting_direct_income = $row['direct_member_income'];  //direct member income
	$upgrade_membership_fees = $row['upgrade_membership_fees'];
	$setting_registration_fees = $row['registration_fees'];
}

$fees = 50;
$user_point_wallet = 1;

//product id for registration

$product_id[1] = "reg";
$product_id[2] = "upg";
//income type
$income_type[1] = 1; // Board Break income
$income_type[2] = 2;  // board breal point


//mail setting


$from = "noreply@ednetk.com";
$SmtpServer="mail.ednetk.com";
$SmtpPort="25"; //default
$SmtpUser="noreply@ednetk.com";
$SmtpPass="9829061228";

//income 
/*
$direct_income = 50;

$board_break_income[1] = 20;
$board_break_income[2] = 40;
$board_break_income[3] = 80;
$board_break_income[4] = 160;*/

/*
 * Define GLC_URL based on the current environment
 * Example value: http://globallearningcenter.com
 * Define siteUrl that can be used in javascript files
 */
if(!defined('GLC_URL')) define('GLC_URL', sprintf('%s://%s', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http', $_SERVER['HTTP_HOST']));
