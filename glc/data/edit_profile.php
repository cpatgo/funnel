<?php
session_start();
require_once("config.php");
include("condition.php");
require_once("validation/validation.php"); 
include("function/setting.php");
include("function/functions.php");
include("function/send_mail.php");
include("function/country_list1.php");

$uploadfolder = 'profileImg/';
$id = $_SESSION['dennisn_user_id'];
$user_class = getInstance('Class_User');

printf('<article class="module width_full"><center>');
?>
<style type="text/css">
	
</style>
<?php
if(isset($_POST['submit']))
{
	$year = $_POST['year'];
	$month = $_POST['month'];
	$day = $_POST['day'];
	$dob = sprintf('%d-%d-%d', $year, $month, $day);
	$gender =$_POST['gender'];
	$address =$_POST['address'];
	$district =$_POST['district'];
	$city = $_POST['city'];
	$country =$_POST['country'];
	$phone =$_POST['phone'];
	// $email =$_POST['email'];
	$state = (isset($_POST['state'])) ? $_POST['state'] : '';
	// $payza = $_POST['payza'];

	$beneficiery_name = $_POST['beneficiery_name'];
	$ac_no = $_POST['ac_no'];
	$bank = $_POST['bank'];
	$branch = $_POST['branch'];
	$bank_code = $_POST['bank_code'];
	$tax_id = $_POST['tax_id'];

	if(!validateMessage($address) || !validateName($city))
	{ 
		printf('<div id="error"><ul>');
			  
		if(!validateName($city)) 		$error_city = "<font color=\"#FF0000\" size=\"2\"><center>Invalid City :</center></font>";
		// if(!validateEmail($email)) 		$error_email = "<font color=\"#FF0000\" size=\"2\"><center>Invalid E-mail</center></font>";
		// if(empty($email))		 		$error_email = "<font color=\"#FF0000\" size=\"2\"><center>E-mail is required</center></font>";
		if(!validateMessage($address))  $error_address = "<font color=\"#FF0000\" size=\"2\"><center>Invalid Address:</center></font>";
		// if(!validateEmail($payza))      $error_payza = "<font color=\"#FF0000\" size=\"2\"><center>Invalid Payza E-mail</center></font>";  
		// if(!validatePayza($payza)) 		$error_payza = "<font color=\"#FF0000\" size=\"2\"><center>Payza account already used by other member</center></font>";  
		printf('</ul></div>');
	}
	else
	{
		//Also update the password in wordpress database
		// include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
		// $userdata = array(
		// 	'ID' 	=> get_current_user_id(),
		// 	'user_email' => $email
		// );
		// $wp_user_id = wp_update_user($userdata);

		// if (is_wp_error($wp_user_id)) {
		// 	printf('<div class="alert alert-error">%s</div>', $wp_user_id->get_error_message());
		// } else {
			$sql = sprintf("UPDATE users SET city = '%s', address = '%s', gender = '%s', country = '%s', phone_no = '%s', dob = '%s', beneficiery_name = '%s', ac_no = '%s', bank = '%s', branch = '%s', bank_code = '%s', tax_id = '%s', district = '%s', state = '%s' WHERE id_user = %d",
						$city, $address, $gender, $country, $phone, $dob, $beneficiery_name, $ac_no, $bank, $branch, $bank_code, $tax_id, $district, $state, $id);
			mysqli_query($GLOBALS["___mysqli_ston"], $sql);

			//Update user meta
			$user_class->glc_update_usermeta($id, 'zip', (isset($_POST['zip'])) ? $_POST['zip'] : '');
			$user_class->glc_update_usermeta($id, 'company_name', (isset($_POST['company_name'])) ? $_POST['company_name'] : '');
			$user_class->glc_update_usermeta($id, 'company_address1', (isset($_POST['company_address1'])) ? $_POST['company_address1'] : '');
			$user_class->glc_update_usermeta($id, 'company_address2', (isset($_POST['company_address2'])) ? $_POST['company_address2'] : '');
			$user_class->glc_update_usermeta($id, 'company_city', (isset($_POST['company_city'])) ? $_POST['company_city'] : '');
			$user_class->glc_update_usermeta($id, 'company_state', (isset($_POST['company_state'])) ? $_POST['company_state'] : '');
			$user_class->glc_update_usermeta($id, 'company_country', (isset($_POST['company_country'])) ? $_POST['company_country'] : '');
			$user_class->glc_update_usermeta($id, 'company_zip', (isset($_POST['company_zip'])) ? $_POST['company_zip'] : '');
			$user_class->glc_update_usermeta($id, 'company_phone', (isset($_POST['company_phone'])) ? $_POST['company_phone'] : '');
			$user_class->glc_update_usermeta($id, 'company_tin', (isset($_POST['company_tin'])) ? $_POST['company_tin'] : '');

			$username = get_user_name($id);
			$updated_by = $username." Your self";
			include("function/logs_messages.php");
			data_logs($id,$data_log[1][0],$data_log[1][1],$log_type[1]);
			printf('<div class="alert alert-success">Successfully Updated</div>');
		// }		
	}	
}

