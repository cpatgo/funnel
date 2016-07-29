<?php
require_once("config.php");
include("condition.php");
include("function/setting.php");
include("function/send_mail.php");
include("function/functions.php");
include("function/wallet_message.php");

$id = $_SESSION['dennisn_user_id'];
if(isset($_POST['submit']) && (!isset($_SESSION['gp_refresh'])) )
{
		$user_pin = $_REQUEST['user_pin'];
		$current_amount = $_REQUEST['curr_amnt'];
		$number = $_REQUEST['number'];
		$max_epin = $_REQUEST['max_epin'];
		$epin_kit = $_POST['epin_kit'];
		
		$quer45 = mysqli_query($GLOBALS["___mysqli_ston"], "select * from products where id = '$epin_kit' ");
		while($rrrr = mysqli_fetch_array($quer45))
		{
			$epin_amount = $rrrr['prod_amount'];
		}		
		
		if($max_epin >= $number)
		{
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where id_user = '$id' and user_pin = '$user_pin' ");
			$num = mysqli_num_rows($query);
			if($num > 0)
			{
				$left_amount = $current_amount-($epin_amount*$number);
				$request_date= date('Y-m-d');
				mysqli_query($GLOBALS["___mysqli_ston"], "update wallet set amount = '$left_amount' , date = '$request_date' where id = '$id' ");
				for($i = 0; $i < $number; $i++)
				{
					$unique_epin = mt_rand(1000000000, 9999999999);	
					$mode = 1;
					$date = date('Y-m-d');
					mysqli_query($GLOBALS["___mysqli_ston"], "insert into e_voucher (voucher, user_id , generate_id , voucher_type , mode , date , used_id , generate_id , epin_amount) values ('$unique_epin' , '$id' , '$id' ,'$epin_kit' , '$mode' , '$date' , 0 , '$id' , '$epin_amount')");
					$epin .= "<br>".$unique_epin;
					
					$username_log = $_SESSION['ednet_user_name'];
					$epin_log = $unique_epin;
					$income_log = $epin_amount;
					include("function/logs_messages.php");
					data_logs($id,$data_log[9][0],$data_log[9][1],$log_type[9]);
					data_logs($id,$data_log[6][0],$data_log[6][1],$log_type[6]);
				}
				$to = $_SESSION['ednet_user_email'];
				$title = "E pin mail";
				$full_message = "Hello User ".$_SESSION['ednet_user_name']." you have generated ".$number." and your e-Voucher is".$epin;
				$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);						$SMTPChat = $SMTPMail->SendMail();
				print "<font color=\"#003366\" size=\"+2\">Your Request for New Pins has Completed Successfully !</font>";
				$_SESSION['gp_refresh'] = "hhj";
			}
			else { print "<font color=\"#FF0000\" size=\"+2\">Please enter correct user pin !</font>"; }
							
		}	
		else { print "<font color=\"#FF0000\" size=\"+2\">Please enter less number of e-Voucher	to generate</font>"; }	
}
else
{
		unset($_SESSION['gp_refresh']);
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "select amount from wallet where id = '$id' ");
		while($row = mysqli_fetch_array($query))
		{
			$curr_amnt = $row[0];
		}
		$max_epin = intval($curr_amnt/$epin_fees);
		
			$msg = $_REQUEST[mg]; echo $msg; ?> 
			<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 width=500>
			<form name="money" action="index.php?val=generate_pin&open=8" method="post">
			<input type="hidden" name="curr_amnt" value="<?php echo $curr_amnt; ?>"  />
			<input type="hidden" name="max_epin" value="<?php echo $max_epin; ?>"  />
			<input type="hidden" name="epin_amount" value="<?php echo $epin_fees; ?>"  />
			  <tr>
				<td colspan="2" class="td_title"><strong><p>Your Wallet Information</p></strong></td>   
			  </tr>
			  <tr>
				<td colspan="2" class="td_title"><font size="+1" color="#003A75"><p>Your Current Income is : $<?php echo $curr_amnt." USD";  ?><br /></p></font></td>
			  </tr>
			  <tr>
				<td colspan="2">&nbsp;</td>   
			  </tr> <?php
			  if($max_epin > 0)
			  { ?>
		      
			  <tr>
			   <td class="td_title"><p>No. of e-Voucher :</td>
				<td ><input type="text" name="number" style="width:188px;" class="input-small" /></b></p></td></font></font>
			  </tr>
			  <tr>
				<td class="td_title"><p>e-Voucher Type:</p></td>
				<td ><p>
				<select name="epin_kit" style="width:200px;">
			<?php		
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from products ");
			while($row = mysqli_fetch_array($query))
			{
				$kit_id = $row['id'];
				$kit_name = $row['products_name'];
				$kit_amount = $row['prod_amount'];
			?>			
				<option value="<?php print $kit_id; ?>"><?php print $kit_name; ?> Of Amount $ <?php print $kit_amount; ?> USD</option>
			<?php		}  ?>				
				</select>
				</p></td>
			  </tr>

			  <tr>
				<td class="td_title"><p>Transaction Pin :</p></td>
				<td ><p><input type="text" name="user_pin" style="width:188px;" class="input-medium" /></p></td>
			  </tr>
			  <tr>
				<td colspan="2">&nbsp;</td>   
			  </tr>
			  <tr>
				<td colspan="2"><p align="center"><input type="submit" name="submit" value="Request"  class="button" /></p></td>   
			  </tr>
			  </form>
			  <?php
			  } 
			  else { print "<tr>
								<td colspan=\"2\"><font color=\"#FF0000\" size=\"+2\">Sorry You Have No Sufficient Balance in Your Wallet !<font></td>   
			  					</tr>"; }
			  ?>
			</table>
	<?php
 	
	 } 
