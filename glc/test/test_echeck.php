<?php 
require_once(dirname(dirname(__FILE__))."/config.php");
if(isset($_POST) && !empty($_POST)):
    $payment_fname = $_POST['customernamefirst'];
    $payment_lname = $_POST['customernamelast'];
    $address1 = $_POST['customeraddress1'];
    $city = $_POST['customercity'];
    $state = $_POST['customerstate'];
    $zip = $_POST['customerzip'];
    $membership = $_POST['membership'];
    $username = $_POST['username'];
    $phone = $_POST['customerphone'];
    $email = $_POST['customeremail'];
    $user_id = $_POST['customerid'];

    require_once(dirname(dirname(__FILE__))."/echeck/process.php");

    echo "<pre>";
    print_r($payment_response);
endif;
?>
<form method="post">
    Membership<br>
    <input type="text" name="membership" value="Executive" /><br><br>

    Username<br>
    <input type="text" name="username" value="joinnow" /><br><br>

    Test merchant<br>
    <input type="text" name="xpschk_usr" value="TestMerchant" /><br><br>

    Password<br>
    <input type="text" name="xpschk_pass" value="a4441febf272df8bbce24bb816c7775db22dec06" /><br><br>

    Customer First Name<br>
    <input type="text" name="customernamefirst" value="Sarah" /><br><br>

    Customer Last Name<br>
    <input type="text" name="customernamelast" value="Gregorio" /><br><br>

    Customer Address 1<br>
    <input type="text" name="customeraddress1" value="Cerritos 2 Molino 3" /><br><br>

    Customer Address 2<br>
    <input type="text" name="customeraddress2" value="" /><br><br>

    Customer City<br>
    <input type="text" name="customercity" value="Bacoor" /><br><br>

    Customer State<br>
    <input type="text" name="customerstate" value="AL" /><br><br>

    Customer Zip<br>
    <input type="text" name="customerzip" value="33064" /><br><br>

    Customer Phone<br>
    <input type="text" name="customerphone" value="9175900811" /><br><br>

    Customer Email<br>
    <input type="text" name="customeremail" value="sarahgregorio29@gmail.com" /><br><br>

    Customer ID<br>
    <input type="text" name="customerid" value="1517" /><br><br>

    Product<br>
    <input type="text" name="product" value="Executive" /><br><br>

    Amount<br>
    <input type="text" name="amount" value="99" /><br><br>

    Check Number<br>
    <input type="text" name="checknum" value="1234" /><br><br>

    Routing Number<br>
    <input type="text" name="routingnum" value="000000000" /><br><br>

    Account Number<br>
    <input type="text" name="accountnum" value="1111" /><br><br>

    <button>Submit</button>
</form>
