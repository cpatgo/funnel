<?php
session_start();
require_once("../config.php");
require_once("../function/setting.php");
include("../function/functions.php");
require_once("../function/send_mail.php");

if($_POST['update'])
{
	
		$old_password = sha1($_POST['old_password']);
		$new_password = sha1($_POST['new_password']);
		$con_new_password = sha1($_POST['con_new_password']);
		
		if($new_password == $con_new_password)
		{
			$qur = mysqli_query($GLOBALS["___mysqli_ston"], "select * from admin where id_user = 1 and password = '$old_password' ");
			$num1 = mysqli_num_rows($qur);
			if($num1 > 0)
			{
				$insert_q = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE admin SET password = '$new_password' WHERE id_user = 1 ");
				
				//Also update the password in wordpress database
				include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
				wp_set_password($_POST['new_password'], 1);
				
				$date = date('Y-m-d');
				$updated_by = $username = "Ourself ".$_SESSION['ednet_user_name'];
				$username = $_SESSION['ednet_user_name'];
				include("function/logs_messages.php");
				data_logs($id,$data_log[2][0],$data_log[2][1],$log_type[2]);
						
				echo "<font color=\"#003A75\" size=\"+2\"><B>Password Updateds uccessfully </B></font>";
			}
			else
			{
				print "<font color=\"#FF0000\" size=\"+2\">Please Enter Correct Old Password</font>";
			}	
		}
		else
		{
			print "<font color=\"#FF0000\" size=\"+2\">Please Enter Same Password in Both New and confirm new password field !</font>"; 
		}	
	
}	

else
{

?>
<div class="ibox-content">
<form name="change_pass" action="index.php?page=change_password" method="post">
<table class="table table-bordered">
	<thead><tr><th colspan="2">Change Password</th></tr></thead>
	<input type="hidden" name="security_password" value="<?=$security_pass; ?>" />
	<tbody>
	<tr>
		<td>Enter Old Password </td>
		<td><input type="password" size=25 name="old_password" /></td>
	</tr>
	<tr>
		<td>Enter New Password </td>
		<td><input type="password" size=25 name="new_password" /></td>
	</tr>
	<tr>
		<td>Confirm New Password </td>
		<td><input type="password" size=25 name="con_new_password" /></td>
	</tr>
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="update" value="Change" class="btn btn-primary" />
		</td>
	</tr>
	</tbody>
</table>
</form>
</div>

<?php } ?>