<?php
/*ini_set("display_errors","off");
include("../config.php");
include("insert_board.php");
print_r(find_board(0));*/

function find_board($real_par)
{
	$resultt[0] = 0;
	if($real_par == 0)
	{
		$sql = "select * from board where mode = 1 order by board_id asc limit 1";
		$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		$rows = mysqli_fetch_array($query);

		$resultt[0] = $rows['board_id'];
		$resultt[1] = $real_par;
	}
	else
	{
		do
		{
			//bug fix - change orger by board_b_id with time
			$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board where mode = 1 and board_id = 
			(select board_b_id from board_break
			inner join board on board_break.board_b_id = board.board_id and mode = 1
			where user_id = '$real_par')
			");
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
	}		
	return $resultt;
}


function find_board_second($real_par) 
{
	$resultt[0] = 0;
	if($real_par == 0)
	{
		$sql = "select * from board_second where mode = 1 order by board_id asc limit 1";
		$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		$rows = mysqli_fetch_array($query);

		$resultt[0] = $rows['board_id'];
		$resultt[1] = $real_par;
	}
	else
	{
		do
		{
			
			$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_second where mode = 1 and board_id = 
			(select board_b_id from board_break_second
			inner join board_second on board_break_second.board_b_id = board_second.board_id and mode = 1
			where user_id = '$real_par')
			");
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
	}	
	return $resultt;
}


function find_board_third($real_par) 
{
	$resultt[0] = 0;
	if($real_par == 0)
	{
		$sql = "select * from board_third where mode = 1 order by board_id asc limit 1";
		$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		$rows = mysqli_fetch_array($query);

		$resultt[0] = $rows['board_id'];
		$resultt[1] = $real_par;
	}
	else
	{
		do
		{
			$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_third where mode = 1 and board_id = 
			(select board_b_id from board_break_third
			inner join board_third on board_break_third.board_b_id = board_third.board_id and mode = 1
			where user_id = '$real_par')
			");
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
	}	
	return $resultt;
}

function find_board_fourth($real_par) 
{
	$resultt[0] = 0;
	if($real_par == 0)
	{
		$sql = "select * from board_fourth where mode = 1 order by board_id asc limit 1";
		$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		$rows = mysqli_fetch_array($query);

		$resultt[0] = $rows['board_id'];
		$resultt[1] = $real_par;
	}
	else
	{
		do
		{
			$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_fourth where mode = 1 and board_id = 
			(select board_b_id from board_break_fourth
			inner join board_fourth on board_break_fourth.board_b_id = board_fourth.board_id and mode = 1
			where user_id = '$real_par')
			");
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
	}	
	return $resultt;
}


function find_board_fifth($real_par) 
{
	$resultt[0] = 0;
	if($real_par == 0)
	{
		$sql = "select * from board_fifth where mode = 1 order by board_id asc limit 1";
		$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		$rows = mysqli_fetch_array($query);

		$resultt[0] = $rows['board_id'];
		$resultt[1] = $real_par;
	}
	else
	{
		do
		{
			$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_fifth where mode = 1 and board_id =
			(select board_b_id from board_break_fifth
			inner join board_fifth on board_break_fifth.board_b_id = board_fifth.board_id and mode = 1
			where user_id = '$real_par')			
			");
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
	}	
	return $resultt;
}

function find_board_sixth($real_par) 
{
	$resultt[0] = 0;
	if($real_par == 0)
	{
		$sql = "select * from board_sixth where mode = 1 order by board_id asc limit 1";
		$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		$rows = mysqli_fetch_array($query);

		$resultt[0] = $rows['board_id'];
		$resultt[1] = $real_par;
	}
	else
	{
		do
		{
			$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_sixth where mode = 1 and board_id = 
			(select board_b_id from board_break_sixth
			inner join board_sixth on board_break_sixth.board_b_id = board_sixth.board_id and mode = 1
			where user_id = '$real_par')			
			");
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
	}	
	return $resultt;
}
?>