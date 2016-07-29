<?php
session_start();
include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "20";

if(isset($_POST['transfer_epin']))
{
	$transfer_username = $_POST['transfer_username'];
	$transfer_epin = $_POST['transfer_epin'];
	$epin_id = $_POST['epin_id'];
	$transfer_id = get_new_user_id($transfer_username);
	
	if($transfer_id == 0)
		print "<B style=\"color:#FF0000; font-size:12pt;\">Error : Enter Correct Username !!</B>";
	else
	{
		mysqli_query($GLOBALS["___mysqli_ston"], "update e_voucher set user_id = '$transfer_id' where mode = 1 and id = '$epin_id' ");
		echo "<B style=\"color:#015A08; font-size:12pt;\">Success : Request for New e-Vouchers has Completed Successfully !</B>";
	
	}	
}


$user_id = $_SESSION['ukwix_user_id'];
$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from e_voucher where mode = 1 and user_id = 0 ");
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{
	echo "
	<div class=\"ibox-content\">
	<table class=\"table table-bordered\">
		<thead>
		<tr>
			<th colspan=3>Total Used e-Voucher : </th>
			<th colspan=3>$totalrows</th>
		</tr>
		<tr>
			<th class=\"text-center\">e-Voucher</th>
			<th class=\"text-center\">Date</th>
			<th class=\"text-center\">Product Id</th>
			<th class=\"text-center\">User Id</th>
			<th class=\"text-center\">Transfer</th>
		</tr>
		</thead>";
	  
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		
		
	
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
	  
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from e_voucher where mode = 1 and user_id = 0 LIMIT $start,$plimit ");   
	while($row = mysqli_fetch_array($query))
	{
		$epin_id = $row['id'];
		$epin = $row['voucher'];
		$date = $row['date'];
		$product_id = $row['voucher_type'];
		$used_id = $row['used_id'];
		$username = get_user_name($used_id);
		$used_date = $row['used_date'];
		?>
		<tr class="text-center">
			<form action="index.php?page=transfer_epin" method="post">
			<input type="hidden" name="epin_id" value="<?=$epin_id; ?>" />
			<td><?=$epin; ?></td>
			<td><?=$date; ?></td>
			<td><?=$product_id; ?></td>
			<td><input type="text" name="transfer_username"  /></td>
			<td>
				<input type="submit" name="transfer_epin" value="Transfer" class="btn btn-primary" />
			</td>
			</form>	
		</tr>
<?php	
	}
	echo "</table></div>";
	?>
	<div id="DataTables_Table_0_paginate" class="dataTables_paginate paging_simple_numbers">
	<ul class="pagination">
	<?php
		if ($newp>1)
		{ ?>
			<li id="DataTables_Table_0_previous" class="paginate_button previous">
				<a href="<?="index.php?page=transfer_epin&p=".($newp-1);?>">Previous</a>
			</li>
		<?php 
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ ?>
				<li class="paginate_button ">
					<a href="<?="index.php?page=transfer_epin&p=$i";?>">
						<?php print_r("$i");?>
					</a>
				</li>
				<?php 
			}
			else
			{ ?>
				<li class="paginate_button active">
					<a href="#"><?php print_r("$i"); ?></a>
				</li><?php 
			}
		} 
		if ($newp<$pnums) 
		{ ?>
		   <li id="DataTables_Table_0_next" class="paginate_button next">
				<a href="<?="index.php?page=transfer_epin&p=".($newp+1);?>">Next</a>
		   </li>
		<?php 
		} 
		?>
		</ul></div>
	<?php
	}
	else 
	{
		print "<B style=\"color:#FF0000; font-size:12pt;\">There is no e-Voucher to show !</B>";
	}
	
?>
