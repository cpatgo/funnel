 <?php
require_once("config.php");
require('include/helper.php');
// if(file_exists(dirname(__FILE__). '/class/membership.php')) require_once(dirname(__FILE__).'/class/membership.php');
// if(file_exists(dirname(__FILE__). '/class/membership.php')) require_once(dirname(__FILE__).'/class/membership.php');
// if(file_exists(dirname(__FILE__). '/class/merchant.php')) require_once(dirname(__FILE__).'/class/merchant.php');

// require_once(dirname(__FILE__)."/class/icontact.php"); 
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


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GLC | Register</title>
<link href="css/bootstrap.min.css" rel="stylesheet">

<link href="css/plugins/steps/jquery.steps.css" rel="stylesheet">  
<link href="css/register.css" rel="stylesheet">  
  
<script type='text/javascript' src='js/jquery.js'></script>
<script src="js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<!-- <link rel="stylesheet" href="css/style.css"> -->
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

</head>
<body class="gray-bg">
<div class="container-fluid">
<div class="row col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3">
<header>
	<div class="row">
		<div class="col-md-6">
			<div class="logo">
				<a href="/">
		            <img src="img/glc-logo-small.png" alt="GLobal Learning Center" />
		        </a>
	        </div>
		</div>
	</div>
	<hr />
	<div class="row">
		<div class="col-md-6">
			<span class="text-left">
				<?php if(isset($_COOKIE['referral'])) { ?>
					Enrolled by: <b><?php echo $_COOKIE['referral']; ?></b>
				<?php } ?></span>
		</div>
		<div class="col-md-6">
			<span class="text-right">
				<a href="/glc/login.php">Already a member? Sign In &raquo;</a>
			</span>
		</div>
	</div>
	<hr />
</header>
<div id="box-content">

