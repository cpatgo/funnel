<?php
class cheker_member
{
public	function chk($req_id,$chk_id,$level) 
		{
		 	$tbl_qur = $tbl_qur2 = ""; 
			for($i = 2; $i <= $level; $i++)
			{
				$j = $i-1;
				$k = ($i+1)-1;
				if($j==1)
					$tbl_qur .= "t$j.id_user AS lev$j ,";
				if($i == $level)
					$tbl_qur .= "t$i.id_user AS lev$i";
				else
					$tbl_qur .= "t$i.id_user AS lev$i ,";
					
				$tbl_qur2 .= "LEFT JOIN users AS t$k"." ON t$k."."real_parent = t$j.id_user ";	
			}	
			
	$query = "SELECT ".$tbl_qur."
					FROM users AS t1 ".
					$tbl_qur2."
					WHERE t1.id_user= ".$req_id;

	
			$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
				$num = mysqli_num_rows($result);
					
		
				$k = 0;
				while ($row = mysqli_fetch_array($result))
				{
					for($i = 0; $i < $level; $i++)
					{
						$j = $i+1;
						//$lev_arr[$k][$i] = $row['lev'.$j];
						if($chk_id == $row['lev'.$j])
						{
							$find = 1;
							return  $find;
						}
						else
						{ 
							$find = 0;
							continue;
						}
					}
					//$k++;					
				}
				
				return $find;						
		}
	
}



/*	$query	= "select t1.id_user as lev1,t2.id_user as lev2,t3.id_user as lev3,t4.id_user as lev4,t5.id_user as lev5,t6.id_user as lev6,t7.id_user as lev7,t8.id_user as lev8,t9.id_user as lev9,t10.id_user as lev10
		from users as t1
		LEFT JOIN users AS t2 ON t2.parent_id = t1.id_user
		LEFT JOIN users AS t3 ON t3.parent_id = t2.id_user  
		LEFT JOIN users AS t4 ON t4.parent_id = t3.id_user
		LEFT JOIN users AS t5 ON t5.parent_id = t4.id_user
		LEFT JOIN users AS t6 ON t5.parent_id = t5.id_user
		LEFT JOIN users AS t7 ON t5.parent_id = t6.id_user
		LEFT JOIN users AS t8 ON t5.parent_id = t7.id_user
		LEFT JOIN users AS t9 ON t5.parent_id = t8.id_user
		LEFT JOIN users AS t10 ON t5.parent_id = t9.id_user
		where t1.id_user = 1"; 	*/


/*
	$query	= "select t1.username as lev1,t2.username as lev2,t3.username as lev3,t4.username as lev4,t5.username as lev5,t6.username as lev6,t7.username as lev7,t8.username as lev8,t9.username as lev9,t10.username as lev10
		from users as t1
		LEFT JOIN users AS t2 ON t2.real_parent = t1.id_user
		LEFT JOIN users AS t3 ON t3.real_parent = t2.id_user  
		LEFT JOIN users AS t4 ON t4.real_parent = t3.id_user
		LEFT JOIN users AS t5 ON t5.real_parent = t4.id_user
		LEFT JOIN users AS t6 ON t5.real_parent = t5.id_user
		LEFT JOIN users AS t7 ON t5.real_parent = t6.id_user
		LEFT JOIN users AS t8 ON t5.real_parent = t7.id_user
		LEFT JOIN users AS t9 ON t5.real_parent = t8.id_user
		LEFT JOIN users AS t10 ON t5.real_parent = t9.id_user
		where t1.id_user = 1 && group by id_user"; 	*/
