<?php
function get_par($a)
{
	$d[0][0] = $a;
	$d[1][0] = $a%2;
	for($i = 0; $i <3; $i++)
	{
		$d[0][$i+1] = intval($a/2);
		if($d[0][$i+1] == 1)
		{
			$d[1][$i+1] = 0;
		}
		else{	
		$d[1][$i+1] = $d[0][$i+1]%2; }
		$a = $d[0][$i+1];
		//echo $d[0][$i+1];
	}
return $d;
}

/*function get_three_par($par)
{
	for($i = 0; $i <3; $i++)
	{
		$parents[$i] = $par[0][$i+1]; // 00 user id & rest all are parents in $par
	}
	return $parents;	//return parents
}	
*/

function check_validition($value)
{
	if($value == '')
	{
	print "Please Enter right information in all fields!";
	//header("location:user_registration.php");
	die;
	}
}
	
function check_password($password,$re_password)
{
	if($password != $re_password)
	{
		print "Please Enter same password in both fields!";
		die;
	}
}		