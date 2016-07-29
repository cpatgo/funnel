<?php
session_start();

include("condition.php");
include("../function/setting.php");
include("../function/send_mail.php");
include("../function/functions.php");
include("../function/wallet_message.php");

if(isset($_POST['submit']) && (!isset($_SESSION['gp_refresh'])) )
{
	$username = $_REQUEST['username'];
	$number = $_REQUEST['number'];
	$epin_kit = $_POST['epin_kit'];
	if($username != '')
	{
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where username = '$username' ");
		$num = mysqli_num_rows($query);
		if($num > 0)
		{
			while($row = mysqli_fetch_array($query))
			{
				$user_id = $row['id_user'];
			}	
		}
		else { print "<font color=\"#FF0000\" size=2><strong>Error : Enter correct User Id !</strong></font>"; }	
	}
	else
		$user_id = 0;
		
	if(($username != '' and $num > 0) or ($username == ''))
	{	
		for($i = 0; $i < $number; $i++)
		{
			$unique_epin = "8S".mt_rand(1000000000, 9999999999);	
			$mode = 1;
			$date = date('Y-m-d');
			mysqli_query($GLOBALS["___mysqli_ston"], "insert into e_voucher (voucher, user_id , mode , date , used_id) values ('$unique_epin' , '$user_id' , '$mode' , '$date' , 0)");
				$epin .= "<br>".$unique_epin;
				
				$epin_log = $unique_epin;
				$username_log = $username."(EDNET ADMIN)";
				include("../function/logs_messages.php");
				data_logs($id,$data_log[9][0],$data_log[9][1],$log_type[9]);
		}
		if($username != '')
		{
			$to = $_SESSION['ednet_user_email'];
			$title = "E pin mail";
			$full_message = "Hello User ".$_SESSION['ednet_user_name']." you have generated ".$number." and your e-Voucher is".$epin;
			$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);						$SMTPChat = $SMTPMail->SendMail();
		}	
		print "<font color=\"#003366\" size=2><strong>Success : Request for New e-Voucher has Completed Successfully !</strong></font>";
		$_SESSION['gp_refresh'] = "hhj";
	}
}
else
{
	unset($_SESSION['gp_refresh']);
 ?>
		
		<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 width=500>
		<form name="money" action="index.php?page=generate_pin" method="post">
		  <tr>
			<td colspan="2" class="td_title"><strong>e-Voucher Generate For Users</strong></td>   
		  </tr>
		  
		  <tr>
			<td colspan="2">&nbsp;</td>   
		  </tr>
		  <tr>
		   <td class="td_title">No. of e-Voucher :</td>
			<td ><input type="text" name="number" style="width:188px;" class="input-medium" /></b></p></td></font></font>
		  </tr>
		  <tr>
			<td class="td_title"><p>User Id : <br /><font color="red" size="-3"><strong>(Leave blank for Admin e-Vouchers)</strong></font></p></td>
			<td ><p><input type="text" name="username" style="width:188px;" class="input-medium" /></p></td>
		  </tr>
		  <tr>
			<td colspan="2">&nbsp;</td>   
		  </tr>
		  <tr>
			<td colspan="2"><p align="center"><input type="submit" name="submit" value="Request"  class="button" /></p></td>   
		  </tr>
		  </form>
		</table>
	
	<?php
	 } 
