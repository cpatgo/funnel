<?php
require_once("config.php");
require('include/helper.php');

$class_membership = getInstance('Class_Membership');
$class_merchant = getInstance('Class_Merchant');


if ( isset($_REQUEST['t']) ) {
	switch ($_REQUEST['t']) {
	    case 'free':
	        $membership = "Free";
			$price = 0;
	        break;
	    case 'executive':
	        $membership = "Executive";
			break;
	    case 'leadership':
	        $membership = "Leadership";
			break;
		case 'professional':
	        $membership = "Professional";
			break;
		case 'masters':
	        $membership = "Masters";
			break;
		default:
			$membership = "Free";
	        break;
	}
}
else{
	die('missing or incorrect parameter supplied.');
}

$query = mysqli_query($GLOBALS["___mysqli_ston"], "select amount from memberships where membership = '$membership'");	
$row = mysqli_fetch_array($query);
$price = $row[0];

// check referral if set
// echo $_REQUEST['nref'];
// setcookie('referral', 'joinnow');
// dd();
if ( isset($_REQUEST['nref']) ) {	
	// setcookie('nref', $_REQUEST['nref']);
	setcookie('referral', '', time()-3600);
    setcookie('referral', '', time()-3600, '/');
    setcookie('referral', $_REQUEST['nref']);
    // dd($_REQUEST['t']);
    header("Location: /glc/registration.php?t=" . $_REQUEST['t']);
}



if( isset($_COOKIE['referral']) ) {
		$sponsor = $_COOKIE['referral'];
}


// if (isset($_COOKIE['referral']) && isset($_COOKIE['nref']) ) {
// 	// dd($_COOKIE);
// 	// unset($_COOKIE['referral']);
// 	setcookie('referral', '', time()-3600);
//     setcookie('referral', '', time()-3600, '/');
//     setcookie('referral', $_COOKIE['nref']);
// }

