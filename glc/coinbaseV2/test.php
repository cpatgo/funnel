<?php 
  $API_BASE = 'https://api.sandbox.coinbase.com';
  $API_KEY = 'Z0AOBsU3T9pxxbqO';
  $API_SECRET = 'hyWv6sunNLyan5nABVs5lVmYfEmGcW16';
  $ACCOUNT_ID = '55f6f436cf9df22272000086';
  $ENDPOINT = '/v2/accounts/' . $ACCOUNT_ID . '/addresses';

  function make_request($base_url, $request_path, $key, $secret, $body=null)
  {
    $API_VERSION = '2015-08-31';
    $timestamp = time();
    $curl     = curl_init();
    $curlOpts = array();

    if (is_null($body))
    {
      $method = 'GET';
      $curlOpts[CURLOPT_HTTPGET] = 1;
      $queryString=null;
    } 
    else                     
    {
      $method = 'POST';
      $queryString = json_encode($body);
      $curlOpts[CURLOPT_POSTFIELDS] = $queryString;
      $curlOpts[CURLOPT_POST]       = 1;
    }

    $headers = array(
        'Content-Type: application/json',
        'CB-VERSION: ' . $API_VERSION,
        'CB-ACCESS-KEY: ' . $key,
        'CB-ACCESS-SIGN: ' . hash_hmac("sha256", $timestamp.$method.$request_path.$queryString, $secret),
        'CB-ACCESS-TIMESTAMP: ' . $timestamp
    );

    $curlOpts[CURLOPT_URL]            = $base_url . $request_path;
    $curlOpts[CURLOPT_HTTPHEADER]     = $headers;
    $curlOpts[CURLOPT_RETURNTRANSFER] = true;

    curl_setopt_array($curl, $curlOpts);
    return $response = curl_exec($curl);
  }

  $username = "a_username";
  $post_request = array(
      "name" => $username,
      "callback_url" => "https://your.callback.url"
  );

  $response = json_decode(make_request(
    $API_BASE, $ENDPOINT, $API_KEY, $API_SECRET, $post_request)
  );
  var_dump($response);
?>