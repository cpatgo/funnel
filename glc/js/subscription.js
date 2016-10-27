

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
var form = $("#register-form").show();
var referform = $('#new-referral-form').show();

var affiliate_warning = 0;

var isUS = 0;

$(function(){
	if (plan_membership_slug == 'free') {
		$('.step_2').show();
		$('.step_3').show();
		$('.finish').show();
	}

	

	referform.validate({
		errorPlacement: function errorPlacement(error, element) {
			if (element.attr("name") == "new_affiliate" )
				error.appendTo("#new_affiliate_error_container");
		},

		rules: {
			new_affiliate: {
				required: true,
                remote: {
                    url: "inc_checksponsor.php",
                    type: "post",
					cache: false,
                    data: {
                        real_parent: function() {
                            return $('#new_affiliate').val();
                        }
                    }
                }
			}
		},
		messages: {
			required: "REQUIRED!",
			new_affiliate: {
				remote: "Please check your Enroller's Username. As Entered, this enroller does not exist in our system. If the problem persists, please contact Support."
			}
		}
	});

	form.validate({
		errorPlacement: function errorPlacement(error, element) { 
			if (element.attr("name") == "reg_by" )
		        error.appendTo('#errordiv');
		    else if  (element.attr("name") == "acceptTerms1" )
		        error.appendTo('#terms1_error_container');
		    else if  (element.attr("name") == "acceptTerms2" )
		        error.appendTo('#terms2_error_container');
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
		    else if (element.attr("name") == "payment_l_name")
		    	error.appendTo('#payment_l_name_error_container');
		    else if (element.attr("name") == "payment_f_name")
		    	error.appendTo('#payment_f_name_error_container');
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
				usernameRegex: true,
				required: true,
				
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
            password: {
            	required: true,
            	equalTo: "#re_password"
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
			},

			cc_ccv: {
				required: true,
				minlength: 3
			}
		},  
		messages: {
            username: {
            	remote: "Username already exists",
            	usernameRegex: "Please use only letters (a-z), numbers, underscores and periods. No spaces allowed."
            },
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
			acceptTerms2: "You must accept our site Terms and Conditions.",
			reg_by: "Please select one of the Payment Methods below to complete your purchase.",
			phone: "Please input valid phone number. Valid phone number must contain the follow '( ) -' and numbers only.",
			pay_method: "Please choose a payment option.",
			cc_ccv: {
				required: "Field required.",
				minlength: "Please enter at least 3 characters."
			}
		}
	});

	
	$('#register-form').validate().settings.ignore = ":disabled,:hidden";
	
	$('#register-form').submit(function(event) {
		// console.log(form.valid());
		// $('#submit_order_btn').attr('disabled', 'disabled');
		
		if ( $('#register-form').valid() ) {
			$('#error-form-message').hide();

			$('.loader_processing').show();
			$('#submit_order_btn').prop('disabled', true);

			$.post('submit_subscription.php', $('#register-form').serialize(), function( data ) { 
				// console.log('submitting form');
				console.log(data);
				if(data.result == 'error'){
					
					$('#error-form-message').show();
					// $('#error-form-message').html('');
					$('#error-form-message').html('Error: ' + data.message + '. Please contact our support at glccenter@outlook.com.');
					// alert(data.message);
					$('.loader_processing').hide();
					$('#submit_order_btn').prop('disabled', false);
				} else {
					window.location.replace(data.message);
				}
				//alert( "Data Loaded: " + data );
			}, 'json');
		}
		else{ 
			$('#error-form-message').show();
			$('#error-form-message').html('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Please check fields that are marked (<span class="required"> * </span>)');
			$('.loader_processing').hide();
			$('#submit_order_btn').prop('disabled', false);
		}
	});




	// field populate
	$('#country_container').html('<select name="country" id="country" class="text form-control required"><option value="" disabled selected>Choose Your Country</option><option value="US">United States of America</option><option value="CA">Canada</option><option value="GB">United Kingdom</option><option value="AF">Afghanistan</option><option value="AL">Albania</option><option value="DZ">Algeria</option><option value="AS">American Samoa</option><option value="AD">Andorra</option><option value="AO">Angola</option><option value="AI">Anguilla</option><option value="AG">Antigua &amp; Barbuda</option><option value="AR">Argentina</option><option value="AA">Armenia</option><option value="AW">Aruba</option><option value="AU">Australia</option><option value="AT">Austria</option><option value="AZ">Azerbaijan</option><option value="BS">Bahamas</option><option value="BH">Bahrain</option><option value="BD">Bangladesh</option><option value="BB">Barbados</option><option value="BY">Belarus</option><option value="BE">Belgium</option><option value="BZ">Belize</option><option value="BJ">Benin</option><option value="BM">Bermuda</option><option value="BT">Bhutan</option><option value="BO">Bolivia</option><option value="BL">Bonaire</option><option value="BA">Bosnia &amp; Herzegovina</option><option value="BW">Botswana</option><option value="BR">Brazil</option><option value="BC">British Indian Ocean Ter</option><option value="BN">Brunei</option><option value="BG">Bulgaria</option><option value="BF">Burkina Faso</option><option value="BI">Burundi</option><option value="KH">Cambodia</option><option value="CM">Cameroon</option><option value="IC">Canary Islands</option><option value="CV">Cape Verde</option><option value="KY">Cayman Islands</option><option value="CF">Central African Republic</option><option value="TD">Chad</option><option value="CD">Channel Islands</option><option value="CL">Chile</option><option value="CN">China</option><option value="CI">Christmas Island</option><option value="CS">Cocos Island</option><option value="CO">Colombia</option><option value="CC">Comoros</option><option value="CG">Congo</option><option value="CK">Cook Islands</option><option value="CR">Costa Rica</option><option value="CT">Cote D Ivoire</option><option value="HR">Croatia</option><option value="CB">Curacao</option><option value="CY">Cyprus</option><option value="CZ">Czech Republic</option><option value="DK">Denmark</option><option value="DM">Dominica</option><option value="DO">Dominican Republic</option><option value="EC">Ecuador</option><option value="EG">Egypt</option><option value="SV">El Salvador</option><option value="GQ">Equatorial Guinea</option><option value="ER">Eritrea</option><option value="EE">Estonia</option><option value="ET">Ethiopia</option><option value="FA">Falkland Islands</option><option value="FO">Faroe Islands</option><option value="FJ">Fiji</option><option value="FI">Finland</option><option value="FR">France</option><option value="GF">French Guiana</option><option value="PF">French Polynesia</option><option value="FS">French Southern Ter</option><option value="GA">Gabon</option><option value="GM">Gambia</option><option value="GE">Georgia</option><option value="DE">Germany</option><option value="GH">Ghana</option><option value="GI">Gibraltar</option><option value="GB">Great Britain</option><option value="GR">Greece</option><option value="GL">Greenland</option><option value="GD">Grenada</option><option value="GP">Guadeloupe</option><option value="GU">Guam</option><option value="GT">Guatemala</option><option value="GN">Guinea</option><option value="GY">Guyana</option><option value="HT">Haiti</option><option value="HW">Hawaii</option><option value="HN">Honduras</option><option value="HK">Hong Kong</option><option value="HU">Hungary</option><option value="IS">Iceland</option><option value="IN">India</option><option value="ID">Indonesia</option><option value="IR">Ireland</option><option value="IM">Isle of Man</option><option value="IL">Israel</option><option value="IT">Italy</option><option value="JM">Jamaica</option><option value="JP">Japan</option><option value="JO">Jordan</option><option value="KZ">Kazakhstan</option><option value="KE">Kenya</option><option value="KI">Kiribati</option><option value="KS">Korea South</option><option value="KW">Kuwait</option><option value="KG">Kyrgyzstan</option><option value="LV">Latvia</option><option value="LB">Lebanon</option><option value="LS">Lesotho</option><option value="LR">Liberia</option><option value="LY">Libya</option><option value="LI">Liechtenstein</option><option value="LT">Lithuania</option><option value="LU">Luxembourg</option><option value="MO">Macau</option><option value="MK">Macedonia</option><option value="MG">Madagascar</option><option value="MY">Malaysia</option><option value="MW">Malawi</option><option value="MV">Maldives</option><option value="ML">Mali</option><option value="MT">Malta</option><option value="MH">Marshall Islands</option><option value="MQ">Martinique</option><option value="MR">Mauritania</option><option value="MU">Mauritius</option><option value="ME">Mayotte</option><option value="MX">Mexico</option><option value="MI">Midway Islands</option><option value="MD">Moldova</option><option value="MC">Monaco</option><option value="MN">Mongolia</option><option value="MS">Montserrat</option><option value="MA">Morocco</option><option value="MZ">Mozambique</option><option value="MM">Myanmar</option><option value="NA">Nambia</option><option value="NU">Nauru</option><option value="NP">Nepal</option><option value="AN">Netherland Antilles</option><option value="NL">Netherlands (Holland, Europe)</option><option value="NV">Nevis</option><option value="NC">New Caledonia</option><option value="NZ">New Zealand</option><option value="NW">Niue</option><option value="NF">Norfolk Island</option><option value="NO">Norway</option><option value="OM">Oman</option><option value="PK">Pakistan</option><option value="PW">Palau Island</option><option value="PS">Palestine</option><option value="PA">Panama</option><option value="PG">Papua New Guinea</option><option value="PY">Paraguay</option><option value="PE">Peru</option><option value="PH">Philippines</option><option value="PO">Pitcairn Island</option><option value="PL">Poland</option><option value="PT">Portugal</option><option value="PR">Puerto Rico</option><option value="QA">Qatar</option><option value="ME">Montenegro</option><option value="RS">Serbia</option><option value="RE">Reunion</option><option value="RO">Romania</option><option value="RU">Russia</option><option value="RW">Rwanda</option><option value="NT">St Barthelemy</option><option value="EU">St Eustatius</option><option value="HE">St Helena</option><option value="KN">St Kitts-Nevis</option><option value="LC">St Lucia</option><option value="MB">St Maarten</option><option value="PM">St Pierre &amp; Miquelon</option><option value="VC">St Vincent &amp; Grenadines</option><option value="SP">Saipan</option><option value="SO">Samoa</option><option value="AS">Samoa American</option><option value="SM">San Marino</option><option value="ST">Sao Tome &amp; Principe</option><option value="SN">Senegal</option><option value="RS">Serbia</option><option value="SC">Seychelles</option><option value="SL">Sierra Leone</option><option value="SG">Singapore</option><option value="SK">Slovakia</option><option value="SI">Slovenia</option><option value="SB">Solomon Islands</option><option value="OI">Somalia</option><option value="ZA">South Africa</option><option value="ES">Spain</option><option value="LK">Sri Lanka</option><option value="SD">Sudan</option><option value="SR">Suriname</option><option value="SZ">Swaziland</option><option value="SE">Sweden</option><option value="CH">Switzerland</option><option value="SY">Syria</option><option value="TA">Tahiti</option><option value="TW">Taiwan</option><option value="TJ">Tajikistan</option><option value="TZ">Tanzania</option><option value="TH">Thailand</option><option value="TG">Togo</option><option value="TK">Tokelau</option><option value="TO">Tonga</option><option value="TT">Trinidad &amp; Tobago</option><option value="TN">Tunisia</option><option value="TR">Turkey</option><option value="TU">Turkmenistan</option><option value="TC">Turks &amp; Caicos Is</option><option value="TV">Tuvalu</option><option value="UG">Uganda</option><option value="UA">Ukraine</option><option value="AE">United Arab Emirates</option><option value="UY">Uruguay</option><option value="UZ">Uzbekistan</option><option value="VU">Vanuatu</option><option value="VS">Vatican City State</option><option value="VE">Venezuela</option><option value="VB">Virgin Islands (Brit)</option><option value="VA">Virgin Islands (USA)</option><option value="WK">Wake Island</option><option value="WF">Wallis &amp; Futana Is</option><option value="YE">Yemen</option><option value="ZR">Zaire</option><option value="ZM">Zambia</option><option value="ZW">Zimbabwe</option></select>');

	$('#country').on('change', function() {
			var states;
			var label_text;

			switch(this.value) {
				case 'US':
					states = '<select class="form-control required" name="us_state" id="us_state" aria-required="true"><option value="" disabled selected>Choose Your State</option><option value="AL">Alabama</option><option value="AK">Alaska</option><option value="AZ">Arizona</option><option value="AR">Arkansas</option><option value="CA">California</option><option value="CO">Colorado</option><option value="CT">Connecticut</option><option value="DE">Delaware</option><option value="DC">District Of Columbia</option><option value="FL">Florida</option><option value="GA">Georgia</option><option value="HI">Hawaii</option><option value="ID">Idaho</option><option value="IL">Illinois</option><option value="IN">Indiana</option><option value="IA">Iowa</option><option value="KS">Kansas</option><option value="KY">Kentucky</option><option value="LA">Louisiana</option><option value="ME">Maine</option><option value="MD">Maryland</option><option value="MA">Massachusetts</option><option value="MI">Michigan</option><option value="MN">Minnesota</option><option value="MS">Mississippi</option><option value="MO">Missouri</option><option value="MT">Montana</option><option value="NE">Nebraska</option><option value="NV">Nevada</option><option value="NH">New Hampshire</option><option value="NJ">New Jersey</option><option value="NM">New Mexico</option><option value="NY">New York</option><option value="NC">North Carolina</option><option value="ND">North Dakota</option><option value="OH">Ohio</option><option value="OK">Oklahoma</option><option value="OR">Oregon</option><option value="PA">Pennsylvania</option><option value="RI">Rhode Island</option><option value="SC">South Carolina</option><option value="SD">South Dakota</option><option value="TN">Tennessee</option><option value="TX">Texas</option><option value="UT">Utah</option><option value="VT">Vermont</option><option value="VA">Virginia</option><option value="WA">Washington</option><option value="WV">West Virginia</option><option value="WI">Wisconsin</option><option value="WY">Wyoming</option></select>';
					label_text = '<span>Province/State: <span class="required" aria-required="true">*</span></span>';
					zip_text = '<span>Zip Code: <span class="required">*</span></span>';
					break;
				case 'CA':
					states = '<select class="form-control required" name="us_state" id="us_state" aria-required="true"><option value="" disabled selected>Choose Your State</option><option value="AB">Alberta</option><option value="BC">British Columbia</option><option value="MB">Manitoba</option><option value="NB">New Brunswick</option><option value="NL">Newfoundland and Labrador</option><option value="NT">Northwest Territories</option><option value="NS">Nova Scotia</option><option value="NU">Nunavut</option><option value="ON">Ontario</option><option value="PE">Prince Edward Island</option><option value="QC">Quebec</option><option value="SK">Saskatchewan</option><option value="YT">Yukon</option></select>';
					label_text = '<span>Province/State: <span class="required" aria-required="true">*</span></span>';
					zip_text = '<span>Zip Code: <span class="required">*</span></span>';
					break;
				case 'GB':
					states = '<input type="text" class="form-control required" name="us_state" id="us_state" aria-required="true" placeholder="Your Region">';
					label_text = '<span>Region: <span class="required" aria-required="true">*</span></span>';
					zip_text = '<span>Zip/Postal Code: <span class="required">*</span></span>';
					break;
				default:
					states = '<input type="text" class="form-control required" name="us_state" id="us_state" aria-required="true" placeholder="Your Providence">';
					label_text = '<span>Providence: <span class="required" aria-required="true">*</span></span>';
					zip_text = '<span>Zip Code: <span class="required">*</span></span>';
					break;
			} 
		  $('#statebox').html(states);
		  $('.state_label').html(label_text);
		  $('.zip_label').html(zip_text);
		});

if (plan_membership_slug == 'free') {
	$('.terms_and_conditions').load("/glc/termsconditions-free.php");
}
else {
	$('.terms_and_conditions').load("/glc/termsconditions-non-free.php");
}


	accept_sms_check();
	$('#country').change(function() {
		accept_sms_check();

		display_payment_selector(); 
	});

	// input masks for jquery.inputmask
	$('#cc_number').inputmask("9999 9999 9999 999[9]");
	$('#cc_ccv').inputmask({
		'mask': "999[9]",
		'greedy': false
	});
	
	$("#zip").inputmask({
		'mask': "****[***********]",
		'greedy': false
	});
	
$('#phone').attr('disabled', 'disabled');
$('#country').on('change', function() {
	$('#phone').focus();
	$('#phone').removeAttr('disabled', 'disabled');
	if( $('#country').val() == 'US' ) {
		$("#phone").inputmask({
			'mask': "(999)-999-9999",
			'greedy': false
		});	
	}
	else {
		$("#phone").inputmask({
			'mask': "(999[9])-999-9999[99999]",
			'greedy': false
		});		
	}
});

	
	
	

	// custom validator functions
	$.validator.addMethod("numericRegex", function(value, element) {
    	return this.optional(element) || /\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/i.test(value);
	}, "Valid phone number must contain the follow '( ) -' numbers only.");

	$.validator.addMethod("usernameRegex", function(value, element) {
    	return this.optional(element) || /^[a-zA-Z0-9_]+((\.(-\.)*-?|-(\.-)*\.?)[a-zA-Z0-9_]+)*$/i.test(value);
	}, "Please use only letters (a-z), numbers, underscores and periods. No spaces allowed.");


	$('.referral-pop').click(function(event) {

		event.preventDefault();
	
		$(this).modal({
			fadeDuration: 100,
			closeClass: 'icon-remove',
			closeText: '<i class="fa fa-times-circle"></i>'
		});
		return false;
	});

	$('input[type="checkbox"]#change-referrer').change(function() {
		if ( this.checked ) {
			$('.form-update-referrer-wrapper').attr('style', 'display:block !important');
		}	
		else{
			$('.form-update-referrer-wrapper').attr('style', 'display:none !important');
		}
	});

	$('#update_referrer').click(function(event) {

		event.preventDefault();
		console.log($('.new_affiliate').val());
		if( $('.new_affiliate').val() != " ") {
			var nref = $('#new_affiliate').val();
			// similar behavior as an HTTP redirect
			window.location.replace("/glc/registration.php?t="+plan_membership_slug+"&nref="+ nref);

			// similar behavior as clicking on a link
			window.location.href("/glc/registration.php?t="+plan_membership_slug+"&nref="+ nref);
		} ;

		
		return false;
	});

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


	$('.where-ccv-pop').click(function(event) {

		event.preventDefault();
	
		$(this).modal({
			fadeDuration: 100,
			closeClass: 'icon-remove',
			closeText: '<i class="fa fa-times-circle"></i>'
		});
		return false;
	});



	$('#register-form input[name="payment_method_radio_group"]').on('change', function() {
		// alert( $('input[name="payment_method_radio_group"]:checked', '#register-form').val() );
		var pmethod = $('input[name="payment_method_radio_group"]:checked', '#register-form').val();

		$('.payment_selector_form').show();
		$('.step_3').show();
		$('.finish').show();

		if ( $('input[name="payment_method_radio_group"]:checked').hasClass('echeck') ) {

			// enable company account name field
			$('#company_account_name').removeAttr('disabled', 'disabled');
			// $('#company_account_name').prop('disabled', 'false');
			$('.company_account_name_wrapper_div').show();
			


			$('.label_creditcard').attr('style', 'opacity:.3');
			$('.label_echeck').attr('style', 'opacity:1; background: white; padding: 20px; border-radius: 10px;border: 2px solid #337ab7;');

			$('#payment_f_name').attr('placeholder', 'Account holder first name');
			$('#payment_l_name').attr('placeholder', 'Account holder last name');

			$('label_echeck').css('.payment_method_label:hover');

			$('#cc_payment_form').hide();
			$('#echeck_payment_form').show();

			$('#hidden_payment_type').attr('value', 'echeck');
			$('#pay_method').attr('value', 'echeck');
			$('#reg_by').attr('value', 'echeck');

			

		} 
		else if ( $('input[name="payment_method_radio_group"]:checked').hasClass('creditcard') ) {
			// disable company account name field
			$('.company_account_name_wrapper_div').hide();
			$('#company_account_name').attr('disabled', 'disabled');
			$('#company_account_name').prop('disabled', 'true');

			if (isUs == 0) {
				$('.label_echeck').attr('style', 'display:none');
			}
			else{
				$('.label_echeck').attr('style', 'opacity:.3');
			}

			$('.label_creditcard').attr('style', 'opacity:1; background: white; padding: 20px; border-radius: 10px;border: 2px solid #337ab7;');

			$('#payment_f_name').attr('placeholder', 'First name on card');
			$('#payment_l_name').attr('placeholder', 'Last name on card');

			$('#echeck_payment_form').hide();	
			$('#cc_payment_form').show();


			$('#hidden_payment_type').attr('value', 'creditcard');
			$('#pay_method').attr('value', 'creditcard');
			$('#reg_by').attr('value', 'creditcard');

			
			
		}
	});

	/*** testing purposes only ***/
	
	// $('#email').val('sample@email.com');
	// $('#username').val('jsmith');
	// $('#password').val('1234');
	// $('input[name="re_password"]').val('1234');
	// $('#country').val('US');
	// $('#f_name').val('Johnny');
	// $('#l_name').val('Bravo');
	// $('#city').val('Davao');
	// $('#us_state').val('AL');
	// $('#company').val('AEP Corporations');
	// $('#phone').val('(582)-299-4872');
	// $('#address_1').val('487 Jeremiah Street');
	// $('#address_2').val('Alpha Homes, Matina');
	// $('#zip').val('80000');
	// $('#acceptTerms-2').prop('checked', true);
	// $('#acceptTerms-1').prop('checked', true); 
	// // $('#cc_number').val('370000000000002');
	// $('#cc_number').val('4111111111111111');
	// $('#expireMM').val('10');
	// $('#expireYY').val('2020');
	// $('#cc_ccv').val('456');
	// $('#country').val('US');
	// $('#us_state').val('AL');
	
});



