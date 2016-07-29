<?php
require_once("config.php");
include("function/setting.php");
include("function/functions.php");
include("function/join_plan.php");
require_once "function/formvalidator.php";
include("function/virtual_parent.php");
include("function/send_mail.php");
include("function/e_pin.php");
include("function/income.php");
include("function/u_id_par_id_pos.php");
include("function/check_income_condition.php");
include("function/direct_income.php");
require_once("function/get_parent_with_same_level.php");
require_once("function/insert_board.php");
require_once("function/insert_board_second.php");
require_once("function/insert_board_third.php");
require_once("function/insert_board_fourth.php");
require_once("function/insert_board_fifth.php");
require_once("validation/validation.php");  
require_once("function/rearrangement.php");
require_once("function/country_list1.php");
require_once("function/find_board.php");
require_once("function/export_all_database_into_sql.php");	

// The Coinbase callback will send JSON input to this file
// as specified in the "Callback URL" in your COinbase.com Account

//The Coinbase Callback requires code of 200
$return_code = 200;

$data = json_decode(file_get_contents("php://input"), TRUE);

$text              = print_r($data,true);
$id                = $data['order']['id'];
$status            = $data['order']['status'];
$custom            = $data['order']['custom'];
$refund_address    = $data['order']['refund_address'];

$query 	= mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM temp_users tu INNER JOIN memberships m ON m.membership = tu.membership WHERE id_user = $custom ");
$row 	= mysqli_fetch_array($query);

//user membership					

//insert into users
$users_sql = "INSERT INTO users (username ,real_parent ,f_name ,l_name ,email ,phone_no , password , dob ,address , city , country , gender,time,type ,provience ,state,optin_affiliate) VALUES ('".$row["username"]."' , '".$row["real_parent"]."' , '".$row["f_name"]."', '".$row["l_name"]."' , '".$row["email"]."' , '".$row["phone"]."', '".$row["password"]."' , '".$row["dob"]."', '".$row["address"]."', '".$row["city"]."', '".$row["country"]."' , '".$row["gender"]."', '".$row["time"]."', 'B' , '".$row["provience"]."',  '".$row["state"]."', '".$row["optin_affiliate"]."') ";
mysqli_query($GLOBALS["___mysqli_ston"], $users_sql);

$user_id 	= mysqli_insert_id($GLOBALS["___mysqli_ston"]);
$real_p		= $row["real_parent"];

mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO user_membership (user_id,payment_type,number,initial,current) VALUES ('".$user_id."' , '".$row["reg_way"]."', '".$refund_address."', '".$row["id"]."' , '".$row["id"]."') ");

//join palns
switch ($row["id"]) {
    case '2':
        $plan = 1;
        break;
    case '3':
        $plan = 2;
        break;
    case '4':
        $plan = 3;
        break;
	case '5':
        $plan = 4;
        break;
}

$spill = 0;
								
