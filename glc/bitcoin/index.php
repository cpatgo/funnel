<?php
require_once("lib/Coinbase.php");
$data = array();

/*--sandbox--*/
$coinbaseAPIKey = 'Z0AOBsU3T9pxxbqO';
$coinbaseAPISecret = 'hyWv6sunNLyan5nABVs5lVmYfEmGcW16';
//$results = '';

$coinbase = Coinbase::withApiKey($coinbaseAPIKey, $coinbaseAPISecret);
echo $coinbase->getBalance() . " BTC";
?>