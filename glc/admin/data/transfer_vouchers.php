<?php


include("condition.php");
include("../function/functions.php");

if(isset($_POST['submit']))
{
	$username = $_REQUEST['username'];
	$voucher = $_REQUEST['voucher'];
	
	$id_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE username = '$username' ");
	$num = mysqli_num_rows($id_query);
	if($num == 0)
	{
		print "Please enter correct Username !";
	}
	else 
	{
		while($row = mysqli_fetch_array($id_query))
		{
			$id = $row['id_user'];
		}
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_voucher WHERE voucher = '$voucher' and user_id = 0 ");
		$num_voucher = mysqli_num_rows($query);
		if($num_voucher == 0)
		{
			print "Please enter correct Voucher !";
		}
		else 
		{
			while($rr = mysqli_fetch_array($query))
			{
				$b_type = $rr['type'];
			}
			$date = date('Y-m-d');
			mysqli_query($GLOBALS["___mysqli_ston"], "update board_voucher set user_id = '$id' , issue_date = '$date' where voucher = '$voucher' and user_id = 0 ");
			$b_voucher = $voucher;
			if($b_type == 'A')
				$b_voucher_type = "TVI";
			else
				$b_voucher_type = "Uni TVI";
			$log_by = "EDNET Admin";
			include("../function/logs_messages.php");
			data_logs($id,$data_log[12][0],$data_log[12][1],$log_type[12]);
			data_logs($id,$data_log[13][0],$data_log[13][1],$log_type[13]);
			
			print "Transfer Voucher Complete Successfully !"; 
		}
	}	
}

else
{ ?>

<table width="50%" border="0">
<form name="myform" action="index.php?page=transfer_vouchers" method="post">
  <tr>
    <td colspan="2">&nbsp;</td>
  
  </tr>
  <tr>
    <td><p>Enter Username :</p></td>
    <td><p><input type="text" name="username" class="input-medium"  /></p></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td><p>Enter Voucher :</p></td>
    <td><p><input type="text" name="voucher" class="input-medium"  /></p></td>
  </tr>
  <tr>
    <td colspan="2"><p align="center"><input type="submit" name="submit" value="submit" class="button"  /></p></td>
  </tr>
  
</table>

<?php  } ?>
	