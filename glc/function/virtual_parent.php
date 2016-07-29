<?php

function geting_virtual_parent($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where parent_id = '$id' ");
	$number = mysqli_num_rows($query);
	return $number;
}


function geting_virtual_parent_with_position($id,$position)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where parent_id = '$id' and position = '$position' ");
	$number = mysqli_num_rows($query);
	return $number;
}


function geting_all_blank_position_with_adding_position($id,$position)
{
	$result = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where parent_id = '$id' and position = '$position' ");
	$num = mysqli_num_rows($result);
	if($num != 0)
	{
		while($row = mysqli_fetch_array($result))
		{
			$position_user_id = $row['id_user'];
		}
	}
	$all_child = geting_all_blank_position($position_user_id);	
	return $all_child;		
}


function geting_all_blank_position($id)
{
	$parent[0] = $id;
	$count = 1;
	for($i = 0; $i <$count; $i++)
	{
			$result = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where parent_id = '$parent[$i]' ");
			$num = mysqli_num_rows($result);
			if($num != 0)
			{
				while($row = mysqli_fetch_array($result))
				{
					$parent[] = $row['id_user'];
				}
				if($num == 1)
				{
					$virtual_parent[] = $parent[$i];
				}
			}
			if($num == 0)
			{
				$virtual_parent[] = $parent[$i];
			}
		$count = count($parent);
	}
	return $virtual_parent;
}