<form id="register-form" action="https://www.google.com" method="post">
		<!-- 1st step -->
        <h3>Account Information</h3>
        <fieldset>
			<legend><?php echo $membership . ' Membership - $' . $price . '.00'; ?></legend>

			<div class="form-group has-feedback ">
				<div class="input-group">
    				<input type="text" name="email" id="email" value="" class="form-control required email" placeholder="Your current email address" />
					<span class="input-group-addon">REQUIRED</span>
				</div>
				<div id="email_error_container"></div>
			</div>
			<div class="form-group has-feedback ">
				<div class="input-group">
					<input type="text" name="username" id="username" value="" class="form-control required" placeholder="Choose your User Name" />
					<span class="input-group-addon">REQUIRED</span>
				</div>
				<div id="username_error_container"></div>
			</div>
			<div class="form-group has-feedback ">
				<div class="input-group">
					<input type="password" id="password" name="password" value="" class="form-control required" placeholder="Choose a Password" />
					<span class="input-group-addon">REQUIRED</span>
				</div>
				<div id="password_error_container"></div>
			</div>
			<div class="form-group has-feedback ">
				<div class="input-group">
					<input type="password" name="re_password" value="" class="form-control required" placeholder="Re-enter your Password" />
					<span class="input-group-addon">REQUIRED</span>
				</div>
				<div id="re_password_error_container"></div>
			</div>

			<?php if($membership == 'Free') { ?>
			<div class="form-group has-feedback ">
					<div class="input-group">
						<input type="text" id="f_name" name="f_name" value="" class="form-control required" placeholder="First name" />
						<span class="input-group-addon">REQUIRED</span>
					</div>
					<div id="f_name_error_container"></div>
				</div>
				<div class="form-group has-feedback ">
					<div class="input-group">
						<input type="text" id="l_name" name="l_name" value="" class="form-control required" placeholder="Last name"  />
						<span class="input-group-addon">REQUIRED</span>
					</div>
					<div id="l_name_error_container"></div>
				</div>
			<?php } ?>

			<div class="form-group">
				
				<?php if(isset($_COOKIE['referral'])) { ?>
					<label>Enrolled by <b><?php echo $_COOKIE['referral']; ?></b></label>
					<input type="hidden" name="real_parent" id="real_parent" value="<?php echo $_COOKIE['referral']; ?>"  class="form-control" />
				<?php } else { ?>
					<input type="text" name="real_parent" id="real_parent" value="<?php if(isset($_COOKIE['referral'])) echo $_COOKIE['referral']; ?>"  class="form-control" placeholder="Enroller User Name" />
			<?php } ?>
				
			</div>
			<div class="form-group has-feedback ">
				<div class="input-group">
					<div class="country_container" id="country_container"></div>
					<span class="input-group-addon">REQUIRED</span>
				</div>
				<div id="country_error_container"></div>
			</div>
        </fieldset>
        <!-- end of 1st step -->
     
     	<?php if($membership != 'Free') { ?>

     	<!-- 2nd step -->
        <h3>Billing Details</h3>
        <fieldset>
        	<!-- <legend><?php echo $membership . ' Package - $' . $price . '.00'; ?></legend> -->
        	
        	<div id="payments"> 
        		<div class="form-group">
					<p>Choose a payment method below and click Checkout!</p>
		    	</div>
					<div class="row form-group product-chooser">
			        	<?php 
			        		// get membership_id and check what available merchant provider is allowed for this package.
			        		// $membership = is plain text - need to get its id.
			        		$current_membership_id = $class_membership->get_membership_id($membership);
			        		$current_membership_id = ($current_membership_id[0]['id']);
			        		
			        		// merchant data 
			        		$merchant_providers = $class_merchant->get_active_merchant((int)$current_membership_id);
			        		// var_dump($merchant_providers);
			        		if(count($merchant_providers) >= 2) {;
			        			// if there are 2 or more active merchant providers, check options table for the default provider
				        		$default_merchant_provider = $class_merchant->get_default_merchant_provider();
				        		//var_dump($default_merchant_provider);
				        		$merchant_provider_id = (int)$default_merchant_provider[0]['option_value'];
				        		//var_dump($merchant_provider_id);
			        		}
			        		else{
			        			$merchant_provider_id = (int)$merchant_providers[0]['merchant_id'];	
			        		}

			        		// var_dump($merchant_provider_id);
			        		// get merchant slug
			        		$merchant_provider_data = $class_merchant->get_merchant_name($merchant_provider_id);
			        		$merchant_provider_name = $merchant_provider_data[0]['slug'];
			        		
			        		// $merchant_provider_name = $class_merchant->get_merchant_name($merchant_provider_id);
			        		// $merchant_provider_name = $merchant_provider_name[0]['slug'];

			        		echo '<input type="hidden" id="reg_by" name="reg_by" value="'.$merchant_provider_name.'" class="required '.$merchant_provider_name.'">';

			        		$pay_methods = $class_merchant->get_active_payment_methods($merchant_provider_id);
			        		// dd($pay_methods);

			        		foreach($pay_methods as $pay_method) { ?>
			        			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<div class="product-chooser-item">
										<div class="pay_method_container row">
											<div class="col-md-3">
												<div class="pay_method_<?=$pay_method['slug']?>_img pay_method_img"><img src="<?=$pay_method['img_url']?>" /></div>
											</div>
											
											<div class="col-md-6">
												<div class="pay_method_text">
													<label for="pay_method">
														<input type="radio" name="pay_method" class="required" value="<?=$pay_method['slug']?>"> <?=$pay_method['verbiage']?>
													</label>
												</div>
											</div>

											<div class="col-md-3">
												<div class="pay_method_checkout">
													<a href="javascript:;" class="btn btn-primary">Checkout &raquo;</a>
												</div>
											</div>

											
											<div class="clear"></div>
										</div>
									</div>
								</div>
						<?php } ?>	
								<div id="errordiv4" name="errordiv4"> </div>
					</div>
				</div>
				<div class="payment_form_container">
				</div>
        </fieldset> 
        <!-- End of 2nd Step -->

        <?php } ?>

        <!--  4th Step -->
        <?=$membership != "Free" ? '<h3>Payment Information</h3>' : '<h3>Complete Order</h3>'?>
        <fieldset>
			<?=$membership != "Free" ? '<legend class="last-step">Payment Information</legend>' : '<legend>Agreement Form</legend>'?>
			
			<?php if($membership != 'Free') { ?>

			<div class="alert alert-success cc_form">
				<div class="form-group has-feedback">
					<div class="input-group">
						<input type="text" id="cc_number" name="cc_number" value="" class="form-control required" placeholder="XXXX-XXXX-XXXX-XXXX" />
						<span class="input-group-addon">REQUIRED</span>
					</div>
					<div id="cc_number_error_container"></div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group has-feedback">
							<div class="input-group">
								<select name="expireMM" id="expireMM" class="form-control required">
								    <option value="" selected disabled>Month</option>
								    <option value="1">01 (Jan)</option>
				           			<option value="2">02 (Feb)</option>
				           			<option value="3">03 (Mar)</option>
				           			<option value="4">04 (Apr)</option>
				           			<option value="5">05 (May)</option>
				           			<option value="6">06 (Jun)</option>
				           			<option value="7">07 (Jul)</option>
				           			<option value="8">08 (Aug)</option>
				           			<option value="9">09 (Sep)</option>
				           			<option value="10">10 (Oct)</option>
				           			<option value="11">11 (Nov)</option>
				           			<option value="12">12 (Dec)</option>
								</select> 
								<span class="input-group-addon">REQUIRED</span>
							</div>
							<div id="expireMM_error_container"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group has-feedback">
							<div class="input-group">
								<select name="expireYY" id="expireYY" class="form-control required">
								    <option value='' selected disabled>Year</option>
									<option value='2016'>2016</option>
									<option value='2017'>2017</option>
									<option value='2018'>2018</option>
									<option value='2019'>2019</option>
									<option value='2020'>2020</option>
									<option value='2021'>2021</option>
									<option value='2022'>2022</option>
									<option value='2023'>2023</option>
									<option value='2024'>2024</option>
									<option value='2025'>2025</option>
									<option value='2026'>2026</option>
									<option value='2027'>2027</option>
									<option value='2028'>2028</option>
									<option value='2029'>2029</option>
									<option value='2030'>2030</option>
								</select>
								<span class="input-group-addon">REQUIRED</span>
							</div>
							<div id="expireYY_error_container"></div>
						</div>
					</div>
				</div>	

				
				<div class="form-group has-feedback">
					<div class="input-group">	
						<input type="text" id="cc_ccv" name="cc_ccv" value="" maxlength="4" class="form-control required" placeholder="CCV - (i.e. 123)" />
						<span class="input-group-addon">REQUIRED</span>
					</div>
					<div id="cc_ccv_error_container"></div>
				</div>
				
				<hr class="grey-hr" />

				<div class="form-group">
					
					
					<div class="row">
						<div class="accepted">
							<div class="accept_cc_text">We Accept Major Credit Cards.</div>
							<div class="cc_img">
							<!-- <img src="img/visamastercarddiscover.png" /> -->
							<img src="images/cc.png" />
							</div>
							<!-- <div class="cc_img">
							<img src="http://i.imgur.com/D2eQTim.png" />
							</div>
							<div class="cc_img">
							<img src="http://i.imgur.com/ewMjaHv.png" />
							</div> -->
							
							
						</div>
						
					</div>			
				</div>	
			</div>

			<hr class="blue-hr" />

			<?php } ?>

			<div id="errordiv" name="errordiv"> </div>
			

			<div class="form-group agreement">

				<div class="terms_and_conditions" style="">
					
				</div>

            	<div id="errordiv3" name="errordiv3"> </div>
		  		<input id="acceptTerms-2" name="acceptTerms2" type="checkbox" class="required" />&nbsp;<?php $link = sprintf('%s://%s', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http', $_SERVER['HTTP_HOST']); ?>
				<strong>I have read the <a href="<?php echo $link?>/affiliates/" target="_blank">Terms &amp; Conditions </a> carefully!</strong> <span class="required">*</span>

				<br /><br />	
                      
                <div id="errordiv2" name="errordiv2"> </div>
				<input id="acceptTerms-1" name="acceptTerms1" type="checkbox" />
				&nbsp;<?php $link = sprintf('%s://%s', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http', $_SERVER['HTTP_HOST']); ?><strong>By checking this box you agree to joining our Partner Program & agree to our <a href="<?php echo $link?>/affiliatesterms/" target="_blank" >Partner Terms & Conditions</a>.</strong>
	
            </div>  
            
            <?php if ($membership != 'Free') { ?>
			<div class="order_information">
				<table class="table table-stripped">
					<tr>
						<td class="order_info_title"><?=$membership?> Package</td>
						<td class="order_info_value order_info_price"><?=$membership != 'Free' ? '(x1) - ' : ''?> $<?=$price?>.00</td>
					</tr>
					<tr>
						<td class="order_info_title">Billing:</td>
						<td class="order_info_value order_info_billing"></td>
					</tr>
					<tr>
						<td class="order_info_title">Payment:</td>
						<td class="order_info_value order_info_payment"></td>
					</tr>
				</table>
            </div>

			<hr />
			<?php } ?>
			

			<!-- H-Fields -->
			<?php if($membership == 'Free') { ?>
				<input type='hidden' name='reg_by' value='Free' />
			<?php } ?>
			<input id="original_membership" name="original_membership" type="hidden" value="<?php echo $membership; ?> " />
			<input id="membership" name="membership" type="hidden" value="<?php echo $membership; ?> " />
			<input name="price" type="hidden" value="<?php echo $price; ?> " />
			<input type="hidden" value="" name="hidden_payment_type" />
			<input type="hidden" value="q" name="q" />

			
        </fieldset>
        <!-- End of 4th Step -->
		
		
    </form>
    <div class="loader_processing"><img src="img/ajax-loader.gif" /></div>
</div>
<br />
<br />
<div class="final_agreement">
	<p>By clicking the Complete Order button, you agree to the purchase and subsequent payment for this order.</p>
</div>

</div>



			
			<div class="col-md-12 creditcard-form box">
				<div class="form-group">
					<legend class="cc">Credit Card Billing Information</legend>
				</div>
				<div class="form-group has-feedback ">
					<div class="input-group">
						<input type="text" id="f_name" name="f_name" value="" class="form-control required" placeholder="First name" />
						<span class="input-group-addon">REQUIRED</span>
					</div>
					<div id="f_name_error_container"></div>
				</div>
				<div class="form-group has-feedback ">
					<div class="input-group">
						<input type="text" id="l_name" name="l_name" value="" class="form-control required" placeholder="Last name"  />
						<span class="input-group-addon">REQUIRED</span>
					</div>
					<div id="l_name_error_container"></div>
				</div>
				<div class="form-group has-feedback ">
					<!-- <div class="input-group"> -->
						<input type="text" id="phone" name="phone" value="" class="form-control" placeholder="Phone - (555)-555-5555" />

						<!-- <span class="input-group-addon">REQUIRED</span> -->
					<!-- </div> -->
					<div class="alert alert-info alert-phone">Enter your cell number if you would like to receive alert messages via text message.</div>
				</div>
				<div class="form-group">
					<input type="text" id="company" name="company" value="" class="form-control" placeholder="Company"  />
				</div>
				<div class="form-group has-feedback ">
					<div class="input-group">
						<input type="text" id="address_1" name="address_1" value="" class="form-control required" placeholder="Address 1" />
						<span class="input-group-addon">REQUIRED</span>
					</div>
					<div id="address_1_error_container"></div>
				</div>
				<div class="form-group">
					<input type="text" id="address_2" name="address_2" value="" class="form-control" placeholder="Address 2" />
				</div>
				<div class="form-group has-feedback ">
					<div class="input-group">
						<input type="text" id="city" name="city" value="" class="form-control required" placeholder="City" />
						<span class="input-group-addon">REQUIRED</span>
					</div>	
					<div id="city_error_container"></div>
				</div>
				<div class="form-group has-feedback ">
					<div class="input-group">
						<div id="statebox">
							<select class="form-control required" name="us_state" id="us_state" aria-required="true"><option value="" disabled selected>Choose Your State</option><option value="AL">Alabama</option><option value="AK">Alaska</option><option value="AZ">Arizona</option><option value="AR">Arkansas</option><option value="CA">California</option><option value="CO">Colorado</option><option value="CT">Connecticut</option><option value="DE">Delaware</option><option value="DC">District Of Columbia</option><option value="FL">Florida</option><option value="GA">Georgia</option><option value="HI">Hawaii</option><option value="ID">Idaho</option><option value="IL">Illinois</option><option value="IN">Indiana</option><option value="IA">Iowa</option><option value="KS">Kansas</option><option value="KY">Kentucky</option><option value="LA">Louisiana</option><option value="ME">Maine</option><option value="MD">Maryland</option><option value="MA">Massachusetts</option><option value="MI">Michigan</option><option value="MN">Minnesota</option><option value="MS">Mississippi</option><option value="MO">Missouri</option><option value="MT">Montana</option><option value="NE">Nebraska</option><option value="NV">Nevada</option><option value="NH">New Hampshire</option><option value="NJ">New Jersey</option><option value="NM">New Mexico</option><option value="NY">New York</option><option value="NC">North Carolina</option><option value="ND">North Dakota</option><option value="OH">Ohio</option><option value="OK">Oklahoma</option><option value="OR">Oregon</option><option value="PA">Pennsylvania</option><option value="RI">Rhode Island</option><option value="SC">South Carolina</option><option value="SD">South Dakota</option><option value="TN">Tennessee</option><option value="TX">Texas</option><option value="UT">Utah</option><option value="VT">Vermont</option><option value="VA">Virginia</option><option value="WA">Washington</option><option value="WV">West Virginia</option><option value="WI">Wisconsin</option><option value="WY">Wyoming</option></select>
						</div>
						<span class="input-group-addon">REQUIRED</span>
					</div>
					<div id="us_state_error_container"></div>
				</div>
				<div class="form-group has-feedback ">
					<div class="input-group">
						<input type="text" id="zip" name="zip" value="" class="form-control required" placeholder="Zip Code" />
						<span class="input-group-addon">REQUIRED</span>
					</div>
					<div id="zip_error_container"></div>
				</div>
				
				
			</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body" id="myModalBody"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="close_modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div id="dialog-confirm" title="Don't Miss Out">
  <p id="dialog-text"></p>
</div>

<!-- End Modal -->

<!-- Steps -->
<script src="js/plugins/steps/jquery.steps.min.js"></script>

<!-- Jquery Validate -->
<script src="js/plugins/validate/jquery.validate.min.js"></script>

<!-- jQuery Credit Card Validator -->
<script src="js/jquery.creditCardValidator.js"></script>

<script>

</script>

<script>
	var siteUrl = "<?php echo GLC_URL; ?>";
	var plan_membership = "<?php echo $membership; ?>";
	var warning = 0;

	// details for shipping and billing 
	// var name, address, city, state, zip, country phone, pay_type, cc_type, cc_last_digit;
	var name;
	var address;
	var state;
	var city; 
	var country;
	var zip;
	var phone;

	var payment_info;
	var billing_info;

	var cc_type; 
	var cc_last;
	var cc_passed = 0;

	var final_step_text = "Complete Order";

       $(document).ready(function(){

			
    
       		$('body').on('click', '#close_modal', function(){
       			$('#myModal').hide().removeClass('in');
       		});

       		var show_modal = function(title, message){
       			$modal = $('#myModal');
       			$modal.find('#myModalLabel').text("");
       			$modal.find('#myModalBody').text("");
       			$modal.find('#myModalLabel').text(title);
       			$modal.find('#myModalBody').text(message);
       			$modal.show().addClass("in");

       			var $dialog = $modal.find(".modal-dialog");
       			var margin = ($(window).outerHeight() - $dialog.outerHeight()) / 2;
       			$dialog.css({
       				"margin-top" : margin 
       			});
       		}


       		$(function(){

       			$('#cc_number').keyup(function() {
       				$('#cc_number').validateCreditCard(function(result) {
       					// console.log(result);
       				    if(result.card_type == null)
       				    {
       				        $('#cc_number').removeClass();
       				        $('#cc_number').addClass('form-control required');
       				    
       				    }
       				    else
       				    {
       				    	$('#cc_number').addClass(result.card_type.name + ' form-control required');
       			        	// cc_last = cc_last.replace(/.(?=.{4})/g, 'x');	
       			        
       				    }
       				    
       				    if(!result.valid)
       				    {
       				        $('#cc_number').removeClass();
       				        $('#cc_number').addClass('form-control required');
							$('td.order_info_payment').html('');
							cc_passed = 0;
       				    }
       				    else
       				    {
       				        $('#cc_number').addClass("valid");

       				        $('#cc_number_error_container').hide();
       				        cc_type = result.card_type.name;
       				        $('td.order_info_payment').html('');
       				        $('td.order_info_payment').html('<span class="card_type_text">' + cc_type + '</span>')

       				        cc_last = $('#cc_number').val().replace(/.(?=.{4})/g, 'x');

       				        $('td.order_info_payment').append('<br />' + cc_last);
       				        cc_passed = 1;
       				    }
       				});
       			});
       		});
				


		   $(function(){
				$('div.product-chooser').not('.disabled').find('div.product-chooser-item').on('click', function(){
					$(this).parent().parent().find('div.product-chooser-item').removeClass('selected');
					$(this).addClass('selected');
					if($(this).find('input[type="radio"]').val() == 'E-pin') {
						$('.e_pin').show();
						$('#isepin').val('yes');
					} else {
						$('.e_pin').hide();
						$('#isepin').val('no');
					}
					$(this).find('input[type="radio"]').prop("checked", true);
					
				});
			});

			var form = $("#register-form").show();
			form.steps({
				headerTag: "h3",
				bodyTag: "fieldset",
				transitionEffect: "slideLeft",
				labels: {
					next: "Continue &raquo;",
					previous: "&laquo; Back",
					finish: final_step_text,
				},
				onInit: function(event, current) {
					$('.actions > ul > li:first-child').attr('style', 'display:none');
					
					$('.steps').attr('style', 'pointer-events:none');

					$('.terms_and_conditions').load("/glc/termsconditions.php");
				},
				onStepChanging: function (event, currentIndex, newIndex)
				{
					if ( $('#country').val() ) {
						country = $('#country').val();
					}

					// check fields for order review details 
					if ( $('#f_name').val() ) { 
						name = $('#f_name').val();
						// console.log(name);
					}

					if ( $('#l_name').val() ) {
						name = name + ' ' + $('#l_name').val();
						// console.log(name);
					}

					if ( $('#address_1').val() ) {
						address = $('#address_1').val();
					}

					if ( $('#address_2').val() ) {
						address = address + '<br />' + $('#address_2').val();
						// console.log(address);
					}					

					if ( $('#city').val() ) {
						city = $('#city').val();
					}					

					switch(country) {
						case "US": state = $('#us_state option:selected').text(); break;
						case "CA": state = $('#us_state option:selected').text(); break;
						default: state = $('#us_state').val();
					}

					if ( $('#zip').val() ) {
						zip = $('#zip').val();
					}

					if ( $('#phone').val() ) {
						phone = $('#phone').val();
					} else{
						phone = '';
					}

					billing_info = name + '<br />' + address + ' <br />' + city + ', ' + state + '<br />' + country + ', ' + zip + '<br />' + phone;
					// console.log(billing_info);
					
					payment_info = cc_type;
					


                    if (currentIndex > newIndex)
                    {
                        return true;
                    }
					// Needed in some cases if the user went back (clean up)
					if (currentIndex < newIndex)
					{
						// To remove error styles
						form.find(".body:eq(" + newIndex + ") label.error").remove();
						form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
					}
					form.validate().settings.ignore = ":disabled,:hidden";
					return form.valid();
				},
				onStepChanged: function (event, currentIndex, priorIndex)
				{
					if (currentIndex > 0) {
					    $('.actions > ul > li:first-child').attr('style', '');
					    // $('.wizard > .content').attr('style', 'background: #fff');
					}
					else if (currentIndex < 1) {
						// $('.wizard > .content').attr('style', 'background: #eee');
					}
					 else {
					    $('.actions > ul > li:first-child').attr('style', 'display:none');
					}

					// if on Step #2
					if (currentIndex == 1 && plan_membership != "Free") {
						$('.actions').attr('style', 'display:none');
					}

					if (currentIndex == 1 && plan_membership == "Free") {
						$('.actions > ul > li:first-child').attr('style', 'display:none');
					}

					// if on Step #3
					if (currentIndex == 2) {
						$('.actions > ul > li:first-child').attr('style', 'display:none');

						$('.actions').attr('style', 'text-align:center');
						$('.final_agreement').attr('style', 'display: block');
						// $('.steps').attr('style', 'pointer-events:none');
						
						// populate fields on 3rd step
						$('td.order_info_billing').html(billing_info);
						$('td.order_info_payment').html(payment_info);
					}	 


				},
				onFinishing: function (event, currentIndex)
				{
					form.validate().settings.ignore = ":disabled,:hidden";

					if (plan_membership == "Free") {
						cc_passed = 1;
					}

					return form.valid();
				},
				onFinished: function (event, currentIndex)
				{  
					if(!$("#acceptTerms-1").is(":checked") && warning == 0){
	                    $('#membership').val($('#original_membership').val());
						$('#dialog-text').text('');
						$('#dialog-text').text("Join the GLC Partner Program now and start earning some extra Money. It's absolutely free to join. Click the box to become a Partner now, you'll be glad you did.");
						$("#dialog-confirm").dialog({
					      	resizable: true,
					      	modal: true,
					      	buttons: {
					        	"Join Now »": function() {
					          		$( this ).dialog( "close" );
					        	}
					      	}
					    });
					    warning = 1;
	                    return false;
					}



					if (cc_passed == 0) {
						// cc validation failed
						$('#cc_number_error_container').attr('style', 'display:block');
						$('#cc_number_error_container').html('');
						$('#cc_number_error_container').html('<label id="cc_number-error" class="error" for="cc_number">Credit Card details invalid.</label>')
						return false;
					}

					//Confirm the package they are registering for
                    // if($('#isepin').val() == 'yes' && $('#original_membership').val() !== $('#membership').val()){
                    //     $('#dialog-text').text('');
                        
                    //     var text = 'You are purchasing a GLC <b>'+$('#original_membership').val()+'</b> Product package, from Enroller <b>'+$('#real_parent').val()+'</b>. However, the voucher you’re using is for <b>'+$('#membership').val()+'</b> membership. \nIf you continue, you will be registered as a <b>'+$('#membership').val()+'</b> member. Please click CONFIRM to continue.';
                    //     if($("#real_parent").val() == ""){
                    //         text = 'You are purchasing a GLC <b>'+$('#original_membership').val()+'</b> Product package. However, the voucher you’re using is for <b>'+$('#membership').val()+'</b> membership. \nIf you continue, you will be registered as a <b>'+$('#membership').val()+'</b> member. Please click CONFIRM to continue.';
                    //     }
                    //     $('#dialog-text').html(text);
                        
                    // } else {
                    //     $('#membership').val($('#original_membership').val());
                    //     $('#dialog-text').text('');
                    //     var text = 'You are purchasing a GLC <b>'+$('#original_membership').val()+'</b> Product package, from Enroller <b>'+$('#real_parent').val()+'</b>. Please click CONFIRM to continue.';
                    //     if($("#real_parent").val() == ""){
                    //         text = 'You are purchasing a GLC <b>'+$('#original_membership').val()+'</b> Product package. Please click CONFIRM to continue.';
                    //     }
                    //     $('#dialog-text').html(text);
                    // }
                    
                    form.validate().settings.ignore = ":disabled,:hidden";
	        		// console.log($("#register-form").serialize());
	        		// event.preventDefault();
	        		// return false;
					
					$.post('submit.php', $('#register-form').serialize(), function( data ) { 
						// alert('submitting form');
						// event.preventDefault();
						// return false;
						if(data.result == 'error'){
							alert(data.message);
						} else {
							window.location.replace(data.message);
						}
						//alert( "Data Loaded: " + data );
					}, 'json');

					$('.loader_processing').attr('style', 'display:block');
					$('.actions').attr('style', 'display:none');
					



					// $("#dialog-confirm").dialog({
				 //      	resizable: true,
				 //      	modal: true,
				 //      	buttons: {
				 //        	"Confirm": function(event) {
				 //        		form.validate().settings.ignore = ":disabled,:hidden";
				 //        		// console.log($("#register-form").serialize());
				 //        		// event.preventDefault();
				 //        		// return false;
					// 			$.post('submit.php', $('#register-form').serialize(), function( data ) { 

					// 				if(data.result == 'error'){
					// 					alert(data.message);
					// 				} else {
					// 					window.location.replace(data.message);
					// 				}
					// 				//alert( "Data Loaded: " + data );
					// 			}, 'json');
				 //          		$( this ).dialog( "close" );
				 //        	},
				 //        	"Cancel": function() {
				 //        		$( this ).dialog( "close" );
				 //          		return false;
				 //        	}
				 //      	}
				 //    });
				}
			}).validate({
				errorPlacement: function errorPlacement(error, element) { 
					if (element.attr("name") == "reg_by" )
				        error.appendTo('#errordiv');
				    else if  (element.attr("name") == "acceptTerms1" )
				        error.appendTo('#errordiv2');
				    else if  (element.attr("name") == "acceptTerms2" )
				        error.appendTo('#errordiv3');
				    else if (element.attr("name") == "pay_method")
				    	error.appendTo('#errordiv4');
				    else if (element.attr("name") == "email")
				    	error.appendTo('#email_error_container');
				    else if (element.attr("name") == "username")
				    	error.appendTo('#username_error_container');
				    else if (element.attr("name") == "password")
				    	error.appendTo('#password_error_container');
				    else if (element.attr("name") == "re_password")
				    	error.appendTo('#re_password_error_container');
				    else if (element.attr("name") == "country")
				    	error.appendTo('#country_error_container');
				    else if (element.attr("name") == "f_name")
				    	error.appendTo('#f_name_error_container');
				    else if (element.attr("name") == "l_name")
				    	error.appendTo('#l_name_error_container');
				    else if (element.attr("name") == "address_1")
				    	error.appendTo('#address_1_error_container');
				    else if (element.attr("name") == "city")
				    	error.appendTo('#city_error_container');
				    else if (element.attr("name") == "us_state")
				    	error.appendTo('#us_state_error_container');
				    else if (element.attr("name") == "zip")
				    	error.appendTo('#zip_error_container');
				    else if (element.attr("name") == "cc_number")
				    	error.appendTo('#cc_number_error_container');
				    else if (element.attr("name") == "expireMM")
				    	error.appendTo('#expireMM_error_container');
				    else if (element.attr("name") == "expireYY")
				    	error.appendTo('#expireYY_error_container');
				    else if (element.attr("name") == "cc_ccv")
				    	error.appendTo('#cc_ccv_error_container');
				    else
				        error.insertAfter(element);
				},
				rules: {

					username: {
						required: true,
						//minlength: 3,
                        remote: 
						{
                            url: "inc_checkusername.php",
                            type: "post",
							cache: false,
                            data:
                            {
                                username: function()
                                {
                                    return $('#username').val();
                                }
                            }
                        }
                    },
					epin: {
						required: true,
						minlength: 5,
                        remote: 
						{
                            url: "inc_checkepin.php",
                            type: "post",
							cache: false,
                            data:
                            {
                                epin: function()
                                {
                                    return $('#epin').val();
                                },
                                membership : plan_membership
                            },
                            complete: function(data){
                            	var data = data.responseText;
                            	if(data == 'Executive' || data == 'Leadership' || data == 'Professional' || data == 'Masters'){
                            		$('body').find('#membership').val(data);
                            		return false;
                            	} else {
                            		$('body').find('#membership').val($('#original_membership').val());
                            		return false;
                            	}
		                    }
                        }
                    },
					real_parent: {
                        remote: 
						{
                            url: "inc_checksponsor.php",
                            type: "post",
							cache: false,
                            data:
                            {
                                real_parent: function()
                                {
                                    return $('#real_parent').val();
                                }
                            }


                        }
                    },
					email: {
						required: true,
                        remote: 
						{
                            url: "inc_checkemail.php",
                            type: "post",
							cache: false,
                            data:
                            {
                                email: function()
                                {
                                    return $('#email').val();
                                }
                            }
                        }
                    }, 
					re_password: {
						required: true,
						equalTo: "#password"
					},
					acceptTerms2: {
						required: true
					},
					reg_by: {
						required: true
					},
					phone: {
						// required: true,
						numericRegex: true
					},
					pay_method: {
						required: true
					}
				},  
				messages: {
                    username: {remote: "Username already exists"},
                    email: { 
                        remote: "Email already exists" 
                    },
					real_parent: "Please check your Enroller's Username. As Entered, this enroller does not exist in our system. If the problem persists, please contact Support.",
					epin: {
						remote: "Wrong e-Voucher"	
					},
					re_password: {
						equalTo: "Your password don't match. Please re-enter your password."
					},
					acceptTerms2: "Please accept the Terms & Conditions.",
					reg_by: "Please select one of the Payment Methods below to complete your purchase.",
					homephone: "Please input valid phone number.",
					pay_method: "Please choose a payment option.",
					cc_number: "Invalid Credit Card Type"
				}
			});
			//clear fields
		$('#username').on('change', function() {
			$("#username-error").hide();
			$("#username").removeData("previousValue");
		});
		$('#email').on('change', function() {
			$("#email-error").hide();
			$("#email").removeData("previousValue");
		});
		$('#epin').on('change', function() {
			$("#epin_error").hide();
			$("#epin-error").hide();
			$("#epin").removeData("previousValue");
		});
		$('#real_parent').on('change', function() {
			$("#real_parent-error").hide();
			$("#real_parent").removeData("previousValue");
		});
			
		$('#country_container').append('<select name="country" id="country" class="form-control required"><option value="" disabled selected>Choose Your Country</option><option value="US">United States of America</option><option value="CA">Canada</option><option value="GB">United Kingdom</option><option value="AF">Afghanistan</option><option value="AL">Albania</option><option value="DZ">Algeria</option><option value="AS">American Samoa</option><option value="AD">Andorra</option><option value="AO">Angola</option><option value="AI">Anguilla</option><option value="AG">Antigua &amp; Barbuda</option><option value="AR">Argentina</option><option value="AA">Armenia</option><option value="AW">Aruba</option><option value="AU">Australia</option><option value="AT">Austria</option><option value="AZ">Azerbaijan</option><option value="BS">Bahamas</option><option value="BH">Bahrain</option><option value="BD">Bangladesh</option><option value="BB">Barbados</option><option value="BY">Belarus</option><option value="BE">Belgium</option><option value="BZ">Belize</option><option value="BJ">Benin</option><option value="BM">Bermuda</option><option value="BT">Bhutan</option><option value="BO">Bolivia</option><option value="BL">Bonaire</option><option value="BA">Bosnia &amp; Herzegovina</option><option value="BW">Botswana</option><option value="BR">Brazil</option><option value="BC">British Indian Ocean Ter</option><option value="BN">Brunei</option><option value="BG">Bulgaria</option><option value="BF">Burkina Faso</option><option value="BI">Burundi</option><option value="KH">Cambodia</option><option value="CM">Cameroon</option><option value="IC">Canary Islands</option><option value="CV">Cape Verde</option><option value="KY">Cayman Islands</option><option value="CF">Central African Republic</option><option value="TD">Chad</option><option value="CD">Channel Islands</option><option value="CL">Chile</option><option value="CN">China</option><option value="CI">Christmas Island</option><option value="CS">Cocos Island</option><option value="CO">Colombia</option><option value="CC">Comoros</option><option value="CG">Congo</option><option value="CK">Cook Islands</option><option value="CR">Costa Rica</option><option value="CT">Cote D Ivoire</option><option value="HR">Croatia</option><option value="CU">Cuba</option><option value="CB">Curacao</option><option value="CY">Cyprus</option><option value="CZ">Czech Republic</option><option value="DK">Denmark</option><option value="DM">Dominica</option><option value="DO">Dominican Republic</option><option value="EC">Ecuador</option><option value="EG">Egypt</option><option value="SV">El Salvador</option><option value="GQ">Equatorial Guinea</option><option value="ER">Eritrea</option><option value="EE">Estonia</option><option value="ET">Ethiopia</option><option value="FA">Falkland Islands</option><option value="FO">Faroe Islands</option><option value="FJ">Fiji</option><option value="FI">Finland</option><option value="FR">France</option><option value="GF">French Guiana</option><option value="PF">French Polynesia</option><option value="FS">French Southern Ter</option><option value="GA">Gabon</option><option value="GM">Gambia</option><option value="GE">Georgia</option><option value="DE">Germany</option><option value="GH">Ghana</option><option value="GI">Gibraltar</option><option value="GB">Great Britain</option><option value="GR">Greece</option><option value="GL">Greenland</option><option value="GD">Grenada</option><option value="GP">Guadeloupe</option><option value="GU">Guam</option><option value="GT">Guatemala</option><option value="GN">Guinea</option><option value="GY">Guyana</option><option value="HT">Haiti</option><option value="HW">Hawaii</option><option value="HN">Honduras</option><option value="HK">Hong Kong</option><option value="HU">Hungary</option><option value="IS">Iceland</option><option value="IN">India</option><option value="ID">Indonesia</option><option value="IA">Iran</option><option value="IQ">Iraq</option><option value="IR">Ireland</option><option value="IM">Isle of Man</option><option value="IL">Israel</option><option value="IT">Italy</option><option value="JM">Jamaica</option><option value="JP">Japan</option><option value="JO">Jordan</option><option value="KZ">Kazakhstan</option><option value="KE">Kenya</option><option value="KI">Kiribati</option><option value="NK">Korea North</option><option value="KS">Korea South</option><option value="KW">Kuwait</option><option value="KG">Kyrgyzstan</option><option value="LA">Laos</option><option value="LV">Latvia</option><option value="LB">Lebanon</option><option value="LS">Lesotho</option><option value="LR">Liberia</option><option value="LY">Libya</option><option value="LI">Liechtenstein</option><option value="LT">Lithuania</option><option value="LU">Luxembourg</option><option value="MO">Macau</option><option value="MK">Macedonia</option><option value="MG">Madagascar</option><option value="MY">Malaysia</option><option value="MW">Malawi</option><option value="MV">Maldives</option><option value="ML">Mali</option><option value="MT">Malta</option><option value="MH">Marshall Islands</option><option value="MQ">Martinique</option><option value="MR">Mauritania</option><option value="MU">Mauritius</option><option value="ME">Mayotte</option><option value="MX">Mexico</option><option value="MI">Midway Islands</option><option value="MD">Moldova</option><option value="MC">Monaco</option><option value="MN">Mongolia</option><option value="MS">Montserrat</option><option value="MA">Morocco</option><option value="MZ">Mozambique</option><option value="MM">Myanmar</option><option value="NA">Nambia</option><option value="NU">Nauru</option><option value="NP">Nepal</option><option value="AN">Netherland Antilles</option><option value="NL">Netherlands (Holland, Europe)</option><option value="NV">Nevis</option><option value="NC">New Caledonia</option><option value="NZ">New Zealand</option><option value="NW">Niue</option><option value="NF">Norfolk Island</option><option value="NO">Norway</option><option value="OM">Oman</option><option value="PK">Pakistan</option><option value="PW">Palau Island</option><option value="PS">Palestine</option><option value="PA">Panama</option><option value="PG">Papua New Guinea</option><option value="PY">Paraguay</option><option value="PE">Peru</option><option value="PH">Philippines</option><option value="PO">Pitcairn Island</option><option value="PL">Poland</option><option value="PT">Portugal</option><option value="PR">Puerto Rico</option><option value="QA">Qatar</option><option value="ME">Montenegro</option><option value="RS">Serbia</option><option value="RE">Reunion</option><option value="RO">Romania</option><option value="RU">Russia</option><option value="RW">Rwanda</option><option value="NT">St Barthelemy</option><option value="EU">St Eustatius</option><option value="HE">St Helena</option><option value="KN">St Kitts-Nevis</option><option value="LC">St Lucia</option><option value="MB">St Maarten</option><option value="PM">St Pierre &amp; Miquelon</option><option value="VC">St Vincent &amp; Grenadines</option><option value="SP">Saipan</option><option value="SO">Samoa</option><option value="AS">Samoa American</option><option value="SM">San Marino</option><option value="ST">Sao Tome &amp; Principe</option><option value="SA">Saudi Arabia</option><option value="SN">Senegal</option><option value="RS">Serbia</option><option value="SC">Seychelles</option><option value="SL">Sierra Leone</option><option value="SG">Singapore</option><option value="SK">Slovakia</option><option value="SI">Slovenia</option><option value="SB">Solomon Islands</option><option value="OI">Somalia</option><option value="ZA">South Africa</option><option value="ES">Spain</option><option value="LK">Sri Lanka</option><option value="SD">Sudan</option><option value="SR">Suriname</option><option value="SZ">Swaziland</option><option value="SE">Sweden</option><option value="CH">Switzerland</option><option value="SY">Syria</option><option value="TA">Tahiti</option><option value="TW">Taiwan</option><option value="TJ">Tajikistan</option><option value="TZ">Tanzania</option><option value="TH">Thailand</option><option value="TG">Togo</option><option value="TK">Tokelau</option><option value="TO">Tonga</option><option value="TT">Trinidad &amp; Tobago</option><option value="TN">Tunisia</option><option value="TR">Turkey</option><option value="TU">Turkmenistan</option><option value="TC">Turks &amp; Caicos Is</option><option value="TV">Tuvalu</option><option value="UG">Uganda</option><option value="UA">Ukraine</option><option value="AE">United Arab Emirates</option><option value="UY">Uruguay</option><option value="UZ">Uzbekistan</option><option value="VU">Vanuatu</option><option value="VS">Vatican City State</option><option value="VE">Venezuela</option><option value="VN">Vietnam</option><option value="VB">Virgin Islands (Brit)</option><option value="VA">Virgin Islands (USA)</option><option value="WK">Wake Island</option><option value="WF">Wallis &amp; Futana Is</option><option value="YE">Yemen</option><option value="ZR">Zaire</option><option value="ZM">Zambia</option><option value="ZW">Zimbabwe</option></select>');



			//change state based on country
		$('#country').on('change', function() {
			var states;
			switch(this.value) {
				case 'US':
					states = '<select class="form-control required" name="us_state" id="us_state" aria-required="true"><option value="" disabled selected>Choose Your State</option><option value="AL">Alabama</option><option value="AK">Alaska</option><option value="AZ">Arizona</option><option value="AR">Arkansas</option><option value="CA">California</option><option value="CO">Colorado</option><option value="CT">Connecticut</option><option value="DE">Delaware</option><option value="DC">District Of Columbia</option><option value="FL">Florida</option><option value="GA">Georgia</option><option value="HI">Hawaii</option><option value="ID">Idaho</option><option value="IL">Illinois</option><option value="IN">Indiana</option><option value="IA">Iowa</option><option value="KS">Kansas</option><option value="KY">Kentucky</option><option value="LA">Louisiana</option><option value="ME">Maine</option><option value="MD">Maryland</option><option value="MA">Massachusetts</option><option value="MI">Michigan</option><option value="MN">Minnesota</option><option value="MS">Mississippi</option><option value="MO">Missouri</option><option value="MT">Montana</option><option value="NE">Nebraska</option><option value="NV">Nevada</option><option value="NH">New Hampshire</option><option value="NJ">New Jersey</option><option value="NM">New Mexico</option><option value="NY">New York</option><option value="NC">North Carolina</option><option value="ND">North Dakota</option><option value="OH">Ohio</option><option value="OK">Oklahoma</option><option value="OR">Oregon</option><option value="PA">Pennsylvania</option><option value="RI">Rhode Island</option><option value="SC">South Carolina</option><option value="SD">South Dakota</option><option value="TN">Tennessee</option><option value="TX">Texas</option><option value="UT">Utah</option><option value="VT">Vermont</option><option value="VA">Virginia</option><option value="WA">Washington</option><option value="WV">West Virginia</option><option value="WI">Wisconsin</option><option value="WY">Wyoming</option></select>';
					break;
				case 'CA':
					states = '<select class="form-control required" name="us_state" id="us_state" aria-required="true"><option value="" disabled selected>Choose Your State</option><option value="AB">Alberta</option><option value="BC">British Columbia</option><option value="MB">Manitoba</option><option value="NB">New Brunswick</option><option value="NL">Newfoundland and Labrador</option><option value="NT">Northwest Territories</option><option value="NS">Nova Scotia</option><option value="NU">Nunavut</option><option value="ON">Ontario</option><option value="PE">Prince Edward Island</option><option value="QC">Quebec</option><option value="SK">Saskatchewan</option><option value="YT">Yukon</option></select>';
					break;
				default:
					states = '<input type="text" class="form-control required" name="us_state" id="us_state" aria-required="true" placeholder="Your Providence">';
					break;
			} 
		  $('#statebox').html(states);
		});

		

		

		// custom validator functions
		$.validator.addMethod("numericRegex", function(value, element) {
        	return this.optional(element) || /\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/i.test(value);
    	}, "Valid phone number must contain numbers only.");

    	
		/*/ prefilled information /*/
        $('#email').val('sample@email.com');
        $('#username').val('jsmith');
        $('#password').val('1234');
        $('input[name="re_password"]').val('1234');
        $('#country').val('US');

		// set box to hide first
		$(".box").hide();
		
        $('div.product-chooser').not('.disabled').find('div.product-chooser-item').on('click', function(){
			
            if($(this).find('input[type="radio"]').val()=="creditcard"){

            	$('#payments').hide();
                $('.payment_form_container').append( $('.creditcard-form').html() );

				$('#f_name').val('Johnny');
		        $('#l_name').val('Bravo');
		        $('#city').val('Davao');
		        $('#us_state').val('AL');
		        $('#company').val('AEP Corporations');
		        $('#phone').val('(582)-299-4872');
		        $('#address_1').val('487 Jeremiah Street');
		        $('#address_2').val('Alpha Homes, Matina');
		        $('#zip').val('80000');
		        $('#acceptTerms-2').prop('checked', true);
		        $('#acceptTerms-1').prop('checked', true); 
		        $('#cc_number').val('370000000000002');
		        // $('#cc_number').val('4111111111111111');
		        $('#expireMM').val('10');
		        $('#expireYY').val('2020');
		        $('#cc_ccv').val('456');

		        $('.actions').attr('style', 'display:block');
		        $('.actions > ul > li:first-child').attr('style', 'display:none');

                $('input[name="hidden_payment_type"]').val("creditcard");

                // console.log('creditcard');
            }
			if($(this).find('input[type="radio"]').val()=="echeck"){

				$('.box').hide();
				$('.echeck-box').show();

				$('input[name="hidden_payment_type"]').val("echeck");

				// console.log('echeck');

				// reset CC form 
				// $('#cc_number').val('');
		  //       $('#expireMM').val('');
		  //       $('#expireYY').val('');
		  //       $('#cc_ccv').val('');
            }

        });
	
        $(".wizard > .actions a").parent().each(function(){       
            if ($(this).attr("aria-disabled") === "false")
            {
                $(this).find('a').addClass("blue");
            }
            else if ($(this).attr("aria-disabled") === "true")
            {
                $(this).find('a').css("background", "#666");
            }
            else
            {
                $(this).find('a').addClass("blue sign_up");
                // $(this).find('a').html("Start Subscription &raquo;");
            }
        });
        


        

    });




 	

    </script>

</body>
</html>

