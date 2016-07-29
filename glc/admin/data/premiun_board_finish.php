<?php
session_start();

include("condition.php");
require_once("../config.php");
require_once("../function/functions.php");

$newp = $_GET['p'];
$plimit = "20";

$show = get_premium_board_finish();
$totalrows = count($show);
if($totalrows == 0)
{
	echo "There is no information to show!"; 
}
else {
print "<table width=600 hspace = 0 cellspacing=0 cellpadding=0 border=0>
	<tr>
	<th height=30 class=\"message tip\">Total User</td>
	<th class=\"message tip\" colspan=2>&nbsp; $totalrows</td></tr>
	<tr><td colspan=3>&nbsp;</td></tr>
	<tr>
	<td height=30 class=\"message tip\"><strong>Username</strong></td>
	<td class=\"message tip\"><strong>Board Id</strong></td>
	<td class=\"message tip\"><strong>Date</strong></td></tr>";
	
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		
			
		
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }	 
	$total_end = $start+$plimit;
	if($total_end < $totalrows)
		$total_ends = $total_end;
	else
		$total_ends = $totalrows;	

	for($j = $start+1; $j <= $total_ends; $j++)
	{
		$b_id = $show[$j][0];
		$date = $show[$j][2];
		$username = get_user_name($show[$j][1]);
		print "<tr>
				<td class=\"input-medium\">$username</td>
				<td class=\"input-medium\">$b_id</td>
				<td class=\"input-medium\">$date</td></tr>";
	}	
	print "<tr><td colspan=5>&nbsp;</td></tr><td colspan=5 height=30px width=400 class=\"message tip\"><strong>";
		if ($newp>1)
	{ ?>
		<a href="<?php echo "index.php?page=premiun_board_finish&p=".($newp-1);?>">&laquo;</a>
	<?php 
	}
	for ($i=1; $i<=$pnums; $i++) 
	{ 
		if ($i!=$newp)
		{ ?>
			<a href="<?php echo "index.php?page=premiun_board_finish&p=$i";?>"><?php print_r("$i");?></a>
			<?php 
		}
		else
		{
			 print_r("$i");
		}
	} 
	if ($newp<$pnums) 
	{ ?>
	   <a href="<?php echo "index.php?page=premiun_board_finish&p=".($newp+1);?>">&raquo;</a>
	<?php 
	} 	
	print "</table>";
}	



function get_premium_board_finish()
{
	$k = 1;
	$qqq = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users ");
	$a = mysqli_num_rows($qqq);
	for($i = 1; $i <= $a; $i++)
	{
		$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break where user_id = '$i' order by id desc limit 1 ");
		while($r = mysqli_fetch_array($q))
		{
			$level_board = $r['level'];
			if($level_board > 1)
			{
				$results[$k][0] = $r['board_b_id'];
				$results[$k][1] = $r['user_id'];
				$results[$k][2] = $r['date'];
				$k++;
			}
		}
	}	
	return $results;
}




?>
	
		
		
		
