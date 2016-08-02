<?php
session_start();
require_once("config.php");
require_once("function/setting.php");
include("function/functions.php");
require_once("function/send_mail.php");

if($_POST['update'])
{
	$id = $_SESSION['dennisn_user_id'];
	$raw_password = $_POST['new_password'];
	$old_password = sha1($_POST['old_password']);
	$new_password = sha1($_POST['new_password']);
	$con_new_password = sha1($_POST['con_new_password']);
	$security_password = $_POST['security_password'];
	
	if($new_password == $con_new_password)
	{
		$qur = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where id_user = '$id' and password = '$old_password' ");
		$num1 = mysqli_num_rows($qur);
		
		if($num1 > 0)
		{
			$insert_q = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE users SET password = '$new_password' WHERE id_user = '$id'");
			$insert_q = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE security_password SET mode = 0 WHERE security_password = '$security_password' and user_id = '$id' ");
			
			//Also update the password in wordpress database
			include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
			wp_set_password($raw_password, get_current_user_id());

			//Update the password in AEM database
			include_once($_SERVER['DOCUMENT_ROOT'].'/aem/manage/config.inc.php');
			$aem_con = mysqli_connect(AWEBP_AUTHDB_SERVER, AWEBP_AUTHDB_USER, AWEBP_AUTHDB_PASS, AWEBP_AUTHDB_DB);
			mysqli_query($aem_con, sprintf("UPDATE aweb_globalauth SET password = '%s' WHERE username = '%s'", md5($raw_password), $_SESSION['dennisn_username']));
			mysqli_close($aem_con);

			$date = date('Y-m-d');
			$updated_by = $username = "Ourself ".$_SESSION['dennisn_username'];
			$username = $_SESSION['dennisn_username'];
			include("function/logs_messages.php");
			data_logs($id,$data_log[2][0],$data_log[2][1],$log_type[2]);
					
			echo "<font color=\"#003A75\" size=\"+2\"><B>Password Updated Successfully </B></font>";
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
{	?>
	<div class="ibox-content">	
	<form name="change_pass" action="index.php?page=change_password" method="post" id="commentform">
	<table class="table table-bordered">
		<input type="hidden" name="security_password" value="<?=$security_pass; ?>" />
		<thead><tr><th colspan="2"><?=$Change_Password;?></th></tr></thead>
		<tbody>
		<tr>
			<td><?=$Current_Password;?> : </td>
			<td><input type="password" size="25" name="old_password" /></td>
		</tr>
		<tr>
			<td><?=$New_Password;?> : </td>
			<td><input type="password" size="25" name="new_password" /></td>
		</tr>
		<tr>
			<td><?=$Conf_New_Password;?> : </td>
			<td><input type="password" size=25 name="con_new_password" /></td>
		</tr>
		<tr>
			<td colspan="2" class="text-center">
				<input type="submit" name="update" value="<?=$Change;?>" class="btn btn-primary" />
			</td>
		</tr>
		</tbody>
	</table>
	</form>
	</div>	

<?php } ?>