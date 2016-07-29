<?php
/*include("../config.php");
include("insert_board.php");*/


function find_board($real_par)
{
	$resultt[0] = 0;
	do
	{
		$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board where mode = 1 and board_id = (select board_b_id from board_break where user_id = '$real_par' order by board_b_id desc limit 1 ) ");
		$num = mysqli_num_rows($q);
		if($num > 0)
		{
			while($row = mysqli_fetch_array($q))
			{
				$bbid = $row['board_id'];
				$resultt[0] = $bbid;
				$resultt[1] = $real_par;
			}
		}
		$real_par = get_user_real_par($real_par);
	}while($resultt[0] == 0 and $real_par > 0);	
	return $resultt;
}


function find_board_second($real_par) 
{
	$resultt[0] = 0;
	do
	{
		$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_second where mode = 1 and board_id = (select board_b_id from board_break_second where user_id = '$real_par' order by board_b_id desc limit 1 ) ");
		$num = mysqli_num_rows($q); 
		if($num > 0)
		{
			while($row = mysqli_fetch_array($q))
			{
				$bbid = $row['board_id'];
				$resultt[0] = $bbid;
				$resultt[1] = $real_par;
			}
		}
		$real_par = get_user_real_par($real_par);
	}while($resultt[0] == 0 and $real_par > 0);	
	return $resultt;
}


function find_board_third($real_par) 
{
	$resultt[0] = 0;
	do
	{
		$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_third where mode = 1 and board_id = (select board_b_id from board_break_third where user_id = '$real_par' order by board_b_id desc limit 1 ) ");
		$num = mysqli_num_rows($q); 
		if($num > 0)
		{
			while($row = mysqli_fetch_array($q))
			{
				$bbid = $row['board_id'];
				$resultt[0] = $bbid;
				$resultt[1] = $real_par;
			}
		}
		$real_par = get_user_real_par($real_par);
	}while($resultt[0] == 0 and $real_par > 0);	
	return $resultt;
}

function find_board_fourth($real_par) 
{
	$resultt[0] = 0;
	do
	{
		$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_fourth where mode = 1 and board_id = (select board_b_id from board_break_fourth where user_id = '$real_par' order by board_b_id desc limit 1 ) ");
		$num = mysqli_num_rows($q); 
		if($num > 0)
		{
			while($row = mysqli_fetch_array($q))
			{
				$bbid = $row['board_id'];
				$resultt[0] = $bbid;
				$resultt[1] = $real_par;
			}
		}
		$real_par = get_user_real_par($real_par);
	}while($resultt[0] == 0 and $real_par > 0);	
	return $resultt;
}


function find_board_fifth($real_par) 
{
	$resultt[0] = 0;
	do
	{
		$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_fifth where mode = 1 and board_id = (select board_b_id from board_break_fifth where user_id = '$real_par' order by board_b_id desc limit 1 ) ");
		$num = mysqli_num_rows($q); 
		if($num > 0)
		{
			while($row = mysqli_fetch_array($q))
			{
				$bbid = $row['board_id'];
				$resultt[0] = $bbid;
				$resultt[1] = $real_par;
			}
		}
		$real_par = get_user_real_par($real_par);
	}while($resultt[0] == 0 and $real_par > 0);	
	return $resultt;
}

function find_board_sixth($real_par) 
{
	$resultt[0] = 0;
	do
	{
		$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_sixth where mode = 1 and board_id = (select board_b_id from board_break_fifth where user_id = '$real_par' order by board_b_id desc limit 1 ) ");
		$num = mysqli_num_rows($q); 
		if($num > 0)
		{
			while($row = mysqli_fetch_array($q))
			{
				$bbid = $row['board_id'];
				$resultt[0] = $bbid;
				$resultt[1] = $real_par;
			}
		}
		$real_par = get_user_real_par($real_par);
	}while($resultt[0] == 0 and $real_par > 0);	
	return $resultt;
}
