<?php
	session_start();
	require_once("../config.php");
	//include("../condition.php");
	require_once("../validation/validation.php");
	include("../function/setting.php");
	include("../function/functions.php");
	include("../function/send_mail.php");
	include("../function/country_list1.php");

	if( file_exists( dirname( dirname(__DIR__) ) . '/include/helper.php') ) {
		include(dirname( dirname(__DIR__) ) . '/include/helper.php');
	}
?>

<?php
if(isset($_REQUEST['submit']))
{
	if($_POST['submit'] == 'Update') {

		$id_user = $_POST['id_user'];
		// $position = $_REQUEST['position'];
		// $epin = $_REQUEST['reg_epin'];
		// $real_parent = $_REQUEST['real_perent_id'];
		// $registration_product = $_REQUEST['registration_product'];
		$f_name =$_POST['f_name'];
		// $father_name =$_POST['father_name'];
		$l_name =$_POST['l_name'];
		$gender =$_POST['gender'];
		$country =$_POST['country'];
		$email =$_POST['email'];
		$phone =$_POST['phone'];
		$username = $_POST['username'];
		$password =$_POST['password'];
		$re_password =$_POST['re_password'];
		// $date = $systems_date; //date('Y-m-d');
		// $bank = $_POST['bank'];
		// $bank_code =$_POST['bank_code'];
		// $beneficiery_name = $_POST['beneficiery_name'];
		// $ac_no =$_POST['ac_no'];
		// $ifsc_code =$_POST['ifsc_code'];
		// $branch = $_POST['branch'];
		// $pan_no = $_POST['pan_no'];
		// $pin_code = $_POST['pin_code'];
		$address2 =$_POST['address2'];
		// $state = $_POST['state'];
		// $nominee =$_POST['nominee'];
		// $relation = $_POST['relation'];
		// $pin_code = $_POST['pin_code'];
		// $mobile_no = $_POST['mobile'];
		// $ge_currency =$_POST['ge_currency'];
		// $liberty =$_POST['liberty'];
		$state =$_POST['state'];
		$address1 =$_POST['address1'];
		$dob =$_POST['dob'];
		$city = $_POST['city'];
		// $account_type = $_POST['account_type'];
		$year = $_POST['year'];
		$month = $_POST['month'];
		$day = $_POST['day'];
		// $form_no = $_POST['form_no'];
		// $nominee_name = $_POST['nominee_name'];
		// $nominee_relation = $_POST['nominee_relation'];
		$dob = $year."-".$month."-".$day;

		// $nominee_year = $_POST['nominee_year'];
		// $nominee_month = $_POST['nominee_month'];
		// $nominee_day = $_POST['nominee_day'];
		// $nominee_dob = $nominee_year."-".$nominee_month."-".$nominee_day;


 
		if( !validateName($_POST['f_name']) || !validateLname($_POST['l_name']) || !validateMessage($_POST['address'])
			|| !validatePhone($_POST['phone'])
			|| !validateEmail($_POST['email'])
			|| !validateName($_POST['city'])
			|| !validateUsername($_POST['username']) ) {

		$hashed = sha1($password);

		
		

		$query_string = "UPDATE users SET f_name='$f_name' , l_name='$l_name' , email='$email' , phone_no='$phone' , city='$city', dob='$dob' , address='$address1', district='$address2', state='$state',  gender='$gender', country='$country' ";

		

		if ( $_POST['password'] && $_POST['re_password'] ) { 

			if (matchPasswordFields( $_POST['password'], $_POST['re_password'] )) {
				$query_string .= ", password = '$hashed'";	
			}
		}

		$query_string .= " WHERE id_user = $id_user; ";

		// dd($query_string);

		// insert to db
		mysqli_query($GLOBALS["___mysqli_ston"], $query_string);

		$date = date('Y-m-d');
		$username = get_user_name($id);
		$updated_by = $username." Your self";
		//include("function/logs_messages.php");
		//data_logs($id,$data_log[1][0],$data_log[1][1],$log_type[1]);
		echo "<strong>Successfully Updated</strong>";


		} 
		else {
			$update_q = 0;
			if($_SESSION['changed_username'] == $username)
			{
				$update_q = 1;
			}
			else
			{
				if($username == '')
				{
					$update_q = 0;
					print "<font color=\"#FF0000\" size=\"2\">Invalid Username !</font>";
				}
				else
				{
					$chk = user_exist($username);
					if($chk > 1)
					{
						$update_q = 0;
						print "<font color=\"#FF0000\" size=\"2\">Username Allready Exists !</font>";
					}
					else
						$update_q = 1;
				}
			}



			if($update_q == 1)
			{

					/*
						First Name, Last Name, DoB, Gender, Address, City, Country, Phone No., Email, Password, Confirm Password
					*/

					$hashed = sha1($password);
					


					$query_string = "UPDATE users SET f_name = '$f_name' ,
						l_name = '$l_name' , email = '$email' , phone_no = '$phone' ,
						city = '$city', dob = '$dob' ,
						address = '$address1', gender = '$gender',
						state='$state', district='$address2',
						country ='$country', ";

					if ( $_POST['password'] && $_POST['re_password'] ) {
						$query_string .= "password = '$hashed' ,";
					}

					$query_string .= "WHERE id_user = '$id_user' ";
					mysqli_query($GLOBALS["___mysqli_ston"], $query_string);


					$date = date('Y-m-d');
					$username = get_user_name($id);
					$updated_by = $username." Your self";
					//include("function/logs_messages.php");
					//data_logs($id,$data_log[1][0],$data_log[1][1],$log_type[1]);
					echo "<B>Successfully Updated</B>";

			}
		}
	}



	elseif($_REQUEST['submit'] == 'submit')
	{

		$username = $_SESSION['changed_username'] =$_REQUEST['username'];
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where username = '$username' ");
		$num = mysqli_num_rows($query);
		if($num == 0)
		{
			print "Please enter Correct username !!";
		}
		else
		{
			while($row = mysqli_fetch_array($query))
			{
				$id_user = $row['id_user'];
				$f_name = $row['f_name'];
				$l_name = $row['l_name'];
				$gender = $row['gender'];
				$dob = $row['dob'];
				$phone = $row['phone_no'];
				$city = $row['city'];
				// $mg = $_REQUEST['mg'];
				$country = $row['country'];
				// $provience = $row['provience'];
				$address1 = $row['address'];
				// $alert_email = $row['alert_email'];
				// $liberty_email = $row['liberty_email'];

				// $ac_no = $row['ac_no'];
				// $bank = $row['bank'];
				// $branch = $row['branch'];
				// $bank_code = $row['bank_code'];
				// $beneficiery_name = $row['beneficiery_name'];

				// $father_name = $row['father_name'];
				// $ge_currency =$row['ge_currency'];
				// $password =$row['password'];
				$username = $row['username'];
				// $liberty =$row['liberty_email'];
				$email =$row['email'];
				$phone =$row['phone_no'];
				// $pan_no = $row['pan_no'];
				$address2 =$row['district'];
				$state = $row['state'];
				// $pin_code = $row['pin_code'];
				// $account_type = $row['account_type'];
				// $form_no = $row['form_no'];

				// $nominee_name = $row['nominee_name'];
				// $nominee_relation = $row['nominee_relation'];
				// $nominee_dob = $row['nominee_dob'];
			}
			$year = date("Y", strtotime(" $dob ") );
			$month = date("m", strtotime(" $dob ") );
			$day = date("d", strtotime(" $dob ") );

			$nominee_year = date("Y", strtotime(" $nominee_dob ") );
			$nominee_month = date("m", strtotime(" $nominee_dob ") );
			$nominee_day = date("d", strtotime(" $nominee_dob ") );
			 ?>

		<div class="ibox-content">
		<form name="money" action="index.php?page=edit_profile" method="post">
		<table class="table table-bordered edit_profile">
			<input type="hidden" name="id_user" value="<?=$id_user; ?>"  />
			<!--<thead><tr><th colspan="3">Official Details :</th></tr></thead>
			<tr>
				<td>From Sr. No  <font color="#FF0000">*</font></td>
				<td><input type="text" name="form_no" value="<?=$form_no;?>" /></td>
				<td><?=$error_form_no;?></td>
			</tr>-->
			<thead><tr><th colspan="3">Personal Details :</th></tr></thead>
			<tr>
				<td>First Name <font color="#FF0000">*</font></td>
				<td> <input type="text" name="f_name" value="<?=$f_name;?>" /></td>
				<td><?=$error_f_name;?></td>
			</tr>
			<tr>
				<td>Last Name <font color="#FF0000">*</font></td>
				<td> <input required type="text" name="l_name" value="<?=$l_name;?>" /></td>
				<td><?=$error_l_name; ?></td>
			</tr>
			<!--<tr>
				<td>Father's Name <font color="#FF0000">*</font></td>
				<td> <input type="text" name="father_name" value="<?=$father_name;?>" /></td>
				<td><?=$error_l_name; ?></td>
			</tr>-->
			<tr>
				<td>Date of Birth</td>
				<td>
				<!-- <span>Year </span> -->
					<select name="year" style="width:70px;">
						<option value="">YYYY</option>
						<?php
						$yr = date('Y-m-d', strtotime("-13 year"));
						 for($i = 1969; $i <= $yr; $i++)
						 { ?>
							<option <?php if($year == $i) { ?> selected="selected" <?php } ?>
							value="<?=$i; ?>"><?=$i; ?></option>
					<?php } ?>
					</select>
					<!-- <span>Month </span> -->
					<select name="month" style="width:52px;">
						<option value="">MM</option>
						<?php
						 for($i = 1; $i <= 12; $i++)
						 { ?>
							<option <?php if($month == $i) { ?> selected="selected" <?php } ?>
							value="<?=$i; ?>"><?=$i; ?></option>
					<?php } ?>
					</select>
					<!-- <span>Day </span> -->
					<select name="day" style="width:52px;">
						<option value="">DD</option>
						<?php
						 for($i = 1; $i <= 31; $i++)
						 { ?>
							<option <?php if($day == $i) { ?> selected="selected" <?php } ?>
							value="<?=$i; ?>"><?=$i; ?></option>
					<?php } ?>
					</select>
					<span id="dateInfo"></span>
				</td>
				<td><?=$error_dob; ?></td>
			</tr>
			<tr>
				<td>Gender</td>
				<td>
					<input type="radio" <?php if($gender == 'male') { ?> checked="checked" <?php } ?>  name="gender" value="male" /> &nbsp;Male
					<input type="radio" name="gender" value="female" <?php if($gender == 'female') { ?> checked="checked" <?php } ?> />&nbsp;Female</td>
				<td><?=$error_gender; ?></td>
			</tr>
			<!--<thead><tr><th colspan="3">Nominee Details :</th></tr></thead>
			<tr>
				<td>Nominee Name  <font color="#FF0000">*</font></td>
				<td><input type="text" name="nominee_name" value="<?=$nominee_name;?>" /></td>
				<td><?=$error_nominee_name;?></td>
			</tr>
			<tr>
				<td>Nominee Relation <font color="#FF0000"></font></td>
				<td><input type="text" name="nominee_relation" value="<?=$nominee_relation; ?>" /></td>
				<td><?=$error_nominee_relation; ?></td>
			</tr>
			<tr>
				<td>Nominee Date of Birth  <font color="#FF0000"></font></td>
				<td>
					<select name="nominee_year" style="width:70px;">
					<option value="">YYYY</option>
					<?php
							$yr = date('Y');
						 for($i = 1930; $i <= $yr; $i++)
						 { ?>
							<option <?php if($nominee_year == $i) { ?> selected="selected" <?php } ?>
							value="<?=$i; ?>"><?=$i; ?></option>
					<?php } ?>
					</select>
					<select name="nominee_month" style="width:52px;">
						<option value="">MM</option>
						<?php
						 for($i = 1; $i <= 12; $i++)
						 { ?>
							<option <?php if($nominee_month == $i) { ?> selected="selected" <?php } ?>
							value="<?=$i; ?>"><?=$i; ?></option>
					<?php } ?>
					</select>
					<select name="nominee_day" style="width:52px;">
						<option value="">DD</option>
						<?php
						 for($i = 1; $i <= 31; $i++)
						 { ?>
							<option <?php if($nominee_day == $i) { ?> selected="selected" <?php } ?>
							value="<?=$i; ?>"><?=$i; ?></option>
					<?php } ?>
					</select>
				</td>
				<td style="text-align:left;"><?=$error_nominee_dob; ?></td>
			</tr>-->
			<thead><tr><th colspan="3">Contact Details :</th></tr></thead>
			<tr>
				<td>Address 1 <span class="required">*</span></td>
				<td><textarea required name="address1" style="width:185px" /><?=$address1; ?></textarea></td>
				<td><?=$error_address1; ?></td>
			</tr>
			<tr>
				<td>Address 2 </td>
				<td><textarea required name="address2" style="width:185px" /><?=$address2; ?></textarea></td>
				<td><?=$error_address2; ?></td>
			</tr>
			<tr>
				<td>City <font color="#FF0000">*</font></td>
				<td> <input type="text" name="city" value="<?=$city; ?>" required /></td>
				<td><?=$error_city; ?></td>
			</tr>
			<!--<tr>
				<td>District <font color="#FF0000">*</font></td>
				<td>
					<input type="text" name="district" value="<?=$district; ?>" />
				</td>
				<td><?=$error_district; ?></td>
			</tr>-->
			<tr>
				<td>State <font color="#FF0000">*</font></td>
				<td><input required type="text" name="state" value="<?=$state; ?>" /></td>
				<td><?=$error_state; ?></td>
			</tr>
			<tr>
				<td>Country <font color="#FF0000">*</font></td>
				<td >
					<select style="width:182px;" name="country" required>
					<option value="">Select One</option>
					<?php
						$list = count($country_list);
						for($cl = 0; $cl < $list; $cl++)
						{ ?>
							<option value="<?=$country_list[$cl]; ?>"
							<?php if($country_list[$cl] == $country) { ?> selected="selected" <?php } ?> >
							<?=$country_list[$cl]; ?></option>
						<?php } ?>
					</select>
				</td>
				<td style="text-align:left;"><?=$error_country; ?></td>
			</tr>
			<!--<tr>
				<td>Pin Code <font color="#FF0000"></font></td>
				<td><input type="text" name="pin_code" value="<?=$pin_code; ?>" /></td>
				<td style="text-align:left;"><?=$error_pin_code; ?></td>
			</tr>-->
			<tr>
				<td>Phone No. <font color="#FF0000">*</font></td>
				<td><input required type="text" name="phone" value="<?=$phone; ?>" /></td>
				<td><?=$error_phone; ?></td>
			</tr>
			<tr>
				<td>E-mail <span class="required">*</span></td>
				<td><input required type="text" name="email" value="<?=$email; ?>" /></td>
				<td><?=$error_email; ?></td>
			</tr>
			<thead><tr><th colspan="3">Override Member's Password</th></tr></thead>
			<!--<tr>
				<td>Username <font color="#FF0000">*</font></td>
				<td> <input type="text" size=26 name="username" value="<?=$username; ?>" /></td>
				<td><?=$error_username; ?></td>
			</tr>-->
			<tr>
				<td>Password <font color="#FF0000">*</font></label></td>
				<td><input type="password" name="password" value="" autocomplete="off" /></td>
				<td><?=$error_password; ?></td>
			</tr>
			<tr>
				<td>Confirm Password <font color="#FF0000">*</font></td>
				<td> <input type="password" name="re_password" value="" autocomplete="off" /></td>
				<td><?=$error_re_password; ?></td>
			</tr>
			<!--<thead><tr><th colspan="3">Bank Details :</th></tr></thead>
			<tr>
				<td>Bank Account Name</label></td>
				<td><input type="text" name="beneficiery_name" value="<?=$beneficiery_name; ?>" /></td>
				<td><?=$error_beneficiery_name; ?></td>
			</tr>
			<tr>
				<td>Account No.</label></td>
				<td><input type="text" name="ac_no" value="<?=$ac_no; ?>" /></td>
				<td><?=$error_ac_no; ?></td>
			</tr>
			<tr>
				<td>Bank Name</label></td>
				<td><input type="text" name="bank" value="<?=$bank; ?>" /></td>
				<td><?=$error_bank; ?></td>
			</tr>
			<tr>
				<td>Branch Name</td>
				<td><input type="text" name="branch" value="<?=$branch; ?>" /></td>
				<td><?=$error_bank; ?></td>
			</tr>
			<tr>
				<td>IFSC Code</td>
				<td><input type="text" name=bank_code value="<?=$bank_code; ?>" /></td>
				<td><?=$error_bank_code; ?></td>
			</tr>
			<tr>
				<td>PAN No.</td>
				<td><input type="text" name="pan_no" value="<?=$pan_no; ?>" /></td>
				<td><?=$error_pan_no; ?></td>
			</tr>-->
			<tr>
				<td colspan="3" class="text-center">
					<input type="submit" name="submit" value="Update" class="btn btn-primary" />
				</td>
			</tr>
			<tr><td colspan="3" style="color:#FF0000">* Mendatory Fields</td></tr>
			</tbody>
		</table>
		</form>
		</div>


<?php 		}
	}

}
else
{
	$user_str = '';
?>


<div class="row">
	<div class="col-lg-6">
		<div class="ibox-content">
			<h5>Other Info</h5>
		</div>
	</div>
<div class="col-lg-6">
<div class="ibox-content">
<form name="my_edit_form" action="index.php?page=edit_profile" method="post">

		<?php
			$username = $_SESSION['changed_username'] =$_REQUEST['username'];
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "select username from users;");
			$num = mysqli_num_rows($query);
			

			while($row = mysqli_fetch_array($query)) {
				$user_str .= '"' . $row['username'] . '", ';
			}
		?>


<table class="table table-bordered">
	<thead><tr><th colspan="2">Edit profile</th></tr></thead>
	<tbody>
	<tr>
		<th>Enter Username </th>
		<td><input type="text" name="username" class="username_select" required /></td>
	</tr>
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="submit" value="submit" class="btn btn-primary" />
		</td>
	</tr>
	</tbody>
</table>
</form>
</div>

<?php
}
?>
	</div>
</div>


<script type="text/javascript">
  $(document).ready(function() {

  	// console.log('<?php echo $num; ?>');

	var availableTags = [
		<?php echo $user_str; ?>
	];

	$( ".username_select" ).autocomplete({
	  source: availableTags
	});
  });

</script>
