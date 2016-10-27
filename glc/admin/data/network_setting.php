<?php
session_start();

include("condition.php");

include("../function/setting.php");

if(isset($_POST['submit']))
{
	//Update setting in options table
	glc_update_option('first_board_name', $_POST['board_name_1']);
	glc_update_option('first_board_income_1', $_POST['board_income_first_1']);
	glc_update_option('first_board_income_2', $_POST['board_income_second_1']);
	glc_update_option('first_board_point', $_POST['board_point_1']);
	glc_update_option('first_board_join', $_POST['board_join_1']);
	glc_update_option('first_reenter', $_POST['board_reenter_1']);
	glc_update_option('first_cocomm', $_POST['board_cocomm_1']);
	glc_update_option('first_cocomm_cycle1', $_POST['board_cocomm_cylcle1_1']);


	glc_update_option('second_board_name', $_POST['board_name_2']);
	glc_update_option('second_board_income_1', $_POST['board_income_first_2']);
	glc_update_option('second_board_income_2', $_POST['board_income_second_2']);
	glc_update_option('second_board_point', $_POST['board_point_2']);
	glc_update_option('second_board_join', $_POST['board_join_2']);
	glc_update_option('second_reenter', $_POST['board_reenter_2']);
	glc_update_option('second_cocomm', $_POST['board_cocomm_2']);
	glc_update_option('second_cocomm_cycle1', $_POST['board_cocomm_cylcle1_2']);


	glc_update_option('third_board_name', $_POST['board_name_3']);
	glc_update_option('third_board_income_1', $_POST['board_income_first_3']);
	glc_update_option('third_board_income_2', $_POST['board_income_second_3']);
	glc_update_option('third_board_point', $_POST['board_point_3']);
	glc_update_option('third_board_join', $_POST['board_join_3']);
	glc_update_option('third_reenter', $_POST['board_reenter_3']);
	glc_update_option('third_cocomm', $_POST['board_cocomm_3']);
	glc_update_option('third_cocomm_cycle1', $_POST['board_cocomm_cylcle1_3']);


	glc_update_option('fourth_board_name', $_POST['board_name_4']);
	glc_update_option('fourth_board_income_1', $_POST['board_income_first_4']);
	glc_update_option('fourth_board_income_2', $_POST['board_income_second_4']);
	glc_update_option('fourth_board_point', $_POST['board_point_4']);
	glc_update_option('fourth_board_join', $_POST['board_join_4']);
	glc_update_option('fourth_reenter', $_POST['board_reenter_4']);
	glc_update_option('fourth_cocomm', $_POST['board_cocomm_4']);
	glc_update_option('fourth_cocomm_cycle1', $_POST['board_cocomm_cylcle1_4']);


	glc_update_option('five_board_name', $_POST['board_name_5']);
	glc_update_option('five_board_income_1', $_POST['board_income_first_5']);
	glc_update_option('five_board_income_2', $_POST['board_income_second_5']);
	glc_update_option('five_board_point', $_POST['board_point_5']);
	glc_update_option('five_board_join', $_POST['board_join_5']);
	glc_update_option('five_reenter', $_POST['board_reenter_5']);
	glc_update_option('five_cocomm', $_POST['board_cocomm_5']);
	glc_update_option('five_cocomm_cycle1', $_POST['board_cocomm_cylcle1_5']);

	glc_update_option('second_step_income_1', $_POST['second_step_income_1']);
	glc_update_option('second_step_income_2', $_POST['second_step_income_2']);
	glc_update_option('second_step_income_3', $_POST['second_step_income_3']);
	glc_update_option('second_step_income_4', $_POST['second_step_income_4']);
	glc_update_option('second_step_income_5', $_POST['second_step_income_5']);

	glc_update_option('third_step_income_1', $_POST['third_step_income_1']);
	glc_update_option('third_step_income_2', $_POST['third_step_income_2']);
	glc_update_option('third_step_income_3', $_POST['third_step_income_3']);
	glc_update_option('third_step_income_4', $_POST['third_step_income_4']);
	glc_update_option('third_step_income_5', $_POST['third_step_income_5']);

	glc_update_option('reserve_percentage', $_POST['reserve_percentage']);
	glc_update_option('reserve_month', $_POST['reserve_month']);

	glc_update_option('aem_free_id', $_POST['aem_free_id']);
	glc_update_option('aem_executive_id', $_POST['aem_executive_id']);
	glc_update_option('aem_leadership_id', $_POST['aem_leadership_id']);
	glc_update_option('aem_professional_id', $_POST['aem_professional_id']);	
	glc_update_option('aem_masters_id', $_POST['aem_masters_id']);	
	glc_update_option('aem_founder_id', $_POST['aem_founder_id']);	

	glc_update_option('aem_special_registration', $_POST['aem_special_registration']);
	glc_update_option('aem_special_wp_membership', $_POST['aem_special_wp_membership']);
	glc_update_option('aem_special_matrix_membership', $_POST['aem_special_matrix_membership']);

	glc_update_option('glc_subscription_registration', $_POST['glc_subscription_registration']);
	glc_update_option('glc_subscription_wp_membership', $_POST['glc_subscription_wp_membership']);
	glc_update_option('glc_subscription_matrix_membership', $_POST['glc_subscription_matrix_membership']);

	glc_update_option('sitebuilder_domain', $_POST['sitebuilder_domain']);

	//Update setting in setting table
	$direct_member_income = $_REQUEST['direct_member_income'];
	$pin_cost = $_REQUEST['pin_cost'];
	$admin_tax = $_REQUEST['admin_tax'];
	$withdrawal_tax = $_REQUEST['withdrawal_tax'];
	$min_withdrawal = $_REQUEST['min_withdrawal'];

	$min_q_referrals 	= $_REQUEST['min_q_referrals'];
	$min_free_referrals = $_REQUEST['min_free_referrals'];
	$q_time 			= $_REQUEST['q_time'];

	 for($i = 1; $i < 7; $i++)
	 {
	 	$board_name[$i] = $_POST['board_name_'.$i];
	 	$board_income[$i][1] = $_POST['board_income_first_'.$i];
	 	$board_income[$i][2] = $_POST['board_income_second_'.$i];
	 	$board_point[$i] = $_POST['board_point_'.$i];
		$board_join[$i] = $_POST['board_join_'.$i];
	 }

	 //Update setting table
	 mysqli_query($GLOBALS["___mysqli_ston"], "update setting set min_q_referrals = '".$min_q_referrals."',min_free_referrals = '".$min_free_referrals."',q_time = '".$q_time."',first_board_name = '".$board_name[1]."' , first_board_income_1 = '".$board_income[1][1]."' , first_board_income_2 = '".$board_income[1][2]."' , first_board_point = '".$board_point[1]."' , second_board_name = '".$board_name[2]."' , second_board_income_1 = '".$board_income[2][1]."' , second_board_income_2 = '".$board_income[2][2]."' , second_board_point = '".$board_point[2]."' , third_board_name = '".$board_name[3]."' , third_board_income_1 = '".$board_income[3][1]."' , third_board_income_2 = '".$board_income[3][2]."' , third_board_point = '".$board_point[3]."' , fourth_board_name = '".$board_name[4]."' , fourth_board_income_1 = '".$board_income[4][1]."' , fourth_board_income_2 = '".$board_income[4][2]."' , fourth_board_point = '".$board_point[4]."' , five_board_name = '".$board_name[5]."' , five_board_income_1 = '".$board_income[5][1]."' , five_board_income_2 = '".$board_income[5][2]."' , five_board_point = '".$board_point[5]."' , six_board_name = '".$board_name[6]."' , six_board_income_1 = '".$board_income[6][1]."' , six_board_income_2 = '".$board_income[6][2]."' , six_board_point = '".$board_point[6]."' , direct_member_income = '$direct_member_income' , pin_cost = '$pin_cost' , admin_tax = '$admin_tax' , withdrawal_tax = '$withdrawal_tax' , min_withdrawal = '$min_withdrawal', first_board_join='".$board_join[1]."', second_board_join='".$board_join[2]."', third_board_join='".$board_join[3]."', fourth_board_join='".$board_join[4]."', five_board_join='".$board_join[5]."' ");

	 //Update memberships table
	 mysqli_query($GLOBALS["___mysqli_ston"], sprintf("UPDATE memberships set amount = %d WHERE membership = 'Executive'", $_POST['board_join_1']));
	 mysqli_query($GLOBALS["___mysqli_ston"], sprintf("UPDATE memberships set amount = %d WHERE membership = 'Leadership'", $_POST['board_join_2']));
	 mysqli_query($GLOBALS["___mysqli_ston"], sprintf("UPDATE memberships set amount = %d WHERE membership = 'Professional'", $_POST['board_join_3']));
	 mysqli_query($GLOBALS["___mysqli_ston"], sprintf("UPDATE memberships set amount = %d WHERE membership = 'Masters'", $_POST['board_join_4']));

	$date = date('Y-m-d');
	include("../function/logs_messages.php");
	data_logs($id,$data_log[14][0],$data_log[14][1],$log_type[14]);

	$p = 1;
}

