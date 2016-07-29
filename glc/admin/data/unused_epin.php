<?php
session_start();
include("../function/functions.php");

if(isset($_POST['Delete']))
{
	$epin_id = $_POST['epin_id'];
	mysqli_query($GLOBALS["___mysqli_ston"], "delete from e_voucher where id = '$epin_id' ");
	echo "<B style=\"color:#015A08; font-size:12pt;\">Success : e-Voucher Deleted Successfully !</B>";
}

$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from e_voucher where mode = 1 ");
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{	?>
	<div class="ibox-content">
	<table class="table table-striped table-bordered dataTablesePins">
		<thead>
		<tr><th colspan=3>Total Unused e-Voucher :</th><th colspan=3><?=$totalrows;?></th></tr>
		<tr>
			<th class="text-center">e-Voucher</th>
			<th class="text-center">Used Id</th>
			<th class="text-center">Date</th>
			<th class="text-center">e-Voucher Type</th>
			<th class="text-center">Amount</th>
			<th class="text-center">Action</th>
		  </tr>
		  </thead>
		  <tbody>
		<?php  
		$e_query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from setting");
		$e_row = mysqli_fetch_array($e_query);
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from e_voucher where mode = 1");   
		while($row = mysqli_fetch_array($query))
		{
			$epin_id = $row['id'];
			$epin = $row['voucher'];
			$date = $row['date'];
			$product_id = $row['voucher_type'];
			
			$e_amount = $e_plan = '';
			switch($product_id)
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
			$e_amount = $e_row[$cost];
			$e_plan = $e_row[$plan_field];
				
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
					<td>$username</td>
					<td>$date</td>
					<td>$e_plan</td>
					<td>$e_amount</td>"; ?>
					<td>
						<form action="index.php?page=unused_epin" method="post">
							<input type="hidden" name="epin_id" value="<?=$epin_id;?>"  />
							<input type="submit" name="Delete" value="Delete" class="btn btn-primary"  />
						</form>
					</td>
				  </tr>
		<?php	
		}
		print "</tbody></table></div>";
		?>
	<?php
}
else 
{
	print "<B style=\"color:#ff0000; font-size:12pt;\">There are no e-Voucher to show !</B>";
}
?>
