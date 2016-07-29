<?php
session_start();
include("condition.php");
require_once("config.php");

$newp = (isset($_GET['p'])) ? $_GET['p'] : '';
$child = (isset($_GET['child'])) ? $_GET['child'] : '';
$plimit = "15";
$membership_class = getInstance('Class_Membership');
?>

<div class="ibox-content">
<?php


$id = (empty($child)) ? $_SESSION['dennisn_user_id'] : $child;
$q = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE real_parent = '$id' ");
$totalrows = mysqli_num_rows($q);
if($totalrows == 0)
{
	echo "<font color=\"#004080\" size=\"+2\">There is no information to show !</font>"; 
}
else
{
if(!empty($child)) printf('<h3>Username: %s</h3>', get_user_name($id));
/*print "<table class=\"table table-bordered directMembersDatatable\">
	<thead>
	<tr>
		<th width=200 height=30px class=\"message tip\">User ID</th>
		<th width=200 class=\"message tip\">Username</th>
		<th width=200 class=\"message tip\">Name</th>
		<th width=200 class=\"message tip\">Date Enrolled</th>
		<th width=200 class=\"message tip\">Referrals</th>
		<th width=200 class=\"message tip\">Status</th>
		<th width=200 class=\"message tip\">Grace Period</th>
		<th width=200 class=\"message tip\">Month Qualification</th>
		<th width=200 class=\"message tip\">Stage 1</th>
		<th width=200 class=\"message tip\">Stage 2</th>
		<th width=200 class=\"message tip\">Stage 3</th>
		<th width=200 class=\"message tip\">Stage 4</th>
		<th width=200 class=\"message tip\">Stage 5</th>
	<tr></thead>"; */
	print "<table class=\"table table-bordered directMembersDatatable\">
	<thead>
	<tr>
		<th width=200 height=30px class=\"message tip\">User ID</th>
		<th width=200 class=\"message tip\">Username</th>
		<th width=200 class=\"message tip\">Name</th>
		<th width=200 class=\"message tip\">Date Enrolled</th>
		<th width=200 class=\"message tip\">Referrals</th>
		<th width=200 class=\"message tip\">Status</th>
		<th width=200 class=\"message tip\">4 Month Qualification</th>
		<th width=200 class=\"message tip\">Stage 1</th>
		<th width=200 class=\"message tip\">Stage 2</th>
		<th width=200 class=\"message tip\">Stage 3</th>
		<th width=200 class=\"message tip\">Stage 4</th>
		<th width=200 class=\"message tip\">Stage 5</th>
	<tr></thead>"; 
	
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		
		
	
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
	
	$query_child = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users u INNER JOIN user_membership um ON u.id_user = um.user_id WHERE real_parent = '$id' LIMIT $start,$plimit ");
	while($row_child = mysqli_fetch_array($query_child))
	{
		$child_id = $row_child['id_user'];
		$username = get_user_name($child_id);
		$date = $row_child['date'];
		$name = $row_child['f_name']." ".$row_child['l_name'];
		$email = $row_child['email'];
		$date_enrolled = date('Y-m-d H:i', $row_child['time']);
		
		print "<tr>
				<td class=\"input-small\" width=200>$child_id</small></td>
				<td class=\"input-small\" width=200>$username</small></td>
				<td class=\"input-small\" width=200>$name</small></td>
				<td class=\"input-small\" width=200>$date_enrolled</small></td>";?>

				<?php 
					//Get number of referrals
					$num_referrals = get_num_referrals($row_child['id_user']);

					//Compute remaining qualification period
					$q_days = $membership_class->remaining_qualification_period($row_child['id_user'], $row_child['time']);
					//Check whether user is qualified or not
					$qualification_type = $membership_class->is_qualified($row_child['id_user'], $row_child['time']);

					printf('<td class="input-small" width=200><a href="/glc/index.php?page=direct_members&child=%d">%s</a></small></td>', $row_child['id_user'], !empty($num_referrals) ? $num_referrals : 0);
					printf('<td class="input-small" width=200>%s</small></td>', $qualification_type);
					
					printf('<td class="input-small %s" width=200>%d days(s)</small></td>', ($q_days <= 5) ? 'red' : '', $q_days);
				?>

			    <td class="text-center">
			  	<?php
				$b_b_id = get_board_break_id($child_id);
				$c = count($b_b_id);
				for($g = 0; $g < $c; $g++)
				{
				  $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board WHERE board_id = '$b_b_id[$g]' AND mode = 1");	
				  while($row = mysqli_fetch_array($query))
    			  {
					$board_id_c_1 = $row['board_id'];
					$bd_name = "first_board_name";
					$bord_name_c_1 = my_bords_name($bd_name);
		
					print "<a style=\"color:#0066BF;  text-decoration:underline;\" href=\"index.php?page=my_board_first&bbid=$board_id_c_1&u_id=$child_id\" >$bord_name_c_1</a></small>
					<br />";
				  }
				}	
				?>
			  </td>
			  <td class="text-center">
			  	<?php
				$b_b_id_2 = get_board_break_id_2($child_id);
				$m = count($b_b_id_2);
				if($m > 0)
				{
					for($g = 0; $g < $m; $g++)
					{
					  $query_2 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_second WHERE board_id = '$b_b_id_2[$g]' AND mode = 1");	
					  while($rows = mysqli_fetch_array($query_2))
					  {
						$board_id_c_2 = $rows['board_id'];
						
						$bd_name = "second_board_name";
						$bord_name_c_2 = my_bords_name($bd_name);
			
						print "<a style=\"color:#0066BF;  text-decoration:underline;\" href=\"index.php?page=my_board_second&bbid=$board_id_c_2&u_id=$child_id\" >$bord_name_c_2</a></small>
						<br />";
					  }
					}
				}	
				else
					print "<i class=\"fa fa-close\"></i>";		
				?>
			  </td>
			  <td class="text-center">
			  		<?php
				$b_b_id_3 = get_board_break_id_3($child_id);
				$n = count($b_b_id_3);
				if($n > 0)
				{
					for($g = 0; $g < $n; $g++)
					{
					  $query_3 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_third WHERE board_id = '$b_b_id_3[$g]' AND mode = 1");	
					  while($rowa = mysqli_fetch_array($query_3))
					  {
						$board_id_c_3 = $rowa['board_id'];
						
						$bd_name = "third_board_name";
						$bord_name_c_3 = my_bords_name($bd_name);
			
						print "<a style=\"color:#0066BF;  text-decoration:underline;\" href=\"index.php?page=my_board_third&bbid=$board_id_c_3&u_id=$child_id\" >$bord_name_c_3</a></small>
						<br />";
					  }
					}
				}	
				else
					print "<i class=\"fa fa-close\"></i>";		
				?>
			  </td>
			   <td class="text-center">
			  	<?php
				$b_b_id_4 = get_board_break_id_4($child_id);
				$x = count($b_b_id_4);
				if($x > 0)
				{
					for($g = 0; $g < $x; $g++)
					{
					  $query_4 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_fourth WHERE board_id = '$b_b_id_4[$g]' AND mode = 1");	
					  while($ro = mysqli_fetch_array($query_4))
					  {
						$board_id_c_4 = $ro['board_id'];
						
						$bd_name = "fourth_board_name";
						$bord_name_c_4 = my_bords_name($bd_name);
			
						print "<a style=\"color:#0066BF;  text-decoration:underline;\" href=\"index.php?page=my_board_fourth&bbid=$board_id_c_4&u_id=$child_id\" >$bord_name_c_4</a></small>
						<br />";
					  }
					}
				}	
				else
					print "<i class=\"fa fa-close\"></i>";		
				?>
			  </td>
			<td class="text-center">
			  	<?php
				$b_b_id_5 = get_board_break_id_5($child_id);
				$y = count($b_b_id_5);
				if($x > 0)
				{
					for($g = 0; $g < $y; $g++)
					{
					  $query_5 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_fifth WHERE board_id = '$b_b_id_5[$g]' AND mode = 1");	
					  while($rowaa = mysqli_fetch_array($query_5))
					  {
						$board_id_c_5 = $rowaa['board_id'];
						
						$bd_name = "five_board_name";
						$bord_name_c_5 = my_bords_name($bd_name);
			
						print "<a style=\"color:#0066BF;  text-decoration:underline;\" href=\"index.php?page=my_board_fifth&bbid=$board_id_c_5&u_id=$child_id\" >$bord_name_c_5</a></small>
						<br />";
					  }
					}
				}	
				else
					print "<i class=\"fa fa-close\"></i>";		
				?>
			  </td>			
<?php		print "</tr>";
		
	}
	print "<tr><td colspan=13>&nbsp;</td></tr><td colspan=13 height=30px width=400 class=\"message tip\"><strong>";
		if ($newp>1)
		{ ?>
			<a href="<?php echo "index.php?val=direct_members&open=4&p=".($newp-1);?>">&laquo;</a>
		<?php 
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ ?>
				<a href="<?php echo "index.php?val=direct_members&open=4&p=$i";?>"><?php print_r("$i");?></a>
				<?php 
			}
			else
			{
				 print_r("$i");
			}
		} 
		if ($newp<$pnums) 
		{ ?>
		   <a href="<?php echo "index.php?val=direct_members&open=4&p=".($newp+1);?>">&raquo;</a>
		<?php 
		} 
		print"</strong></td></tr></table>";

}	
?>
</div>
<?php

