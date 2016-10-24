<?php
include(dirname(dirname(__FILE__)).'/function/uploaddocs.php');

$msg = isset($_GET['msg'])?$_GET['msg']:"";
$err = isset($_GET['err'])?$_GET['err']:"";


$id = $_SESSION['dennisn_user_id'];
$user_class = getInstance('Class_User');
$user = $user_class->get_user($id);
$docs = $user_class->get_user_documents($id);
$country = $user[0]['country'];
$identification_status = $tax_status = $company_status = $corporate_status = 0;

$is_company = $user_class->glc_usermeta($id, 'company_name');
$user_type = (empty($is_company)) ? 'individual' : 'company';

// email requirement
$mailtoaddress = 'admin@glchub.com';


$str_builder = 'User ' .$user[0]['username']. ' has submitted a document requirement. Below are his/her details:%0D';
$str_builder .= '%0DUser ID: %09%09%09' . $user[0]['id_user'];
$str_builder .= '%0DUsername: %09%09' . $user[0]['username'];
$str_builder .= '%0DFirst Name: %09%09' . $user[0]['f_name'];
$str_builder .= '%0DLast Name: %09%09' . $user[0]['l_name'];
$str_builder .= '%0DEmail Address: %09' . $user[0]['email'];
$str_builder .= '%0D%0D';
$str_builder .= 'Please attach your documents(s) to this message. Click the ATTACHMENT icon of your email client to add attachment.';
$str_builder .= '%0D%0D';
$str_builder .= '---------------------------%0D';
$str_builder .= 'Generated via GLC document page.';



$body = $str_builder;


$subject = 'GLC Document Requirement - User '.$user[0]['username']. ' has submitted a document.';

?>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
                <div class="ibox-title">
				<h3>
					My Documents
				</h3>
			</div>
			<div class="ibox-content">
			<!-- Content Starts Here -->
			<?php
			if($msg != "" || $err != "") { ?>
			<div class="alert alert-<?php echo $c = ($err != "")?"danger":"success"; ?>">				
				<?php echo $c = ($err != "")?$err:$msg; ?>
			</div>
			<?php } ?>
			<table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
			
				<thead>				
					<tr>
						<th scope="col">Document</th>
						<th scope="col" class="hideformobile">Date Submited</th>
						<th scope="col" class="hideformobile">Date Approved</th>
						<th scope="col">Status</th>
					</tr>
				</thead>
				
				<tbody>
				<?php
				foreach($docs as $row)
				{ 
					?>
					<tr>
						<td>
							<?php 
								switch ($row['doctype']) {
									case 1:
										$doctype = "Identification";
										break;
									case 2:
										$doctype = "Tax Form";
										break;
									case 3:
										$doctype = "W-8 BEN";
										break;
									case 4:
										$doctype = "Corporate Tax Number";
										break;
								}
								echo $doctype;
							?>
						</td>
						<td class="hideformobile"><?php echo date('l jS \of F Y h:i:s A' ,$row['date'] ); ?></td>
						<td class="hideformobile"><?php echo $d = ($row['dateapproved'] > 0)?date('l jS \of F Y h:i:s A' ,$row['dateapproved'] ):"-"; ?></td>
						<td><?php 
							switch ($row['approved']) {
									case 0:
										$status = "<span class='yellowtext'>Pending</span>";
										break;
									case 1:
										$status = "<span class='greentext'>Approved</span>";
										if($row['doctype'] == 1) $identification_status = 1;
										if($row['doctype'] == 2) $tax_status = 1;
										if($row['doctype'] == 3) $company_status = 1;
										if($row['doctype'] == 4) $corporate_status = 1;
										break;
									case 2:
										$status = "<span class='redtext'>Declined</span>";
										break;
								}
								echo $status; 
						?></td>
					</tr>
					<?php 
				}
				?>	
				</tbody>
			
			</table>
			<!-- Content Ends Here -->
			</div>
		</div>
	</div>		
</div>

