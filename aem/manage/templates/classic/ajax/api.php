<?php
if(!session_id()) session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/aem/manage/config.inc.php');
$GLOBALS["aem_con"] = mysqli_connect(AWEBP_AUTHDB_SERVER, AWEBP_AUTHDB_USER, AWEBP_AUTHDB_PASS, AWEBP_AUTHDB_DB);

$action = $_POST['action'];
if($action === 'add_list') add_list();
if($action === 'add_subscriber') add_subscriber();
if($action === 'add_form') add_form();

function add_form() {
	parse_str($_POST['fields'], $fields);
	$list_id = $_SESSION['selected_list_id'];
	$ask4fname = (array_key_exists('ask4fname', $fields) && $fields['ask4fname']) ? $fields['ask4fname'] : 0;
	$ask4lname = (array_key_exists('ask4lname', $fields) && $fields['ask4lname']) ? $fields['ask4lname'] : 0;

	$post = array(
		'name'                     => $fields['form_name'], // the internal name of the subscription form
		'type'                     => 'subscribe', // options: both, subscribe, unsubscribe
		'sub1'                     => 'redirect', // options: default, custom, redirect
		'sub1_redirect'            => $fields['sub2_redirect'], // URL (for redirect)
		'sub2'                     => 'redirect', // options: default, custom, redirect
		'sub2_redirect'            => $fields['sub2_redirect'], // URL (for redirect)
		'sub3'                     => 'redirect', // options: default, custom, redirect
		'sub3_redirect'            => $fields['sub3_redirect'], // URL (for redirect)
		'sub4'                     => 'default', // options: default, custom, redirect
		'unsub1'                   => 'default', // options: default, custom, redirect
		'unsub2'                   => 'default', // options: default, custom, redirect
		'unsub3'                   => 'default', // options: default, custom, redirect
		'unsub4'                   => 'default', // options: default, custom, redirect
		'up1'                      => 'default', // options: default, custom, redirect
		'up2'                      => 'default', // options: default, custom, redirect
		'allowselection'           => 0, // options: 1 or 0
		'emailconfirmations'       => 1, // options: 1 or 0
		'ask4fname'                => 1, // First Name - options: 1 or 0
		'ask4lname'                => 1, // Last Name - options: 1 or 0
		'optinoptout'              => 1, // ID of Opt-In/Out set
		'captcha'                  => 0, // options: 1 or 0
		'p[0]'                     => $list_id, // example list ID
	);

	foreach ($fields['fields'] as $key => $value) {
		$post[sprintf('fields[%d]', $key)] = $value;
	}

	$add_form = curl_request($post, 'form_add');

	if((int)$add_form['result_code'] == 1): 
		die(json_encode(array('type' => 'success', 'message' => $add_form)));
	else:
		die(json_encode(array('type' => 'error', 'message' => 'Failed to add new form.', 'data' => $add_form)));
	endif;
}

function add_subscriber() {
	$email = $_POST['email'];
	$list_id = $_SESSION['selected_list_id'];
	$post = array(
		'email'                    => $email,
		'p['.$list_id.']'          => $list_id, // example list ID
		'status['.$list_id.']'     => 1, // 0: unconfirmed (Downloaded users only), 1: active, 2: unsubscribed
	);
	$add_subscriber = curl_request($post, 'subscriber_add');

	if((int)$add_subscriber['result_code'] == 1): 
		die(json_encode(array('type' => 'success', 'message' => $add_subscriber)));
	else:
		die(json_encode(array('type' => 'error', 'message' => 'Failed to add new subscriber.', 'data' => $add_subscriber)));
	endif;
}

function add_list() {
	parse_str($_POST['fields'], $fields);
	$post = array(
		'name'                     => $fields['list_name'], // list name
		'subscription_notify'      => '', // comma-separated list of email addresses to notify on new subscriptions to this list
		'unsubscription_notify'    => '', // comma-separated list of email addresses to notify on any unsubscriptions from this list
		'to_name'                  => "Subscriber", // if subscriber doesn't enter a name, use this
		'carboncopy'               => '', // comma-separated list of email addresses to send a copy of all mailings to upon send
		'stringid'                 => 'api-test', // URL-safe list name
		'optid'                    => '1', // ID of a Email Confirmation Set to use
		'bounceid[1]'              => 1, // use default bounce management account

		// HOSTED users only: sender information (all fields below) required
		'sender_name'				=> $fields['list_company'], // Company (or Organization)
		'sender_addr1'				=> sprintf('%s %s', $fields['list_address'], $fields['list_address2']), // Address
		'sender_zip'				=> $fields['list_postal'], // Zip or Postal Code
		'sender_city'				=> $fields['list_city'], // City
		'sender_state' 				=> $fields['list_state'],
		'sender_country'			=> $fields['list_country'], // Country
	);
	$add_list = curl_request($post, 'list_add');

	if((int)$add_list['result_code'] == 1): 
		$_SESSION['selected_list_id'] = $add_list['id'];
		$add_list['list_name'] = $fields['list_name'];
		die(json_encode(array('type' => 'success', 'message' => $add_list)));
	else:
		die(json_encode(array('type' => 'error', 'message' => 'Failed to add new list.', 'data' => $add_list)));
	endif;
}



function curl_request($post, $method)
{
	$pw = $_SESSION['dennisn_usertoken'];
	$decode = base64_decode($pw);
	$chunk = explode('-', $decode);
	$password = base64_decode($chunk[1]);

	$username = $_SESSION['dennisn_username'];
	$password = $password;

	$params = array(
		'api_user'     => $username,
		'api_pass'     => $password,
		'api_action'   => $method,
		'api_output'   => 'serialize',
	);

    include_once($_SERVER['DOCUMENT_ROOT'].'/glc/config.php');
    $url = sprintf('%s/aem', GLC_URL);

    // This section takes the input fields and converts them to the proper format
    $query = "";
    foreach( $params as $key => $value ) $query .= $key . '=' . urlencode($value) . '&';
    $query = rtrim($query, '& ');

    // This section takes the input data and converts it to the proper format
    $data = "";
    foreach( $post as $key => $value ) $data .= $key . '=' . urlencode($value) . '&';
    $data = rtrim($data, '& ');

    // clean up the url
    $url = rtrim($url, '/ ');

    // define a final API request - GET
    $api = $url . '/manage/awebdeskapi.php?' . $query;

    $request = curl_init($api); // initiate curl object
    curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
    curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
    curl_setopt($request, CURLOPT_POSTFIELDS, $data); // use HTTP POST to send form data

    $response = (string)curl_exec($request); // execute curl post and store results in $response
    curl_close($request); // close curl object

    if ( !$response ) {
        return array('result_code' => 0, 'result_message' => 'Nothing was returned. Do you have a connection to Email Marketing server?');
    }

    return unserialize($response);
}