<?php

$identity = 'jobcpa1@yahoo.com';
$password = '4321#';
$remember = '1';

$loginUrl = 'http://sitebuilder.glchub.com/authlogin';

//init curl
$ch = curl_init();

//Set the URL to work with
curl_setopt($ch, CURLOPT_URL, $loginUrl);

// ENABLE HTTP POST
curl_setopt($ch, CURLOPT_POST, 1);

// curl_setopt($ch, )

//Set the post parameters
curl_setopt($ch, CURLOPT_POSTFIELDS, 'identity=' . $username . '&password=' . $password . '&remember=' . $remember);

//Handle cookies for the login
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');

//Setting CURLOPT_RETURNTRANSFER variable to 1 will force cURL
//not to print out the results of its query.
//Instead, it will return the results as a string return value
//from curl_exec() instead of the usual true/false.
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//execute the request (the login)
$store = curl_exec($ch);

print_r($store);

//the login is now done and you can continue to get the
//protected content.

//set the URL to the protected file
curl_setopt($ch, CURLOPT_URL, 'https://glchub.com/resources/glc-dfysalesfunnelmoney/ebook-sales-funnel-money.pdf');

//execute the request
$content = curl_exec($ch);

print_r($content);

die('exiting');
