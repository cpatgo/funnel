<?php

include("condition.php");
include("../function/functions.php");

$id = $_SESSION['admin_id'];

$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from paid_unpaid where paid = 1 and amount > 0 ");
$num = mysqli_num_rows($query);
if($num != 0)
{ ?>
<div class="ibox-content">
<table class="table table-bordered dataTable">
	<thead>
	<tr>
		<th>Id</th>
		<th>User Name</th>
		<th>Request Amount</th>
		<th>Payment Type</th>
		<th>Date Requested</th>
		<th>Date Paid</th>
	</tr>
	</thead>
	<?php
	while($row = mysqli_fetch_array($query))
	{
		$id = $row['id'];
		$u_id = $row['user_id'];
		$username = get_user_name($u_id);
		$request_amount = number_format($row['amount']);
		$payment_type = $row['pay_mode'];
		$paid_requested = date("m/d/Y", strtotime($row['paid_requested']));
		$paid_date = date("m/d/Y", strtotime($row['paid_date']));
		
		echo "
			<tr>
				<td>$id</td>
				<td>$username</td>
				<td>$ $request_amount USD</td>
				<td>$payment_type</td>
				<td>$paid_requested</td>
				<td>$paid_date</td>
			</tr>";
	}
	echo "</table></div>";	
}
else{ echo "<B style=\"color:#ff0000; font-size:12pt;\">There are no fund for approved !</B>"; }
?>

