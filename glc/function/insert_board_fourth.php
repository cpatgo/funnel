<?php

//include("../config.php");
//session_start();  
 
function insert_into_board_fourth($user_id,$user_real_pp,$spill,$plc_user_id)  
{
	$new_id[0] = $user_id;

	$board_b_idds = find_board_fourth($plc_user_id);
	if($board_b_idds[0] > 0)
	{
		$find_brd_id = $board_b_idds[0];
		$findqualified_id = $board_b_idds[1];
		$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_fourth where mode = 1 and board_id = '$find_brd_id' ");
		while($row = mysqli_fetch_array($q))
		{
			$board_user_id = $row['pos1'];
		}
		update_board_fourth($board_user_id,$new_id,$spill,$plc_user_id,$findqualified_id);
		board_break_fourth($board_user_id);
	}
	else
	{
		insert_board_fourth($user_id);
	}	


	$cnt = count($_SESSION['board_fourth_breal_id']);
	for($j = 0; $j < $cnt; $j++)
	{ 
	$result[$j][0] = $_SESSION['board_fourth_breal_id'][$j][0];
	$result[$j][1] = $_SESSION['board_fourth_breal_id'][$j][1]; 
	}
	return $result;
}


function get_blank_position_fourth($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * from board_fourth where  pos1 = '$id' and mode = 1 ");
	while($row = mysqli_fetch_array($query))
	{		
		for($i = 1; $i < 8; $i++)
		{		
			$arr = $row['pos'.$i];
			if($arr==0)	
			{
				$k=$i;
				return $k;
			}
		}
	}
}

function get_blank_position_by_id_fourth($bbi)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * from board_fourth where  board_id = '$bbi' and mode = 1 ");
	while($row = mysqli_fetch_array($query))
	{		
		for($i = 1; $i < 8; $i++)
		{		
			$arr = $row['pos'.$i];
			if($arr==0)	
			{
				$k=$i;
				return $k;
			}
		}
	}
}

function update_board_fourth($parents_id,$user_id,$spill,$plc_user_id,$brd_qualifid_id) 
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_fourth where pos1 = '$parents_id' and mode = 1 ");
	while($row = mysqli_fetch_array($q))
	{
		$board_id = $row['board_id'];
	}
	$date = date('Y-m-d');
	$cnt = count($user_id);
	for($i = 0; $i < $cnt; $i++)
	{
		$pos = get_blank_position_fourth($parents_id);
		mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE board_fourth SET pos".$pos."=".$user_id[$i]." , date = '$date' where pos1 = ".$parents_id." and mode = 1 ");


		$log_username = get_user_name($user_id[$i]);
		include("logs_messages.php");
		data_logs($user_id[$i],$data_log[15][0],$data_log[15][1],$log_type[15]);

		insert_into_board_break_fourth($user_id[$i],$board_id,$spill,$plc_user_id,$brd_qualifid_id);
	}
}		

function insert_board_fourth($user_id)
{
	$date = date('Y-m-d');
	$mode = 1;
	$time = 0;
	$parent_id = get_parent_fourth($user_id);
		
		mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO board_fourth (pos1, date, time, mode , parent_id) VALUES ('$user_id' ,  '$date' , '$time' , '$mode' , '$parent_id' )");
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_fourth where pos1 = '$user_id' and mode = 1 ");
	while($row = mysqli_fetch_array($q))
	{
		$board_id = $row['board_id'];
	}
	
	$log_username = get_user_name($user_id);
	include("logs_messages.php");
	data_logs($user_id,$data_log[15][0],$data_log[15][1],$log_type[15]);
	$spill = 0;
	insert_into_board_break_fourth($user_id,$board_id,$spill,0,0);
}

function board_break_fourth($board_user_id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * from board_fourth where pos1 = '$board_user_id' and mode = 1 ");
	while($row = mysqli_fetch_array($query))
	{
		$pos7 = $row['pos7'];
		$break_board_parent = $row['parent_id'];
		$break_board_table_id = $row['board_id'];
	}	
	if($pos7 != 0)
	{
		$time = time();
		mysqli_query($GLOBALS["___mysqli_ston"], "update board_fourth set time = '$time' WHERE pos1 = '$board_user_id' and mode = 1 ");
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * from board_fourth where pos1 = '$board_user_id' and mode = 1 ");
		while($row = mysqli_fetch_array($query))
		{
			$board_b_id = $row['board_id'];
			for($board_position = 1; $board_position < 8; $board_position++)
			{
				$board_position_old[$board_position][0] = $pos_id = $row['pos'.$board_position]; 
			}
		}

		$new_board_id = $board_position_old[1][0];
		
		$new_board_pos_id[] = $board_position_old[2][0];
		$new_board_pos_id[] = $board_position_old[3][0];
		
		$child_1[] = $board_position_old[4][0];
		$child_1[] = $board_position_old[5][0];
				
		$child_2[] = $board_position_old[6][0];
		$child_2[] = $board_position_old[7][0];
		
		mysqli_query($GLOBALS["___mysqli_ston"], "update board_fourth set mode = 0 where board_id = '$break_board_table_id' and mode = 1 ");
		
		$b_c = count($_SESSION['board_fourth_breal_id']);
		$_SESSION['board_fourth_breal_id'][$b_c][0] = $new_board_id;
		
		for($i = 0; $i < 2; $i++)
		{
			$spill = 0;
			insert_board_fourth($new_board_pos_id[$i]);

			//Send email to user if the user has not enrolled 2 users yet
			send_step_2_email($new_board_pos_id[$i]);
			//Give partial commission if the user is qualified
			second_step_commission($new_board_pos_id[$i], 4);

			if($i == 0) 
			{ 
				$brd_real_prnt = get_user_real_par($new_board_pos_id[$i]);	
				update_board_fourth($new_board_pos_id[$i],$child_1,$spill,$brd_real_prnt,0); 
			}
			if($i == 1) 
			{
				$brd_real_prnt = get_user_real_par($new_board_pos_id[$i]);
				update_board_fourth($new_board_pos_id[$i],$child_2,$spill,$brd_real_prnt,0); 
			}
		}
		$brd_real_prnt = get_user_real_par($new_board_id);
		insert_into_board_fourth($new_board_id,$brd_real_prnt,0,$brd_real_prnt);	
	}
}

function get_parent_fourth($user_id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE id_user = '$user_id' ");
	while($row = mysqli_fetch_array($query))
	{
		$par = $row['parent_id'];
	}
	return $par;	
}	


function insert_into_board_break_fourth($user_id,$b_id,$spill,$real_parent,$brd_qualifid_id)
{
	$date = date('Y-m-d');
	$t = time();
	$real_p = get_user_real_par($user_id);
	mysqli_query($GLOBALS["___mysqli_ston"], "insert into board_break_fourth (user_id , real_parent , board_b_id , date , time) values ('$user_id' , '$real_p' , '$b_id' , '$date' , '$t') ");
}



function get_virtual_parent_by_will_fourth($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where id_user = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$parent_id = $row['parent_id'];
	}
	return $parent_id;
} 

