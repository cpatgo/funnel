<?php
session_start();

include("condition.php");
include("../function/setting.php");	
include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "50";

$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from logs where type = '$type_data[4]' ");
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{
	print "<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 width=100%>
			<tr><th width=200 class=\"message tip\"><strong>User Name/Admin</strong></th>
				<th width=200 class=\"message tip\"><strong>Date</strong></th>
				<th width=200 class=\"message tip\"><strong>Massage</strong></th>
				<th width=200 class=\"message tip\"><strong>Title</strong></th></tr>";
				
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		
		
	
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
				
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from logs where type = '$type_data[4]' LIMIT $start,$plimit ");
			
	while($row = mysqli_fetch_array($query))
	{
		$title = $row['title'];
		$message = $row['message'];
		$user_id = $row['user_id'];
		$username = get_user_name($user_id);
		if($user_id == $admin_data){ $action_by = "Admin"; } else { $action_by = $username; }
		$date = $row['date'];
		print  "<tr><td width=200 class=\"input-medium\" style=\"padding-left:35px\">$action_by</td>
					<td width=200 class=\"input-medium\" style=\"padding-left:45px\">$date</small></td>
					<td width=200 class=\"input-medium\" style=\"padding-left:45px\">$message</small></td>
					<td width=200 class=\"input-medium\" style=\"padding-left:50px\">$title</small> </td></tr>";
	}
	print "<tr><td colspan=5>&nbsp;</td></tr><td colspan=5 height=30px width=400 class=\"message tip\"><strong>";
		if ($newp>1)
		{ ?>
			<a href="<?php echo "index.php?page=wallet_logs&p=".($newp-1);?>">&laquo;</a>
		<?php 
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ ?>
				<a href="<?php echo "index.php?page=wallet_logs&p=$i";?>"><?php print_r("$i");?></a>
				<?php 
			}
			else
			{
				 print_r("$i");
			}
		} 
		if ($newp<$pnums) 
		{ ?>
		   <a href="<?php echo "index.php?page=wallet_logs&p=".($newp+1);?>">&raquo;</a>
		<?php 
		} 
		print"</strong></td></tr></table>";	
}
else { print "<tr><td colspan=\"5\" width=200 class=\"td_title\">There is no logs !</td></tr></table>"; }


?>

