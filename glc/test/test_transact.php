<!DOCTYPE html>
<html>
	<head>
		<title>Transact Query</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" media="screen">
	</head>
	<body>
		<div class="container">
			<div class="row">
				<form class="form-horizontal transact_form" id="transact_form" name="transact_form" method="post">
					<div class="col-md-6">
						<fieldset>
							<legend>Billing Information</legend>

							<div class="control-group">
								<label class="control-label" for="first_name">First Name</label>
								<div class="controls">
									<input type="text" id="first_name" class="first_name" name="first_name" placeholder="First Name" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="last_name">Last Name</label>
								<div class="controls">
									<input type="text" id="last_name" class="last_name" name="last_name" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="company">Company</label>
								<div class="controls">
									<input type="text" id="company" class="company" name="company" />	
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="address1">Address 1: </label>
								<div class="controls">
									<input type="text" id="address1" class="address1" name="address1" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="address2">Address 2</label>
								<div class="controls">
									<input type="text" id="address2" class="address2" name="address2" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="city">City</label>
								<div class="controls">
									<input type="text" id="city" class="city" name="city" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="state">State</label>
								<div class="controls">
									<input type="text" id="state" class="state" name="state" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="zip">Zip</label>
								<div class="controls">
									<input type="text" id="zip" class="zip" name="zip" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="country">Country</label>
								<div class="controls">
									<input type="text" id="country" class="country" name="country" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="phone">Phone</label>
								<div class="controls">
									<input type="tel" id="phone" class="phone" name="phone" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="fax">Fax</label>
								<div class="controls">
									<input type="text" id="fax" class="fax" name="fax" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="email">Email</label>
								<div class="controls">
									<input type="email" id="email" class="email" name="email" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="website">Website</label>
								<div class="controls">
									<input type="url" id="website" class="website" name="website" />
								</div>
							</div>				
						</fieldset>
					</div>
					
					<div class="col-md-6">
						<fieldset>
							<legend>Shipping Information</legend>

							<div class="control-group">
								<label class="control-label" for="shipping_first_name">First Name</label>
								<div class="controls">
									<input type="text" id="shipping_first_name" class="shipping_first_name" name="shipping_first_name" placeholder="First Name" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="shipping_last_name">Last Name</label>
								<div class="controls">
									<input type="text" id="shipping_last_name" class="shipping_last_name" name="shipping_last_name" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="shipping_company">Company</label>
								<div class="controls">
									<input type="text" id="shipping_company" class="shipping_company" name="shipping_company" />	
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="shipping_address1">Address 1: </label>
								<div class="controls">
									<input type="text" id="shipping_address1" class="shipping_address1" name="shipping_address1" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="shipping_address2">Address 2</label>
								<div class="controls">
									<input type="text" id="shipping_address2" class="shipping_address2" name="shipping_address2" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="shipping_city">City</label>
								<div class="controls">
									<input type="text" id="shipping_city" class="shipping_city" name="shipping_city" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="shipping_state">State</label>
								<div class="controls">
									<input type="text" id="shipping_state" class="shipping_state" name="shipping_state" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="shipping_zip">Zip</label>
								<div class="controls">
									<input type="text" id="shipping_zip" class="shipping_zip" name="shipping_zip" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="shipping_country">Country</label>
								<div class="controls">
									<input type="text" id="shipping_country" class="shipping_country" name="shipping_country" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" shipping_for="phone">Phone</label>
								<div class="controls">
									<input type="tel" id="shipping_phone" class="shipping_phone" name="shipping_phone" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="shipping_fax">Fax</label>
								<div class="controls">
									<input type="text" id="shipping_fax" class="shipping_fax" name="shipping_fax" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="shipping_email">Email</label>
								<div class="controls">
									<input type="email" id="shipping_email" class="shipping_email" name="shipping_email" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="shipping_website">Website</label>
								<div class="controls">
									<input type="url" id="shipping_website" class="shipping_website" shipping_name="website" />
								</div>
							</div>				
						</fieldset>						
					</div>
					<hr />
					
					<div class="row">
						<div class="controls">
							<input type="submit" value="process" class="btn btn-primary" />
						</div>
					</div>
				</form>
			</div>
			
		</div>
		<script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<script>
			$(document).ready(function() {
				
				$('#transact_form').submit(function(event) {
					
					alert('test');
					event.preventDefault();
					

					$.post('process.php', function(data) {
						
							window.location.replace('http://google.com/');		
						
					});
						
					

					// $.ajax({
					// 	url: '/glc/process.php',
					// 	type: 'POST',
					// 	data: $this->serialize(),
					// 	success: function(data) {
					// 		console.log(data);
					// 		},
					// 	error: function( xhr, desc, err) {
					// 		console.log('error');
					// 	}
					// });
				});

				

			});

		</script>
	</body>
</html>