function validatePayza($payza)
{
    $id = $_SESSION['dennisn_user_id'];
    if (!empty($payza))
    {
        $sql = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT payza_account FROM users WHERE payza_account = '$payza' AND id_user <> '$id'");
        $count = mysqli_num_rows($sql);

        if ($count > 0)
        {
            return false;
        }
        return true;
    }
}

$sql = sprintf('SELECT * FROM users WHERE id_user = %d', $id);
$user_class = getInstance('Class_User');
$user = $user_class->get_user($id);
$user = $user[0];
$year = date('Y', strtotime($user['dob']));
$month = date('m', strtotime($user['dob']));
$day = date('d', strtotime($user['dob']));
?>

<div class="ibox-content">	
<form name="money" action="index.php?page=edit_profile" method="post" enctype="multipart/form-data">			
<table class="table table-bordered edit_profile">
	<thead><tr><th colspan="3"><?=$Personnel_Details;?> :</th></tr></thead>
	<tbody>
	<!-- First Name -->
	<tr>
		<td><?=$First_Name;?><font color="#FF0000">*</font></td>
		<td><?=$user['f_name']; ?></td>
		<td><?=$error_f_name; ?></td>
	</tr>
	<!-- Last Name -->
	<tr>
		<td><?=$Last_Name;?><font color="#FF0000">*</font></td>
		<td><?=$user['l_name']; ?></td>
		<td><?php $error_l_name; ?></td>
	</tr>
	<!-- Date of Birth -->
	<tr>
		<td><?=$Date_Of_Birth;?></td>
		<td>
			Year 
			<select name="year">
				<option value="">YYYY</option>
				<?php
				$yr = date('Y');
			 	for($i = 1930; $i <= $yr; $i++) 
			 	{ ?>
				 	<option <?php if($year == $i) { ?> selected="selected" <?php } ?> value="<?=$i; ?>">
						<?=$i; ?>
					</option>
				<?php } ?> 
			</select>
			Month 
			<select name="month">
				<option value="">MM</option>
				<?php
				for($i = 1; $i <= 12; $i++) 
				{ ?>
				 	<option <?php if($month == $i) { ?> selected="selected" <?php } ?> value="<?=$i; ?>">
					<?=$i; ?></option>
				<?php } ?> 
			</select>
			Day 
			<select name="day">
				<option value="">DD</option>
				<?php
				 for($i = 1; $i <= 31; $i++) 
				 { ?>
				 	<option <?php if($day == $i) { ?> selected="selected" <?php } ?> value="<?=$i; ?>">
					<?=$i; ?></option>
				<?php } ?> 
			</select> 
	    </td>
		<td><?=$error_dob; ?></td>
	</tr>
	<!-- Gender -->
	<tr>
		<td><?=$Gender;?></td>
		<td> 
			<input type="radio" <?php if($user['gender'] == 'male'){ ?>checked="checked" <?php } ?> name="gender" value="male" /> &nbsp;Male
			<input type="radio" <?php if($user['gender'] == 'female') { ?> checked="checked" <?php } ?>name="gender" value="female"  />&nbsp;Female
		</td>
		<td></td>
	</tr>
	<thead><tr><th colspan="3"><?=$Contact_Details;?> :</th></tr></thead>
	<!-- Address 1 -->
	<tr>
		<td><?=$add_ress;?> <font color="#FF0000">*</font></td>
		<td>
			<textarea name="address"  id="message"  required /><?=$user['address']; ?></textarea>
			<span id="messageInfo"></span>
		</td>
		<td><?=$error_address; ?></td>
	</tr>
	<!-- Address 2 -->
	<tr>
		<td><?=$add_ress2;?></td>
		<td>
			<textarea name="district"  id="message" /><?=$user['district']; ?></textarea>
			<span id="messageInfo"></span>
		</td>
		<td></td>
	</tr>
	<!-- State -->
	<tr>	
		<td>State</td>
		<td>
			<input type="text" name="state" id="state" value="<?=$user['state']; ?>" required />
			<span id="stateInfo"></span>
		</td>
		<td><?=$error_state; ?></td>
	</tr>
	<!-- Zip Code -->
	<tr>	
		<td>Zip</td>
		<td>
			<input type="text" name="zip" id="postal" value="<?=$user_class->glc_usermeta($user['id_user'], 'zip'); ?>" />
			<span id="zipInfo"></span>
		</td>
		<td><?=$error_zip; ?></td>
	</tr>
	<!-- City -->
	<tr>	
		<td><?=$City;?> <font color="#FF0000">*</font></td>
		<td>
			<input type="text" name="city" id="city" value="<?=$user['city']; ?>" required />
			<span id="cityInfo"></span>
		</td>
		<td><?=$error_city; ?></td>
	</tr>
	<!-- Country -->
	<tr>
		<td><?=$Country;?> <font color="#FF0000">*</font></td>
		<td>
			<select name="country" required>
				<option value="">Select One</option>
				<?php
					$list = count($country_list);
					for($cl = 0; $cl < $list; $cl++)
					{ ?>
						<option value="<?=$country_list[$cl]; ?>" <?php if($country_list[$cl] == $user['country']) { ?> selected="selected" <?php } ?> ><?=$country_list[$cl]; ?></option>
					<?php } ?>
			</select>
		</td>
		<td><?=$error_country; ?></td>
	</tr>
	<!-- Phone No -->
	<tr>
		<td><?=$Phone_No; ?></td>
		<td><input type="text" name="phone" value="<?=$user['phone_no']; ?>" /></td>
		<td><?= $error_phone; ?></td>
	</tr>
	<!-- Email -->
	<tr>
		<td><?=$E_mail; ?> <font color="#FF0000">*</font></td>
		<td><?=$user['email']; ?></td>
		<td><?=$error_email; ?></td>
	</tr>
	<!-- Payza Account -->
	<!-- <thead><tr><th colspan="3"><?=$Payza_Account;?> :</th></tr></thead>
	<tr>
		<td><?=$Payza;?></td>
		<td>
			<input type="email" name="payza" value="<?=$user['payza']; ?>" />
			<?php 
				if(empty($payza)):
					printf('<br>Create a free payza account <a href="%s" target="_blank">here</a>.', $payza_referral_link);
				endif;
			?>
		</td>
		<td><?=$error_payza; ?></td>
	</tr> -->

	<!-- COMPANY DETAILS -->
	<thead><tr><th colspan="6">Company Details :</th></tr></thead>
	<tr>
		<td>Company Name</td>
		<td><input type="text" name="company_name" value="<?=$user_class->glc_usermeta($user['id_user'], 'company_name'); ?>" /></td>
	</tr>
	<tr>
		<td>Company Address 1</td>
		<td><input type="text" name="company_address1" value="<?=$user_class->glc_usermeta($user['id_user'], 'company_address1'); ?>" /></td>
	</tr>
	<tr>
		<td>Company Address 2</td>
		<td><input type="text" name="company_address2" value="<?=$user_class->glc_usermeta($user['id_user'], 'company_address2'); ?>" /></td>
	</tr>
	<tr>
		<td>City</label></td>
		<td><input type="text" name="company_city" value="<?=$user_class->glc_usermeta($user['id_user'], 'company_city'); ?>" /></td>
	</tr>
	<tr>
		<td>State</td>
		<td><input type="text" name="company_state" value="<?=$user_class->glc_usermeta($user['id_user'], 'company_state'); ?>" /></td>
	</tr>
	<tr>
		<td>Country</td>
		<td>
			<select name="company_country" required>
				<option value="">Select One</option>
				<?php
					$list = count($country_list);
					for($cl = 0; $cl < $list; $cl++)
					{ ?>
						<option value="<?=$country_list[$cl]; ?>" <?php if($country_list[$cl] == $user_class->glc_usermeta($user['id_user'], 'company_country')) { ?> selected="selected" <?php } ?> ><?=$country_list[$cl]; ?></option>
					<?php } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td>Zip</td>
		<td><input type="text" name="company_zip" value="<?=$user_class->glc_usermeta($user['id_user'], 'company_zip'); ?>" /></td>
	</tr>
	<tr>
		<td>Company Phone</td>
		<td><input type="text" name="company_phone" value="<?=$user_class->glc_usermeta($user['id_user'], 'company_phone'); ?>" /></td>
	</tr>
	<tr>
		<td>Company Tax Identification Number (FEIN)</td>
		<td><input type="text" name="company_tin" value="<?=$user_class->glc_usermeta($user['id_user'], 'company_tin'); ?>" maxLength="15" /></td>
	</tr>
	<!-- END OF COMPANY DETAILS -->

	<!-- <thead><tr><th colspan="6">Bank Details :</th></tr></thead>
	<tr>
		<td>Bank Account Name</td>
		<td><input type="text" name="beneficiery_name" value="<?=$user['beneficiery_name']; ?>" /></td>
	</tr>
	<tr>
		<td>Account No.</td>
		<td><input type="text" name="ac_no" value="<?=$user['ac_no']; ?>" /></td>
	</tr>
	<tr>
		<td>Bank Name</td>
		<td><input type="text" name="bank" value="<?=$user['bank']; ?>" /></td>
	</tr>
	<tr>
		<td>Branch Name</label></td>
		<td><input type="text" name="branch" value="<?=$user['branch']; ?>" /></td>
	</tr>
	<tr>
		<td>IFSC Code</td>
		<td><input type="text" name="bank_code" value="<?=$user['bank_code']; ?>" /></td>
	</tr>
	<tr>
		<td>Tax ID</td>
		<td><input type="text" name="tax_id" value="<?=$user['tax_id']; ?>" /></td>
	</tr> -->
	<tr>
		<td colspan="3" class="text-center">
			<input id="send" type="submit" name="submit" value="<?=$Update;?>" class="btn btn-primary btn-large"/> 
		</td>
	</tr>
	<tr><td colspan="3" style="text-align:left; color:#FF0000">* Mandatory Fields</td></tr>
	</tbody>
</table>
</form>	
</div>	

