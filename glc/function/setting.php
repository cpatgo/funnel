<?php
// select from setting table
include 'account_maintain.php';
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
	
	$setting_admin_tax = $row['admin_tax'];
	$setting_withdrawal_tax = $row['withdrawal_tax'];
	/*$setting_reg_voucher[1] = $row['reg_voucher_1'];
	$setting_reg_voucher[2] = $row['reg_voucher_2'];
	$setting_reg_voucher[3] = $row['reg_voucher_3'];
	$setting_reg_voucher[4] = $row['reg_voucher_4'];
	$setting_min_withdrawal = $row['min_withdrawal'];*/
	
	//user pin cost 	 	 	 	
	$epin_fees = $row['pin_cost'];
	$setting_direct_income = $row['direct_member_income'];  //direct member income
	$upgrade_membership_fees = $row['upgrade_membership_fees'];
	$setting_registration_fees = $row['registration_fees'];
}

//Income
$setting_board_name[1] = glc_option('first_board_name');
$board_income[1][1] = glc_option('first_board_income_1');
$board_income[1][2] = glc_option('first_board_income_2');
$board_point[1] = glc_option('first_board_point');
$board_reenter[1] = glc_option('first_reenter');
$board_cocomm[1] = glc_option('first_cocomm');
$board_cocomm_cylcle1[1] = glc_option('first_cocomm_cycle1');
$board_join[1] = glc_option('first_board_join');

$setting_board_name[2] = glc_option('second_board_name');
$board_income[2][1] = glc_option('second_board_income_1');
$board_income[2][2] = glc_option('second_board_income_2');
$board_point[2] = glc_option('second_board_point');
$board_reenter[2] = glc_option('second_reenter');
$board_cocomm[2] = glc_option('second_cocomm');
$board_cocomm_cylcle1[2] = glc_option('second_cocomm_cycle1');
$board_join[2] = glc_option('second_board_join');

$setting_board_name[3] = glc_option('third_board_name');
$board_income[3][1] = glc_option('third_board_income_1');
$board_income[3][2] = glc_option('third_board_income_2');
$board_point[3] = glc_option('third_board_point');
$board_reenter[3] = glc_option('third_reenter');
$board_cocomm[3] = glc_option('third_cocomm');
$board_cocomm_cylcle1[3] = glc_option('third_cocomm_cycle1');
$board_join[3] = glc_option('third_board_join');

$setting_board_name[4] = glc_option('fourth_board_name');
$board_income[4][1] = glc_option('fourth_board_income_1');
$board_income[4][2] = glc_option('fourth_board_income_2');
$board_point[4] = glc_option('fourth_board_point');
$board_reenter[4] = glc_option('fourth_reenter');
$board_cocomm[4] = glc_option('fourth_cocomm');
$board_cocomm_cylcle1[4] = glc_option('fourth_cocomm_cycle1');
$board_join[4] = glc_option('fourth_board_join');

$setting_board_name[5] = glc_option('five_board_name');
$board_income[5][1] = glc_option('five_board_income_1');
$board_income[5][2] = glc_option('five_board_income_2');
$board_point[5] = glc_option('five_board_point');
$board_reenter[5] = glc_option('five_reenter');
$board_cocomm[5] = glc_option('five_cocomm');
$board_cocomm_cylcle1[5] = glc_option('five_cocomm_cycle1');
$board_join[5] = glc_option('five_board_join');

$fees = 50;
$user_point_wallet = 1;

//product id for registration

$product_id[1] = "reg";
$product_id[2] = "upg";
//income type
$income_type[1] = 1; // Board Break income
$income_type[2] = 2;  // board breal point
$income_type[3] = 3; // Board Break partial income


//mail setting
/*
$from = "no-reply@cardgenius.com";
$SmtpServer="smtpout.secureserver.net";
$SmtpPort="80"; //default
$SmtpUser="no-reply@cardgenius.com";
$SmtpPass="999552";
*/

$from = "info@saepiosecurity.com";
$SmtpServer="usm1.siteground.biz";
$SmtpPort="587"; //default
$SmtpUser="info@saepiosecurity.com";
$SmtpPass="uF~Xxr8%e86%";

//income 

$direct_income = 50;

$board_break_income[1] = 20;
$board_break_income[2] = 40;
$board_break_income[3] = 80;
$board_break_income[4] = 160;

/*
 * Define GLC_URL based on the current environment
 * Example value: http://globallearningcenter.com
 * Define siteUrl that can be used in javascript files
 */
if(!defined('GLC_URL')) define('GLC_URL', sprintf('%s://%s', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http', $_SERVER['HTTP_HOST']));
