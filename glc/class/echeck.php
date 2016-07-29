<?php
define("APPROVED", 1);
define("DECLINED", 2);
define("ERROR", 3);

class Class_Echeck {
// Initial Setting Functions
  function setLogin($username, $password) {
    $this->login['username'] = $username;
    $this->login['password'] = $password;
  }

  function setCustomer($firstname,
        $lastname,
        $address1,
        $address2,
        $city,
        $state,
        $zip,
        $phone,
        $email) {
    $this->customer['customername']      = sprintf("%s %s", $firstname, $lastname);
    $this->customer['customeraddress1']  = $address1;
    $this->customer['customeraddress2']  = $address2;
    $this->customer['customercity']      = $city;
    $this->customer['customerstate']     = $state;
    $this->customer['customerzip']       = $zip;
    $this->customer['customerphone']     = $phone;
    $this->customer['customeremail']     = $email;
  }

  function setOrder($customerid,
        $product,
        $amount) {
    $this->order['customerid']  = $customerid;
    $this->order['product']     = $product; 
    $this->order['amount']      = $amount;
  }

  // Transaction Functions

  function doSale($checknum, $routingnum, $accountnum) {

    $data = array();
    // Login Information
    $data['formcapture']        = 0;
    $data['xpschk_usr']         = $this->login['username'];
    $data['xpschk_pass']        = $this->login['password'];

    // Customer Information
    $data['customername']       = $this->customer['customername'];
    $data['customeraddress1']   = $this->customer['customeraddress1'];
    $data['customeraddress2']   = $this->customer['customeraddress2'];
    $data['customercity']       = $this->customer['customercity'];
    $data['customerstate']      = $this->customer['customerstate'];
    $data['customerzip']        = $this->customer['customerzip'];
    $data['customerphone']      = $this->customer['customerphone'];
    $data['customeremail']      = $this->customer['customeremail'];

    // Order Information
    $data['customerid']         = $this->order['customerid'];
    $data['product']            = $this->order['product'];
    $data['amount']             = $this->order['amount'];

    // Echeck Information
    $data['checknum']           = $checknum;
    $data['routingnum']         = $routingnum;
    $data['accountnum']         = $accountnum;
    return $this->_doPost(http_build_query($data)); 
  }

  function _doPost($query) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://xpresschecknetwork.net/web/xpresscheck.php");
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
    curl_setopt($ch, CURLOPT_POST, 1);

    if (!($data = curl_exec($ch))) {
        return curl_error($ch);
    }
    curl_close($ch);
    unset($ch);
    return json_decode($data);
  }
}