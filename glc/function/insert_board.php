<?php

//include("../config.php");
//session_start();  
 
function insert_into_board($user_id,$user_real_pp,$spill,$plc_user_id)  
{
	$new_id[0] = $user_id;

	$board_b_idds = find_board($plc_user_id);
	if($board_b_idds[0] > 0)
	{
		$find_brd_id = $board_b_idds[0];
		$findqualified_id = $board_b_idds[1];
		$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board where mode = 1 and board_id = '$find_brd_id' ");
		while($row = mysqli_fetch_array($q))
		{
			$board_user_id = $row['pos1'];
		}
		update_board($board_user_id,$new_id,$spill,$plc_user_id);
		board_break($board_user_id);
	}
	else
	{
		insert_board($user_id);
	}	


	$cnt = count($_SESSION['board_breal_id']);
	for($j = 0; $j < $cnt; $j++)
	{ 
	$result[$j][0] = $_SESSION['board_breal_id'][$j][0];
	$result[$j][1] = $_SESSION['board_breal_id'][$j][1]; 
	}
	return $result;
}


function get_blank_position($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board  WHERE  pos1 = '$id' and mode = 1 ");
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

function get_blank_position_by_id($bbi)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board  WHERE  board_id = '$bbi' and mode = 1 ");
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

function update_board($parents_id,$user_id,$spill,$plc_user_id) //,$from,$data_log,$type_data
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board where pos1 = '$parents_id' and mode = 1 ");
	while($row = mysqli_fetch_array($q))
	{
		$board_id = $row['board_id'];
	}
	$date = date('Y-m-d');
	$cnt = count($user_id);
	for($i = 0; $i < $cnt; $i++)
	{
		$pos = get_blank_position($parents_id);
		$sql = "UPDATE board SET pos".$pos."=".$user_id[$i]." , date = '$date' where pos1 = ".$parents_id." and mode = 1 ";
		mysqli_query($GLOBALS["___mysqli_ston"], $sql);

		$log_username = get_user_name($user_id[$i]);
		include("logs_messages.php");
		//data_logs($user_id[$i],$data_log[15][0],$data_log[15][1],$log_type[15]);

		insert_into_board_break($user_id[$i],$board_id);
	}
}		

function insert_board($user_id) //,$from,$data_log,$type_data
{
	//$date = date('Y-m-d');
	$date = time();
	$mode = 1;
	$time = 0;
	$parent_id = get_parent($user_id);
	$sql = "INSERT INTO board (pos1, date, time, mode , parent_id) VALUES ('$user_id' , '$date' , '$time' , '$mode' , '$parent_id' )";	
	mysqli_query($GLOBALS["___mysqli_ston"], $sql);
	//data_logs($from,$data_log[15][0],$data_log[15][1],$type_data[0]);
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board where pos1 = '$user_id' and mode = 1 ");
	while($row = mysqli_fetch_array($q))
	{
		$board_id = $row['board_id'];
	}

	$log_username = get_user_name($user_id);
	include("logs_messages.php");
	//data_logs($user_id,$data_log[15][0],$data_log[15][1],$log_type[15]);
	$spill = 0;
	insert_into_board_break($user_id,$board_id);
}

