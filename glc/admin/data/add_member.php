<?php
session_start();
	require_once("../config.php");
	include("condition.php");
	include("../function/setting.php");
	include("../function/functions.php");
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
	require_once("../validation/validation.php");  
	require_once("../function/rearrangement.php");
	require_once("../function/country_list1.php");
	require_once("../function/find_board.php");
	require_once("../function/export_all_database_into_sql.php");

		
if(isset($_POST['submit']))
{
	if($_POST['submit'] == 'Enter')
	{ 
		$username = $_REQUEST['username'];
	
		$id_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE username = '$username' ");
		$num = mysqli_num_rows($id_query);
		if($num == 0)
		{
			print "<font color=\"#FF0000\" size=\"+2\">Please enter correct Username !</font>";
		}
		else 
		{
			while($row = mysqli_fetch_array($id_query))
			{
				$user_id = $row['id_user'];
			} ?>
				<!-- Form Code Start -->
<div class="comment odd alt thread-odd thread-alt depth-1" style="width:90%">
				<!-- Form Code Start -->  
				<div id="container"> 
				<form name="form" id="registrarionForm" action="index.php?page=add_member" method="post"  >
				<input type="hidden" name="user_id" value="<?php print $user_id; ?>"  />
<label for="name">Name</label> <input type=text size=26 id="name" name=f_name class="input-medium" /><span id="nameInfo"></span> 
				<label for="l_name"> Last Name </label><input type=text size=26 id="l_name" name=l_name class="input-medium" /><span id="l_nameInfo"></span> 
				<label for="date">Date of Birth</label><input type=text id="date" size=26 name=dob class="input-medium flexy_datepicker_input"/><span id="dateInfo"></span>
				<div class="form_label"> Gender </div><div class="form_data"><input type="radio" name=gender value="male" checked="checked" />	<strong>Male</strong><input type="radio" name=gender value="female" /><strong>Female</strong></div>
				 <label for="message">Address</label><textarea name=address  id="message" style="height:50px; width:240px" /></textarea><span id="messageInfo"></span>
				<label for="city">City </label><input type=text size=26 name=city id="city" class="input-medium" /><span id="cityInfo"></span>
				<label for="provience">Provience </label><input type=text size=26 name=provience id="provience" class="input-medium" /><span id="provienceInfo"></span>
				<label for="country"> Country </label>
					<select name=country id="country">
					<option value="">Select One</option>
					<?php
						$list = count($country_list);
						for($cl = 0; $cl < $list; $cl++)
						{ ?>
						<option value="<?php print $country_list[$cl]; ?>"><?php print $country_list[$cl]; ?></option>
					<?php } ?>
					</select>
				<span id="countryInfo"></span> 
				 <label for="email">E-mail</label> <input type=text size=26 id="email" name=email class="input-medium" /><span id="emailInfo">Valid E-mail please, you will need it to log in!</span>
				<label for="phone"> Phone No.</label><input type=text size=26 name=phone id="phone" class="input-medium" /><span id="phoneInfo"></span>
				<label for="username"> User Name </div><div class="form_data"><input type=text size=26 name=username id="username" class="input-medium" /><span id="usernameInfo"></span>
				<label for="pass1">Password</label>  <input type="password" size=26 id="pass1" name=password class="input-medium" /><span id="pass1Info">At least 5 characters: letters, numbers and '_'</span> 
				<label for="pass2">Confirm Password</label><input type="password" id="pass2" size=26 name=re_password class="input-medium" /><span id="pass2Info">Confirm password</span>
				<div>
				<input id="send" type="submit" name="submit" value="submit" class="button" />
				</div>
				</form>
				
				</div> 
				
				<script type="text/javascript" src="../validation/jquery.js"></script>  
        		<script type="text/javascript" src="../validation/validation.js"></script> 
			</div> 
			<?php
		}	
	}		
	elseif(($_POST['submit'] == 'submit'))
	{
		if(!validateEmail($_POST['email']) || !validateUsername($_POST['username']) || !validatePhone($_POST['phone']) || !validatePasswords($_POST['password'], $_POST['re_password']) || !validateDate($_POST['dob']) || !validateCountry($_POST['country']) || !validateName($_POST['f_name']) || !validateLname($_POST['l_name']) || !validateProvience($_POST['provience']) || !validateCity($_POST['city']) || !validateMessage($_POST['address']) )
		 { ?>  
		 	<div id="error">  
                 <ul>  
							<?php if(!validateUsername($_POST['username'])):?>  
                                <li style="color:#CC3300";><strong>Invalid Username:</strong></li>  
                            <?php endif?> 
							<?php if(!validatePhone($_POST['phone'])):?>  
                                <li style="color:#CC3300";><strong>Invalid Phone:</strong></li>  
                            <?php endif?>  
							<?php if(!validateDate($_POST['dob'])):?>  
                                <li style="color:#CC3300";><strong>Invalid Date:</strong></li>  
                            <?php endif?> 
							<?php if(!validateCountry($_POST['country'])):?>  
                                <li style="color:#CC3300";><strong>Please Enter Country:</strong></li>  
                            <?php endif?>
                            <?php if(!validateEmail($_POST['email'])):?>  
                                <li style="color:#CC3300";><strong>Invalid E-mail:</strong></li>  
                            <?php endif?>  
                            <?php if(!validatePasswords($_POST['password'], $_POST['re_password'])):?>  
                                <li style="color:#CC3300";><strong>Passwords are invalid:</strong></li>  
                            <?php endif?> 
							<?php if(!validateName($_POST['f_name'])):?>  
                                <li style="color:#CC3300";><strong>Invalid First Name:</strong></li>  
                            <?php endif?> 
							<?php if(!validateLname($_POST['l_name'])):?>  
                                <li style="color:#CC3300";><strong>Invalid Last Name:</strong></li>  
                            <?php endif?>  
							<?php if(!validateProvience($_POST['provience'])):?>  
                                <li style="color:#CC3300";><strong>Invalid Provience:</strong></li>  
                            <?php endif?> 
							<?php if(!validateCity($_POST['city'])):?>  
                                <li style="color:#CC3300";><strong>Invalid City:</strong></li>  
                            <?php endif?>
                            <?php if(!validateMessage($_POST['address'])):?>  
                                <li style="color:#CC3300";><strong>Invalid Address:</strong></li>  
                            <?php endif?>   
                            
                        </ul>  
                    </div>  
       <?php }
		else
		{  
			
				$validator = new FormValidator();
				//$validator->addValidation("f_name","req","Please fill in First Name");
				//$validator->addValidation("l_name","req","Please fill in Last Name");
				//$validator->addValidation("dob","req","Please fill Date of Birth");
				//$validator->addValidation("gender","req","Please fill in Gender");
				//$validator->addValidation("address","req","Please fill in Address");
				//$validator->addValidation("city","req","Please fill City");
				//$validator->addValidation("provience","req","Please fill in Provience");
				//$validator->addValidation("country","req","Please fill in Country");
				$validator->addValidation("email","email","Please Enter a valid Email Id");
				$validator->addValidation("email","req","Please fill in Email");
				$validator->addValidation("phone","req","Please fill in Phone");
				$validator->addValidation("username","req","Please fill in username");
				$validator->addValidation("password","req","Please fill password");
				$validator->addValidation("re_password","req","Please fill re_password");
				$password =$_POST['password'];
				$re_password =$_POST['re_password'];
				if($password != $re_password)
				{	print "please enter same password in both field!"; die; } 
			
				if($validator->ValidateForm())
				{
					//Validation success. 
					//Here we can proceed with processing the form 
					//(like sending email, saving to Database etc)
					// In this example, we just display a message
					//echo "<h2>Validation Success!</h2>";
					//$show_form=false;
					
				  
					$type = "B";
					$f_name =$_POST['f_name'];
					$l_name =$_POST['l_name'];
					$user_name = $f_name." ".$l_name;
					$dob =$_POST['dob'];
					$gender =$_POST['gender'];
					$address =$_POST['address'];
					$city =$_POST['city'];
					$provience =$_POST['provience'];
					$country =$_POST['country'];
					$email =$_POST['email'];
					$phone =$_POST['phone'];
					$username = $_POST['username'];
					
					$password =$_POST['password'];
					$alert = $_POST['alert'];
					$liberty =$_POST['liberty'];
					$re_password =$_POST['re_password'];
					$date = date('Y-m-d');
					$reg_mode =$_POST['reg_mode'];
					$reg_amount = $_SESSION['registration_amount'];	
					$number =2;
					
					$real_p = $real_parent_id = $from = $_POST['user_id'];
					$user_pin = mt_rand(100000, 999999);
				  	$username = $_POST['username'];
				  	
					if($virtual_parent_condition == 1) // checking condition
					{
						$chk = user_exist($username);
						if($chk >0)
						{
							echo "<font color=\"#FF0000\" size=\"+2\">User name $username is already stored!</font>";
						}
						else
						{ 	
							mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO users (username,real_parent) VALUES ('$username' , '$real_parent_id') ");
							
							$real_parent_username_log = get_user_name($real_p);
							include("../function/logs_messages.php");
							data_logs($from,$data_log[3][0],$data_log[3][1],$log_type[3]);
							
							$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id_user FROM users WHERE username = '$username' ");
							while($row = mysqli_fetch_array($query))
							{
								$user_id = $row[0];
							}
							insert_wallet();                  // inserting in wallet
							data_logs($from,$data_log[4][0],$data_log[4][1],$log_type[4]);  // inserting in wallet log
							$par = get_par($user_id);
							$user_pos = $par[1][0];          //user position
							$users_parent_id = $par[0][1];  //parent id
							$children = geting_virtual_parent($users_parent_id);
							if($children < 2)
							{
								mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE users SET parent_id = '$users_parent_id' , real_parent = '$real_p' , position =  '$user_pos' , f_name = '$f_name' , l_name = '$l_name' , gender = '$gender' , email = '$email' , phone_no = '$phone' , city = '$city' , password = '$password' , dob = '$dob' , address = '$address' , provience = '$provience' , country ='$country' , user_pin = '$user_pin' , date = '$date' , type = '$type' WHERE id_user = '$user_id' ");
											
								
								
								//new registration message
								$title = "new User register";
								$to = $email;
								$db_msg = $email_welcome_message;
								include("../function/full_message.php");
								$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
								$SMTPChat = $SMTPMail->SendMail();
								
								//direct member message
								$real_parent_username = get_user_name($real_parent_id);
								//$new_username = $username;
								//$to = get_user_email($real_parent_id);
								//$db_msg = $direct_member_message;
								//include("../function/full_message.php");
								//$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
								//$SMTPChat = $SMTPMail->SendMail();
															
								$user_id = get_new_user_id($username); //newlly entered user id
								
								$par_id = get_real_parent($user_id);
								
								$t = time(); 
								$reg_pin_used = $_SESSION['add_member_epin'];	
									//mysql_query("update e_voucher set mode = 0 , used_date = '$date' , used_id = '$user_id' where voucher = '$reg_pin_used' ");
								include("../function/logs_messages.php");
								data_logs($from,$data_log[10][0],$data_log[10][1],$log_type[10]);	
									
								direct_member_income($user_id);  //direct income
								
								unset($_SESSION['add_member_epin']);							
									
								unset($_SESSION['board_breal_id']);
								$spill = 0;
								$board_break_info = insert_into_board($user_id,$real_p,0,$spill);
								$countt = count($board_break_info);
								
								
								board_break_income($board_break_info);
								
								//backup_tables($backup_path);
								
								print "<font color=\"#00274F\" size=\"3\"><b>User Registration Successfully Completed !</b></font>";
							
							}
							else { print "Selected virtual parent already have two child !"; } 
						} 
				   }
				}
				else				
				{
					echo "<B>Validation Errors:</B>";
			
					$error_hash = $validator->GetErrors();
					foreach($error_hash as $inpname => $inp_err)
					{
						echo "<p>$inpname : $inp_err</p>\n";
					}        
				}
		}	
	}
	else { print "<font color=\"#FF0000\" size=\"+2\">There is some conflict!!</font>"; }	 
}
else
{  ?>

<table width="50%" border="0">
<form name="myform" action="index.php?page=add_member" method="post">
  <tr>
    <td colspan="2">&nbsp;</td>
  
  </tr>
  <tr>
    <td><p>Enter Username :</p></td>
    <td><p><input type="text" name="username" class="input-medium"  /></p></td>
  </tr>
  <tr>
    <td colspan="2"><p align="center"><input type="submit" name="submit" value="Enter" class="button"  /></p></td>
  </tr>
</table>

<?php  } ?>
