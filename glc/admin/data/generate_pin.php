<?php
session_start();
//ini_set("display_errors" , "off");
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
	$epin_type = $_REQUEST['epin_type'];
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
		else { echo "<B style=\"color:#ff0000; font-size:12pt;\">Error : Enter correct User Id !</B>"; }	
	}
	else
		$user_id = 0;
		
	if(($username != '' and $num > 0) or ($username == ''))
	{	
		$query1 = mysqli_query($GLOBALS["___mysqli_ston"], "select * from setting");
		$num1 = mysqli_num_rows($query1);
		if($num1 > 0)
		{
			while($row1 = mysqli_fetch_array($query1))
			{
				switch($epin_type)
				{
					case 1 : $cost = 'first_board_join';
							 $plan_field = 'first_board_name';
							 break;
					case 2 : $cost = 'second_board_join';
							 $plan_field = 'second_board_name';
							 break;
					case 3 : $cost = 'third_board_join';
							 $plan_field = 'third_board_name';
							 break;
					case 4 : $cost = 'fourth_board_join';
							 $plan_field = 'fourth_board_name';
							 break;
				}
			
				$e_amount = $row1[$cost];
			}	
		}
			for($i = 0; $i < $number; $i++)
			{
				do 
				{
					$unique_epin = substr(md5(rand(0, 1000000)), 0, 10);
					$query_object = mysqli_query($GLOBALS["___mysqli_ston"],  "SELECT * FROM e_voucher WHERE voucher = $unique_epin");
					$query_record = mysqli_fetch_array($query_object);
					if(! $query_record) {
						break;
					}
				} while(1);
				
				$mode = 1;
				$date = date('Y-m-d');
				mysqli_query($GLOBALS["___mysqli_ston"], "insert into e_voucher (voucher , voucher_type , epin_amount , user_id , mode , date , used_id) values ('$unique_epin' , '$epin_type' , '$e_amount' , '$user_id' , '$mode' , '$date' , 0)");
					$epin .= "<br>".$unique_epin;
					
					$epin_log = $unique_epin;
					$username_log = $username."(Dennisn ADMIN)";
					include("../function/logs_messages.php");
					data_logs($id,$data_log[9][0],$data_log[9][1],$log_type[9]);
			}
			if($username != '')
			{
				$to = $_SESSION['ednet_user_email'];
				$title = "E pin mail";
				$full_message = "Hello User ".$_SESSION['dennisn_user_name']." you have generated ".$number." and your e-Voucher is".$epin;
				$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);						
				$SMTPChat = $SMTPMail->SendMail();
			}	
			echo "<B style=\"color:#167C1E; font-size:12pt;\">Success : Request for New e-Voucher has Completed Successfully !</B>";
			$_SESSION['gp_refresh'] = "hhj";
		}
	}
else{
	unset($_SESSION['gp_refresh']);
 ?>
<div class="ibox-content">
<form name="money" action="index.php?page=generate_pin" method="post">
<table class="table table-bordered">		
	<thead><tr><th colspan="2">e-Voucher Generate For Users</th></tr></thead>
	<tbody>
	<tr>
		<th>No. of e-Voucher </th>
		<td><input type="text" name="number" /></td>
	</tr>
	<tr>
		<th>e-Voucher Type </th>
		<td>
			<select name="epin_type">
			<?php
				$qu = mysqli_query($GLOBALS["___mysqli_ston"], "select * from setting ");
				while($rrr = mysqli_fetch_array($qu))
				{ 
					for($i = 1; $i < 5; $i++)
					{
						switch($i)
						{
							case 1 : $cost = 'first_board_join';
									 $plan_field = 'first_board_name';
									 break;
							case 2 : $cost = 'second_board_join';
									 $plan_field = 'second_board_name';
									 break;
							case 3 : $cost = 'third_board_join';
									 $plan_field = 'third_board_name';
									 break;
							case 4 : $cost = 'fourth_board_join';
									 $plan_field = 'fourth_board_name';
									 break;
						}
					$plan_name = $rrr[$plan_field];
					$plan_id = $i;
					$amount = $rrr[$cost];
					?>
					<option value="<?=$i;?>"><?=$plan_name.' ('.$amount.')'; ?></option>
			<?php	}	
				}	
				?>		
			</select>
		</td>
	</tr>
	<tr>
		<th>User Id <B style="color:#ff0000; font-size:12px;">(Leave blank for Admin e-Voucher)</B></th>
		<td><input type="text" name="username" /></td>
	</tr>
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="submit" value="Request" class="btn btn-primary" />
		</td>   
	</tr>
	</tbody>
</table> 
</form>
</div>
<?php
}

?>