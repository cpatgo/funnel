<?php
session_start();

include("condition.php");


if(isset($_POST['submit']))
{
	$u_name = $_REQUEST[user_name];
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where username = '$u_name' ");
	$num = mysqli_num_rows($q);
	if($num == 0)
	{
		echo "<B style=\"color:#ff0000; font-size:12pt;\">Please Enter right User Name!</B>"; 
	}
	else
	{
		while($id_row = mysqli_fetch_array($q))
		{
			$id_user = $id_row['id_user'];
		}
	?>
		<div class="ibox-content">
		<table class="table table-bordered">
			<thead>
			<tr>
				<th>User Name</th>
				<th>Amount</th>
				<!--th>Points</th-->
			</tr>
			</thead>
			<tbody>	
		<?php
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM wallet WHERE id = '$id_user' ");
		while($row = mysqli_fetch_array($query))
		{
			$income = number_format($row['amount'], 2);
		}
		$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from point_wallet where user_id = '$id_user' ");
		while($row = mysqli_fetch_array($q))
		{
			$user_point = $row['user_point'];
		}
		/* <td>$user_point Pt</td> */
		echo "
			<tr>
				<td>$u_name</td>
				<td>$income</td>
				
			</tr>
			</tbody>
			</table></div>";
	}
}

else
{ ?>
<div class="ibox-content">
<form name="my_form" action="index.php?page=wallet_amount" method="post">
<table class="table table-bordered">
	<thead><tr><th colspan="2">Wallet Information</th></tr></thead>
	<tbody>
	<tr>
		<th>Enter Member UserName</th>
		<td><input type="text" name="user_name" /></td>
	</tr>
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="submit" value="Submit" class="btn btn-primary" />
		</td>
	</tr>
  </tbody>
</table>
</form>
</div>
<?php  
}  
?>

