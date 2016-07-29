<?php
session_start();

require_once("config.php");
include("condition.php");
include("function/binary_layout/display.php");
include("function/functions.php");
include("function/setting.php");

$u_bbid = $_GET['bbid'];

//DISPLAY BOARD HISTORY
if(isset($u_bbid) && !empty($u_bbid)):
	$b_swow = 1;
	$step3 = $u_bbid;
	$step2 = step2($step3);
	$step1 = step1($step2);

	//SHOW STEP 1
	$board_break_id = $step1;
	if(!empty($board_break_id)):
		printf('<h3 class="no-margins space-above steps">STEP #1:<br>Board ID #%d</h3>', $board_break_id);
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_third WHERE board_id = '$board_break_id' ");
		while($row = mysqli_fetch_array($query))
		{
			$bb_level = $row['level']; 
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

		$brdimg = "board_third";		
		$page = "index.php?val=my_board&open=4";	
		display($poss,$page,$img,$user_name,$parent_u_name,$name,$position,$date,$gender,$real_parent_u,$real_child,$bb_level,$real_info_time,$comming_board_username,$real_parent_u,$brdimg);

		?>
		<div >
			<div id="popup">
			</div>
		</div>
		<?php
	endif;

	//SHOW STEP 2
	$board_break_id = $step2;
	if(!empty($board_break_id)):
		printf('<h3 class="no-margins space-above steps">STEP #2:<br>Board ID #%d</h3>', $board_break_id);
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_third WHERE board_id = '$board_break_id' ");
		while($row = mysqli_fetch_array($query))
		{
			$bb_level = $row['level']; 
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

		$brdimg = "board_third";		
		$page = "index.php?val=my_board&open=4";	
		display($poss,$page,$img,$user_name,$parent_u_name,$name,$position,$date,$gender,$real_parent_u,$real_child,$bb_level,$real_info_time,$comming_board_username,$real_parent_u,$brdimg);

		?>
		<div >
			<div id="popup">
			</div>
		</div>
		<?php
	endif;

	//SHOW STEP 3
	$board_break_id = $step3;
	if(!empty($board_break_id )):
		printf('<h3 class="no-margins space-above steps">STEP #3:<br>Board ID #%d</h3>', $board_break_id);
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_third WHERE board_id = '$board_break_id' ");
		while($row = mysqli_fetch_array($query))
		{
			$bb_level = $row['level']; 
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

		$brdimg = "board_third";		
		$page = "index.php?val=my_board&open=4";	
		display($poss,$page,$img,$user_name,$parent_u_name,$name,$position,$date,$gender,$real_parent_u,$real_child,$bb_level,$real_info_time,$comming_board_username,$real_parent_u,$brdimg);

		?>
		<div >
			<div id="popup">
			</div>
		</div>
		<?php
	endif;
else:
//DISPLAY BOARD HISTORY
	$id = $_SESSION['dennisn_user_id'];
	$b_b_id = get_board_break_ids($id);
	?>
	<div class="ibox-content">		
	<table class="table table-bordered">
		<thead>
		<tr>
			<th class="text-center"><?=$PayCycleNo?></th>
			<th class="text-center"><?=$PayCycle;?></th>
			<th class="text-center"><?=$Date;?></th>
		</tr>
		</thead>
		<tbody> 
	<?php
	$c = count($b_b_id);

	if($c < 1):
		?>
		<tr align=center>
			<td>No history.</th>
			<td>No history.</td>
			<td>No history.</th>
		</tr>
		<?php
	endif;
		
	for($g = 0; $g < $c; $g++)
	{
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_third WHERE board_id = '$b_b_id[$g]' AND mode = 0 ");
			while($row = mysqli_fetch_array($query))
			{
				$board_id = $row['board_id'];
				$date = $row['date'];
				$level = $row['level'];
				
				$bd_name = "third_board_name";
				$bord_name_h_3 = my_bords_name($bd_name);
				$no_b = $g+1;

				print "
					<tr align=center>
						<td>$no_b</td>
						<td><a style=\"color:#0066BF;  text-decoration:underline;\" href=\"index.php?page=summary_third&bbid=$board_id\" >ID# $board_id </a>
						</td>
						<td>$date</td>
					</tr>";
			}
			
	}
	print "</tbody></table></div>";
endif;

function get_board_break_ids($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], sprintf("select * from board_third where pos1 = %d and mode = 0", $id));
	while($r = mysqli_fetch_array($q))
	{
		$b_b_id[] = $r['board_id'];
	}
	return $b_b_id;
}

function step1($board_id)
{
	$user_id = get_user_id($board_id);
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_third where user_id = '$user_id' and id < (select id from board_break_third where board_b_id = '$board_id' and user_id = '$user_id') order by id desc limit 1");
	while($r = mysqli_fetch_array($q))
	{
		return $r['board_b_id'];
	}
}

function step2($board_id)
{
	$user_id = get_user_id($board_id);
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_third where user_id = '$user_id' and id < (select id from board_break_third where board_b_id = '$board_id' and user_id = '$user_id') order by id desc limit 1");
	while($r = mysqli_fetch_array($q))
	{
		return $r['board_b_id'];
	}
}

function get_user_id($board_id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_third where board_id = '$board_id'");
	while($r = mysqli_fetch_array($q))
	{
		return $r['pos1'];	
	}
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

function get_img($type)	
{ 
	if($type == 'B') { $imges = "b"; }
	if($type == 'C') { $imges = "d"; }
	return $imges;
}

function get_board_break_id($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_third where user_id = '$id' order by id desc limit 1 ");
	while($r = mysqli_fetch_array($q))
	{
		$b_b_id[0] = $r['board_b_id'];
		$b_b_id[1] = $r['level'];
	}
	return $b_b_id;
}

function get_send_level_board($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_third where user_id = '$id'  order by id desc limit 1 ");
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
		$qur = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_third where user_id = '$board_break_id' and level = '$level' ");
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
		$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_third where board_id = '$u_bbb_id' ");
		while($rrr = mysqli_fetch_array($q))
		{
			$time = $rrr['time'];
			$mode = $rrr['mode'];
			if($mode == 0)
				$time_qur = " and time <= '$time'"; //and board_b_id = '$u_bbb_id'";
			else
				$time_qur = "";	
		}	
			
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_break_third WHERE qualified_id = '$id' and level = '$level' $time_qur group by user_id  order by time ");
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
	$qur = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_third where board_id = '$u_bbid' ");
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
			$qur = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_third where board_id = '$brd_id[0]' ");
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
	
		
		
		
