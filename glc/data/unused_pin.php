<?php
session_start();
require_once("config.php");

$newp = $_GET['p'];
$plimit = "15";

$user_id = $_SESSION['dennisn_user_id'];
$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from e_voucher where user_id = '$user_id' and mode = 1 ");
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{
?>
<div class="ibox-content">	
<table class="table table-bordered">
	<thead>
	<tr>
		<th class="text-center"><?=$Epin;?></th>
		<th class="text-center"><?=$Date;?></th> 
		<th class="text-center"><?=$EVoucher_Type;?></th>
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
	 
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from e_voucher where user_id = '$user_id' 
	and mode = 1 LIMIT $start,$plimit ");  
	while($row = mysqli_fetch_array($query))
	{
		$epin = $row['voucher'];
		$date = $row['date'];
		$product_id = $row['voucher_type'];
		$used_id = $row['used_id'];
		$used_date = $row['used_date'];
		
		$quer45 = mysqli_query($GLOBALS["___mysqli_ston"], "select * from products where id = '$product_id' ");
		while($rrrr = mysqli_fetch_array($quer45))
		{
			$products_name = $rrrr['products_name'];
		}		
		?>
		<tr>
			<td>
				<form action="register.php" target="_new" method="post">
				<input type="submit" name="submit_epin" value="<?=$epin; ?>" class="btn btn-primary" />
				</form>
			</td>
			<td><?=$date;?></td>
			<td>Registration</td>
		</tr>
	<?php
	}
	print "</tbody></table></div>";
?>
<div id="DataTables_Table_0_paginate" class="dataTables_paginate paging_simple_numbers">
<ul class="pagination">

<?php	
	if ($newp>1)
	{ ?> 
		<li id="DataTables_Table_0_previous" class="paginate_button previous" aria-controls="DataTables_Table_0" tabindex="0">
		<a href="<?php echo "index.php?page=unused_pin&p=".($newp-1);?>">Previous</a>
		</li>
	<?php 
	}
	for ($i=1; $i<=$pnums; $i++) 
	{ 
		if ($i!=$newp)
		{ ?>
			<li class="paginate_button " aria-controls="DataTables_Table_0" tabindex="0">
			<a href="<?php echo "index.php?page=unused_pin&p=$i";?>"><?php print_r("$i");?></a>
			</li>
			<?php 
		}
		else
		{	?>
			<li class="paginate_button active" aria-controls="DataTables_Table_0" tabindex="0">
				<a href="#"><?php print_r("$i"); ?></a>
			</li>
			<?php
		}
	} 
	if ($newp<$pnums) 
	{ ?>
	<li id="DataTables_Table_0_next" class="paginate_button next" aria-controls="DataTables_Table_0" tabindex="0">
	   	<a href="<?php echo "index.php?page=unused_pin&p=".($newp+1);?>">Next</a>
	   </li>
	<?php 
	} 
	?>
</ul></div>

<?php
}		
else{ print "<B style=\"color:#ff0000; font-size:12pt;\">$There_is_no_Epin_show</B>"; }
?>