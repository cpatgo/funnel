<?php
session_start();

include("condition.php");
include("../function/setting.php");	
include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "50";

$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from logs where type = '$type_data[1]' ");
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{ ?>
<div class="ibox-content">
<table class="table table-bordered">
	<thead>
	<tr>
		<th>User Name/Admin</th>
		<th>Date</th>
		<th>Massage</th>
		<th>Title</th>
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
				
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from logs where type = '$type_data[1]' LIMIT $start,$plimit ");
			
	while($row = mysqli_fetch_array($query))
	{
		$title = $row['title'];
		$message = $row['message'];
		$user_id = $row['user_id'];
		$username = get_user_name($user_id);
		if($user_id == $admin_data){ $action_by = "Admin"; } else { $action_by = $username; }
		$date = $row['date'];
		
		echo  "
			<tr>
				<td>$action_by</td>
				<td>$date</td>
				<td>$message</td>
				<td>$title </td>
			</tr>";
	}
	print "</tbody></table></div>";
	?>
	<div id="DataTables_Table_0_paginate" class="dataTables_paginate paging_simple_numbers">
	<ul class="pagination">
	<?php
	if ($newp>1)
	{ ?>
		<li id="DataTables_Table_0_previous" class="paginate_button previous">
			<a href="<?="index.php?page=finiancial_logs&p=".($newp-1);?>">Previous</a>
		</li>
	<?php 
	}
	for ($i=1; $i<=$pnums; $i++) 
	{ 
		if ($i!=$newp)
		{ ?>
			<li class="paginate_button ">
				<a href="<?="index.php?page=finiancial_logs&p=$i";?>"><?php print_r("$i");?></a>
			</li>
			<?php 
		}
		else
		{ ?><li class="paginate_button active"><a href="#"><?php print_r("$i"); ?></a></li><?php }
	} 
	if ($newp<$pnums) 
	{ ?>
	   <li id="DataTables_Table_0_next" class="paginate_button next" >
			<a href="<?="index.php?page=finiancial_logs&p=".($newp+1);?>">Next</a>
	   </li>
	<?php 
	} 
	?>
	</ul></div>
	<?php
}
else { echo "<B style=\"color:#ff0000; font-size:12pt;\">There is no logs !</B>"; }

?>