function get_last_enrollee($user_id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], sprintf('SELECT time FROM users WHERE real_parent = %d ORDER BY time DESC LIMIT 1', $user_id));
	while($row = mysqli_fetch_array($query))
	{
		$time = $row['time'];
		return $time;
	}	
}

function get_qualification_month()
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT q_time FROM setting WHERE id = 1");
	while($row = mysqli_fetch_array($query))
	{
		$q_time = $row['q_time'];
		return $q_time;
	}
}

function get_num_referrals($user_id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users u INNER JOIN user_membership um ON u.id_user = um.user_id WHERE real_parent = '$user_id' AND initial <> 1 ");
	return mysqli_num_rows($query);
}

function get_user_name($parent)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE id_user = '$parent' ");
	while($row = mysqli_fetch_array($query))
	{
		$user_name = $row['username'];
		return $user_name;
	}
}		


function get_board_break_id($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break where user_id = '$id' and level <= 1  ");
	while($r = mysqli_fetch_array($q))
	{
		$level = $r['level'];
		if($level < 2)
			$b_b_id[] = $r['board_b_id'];
	}
	return $b_b_id;
}

function get_board_break_id_2($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_second where user_id = '$id' and level <= 1  ");
	while($r = mysqli_fetch_array($q))
	{
		$level = $r['level'];
		if($level < 2)
			$b_b_id[] = $r['board_b_id'];
	}
	return $b_b_id;
}
function get_board_break_id_3($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_third where user_id = '$id' and level <= 1  ");
	while($r = mysqli_fetch_array($q))
	{
		$level = $r['level'];
		if($level < 2)
			$b_b_id[] = $r['board_b_id'];
	}
	return $b_b_id;
}
function get_board_break_id_4($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_fourth where user_id = '$id' and level <= 1  ");
	while($r = mysqli_fetch_array($q))
	{
		$level = $r['level'];
		if($level < 2)
			$b_b_id[] = $r['board_b_id'];
	}
	return $b_b_id;
}
function get_board_break_id_5($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_fifth where user_id = '$id' and level <= 1  ");
	while($r = mysqli_fetch_array($q))
	{
		$level = $r['level'];
		if($level < 2)
			$b_b_id[] = $r['board_b_id'];
	}
	return $b_b_id;
}
function get_board_break_id_6($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_sixth where user_id = '$id' and level <= 1  ");
	while($r = mysqli_fetch_array($q))
	{
		$level = $r['level'];
		if($level < 2)
			$b_b_id[] = $r['board_b_id'];
	}
	return $b_b_id;
}