function board_break($board_user_id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board WHERE pos1 = '$board_user_id' and mode = 1 ");
	while($row = mysqli_fetch_array($query))
	{
		$pos7 = $row['pos7'];
		$break_board_parent = $row['parent_id'];
		$break_board_table_id = $row['board_id'];
	}	
	if($pos7 != 0)
	{
		$time = time();
		$sql = "update board set time = '$time' WHERE pos1 = '$board_user_id' and mode = 1 ";
		mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board WHERE pos1 = '$board_user_id' and mode = 1 ");
		while($row = mysqli_fetch_array($query))
		{
			$board_b_id = $row['board_id'];
			for($board_position = 1; $board_position < 8; $board_position++)
			{
				$board_position_old[$board_position][0] = $pos_id = $row['pos'.$board_position]; 
				$board_position_old[$board_position][1] = $qualification_infos[1];
				$board_position_old[$board_position][2] = $qualification_infos[0];
			}
		}

		$new_board_id = $board_position_old[1][0];
		
		$new_board_pos_id[] = $board_position_old[2][0];
		$new_board_pos_id[] = $board_position_old[3][0];
		
		$child_1[] = $board_position_old[4][0];
		$child_1[] = $board_position_old[5][0];
				
		$child_2[] = $board_position_old[6][0];
		$child_2[] = $board_position_old[7][0];
		
		$sql = "update board set mode = 0 where board_id = '$break_board_table_id' and mode = 1 ";
		mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		
		$b_c = count($_SESSION['board_breal_id']);
		$_SESSION['board_breal_id'][$b_c][0] = $new_board_id;
		
		

		for($i = 0; $i < 2; $i++)
		{
			$spill = 0;
			insert_board($new_board_pos_id[$i]);
			
			//Send email to user if the user has not enrolled 2 users yet
			send_step_2_email($new_board_pos_id[$i]);
			//Give partial commission if the user is qualified
			second_step_commission($new_board_pos_id[$i], 1);

			if($i == 0) 
			{ 
				$brd_real_prnt = get_user_real_par($new_board_pos_id[$i]);	
				update_board($new_board_pos_id[$i],$child_1,$spill,$brd_real_prnt); 
			}
			if($i == 1) 
			{
				$brd_real_prnt = get_user_real_par($new_board_pos_id[$i]);
				update_board($new_board_pos_id[$i],$child_2,$spill,$brd_real_prnt); 
			}
		}
		
		$brd_real_prnt = get_user_real_par($new_board_id); 
		insert_into_board($new_board_id,$brd_real_prnt,0,$brd_real_prnt);	
	}
}

function send_step_2_email($user_id, $board_id)
{
	require_once(dirname(dirname(__FILE__))."/config.php");
	$user_class = getInstance('Class_User');
	$email_class = getInstance('Class_Email');

	//Send email if the user has not enrolled 2 users yet
	$affiliates = $user_class->get_affiliates($user_id);
	if(count($affiliates) < 2):
		$user = $user_class->get_user($user_id);
		$email_class->icontact_stage2_lack_enrollee($user[0]);
	endif;
	return true;
}

function second_step_commission($user_id, $level)
{
	require_once(dirname(dirname(__FILE__))."/config.php");
	$user_class = getInstance('Class_User');
	$email_class = getInstance('Class_Email');
	$income_class = getInstance('Class_Income');
	$membership_class = getInstance('Class_Membership');

	$user = $user_class->get_user($user_id);
	$user = $user[0];
	$is_qualified = $membership_class->is_qualified($user_id, $user['time'], true);
	$is_advancing = $membership_class->is_advancing($user_id, $level);

	$time = time();
	$other_type = ""; $other = 0;
	$income = glc_option(sprintf('second_step_income_%s', $level));

	//If user is not qualified or is blocked, forfeit the commission
	if($user['type'] === 'D' || $user['type'] === 'C'):
		$other = $income;
    	$income = 0;
		$other_type = "blocked member";
	elseif($is_advancing):
		$other = $income;
        $income = 0;
		$other_type = "advanced comm";
	elseif(!$is_qualified):
		$other = $income;
    	$income = 0;
		$other_type = "less than 2 qp";
	endif;

	//If user is qualified, give partial commission
	$income_data = array(
		'user_id' 		=> $user_id,
		'amount' 		=> $income,
		'other' 		=> $other,
		'other_type'	=> $other_type,
		'reenter' 		=> 0,
		'co_comm' 		=> 0,
		'admin_tax' 	=> 0,
		'left_income' 	=> 0,
		'type' 			=> 3,
		'date' 			=> date('Y-m-d', $time),
		'time' 			=> $time,
		'level'			=> $level,
		'board_type'	=> 2,
		'approved' 		=> 0
	);
	$income_id = $income_class->second_step_commission($income_data);

	//Save relationship to income relation table. This will relate the 2nd and 3rd commission in income table
	$income_relation_data = array(
		'second_step_income_id' => $income_id['message'],
		'third_step_income_id' 	=> 0,
		'user_id' 				=> $user_id,
		'level'					=> $level
	);
	$income_class->income_relation($income_relation_data);

	//If income is not forfeited, do rolling reserve and update income relation
	if($income > 0 && $other_type === ""):
		//Deduct a rolling reserve on 2nd step commission
		rolling_reserve($income_id['message'], glc_option(sprintf('second_step_income_%s', $level)), date('Y-m-d H:i:s', $time));

		//Send email to user about completed cycle
        send_step2_cycle_completed_email($user_id, $level);
	endif;
	return true;
}

