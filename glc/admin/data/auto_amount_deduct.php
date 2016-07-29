<?php

$sql = "select amount , date ,user_id from deduct_amount order by id desc limit 1";
$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
while($row = mysqli_fetch_array($query))
{
	$ded_amount = $row[0];
	$date = $row[1];
	$id_user = $row[2];
	
	$u_id = explode('-' ,$id_user);
	if($date == $systems_date)
	{
		if($id_user == '')
		{
			$user_sql = "select * from wallet where amount >= $ded_amount ";
			$user_query = mysqli_query($GLOBALS["___mysqli_ston"], $user_sql);
			$num = mysqli_num_rows($user_query);
			$i = 0;
			while($rows = mysqli_fetch_array($user_query))
			{
				$i++;
				$user_id = $rows['id'];
				
				$update_sql = "update wallet set amount = amount-'$ded_amount' where id = '$user_id' ";
				mysqli_query($GLOBALS["___mysqli_ston"], $update_sql);
			
				 
				 if($num == $i)
					$user_ids .= $user_id; 
				else
					$user_ids .= $user_id.'-'; 	
				
					
			}
			$update_ded_amount = "update deduct_amount set user_id = '$user_ids' ,no_of_user = '$num' , total_amount = '$num'*'$ded_amount' order by id desc limit 1 ";
			mysqli_query($GLOBALS["___mysqli_ston"], $update_ded_amount);
			
			print "Amount Deduct Successfully !!";
		}
		else
			print 	"<font color='red'>Amount Already Deduct !!</font>";
	}
}

?>