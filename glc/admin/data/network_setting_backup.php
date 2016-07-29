<?php
session_start();

include("condition.php");

include("../function/setting.php");

if(isset($_POST['submit']))
{
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

	 mysqli_query($GLOBALS["___mysqli_ston"], "update setting set min_q_referrals = '".$min_q_referrals."',min_free_referrals = '".$min_free_referrals."',q_time = '".$q_time."',first_board_name = '".$board_name[1]."' , first_board_income_1 = '".$board_income[1][1]."' , first_board_income_2 = '".$board_income[1][2]."' , first_board_point = '".$board_point[1]."' , second_board_name = '".$board_name[2]."' , second_board_income_1 = '".$board_income[2][1]."' , second_board_income_2 = '".$board_income[2][2]."' , second_board_point = '".$board_point[2]."' , third_board_name = '".$board_name[3]."' , third_board_income_1 = '".$board_income[3][1]."' , third_board_income_2 = '".$board_income[3][2]."' , third_board_point = '".$board_point[3]."' , fourth_board_name = '".$board_name[4]."' , fourth_board_income_1 = '".$board_income[4][1]."' , fourth_board_income_2 = '".$board_income[4][2]."' , fourth_board_point = '".$board_point[4]."' , five_board_name = '".$board_name[5]."' , five_board_income_1 = '".$board_income[5][1]."' , five_board_income_2 = '".$board_income[5][2]."' , five_board_point = '".$board_point[5]."' , six_board_name = '".$board_name[6]."' , six_board_income_1 = '".$board_income[6][1]."' , six_board_income_2 = '".$board_income[6][2]."' , six_board_point = '".$board_point[6]."' , direct_member_income = '$direct_member_income' , pin_cost = '$pin_cost' , admin_tax = '$admin_tax' , withdrawal_tax = '$withdrawal_tax' , min_withdrawal = '$min_withdrawal', first_board_join='".$board_join[1]."', second_board_join='".$board_join[2]."', third_board_join='".$board_join[3]."', fourth_board_join='".$board_join[4]."', five_board_join='".$board_join[5]."' ");

	$date = date('Y-m-d');
	include("../function/logs_messages.php");
	data_logs($id,$data_log[14][0],$data_log[14][1],$log_type[14]);

	$p = 1;
}

$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from setting ");
while($row = mysqli_fetch_array($query))
{
	$board_name[1] = $row['first_board_name'];
	$board_income[1][1] = $row['first_board_income_1'];
	$board_income[1][2] = $row['first_board_income_2'];
	$board_point[1] = $row['first_board_point'];
	$board_join_cost[1] = $row['first_board_join'];

	$board_name[2] = $row['second_board_name'];
	$board_income[2][1] = $row['second_board_income_1'];
	$board_income[2][2] = $row['second_board_income_2'];
	$board_point[2] = $row['second_board_point'];
	$board_join_cost[2] = $row['second_board_join'];

	$board_name[3] = $row['third_board_name'];
	$board_income[3][1] = $row['third_board_income_1'];
	$board_income[3][2] = $row['third_board_income_2'];
	$board_point[3] = $row['third_board_point'];
	$board_join_cost[3] = $row['third_board_join'];

	$board_name[4] = $row['fourth_board_name'];
	$board_income[4][1] = $row['fourth_board_income_1'];
	$board_income[4][2] = $row['fourth_board_income_2'];
	$board_point[4] = $row['fourth_board_point'];
	$board_join_cost[4] = $row['fourth_board_join'];

	$board_name[5] = $row['five_board_name'];
	$board_income[5][1] = $row['five_board_income_1'];
	$board_income[5][2] = $row['five_board_income_2'];
	$board_point[5] = $row['five_board_point'];
	$board_join_cost[5] = $row['five_board_join'];

	$board_name[6] = $row['six_board_name'];
	$board_income[6][1] = $row['six_board_income_1'];
	$board_income[6][2] = $row['six_board_income_2'];
	$board_point[6] = $row['six_board_point'];
	$board_join_cost[6] = '';

	$min_q_referrals 	= $row['min_q_referrals'];
	$min_free_referrals = $row['min_free_referrals'];
	$q_time 			= $row['q_time'];

	$direct_member_income = $row['direct_member_income'];
	$pin_cost = $row['pin_cost'];
	$admin_tax = $row['admin_tax'];
	$withdrawal_tax = $row['withdrawal_tax'];


}
?>
<div class="ibox-content">
<form name="setting" method="post" action="index.php?page=network_setting">
<table class="table table-bordered">
	<tr><td colspan="5"><?php if($p == 1) { print "Updating completed Successfully"; } ?></td></tr>
	<thead>
	<tr><th colspan="4">Board Break Income</th></tr>
	<tr>
		<th>Board Name</th>
		<th>First Pass</th>
		<th>Infinity</th>
		<!--<th>Income</th>-->
		<th>Join Cost</th>
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
			<input type="text" name="board_income_second_<?=$i;?>" value="<?=$board_income[$i][2]; ?>" />
		</td>
		<!--<td><input type="text" name="board_point_<?=$i;?>" value="<?=$board_point[$i];?>" /></td>-->
		<td><input type="text" name="board_join_<?=$i;?>" value="<?=$board_join_cost[$i];?>" /></td>
	</tr>
 <?php
 }
 ?>
	<tr><td colspan="5">&nbsp;</td></tr>
	<tr>
		<td colspan="2"><B>Min Number of referrals to Qualify</B></td>
		<td colspan="3">
			<input type="text" name="min_q_referrals" value="<?=$min_q_referrals;?>" /> members
		</td>
	</tr>
	<tr>
		<td colspan="2"><B>Min Number of referrals to Qualify for Free Members</B></td>
		<td colspan="3">
			<input type="text" name="min_free_referrals" value="<?=$min_free_referrals;?>" /> members
		</td>
	</tr>
	<tr>
		<td colspan="2"><B>Qualify Exp. Time</B></td>
		<td colspan="3">
			<input type="text" name="q_time" value="<?=$q_time;?>" /> months
		</td>
	</tr>

	<tr><td colspan="5">&nbsp;</td></tr>
	<tr>
		<td colspan="2"><B>Direct Member Income</B></td>
		<td colspan="3">
			<input type="text" name="direct_member_income" value="<?=$direct_member_income;?>" />
		</td>
	</tr>
	<tr>
		<td colspan="2"><B>Admin Tax</B></td>
		<td colspan="3"><input type="text" name="admin_tax" value="<?=$admin_tax;?>" /></td>
	</tr>
	<tr>
		<td colspan="2"><B>Minimum Withdrawal</B></td>
		<td colspan="3"><input type="text" name="min_withdrawal" value="<?=$min_withdrawal;?>" /></td>
	</tr>
	<tr>
		<td colspan="2"><B>Withdrawal Tax</B></td>
		<td colspan="3"><input type="text" name="withdrawal_tax" value="<?=$withdrawal_tax;?>" /></td>
	</tr>

	<tr>
		<td colspan="5" class="text-center">
			<input type="submit" name="submit" value="Update" class="btn btn-primary" />
		</td>
	</tr>

	</tbody>
</table>
</form>
</div>
