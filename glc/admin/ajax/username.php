<?php
// This file will perform ajax requests for getting username in the database
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
if(!isset($_POST)) printf('<script type="text/javascript">window.location="%s/glc/admin";</script>', GLC_URL);

//Ajax request to send single username to a contact
$username = $_POST['username'];
$user = getInstance('Class_User');

$get_username = $user->search_username($username);
$usernames = array();
foreach ($get_username as $key => $value) {
	$usernames[] = $value['username'];
}
$response = array('type' => 'success', 'message' => $usernames);
echo json_encode($response);
die();