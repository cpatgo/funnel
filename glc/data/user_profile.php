<?php
session_start();
include('condition.php');
require_once("config.php");
include("function/setting.php");
include("function/send_mail.php");
include("function/functions.php");
include 'function/uploadpicture.php';
$id = $_SESSION['dennisn_user_id'];
$user_class = getInstance('Class_User');
$user = $user_class->get_user($id);

$is_company_document_approved = $user_class->is_company_document_approved($id, $user[0]['country']);
		
$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE id_user = '$id' ");
while($row = mysqli_fetch_array($query))
{
	$f_name = $row['f_name'];
	$l_name = $row['l_name'];
	$father_name = $row['father_name'];
	$gender = $row['gender'];
	$age = $row['dob'];
	$email = $row['email'];
	$phone = $row['phone_no'];
	$city = $row['city'];
	
	$parent_id = get_user_name($row['parent_id']);
	$real_parent = get_user_name($row['real_parent']);
	$pos = $row['position'];
	if($pos == 0)
		$position = "Left Power Leg";
	else
		$position = "Right Power Leg";	
	
	$user_name = $row['username'];
	$step = $row['step'];
	$address = $row['address'];
	$provience = $row['provience'];
	$country = $row['country'];
	$district =$row['district'];
	$state = $row['state'];
	$pan_no = $row['pan_no'];
	
	$ac_no = $row['ac_no'];
	$bank = $row['bank'];
	$branch = $row['branch'];
	$bank_code = $row['bank_code'];
	$beneficiery_name = $row['beneficiery_name'];
	$account_type = $row['account_type'];
	$tax_id = $row['tax_id'];
	
	$nominee_name = $row['nominee_name'];
	$nominee_relation = $row['nominee_relation'];
	$nominee_dob = $row['nominee_dob'];
	$form_no = $row['form_no'];
	$user_img = $row['user_img'];
	$postal_code = $row['pin_code'];
	
	$payza = $row['payza_account'];

	$country = $row['country'];
	$zip = $user_class->glc_usermeta($row['id_user'], 'zip');
	
} ?>

<?php if(isset($_GET['err']) && !empty($_GET['err'])) printf('<div class="alert alert-danger">%s</div>', $_GET['err']); ?>
<?php if(isset($_GET['msg']) && (int)$_GET['msg'] === 1) printf('<div class="alert alert-success">Profile photo is successfully uploaded!</div>'); ?>

<div class="ibox-content">	
	<form enctype="multipart/form-data" action="index.php?page=user_profile" method="post">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Change Profile Picture:</th>
					<td>
						<input type="hidden" name="doctype" value="2" />
						<input type="hidden" name="MAX_FILE_SIZE" value="9999999" />
						<input name="userfile" type="file" class="display-grid" />
						<button class="btn btn-primary float-right">Update Picture Now</button>
					</td>
				</tr>
			</thead>
		</table>
	</form>
</div>

<div class="ibox-content">	
<table class="table table-bordered">
	<thead>
		<tr>
			<th colspan="4"><?=$offic_detail;?> :
				<div class="pull-right"><a class="btn btn-primary btn-sm" href="index.php?page=edit_profile"><i class="fa fa-edit"></i> Edit Profile</a></div>
			</th>
		</tr>
	</thead>
	<tbody>
	<tr>
		<td><B><?=$From_Sr_No;?></B></td>
		<td><?=$form_no; ?></td>
		<!--<td rowspan="6"><img src="profileImg/<?=$user_img; ?>" width="120" /></td>-->
	</tr>
	<thead><tr><th colspan="4">Personal Details :</th></tr></thead>
	<tr>
		<td><B><?=$First_Name; ?></B></td>
		<td><?=$f_name; ?></td>
	
		<td><B><?=$Last_Name; ?></B></td>
		<td><?=$l_name; ?></td>
	</tr>
	<tr>
		<!--<td>Father's Name</td>
		<td><?=$father_name; ?></td>-->
<!-- 
		<td><B><?=$Date_Of_Birth; ?></B></td>
		<td><?=$age; ?></td> -->

