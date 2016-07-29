<?php

//include("../config.php");

/*function get_parent_whith_same_level($id,$level)
{
	$active_par = 0;
	do
	{
		$q = mysql_query("select * from users where id_user = '$id' ");
		while($r = mysql_fetch_array($q))
		{
			print $real_parent = $r['real_parent'];
			$real = $real_parent;
		}	
		 $real_parent_level = get_real_parent_level($real_parent);
		if(real_parent_level == $level)
		{
			$active_par = $real_parent;
			$real = 0;
		}	
	}while($real != 0);
	return $active_par;
}*/

function get_parent_whith_same_level($id,$b_level)
{
	$active_par[0] = 0;
	$active_par[1] = 0;
	$user_ids[0] = $id;
	$cnt = count($user_ids);
	for($i = 0; $i < $cnt; $i++)
	{
		$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where id_user = '$user_ids[$i]' ");
		while($r = mysqli_fetch_array($q))
		{
			$real_parent = $r['real_parent'];
			$user_ids[] = $real_parent;
		}	
		$real_parent_level = get_real_parent_level($real_parent);
		if($real_parent_level[0] == $b_level)
		{
			$active_par[0] = $real_parent;
			$active_par[1] = $real_parent_level[1];
			$active_par[2] = $real_parent_level[2];
			$i = $cnt+5;
		}
		$cnt = count($user_ids);	
	}
	return $active_par;
}


function get_real_parent_level($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break where user_id = '$id' ORDER BY id DESC LIMIT 1 ");
	while($r = mysqli_fetch_array($q))
	{
		$level[0] = $r['level'];
		$level[1] = $tt = $r['board_b_id'];
		$level[2] = get_bbi_pos($tt);
	}	
	return $level;
}

function get_bbi_pos($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board  WHERE  board_id = '$id' and mode = 1 ");
	while($row = mysqli_fetch_array($query))
	{
		$pos = $row['pos1'];
	}
	return $pos;	
}

/*$g = get_parent_whith_same_level(2,1);
print  $g[0]."   ".$g[1]."  ".$g[2];*/