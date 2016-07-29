<?php

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
	require_once("../function/insert_board_second.php");
	require_once("../function/insert_board_third.php");
	require_once("../function/insert_board_fourth.php");
	require_once("../function/insert_board_fifth.php");
	require_once("../function/insert_board_six.php");
	require_once("../validation/validation.php");  
	require_once("../function/rearrangement.php");
	require_once("../function/country_list1.php");
	require_once("../function/find_board.php");
	require_once("../function/export_all_database_into_sql.php");

$newp = $_GET['p'];
$plimit = "25";
?>

<?php
if(isset($_REQUEST['activate']))
{	
	
	 $user_id = $_REQUEST['user_id'];
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from temp_users where id_user = '$user_id' ");
	while($row = mysqli_fetch_array($query))
	{
		
		$f_name = $row['f_name'];
		$l_name = $row['l_name'];
		$name = $f_name.' '.$l_name;
		$real_p = $row['real_parent'];
		$real_parent_id = $real_p;
		$gender = $row['gender'];
		$date = $row['date'];
		$db_time = $row['time'];
		$time = date('h:i A' ,  $db_time );
		$username = $row['username'];
		$password = $row['password'];
		//$real_parent = get_user_name($real_parent);
		$dob = $row['dob'];
		$address = $row['address'];
		$email = $row['email'];
		$phone_no = $row['phone_no'];
		$type = 'B';
		$city = $row['city'];
		$country = $row['country'];
			
		$birthDate = $dob;
		$birthDate = explode("-", $birthDate);
		$birthDate = $birthDate[2].'-'.$birthDate[1];
		$time = time();
		$user_pin = mt_rand(100000, 999999);
		
	}
	$chk = user_exist($username);
	if($chk >0)
	{
		$error_username = "<B style=\"color:#FF0000; font-size:12pt;\">User Id '$username' is already exist!</B>";
	} 
	else
	{ 
		mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE temp_users SET type = '$type' WHERE id_user = '$user_id' ");
		mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO users (username,real_parent) VALUES ('$username' , '$real_parent_id') ");
			
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
	
			mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE users SET parent_id = '$users_parent_id' , real_parent = '$real_p' , position =  '$user_pos' , f_name = '$f_name' , l_name = '$l_name' , gender = '$gender' , email = '$email' , phone_no = '$phone_no' , city = '$city' , password = '$password' , dob = '$dob' , address = '$address' , time = '$time' , country ='$country' , user_pin = '$user_pin' , date = '$date' , activate_date = '$activate_date' , type = '$type' , pan_no = '$pancard_no' WHERE id_user = '$user_id' ");
											
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
			mysqli_query($GLOBALS["___mysqli_ston"], "update e_voucher set mode = 0 , used_date = '$date' , used_id = '$user_id' WHERE voucher = '$epin' and mode = 1 ");
				
			unset($_SESSION['board_second_breal_id']);
			unset($_SESSION['board_third_breal_id']);
			unset($_SESSION['board_fourth_breal_id']);
			unset($_SESSION['board_fifth_breal_id']);
			unset($_SESSION['board_sixth_breal_id']);
			unset($_SESSION['board_breal_id']);
			$spill = 0;
			
			$board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
			
			$count = count($board_break_info);
			for($pp = 0; $pp < $count; $pp++)
			{
				$first_id = $board_break_info[$pp][0];
				$qquu_first = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_second where user_id = '$first_id'");
				$num_first = mysqli_num_rows($qquu_first); 
				if($num_first == 0)
				{
					board_break_income($first_id,1,1);
					board_break_point($first_id,1);
					$real_par = get_real_parent($first_id);
			
					$board_break_info_second = insert_into_board_second($first_id,$real_par,$spill,$real_par);
					$count_second = count($board_break_info_second);
					for($ij = 0; $ij < $count_second; $ij++)
					{
						$second_id = $board_break_info_second[$ij][0];
						$qquu_second = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_third where user_id = '$second_id'");
						$num_second = mysqli_num_rows($qquu_second); 
						if($num_second == 0)
						{
							board_break_income($second_id,1,2);
							board_break_point($second_id,2);
							
							$real_par1 = get_real_parent($second_id);
							$board_break_info_third = insert_into_board_third($second_id,$real_par1,$spill,$real_par1);
							
							$count_third = count($board_break_info_third);
							for($jj = 0; $jj < $count_third; $jj++)
							{
								$third_id = $board_break_info_third[$jj][0];
								$qquu_third = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_fourth where user_id = '$third_id' ");
								$num_third = mysqli_num_rows($qquu_third); 
								if($num_third == 0)
								{
									board_break_income($third_id,1,3);
									board_break_point($third_id,3);
									
									$real_par2 = get_real_parent($third_id);
									$board_break_info_fourth = insert_into_board_fourth($third_id,$real_par2,$spill,$real_par2);
									
									$count_fourth = count($board_break_info_fourth);
									for($kk = 0; $kk < $count_fourth; $kk++)
									{
										$fourth_id = $board_break_info_fourth[$kk][0];
										$qquu_fourth = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_fifth where user_id = '$fourth_id' ");
										$num_fourth = mysqli_num_rows($qquu_fourth); 
										if($num_fourth == 0)
										{
											board_break_income($fourth_id,1,4);
											board_break_point($fourth_id,4);
									
											$real_par3 = get_real_parent($fourth_id);
											$board_break_info_fifth = insert_into_board_fifth($fourth_id,$real_par3,$spill,$real_par3);
											$count_fifth = count($board_break_info_fifth);
											for($nk = 0; $nk < $count_fifth; $nk++)
											{
												$fifth_id = $board_break_info_fifth[$nk][0];
												$qquu_fifth = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break_sixth where user_id = '$fifth_id' ");
												$num_fifth = mysqli_num_rows($qquu_fifth); 
												if($num_fifth == 0)
												{
													board_break_income($fifth_id,1,5);
													board_break_point($fifth_id,5);
												
													$real_par4 = get_real_parent($fourth_id);
													$board_break_info_sixth = insert_into_board_sixth($fifth_id,$real_par4,$spill,$real_par4);
												
													$count_sixth = count($board_break_info_sixth);
													for($nkk = 0; $nkk < $count_sixth; $nkk++)
													{
														$sixth_id = $board_break_info_sixth[$nk][0];
													
														$qquu_sixth = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_seven where user_id = '$sixth_id' ");
														$num_sixth = mysqli_num_rows($qquu_sixth); 
														if($num_sixth == 0)
														{
															mysqli_query($GLOBALS["___mysqli_ston"], "insert into board_seven (user_id) values ('$sixth_id') ");
															board_break_income($sixth_id,1,6);
															board_break_point($sixth_id,6);
														}
														else
														{
															board_break_income($sixth_id,2,6);
															board_break_point($sixth_id,6);
														}	
													}
												}
												else
												{
													board_break_income($fourth_id,2,5);
													board_break_point($fourth_id,5);
												}
											}			
										}
										else
										{
											board_break_income($fourth_id,2,4);
											board_break_point($fourth_id,4);
										}
									}
								}
								else
								{
									board_break_income($third_id,2,3);
									board_break_point($third_id,3);
								}
							}	
						}
						else
						{
							board_break_income($second_id,2,2);
							board_break_point($second_id,2);
						}
					}	
				}
				else
				{
					board_break_income($first_id,2,1);
					board_break_point($first_id,1);
				}
			}
		
			$position = $f_name = $l_name = $user_name = $gender = $address = $city = $provience = $country = $email = $phone = $username = $virtual_par = $password = $alert = $liberty = $re_password = $reg_mode = $pancard_no = $username = $epin = $real_parent = $year = $month = $day = "";
			
			$regtra = "<B style=\"color:#00274F; font-size:12pt;\">User Registration Successfully Completed !</B>";
				
		}
		else 
		{ 
			$regtra = "<B style=\"color:#FF0000; font-size:12pt;\">Selected virtual parent already have two child !</B>"; 
		}
	}
}



