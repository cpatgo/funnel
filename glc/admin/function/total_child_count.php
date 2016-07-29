<?php
function give_total_children($id)  //give all children
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where parent_id = '$id' and position = 0 ");
	$num = mysqli_num_rows($query);
	if($num == 0)
	{
		$children[0] = 0;
		$children[1] = 0;
		return $children;
	}
	while($row = mysqli_fetch_array($query))
	{
		$left = $row['id_user'];
		$children[0] = get_all_total_child($left);
	}
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where parent_id = '$id' and position = 1 ");
	$num1 = mysqli_num_rows($query);
	if($num1 == 0)
	{
		$children[1] = 0;
		return $children;
	}
	while($row = mysqli_fetch_array($query))
	{
		$right = $row['id_user'];
		$children[1] = get_all_total_child($right);
	}
	return $children;
}



function get_all_total_child($id)  // get all child in id network
{
	$total = 1;
	$child[0] = $id;
	$count = count($child);
	for($i = 0; $i <$count; $i++)
	{
	
			$result = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where parent_id = '$child[$i]' ");
			$num = mysqli_num_rows($result);
			if($num != 0)
			{
				while($row = mysqli_fetch_array($result))
				{
					$child[] = $row['id_user'];
					$total++;
				}
			}
		$count = count($child);
	}
	return $total;
}
