<?php
session_start();

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "25";

$user_id = $_SESSION['ukwix_user_id'];
$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from reg_voucher where mode = 0 ");
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{ ?>
<div class="ibox-content">
	<table class="table table-bordered">	
		<thead>
		<tr>
			<th colspan=2>Total Vouchers: </th>
			<th colspan=2><?=$totalrows;?></th>
		</tr>
		<tr>
			<th>Date</th>
			<th>User Id</th>
			<th>Voucher</th>
			<th>Amount</th>
		</tr>
		</thead>
	<?php
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		
		
	
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
	  
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from reg_voucher where mode = 0 LIMIT $start,$plimit ");   	 	 	
	while($row = mysqli_fetch_array($query))
	{
		$voucher = $row['voucher'];
		$date = $row['date'];
		$voucher_amount = $row['voucher_amount'];
		$used_id = $row['user_id'];
		$username = get_user_name($used_id);
		
		echo "
			<tr>
				<td>$date</td>
				<td>$username</td>
				<td>$voucher</td>
				<td >$voucher_amount</td>
			</tr>";
	}
	print "</table></div>";
	?>
	<div id="DataTables_Table_0_paginate" class="dataTables_paginate paging_simple_numbers">
	<ul class="pagination">
	<?php
	if ($newp>1)
	{ ?>
		<li id="DataTables_Table_0_previous" class="paginate_button previous">
			<a href="<?="index.php?page=inactive_vouchers&p=".($newp-1);?>">Previous</a>
		</li>
	<?php 
	}
	for ($i=1; $i<=$pnums; $i++) 
	{ 
		if ($i!=$newp)
		{ ?>
			<li class="paginate_button ">
				<a href="<?="index.php?page=inactive_vouchers&p=$i";?>"><?php print_r("$i");?></a>
			</li>
			<?php 
		}
		else
		{ ?><li class="paginate_button active"><a href="#"><?php print_r("$i"); ?></a></li><?php }
	} 
	if ($newp<$pnums) 
	{ ?>
	   <li id="DataTables_Table_0_next" class="paginate_button next">
			<a href="<?="index.php?page=inactive_vouchers&p=".($newp+1);?>">Next</a>
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
