<?php
function user_exist($username) //check user id is already store or not
{	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_user FROM users WHERE username = '$username' ");
	$num = mysqli_num_rows($query);
	return $num;
}
function user_exist1($username) //check user id is already store or not
{	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_user FROM temp_users WHERE username = '$username' ");
	$num = mysqli_num_rows($query);
	return $num;
}	
	

function get_new_user_id($username)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE username = '$username' ");
	$num = mysqli_num_rows($query);
	if($num != 0)
	{
		while($row = mysqli_fetch_array($query))
		{
			$id = $row['id_user'];
			return $id;
		}
	}
	else { return 0; }		
}

function show_details($user_id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE id_user = '$user_id' ");
	while($row = mysqli_fetch_array($query))
	{
		echo "<center>","<h1>Step 1","</center>";
		echo "<h2>Your ID is ".$row['id_user']."<br>";
		echo "<h2>Your real patent is ".$row['real_parent']."<br>";
		echo "You are added at your virtual parent ".$row['parent_id'];
		echo " at position ".$row['position']."</h2>";
	}	
}

function get_user_name($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE id_user = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$username = $row['username'];
		return $username;
	}	
}

function get_user_type($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE id_user = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$type = $row['type'];
		if($type == 'A') { $status = "Deactive"; }
		if($type == 'B') { $status = "Light"; }
		if($type == 'C') { $status = "Light Plus"; }
		return $status;
	}	
}

function get_user_email($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE id_user = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$email = $row['email'];
		return $email;
	}	
}

function update_member_wallet($id,$req_amount,$data_log,$log_type)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from wallet where id = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$amount = $row['amount'];
	}
	$date = date('Y-m-d');
	$amnt = $amount+$req_amount;
	mysqli_query($GLOBALS["___mysqli_ston"], "update wallet set amount = '$amnt' , date = '$date' where id = '$id' ");
	$pos = get_user_position($id);
	data_logs($id,$pos,$data_log[5][0],$data_log[5][1],$log_type[4]);
}

function active_check($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where id_user = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$type = $row['type'];
		if($type == 'C') { return 'yes';  }
		else { return 'no';  }
	}	
}

function get_upgrade_membership_fees($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from wallet where id = '$id' ");
	while($row = mysqli_fetch_array($q))
	{
		$amount = $row['amount'];
	}
}	

function insert_ebank($id)
{
	$date = date('Y-m-d');
	mysqli_query($GLOBALS["___mysqli_ston"], "insert into e_bank (user_id , date) values ('$id' , '$date') ");
	//data_logs($par,$pos,$title,$message,$log_type);
}
function get_referrals($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where real_parent = '$id'");
	$num = mysqli_num_rows($q);
	return $num;
}
function get_type_user($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where id_user = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$type = $row['type'];
		return $type;	}	
}	

function get_user_pos($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where id_user = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$position = $row['position'];
		if($position == 0) { $pos = "Left"; }
		else { $pos = "Right";  }
	}
	return $pos;	
}	

function get_message($field)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from setting ");
	while($row = mysqli_fetch_array($query))
	{
		$message = $row[$field];
	}
return $message;
}

function get_user_position($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where id_user = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$position = $row['position'];
	}
	return $position;	
}

function insert_wallet()
{
	$date = date('Y-m-d');
	mysqli_query($GLOBALS["___mysqli_ston"], "insert into wallet (id , date) values ('$id' , '$date') ");
	mysqli_query($GLOBALS["___mysqli_ston"], "insert into point_wallet (user_point) values (0) ");
}	

function get_real_parent($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where id_user = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$real_parent = $row['real_parent'];
	}
	return $real_parent;	
}	

function get_status($id) //check user id is already store or not
{	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE id_user = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$status = $row['status'];
	}
	return $status;
}


function get_date($id) //check user id is already store or not
{	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE id_user = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$date = $row['date'];
	}
	return $date;
}
	
function get_level($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break where user_id = '$id' and level <= 1 order by id desc limit 1 ");
	while($r = mysqli_fetch_array($q))
	{
		$b_b_id = $r['level'];
	}
	return $b_b_id;
}


function get_free_member($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where real_parent = '$id' and status = 'A' ");
	$num = mysqli_num_rows($q);
	return $num;
}

function get_paid_member($id)
{
	$sql = sprintf("SELECT * FROM users WHERE real_parent = %d AND status = 'B'", $id);
	$q = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
	$num = mysqli_num_rows($q);
	return $num;
}
function get_pv($id)
{
	$total_pv = 0;
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from profile_payment_status where user_id = '$id' ");
	while($row = mysqli_fetch_array($q))
	{
		$pv = $row['pv'];
		$total_pv = $total_pv+$pv;
	}
	return $total_pv;
}	

function user_e_bank_amount($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from e_bank where user_id = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$amount = $row['amount'];
	}
	return $amount;	
}

function get_full_name($id)
{
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where id_user = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$name = $row['f_name']." ".$row['m_name']." ".$row['l_name'];
	}
	return $name;
} 



function get_user_total_income($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from income where user_id = '$id' ");
	$num = mysqli_num_rows($query);
	if($num > 0)
	{
		while($row1 = mysqli_fetch_array($query))
			$tatal_income = $row1['amount'];
	}
	else
		$tatal_income = 0;
		
	return $tatal_income;	
}

function get_user_total_income_receive($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from payment_information where user_id = '$id' and mode = 1 ");
	$num = mysqli_num_rows($query);
	if($num > 0)
	{
		while($row1 = mysqli_fetch_array($query))
			$paid_income = $row1['income'];
	}
	else
		$paid_income = 0;
		
	return $paid_income;	
}

function code_country($data)
{
?>
	<input type=text style="width:70px;" name="<?php print $data; ?>" id="code" class="input-medium" value=""  placeholder="Country Code" />
<?php }