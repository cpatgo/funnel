<?php
require_once("config.php");
include("condition.php");

$newp = $_GET['p'];
$plimit = "15";

$id = $_SESSION['dennisn_user_id'];

	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from paid_unpaid where user_id = '$id' ");
	$totalrows = mysqli_num_rows($q);
	if($totalrows != 0)
	{
	?>
		<div class="ibox-content">	
		<table class="table table-striped table-bordered dataTables">
			<thead>
			<tr>
				<th class="text-center"><?php echo $Request_Amt;?></th>
				<th class="text-center"><?php echo $Request_Date; ?></th> 
				<th class="text-center"><?php echo $Satus;?></th>
			</tr>
			</thead>
			<tbody>
<?php				
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from paid_unpaid where user_id = '$id' ");	
	while($row = mysqli_fetch_array($query))
	{
		$request_amount = number_format($row['amount']);
		$request_date = date("m/d/Y",strtotime($row['request_date']));
		$paid_date = $row['paid_date'];
		if($paid_date == '0000-00-00')
		{
			$status = "<span class='label label-danger'>".$Pending."</span>";
		} else {
			$date = date("m/d/Y",strtotime($row['paid_date']));
			$status = "<span class='label label-primary'>".$Paid."</span> ".$date;
		}
		print "
			<tr>
				<td class='text-center'>$".$request_amount."</td>
				<td class='text-center'>".$request_date."</td>
				<td class='text-center'>".$status."</td>	
			</tr>";
	}
	print "</tbody></table></div>";
?>
<div id="DataTables_Table_0_paginate" class="dataTables_paginate paging_simple_numbers">
<ul class="pagination">

<?php	
	if ($newp>1)
	{ ?> 
		<li id="DataTables_Table_0_previous" class="paginate_button previous" aria-controls="DataTables_Table_0" tabindex="0">
			<a href="<?= "index.php?page=request_status&p=".($newp-1);?>">Previous</a>
		</li>
	<?php 
	}
	for ($i=1; $i<=$pnums; $i++) 
	{ 
		if ($i!=$newp)
		{ ?>
			<li class="paginate_button " aria-controls="DataTables_Table_0" tabindex="0">
				<a href="<?= "index.php?page=request_status&p=$i";?>"><?php print_r("$i");?></a>
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
	   		<a href="<?= "index.php?page=request_status&p=".($newp+1);?>">Next</a>
	   	</li>
		<?php 
	} 
	?>
</ul></div>

<?php

}
else{ print "<div class='alert alert-danger'>There are no request at that point.</div>"; }
?>
