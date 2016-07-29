<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>VG Coin Shop</title>
<style type="text/css">
body{font-family: arial;color: #7A7A7A;margin:0px;padding:0px;}
.procut_item {width: 550px;margin-right: auto;margin-left: auto;padding: 20px;background: #F1F1F1;margin-bottom: 1px;font-size: 12px;border-radius: 5px;text-shadow: 1px 1px 1px #FCFCFC;}
.procut_item h4 {margin: 0px;padding: 0px;font-size: 20px;}
</style>
<h2 align="center">VG Coins Purchases</h2>
<div class="product_wrapper">
<table class="procut_item" border="0" cellpadding="4">
        <tr>
            <td width="70%"><h4>5 VG Coins</h4>Currency to enter into Victorious Gaming Paid Tournaments</td>
            <td width="30%">
                <form method="post" action="order_process.php">
                    <input type="hidden" name="item_name" value="5 VG Coins" /> 
                    <input type="hidden" name="item_code" value="1" /> 
                    <input type="hidden" name="item_desc" value="5 VG Coins" />
                    <input type="hidden" name="qty" value="5" />
                    <input class="dw_button" type="submit" name="submitbutt" value="Buy (5.00 <?php echo $PayPalCurrencyCode; ?>)" />
                </form>
            </td>
        </tr>
</table>
<table class="procut_item" border="0" cellpadding="4">
  <tr>
    <td width="70%"><h4>10 VG Coins</h4>Currency to enter into Victorious Gaming Paid Tournaments</td>
    <td width="30%">
    <form method="post" action="order_process.php">
	<input type="hidden" name="item_name" value="10 VG Coins" /> 
	<input type="hidden" name="item_code" value="1" /> 
    <input type="hidden" name="item_desc" value="10 VG Coins" />
	<input type="hidden" name="qty" value="10" />
    <input class="dw_button" type="submit" name="submitbutt" value="Buy (10.00 <?php echo $PayPalCurrencyCode; ?>)" />
    </form>
    </td>
  </tr>
</table>
<table class="procut_item" border="0" cellpadding="4">
  <tr>
    <td width="70%"><h4>20 VG Coins</h4>Currency to enter into Victorious Gaming Paid Tournaments</td>
    <td width="30%">
    <form method="post" action="order_process.php">
	<input type="hidden" name="item_name" value="20 VG Coins" /> 
	<input type="hidden" name="item_code" value="1" /> 
    <input type="hidden" name="item_desc" value="20 VG Coins" /> 
	<input type="hidden" name="qty" value="20" />
    <input class="dw_button" type="submit" name="submitbutt" value="Buy (20.00 <?php echo $PayPalCurrencyCode; ?>)" />
    </form>
    </td>
  </tr>
</table>
<table class="procut_item" border="0" cellpadding="4">
  <tr>
    <td width="70%"><h4>30 VG Coins</h4>Currency to enter into Victorious Gaming Paid Tournaments</td>
    <td width="30%">
    <form method="post" action="order_process.php">
	<input type="hidden" name="item_name" value="30 VG Coins" /> 
	<input type="hidden" name="item_code" value="1" /> 
    <input type="hidden" name="item_desc" value="30 VG Coins" /> 
	<input type="hidden" name="qty" value="30" />
    <input class="dw_button" type="submit" name="submitbutt" value="Buy (30.00 <?php echo $PayPalCurrencyCode; ?>)" />
    </form>
    </td>
  </tr>
</table>
</div>
</body>
</html>

