<?php

//include("../config.php"); 
//include("functions.php");

function get_direct_income($id)
{
	$real_parent = real_par($id);
	include("setting.php");
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from reg_fees_structure where user_id = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$registration_fees = $row['reg_fees'];
	}
	$date = date('Y-m-d');
	$income = $registration_fees*($direct_income_percent/100);
	mysqli_query($GLOBALS["___mysqli_ston"], "insert into income (user_id , amount , date , type ) values ('$real_parent' , '$income' , '$date' , '$income_type[1]') ");
	update_member_wallet($real_parent,$income,$data_log,$log_type);
	
	
}

