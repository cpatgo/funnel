<?php
// twit(ter).php

function adesk_twit($message, $username = null, $password = null, $crop2 = 140) {
	require_once(awebdesk_functions("json.php"));
	$json = function_exists('json_decode');
	if ( !$json ) {
		require_once awebdesk_pear("Unserializer.php");
		// The twitter API address
		$url = 'http://twitter.com/statuses/update.xml';
	} else {
		// Alternative JSON version
		$url = 'http://twitter.com/statuses/update.json';
	}

	if ( $crop2 ) $message = substr($message, 0, $crop2);

	// Set up and execute the curl process
	if ( !function_exists('curl_init') ) return false;

	$curl_handle = curl_init();
	curl_setopt($curl_handle, CURLOPT_URL, $url);
	curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl_handle, CURLOPT_POST, 1);
	curl_setopt($curl_handle, CURLOPT_POSTFIELDS, "status=$message");
	curl_setopt($curl_handle, CURLOPT_USERPWD, "$username:$password");
	$buffer = curl_exec($curl_handle);
	curl_close($curl_handle);

	// check for success or failure
	if ( empty($buffer) ) {
		return false;
	}

	// return json result
	if ( $json ) return json_decode($buffer);

	// try with pear xml class
	require_once(awebdesk_pear('Unserializer.php'));
	$unserializer = new XML_Unserializer();
	$unserializer->unserialize($buffer);
	$data = $unserializer->getUnserializedData();
	if ( !PEAR::isError($data) ) return $data;

	// return error from xml
	if ( preg_match('~<error>(.*)</error>~', $buffer, $m) ) return array('error' => $m[1]);

	// return result from xml
	if ( preg_match('~<id>(.*)</id>~', $buffer, $m) ) return array('id' => $m[1]);

	// return dummy result
	return array('id' => 0);
}

function adesk_twit_oauth($token, $token_secret, $status) {
	require_once( adesk_admin("functions/list.php") );
	$oauth = list_twitter_oauth_init($token, $token_secret);
	$status_message = $status; // in case we need to try again (we are re-declaring $status below)
	$status = $oauth->post('statuses/update', array('status' => $status));

	if ( isset($status->error) ) {
	  // try new app keys, if they are using the old ones
	  if ($GLOBALS["site"]["twitter_consumer_key"] == "JsjUb8QUaCg0fUDRfxnfcg") $GLOBALS["site"]["twitter_consumer_key"] = "xuezwkFT39aJKr50Z1qM9g";
	  if ($GLOBALS["site"]["twitter_consumer_secret"] == "ufR6occzeroEg4QzDYDZbqL8vMC8bji1a7c8oAYVM") $GLOBALS["site"]["twitter_consumer_secret"] = "RY7Xcn3utS3dlAz5XV2fAWDsBg9vDAZOXqkdIgYM";
    $oauth = list_twitter_oauth_init($token, $token_secret);
    $status = $oauth->post('statuses/update', array('status' => $status_message));
	}

	$err = ( isset($status->error) ? $status->error : '' );
	$id  = ( isset($status->id) ? $status->id : 0 );

	return array( "error" => $err, "id" => $id );
}

function adesk_bitly($url) {
	$bitly = 'http://api.bit.ly/shorten?version=2.0.1&format=xml&login=awebdesk&apiKey=R_71787055df34207acd48c132b292ac42&longUrl=' . urlencode($url);
	$r = adesk_http_get($bitly);
	if ( !$r ) return false;
	preg_match('~<shortUrl>(.*)</shortUrl>~', $r, $matches);
	if ( !isset($matches[1]) ) return false;
	return $matches[1];
}

function adesk_twit_verify_credentials($username, $password) {

	$url = "http://twitter.com/account/verify_credentials.xml";

	if ( !function_exists('curl_init') ) return false;

	$curl_handle = curl_init();
	curl_setopt($curl_handle, CURLOPT_URL, $url);
	curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl_handle, CURLOPT_USERPWD, "$username:$password");
	$buffer = curl_exec($curl_handle);
	curl_close($curl_handle);

	// check for success or failure
	if ( empty($buffer) ) {
		return false;
	}

	require_once(awebdesk_pear('Unserializer.php'));
	$unserializer = new XML_Unserializer();
	$unserializer->unserialize($buffer);
	$data = $unserializer->getUnserializedData();

	if ( !isset($data['error']) ) {
		return true;
	}
	else {
		return false;
	}
}

function adesk_twit_api_search($query) {
	$r = array();
	if ( !function_exists('curl_init') ) {
		$r['message'] = _a('PHP cURL extension required.');
		return $r;
	}
	if ( !function_exists('simplexml_load_string') ) {
		$r['message'] = _a('PHP SimpleXML extension required.');
		return $r;
	}
	// searching for multiple terms
	if ( is_array($query) ) {
		$search = implode(" OR ", $query);
	}
	else {
		$search = $query;
	}
	$url = "http://search.twitter.com/search.atom?q=" . urlencode($search) . "&result_type=recent";
	//dbg($url);
	$request = curl_init($url);
	curl_setopt($request, CURLOPT_HEADER, 0);
	curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($request, CURLOPT_USERAGENT, "AwebDesk Email Marketing software");
	$response = curl_exec($request);
	//dbg($response);
	curl_close($request);

	# Also known as the fail whale; if we get this, something went wrong, like Twitter timing
	# out.
	if (strpos($response, "http://static.twitter.com/images/whale.png") !== false)
		return false;

	# Another potential twitter error message.
	if (strpos($response, "We're sorry, but something went wrong.") !== false)
		return false;

	if (strpos($response, "Error 503 Service Unavailable") !== false)
		return false;

	if (strpos($response, "<!DOCTYPE HTML") !== false && strpos($response, "Access control configuration prevents your request") !== false)
		return false;

	$object = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
	return $object;
}

function adesk_twit_available() {
	$site = adesk_site_get();

	return function_exists('curl_init') && $site["twitter_consumer_key"] != "" && $site["twitter_consumer_secret"] != "";
}

?>
