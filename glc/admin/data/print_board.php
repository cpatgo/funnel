<?php

include("../../config.php");
include("../../condition.php");
include("../../function/binary_layout/print_display.php");
include("../../function/functions.php");
 
$u_bbid = $_REQUEST[bbid];
if($u_bbid == '')
{ 
	if($u_id == '') { $id = $_SESSION['dennisn_user_id']; } else {$id = $u_id; }

	$board_info = get_board_break_id($id);
	 $level = $board_info[1];	
	$board_break_id = $board_info[0];
	$b_swow = 1;
		
	
}
else
{
	$board_break_id = $u_bbid;
	$b_swow = 1;
}		
$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board WHERE board_id = '$board_break_id' ");
		
while($row = mysqli_fetch_array($query))
{
	$bb_level = $row['level']; 
	?>
			<br /><br /><div style="color:#003D79; font-size:22px; float:left;"> <strong><?php print $bb_level+1; ?> Level Board<br /></strong></div><div style="color:#003D79; font-size:22px; float:right; padding-right:100px;">
			<form>
			<input type="button" value="Print" onClick="window.print()" />
			</form>
			</div> <br /><br /><br />
					
			<?php 
		for($i=1 ; $i <16; $i++)
		{
			$pos[$i] = $bbid = $row['pos'.$i];
			$id_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE id_user = '$bbid' ");
			while($id_row = mysqli_fetch_array($id_query))
			{
				$type = $id_row['type'];
				$position[$i] = get_user_pos($id);
				$date[$i] = $id_row['date'];
				$gender[$i] = $id_row['gender'];
				$user_names[$i] = $id_row['username'];
				$real_parent_u[$i] = get_user_name($id_row['real_parent']);
				$chld = get_qualification_summary($bbid,$bb_level,$board_break_id);
				$cnt = count($chld);
				for($k = 0 ; $k < $cnt; $k++)
				{
					$real_child[$i][$k] = $chld[$k][0];
					$real_info_time[$i][$k] = $chld[$k][1];
				}
				$img[$i] = get_img($cnt,$type,$bb_level);
				$name[$i] = $id_row['f_name']." ".$id_row['l_name']; 
				$parent_u_name[$i] = get_user_name($id_row['parent_id']);	
			}
		}
	}	
?>
<body style="background-color:#FFD9D9;">
<?php		
$page = "index.php?page=my_board";	
display($pos,$page,$img,$user_names,$parent_u_name,$name,$position,$date,$gender,$real_parent_u,$real_child,$bb_level,$real_info_time);

?>
	
</body>

<?php
function get_img($chlds,$type,$bb_level)	
{ 
	if($chlds == '1' and $type == 'B') { $imges = "o"; }
	if($chlds == '2' and $type == 'B') { $imges = "q"; }
	if($chlds == '0' and $type == 'B') { $imges = "u"; }
	if($type == 'C') { $imges = "b"; }
	
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
				$time_qur = " and time <= '$time'"; //and board_b_id = '$u_bbb_id'";
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
