<?php
require_once("config.php");
include("condition.php");
include("function/setting.php");
include("function/send_mail.php");
include("function/functions.php");
include("function/wallet_message.php");

$id = $_SESSION['dennisn_user_id'];

$check = 1; //check_amount_transfer($id);
if($check == 1)
{
	$position = $_SESSION['position'];
	if(isset($_POST['submit']))
	{
		$user_pin = $_REQUEST['user_pin'];
		$epin = $_REQUEST['epin'];
		$request_amount = $_REQUEST['request'];
		$requested_user = $_REQUEST['requested_user'];
		$requested_user_id = get_new_user_id($requested_user);
		
		if($_SESSION['dennisn_user_type'] == 'B')
		{
				$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where id_user = '$id' ");
				$num = mysqli_num_rows($query);
				if($num > 0)
				{
					while($row = mysqli_fetch_array($query))
					{
						$user_type = $row['type'];
					}
					$qu = mysqli_query($GLOBALS["___mysqli_ston"], "select * from e_voucher where user_id = '$id' and mode = 1 and voucher = '$epin' ");
					$epin_chk = mysqli_num_rows($qu);
					if($epin_chk > 0)
					{ 
						$left_amount = $current_amount-$request_amount;
						$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from wallet where id = '$requested_user_id' ");
						while($row = mysqli_fetch_array($query))
						{
							$wallet_amount = $row['amount'];
							$total_amount = $wallet_amount+$request_amount;
						}
						$request_date= date('Y-m-d');
						
						mysqli_query($GLOBALS["___mysqli_ston"], "update e_voucher set user_id = '$requested_user_id' , date = '$request_date' where user_id = '$id' and mode = 1 and voucher = '$epin' ");								
								
						$to = $requested_user;
						$username = $_SESSION['ednet_user_name'];	
						$position = $_SESSION['ednet_user_position'];
						$req_position = get_user_position($requested_user_id);
						
						
						$transfered_usernane = $requested_user;
						include("function/logs_messages.php");
						data_logs($id,$data_log[7][0],$data_log[7][1],$log_type[7]);
						data_logs($requested_user_id,$data_log[8][0],$data_log[8][1],$log_type[8]);
						
							//email
						$to = get_user_email($requested_user_id);  //message foe mail
						$title = "Payment Request Message";
						$full_message = $Hello_user.$requested_user.$You_Hav_Rec_Epin.$epin;
						$full_message;
						$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
						$SMTPChat = $SMTPMail->SendMail();
							
						echo "<B style=\"color:#003366; font-size:12pt;\">$You_req_Trans_Epin ".$epin." $has_compl_success</B>";
							
					}		
					else { echo "<B style=\"color:#FF0000; font-size:12pt;\">$Plz_ent_cor_epin</B>"; }
				}
				else { echo "<B style=\"color:#FF0000; font-size:12pt;\">$Plz_ent_cor_user_pin</B>"; }	
		}
		else 
		{ echo "<B style=\"color:#FF0000; font-size:12pt;\">$Trans_not_comp_Blocked_By_Admin</B>."; }			
	}
	else
	{
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from e_voucher where user_id = '$id' and mode = 1 ");
		$n = mysqli_num_rows($query);
		
		$msg = $_REQUEST[mg]; echo $msg; ?> 
	
	<div class="ibox-content">	
	<form name="money" action="index.php?page=transfer_pin" method="post">
	<table class="table table-bordered">
		<input type="hidden" name="curr_amnt" value="<?=$curr_amnt;?>"  />
		<thead><tr><th colspan="2"><?=$Your_Total_Epin;?> <?=$n;?></th></tr></thead>
		<tbody>  
		<tr>
			<td width=""><?=$Enter_Epin;?></td>
			<td><input type="text" name="epin" /></td>
		</tr>
		<tr>
			<td><?=$Requested_Username;?></td>
			<td><input type="text" name="requested_user" /></td>
		</tr>
		<!--<tr>
			<td>Transaction Password :</td>
			<td><input type="text" name="user_pin" /></td>
		</tr>-->
		<tr>
			<td colspan="2" align="center">
				<input type="submit" name="submit" value="<?=$Request;?>"  class="btn btn-primary" />
			</td>   
		</tr>
		</tbody>
	</table>
	</form>
	</div>
	
<?php  
	}  
} 


function get_transfer_limit($id)	
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from wallet where id = '$id' ");
	while($row = mysqli_fetch_array($q))
	{
		$limit = $row['transfer_limit'];
	}
	return $limit;	
}