<!-- 		<td><B><?=$Gender; ?></B></td>
		<td><?php if($gender == 0) print "Male"; else print "Female"; ?></td> -->
	</tr>
	<!--<thead><tr><th colspan="4">Nominee Details :</th></tr></thead>
	<tr>
		<td>Nominee Name</td>
		<td><?=$nominee_name; ?></td>
		<td><?=$error_nominee_name; ?></td>
		<td>Nominee Relation</strong></td>
		<td><?=$nominee_relation; ?></td>
		<td style="text-align:left; width:200x;"><?=$error_nominee_relation; ?></td>
	</tr>
	<tr>
		<td>Nominee Date of Birth  </td>
		<td><?=$nominee_dob; ?>	</td>
		<!--<td style="text-align:left;"><?=$error_nominee_dob; ?></td>
	</tr>-->
	<thead><tr><th colspan="4"><?=$Contact_Details;?> :</th></tr></thead>
	<tr>
		<td><B><?=$add_ress;?></B></td>
		<td><?=$address; ?></td>
		<!--<td><?=$error_address; ?></td>-->
	
		<td><B><?=$City;?></B></td>
		<td><?=$city; ?></td>
		<!--<td><?=$error_city; ?></td>-->
	</tr>
	<tr>
		<td><B><?=$add_ress2; ?></B></td><td><?=$district; ?></td>
		<!--<td><?=$error_district; ?></td>-->
		<td><B><?=$State; ?></B></td>
		<td><?=$state; ?></td>
		<!--<td><?=$error_state; ?></td>-->
	</tr>
	<!--<tr>
		<td>Country</td>
		<td>
			<select>
			<option value="">Select One</option>
			<?php
				$list = count($country_list);
				for($cl = 0; $cl < $list; $cl++)
				{ ?>
					<option value="<?=$country_list[$cl]; ?>" <?php if($country_list[$cl] == $country) { ?> selected="selected" <?php } ?>><?=$country_list[$cl]; ?></option>
				<?php } ?>
			</select>		
		</td>
		<td><?=$error_country; ?></td>
	</tr>-->
	<tr>
		<td><B><?=$Phone_No; ?></B></td>
		<td><?=$phone; ?></td> 
		<!--<td><?=$error_phone; ?></td>-->
	
		<td><B><?=$E_mail; ?></B></td>
		<td><?=$email; ?></td>
		<!--<td><?=$error_email; ?></td>-->
	</tr>
	<tr>
		<td><B>Zip</B></td>
		<td><?=$zip; ?></td> 
		<!--<td><?=$error_phone; ?></td>-->
	
		<td><B>Country</B></td>
		<td><?=$country; ?></td>
		<!--<td><?=$error_email; ?></td>-->
	</tr>
	<tr>
	<!-- 	<td><B><?=$Postle_Code;?></B></td>
		<td><?=$postal_code; ?></td>  -->
		<!--<td><?=$error_phone; ?></td>-->
	
		<td></td>
		<td><?php  ?></td>
		<!--<td><?php  ?></td>-->
	</tr>

	<!-- COMPANY DETAILS -->
	<thead><tr><th colspan="4">Company Details :</th></tr></thead>	
	<tr>
		<td><b>Company Name</b></td>
		<td><?=$user_class->glc_usermeta($id, 'company_name'); ?></td>
	
		<td><b>Company Address 1</b></td>
		<td><?=$user_class->glc_usermeta($id, 'company_address1'); ?></td>
	</tr>
	<tr>
		<td><b>Company Address 2</b></td>
		<td><?=$user_class->glc_usermeta($id, 'company_address2'); ?></td>
	
		<td><b>City</b></td>
		<td><?=$user_class->glc_usermeta($id, 'company_city'); ?></td>
	</tr>
	<tr>
		<td><b>State</b></td>
		<td><?=$user_class->glc_usermeta($id, 'company_state'); ?></td>
	
		<td><b>Country</b></td>
		<td><?=$user_class->glc_usermeta($id, 'company_country'); ?></td>
	</tr>
	<tr>
		<td><b>Zip</b></td>
		<td><?=$user_class->glc_usermeta($id, 'company_zip'); ?></td>
	
		<td><b>Company Phone</b></td>
		<td><?=$user_class->glc_usermeta($id, 'company_phone'); ?></td>
	</tr>
	<tr>
		<td><b>Company Tax Identification Number (FEIN)</b></td>
		<td><?=$user_class->glc_usermeta($id, 'company_tin'); ?></td>
	
		<td><b>Company Documents Received</b></td>
		<td><?= (empty($is_company_document_approved)) ? 'NO' : 'YES' ?></td>
	</tr>
	<!-- END COMPANY DETAILS -->

	<!-- <thead><tr><th colspan="4">Bank Details :</th></tr></thead> -->
	<!-- <tr> -->
		<!-- <td><b>Beneficiery Name</b></td> -->
		<!-- <td><?=$beneficiery_name; ?></td> -->
		<!-- <td><?=$error_beneficiery_name; ?></td> -->
	
		<!-- <td><b>Account No.</b></td> -->
		<!-- <td><?=$ac_no; ?></td> -->
		<!-- <td><?=$error_ac_no; ?></td> -->
	<!-- </tr> -->
	<!-- <tr> -->
		<!-- <td><b>Bank Name</b></td> -->
		<!-- <td><?=$bank; ?></td> -->
		<!-- <td><?=$error_bank; ?></td> -->

		<!-- <td><b>Branch Name</strong></b></td> -->
		<!-- <td><?=$branch; ?></td> -->
		<!-- <td><?=$error_branch; ?></td> -->
	<!-- </tr> -->
	<!-- <tr> -->
		<!-- <td><b>IFSC/MIRC Code</b></td> -->
		<!-- <td><?=$bank_code; ?></td> -->
		<!-- <td><?=$error_bank_code; ?></td> -->

		<!-- <td><b>Tax ID</b></td> -->
		<!-- <td><?=$tax_id; ?></td> -->
		
		<!-- <td>PAN No.</td>
		<td><?=$pan_no; ?></td> -->
		<!-- <td><?=$error_pan_no; ?></td> -->
	<!-- </tr> -->
	<!-- <thead><tr><th colspan="4"><?=$Payza_Account;?> :</th></tr></thead>
	<tr>
		<td><B><?=$Payza;?></B></td>
		<td><?=$payza; ?></td>
		<td></td>
		<td></td>
	</tr> -->
	</tbody>
</table>
</div>