?>
<span style="color:#07537E; font-size:18px;"><B><?=$regtra; ?></B></span>
<?php
$sql = "select * from temp_users where type = 'L'";

$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);

$totalrows = mysqli_num_rows($query);
if($totalrows > 0)
{ ?>
<div class="ibox-content">
<table class="table table-bordered">
	<thead>	
	<tr>
		<th class="text-center">Sr. No.</th>
		<th class="text-center">User Id</th>
		<th class="text-center">Name</th>
		<th class="text-center">Date</th>
		<th class="text-center">Email</th>
		<th class="text-center">Phone No.</th>
		<th class="text-center">Action</th>
	</tr>
	</thead>
<?php
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		
		
	
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
	
	$srno = $start;

	$sql = "select * from temp_users where type = 'L' LIMIT $start,$plimit";
	$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
	while($row = mysqli_fetch_array($query))
	{
		$srno++;	
		$id = $row['id_user'];
		$username = $row['username'];
		$name = $row['f_name']." ".$row['l_name'];
		$type = $row['type'];
		$date = $row['date'];
		$phone_no = $row['phone_no'];
		$email = $row['email'];
		$db_time = $row['time'];
		$time = date('H:i:s' ,  $db_time );
	?>	
		<tr class="text-center">
			<td><?=$srno;?></td>
			<td><?=$username;?></td>
			<td><?=$name;?></td>
			<td><?=$date;?> <br> <?=$time;?></td>
			<td><?=$email;?></td>
			<td><?=$phone_no;?></td>"; 
			<td>
				<form action="index.php?page=help_customer" method="post">
					<input type="hidden" name="user_id" value="<?=$id; ?>"  />
					<input type="hidden" name="username" value="<?=$username; ?>"  />
					<input type="hidden" name="type" value="<?=$type; ?>"  />
					<input type="submit" name="activate" class="btn btn-primary" value="Activate"  />
				</form>
			</td>
		</tr>
<?php			
	}
	echo "</tbody></table></div>";
	?>
	<div id="DataTables_Table_0_paginate" class="dataTables_paginate paging_simple_numbers">
	<ul class="pagination">
	<?php
		if ($newp>1)
		{ ?>
			<li id="DataTables_Table_0_previous" class="paginate_button previous">
				<a href="<?="index.php?page=help_customer&p=".($newp-1);?>">Previous</a>
			</li>
		<?php 
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ ?>
				<li class="paginate_button ">
					<a href="<?="index.php?page=help_customer&p=$i";?>"><?php print_r("$i");?></a>
				</li>
				<?php 
			}
			else
			{ ?><li class="paginate_button active"><a href="#"><?php print_r("$i"); ?></a></li><?php }
		} 
		if ($newp<$pnums) 
		{ ?>
		   <li id="DataTables_Table_0_next" class="paginate_button next">
				<a href="<?="index.php?page=help_customer&p=".($newp+1);?>">Next</a>
		   </li>
		<?php 
		} 
		?>
		</ul></div>
<?php	
}
else
{
	echo "<B style=\"color:#FF0000; font-size:12pt;\">There is no information about that's user</B>";
}

