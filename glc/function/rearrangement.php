<?php

function rearrangement_calc($arr)
{
	$result[0][0] = $arr[0][0];
	$result[0][1] = $arr[0][1];	
	$result[0][2] = $arr[0][2];		
	$c  =  count($arr);
	for($i = 1; $i <= $c; $i++)
	{
		$not = 0;	
		if($i == 1)
		{
			$result[1][0] = $arr[1][0];
			$result[1][1] = $arr[1][1];	
			$result[1][2] = $arr[1][2];		
		}
		else
		{
			for($j = 1; $j < $i; $j++)
			{
				if($result[$j][1] < $arr[$i][1] && $not == 0)
				{
					if($result[$j][1] < 2)
					{
						$not = 1;	
						for($k = $i; $k > $j; $k--)
						{
							$result[$k][0] = $result[$k-1][0];
							$result[$k][1] = $result[$k-1][1];
							$result[$k][2] = $result[$k-1][2];
						}
						$result[$k][0] = $arr[$i][0];
						$result[$k][1] = $arr[$i][1];
						$result[$k][2] = $arr[$i][2];
					}
				}
				elseif($result[$j][1] == $arr[$i][1] && $not == 0)
				{ 
					if($result[$j][2] > $arr[$i][2])
					{
						$not = 1;	
						for($k = $i; $k > $j; $k--)
						{
							$result[$k][0] = $result[$k-1][0];
							$result[$k][1] = $result[$k-1][1];
							$result[$k][2] = $result[$k-1][2];
						}
						$result[$k][0] = $arr[$i][0];
						$result[$k][1] = $arr[$i][1];
						$result[$k][2] = $arr[$i][2];
					}
				}
			}
		}
		if($not == 0)
		{
			$result[$i][0] = $arr[$i][0];
			$result[$i][1] = $arr[$i][1];
			$result[$i][2] = $arr[$i][2];	
		}
	}
	return $result;
}	

/*

$arr[1][0] = 4;
$arr[1][1] = 0;
$arr[1][2] = 121221;
$arr[2][0] = 5;
$arr[2][1] = 2;
$arr[2][2] = 121251;
$arr[3][0] = 6;
$arr[3][1] = 2;
$arr[3][2] = 121211;
$arr[4][0] = 7;
$arr[4][1] = 1;
$arr[4][2] = 121221;
$arr[5][0] = 8;
$arr[5][1] = 2;
$arr[5][2] = 101221;
$arr[6][0] = 9;
$arr[6][1] = 1;
$arr[6][2] = 121221;


$result = rearrangement_calc($arr);
$c  =  count($result);	

for($j = 1; $j <= $c; $j++)
{ print $result[$j][0]."  ".$result[$j][1];
print "<br>"; }

*/