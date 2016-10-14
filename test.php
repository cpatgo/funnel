<?php

function get_web_page( $url,$curl_data )
{
    $options = array(
				CURLOPT_COOKIEJAR			 => "cookie.txt";
        CURLOPT_RETURNTRANSFER => true,         // return web page
        CURLOPT_HEADER         => false,        // don't return headers
        CURLOPT_FOLLOWLOCATION => true,         // follow redirects
        CURLOPT_ENCODING       => "",           // handle all encodings
        CURLOPT_USERAGENT      => "spider",     // who am i
        CURLOPT_AUTOREFERER    => true,         // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,          // timeout on connect
        CURLOPT_TIMEOUT        => 120,          // timeout on response
        // CURLOPT_MAXREDIRS      => 10,           // stop after 10 redirects
        CURLOPT_POST            => 1,            // i am sending post data
      	CURLOPT_POSTFIELDS     => $curl_data,    // this are my post vars
        // CURLOPT_SSL_VERIFYHOST => 0,            // don't verify ssl
        // CURLOPT_SSL_VERIFYPEER => false,        //
        // CURLOPT_VERBOSE        => 1                //
    );

    $ch      = curl_init($url);
    curl_setopt_array($ch,$options);
    $content = curl_exec($ch);
    $err     = curl_errno($ch);
    $errmsg  = curl_error($ch) ;
    $header  = curl_getinfo($ch);
    curl_close($ch);

  //  $header['errno']   = $err;
  //  $header['errmsg']  = $errmsg;
  //  $header['content'] = $content;
    return $header;
}

$curl_data = "remember=1&identity=jobcpa1@yahoo.com&password=4321#";
$url = "http://sitebuilder.glchub.com/authlogin";
$response = get_web_page($url,$curl_data);

print '<pre>';
print_r($response);

// curl -c ~/cookies.txt -b ~/cookies.txt --data "" 

// $identity = 'jobcpa1@yahoo.com';
// $password = '4321#';
// $remember = '1';
//
// $loginUrl = 'http://sitebuilder.glchub.com/authlogin';
//
// //init curl
// $ch = curl_init();
//
// //Set the URL to work with
// curl_setopt($ch, CURLOPT_URL, $loginUrl);
//
// // ENABLE HTTP POST
// curl_setopt($ch, CURLOPT_POST, 1);
//
// // curl_setopt($ch, )
//
// //Set the post parameters
// curl_setopt($ch, CURLOPT_POSTFIELDS, 'identity=' . $username . '&password=' . $password . '&remember=' . $remember);
//
// //Handle cookies for the login
// curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
//
// //Setting CURLOPT_RETURNTRANSFER variable to 1 will force cURL
// //not to print out the results of its query.
// //Instead, it will return the results as a string return value
// //from curl_exec() instead of the usual true/false.
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//
// //execute the request (the login)
// $store = curl_exec($ch);
//
// // print_r($store);
//
// //the login is now done and you can continue to get the
// //protected content.
//
// //set the URL to the protected file
// curl_setopt($ch, CURLOPT_URL, 'https://glchub.com/resources/glc-dfysalesfunnelmoney/ebook-sales-funnel-money.pdf');
//
// //execute the request
// $content = curl_exec($ch);
//
// // print_r($content);
//
// die('exiting');