$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from setting ");
while($row = mysqli_fetch_array($query))
{
	$min_q_referrals 	= $row['min_q_referrals'];
	$min_free_referrals = $row['min_free_referrals'];
	$q_time 			= $row['q_time'];

	$direct_member_income = $row['direct_member_income'];
	$pin_cost = $row['pin_cost'];
	$admin_tax = $row['admin_tax'];
	$withdrawal_tax = $row['withdrawal_tax'];
}

//Get setting options table
$board_name[1] = glc_option('first_board_name');
$board_income[1][1] = glc_option('first_board_income_1');
$board_income[1][2] = glc_option('first_board_income_2');
$board_point[1] = glc_option('first_board_point');
$board_join_cost[1] = glc_option('first_board_join');
$board_reenter[1] = glc_option('first_reenter');
$board_cocomm[1] = glc_option('first_cocomm');
$board_cocomm_cylcle1[1] = glc_option('first_cocomm_cycle1');

$board_name[2] = glc_option('second_board_name');
$board_income[2][1] = glc_option('second_board_income_1');
$board_income[2][2] = glc_option('second_board_income_2');
$board_point[2] = glc_option('second_board_point');
$board_join_cost[2] = glc_option('second_board_join');
$board_reenter[2] = glc_option('second_reenter');
$board_cocomm[2] = glc_option('second_cocomm');
$board_cocomm_cylcle1[2] = glc_option('second_cocomm_cycle1');

