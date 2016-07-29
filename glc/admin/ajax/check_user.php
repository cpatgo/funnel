<?php
// This file will perform ajax requests for getting user in the database
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
if(!isset($_POST)) printf('<script type="text/javascript">window.location="%s/glc/admin";</script>', GLC_URL);

//Ajax request
$id = $_POST['user_id'];
$user_class = getInstance('Class_User');

$user = $user_class->get_user($id);
if(empty($user)) die(json_encode(array('type' => 'error', 'message' => 'User not found.')));

$membership = $user_class->user_membership($id);
$response = array('type' => 'success', 'message' => array('user' => $user[0], 'membership' => $membership[0]));
echo json_encode($response);
die();