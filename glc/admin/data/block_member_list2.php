<?php
ini_set("display_errors","on");

include("condition.php");
include("../function/functions.php");


$newp = $_GET['p'];
$plimit = "15";

if(isset($_POST['submit']))
{
	$user_id = $_REQUEST['user_id'];
	mysqli_query($GLOBALS["___mysqli_ston"], "update users set type = 'B' where id_user = '$user_id' ");
	print "<font color=\"#00366C\" size=\"+2\">User Unblocked Successfully !</font>";
}
else
{ 
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE type = 'C' ");
	$totalrows = mysqli_num_rows($query);
	if($totalrows == '')
	{
		echo "<font color=\"#FF0000\" size=\"+2\">There Is No Block Member !</font>";
	}
	else
	{ ?>
		<div class="ibox-content">
		<table class="table table-bordered">
		<thead>
		<tr><th>Total Block members :</th><th colspan="3"><?=$totalrows;?></th></tr>
		<tr>
			<th>Name</th>
			<th>User Name</th>
			<th>Discription</th>
			<th>User Name</th>
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
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE type = 'C' LIMIT $start,$plimit ");
		while($id_row = mysqli_fetch_array($query))
			{
				$id = $id_row['id_user'];
				$discription = $id_row['discription'];
				$username = $id_row['username'];
				$name = $id_row['f_name']." ".$id_row['l_name'];
				print "<tr><td class=\"input-medium\">$name</td>
							<td class=\"input-medium\">$username</td>
							<td class=\"input-medium\">$discription</td>
							<td class=\"input-medium\">
								<form name=\"block_form\" method=\"post\" action=\"index.php?page=block_member_list\">
								<input type=\"hidden\" name=\"user_id\" value=\"$id\" />
								<input type=\"submit\" name=\"submit\" value=\"Unblock User\" />
								</form>
							</td>
							</tr>";
			}
			print "<tr><td colspan=5>&nbsp;</td></tr><td colspan=5 height=30px width=400 class=\"message tip\"><strong>";
			if ($newp>1)
			{ ?>
				<a href="<?php echo "index.php?page=block_member_list&p=".($newp-1);?>">&laquo;</a>
			<?php 
			}
			for ($i=1; $i<=$pnums; $i++) 
			{ 
				if ($i!=$newp)
				{ ?>
					<a href="<?php echo "index.php?page=block_member_list&p=$i";?>"><?php print_r("$i");?></a>
					<?php 
				}
				else
				{
					 print_r("$i");
				}
			} 
			if ($newp<$pnums) 
			{ ?>
			   <a href="<?php echo "index.php?page=block_member_list&p=".($newp+1);?>">&raquo;</a>
			<?php 
			} 	
			print "</table>"; 
	}
}