$board_name[3] = glc_option('third_board_name');
$board_income[3][1] = glc_option('third_board_income_1');
$board_income[3][2] = glc_option('third_board_income_2');
$board_point[3] = glc_option('third_board_point');
$board_join_cost[3] = glc_option('third_board_join');
$board_reenter[3] = glc_option('third_reenter');
$board_cocomm[3] = glc_option('third_cocomm');
$board_cocomm_cylcle1[3] = glc_option('third_cocomm_cycle1');

$board_name[4] = glc_option('fourth_board_name');
$board_income[4][1] = glc_option('fourth_board_income_1');
$board_income[4][2] = glc_option('fourth_board_income_2');
$board_point[4] = glc_option('fourth_board_point');
$board_join_cost[4] = glc_option('fourth_board_join');
$board_reenter[4] = glc_option('fourth_reenter');
$board_cocomm[4] = glc_option('fourth_cocomm');
$board_cocomm_cylcle1[4] = glc_option('fourth_cocomm_cycle1');

$board_name[5] = glc_option('five_board_name');
$board_income[5][1] = glc_option('five_board_income_1');
$board_income[5][2] = glc_option('five_board_income_2');
$board_point[5] = glc_option('five_board_point');
$board_join_cost[5] = glc_option('five_board_join');
$board_reenter[5] = glc_option('five_reenter');
$board_cocomm[5] = glc_option('five_cocomm');
$board_cocomm_cylcle1[5] = glc_option('five_cocomm_cycle1');

$second_step_income[1] = glc_option('second_step_income_1');
$second_step_income[2] = glc_option('second_step_income_2');
$second_step_income[3] = glc_option('second_step_income_3');
$second_step_income[4] = glc_option('second_step_income_4');
$second_step_income[5] = glc_option('second_step_income_5');

$third_step_income[1] = glc_option('third_step_income_1');
$third_step_income[2] = glc_option('third_step_income_2');
$third_step_income[3] = glc_option('third_step_income_3');
$third_step_income[4] = glc_option('third_step_income_4');
$third_step_income[5] = glc_option('third_step_income_5');

$reserve_percentage = glc_option('reserve_percentage');
$reserve_month = glc_option('reserve_month');

$aem_free_id = glc_option('aem_free_id');
$aem_executive_id = glc_option('aem_executive_id');
$aem_leadership_id = glc_option('aem_leadership_id');
$aem_professional_id = glc_option('aem_professional_id');
$aem_masters_id = glc_option('aem_masters_id');
$aem_founder_id = glc_option('aem_founder_id');

$aem_special_registration = glc_option('aem_special_registration');
$aem_special_wp_membership = glc_option('aem_special_wp_membership');
$aem_special_matrix_membership = glc_option('aem_special_matrix_membership');