function my_bords_name($nme)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select $nme from setting");
	while($r = mysqli_fetch_array($q))
	{
		$board_name = $r[$nme];
	}
	return $board_name;
}

function get_user_type($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT type FROM users WHERE id_user = '$id' ");
	$row = mysqli_fetch_array($query);
	$user_type = $row[0];	
	if($user_type == 'D')
	{
		$type = '<span class="label label-danger">Blocked</span>';
	} else {
		//Select q_time in settings table : 6months
		$row = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT q_time FROM setting limit 1"));
		$months = $row[0];

		//Deduct 6 months from current time
		$effectiveDate = strtotime("-".$months." months", time());

		//Select referrals of the user where the date registered is greater than the effective date
		$sql = sprintf('SELECT count(id_user) FROM users WHERE real_parent = %d AND type <> "F" AND time > %d', $id, $effectiveDate);

		$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		$row = mysqli_fetch_array($query);
		$num = $row[0];	
		if ($num > 1) {
			$type = '<span class="label label-primary green-qualified">Qualified</span>';
		} else {
			$type = '<span class="label label-warning yellow-non-qualified">non-Qualified</span>';
		}
	}
	return $type;
}
?>
<!-- JQUERY -->
 <script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
    $(function() {
        $('.directMembersDatatable').DataTable({
            "iDisplayLength": 100,
            responsive: true,
                "dom": 'T<"clear">lfrtip',
                "tableTools": {
                    "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
            }
        });   
    });
</script>
<!-- END JQUERY -->
