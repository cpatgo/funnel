<?php
session_start();
include '../config.php';

if(isset($_POST['check']))
{
$_SESSION['admin_login_id'] = $username = $_POST['username'];
	
$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where username='$username'");
	while($row = mysqli_fetch_array($query))
		{
			 $login_username = $row['username'];
			 $user_value = $row['id_user'];
			 $user_no = $row['id_user']
?>

<div align="center">
 <form method="post" action="">
	<table style="line-height:42px;" width="500">
	 
	 <tr>
	  <td width="30%" style="font-size:12pt;">User Id</td>
	  <td>
	  <input type="hidden" name="user_value"  value="<?php print $user_no; ?>" />
	  <input type="text" name="catg_name" required class="input" value="<?php print $login_username; ?>" />
	  
	  </td>
	 </tr>
	 
	 <tr>
	  <td style="font-size:12pt;">Total Amount</td>
	  <td>
	  		<?php
			$query= mysqli_query($GLOBALS["___mysqli_ston"], "select * from reg_voucher where user_id='$user_no' order by id DESC limit 1");
			$num = mysqli_num_rows($query);
		if($num == 0)
			{
				print "<span style=\"color:#000;\">please before invest your voucher amount</span>";
			}
			else
			{
			while($row = mysqli_fetch_array($query))
				{	
				 $voucher_amount = $row['voucher_amount'];
				
		?>  		<input style="text-align:left;" type="text" name="total_amount" required  value="<?php print $voucher_amount; ?>" />
			<?php 	}
			 }
			?>
			
	  </td>
	</tr>  
	  
	  <tr>
	  <td style="font-size:12pt;">Product Name</td>
	  
	<td>
			<input type="text" value="" name="pro_name" class="input"/>
	</td>
	 </tr>

	 <tr>
	  <td style="font-size:12pt;">Invest Amount</td>
	  <td><input type="text"  name="invest_amount" required  class="input"/></td>
	 </tr>

	 	 <tr>
	  <td align="center" colspan="2">
	  <input type="submit" name="submit" required  value="Submit" size="20"  style="width:100px; height:30px; background:#00CC00;"/>
	  </td>
	 </tr>
	 
	</table>
</form>	
</div>


<?php }}
	
	elseif(isset($_POST['submit']))
	{
		$user_value = $_POST['user_value'];
		$my_query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from reg_voucher where user_id = '$user_value'");
		while($myro = mysqli_fetch_array($my_query))
		{
			 $voucher_amount = $myro['voucher_amount'];
			 $user_no = $myro['user_id'];
			 $id_ro = $myro['id'];
		}
		$invest_amount = $_POST['invest_amount'];
		if($invest_amount == 0  || $voucher_amount < $invest_amount)
		{
				print "please amount properly";
		}
		else
		{	
				$voucher_amount1 = $voucher_amount-$invest_amount;
				mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE reg_voucher SET voucher_amount='$voucher_amount1' where id ='$id_ro' ");
				
				 $pro_name=$_REQUEST['pro_name'];
				 $pro_amount=$_REQUEST['invest_amount'];
				 $date = date('y-m-d');
					mysqli_query($GLOBALS["___mysqli_ston"], "insert into user_panel  (serial_no, pro_name, user_id, date, pro_amount) VALUES ('$serial_no' , '$pro_name',                 '$user_no', '$date', '$pro_amount') ");
				
				print $_SESSION['success'] = "<font color=green size=2><strong>Amount successfully invested</strong></font>";
			echo "<script type=\"text/javascript\">";
			echo "window.location = \"index.php?page=voucher1\"";
			echo "</script>";

		}
			
	}

else{

?>
 <form method="post" action="">
 	Enter User Name : <input type="text" name="username"  />
		<input type="submit" name="check" value="submit" />
 
 </form> 
 <?php } ?>