function send_step2_cycle_completed_email($user_id, $level)
{
    require_once(dirname(dirname(__FILE__))."/config.php");

    $email_class = getInstance('Class_Email');
    $user_class = getInstance('Class_User');

    $user = $user_class->get_user($user_id);
    $email_class->icontact_step2_cycle_completed($user[0], $level);
    return true;
}

function get_parent($user_id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE id_user = '$user_id' ");
	while($row = mysqli_fetch_array($query))
	{
		$par = $row['parent_id'];
	}
	return $par;	
}	
		
function get_user_id($username)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE username = '$username' ");
	while($row = mysqli_fetch_array($query))
	{
		$id_user = $row['id_user'];
	}
	return $id_user;	
}

function get_user_real_par($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where id_user = '$id' ");
	while($row = mysqli_fetch_array($q))
	{
		$real_parent = $row['real_parent'];
	}
	return $real_parent;
}


function insert_into_board_break($user_id,$b_id)
{
	$date = date('Y-m-d');
	$t = time();
	$real_p = get_user_real_par($user_id);
	$sql = "insert into board_break (user_id , real_parent , board_b_id , date , time) values ('$user_id' , '$real_p' , '$b_id' , '$date' , '$t') ";
	mysqli_query($GLOBALS["___mysqli_ston"], $sql);
}

function get_current_total_child($id)
{
	if($id != 0)
	{
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_break WHERE qualified_id = '$id' group by user_id ");
		$row1 = mysqli_num_rows($query);
		return $row1;	
	}
}


function voucher_distribution($user_id)
{
	$date = date('Y-m-d');
	$tvi_type = "A";
	$tvi_id = get_voucher($tvi_type);
	if($tvi_id != 0)
	{
		mysqli_query($GLOBALS["___mysqli_ston"], "update board_voucher set user_id = '$user_id' , issue_date = '$date' , mode = 0 where id = '$tvi_id[0]' ");
		
		$b_voucher = $tvi_id[1];
		$b_voucher_type = "TVI";
		$username = get_user_name($user_id);
		include("logs_messages.php");
		//data_logs($user_id,$data_log[15][0],$data_log[15][1],$log_type[15]);
	}	
	$uni_type = "B";
	$uni_id = get_voucher($uni_type);
	if($uni_id != 0)
	{
		mysqli_query($GLOBALS["___mysqli_ston"], "update board_voucher set user_id = '$user_id' , issue_date = '$date' , mode = 0 where id = '$uni_id[0]' ");
		
		$b_voucher = $uni_id[1];
		$b_voucher_type = "Uni TVI";
		$username = get_user_name($user_id);
		include("logs_messages.php");
		//data_logs($user_id,$data_log[15][0],$data_log[15][1],$log_type[15]);
	}	
}

function get_voucher($voucher_type)
{
	$voucher_q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_voucher where mode = 1 and type = '$voucher_type' LIMIT 1");
	$voucher_n = mysqli_num_rows($voucher_q);
	if($voucher_n > 0 )
	{
		while($voucher_r = mysqli_fetch_array($voucher_q))
		{
			$board_voucher_id[0] = $voucher_r['id'];
			$board_voucher_id[1] = $voucher_r['voucher'];
		}
	}
	else
	{
		$board_voucher_id = 0;
	}		
	return $board_voucher_id;
}

function get_virtual_parent_by_will($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where id_user = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$parent_id = $row['parent_id'];
	}
	return $parent_id;
} 



/*for($i = 10; $i <17; $i=$i+3)
{
	$rank = 'B';
	$real_par = $i%3;
	insert_into_rank($i,$real_par,$rank);
}
update_user_rank(11,8);
*/
