<?php

require_once("config.php");
include("condition.php");
include("function/binary_layout/display.php");
include("function/functions.php");
include("function/setting.php");
 
$u_bbid = (isset($_REQUEST['bbid']) && !empty($_REQUEST['bbid'])) ? $_REQUEST['bbid'] : '';
$u_id = (isset($_REQUEST['u_id']) && !empty($_REQUEST['u_id'])) ? $_REQUEST['u_id'] : '';

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
	if($u_id == '') { $id = $_SESSION['dennisn_user_id']; } else {$id = $u_id; }
	$board_break_id = $u_bbid;
	$b_swow = 1;
}		

//GET CURRENT STAGE
$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_second WHERE board_id = '$board_break_id' ");
while($row = mysqli_fetch_array($query)){
	if($row['pos1'] == $id):
		$stage = 3;
	elseif($row['pos2'] == $id || $row['pos3'] == $id):
	 	$stage = 2;
	else:
		$stage = 1;
	endif;
}
printf('<h3 class="no-margins space-above">Step #%d<br>Board ID #%d</h3>', $stage, $board_break_id);

//DISPLAY STAGE POSITIONS
$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_second WHERE board_id = '$board_break_id' ");
while($row = mysqli_fetch_array($query))
{
	$bb_level = $row['level']; 
	?>
			<font size="+2" color="#003D79"> <strong><?php print $board_names[$bb_level+1]; ?><br /></strong></font>
				<div>
					<!--<div style="float:left; width:200px;"> <a href="index.php?page=my_board_third"><img src="images/Back.png" style="height:50px; width:50px" /></a></div>-->
					<div style="position: absolute;top:0;right:0">
					<form action="data/print_board.php" target="_new" method="post">
					<input type="hidden" name="bbid" value="<?php print $board_break_id; ?>"  />
					<input type="submit" name="Submit" class="btn btn-primary" value="Print" />
					</form>
					</div>
				</div>	
			<?php 
		for($i=1 ; $i <16; $i++)
		{
			$poss[$i] = $bbid = $row['pos'.$i];
			$id_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE id_user = '$bbid' ");
			while($id_row = mysqli_fetch_array($id_query))
			{
				$type = $id_row['type'];
				$position[$i] = get_user_pos($id);
				$date[$i] = $id_row['date'];
				$gender[$i] = $id_row['gender'];
				$user_name[$i] = $id_row['username'];
				$real_parent_u[$i] = get_user_name($id_row['real_parent']);
				$chld = get_qualification_summary($bbid,$bb_level,$board_break_id);
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
		
$comming_board_username = get_comming_board_username($poss[1],$board_break_id);	
		
$brdimg = "board_second";		
$page = "index.php?val=my_board&open=4";	
display($poss,$page,$img,$user_name,$parent_u_name,$name,$position,$date,$gender,$real_parent_u,$real_child,$bb_level,$real_info_time,$comming_board_username,$real_parent_u,$brdimg);

?>
	
 <div >
	<div id="popup">
		
	</div>
 </div>
 
<?php
function get_img($type)	
{ 
	if($type == 'B') { $imges = "b"; }
	if($type == 'C') { $imges = "d"; }
	return $imges;
}

function get_board_break_id($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_second where user_id = '$id' order by id desc limit 1 ");
	while($r = mysqli_fetch_array($q))
	{
		$b_b_id[0] = $r['board_b_id'];
		$b_b_id[1] = $r['level'];
	}
	return $b_b_id;
}

function get_send_level_board($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_second where user_id = '$id'  order by id desc limit 1 ");
	while($r = mysqli_fetch_array($q))
	{
		$second_level_board = $r['board_b_id'];
	}
	return $second_level_board;
}


function get_next_inserting_board($user_id,$level)
{
	$board_break_id = $user_id;
	$result = 0;
	do
	{
		$board_break_id = get_user_real_par($board_break_id);
		$qur = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_second where user_id = '$board_break_id' and level = '$level' ");
		$chk_num = mysqli_num_rows($qur);
		$result = $board_break_id;		
	}
	while($chk_num == 0 and $result > 0);
	return $result;		
}

function get_user_real_par($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where id_user = '$id';");
	while($row = mysqli_fetch_array($q))
	{
		$real_parent = $row['real_parent'];
	}
	return $real_parent;
}


function get_qualification_summary($id,$level,$u_bbb_id)
{
	$h = 0; 
	if($id != 0)
	{
		$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_second where board_id = '$u_bbb_id' ");
		while($rrr = mysqli_fetch_array($q))
		{
			$time = $rrr['time'];
			$mode = $rrr['mode'];
			if($mode == 0)
				$time_qur = " and time <= '$time'"; //and board_b_id = '$u_bbb_id'";
			else
				$time_qur = "";	
		}	
			
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_break_second WHERE qualified_id = '$id' and level = '$level' $time_qur group by user_id  order by time ");
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

function get_comming_board_username($user_id,$u_bbid)
{
	$ki = $k = 1;
	$qur = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_second where board_id = '$u_bbid' ");
	while($row = mysqli_fetch_array($qur))
	{
		$i = 1;
		do
		{
			$member_id = $row['pos'.$i];
			if($member_id > 0) 
			{	$parent_arr[$ki]= $row['pos'.$i];
				$ki++;
			}
			$i++;	
		}while($i < 16 or $member_id > 0);	
	}

	for($i = 1; $i < count($parent_arr); $i++)
	{
		$qu = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where real_parent = '$parent_arr[$i]' ");
		while($rrrrrr = mysqli_fetch_array($qu))
		{
			$child_id = $rrrrrr['id_user'];
			$brd_id = get_board_break_id($child_id);
			$qur = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_second where board_id = '$brd_id[0]' ");
			while($row = mysqli_fetch_array($qur))
			{
				for($i = 1; $i < 16; $i++)
				{
					$member_id = $row['pos'.$i];
					if($child_id == $member_id)
					{
						if(count($comming_id) == 4)
						{
							for($j = 1; $j <= 4; $j++)
							{
								if($comming_pos[$j] < $i)
								{
									$comming_id[$j] = $member_id;
									$comming_pos[$j] = $i;
								}	
							}	
						}
						else
						{
							$comming_id[$k] = $member_id;
							$comming_pos[$k] = $i;
							$k++;
						}	
					}	
				}
			}
		}		
	}
	return $comming_id;
}

?> 
