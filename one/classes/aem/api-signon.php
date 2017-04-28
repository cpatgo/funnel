<?php
$params = array(
    'api_user'     => $creds['user_login'],
    'api_pass'     => $creds['user_password'],
    'api_action'   => 'singlesignon_sameserver',
    'api_output'   => 'serialize',
);
curl_request($params);

function curl_request($params) {
	$url    = sprintf('%s/aem', GLC_URL);
	$query = "";
	foreach( $params as $key => $value ) $query .= $key . '=' . urlencode($value) . '&';
	$query = rtrim($query, '& ');

	$url = rtrim($url, '/ ');

	if ( !function_exists('curl_init') ) die('CURL not supported. (introduced in PHP 4.0.2)');

	if ( $params['api_output'] == 'json' && !function_exists('json_decode') ) {
		die('JSON not supported. (introduced in PHP 5.2.0)');
	}

	$api = $url . '/awebdeskapi.php?' . $query;

	$request = curl_init($api); // initiate curl object
	curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
	curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)

	$response = (string)curl_exec($request); // execute curl post and store results in $response

	curl_close($request); // close curl object

	if ( !$response ) {
		die('Nothing was returned. Do you have a connection to Email Marketing server?');
	}

	$result = unserialize($response);

	$remember = ( isset($remember) ? (bool)$remember : false );
	if ( $result['result_code'] ) {
		$keys = explode('|', $result['prfxs']);
		foreach ( $keys as $k ) {
			$cookie = $k . 'aweb_globalauth_cookie';
	    	if (@setcookie($cookie, $result['hash'], ($remember ? time() + 1296000 : time() + 1296000), "/"))
	        	$_COOKIE[$cookie] = $result['hash'];
		}
	}
}