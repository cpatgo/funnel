<?php
if(isset($_POST['submit']))
{
	
	include("../function/functions.php");
	$username = $_REQUEST['username'];
	$user_id = get_new_user_id($username);
	if($user_id != 0)
	{
		$amount = $_REQUEST['amount'];
		if($amount > 0)
		{
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from wallet where id = '$user_id' ");
			while($row = mysqli_fetch_array($query))
			{
				$db_amount = $row['amount'];
			}
			$amount = $amount + $db_amount;
			$date = date('Y-m-d');
			mysqli_query($GLOBALS["___mysqli_ston"], "update wallet set amount = '$amount' , date = '$date' where id = '$user_id' ");
			$log_username = $username;	
			include("../function/logs_messages.php");
			data_logs($user_id,$data_log[16][0],$data_log[16][1],$log_type[16]);
			
			print "<B style=\"color:#015A08; font-size:12pt;\">Amount Added Successfully!</B>";
		}
		else
		{
			print "<B style=\"color:#FF0000; font-size:12pt;\">Please Enter correct Amount!</B>";	
		}
	}
	else { print "<B style=\"color:#FF0000; font-size:12pt;\">Please Enter correct username!</B>";	 }
}
else
{ ?>
<div class="ibox-content">
<form name="add_funds" action="index.php?page=add_funds" method="post">
<table class="table table-bordered"> 
	<thead><tr><th colspan="2">Add Amount Pannel</th></tr></thead>
	<tr>
		<th>Enter Username</th>
		<td><input type="text" name="username" /></td>
	</tr>
	<tr>
		<th>Amount</th>
		<td><input type="text" name="amount" />USD $</td>
	</tr>
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="submit" value="Show" class="btn btn-primary" />
		</td>
	</tr>
</table>
</form>
</div>
<?php } ?>

