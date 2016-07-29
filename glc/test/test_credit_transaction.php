<?php 
if(isset($_POST) && !empty($_POST)):
	$payment_f_name = $_POST['payment_f_name'];
	$payment_l_name = $_POST['payment_l_name'];
	$company = $_POST['company'];
	$address1 = $_POST['address1'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zip = $_POST['zip'];
	$country = $_POST['country'];
	$membership = $_POST['membership'];
	$username = $_POST['username'];

	require_once(dirname(__FILE__)."/class/process.php");

	echo "<pre>";
	print_r($response);
endif;
?>
<form method="post">
	Membership<br>
	<input type="text" name="membership" value="Executive" /><br><br>

	Credit Card<br>
	<input type="text" name="cc_number" value="4111111111111111" /><br><br>

	CCV<br>
	<input type="text" name="cc_ccv" value="123" /><br><br>

	Pay Type<br>
	<input type="text" name="pay_method" value="creditcard" /><br><br>

	Expeire Month<br>
	<input type="text" name="expireMM" value="08" /><br><br>

	Expeire Year<br>
	<input type="text" name="expireYY" value="2019" /><br><br>

	Username<br>
	<input type="text" name="username" value="joinnow" /><br><br>

	First Name<br>
	<input type="text" name="payment_f_name" value="Sarah" /><br><br>

	Last Name<br>
	<input type="text" name="payment_l_name" value="Gregorio" /><br><br>

	Company<br>
	<input type="text" name="company" value="Kats" /><br><br>

	Address 1<br>
	<input type="text" name="address1" value="Florida" /><br><br>

	City<br>
	<input type="text" name="city" value="FL" /><br><br>

	State<br>
	<input type="text" name="state" value="FL" /><br><br>

	Zip<br>
	<input type="text" name="zip" value="1234" /><br><br>

	Country<br>
	<input type="text" name="country" value="US" /><br><br>

	<button>Submit</button>
</form>