$glc_subscription_registration = glc_option('glc_subscription_registration');
$glc_subscription_wp_membership = glc_option('glc_subscription_wp_membership');
$glc_subscription_matrix_membership = glc_option('glc_subscription_matrix_membership');

$sitebuilder_domain = glc_option('sitebuilder_domain');

?>
<div class="ibox-content">
<form name="setting" method="post" action="index.php?page=network_setting">
<table class="table table-bordered" id="network_setting_table">
	<tr><td colspan="9"><?php if($p == 1) { print "Updating completed Successfully"; } ?></td></tr>
	<thead>
	<tr><th colspan="9">Board Break Income</th></tr>
	<tr>
		<th>Board Name</th>
		<th>First Pass</th>
		<th width="10">Step 2 Income</th>
		<th width="10">Step 3 Income</th>
		<th width="10">Income (Whole amount)</th>
		<th>Recycle Cost</th>
		<th>Actual Stage Amount</th>
		<th>New Member Stage Amount</th>
		<th>Membership Cost</th>
	</tr>
	</thead>
	<tbody>
 <?php
 for($i = 1; $i < 7; $i++)
 {
?>
	<tr>
		<td><input type="text" name="board_name_<?=$i;?>" value="<?=$board_name[$i];?>" /></td>
		<td>
			<input type="text" name="board_income_first_<?=$i;?>" value="<?=$board_income[$i][1];?>" />
		</td>
		<td>
			<input type="text" name="second_step_income_<?=$i;?>" value="<?=$second_step_income[$i]; ?>" />
		</td>
		<td>
			<input type="text" name="third_step_income_<?=$i;?>" value="<?=$third_step_income[$i]; ?>" />
		</td>
		<td>
			<input type="text" name="board_income_second_<?=$i;?>" value="<?=$board_income[$i][2]; ?>" />
		</td>
		
		<!--<td><input type="text" name="board_point_<?=$i;?>" value="<?=$board_point[$i];?>" /></td>-->
		<td><input type="text" name="board_reenter_<?=$i;?>" value="<?=$board_reenter[$i];?>" /></td>
		<td><input type="text" name="board_cocomm_<?=$i;?>" value="<?=$board_cocomm[$i];?>" /></td>
		<td><input type="text" name="board_cocomm_cylcle1_<?=$i;?>" value="<?=$board_cocomm_cylcle1[$i];?>" /></td>
		<td><input type="text" name="board_join_<?=$i;?>" value="<?=$board_join_cost[$i];?>" /></td>
	</tr>
 <?php
 }
 ?>
	<tr><td colspan="9">&nbsp;</td></tr>
	<tr>
		<td colspan="4"><B>Rolling Reserve Percentage</B></td>
		<td colspan="7">
			<input type="text" name="reserve_percentage" value="<?=$reserve_percentage;?>" />%
		</td>
	</tr>
	<tr>
		<td colspan="4"><B>Number of months for rolling reserve</B></td>
		<td colspan="7">
			<input type="text" name="reserve_month" value="<?=$reserve_month;?>" />months
		</td>
	</tr>
	<tr>
		<td colspan="4"><B>Min Number of referrals to Qualify</B></td>
		<td colspan="7">
			<input type="text" name="min_q_referrals" value="<?=$min_q_referrals;?>" /> members
		</td>
	</tr>
	<tr>
		<td colspan="4"><B>Min Number of referrals to Qualify for Free Members</B></td>
		<td colspan="7">
			<input type="text" name="min_free_referrals" value="<?=$min_free_referrals;?>" /> members
		</td>
	</tr>
	<tr>
		<td colspan="4"><B>Qualify Exp. Time</B></td>
		<td colspan="7">
			<input type="text" name="q_time" value="<?=$q_time;?>" /> months
		</td>
	</tr>

	<tr><td colspan="9">&nbsp;</td></tr>
	<tr>
		<td colspan="4"><B>Direct Member Income</B></td>
		<td colspan="7">
			<input type="text" name="direct_member_income" value="<?=$direct_member_income;?>" />
		</td>
	</tr>
	<tr>
		<td colspan="4"><B>Admin Tax</B></td>
		<td colspan="7"><input type="text" name="admin_tax" value="<?=$admin_tax;?>" /></td>
	</tr>
	<tr>
		<td colspan="4"><B>Minimum Withdrawal</B></td>
		<td colspan="7"><input type="text" name="min_withdrawal" value="<?=$min_withdrawal;?>" /></td>
	</tr>
	<tr>
		<td colspan="4"><B>Withdrawal Tax</B></td>
		<td colspan="7"><input type="text" name="withdrawal_tax" value="<?=$withdrawal_tax;?>" /></td>
	</tr>

	<!-- AEM SETTINGS -->
	<tr><td colspan="9">&nbsp;</td></tr>
	<tr><td colspan="9"><b>AEM GROUP ID SETTINGS</b></td></tr>
	<tr>
		<td colspan="4"><B>AEM Free ID</B></td>
		<td colspan="7">
			<input type="text" name="aem_free_id" value="<?=$aem_free_id;?>" />
		</td>
	</tr>
	<tr>
		<td colspan="4"><B>AEM Executive ID</B></td>
		<td colspan="7">
			<input type="text" name="aem_executive_id" value="<?=$aem_executive_id;?>" />
		</td>
	</tr>
	<tr>
		<td colspan="4"><B>AEM Leadership ID</B></td>
		<td colspan="7">
			<input type="text" name="aem_leadership_id" value="<?=$aem_leadership_id;?>" />
		</td>
	</tr>
	<tr>
		<td colspan="4"><B>AEM Professional ID</B></td>
		<td colspan="7">
			<input type="text" name="aem_professional_id" value="<?=$aem_professional_id;?>" />
		</td>
	</tr>
	<tr>
		<td colspan="4"><B>AEM Masters ID</B></td>
		<td colspan="7">
			<input type="text" name="aem_masters_id" value="<?=$aem_masters_id;?>" />
		</td>
	</tr>
	<tr>
		<td colspan="4"><B>AEM Founder ID</B></td>
		<td colspan="7">
			<input type="text" name="aem_founder_id" value="<?=$aem_founder_id;?>" />
		</td>
	</tr>

	<!-- SPECIAL PROMO REGISTRATION -->
	<tr><td colspan="9">&nbsp;</td></tr>
	<tr><td colspan="9"><b>SPECIAL REGISTRATION DETAILS</b></td></tr>
	<tr>
		<td colspan="4"><B>Registration Fee</B></td>
		<td colspan="7">
			<input type="text" name="aem_special_registration" value="<?=$aem_special_registration;?>" />
		</td>
	</tr>
	<tr>
		<td colspan="4"><B>Wordpress Membership</B></td>
		<td colspan="7">
			<input type="text" name="aem_special_wp_membership" value="<?=$aem_special_wp_membership;?>" />
		</td>
	</tr>
	<tr>
		<td colspan="4"><B>Matrix Membership</B></td>
		<td colspan="7">
			<input type="text" name="aem_special_matrix_membership" value="<?=$aem_special_matrix_membership;?>" />
		</td>
	</tr>

	<!-- SUBSCRIPTION REGISTRATION -->
	<tr><td colspan="9">&nbsp;</td></tr>
	<tr><td colspan="9"><b>SUBSCRIPTION REGISTRATION DETAILS</b></td></tr>
	<tr>
		<td colspan="4"><B>Registration Fee</B></td>
		<td colspan="7">
			<input type="text" name="glc_subscription_registration" value="<?=$glc_subscription_registration;?>" />
		</td>
	</tr>
	<tr>
		<td colspan="4"><B>Wordpress Membership</B></td>
		<td colspan="7">
			<input type="text" name="glc_subscription_wp_membership" value="<?=$glc_subscription_wp_membership;?>" />
		</td>
	</tr>
	<tr>
		<td colspan="4"><B>Matrix Membership</B></td>
		<td colspan="7">
			<input type="text" name="glc_subscription_matrix_membership" value="<?=$glc_subscription_matrix_membership;?>" />
		</td>
	</tr>

	<!-- SITEBUILDER DOMAIN -->
	<tr><td colspan="9">&nbsp;</td></tr>
	<tr><td colspan="9"><b>SITEBUILDER DETAILS</b></td></tr>
	<tr>
		<td colspan="4"><B>DOMAIN</B></td>
		<td colspan="7">
			<input type="text" name="sitebuilder_domain" value="<?=$sitebuilder_domain;?>" placeholder="Ex. http://sitebuilder.glchub.com" />
		</td>
	</tr>
	
	<!-- SUBMIT BUTTON -->
	<tr>
		<td colspan="9" class="text-center">
			<input type="submit" name="submit" value="Update" class="btn btn-primary" />
		</td>
	</tr>

	</tbody>
</table>
</form>
</div>
