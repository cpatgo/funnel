<?php
ini_set("display_errors",'off');
session_start();
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
	require_once("function/insert_board_six.php");
	require_once("validation/validation.php");  
	require_once("function/rearrangement.php");
	require_once("function/country_list1.php");
	require_once("function/find_board.php");
	require_once("function/export_all_database_into_sql.php");


if(($_POST['submit'] == 'submit'))
{
	$real_username = $_REQUEST['username'];
	$plan = $_REQUEST['epin_type'];
	$id_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE username = '$real_username' ");
	$num = mysqli_num_rows($id_query);
	if($num == 0)
	{
		print "<font color=\"#FF0000\" size=\"+2\"><center>Please enter correct Username !</center></font>";
	}
	else
	{
		while($row = mysqli_fetch_array($id_query))
		{
			$bosd_id = $real_p = $real_parent_id = $row['id_user'];
		}
		$type = "B";
		$f_name =$_POST['f_name'];
		$no_of_reg =$_POST['no_of_reg'];
		$l_name =$_POST['l_name'];
		$user_name = $f_name." ".$l_name;
		$date = date('Y-m-d');
		$reg_mode =$_POST['reg_mode'];
		$reg_amount = $_SESSION['registration_amount'];	
		$number =2;
		
$trttrtrrt = 1;		
		
		
	for($trt = 0; $trt < $no_of_reg; $trt++)
	{		
				
		$user_pin = mt_rand(100000, 999999);
		$username = $username;
		do
		{
			$username = "U".mt_rand(100000, 999999);
			$chk = user_exist($username);
		}	
		while($chk > 0);
		$password = 123456;
		
		mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO users (username,password,real_parent) VALUES ('$username' , '$password' , '$real_parent_id') ");

		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_user FROM users WHERE username = '$username' ");
		while($row = mysqli_fetch_array($query))
		{
			$user_id = $row[0];
		}
		insert_wallet();                  // inserting in wallet
		$par = get_par($user_id);
		$user_pos = $par[1][0];          //user position
		$users_parent_id = $par[0][1];  //parent id
		$children = geting_virtual_parent($users_parent_id);
		if($children < 2)
		{
			mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE users SET parent_id = '$users_parent_id' , position =  '$user_pos' , f_name = '$f_name' , l_name = '$l_name' , user_pin = '$user_pin' , date = '$date' , type = '$type' WHERE id_user = '$user_id' ");
						
							
			unset($_SESSION['board_second_breal_id']);
			unset($_SESSION['board_third_breal_id']);
			unset($_SESSION['board_fourth_breal_id']);
			unset($_SESSION['board_fifth_breal_id']);
			unset($_SESSION['board_sixth_breal_id']);
			unset($_SESSION['board_breal_id']);
			$spill = 0;
			
			if($plan == 1)
			{
				$board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
				join_plan1($board_break_info);
			}
			if($plan == 2)
			{
				$board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
				join_plan1($board_break_info);
				
				$board_break_info = '';
				
				$board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
				join_plan2($board_break_info);
			}
			if($plan == 3)
			{
				$board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
				join_plan1($board_break_info);
				
				$board_break_info = '';
				
				$board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
				join_plan2($board_break_info);
				
				$board_break_info = '';
				$board_break_info = insert_into_board_third($user_id,$real_p,$spill,$real_p);
				join_plan2($board_break_info);
			}		
			sleep(1);				
		}
		else
		{ 
			print "<font color=\"#FF0000\" size=\"3\"><center>Selected virtual parent already have two child !</center></font>"; 
		} 
	}		
		
		
		
	}
}

			 ?>
				<!-- Form Code Start -->
	<table align="center" border="0" width="600"><font color="#FF0000" size="+2" style="padding-top:50px"><center></center></font> 
			<!-- Form Code Start -->  
 
	<form name="form" id="registrarionForm" action="multi_register.php" method="post"  >
	<input type="hidden" name="user_id" value="<?php print $user_id; ?>"  />
	<tr>
    <td><p>Enter Sponsor Name </p></td>
    <td><p><input type="text" name="username" class="input-medium"  /></p></td>
    </tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
    <td><p>No. Of Registration</p></td>
    <td><p><input type="text" name="no_of_reg" class="input-medium"  /></p></td>
    </tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
				<td>Plan</td>
				<td><select name="epin_type" style="width:200px;">
				<?php
					$qu = mysqli_query($GLOBALS["___mysqli_ston"], "select * from setting ");
					while($rrr = mysqli_fetch_array($qu))
					{ 
						for($i = 1; $i < 4; $i++)
						{
							switch($i)
							{
								case 1 : $cost = 'first_board_join';
										 $plan_field = 'first_board_name';
										 break;
								case 2 : $cost = 'second_board_join';
										 $plan_field = 'second_board_name';
										 break;
								case 3 : $cost = 'third_board_join';
										 $plan_field = 'third_board_name';
										 break;
							}
						$plan_name = $rrr[$plan_field];
						$plan_id = $i;
						$amount = $rrr[$cost];
						?>
						<option value="<?php print $i; ?>"><?php print $plan_name.' ('.$amount.')'; ?></option>
				<?php	}	
				}	
				?>		</select>
				</td>
		  </tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
	<td><label for="name">Name</label> </td>
	<td><input type=text size=26 id="name" name=f_name class="input-medium" /><span style="color:#CC6666" id="nameInfo"></span> </td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
	<td width="30%">
	<label for="l_name"> Last Name </label></td>
	<td>
	<input type=text size=26 id="l_name" name=l_name class="input-medium" /><span style="color:#CC6666" id="l_nameInfo"></span> </td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr><td colspan="2" align="center">
	<input id="send" type="submit" name="submit" value="submit" class="button" />
	</td>
	</form>
				
	</tr></table>
		<?php

?> 