function accept_sms_check() {
	if ($('#country').val() != "US") {
		$('.opt-in').hide();
		$('label[for="accept_sms"]').hide();
		
	} else {
		$('.opt-in').show();
		$('label[for="accept_sms"]').show();
	}
}

function display_payment_selector() {
	if($('#country').val() != "US") {

		isUs = 0;

		$('.step_2').slideDown('slow');
		// hide and disable echeck related fields if any
		$('.label_echeck').hide();
		// $('#echeck_payment_form').hide();
		$('#echeck_payment_form > .inp > .form-group input').prop('disabled', 'true');
		$('#echeck_payment_form > .inp > .form-group input').attr('disabled', 'disabled');
		$('#echeck_payment_form > .inp > .form-group input').hide();
		// $('.payment_selector_form').hide();
		


		if( $('.creditcard_counter').hasClass('col-sm-6') ) { 
			$('.creditcard_counter').removeClass('col-sm-6');
			$('.creditcard_counter').addClass('col-sm-6 col-sm-offset-3');
		}

		$('.creditcard_radiobtn').click();
	}
	else {
		isUs = 1; 

		$('.step_2').slideDown('slow');
		// hide and disable echeck related fields if any
		$('.label_echeck').show();
		// $('#echeck_payment_form').hide();
		$('#echeck_payment_form > .inp > .form-group input').prop('disabled', 'false');
		$('#echeck_payment_form > .inp > .form-group input').removeAttr('disabled', 'disabled');
		$('#echeck_payment_form > .inp > .form-group input').show();

		if (count_method == 2) {
			if( $('.creditcard_counter').hasClass('col-sm-offset-3') ) {
				$('.creditcard_counter').removeClass('col-sm-6 col-sm-offset-3');
				$('.creditcard_counter').addClass('col-sm-6');
			}

			
		} else if (count_method == 1) {

		}


		$('.echeck_radiobtn').click();
	}
}