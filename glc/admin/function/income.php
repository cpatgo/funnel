<?php

function board_break_income($id,$type,$levels)
{ 
	include("setting.php");
	$date = date('Y-m-d');
	$types = get_type($id);
	if($types == 'B')
	{
		$income = $board_income[$levels][$type];
		if($income > 0)
		{
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "insert into income (user_id , amount , date , type , level , board_type) values ('$id' , '$income', '$date' , '$income_type[1]' , '$levels' , '$type') ");	
				
			insert_into_wallet($id,$income,$income_type[1]);
		}
	}		
}

function get_type($user_id)  //getting type
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where id_user = '$user_id' ");
	while($row = mysqli_fetch_array($query))
	{
		$type = $row['type'];
	}
	return $type;
}

function board_income($board_b_level)
{
	include("setting.php");
	$income = $board_break_income[$board_b_level]; 
	return $income;	
}	

function insert_into_wallet($id,$income,$inc_type)
{	
	include("setting.php");
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from wallet where id = '$id' ");
	while($row = mysqli_fetch_array($q))
	{
		$amount = $row['amount'];
	}	
	$date = date('Y-m-d');
	$total_income = $income+$amount;
	mysqli_query($GLOBALS["___mysqli_ston"], "update wallet set amount = '$total_income' , date = '$date' where id = '$id' ");
	
	if($inc_type == 1)
		$inc_type_log = "Board Break Income";
	elseif($inc_type == 2)	
		$inc_type_log = "Board Point";
		
	$user_income_log = $income;
	$wallet_amount_log = $amount;
	$total_wallet_amount_log = $total_income;
	$log_username = get_user_name($id);
	include("logs_messages.php");
	data_logs($id,$data_log[5][0],$data_log[5][1],$log_type[5]);
}		


function board_break_point($id,$type)
{ 
	include("setting.php");
	$date = date('Y-m-d');
	$types = get_type($id);
	if($types == 'B')
	{
		$income = $board_point[$type];
		if($income > 0)
		{
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "insert into income (user_id , amount , date , type , board_type) values ('$id' , '$income', '$date' , '$income_type[2]' , '$type') ");	
				
			insert_into_point_wallet($id,$income);
		}
	}		
}

function insert_into_point_wallet($id,$income)
{	
	include("setting.php");
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from point_wallet where user_id = '$id' ");
	while($row = mysqli_fetch_array($q))
	{
		$user_point = $row['user_point'];
	}	
	$date = date('Y-m-d');
	$total_income = $income+$user_point;
	mysqli_query($GLOBALS["___mysqli_ston"], "update point_wallet set user_point = '$total_income' where user_id = '$id' ");
	
	$inc_type_log = "Board Point";
		
	$user_income_log = $income;
	$wallet_amount_log = $amount;
	$total_wallet_amount_log = $total_income;
	$log_username = get_user_name($id);
	include("logs_messages.php");
	data_logs($id,$data_log[5][0],$data_log[5][1],$log_type[5]);
}		
