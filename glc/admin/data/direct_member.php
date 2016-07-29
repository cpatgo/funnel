<?php
session_start();

include("condition.php");
include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "15";

if((isset($_POST['submit'])) or $newp != '')
{
	if($newp == '')
	{
		$_SESSION['save_username_ednet'] = $_REQUEST['username'];
	}
	$username = $_SESSION['save_username_ednet'];
	
	$id_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE username = '$username' ");
	$num = mysqli_num_rows($id_query);
	if($num == 0)
	{ echo "<B style=\"color:#ff0000; font-size:12pt;\">Please enter correct Username !</B>"; }
	else
	{
		while($row = mysqli_fetch_array($id_query))
		{
			$id = $row['id_user'];
		}
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE real_parent = '$id' ");
		$totalrows = mysqli_num_rows($query);
		if($totalrows == '')
		{
			echo "<B style=\"color:#ff0000; font-size:12pt;\">$username have no child !</B>";
		}
		else
		{	?>
			<div class="ibox-content">
			<table class="table table-bordered">
				<thead><tr><th>Total Direct members :</th><th><?=$totalrows;?></th></tr></thead>
				<tbody>
					<tr><td><B>User Name</B></td>
						<td><B>Name</B></td>
						<td><B>Status</B></td>
					</tr>
		<?php			
		
			$pnums = ceil ($totalrows/$plimit);
			if ($newp==''){ $newp='1'; }
				
			$start = ($newp-1) * $plimit;
			$starting_no = $start + 1;
			
			if ($totalrows - $start < $plimit) { $end_count = $totalrows;
			} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
				
				
			
			if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
			} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
				
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE real_parent = '$id' LIMIT $start,$plimit ");		
			while($id_row = mysqli_fetch_array($query))
			{
				$id = $id_row['id_user'];
				$username = get_user_name($id);
				$type = $id_row['type'];
				if($type == 'B') { $status = "Active"; }
				elseif($type == 'C') {  $status = "Blocked"; }
				else { $status = "Deactive"; }
				$name = $id_row['f_name']." ".$id_row['l_name'];
				print "<tr><td class=\"input-medium\">$username</td>
							<td class=\"input-medium\">$name</td>
							<td class=\"input-medium\">$status</td>
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
					<a href="<?="index.php?page=direct_member&p=".($newp-1);?>">Previous</a>
				</li>
			<?php 
			}
			for ($i=1; $i<=$pnums; $i++) 
			{ 
				if ($i!=$newp)
				{ ?>
					<li class="paginate_button ">
						<a href="<?="index.php?page=direct_member&p=$i";?>"><?php print_r("$i");?></a>
					</li>
					<?php 
				}
				else
				{ ?>
					<li class="paginate_button active"><a href="#"><?php print_r("$i"); ?></a></li>
				  <?php
				}
			} 
			if ($newp<$pnums) 
			{ ?>
				<li id="DataTables_Table_0_next" class="paginate_button next">
					<a href="<?="index.php?page=direct_member&p=".($newp+1);?>">Next</a>
				</li>
			<?php 
			} 	
			?>
			</ul></div>
		<?php
	
		}
	}	
}
else
{ ?>
<form name="myform" action="index.php?page=direct_member" method="post"> 
<div class="ibox-content">
<table class="table table-bordered">
	<thead><tr><th colspan="2">Direct Member</th></tr></thead>
	<tr>
		<th>Enter Username </th>
		<td><input type="text" name="username" class="input-medium"  /></td>
	</tr>
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="submit" value="submit" class="btn btn-primary" />
		</td>
	</tr>
</table>
</div>

<?php  } ?>
