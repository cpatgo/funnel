<?php
session_start();

include("condition.php");
require_once("../config.php");
require_once("../function/setting.php");
require_once("../function/functions.php");
include("../function/binary_layout/display.php");

$newp = $_GET['p'];
$plimit = "15";



if(isset($_POST['show_brd']))
{
	$board_id = $_POST['board_id'];
	$board_type = $_POST['board_type'];
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM ".$board_type." WHERE board_id = '$board_id' ");
	while($row = mysqli_fetch_array($query))
	{
		$bb_level = $row['level']; 
		?>
			
			<div style="float:right; padding-right:0px; padding-right:50px;">
			<form action="data/print_board.php" target="_new" method="post">
			<input type="hidden" name="bbid" value="<?php print $board_id; ?>"  />
			<input type="submit" name="Submit" class="button" value="Print" />
			</form>
			</div>
				 <a href="index.php?page=live_board"><img src="images/ip_icon_02_Back1.png" style="height:50px; width:50px" /></a><br />
			<?php 
		for($i = 1; $i < 8; $i++)
		{
			$pos[$i] = $bbid = $row['pos'.$i];
			$id_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE id_user = '$bbid' ");
			while($id_row = mysqli_fetch_array($id_query))
			{
				$type = $id_row['type'];
				$position[$i] = get_user_pos($id);
				$date[$i] = $id_row['date'];
				$gender[$i] = $id_row['gender'];
				$user_name[$i] = $id_row['username'];
				$real_parent_u[$i] = get_user_name($id_row['real_parent']);
				$chld = get_qualification_summary($bbid,$bb_level,$board_id);
				$cnt = count($chld);
				for($k = 0 ; $k < $cnt; $k++)
				{
					$real_child[$i][$k] = $chld[$k][0];
					$real_info_time[$i][$k] = $chld[$k][1];
				}
				$img[$i] = get_img($type);
				$name[$i] = $id_row['f_name']." ".$id_row['l_name']; 
				$parent_u_name[$i] = get_user_name($id_row['parent_id']);	
			}
		}
	}		
	
	$page = "index.php?page=live_board";
	display($pos,$page,$img,$user_name,$parent_u_name,$name,$position,$date,$gender,$real_parent_u,$real_child,$bb_level,$real_info_time,$inserting_board,$rt,$board_type);
}
elseif(isset($_POST['submit']) or isset($newp) or isset($_POST['Search']))
{
	if(isset($_POST['Search']))
	{	
		$_SESSION['search_username'] = get_new_user_id($_POST['username']);
		$session_sql = " and pos1 = ".$_SESSION['search_username'];
	}	
	elseif( isset($newp) and $_SESSION['search_username'] != '' )
		$session_sql = " and pos1 = ".$_SESSION['search_username'];
	else
		unset($_SESSION['search_username']);	

	if(isset($_POST['submit']))
		$board_level = $_SESSION['session_search_level'] = $_POST['search_level'];
	else
		$board_level = $_SESSION['session_search_level'];
				
	if($board_level == 'board')
		$board_name = "SEED ";
	if($board_level == 'board_second')
		$board_name = "SPROUT ";
	if($board_level == 'board_third')
		$board_name = "SAPLING ";
	if($board_level == 'board_fourth')
		$board_name = "OAK ";
	if($board_level == 'board_fifth')
		$board_name = "MATURE ";
	if($board_level == 'board_sixth')
		$board_name = "HARVEST ";					

	$sql = "SELECT * FROM ".$board_level." WHERE mode = 0 $session_sql order by date desc";
	$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
	$totalrows = mysqli_num_rows($query);
	if($totalrows == 0)
	{
		echo "There is no information to show!"; 
	}
	else {
	print "<table hspace = 0 cellspacing=0 cellpadding=0 border=0 width=900>
			<tr>
				<th colspan=4 align='right' >
				<form action=\"\" method=\"post\">
				Enter Username <input type=\"text\" name=\"username\"  />
				<input type=\"submit\" name=\"Search\" class=\"button\" value=\"Search\" />
				</form>
				</th>
			</tr>
			<tr><th colspan=4 height=10 ></th></tr>
			
			<tr><th colspan=2 height=30 class=\"message tip\">Total Board Break :</th>
			<th colspan=2 class=\"message tip\">$totalrows Boards</th></tr>
			<tr><th colspan=2>&nbsp;</th></tr>
			<tr>
			<th height=30 class=\"message tip\">Board</th>
			<th height=30 class=\"message tip\">Board Name</th>
			<th height=30 class=\"message tip\">Top Member</th>
			<th height=30 class=\"message tip\">Date</th>
			</tr>"; 
		
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		if ($totalrows - $start < $plimit) { $end_count = $totalrows;
		} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
		} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
		 	
		$lvl_brd = $board_level+1;
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "$sql LIMIT $start,$plimit ");		
		while($id_row = mysqli_fetch_array($query))
		{
			$board_id = $id_row['board_id'];
			$top_username = get_user_name($id_row['pos1']);
			$username = get_user_name($id);
			$date = $id_row['date'];
			
?>
			<tr>
			<form action="index.php?page=live_board" method="post">
				<td class="input-medium" style="text-align:left; padding-left:20px;">
				<input type="hidden" name="board_id" value="<?php print $board_id; ?>"  />
				<input type="hidden" name="board_type" value="<?php print $board_level; ?>" />
				<input type="submit" name="show_brd" style="color:#0066BF; background:none; border:none; text-decoration:underline;" value="<?php print $top_username; ?>"  /></td>
				<td class="input-medium" style="text-align:left; padding-left:20px;"><?php print $board_name; ?></td>
				<td class="input-medium" style="text-align:left; padding-left:20px;"><?php print $top_username; ?></td>
				<td class="input-medium" style="text-align:center;"><?php print $date; ?></td>
			</form>	
			</tr>
<?php		}
		print "<tr><td colspan=5>&nbsp;</td></tr><td colspan=5 height=30px width=400 class=\"message tip\" style=\"text-align:left; padding-left:20px;\"><strong>";
		if ($newp>1)
		{ ?>
			<a href="<?php echo "index.php?page=live_board&p=".($newp-1);?>">&laquo;</a>
		<?php 
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ ?>
				<a href="<?php echo "index.php?page=live_board&p=$i";?>"><?php print_r("$i");?></a>
				<?php 
			}
			else
			{
				 print_r("$i");
			}
		} 
		if ($newp<$pnums) 
		{ ?>
		   <a href="<?php echo "index.php?page=live_board&p=".($newp+1);?>">&raquo;</a>
		<?php 
		} 	
		print "</table>";
	}
}

