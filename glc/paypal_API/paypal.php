<?php
ini_set("display_errors","off");


// form var


$form ="q400";
$pass = $password;
$Cost = $setting_registration_fees

?>

<form action="https://www.paypal.com/cgi-bin/webscr" name="paypal" method="post">
<!--<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" name="paypal" method="post">-->
	<input type="hidden" name="cmd" value="_ext-enter">
	<input type="hidden" name="redirect_cmd" value="_xclick">
	<input type="hidden" name="business" value=<?php echo $business;?> />
	<input type="hidden" name="notify_url" value="<?=$notify_url;?>" />
	<input type="hidden" name="item_name" value="Registration Fee" />
	<input type="hidden" name="item_number" value="<?php echo $pass; ?>" />
	<input type="hidden" name="amount" value="<?php echo $Cost; ?>" />
	<input type="hidden" name="no_shipping" value="1" />
	<input type="hidden" name="no_note" value="1" />
	<input type="hidden" name="quantity" value="1" />
	<input type="hidden" name="currency_code" value="USD" />
	<input type="hidden" name="return" value=<?php echo $return_page?> />
	<input type="hidden" name="custom" value="<?php echo $email; ?>" />
	<input type="hidden" name="cancel_return" value="<?=$cancel_url;?>" />
	<input type="hidden" name="email" value="<?php echo $email; ?>" />
	<input type="hidden" name="username" value="<?php echo $username; ?>" />
	<input type="hidden" name="name" value="<?php echo $user_name; ?>" />
	<input type="hidden" name="sponser_id" value="<?php echo $real_parent; ?>" />
	<input type="hidden" name="address1" value="<?php echo $address; ?>" />
	<input type="hidden" name="city" value="<?php echo $city; ?>" />
	<input type="hidden" name="phone" value="<?php echo $phone; ?>" />
	<input type="hidden" name="lc" value="US" />
	<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypalobjects.com/en_AU/i/scr/pixel.gif" width="1" height="1">											
</form>
<?php
	echo "
		<script language=\"javascript\" type=\"text/javascript\">
			setTimeout('document.paypal.submit()', 10);
		</script>";
?>
<!--Multi product form
<form action="https://www.paypal.com/cgi-bin/webscr" name="paypal" method="post">
	<input type="hidden" name="cmd" value="_cart">
<input type="hidden" name="business" value="myemail">
<input type="hidden" name="upload" value="">
	<input type="hidden" name="business" value=<?php echo $business;?> />
	<input type="hidden" name="notify_url" value="<?=$notify_url;?>" />
	<input type="hidden" name="item_name_1" value="<?php echo strtoupper($form); ?> Wholesale" />
	
	<input type="hidden" name="amount_1" value="<?php echo $q400Cost; ?>" />
	
	<input type="hidden" name="item_name_2" value="<?php echo "rose"; ?> Wholesale" />
	
	<input type="hidden" name="amount_2" value="<?php echo $q400Cost; ?>" />
	
	<input type="hidden" name="no_shipping" value="2" />
	<input type="hidden" name="no_note" value="2" />
	<input type="hidden" name="currency_code" value="USD" />
	<input type="hidden" name="return" value=<?php echo $return_page?> />
	<input type="hidden" name="custom" value="<?php echo $email; ?>" />
	<input type="hidden" name="cancel_return" value="<?=$cancel_url;?>" />
	<input type="hidden" name="email" value="<?php echo $email; ?>" />
	<input type="hidden" name="first_name" value="<?php echo $_POST['First_Name']; ?>" />
	<input type="hidden" name="last_name" value="<?php echo $_POST['Last_Name']; ?>" />
	<input type="hidden" name="address1" value="<?php echo $street; ?>" />
	<input type="hidden" name="city" value="<?php echo $city; ?>" />
	<input type="hidden" name="state" value="<?php echo $state; ?>" />
	<input type="hidden" name="zip" value="<?php echo $zip; ?>" />
	<input type="hidden" name="lc" value="US" />
	<input type="image" src="https://www.paypalobjects.com/en_AU/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypalobjects.com/en_AU/i/scr/pixel.gif" width="1" height="1">											
</form>
-->