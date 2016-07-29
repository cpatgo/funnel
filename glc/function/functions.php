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
function useremail_exist($email) //check user id is already store or not
{	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_user FROM users WHERE email = '$email' ");
	$num = mysqli_num_rows($query);
	return $num;
}
function useremail_exist1($email) //check user id is already store or not
{	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_user FROM temp_users WHERE email = '$email' ");
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

function chk_real_forth_member($real_p) //check user id is already store or not
{	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT count(t1.real_parent) cnt,t2.type 
						  FROM users t1
						  inner join users t2 on t2.id_user = t1.real_parent
						  WHERE t1.real_parent = '$real_p' and t2.type='F' and t1.type='B'
						  group by t1.real_parent ");
	$num = mysqli_num_rows($query);
	if($num > 0)
	{
		while($row = mysqli_fetch_array($query))
		{
			$cnt = $row['cnt'];
			//bug fix - $cnt=4 to $cnt>3
			if($cnt>3)
				return true;
			else
				return false;
		}
	}
	return false;
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
function get_missed_commission($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM income WHERE user_id = '$id' and other_type='less than 2 qp'");
	if($query){
		while($row = mysqli_fetch_array($query))
		{
			return  $row['time'];
		}
	} 
	else 
	{ 
		return ""; 
	}
}
function get_referrals($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where real_parent = '$id'");
	$num = mysqli_num_rows($q);
	return $num;
}

function get_user_type($id)
{
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT type FROM users WHERE id_user = '$id' ");
	$row = mysqli_fetch_array($query);
	$user_type = $row[0];	
	if($user_type == 'D')
	{
		$type = '<span class="label label-danger">Blocked</span>';
	} else {
		//Select q_time in settings table : 6months
		$row = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT q_time FROM setting limit 1"));
		$months = $row[0];

		//Deduct 6 months from current time
		$effectiveDate = strtotime("-".$months." months", time());

		//Select referrals of the user where the date registered is greater than the effective date
		$sql = sprintf('SELECT count(id_user) FROM users WHERE real_parent = %d AND type <> "F" AND time > %d', $id, $effectiveDate);

		$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		$row = mysqli_fetch_array($query);
		$num = $row[0];	
		if ($num > 1) {
			$type = '<span class="label label-primary">Qualified</span>';
		} else {
			$type = '<span class="label label-warning">non-Qualified</span>';
		}
	}
	return $type;
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
function get_user_dwolla_id($id)
{	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT dwolla_id FROM users WHERE id_user = '$id' ");	
	while($row = mysqli_fetch_array($query))
	{
		$dwolla_id = $row['dwolla_id'];
		return $dwolla_id;	
	}
}
function get_user_payza_id($id)
{	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT payza_account FROM users WHERE id_user = '$id' ");	
	while($row = mysqli_fetch_array($query))
	{
		$payza = $row['payza_account'];
		return $payza;	
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
		$status = $row['type'];
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
function get_levels($id)
{
	$levels = '';
	$q1  = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break where user_id = '$id' ");
	$num1 = mysqli_num_rows($q1);
	if($num1>0){ $levels .= '<span class="label label-primary">1</span> '; }
	
	$q2  = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_second where user_id = '$id' ");
	$num2 = mysqli_num_rows($q2);
	if($num2>0){ $levels .= '<span class="label label-info">2</span> '; }
	
	$q3  = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_third where user_id = '$id' ");
	$num3 = mysqli_num_rows($q3);
	if($num3>0){ $levels .= '<span class="label label-success">3</span> '; }
	
	$q4  = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_fourth where user_id = '$id' ");
	$num4 = mysqli_num_rows($q4);
	if($num4>0){ $levels .= '<span class="label label-danger">4</span> '; }
	
	$q5  = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_fifth where user_id = '$id' ");
	$num5 = mysqli_num_rows($q5);
	if($num5>0){ $levels .= '<span class="label label-black">5</span> '; }
	
	return $levels;
}


function get_free_member($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where real_parent = '$id' and type = 'A' ");
	$num = mysqli_num_rows($q);
	return $num;
}

function get_paid_member($id)
{
	$sql = sprintf("SELECT * FROM users WHERE real_parent = %d AND type = 'B'", $id);
	$q = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
	$num = mysqli_num_rows($q);

// $myFile = "IPNRes.txt";
// $fh = fopen($myFile,'a') or die("can't open the file");
// fwrite($fh, $sql);
// fwrite($fh, "\n");
// fwrite($fh, $num);
// fwrite($fh, "\n");
// fclose($fh);

	return $num;
}
function get_qualified_referrals($id)
{
	$row = mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT q_time FROM setting limit 1"));
	$months = $row[0];
	$effectiveDate = strtotime("-".$months." months", time());
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT count(id_user) FROM users 
													   WHERE real_parent = '$id' and type <> 'F' and time > ".$effectiveDate." ");
	$row = mysqli_fetch_array($query);
	$num = $row[0];
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

function validate_request_amount($amount) 
{  
	//if(preg_match("^[0-9]{1,15}$", $amount) == 1)
	if(preg_match('/\b\d{1,3}(?:,?\d{3})*(?:\.\d{2})?\b/', $amount))
	{
		if(preg_replace("/([^0-9\\.])/i", "", $amount) >= 100)
			return 1;
		else
			return 0;
	}			
	else
		return 0;     
}

function insert_wallet_account($id , $recieve_id , $amount , $date , $type , $account, $mode , $wallet_balance)
{
	//include 'setting.php';
	$username = get_user_name($recieve_id);
	if($mode == 1)
	{
                /*
		$sql = "insert into account (user_id , cr , date , type , account , wallet_balance)
				values('$id' , '$amount' , '$date' , '$type' , '".$username." $account ' , '$wallet_balance') ";
                */
                $sql = "insert into account (user_id , cr , date , type , account , wallet_balance)
				values('$id' , '$amount' , '$date' , '$type' , '$account' , '$wallet_balance') ";
					
		mysqli_query($GLOBALS["___mysqli_ston"], $sql);			
	}
	elseif($mode == 2)
	{
                /*
		$sql = "insert into account (user_id , dr , date , type , account , wallet_balance)
				values('$id' , '$amount' , '$date' , '$type' , '".$username." $account' , '$wallet_balance') ";
                */
		$sql = "insert into account (user_id , dr , date , type , account , wallet_balance)
				values('$id' , '$amount' , '$date' , '$type' , '$account' , '$wallet_balance') ";
					
		mysqli_query($GLOBALS["___mysqli_ston"], $sql);			
	}
}

function get_wallet_balance($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from wallet where id = '$id' ");
	while($row = mysqli_fetch_array($q))
	{
		$amount = $row['amount'];
	}
	return $amount;
}

function get_available_funds($id)
{
	require_once(dirname(dirname(__FILE__))."/config.php");
    $income_class = getInstance('Class_Income');
    $reserve_month = glc_option('reserve_month');
    $approved = 0;
	//Get approved commissions in income table
	$approved_commissions = mysqli_query($GLOBALS["___mysqli_ston"], "select * from income where user_id = $id and approved = 1");
	while($row = mysqli_fetch_array($approved_commissions)){
		//Compute rolling reserve
        $rolling_reserve = $income_class->get_rolling_reserve($row['id']);
        if(!empty($rolling_reserve)):
            $rolling_reserve = $rolling_reserve[0];
            //Check if current date is N months after the commission was created
            $date_created = date('Y-m', strtotime(sprintf('+%d months', $reserve_month), strtotime($rolling_reserve['date_created'])));
            //If current date is equal or less than the reserve created date, deduct the reserve amount
            if(date('Y-m') <= $date_created) $row['amount'] = (float)$row['amount'] - $rolling_reserve['reserve'];
        endif;
		$approved += $row['amount'];
	}

	//Get requested/paid commissions in paid_unpaid table
	$requested_commissions = mysqli_query($GLOBALS["___mysqli_ston"], "select SUM(amount) as amount from paid_unpaid where user_id = $id and paid > -1");
	while($row = mysqli_fetch_array($requested_commissions)){
		$requested = $row['amount'];
	}

	//Get funds used to purchase vouchers
	$purchase_funds = mysqli_query($GLOBALS["___mysqli_ston"], "select SUM(pd.amount) as amount from purchase_details pd INNER JOIN purchases pu ON pu.id = pd.purchase_id where pu.user_id = $id AND pd.payment_method = 1");
	while($row = mysqli_fetch_array($purchase_funds)){
		$purchases = $row['amount'];
	}

	//Return approved commissions less requested/paid commissions
	return (float)$approved - (float)$requested - (float)$purchases;
}

function get_pending_payments($id)
{
	//Get pending payments in paid_unpaid table
	$requested_payments = mysqli_query($GLOBALS["___mysqli_ston"], "select SUM(amount) as amount from paid_unpaid where user_id = $id and paid = 0");
	while($row = mysqli_fetch_array($requested_payments)){
		$pending = $row['amount'];
	}
	return $pending;
}

function get_join_date($id)
{
	//Get join date of user
	$get_join_date = mysqli_query($GLOBALS["___mysqli_ston"], "select time from users where id_user = '$id'");
	while($row = mysqli_fetch_array($get_join_date)){
		$join_date = $row['time'];
	}
	return date('Y-m-d', $join_date);
}

function has_payza_account($id)
{
	//Get payza account of user
	$get_payza_account = mysqli_query($GLOBALS["___mysqli_ston"], "select payza_account from users where id_user = '$id'");
	while($row = mysqli_fetch_array($get_payza_account)){
		$payza = $row['payza_account'];
	}
	return (empty($payza)) ? false : $payza;
}

function get_user_membership($id)
{
	//Get membership of user
	$get_membership = mysqli_query($GLOBALS["___mysqli_ston"], sprintf("SELECT membership FROM memberships WHERE id = %d", $id));
	while($row = mysqli_fetch_array($get_membership)){
		$membership = $row['membership'];
	}
	return $membership;
}
?>