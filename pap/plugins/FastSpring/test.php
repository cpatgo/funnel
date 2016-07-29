<html>
<body>
GENERAL ORDER (per item):<br>
<form action="fastspring.php?PDebug=Y" method=post>
User (cookie): <input id="pap_dx8vc2s5" type="text" name="OrderReferrer" value=""><br/>
Amount: <input type="text" name="OrderItemTotalUSD" value="10.5"><br/>
Discount: <input type="text" name="OrderItemDiscountUSD" value="0"><br/>
Transaction ID: <input type="text" name="OrderID" value="FastSpring"><br/>
Subscrption Reference: <input type="text" name="SubscriptionReference" value="FastSpring"><br/> (is always the same for initial transaction and recurring payments as well)<br><br>

Product <input type="text" name="OrderItemProductName"><br>
Customer email: <input type="text" name="CustomerEmail" value="user@name.com"><br/>

<input type="hidden" name="isRebill" value="false">
<input type="hidden" name="PDebug" value="Y">
<input type="submit" value="Test normal sale">
<script id="pap_x2s6df8d" src="../../scripts/notifysale.php" type="text/javascript">
</script>
</form>

<hr>
SUBSCRIPTION: <br>
<form action="fastspring.php?PDebug=Y" method=post>
User (cookie): <input id="pap_dx8vc2s5" type="text" name="OrderReferrer" value=""><br/>
Amount: <input type="text" name="OrderItemTotalUSD" value="10.5"><br/>
Discount: <input type="text" name="OrderItemDiscountUSD" value="0"><br/>
Transaction ID: <input type="text" name="OrderID" value="FastSpring"><br>
Subscription Reference: <input type="text" name="SubscriptionReference" value="FastSpring"><br/>
(is always the same for initial transaction and recurring payments as well)
<br/><br>

Product<input type="text" name="OrderItemProductName"><br>
Customer email: <input type="text" name="CustomerEmail" value="user@name.com"><br/>


<input type="hidden" name="isRebill" value="true">

<input type="hidden" name="PDebug" value="y">
<input type="submit" value="Test recurring payment / subscription">
<script id="pap_x2s6df8d" src="../../scripts/notifysale.php" type="text/javascript">
</script>
</form> 
 
</body>
</html>