else
{ unset($_SESSION['search_username']); 
?> <center>
	<table width="500" border="0">
	<form action="index.php?page=live_board" method="post">
  <tr>
    <td width="170" style="font-size:14px;"><strong>Select Board</strong></td>
    <td>
		<select name="search_level">
			<option value="board"><?php print $setting_board_name[1]; ?></option>
			<option value="board_second"><?php print $setting_board_name[2]; ?></option>
			<option value="board_third"><?php print $setting_board_name[3]; ?></option>
			<option value="board_fourth"><?php print $setting_board_name[4]; ?></option>
			<option value="board_fifth"><?php print $setting_board_name[5]; ?></option>
		</select>	
	</td>
	</tr>
  <tr>
	<td colspan="2" height="80"><input type="submit" name="submit" value="Show" class="button" /></td>
  </tr>
  </form>
</table>

<?php
}


function get_img($type)	
{ 
	if($type == 'B') { $imges = "b"; }
	if($type == 'C') { $imges = "d"; }
	return $imges;
}
	
			

function get_board_break_id($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break where user_id = '$id' order by id desc limit 1 ");
	while($r = mysqli_fetch_array($q))
	{
		$b_b_id[0] = $r['board_b_id'];
		$b_b_id[1] = $r['level'];
	}
	return $b_b_id;
}

function get_send_level_board($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break where user_id = '$id'  order by id desc limit 1 ");
	while($r = mysqli_fetch_array($q))
	{
		$second_level_board = $r['board_b_id'];
	}
	return $second_level_board;
}

function get_qualification_summary($id,$level,$u_bbb_id)
{
	$h = 0;
	if($id != 0)
	{
		$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board where board_id = '$u_bbb_id' ");
		while($rrr = mysqli_fetch_array($q))
		{
			$time = $rrr['time'];
			$mode = $rrr['mode'];
			if($mode == 0)
				$time_qur = "and time <= '$time'";
			else
				$time_qur = "";	
		}	
			
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_break WHERE qualified_id = '$id' and level = '$level' $time_qur group by user_id  order by time ");
		
		while($row = mysqli_fetch_array($query))
		{
			$username_id = $row['user_id'];
			$username[$h][0] = get_user_name($username_id);
			$username[$h][1] = $row['time'];
			$h++;
		}
	}
	return $username;	
}	

?>