<div class="row">
	<?php if($identification_status == 0): ?>
		<!-- <div class="col-lg-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h3>
						 Required Documents
					</h3>
				</div>
	            <div class="ibox-content">
					<p>You are <strong>required</strong> to provide <strong>acceptable forms of identification</strong> as listed below. You'll only need to do this one time, so long as your place of residence does not change. Once your information is verified and confirmed, there will be no need to do this again.</p>
					<p>Verification of your information consists of <strong>Proof of Identity</strong>, <strong>Proof of Residency</strong>, and <strong>Proof of Age</strong>. This information MUST be verified before you are eligible to claim your prize.</p>
					<p>Upon receipt of your documents, we will begin to verify your information within 72 hours. If we find that your information is incomplete, or we cannot verify it; we will send you an email detailing the issue(s). Once your documents are verified and you have met all of our requirements for collecting your prize; you will be notified via email with instructions on how to proceed. You can also visit our Support section if you have additional questions on how this process works. </p>
				</div>		
			</div>
		</div>	 -->
	<?php endif; ?>

	<!-- TAX DOCUMENT INFORMATION -->
	<?php if($user_type === 'individual'): ?>
		<?php if($tax_status == 0): ?>
			<div class="col-lg-12 col-md-12 col-sm-12">
				<?php if($country == 'United States' or $country == 'US'){ ?>

					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h3>
								 US Tax Information
							</h3>
						</div>
						<div class="ibox-content">
							<p>US Federal Tax regulations require that we obtain a Form W-9 from all independent contractors(affiliates). If you earn more than $600.00 in a calendar year we are required to issue to you a Form 1099 Misc. the following year. Under IRS regulations, you are required to provide us, under penalty of perjury, with your correct taxpayer Identification Number and certify that you either are or are not subject to backup withholding. Please complete the Form W-9 prior to requesting any payment for Affiliate Commissions. You can down load the form here. Please print the form and complete it. Please sign the completed Form W-9 and follow the instructions for uploading the form here. If you are unable to scan and upload the document, you can mail it or fax it. Please follow the instructions below. </p>
							<a target="_blank" href="/glc/forms/IRS_FormW-9.pdf">Download W-9 form</a>	
							</p>
						</div>
					</div>
				<?php } else { ?>
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h3>
								 International Tax Information
							</h3>
						</div>
						<div class="ibox-content">
							<p> As a United States based company, we are required to withhold 30% of your winnings and commissions, unless you tell us otherwise. The withholding can be reduced based on tax treaties that exist between your country and the United States. In order for us to reduce the withholding, you must complete and provide us with a copy of the form W-8BEN. For your convenience, we have provided you with a fillable copy of the form. Upon receipt of the completed form and your identifying information, upon appropriate approval, we will reduce the withholding on your winnings and commissions based on the tax treaty which you tell us applies. If you do not provide a form W-8BEN, then we will continue to withhold 30% on all monies due you. If you have any questions concerning this, please review the information in our knowledge base or contact customer service.  In order to submit a form W-8BEN, please follow the directions below. 
							</p>
							<p>
							<a href="/glc/forms/IRS_Form_W-8BEN.pdf" target="_blank">Download W-8BEN form</a>
							<h4>Print it. Complete it. Sign it. Upload it.</h4>
							</p>
						</div>
					</div>
				<?php } ?>
			</div>
		<?php endif; ?>
	
	<?php elseif($user_type === 'company'): ?>
		<!-- COMPANY DOCUMENT INFORMATION -->
		<?php if($country == 'United States' or $country == 'US'): ?>
			<?php if($corporate_status == 0): ?>
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h3>
								 Corporate Tax Number
							</h3>
						</div>
			            <div class="ibox-content">
							<p>
								Please submit your Corporate Tax Number.<br>
								<a target="_blank" href="/glc/forms/IRS_FormW-9.pdf">Download W-9 form</a>
							</p>
						</div>		
					</div>
				</div>	
			<?php endif; ?>
		<?php else: ?>
			<?php if($company_status == 0): ?>
				<div class="col-lg-6">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h3>
								 W-8 BEN
							</h3>
						</div>
			            <div class="ibox-content">
							<p>We will review your document.</p>
						</div>		
					</div>
				</div>	
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
	