if($plan == 1)
{
	unset($_SESSION['board_second_breal_id']);
    unset($_SESSION['board_third_breal_id']);
    unset($_SESSION['board_fourth_breal_id']);
    unset($_SESSION['board_fifth_breal_id']);
    unset($_SESSION['board_sixth_breal_id']);
    unset($_SESSION['board_breal_id']);
    $board_break_info = '';
    
	$board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
	join_plan1($board_break_info);
	$membership_type = 2;
}
if($plan == 2)
{
	unset($_SESSION['board_second_breal_id']);
    unset($_SESSION['board_third_breal_id']);
    unset($_SESSION['board_fourth_breal_id']);
    unset($_SESSION['board_fifth_breal_id']);
    unset($_SESSION['board_sixth_breal_id']);
    unset($_SESSION['board_breal_id']);
    $board_break_info = '';

	$board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
	//var_dump($board_break_info);
	join_plan1($board_break_info);

	unset($_SESSION['board_second_breal_id']);
	unset($_SESSION['board_third_breal_id']);
	unset($_SESSION['board_fourth_breal_id']);
	unset($_SESSION['board_fifth_breal_id']);
	unset($_SESSION['board_sixth_breal_id']);
	unset($_SESSION['board_breal_id']);
	$board_break_info = '';
	
	$board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
	//var_dump($board_break_info);
	join_plan2($board_break_info);
	$membership_type = 3;
}
if($plan == 3)
{
	unset($_SESSION['board_second_breal_id']);
    unset($_SESSION['board_third_breal_id']);
    unset($_SESSION['board_fourth_breal_id']);
    unset($_SESSION['board_fifth_breal_id']);
    unset($_SESSION['board_sixth_breal_id']);
    unset($_SESSION['board_breal_id']);
    $board_break_info = '';

	$board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
	join_plan1($board_break_info);

	unset($_SESSION['board_second_breal_id']);
	unset($_SESSION['board_third_breal_id']);
	unset($_SESSION['board_fourth_breal_id']);
	unset($_SESSION['board_fifth_breal_id']);
	unset($_SESSION['board_sixth_breal_id']);
	unset($_SESSION['board_breal_id']);
	$board_break_info = '';
	
	$board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
	join_plan2($board_break_info);

	unset($_SESSION['board_second_breal_id']);
	unset($_SESSION['board_third_breal_id']);
	unset($_SESSION['board_fourth_breal_id']);
	unset($_SESSION['board_fifth_breal_id']);
	unset($_SESSION['board_sixth_breal_id']);
	unset($_SESSION['board_breal_id']);
	$board_break_info = '';
	$board_break_info = insert_into_board_third($user_id,$real_p,$spill,$real_p);
	join_plan3($board_break_info);
	$membership_type = 4;
}
if($plan == 4)
{
	unset($_SESSION['board_second_breal_id']);
    unset($_SESSION['board_third_breal_id']);
    unset($_SESSION['board_fourth_breal_id']);
    unset($_SESSION['board_fifth_breal_id']);
    unset($_SESSION['board_sixth_breal_id']);
    unset($_SESSION['board_breal_id']);
    $board_break_info = '';

	$board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
	join_plan1($board_break_info);

	unset($_SESSION['board_second_breal_id']);
	unset($_SESSION['board_third_breal_id']);
	unset($_SESSION['board_fourth_breal_id']);
	unset($_SESSION['board_fifth_breal_id']);
	unset($_SESSION['board_sixth_breal_id']);
	unset($_SESSION['board_breal_id']);
	$board_break_info = '';
	
	$board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
	join_plan2($board_break_info);

	unset($_SESSION['board_second_breal_id']);
	unset($_SESSION['board_third_breal_id']);
	unset($_SESSION['board_fourth_breal_id']);
	unset($_SESSION['board_fifth_breal_id']);
	unset($_SESSION['board_sixth_breal_id']);
	unset($_SESSION['board_breal_id']);
	$board_break_info = '';

	$board_break_info = insert_into_board_third($user_id,$real_p,$spill,$real_p);
	join_plan3($board_break_info);
	
	unset($_SESSION['board_second_breal_id']);
	unset($_SESSION['board_third_breal_id']);
	unset($_SESSION['board_fourth_breal_id']);
	unset($_SESSION['board_fifth_breal_id']);
	unset($_SESSION['board_sixth_breal_id']);
	unset($_SESSION['board_breal_id']);
	$board_break_info = '';
	$board_break_info = insert_into_board_fourth($user_id,$real_p,$spill,$real_p);
	join_plan4($board_break_info);
	$membership_type = 5;
}
if(chk_real_forth_member($real_p))
{
	$new_user_id = $real_p;
	$new_real_p = get_real_parent($real_p);
	mysqli_query($GLOBALS["___mysqli_ston"], "update users set type='B' where id_user='$real_p' and type='F'");
	$board_break_info = insert_into_board($new_user_id,$new_real_p,$spill,$new_real_p);
	join_plan1($board_break_info);
	$membership_type = 2;
}

mysqli_query($GLOBALS["___mysqli_ston"], "delete from temp_users where id_user = ".$row["id_user"]);
?>