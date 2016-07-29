<?php
session_start();

include("condition.php");

include("../function/setting.php");


if(isset($_POST['submit']))
{
	$welcome_message = $_REQUEST['welcome_message'];
	$forget_password_message = $_REQUEST['forget_password_message'];
	$payout_generate_message = $_REQUEST['payout_generate_message'];
	$email_welcome_message = $_REQUEST['email_welcome_message'];
	$direct_member_message = $_REQUEST['direct_member_message'];
	$payment_request_message = $_REQUEST['payment_request_message'];
	$payment_transfer_message = $_REQUEST['payment_transfer_message'];
	$epin_generate_message = $_REQUEST['epin_generate_message']; 
	$user_pin_generate_message = $_REQUEST['user_pin_generate_message']; 
	
	mysqli_query($GLOBALS["___mysqli_ston"], "update setting set welcome_message  = '$welcome_message' , forget_password_message = '$forget_password_message' , payout_generate_message = '$payout_generate_message' , email_welcome_message = '$email_welcome_message' , direct_member_message = '$direct_member_message' , payment_request_message = '$payment_request_message' , payment_transfer_message = '$payment_transfer_message' , epin_generate_message = '$epin_generate_message' , user_pin_generate_message = '$user_pin_generate_message' ");
	
	data_logs($id,$pos,$data_log[12][0],$data_log[12][1],$log_type[9]);
	
	$p = 1; 
		 	 	 	 	 	 	
}

$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from setting ");
while($row = mysqli_fetch_array($query))
{
	$welcome_message = $row['welcome_message'];
	$forget_password_message = $row['forget_password_message'];
	$payout_generate_message = $row['payout_generate_message'];
	$email_welcome_message = $row['email_welcome_message'];
	$direct_member_message = $row['direct_member_message'];
	$payment_request_message = $row['payment_request_message'];
	$payment_transfer_message = $row['payment_transfer_message'];
	$epin_generate_message = $row['epin_generate_message'];
	$user_pin_generate_message = $row['user_pin_generate_message'];
	$member_to_member_message = $row['member_to_member_message'];
}
?>	
<div class="ibox-content">	
<form name="request" action="index.php?page=general_setting" method="post" >
<table class="table table-bordered">
	<tr><td colspan="2"><?php if($p == 1) { print "Updating completed Successfully"; } ?></td></tr>
	<thead><tr><th colspan="2">General Setting Form</th></tr></thead>
	<tbody>
	<tr>
		<td><B>Welcome Message</B></td>
		<td>
			<textarea name="welcome_message" style="width:600px; height:150px" >
				<?=$welcome_message; ?>
			</textarea>
		</td>
	</tr>
	<tr>
		<td><B>Forget Password Message</B></td>
		<td>
			<textarea name="forget_password_message" style="width:600px; height:150px" >
				<?=$forget_password_message; ?>
			</textarea>
		</td>
	</tr>
	<tr>
		<td><B>Payout Generate Message</B></td>
		<td>
			<textarea name="payout_generate_message" style="width:600px; height:150px" >
				<?=$payout_generate_message; ?>
			</textarea>
		</td>
	</tr>
	<tr>
		<td><B>Email Welcome Message</B></td>
		<td>
			<textarea name="email_welcome_message" style="width:600px; height:150px" >
				<?=$email_welcome_message; ?>
			</textarea>
		</td>
	</tr>
	<tr>
		<td><B>Direct Member Message</B></td>
		<td>
			<textarea name="direct_member_message" style="width:600px; height:150px" >
				<?=$direct_member_message; ?>
			</textarea>
		</td>
	</tr>
	<tr>
		<td><B>Payout Request Message</B></td>
		<td>
			<textarea name="payment_request_message" style="width:600px; height:150px" >
				<?=$payment_request_message; ?>
			</textarea>
		</td>
	</tr>
	<tr>
		<td><B>Payout Transfer Message</B></td>
		<td>
			<textarea name="payment_transfer_message" style="width:600px; height:150px" >
				<?=$payment_transfer_message; ?>
			</textarea>
		</td>
	</tr>
	<tr>
		<td><B>e-Voucher Generate Message</B></td>
		<td>
			<textarea name="epin_generate_message" style="width:600px; height:150px" >
				<?=$epin_generate_message; ?>
			</textarea>
		</td>
	</tr>
	<tr>
		<td><B>User pin Generate Message</B></td>
		<td>
			<textarea name="user_pin_generate_message" style="width:600px; height:150px" >
				<?=$user_pin_generate_message; ?>
			</textarea>
		</td>
	</tr>
	<tr>
		<td><B>Admin Alert On Join</B></td>
		<td>
			<textarea name="user_pin_generate_message" style="width:600px; height:150px" >
				<?=$member_to_member_message; ?>
			</textarea>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="submit" value="Update" class="btn btn-primary" />
		</td>
	</tr>
	</tbody>
</table>
</form>
</div>