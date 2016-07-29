<?php
session_start();

include("condition.php");
include("../function/setting.php");	
include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "25";

$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from logs ");
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{ ?>
<div class="ibox-content">	
<table class="table table-bordered">
	<thead>
		<tr>
			<th class="text-center" width="150"><B>User Name/Admin</B></th>
			<th class="text-center"><B>Date</B></th>
			<th class="text-center" width="150"><B>Type</B></th>
			<th class="text-left"><B>Title</B></th>
			<th class="text-center"><B>Massage</B></th>
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
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from logs LIMIT $start,$plimit ");			
	while($row = mysqli_fetch_array($query))
	{
		$title = $row['title'];
		$type = $row['type'];
		if($type == $type_data[0]){ $action = "General"; } else { $action = "Financial"; }
		$message = $row['message'];
		$user_id = $row['user_id'];
		$username = get_user_name($user_id);
		//if($user_id == $admin_data){ $action_by = "Admin"; } else { $action_by = $username; }
		if($user_id == 0){ $action_by = "Admin"; } 
		$date = date("m/d/Y",strtotime($row['date']));
		print  "
			<tr>
				<td class=\"text-center\">$action_by</td>
				<td class=\"text-center\">$date</small></td>
				<td class=\"text-center\">$action</small></td>
				<td>$message</small></td>
				<td class=\"text-center\">$title</small></td>
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
			<a href="<?="index.php?page=project_logs&p=".($newp-1);?>">Previous</a>
		</li>
	<?php 
	}
	for ($i=1; $i<=$pnums; $i++) 
	{ 
		if ($i!=$newp)
		{ ?>
			<li class="paginate_button ">
				<a href="<?="index.php?page=project_logs&p=$i";?>"><?php print_r("$i");?></a>
			</li>
			<?php 
		}
		else
		{ ?><li class="paginate_button active"><a href="#"><?php print_r("$i"); ?></a></li><?php }
	} 
	if ($newp<$pnums) 
	{ ?>
		<li id="DataTables_Table_0_next" class="paginate_button next">
	   		<a href="<?="index.php?page=project_logs&p=".($newp+1);?>">Next</a>
	   	</li>
	<?php 
	} 
	?>
</ul></div>

<?php
}		
else{ print "<B style=\"color:#ff0000; font-size:12pt;\">There are No information to show !</B>"; }
?>