</div>

<!-- UPLOAD FORM -->
<div class="row">
	<?php if($user_type === 'individual'): ?>
		<!-- IDENTIFICATION -->
		<?php if($identification_status == 0): ?>
			<!-- <div class="col-lg-6">
				<div class="ibox float-e-margins">
		                <div class="ibox-title">
							<h3>
								Submit an Identification
							</h3>
						</div>
						<div class="ibox-content">
					  <form enctype="multipart/form-data" action="index.php?page=documents" method="post">
						  <input type="hidden" name="doctype" value="1" />
						  <input type="hidden" name="MAX_FILE_SIZE" value="9999999" />
						  <input name="userfile" type="file" />
						  <div class="pull-right"><input type="submit" value="Submit" class="btn btn-w-m btn-primary" /></div>
						  <div class="clearfix"></div>
					  </form>
				</div>
				</div>
			</div> -->
		<?php endif; ?>
		<!-- TAX -->
		<?php if($tax_status == 0): ?>
			<div class="col-lg-6">
				<div class="ibox float-e-margins">
		                <div class="ibox-title">
							<h3>
								Submit a Tax Form
							</h3>
						</div>
						<div class="ibox-content">
					  <form enctype="multipart/form-data" action="index.php?page=documents" method="post">
						  <input type="hidden" name="doctype" value="2" />
						  <input type="hidden" name="MAX_FILE_SIZE" value="9999999" />
						  <input name="userfile" type="file" />
						  <div class="pull-right"><input type="submit" value="Submit" class="btn btn-w-m btn-primary btn-large" /></div>
						  <div class="clearfix"></div>
					  </form>
				</div>
				</div>
			</div>
		<?php endif; ?>

	<?php elseif($user_type === 'company'): ?>
		<!-- COMPANY DOCUMENT -->
		<?php if($country == 'United States' or $country == 'US'): ?>
			<?php if($corporate_status == 0): ?>
				<div class="col-lg-6">
					<div class="ibox float-e-margins">
			                <div class="ibox-title">
								<h3>
									Submit your Corporate Tax Number
								</h3>
							</div>
							<div class="ibox-content">
						  <form action="index.php?page=documents" method="post">
								<div class="input-group">
									<input type="hidden" name="doctype" value="4" />
									<input type="text" class="form-control" name="corporate_tax_number"> 
									<span class="input-group-btn"> 
										<input type="submit" class="btn btn-primary">Submit</button>
									</span>
								</div>
							  <div class="clearfix"></div>
						  </form>
					</div>
					</div>
				</div>
			<?php endif; ?>
		<?php else: ?>
			<?php if($company_status == 0): ?>
				<div class="col-lg-6">
					<div class="ibox float-e-margins">
			                <div class="ibox-title">
								<h3>
									Submit your W-8 BEN
								</h3>
							</div>
							<div class="ibox-content">
						  <form enctype="multipart/form-data" action="index.php?page=documents" method="post">
							  <input type="hidden" name="doctype" value="3" />
							  <input type="hidden" name="MAX_FILE_SIZE" value="9999999" />
							  <input name="userfile" type="file" />
							  <div class="pull-right"><input type="submit" value="Submit" class="btn btn-w-m btn-primary btn-large" /></div>
							  <div class="clearfix"></div>
						  </form>
					</div>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>

	<div class="col-lg-6">
		<div class="ibox float-e-margins">
            <div class="ibox-title">
				<h3>
					Email Document
				</h3>
			</div>
			<div class="ibox-content">
				<p>This method will use your existing email client with the pre-populated field added. The only thing you'll need is to attach the document requirement.</p>
			  	<div class="">
			  		<a href="mailto:<?=$mailtoaddress?>?subject=<?=$subject?>&body=<?=$body?>" class="btn btn-lg btn-primary" style="width:100%;">Email Document</a>
			  	</div>
			  	<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
	