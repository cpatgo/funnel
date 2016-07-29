<?php
require_once(dirname(__FILE__) . '/src/Client.php');
require_once(dirname(__FILE__) . '/src/Configuration.php');

use Coinbase\Wallet\Client;
use Coinbase\Wallet\Configuration;

define("API_BASE", "https://api.sandbox.coinbase.com/v2/");
$apiKey = 'BgpUvkgK0suj9apk';
$apiSecret = 'W5lYiC5EWkXLAlqY4FJLec7Ma0ydRfZj';

$configuration = Configuration::apiKey($apiKey, $apiSecret);
$configuration->setApiUrl(Configuration::SANDBOX_API_URL);
$client = Client::create($configuration);
$account = $client->getPrimaryAccount();
echo 'Account name: ' . $account->getName() . '<br>';
echo 'Account currency: ' . $account->getCurrency() . '<br>';
?>

/*
		$button = $coinbase->createButton(
			"Your Donation to S.R.R.O.", 
			$_POST['amount'], 
			"BTC", 
			"", 
			array(
	        	"description" => "My " . $_POST['amount'] . " BTC donation to S.S.R.O.",
	        	"cancel_url" => "http://localhost/coinbase/cancel.php",
	        	"success_url" => "http://localhost/coinbase/thanks.php"
	    	)
	    );