<?php
include('./header.php');
?>

<div class="c1_MainBox c1_SamplesMainbox">
	<div class="c1_MainBoxContainer">
		<h1>Sample sale / lead tracking</h1>
		<p>this page simulates your order confirmation or "thank you for order" page. It contains hidden sale tracking code that notifies the affiliate system about the sale. </p>
		<a class="c1_sButton" href="./">Back to Samples & tests home</a>
	</div>
</div>
<div class="c1_Wrapper">
	<div class="c1_WrapperContainer">

<h3>Sales / leads tracking explained</h3>
<p>
To track leads and sales, you have to use sale tracking code.
The exact integration depends on your shopping cart or payment gateway, so refer to our documentation for this.
</p>

<div class="c1_WideSampleBox">
	<h2>General tracking method</h2>
	<p>General tracking method uses javascript that you should put to your order confirmation page.</p>
The general tracking code is:
	<pre>
&lt;script id="pap_x2s6df8d" src="<?php echo $urlPart?>/scripts/trackjs.js" type="text/javascript"&gt;
&lt;/script&gt;
&lt;script type="text/javascript"&gt;
PostAffTracker.setAccountId('default1'); //use this line for PAN account, set here your account Id instead of default1
var sale = PostAffTracker.createSale();
sale.setTotalCost('120.50');
sale.setOrderID('ORD_12345XYZ');
sale.setProductID('test product');
PostAffTracker.register();
&lt;/script&gt;
	</pre>
</div>


<div class="c1_WideSampleBox">
	<h2>Hidden image example</h2>
	<p>
If you don't want to use JavaScript tracking code, you can use also hidden image (hidden pixel tracking) version.<br/>
Note that by using hidden the system cannot use functionality of Flash cookies, it will depend only on standard cookies and IP address.</p>
The hidden image variant of the tracking code above is:
	<pre>
&lt;img src="<?php echo $urlPart?>/scripts/sale.php?TotalCost=120.50&OrderID=ORD_12345XYZ&ProductID=test+product" width="1" height="1""&gt;
	</pre>
	Variables you can use in hidden image are:<br/>
	TotalCost, OrderID, ProductID, data1, data2, data3, data4, data5, AffiliateID, CampaignID, ChannelID, Commission, PStatus and Currency
</div>

<script id="pap_x2s6df8d" src="../scripts/trackjs.php" type="text/javascript">
</script>
<script type="text/javascript">
PostAffTracker.setAccountId('default1'); //use this line for PAN account, set here your account Id instead of default1
var sale = PostAffTracker.createSale();
sale.setTotalCost('150.50');
sale.setOrderID('ORD_123');
sale.setFixedCost('$44');
sale.setProductID('test product');
PostAffTracker.register();
</script>
			  
</div></div>
<?php
include('./footer.php');
?>
