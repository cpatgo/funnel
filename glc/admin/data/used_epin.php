<?php
session_start();
include("../function/functions.php");

$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from e_voucher where mode = 0 ");
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{	?>
	<div class="ibox-content">
	<table class="table table-striped table-bordered dataTablesePins">
		<thead>
		<tr><th colspan=3>Total Used e-Voucher :</th><th colspan=3><?=$totalrows;?></th></tr>
		<tr>
			<th class="text-center">e-Voucher</th>
			<th class="text-center">Date</th>
			<th class="text-center">Used Id</th>
			<th class="text-center">Product Id</th>
			<th class="text-center">Used By (Username)</th>
			<th class="text-center">Used Date</th>
		  </tr>
		  </thead>
		  <tbody>
		<?php  		  
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from e_voucher where mode = 0");   
		while($row = mysqli_fetch_array($query))
		{
			$epin = $row['voucher'];
			$date = $row['date'];
			$product_id = $row['voucher_type'];
			$used_id = $row['used_id'];
			$used_username = get_user_name($used_id);
			$user_id = $row['user_id'];
			$username = get_user_name($user_id);
			$used_date = $row['used_date'];
			
			$quer45 = mysqli_query($GLOBALS["___mysqli_ston"], "select * from products where id = '$product_id' ");
			while($rrrr = mysqli_fetch_array($quer45))
			{
				$products_name = $rrrr['products_name'];
			}
			echo "
				<tr class=\"text-center\">
					<td>$epin</td>
					<td>$date</td>
					<td>$username</td>
					<td>Registration</td>
					<td>$used_username</td>
					<td>$used_date</td>
				 </tr>";
		}
		print "</tbody></table></div>";
		 
}
else 
{
	print "<B style=\"color:#ff0000; font-size:12pt;\">There is no e-Voucher to show !</B>";
}
	
?>
