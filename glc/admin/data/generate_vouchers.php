<?php
session_start();

include("condition.php");
include("../function/setting.php");
include("../function/send_mail.php");
include("../function/functions.php");
include("../function/wallet_message.php");

if(isset($_POST['submit']) && (!isset($_SESSION['gp_refresh'])) )
{
		$voucher_pin = $_REQUEST['voucher_pin'];
		$voucher_type = $_REQUEST['voucher_type'];
		
		$mode = 1;
		$date = date('Y-m-d');
		mysqli_query($GLOBALS["___mysqli_ston"], "insert into board_voucher (voucher, type , mode , date) values ('$voucher_pin' , '$voucher_type' , '$mode' , '$date')");
		
		$b_voucher = $voucher_pin;
		if($voucher_type == 'A')
			$b_voucher_type = "TVI";
		else
			$b_voucher_type = "Uni TVI";
		include("../function/logs_messages.php");
		data_logs($from,$data_log[11][0],$data_log[11][1],$log_type[11]);
		
		print "<font color=\"#00376F\" size=\"+1\"><strong>Vouchers Generated Successfully !</strong></font>";
}		
			
else
{
	unset($_SESSION['gp_refresh']);
 ?> 
		
		<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 width=500>
		<form name="money" action="index.php?page=generate_vouchers" method="post">
		  <tr>
			<td colspan="2" class="td_title"><strong>e-Voucher Generate For Users</strong></td>   
		  </tr>
		  
		  <tr>
			<td colspan="2">&nbsp;</td>   
		  </tr>
		  <tr>
		   <td class="td_title">Vouchers Pin :</td>
			<td ><input type="text" name="voucher_pin" size="18" class="input-small" /></b></p></td></font></font>
		  </tr>
		  
		  <tr>
			<td class="td_title"><p>Voucher Type:</p></td>
			<td>
				<select name="voucher_type">
					<option value="A">TVI</option>
					<option value="B">Uni TVI</option>
				</select>
			</td>
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
