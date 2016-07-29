<?php
	require_once("../bitcoin/coinbase-php/lib/Coinbase.php");
	//require('vendor/autoload.php');

	$coinbaseAPIKey = 'BgpUvkgK0suj9apk';
	$coinbaseAPISecret = 'W5lYiC5EWkXLAlqY4FJLec7Ma0ydRfZj';

	$coinbase = Coinbase::withApiKey($coinbaseAPIKey, $coinbaseAPISecret);

	if(!isset($_POST['amount']))
		header('Location: index.php');

	if (!preg_match('/^[0-9]+(?:\.[0-9]+)?$/', $_POST['amount']))
		header('Location: index.php');

	$siteurl = sprintf('%s://%s', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http', $_SERVER['HTTP_HOST']);

	$button = $coinbase->createButton(
		"Your Donation to S.R.R.O.", 
		$_POST['amount'], 
		"BTC", 
		"", 
		array(
        	"description" => "My " . $_POST['amount'] . " BTC donation to S.S.R.O.",
        	"cancel_url" => sprintf('%s/glc/bb/cancel.php', $siteurl),
        	"success_url" => sprintf('%s/glc/bb/thanks.php', $siteurl)
    	)
    );