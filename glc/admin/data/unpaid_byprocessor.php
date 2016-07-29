<?php
session_start();
ini_set("display_errors",'off');

include("condition.php");
require_once("../config.php");
include("../function/setting.php");
include("../function/functions.php");
include("../function/join_plan.php");
require_once "../function/formvalidator.php";
include("../function/virtual_parent.php");
include("../function/send_mail.php");
include("../function/e_pin.php");
include("../function/income.php");
include("../function/u_id_par_id_pos.php");
include("../function/check_income_condition.php");
include("../function/direct_income.php");
require_once("../function/get_parent_with_same_level.php");
require_once("../function/insert_board.php");
require_once("../function/insert_board_second.php");
require_once("../function/insert_board_third.php");
require_once("../function/insert_board_fourth.php");
require_once("../function/insert_board_fifth.php");
//require_once("../function/insert_board_six.php");
require_once("../validation/validation.php");  
require_once("../function/rearrangement.php");
require_once("../function/country_list1.php");
require_once("../function/find_board.php");
require_once("../function/export_all_database_into_sql.php");
?>
<?php
if(isset($_REQUEST['paid']))
{
	$pay_id = $_REQUEST['pay_id'];
	$sql = "select * from temp_users where id_user='$pay_id' and paid=0";
	$qu = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
	$obj = mysqli_fetch_object($qu);
	$username = $obj->username;
	$real_parent_id = $obj->real_parent;
	$chk = user_exist($username);
	if($chk > 0)
	{
	print	$error_username = "<B style=\"color:#FF0000; font-size:12pt;\">User Id '$username' is already exist!</B>";
	}
	else
	{
		mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO users (username,real_parent) 
								VALUES ('$username' , '$real_parent_id') ");
			
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_user FROM users WHERE username = '$username' ");
		while($row = mysqli_fetch_array($query))
		{
			$user_id = $row[0];
		}
		insert_wallet();  
	
		$par = get_par($user_id);
		$user_pos = $par[1][0];          //user position
		$users_parent_id = $par[0][1];  //parent id
		$children = geting_virtual_parent($users_parent_id);
		if($children < 2)
		{
			$f_name = $obj->f_name;
			$l_name = $obj->l_name;
			$email = $obj->email;
			$gender = $obj->gender;
			$phone = $obj->phone_no;
			$city = $obj->city;
			
			$password = $obj->password;
			$dob = $obj->dob;
			$address = $obj->address;
			$time = time();
			$country = $obj->country;
			
			$provience = $obj->provience;
			$state = $obj->state;
			$user_pin = mt_rand(100000, 999999);
			$date = $systems_date = date('Y-m-d');
			$activate_date = $obj->activate_date;
			
			$type = "c";
			$pancard_no = $obj->pan_no;
			
			mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE users SET parent_id = '$users_parent_id' , real_parent = '$real_parent_id' , position =  '$user_pos' , f_name = '$f_name' , l_name = '$l_name' , gender = '$gender' , email = '$email' , phone_no = '$phone' , city = '$city' , password = '$password' , dob = '$dob' , address = '$address' , time = '$time' , country ='$country' , provience ='$provience', state ='$state' , user_pin = '$user_pin' , date = '$date' , activate_date = '$activate_date' , type = '$type' , pan_no = '$pancard_no' WHERE id_user = '$user_id' ");
						
			//new registration message
			$title = "new User register";
			$to = $email;
			$db_msg = $email_welcome_message;
			include("function/full_message.php");
			$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
			$SMTPChat = $SMTPMail->SendMail();
			
			include 'admin_mail.php';
			$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to_admin, $title_admin, $full_message_admin);
			$SMTPChat = $SMTPMail->SendMail();
						
			//direct member message
			$real_parent_username_log = get_user_name($real_parent_id);
						
			$t = date('H:i:s'); 
			
				
			unset($_SESSION['board_second_breal_id']);
			unset($_SESSION['board_third_breal_id']);
			unset($_SESSION['board_fourth_breal_id']);
			unset($_SESSION['board_fifth_breal_id']);
			unset($_SESSION['board_sixth_breal_id']);
			unset($_SESSION['board_breal_id']);
			$spill = 0;
			$board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
			join_plan1($board_break_info);
		}
		mysqli_query($GLOBALS["___mysqli_ston"], "update temp_users set paid=1 where id_user='$pay_id' and paid=0");
		echo "<script type=\"text/javascript\">";
		echo "window.location = \"index.php?page=unpaid_byprocessor\"";
		echo "</script>";
	}
				
								
}

?>
<div class="ibox-content">
<table class="table table-bordered"> 
	<thead>
	<tr>
		<th class="text-center">No.</th>
		<th class="text-center">User Id</th>
		<th class="text-center">Name</th>
		<th class="text-center">Sponser</th>
		<th class="text-center">Email</th>
		<th class="text-center">Phone</th>
		<th class="text-center">Address</th>
		<th class="text-center">Pay Processor</th>
		<th class="text-center"></th>
	</tr>
	</thead>
	<tbody>
<?php
	$my_query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from temp_users where paid=0 order by id_user desc");
	$num = mysqli_num_rows($my_query);
	$srno = 1;
	while($my_row = mysqli_fetch_array($my_query))
	{
		$username = $my_row['username'];
		$real_p = $my_row['real_parent'];
		$sponser = get_user_name($real_p);
		$name = $my_row['f_name']." ".$my_row['username'];
		
		$id_user = $my_row['id_user'];
		$date = $my_row['date'];
		$time = $my_row['time'];
		$email = $my_row['email'];
		$reg_by = '';
		$reg_by = $my_row['reg_way'];
		
		$phone_no = $my_row['phone_no'];
		$address = $my_row['address']."<br>City : ".$my_row['city'].",Country : ".$my_row['country'];
		
		echo "
			<tr class=\"text-center\">
				<td>$srno</td>
				<td>$username</td>
				<td>$name</td>
				<td>$sponser</td>
				<td>$email</td>
				<td>$phone_no</td>
				<td>$address</td>
				<td>$reg_by</td>
				<td>
					<form action=\"\" method=\"post\">
						<input type=hidden name=pay_id value=$id_user />
						<input type=submit value=Paid name=paid class='btn btn-primary'/>
					</form>
				</td>
			</tr>";
		$srno++;	
	}
?>
</tbody>	
</table>
</div>

