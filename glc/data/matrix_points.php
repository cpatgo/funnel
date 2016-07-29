<?php
session_start();
include("condition.php");
include("function/setting.php");

$newp = $_GET['p'];
$plimit = "15";
$user_id = $_SESSION['dennisn_user_id'];

$que = mysqli_query($GLOBALS["___mysqli_ston"], "select * from income where user_id = '$user_id' and type = '$income_type[2]' ");
$totalrows = mysqli_num_rows($que);
if($totalrows > 0)
{
?>
<div class="ibox-content">	
<table class="table table-bordered">
<?php	
	$query1 = mysqli_query($GLOBALS["___mysqli_ston"], "select sum(amount) from income where user_id = '$user_id' and 
	type = '$income_type[1]' ");
	while($row1 = mysqli_fetch_array($query1))
	{ 
		$total_income = $row1[0];
	}
?>
	<thead>
	<tr>
		<th colspan=1><?=$Total_Incomes;?></th>
		<th colspan=2><b><?=$total_income;?> INR</b></th>
	</tr>
	</thead>
	<thead>
	<tr>
		<th class="text-center"><?=$Date;?></th> 
		<th class="text-center"><?=$Points;?></th>
		<th class="text-center"><?=$Board_Name;?></th> 
	</tr>
	</thead>
	<tbody>
<?php
		
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from income where user_id = '$user_id' and 
	type = '$income_type[2]' LIMIT $start,$plimit ");
	while($row = mysqli_fetch_array($query))
	{
		$date = $row['date'];
		$amount = $row['amount'];
		$admin_tax = $row['admin_tax']; 
		$board_type = $row['board_type']; 

		$board_naam = $setting_board_name[$row['board_type']];	
		print "
			<tr align=\"center\">
				<td>$date</td>
				<td>$amount Pt</td>
				<td>$board_naam</td>
			</tr>";
		$j = 1;
	}
	print "</tbody></table></div>";
?>
<div id="DataTables_Table_0_paginate" class="dataTables_paginate paging_simple_numbers">
<ul class="pagination">

<?php	
	if ($newp>1)
	{ ?> 
		<li id="DataTables_Table_0_previous" class="paginate_button previous" aria-controls="DataTables_Table_0" tabindex="0">
			<a href="<?="index.php?page=matrix_points&p=".($newp-1);?>"><?=$Previous;?></a>
		</li>
	<?php 
	}
	for ($i=1; $i<=$pnums; $i++) 
	{ 
		if ($i!=$newp)
		{ ?>
			<li class="paginate_button " aria-controls="DataTables_Table_0" tabindex="0">
				<a href="<?="index.php?page=matrix_points&p=$i";?>"><?php print_r("$i");?></a>
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
	   		<a href="<?="index.php?page=matrix_points&p=".($newp+1);?>"><?=$Next;?></a>
	   	</li>
	<?php 
	} 
	?>
</ul></div>

<?php
}		
else{ print "<B style=\"color:#ff0000; font-size:12pt;\">$No_info_to_show</B>"; }
?>
