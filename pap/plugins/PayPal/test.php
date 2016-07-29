<html>
<body>
<p>In order to test 'per product' tracking, use the <b>num_cart_items=X</b>  (X = 1,2,...) <br>
<form action="test.php" method=get>
Items number 'num_cart_items': <input id="num_cart_items" type="text" name="num_cart_items" value="<?php echo @$_GET['num_cart_items']?>">
<input type="submit" value="Set items number">
</form>
and do not forget to uncheck the "<b>Process whole cart as one transaction</b>" option in the configuration of PayPal IPN Handling plugin
</p>


<hr />

<form action="paypal.php?PDebug=Y" method=post>
User (cookie): <input id="pap_dx8vc2s5" type="text" name="custom" value=""><br/>
Amount: <input type="text" name="mc_gross" value="120.50"><br/>
Transaction ID: <input type="text" name=txn_id value="AB_12345"><br/>

Customer first name: <input type="text" name=first_name value="fname1"><br/>
Customer last name: <input type="text" name=last_name value="lname1"><br/>
Customer email: <input type="text" name=payer_email value="user@name.com"><br/>
Customer city: <input type="text" name=address_city value="User City"><br/>
Customer address: <input type="text" name=address_street value="User Address"><br/>
Shipping: <input type="text" name=mc_shipping value=""><br/>
Tax: <input type="text" name=tax value=""><br/>

<?php  
$item_count=@$_GET['num_cart_items'];
if (isset($item_count) && is_numeric($item_count) && $item_count > 0) {
	echo 'num_cart_items <input type="text" name="num_cart_items" value="'.$item_count.'"><br />';
	
	for ($i=1; $i< $item_count+1; $i++){
	echo 'item_number'.$i.'&nbsp;<input type="text" name="item_number'.$i.'" value="test campaign"><br />';			
	}	
}
?>

<input type="hidden" name="payment_status" value="Completed">
<input type="hidden" name="txn_type" value="payment">
<input type="hidden" name="mc_currency" value="EUR">

<input type="hidden" name="PDebug" value="Y">
<input type="submit" value="Test normal sale">
<script id="pap_x2s6df8d" src="../../scripts/notifysale.php" type="text/javascript">
</script>
</form>

<hr>
<form action="paypal.php?PDebug=Y" method=post>
User (cookie): <input id="pap_dx8vc2s5" type="text" name="custom" value=""><br/>
Amount: <input type="text" name="mc_gross" value="120.50"><br/>
Transaction ID: <input type="text" name=subscr_id value="SUB_12345"><br/>

Customer first name: <input type="text" name=first_name value="fname1"><br/>
Customer last name: <input type="text" name=last_name value="lname1"><br/>
Customer email: <input type="text" name=payer_email value="user@name.com"><br/>
Customer city: <input type="text" name=address_city value="User City"><br/>
Customer address: <input type="text" name=address_street value="User Address"><br/>

<input type="hidden" name="item_number" value="2">
<input type="hidden" name="payment_status" value="Completed">
<input type="hidden" name="txn_type" value="subscr_payment">

<input type="hidden" name="PDebug" value="y">
<input type="submit" value="Test recurring payment / subscription">
<script id="pap_x2s6df8d" src="../../scripts/notifysale.php" type="text/javascript">
</script>
</form> 
<hr>
<form action="paypal.php?PDebug=Y" method=post>
User (cookie): <input id="pap_dx8vc2s5" type="text" name="custom" value=""><br/>
Transaction ID: <input type="text" name=txn_id value="AB_12345"><br/>
Parent transaction ID: <input type="text" name=parent_txn_id value="ORD_123"><br/>
<input type="hidden" name="item_number" value="2">
<input type="hidden" name="reason_code" value="refund">
<input type="hidden" name="payment_status" value="Refunded">
<input type="hidden" name="txn_type" value="web_accept">

<input type="hidden" name="PDebug" value="y">
<input type="submit" value="Test refund">
<script id="pap_x2s6df8d" src="../../scripts/notifysale.php" type="text/javascript">
</script>
</form>  

<hr />

<form action="paypal.php?PDebug=Y" method=post>
User (cookie): <input id="pap_dx8vc2s5" type="text" name="custom" value=""><br/>
Amount: <input type="text" name="mc_gross" value="120.50"><br/>
Transaction ID: <input type="text" name=txn_id value="AB_12345"><br/>

Customer first name: <input type="text" name=first_name value="fname1"><br/>
Customer last name: <input type="text" name=last_name value="lname1"><br/>
Customer email: <input type="text" name=payer_email value="user@name.com"><br/>
Customer city: <input type="text" name=address_city value="User City"><br/>
Customer address: <input type="text" name=address_street value="User Address"><br/>
Shipping: <input type="text" name=mc_shipping value=""><br/>
Tax: <input type="text" name=tax value=""><br/>

Data1: <input type="text" name=data1 value="data1_value"><br/>
Data2: <input type="text" name=data2 value="data2_value"><br/>
Data3: <input type="text" name=data3 value="data3_value"><br/>
Data4: <input type="text" name=data4 value="data4_value"><br/>
Data5: <input type="text" name=data5 value="data5_value"><br/>
Coupon: <input type="text" name=coupon_code value="coupon1"><br/>
Channel: <input type="text" name=channelId value="channel_code1"><br/>

<input type="hidden" name="item_number" value="2">
<input type="hidden" name="payment_status" value="Completed">
<input type="hidden" name="txn_type" value="payment">
<input type="hidden" name="mc_currency" value="EUR">

<input type="hidden" name="PDebug" value="Y">
<input type="submit" value="Test sale with additional parameters">
<script id="pap_x2s6df8d" src="../../scripts/notifysale.php" type="text/javascript">
</script>
</form>
 
</body>
</html>