if ($membership != 'Free') {
	// set payment type
	$payment_type = "creditcard";
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    

    <title>GLC | Registration</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
	<link href='//fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css' />
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" />
	<link href="css/registration.css" rel="stylesheet" />
	<link href="css/jquery.modal.css" rel="stylesheet" />

	<!-- <link href="css/registration-dev.css" rel="stylesheet" /> -->
	

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style type="text/css">
	    
    </style>
  </head>

  <body>
	
	<div id="ex1" style="display:none;">
    <p>Thanks for clicking.  That felt good.  <a href="#" rel="modal:close">Close</a> or press ESC</p>
  </div>
<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
	      <div class="col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2 col-sm-12">
	        <div class="nav-pills">
	        	<div class="pull-right header-help" style="">
	        		<div class="align-right">Need Help?<br /><a href="/faq/" target="_blank">Contact Customer Service</a></div>
	        		<br />
	        		<div class="align-right"><a class="btn btn-primary" href="login.php" style="text-align: right;">Already a Member? Login here.</a></div>
	        	</div>
	        	<div class="pull-left" style="padding-top:18px;">
		        	<a href="/">
			            <img src="images/glc-logo-250x40.png" alt="GLobal Learning Center" />
			        </a>
		        </div>
	        </div>
	    </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="content-wrapper col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2 col-sm-12">

      	<div class="row">
      		<div class="col-sm-12">
	      		<div id="sponsornote">
	      			<?=isset($sponsor) ? '
					<div class="sponsorship">
						<p>
						<b>Note:</b> Your Referring GLC Brand Affiliate (your Enroller) is <span>'.$sponsor.'</span>.
						<br>
						If this is <strong>not correct</strong>, change it now <a href="#update-referrer"  class="referral-pop">clicking here</a>.
						</p>
						<input type="hidden" id="real_parent" name="real_parent" value="'.$sponsor.'" />
					</div>' : '' ?>
					
					<div class="langswitcher">
					
					</div>
					
					<div class="clear"></div>
				</div>
			</div>
      	</div>
      	<div class="row">
      		<div class="col-sm-12">
				<div class="alert alert-warning alert-dismissible fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					All fields with an asterisk (<span class="required"> * </span>) are required.
				</div>
				<div class="alert alert-danger alert-dismissible fade in" id="error-form-message" style="display:none;" role="alert"></div>
			</div>
      		<div class="col-sm-7 left">
      			
				<form id="register-form" action="https://www.google.com" method="post" class="form-horizontal">
					<div class="step account">
						<div class="head">
							<i class="fa fa-exclamation-circle"></i><i class="fa fa-exclamation-circle"></i><h3>Step 1:  Account Information</h3>
						</div>
						<div class="content">
							<div class="inp">
								<div class="form-group">
									<label for="f_name" class="col-sm-3 control-label"><span>Member's First Name: <span class="required">*</span></span></label>
									<div class="col-sm-9">
										<input type="text" id="f_name" name="f_name" value="" class="form-control required" placeholder="Member's first name" />
										<div id="f_name_error_container"></div>
									</div>
								</div>	
							</div>
							<div class="inp">
								<div class="form-group">
									<label for="l_name" class="col-sm-3 control-label"><span>Member's Last Name: <span class="required">*</span></span></label>
									<div class="col-sm-9">
										<input type="text" id="l_name" name="l_name" value="" class="form-control required" placeholder="Member's last name"  />
										<div id="l_name_error_container"></div>
									</div>
								</div>	
							</div>
							<div class="inp">
								<div class="form-group">
									<label for="company_name" class="col-sm-3 control-label"><span>Company Name: </span></label>
									<div class="col-sm-9">
										<input type="text" id="company_name" name="company_name" value="" class="form-control" placeholder="If registering as a company" />
										<div id="company_name_error_container"></div>
									</div>
								</div>	
							</div>
							<div class="inp">
								<div class="form-group">
									<label for="email" class="col-sm-3 control-label"><span>Email <span class="required">*</span></span></label>
									<div class="col-sm-9">
										<input type="text" name="email" id="email" value="" class="form-control required email" placeholder="Member's current email address" />
										<div id="email_error_container"></div>
									</div>
								</div>
							</div>
							<div class="inp">
								<div class="form-group">
									<label for="username" class="col-sm-3 control-label"><span>User Name: <span class="required">*</span></span></label>
									<div class="col-sm-9">
										<input type="text" name="username" id="username" value="" class="form-control required" placeholder="If Affiliate, used as Referral Website" />
										<div id="username_error_container"></div>
									</div>
								</div>
							</div>
							<div class="inp">
								<div class="form-group">
									<label for="password" class="col-sm-3 control-label"><span>Password: <span class="required">*</span></span></label>
									<div class="col-sm-9">
										<input type="password" id="password" name="password" value="" class="form-control required" placeholder="Choose a Password." />
										<div id="username_error_container"></div>
										<div id="password_error_container"></div>
									</div>
								</div>
							</div>
							<div class="inp">
								<div class="form-group">
									<label for="re_password" class="col-sm-3 control-label"><span>Re-Password: <span class="required">*</span></span></label>
									<div class="col-sm-9">
										<input type="password" id="re_password" name="re_password" value="" class="form-control required" placeholder="Re-enter your Password" />
										<div id="re_password_error_container"></div>
									</div>
								</div>
							</div>
							<?php if ( !isset($sponsor) ) { ?>
								<div class="inp">
									<div class="form-group">
										<label for="username" class="col-sm-3 control-label"><span>Enroller: </span></label>
										<div class="col-sm-9">
											<input type="text" name="real_parent" id="real_parent" value="" class="form-control" placeholder="Enroller User Name" />
											<div id="username_error_container"></div>
										</div>
									</div>
								</div>	
							<?php } else { ?>
								<!-- <div class="inp">
									<div class="form-group">
										<label for="username" class="col-sm-3 control-label"><span>Enroller: </span></label>
										<div class="col-sm-9">
											<input type="text" name="real_parent" id="real_parent" value="" class="form-control required" placeholder="Enroller User Name" />
											<div id="username_error_container"></div>
										</div>
									</div>
								</div> -->
								<input type="hidden" id="real_parent" name="real_parent" value="<?=isset($sponsor) ? $sponsor : "" ?>" />
							<?php } ?> 
							<?php if ($membership != 'Free') { ?>
							<div class="inp">
								<div class="form-group">
									<label for="phone" class="col-sm-3 control-label"><span>Phone: <span class="">&nbsp;</span></span></label>
									<div class="col-sm-9">
										<input type="text" id="phone" name="phone" value="" class="form-control"  placeholder="Your Phone - (555)-555-5555" />
										<div class="opt-in checkbox">
											<label>
												<input id="text_campaign" name="text_campaign" type="checkbox" class="" /><span>Yes, text me updates and special announcements</span>
											</label>
										</div>
									</div>
								</div>
							</div>
							<?php } ?>
						</div>
					</div>
					
					<?php if ($membership != 'Free') { ?>

					<div class="step payment">
						<div class="head">
							<i class="fa fa-bank"></i><h3>Step 2: Member Details</h3>
						</div>
						<div class="content">
							<div class="inp">
								<div class="form-group">
									<label for="country_container" class="col-sm-3 control-label"><span>Country: <span class="required">*</span></span></label>
									<div class="col-sm-9">
										<div class="country_container" id="country_container"></div>
									</div>
								</div>	
							</div>
							<div class="inp">
								<div class="form-group">
									<label for="zip" class="col-sm-3 control-label"><span>Zip Code: <span class="required">*</span></span></label>
									<div class="col-sm-9">
										<input type="text" id="zip" name="zip" value="" class="form-control required" placeholder="Zip Code" />
									</div>
								</div>	
							</div>
							<div class="inp">
								<div class="form-group">
									<label for="address_1" class="col-sm-3 control-label"><span>Address 1: <span class="required">*</span></span></label>
									<div class="col-sm-9">
										<input type="text" id="address_1" name="address_1" value="" class="form-control required" placeholder="Address 1" />
									</div>
								</div>	
							</div>
							<div class="inp">
								<div class="form-group">
									<label for="address_2" class="col-sm-3 control-label"><span>Address 2: <span class="">&nbsp;</span></span></label>
									<div class="col-sm-9">
										<input type="text" id="address_2" name="address_2" value="" class="form-control" placeholder="Address 2" />
									</div>
								</div>	
							</div>
							
							<div class="inp">
								<div class="form-group">
									<label for="city" class="col-sm-3 control-label"><span>City: <span class="required">*</span></span></label>
									<div class="col-sm-9">
										<input type="text" id="city" name="city" value="" class="form-control required" placeholder="City" />
									</div>
								</div>
							</div>

							<div class="inp">
								<div class="form-group">
									<label for="state" class="col-sm-3 control-label state_label"><span>Province/State: <span class="required">*</span></span></label>
									<div class="col-sm-9">
										<div id="statebox">
											<select class="form-control required" name="us_state" id="us_state" aria-required="true"><option value="" disabled selected>Choose Your State</option><option value="AL">Alabama</option><option value="AK">Alaska</option><option value="AZ">Arizona</option><option value="AR">Arkansas</option><option value="CA">California</option><option value="CO">Colorado</option><option value="CT">Connecticut</option><option value="DE">Delaware</option><option value="DC">District Of Columbia</option><option value="FL">Florida</option><option value="GA">Georgia</option><option value="HI">Hawaii</option><option value="ID">Idaho</option><option value="IL">Illinois</option><option value="IN">Indiana</option><option value="IA">Iowa</option><option value="KS">Kansas</option><option value="KY">Kentucky</option><option value="LA">Louisiana</option><option value="ME">Maine</option><option value="MD">Maryland</option><option value="MA">Massachusetts</option><option value="MI">Michigan</option><option value="MN">Minnesota</option><option value="MS">Mississippi</option><option value="MO">Missouri</option><option value="MT">Montana</option><option value="NE">Nebraska</option><option value="NV">Nevada</option><option value="NH">New Hampshire</option><option value="NJ">New Jersey</option><option value="NM">New Mexico</option><option value="NY">New York</option><option value="NC">North Carolina</option><option value="ND">North Dakota</option><option value="OH">Ohio</option><option value="OK">Oklahoma</option><option value="OR">Oregon</option><option value="PA">Pennsylvania</option><option value="RI">Rhode Island</option><option value="SC">South Carolina</option><option value="SD">South Dakota</option><option value="TN">Tennessee</option><option value="TX">Texas</option><option value="UT">Utah</option><option value="VT">Vermont</option><option value="VA">Virginia</option><option value="WA">Washington</option><option value="WV">West Virginia</option><option value="WI">Wisconsin</option><option value="WY">Wyoming</option></select>
										</div>
										<div id="us_state_error_container"></div>
									</div>
								</div>
							</div>	
						</div>
					</div>

					<?php } ?> 
					<div class="step payment">
						<div class="content">
				            <div class="terms_and_conditions_wrapper">
				            	<h4>Terms & Conditions<?=$membership != 'Free' ? ' and Refund Policy' : ''?> </h4>
					            
					            <div class="terms_and_conditions"></div>
					            <br />
					            <div class="form-group">
									<div class="col-sm-12">
										<div id="terms2_error_container" name="terms2_error_container"> </div>
										<div class="checkbox">
											<label>
												<input id="acceptTerms-2" name="acceptTerms2" type="checkbox" class="required" />I have read and agree to the Terms and Conditions shown above<?=$membership != 'Free' ? ', including the Refund Policy.' :'.' ?>
											</label>
										</div>
									</div>
								</div>

<div class="form-group">
									<div class="col-sm-12">
									<div id="terms1_error_container" name="terms1_error_container"> </div>
										<div class="checkbox">
											<label>
												<input id="acceptTerms-1" name="acceptTerms1" type="checkbox" /><?php $link = sprintf('%s://%s', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http', $_SERVER['HTTP_HOST']); ?>By checking this box you agree to joining our Affiliate Program & agree to our <a href="<?php echo $link?>/affiliatesterms/" target="_blank" >Affiliate Terms & Conditions</a>.
											</label>
										</div>
									</div>
								</div>		
				            </div>
				            <p class="usDisclaimerText">Note: If you are a U.S. citizen, please be advised that your bank may charge an "international fee" for your purchase. This is a fee that you are ultimately responsible for, so please contact your bank for further clarification about any additional fees for international transactions.</p>
				            
						</div>	
					</div>
					<!-- <div class="step finish"> -->
						<div class="inp">
							<div class="form-group">
								<div class="col-sm-12">
									<div class="row">
										<div class="align-center">
											<div class="loader_processing" style="display:none;"><img src="img/ajax-loader.gif" /></div>
											<br /><br /><br />
							      			<button type="submit" id="submit_order_btn" class="btn btn-primary submit_order_btn">Complete Order <i class="fa fa-chevron-circle-right"></i></button>
										</div>
									</div>
							    </div>
							</div>
						</div>
					<!-- </div> -->
	
					<?php 
						if($membership == 'Free') $reg_by = 'Free';
						if($membership == 'Masters') $reg_by = 'Masters';
					?>

					<input type="hidden" id="reg_by" name="reg_by" value="<?=$reg_by;?>" class="required <?=$reg_by;?>">
					
					<input id="original_membership" name="original_membership" type="hidden" value="<?=$membership?> " />
					<input id="membership" name="membership" type="hidden" value="<?=$membership?>" />
					<input name="price" type="hidden" value="<?=$price?> " />
					<?php if ($membership != 'Free') { ?>
						<input type="hidden" name="hidden_payment_type" value="<?=$payment_type?>" />
						<input type="hidden" name="pay_method" value="<?=$payment_type?>" />	
					<?php } ?>
					
					<input type="hidden" value="q" name="q" />
				</form>		
      		</div>
      		<div class="col-sm-4 col-sm-offset-1 sidebar">
      			<div class="sidebar_widget">
		      		<h3 class="headline"><span class="wp"><i class="fa fa-shopping-cart"></i>ORDER DETAILS:</span></h3>
					
					<div class="sidebar_content">
						<p class="txt">
						<span class="product-name"><?=$membership?> Membership</span>
						<br />
						<span class="product-price">$<?=$price?>.00</span>
						<br />
						<span>for lifetime access to the GLC Library.</span>
						</p>
						<br />
						<p class="img"><img src="img/membership-checkout.png" alt=""></p>
					</div>
	      		</div>
      			<div class="sidebar_widget">
		      		<h3 class="headline moneyBackHeadline"><i class="fa fa-thumbs-up"></i><span class="wp">OUR GUARANTEE:</span></h3>
					
					<div class="sidebar_content">
						<div class="block moneyBack">
						   	<p class="img"><img src="img/money-back.png" alt=""></p>
							
						    <p class="txt">14 Days Money Back Guarantee.<br>
							<span>No Questions Asked. No Hassles. No Problems.</span></p>
						</div>
					</div>
	      		</div>
      			
      		</div>
      		<div class="clearfix"></div>
      	</div>
      	<div class="row">
	      	
      	</div>
      </div>
	</div><!-- /.container -->

    <div class="clearfix"></div>																		

     <div class="container-fluid">
	      <div class="col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2 col-sm-12">
	      <div class="bottom">
				<a class="money ajaxablePopup cboxElement" href="#colorbox-popup">
					14 Day Money Back Guaranteed. <span>No Questions Asked. No Hassles. No Problems.</span></a>
				</div>
				<div class="footer">
					<div class="page-wrapper">
						<ul>
							<li>© 2016 Global Learning Center</li>
							<li><a target="_blank" href="/earnings-disclaimer/">Earnings Disclaimer</a></li>
							<li><a target="_blank" href="/affiliatesterms/">Affiliate Program Terms</a></li>
							<li><a target="_blank" href="/refund/">Refund</a></li>
							<li><a target="_blank" href="/privacy-policy/">Privacy Policy</a></li>
							<li><a target="_blank" href="/site-terms/">Website Use Policy</a></li>
							<!-- <li><a target="_blank" href="javascript:;">Contact Us</a></li> -->
						</ul>
						<div class="clear"></div>
					</div>
				</div>
			</div>
		</div>
<div id="update-referrer" style="display: none;">
	<div class="row">
		<div class="sidebar_widget">
			<div class="head">
				<h3>Update Referring GLC Brand Affiliate (Your Enroller)</h3>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="alert">
		<p>If <strong><span class="required"><?=$sponsor?></span></strong> is NOT your Referring Affiliate (Your Enroller), click the box below and enter the correct Referring Affiliate Username.</p>
		</div>
	</div>
	<div class="row">
		<div class="form-group">
			<div class="col-sm-8 col-sm-offset-2">
			<div id="change-referrer" name="terms1_error_container"> </div>
				<div class="checkbox">
					<div class="alert alert-warning">
						<label>
							<input type="checkbox" name="change-referrer" id="change-referrer">&nbsp;<strong><span class="required"><?=$sponsor?></span></strong> is NOT my Referring Affiliate
						</label>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	
	<div class="form-update-referrer-wrapper" style="display:none;" >
		<form id="new-referral-form" action="https://www.google.com" method="post" class="form-horizontal">
			<div class="row alert">
					<div class="form-update-referrer">
						<div class="inp">
							<div class="form-group">

								<label for="country_container" class="col-sm-6 control-label">Okay, no problem! Please enter your referring Affiliate's username.</label>
								<div class="col-sm-5">
									<input type="text" id="new_affiliate" name="new_affiliate" value="" class="form-control required" placeholder="Affiliate's Username" />
									<div id="new_affiliate_error_container"></div>
								</div>
							</div>	
						</div>
						<br />
					</div>
				
			</div>
			<div class="row">
				<div class="inp">
					<div class="form-group">
						<div class="col-sm-12">
							<button type="button" id="update_referrer" class=" pull-right btn btn-primary update_referral_btn">Update Referrer <i class="fa fa-pencil-square"></i></button>
						</div>
					</div>	
				</div>
			</div>
		</form>
	</div>
</div>

<div id="where-ccv" style="display:none;">
	<img src="images/back_cvv.png" />
</div>

<div id="affiliate-pop" style="display:none;">
	<p>Don't miss out, join the GLC Affiliate Program right now and start making Big Money. It's absolutely free to join. Check the box to become an Affiliate, you'll be glad you did.</p>
</div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script type='text/javascript' src='//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js'></script>
    <!-- <script src="../../dist/js/bootstrap.min.js"></script> -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <!--<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>-->
	
    <script src="js/inputmask/inputmask.js"></script>
    <script src="js/inputmask/jquery.inputmask.js"></script>
    
    <!-- Jquery Validate -->
	<script src="js/plugins/validate/jquery.validate.min.js"></script>
	
	<!-- Jquery Modal -->
	<script src="js/modal/jquery.modal.min.js"></script>
	
	<!-- jQuery Credit Card Validator -->
	<script src="js/jquery.creditCardValidator.js"></script>

	<script>
		var siteUrl = "<?php echo GLC_URL; ?>";
		var plan_membership = "<?php echo $membership; ?>";
		var plan_membership_slug = "<?php echo $_REQUEST['t']; ?>";
	</script>

	<script src="js/registration.js"></script>
  </body>
</html>