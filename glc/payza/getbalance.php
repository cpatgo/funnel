<?php

// PHP provided class by payza
include_once('GetBalanceAPIClient.php');

$do = new GetBalanceAPIClient('seller_1_pokiyoki@aol.com','xn28vVXhAfDMDX5G');

$do->setServer("sandbox.Payza.com");
$do->setUrl("/api/api.svc/GetBalance");
$do->BuildPostVariables("USD");

$output = $do->send();
$do->parseResponse($output);
$result = $do->getResponse();

// Output variables
$result['RETURNCODE'];
$result['DESCRIPTION'];
$result['AVAILABLEBALANCE_1'];
$result['CURRENCY_1'];

// you can always check array with print_r
echo '';
print_r($result);

?>