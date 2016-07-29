<html>
<body>
<form action="bluepay.php?PDebug=Y" method=post>
User (cookie): <input id="pap_dx8vc2s5" type="text" name="CUSTOM_ID" value=""><br/>
Amount: <input type="text" name="amount" value="120.50"><br/>
Transaction ID: <input type="text" name=trans_id value="AB_12345"><br/>

Customer first name: <input type="text" name=name1 value="firstname1"><br/>
Customer last name: <input type="text" name=name2 value="lastname1"><br/>
Customer email: <input type="text" name=email value="user@name.com"><br/>
Customer city: <input type="text" name=city value="User City"><br/>
Customer address1: <input type="text" name=addr1 value="User Address1"><br/>
Customer address2: <input type="text" name=addr2 value="User Address2"><br/>
trans_status: <input type="text" name=trans_status value="1"><br/>
trans_type: <input type="text" name=trans_type value="SALE"><br/>
(trans_type options: 'AUTH', 'CAPTURE', 'CREDIT', 'REFUND', 'SALE', 'VOID)<br />


<input type="hidden" name="PDebug" value="Y">
<input type="submit" value="Test normal sale">
<script id="pap_x2s6df8d" src="../../scripts/notifysale.php" type="text/javascript">
</script>
</form>

<hr>
<form action="paypal.php?PDebug=Y" method=post>
User (cookie): <input id="pap_dx8vc2s5" type="text" name="CUSTOM_ID" value=""><br/>
Amount: <input type="text" name="amount" value="120.50"><br/>
Transaction ID: <input type="text" name=trans_id value="AB_34567"><br/>
Subscription ID: <input type="text" name=master_id value="AB_12345"><br/>

Customer first name: <input type="text" name=name1 value="firstname1"><br/>
Customer last name: <input type="text" name=name2 value="lastname1"><br/>
Customer email: <input type="text" name=email value="user@name.com"><br/>
Customer city: <input type="text" name=city value="User City"><br/>
Customer address1: <input type="text" name=addr1 value="User Address1"><br/>
Customer address2: <input type="text" name=addr2 value="User Address2"><br/>
trans_status: <input type="text" name=trans_status value="1"><br/>
trans_type: <input type="text" name=trans_type value="SALE"><br/>
(trans_type options: 'AUTH', 'CAPTURE', 'CREDIT', 'REFUND', 'SALE', 'VOID)<br />


<input type="hidden" name="PDebug" value="Y">
<input type="submit" value="Test recurring payment / subscription">
<script id="pap_x2s6df8d" src="../../scripts/notifysale.php" type="text/javascript">
</script>
</form> 
</body>
</html>
