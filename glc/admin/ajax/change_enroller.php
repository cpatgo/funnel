<?php
// This file will perform ajax requests for changing the enroller of the user
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
if(!isset($_POST)) printf('<script type="text/javascript">window.location="%s/glc/admin";</script>', GLC_URL);

//Ajax request to change enroller of user
$id = $_POST['id'];
$enroller = $_POST['enroller'];
$user = getInstance('Class_User');

$get_username = $user->get_by_username($enroller);
if(!$get_username || empty($get_username)) die(json_encode(array('type' => 'error', 'message' => 'Invalid username.')));

$update = $user->update_enroller($id, $enroller);
$response = array('type' => 'success', 'message' => 'Successfully updated enroller.');
echo json_encode($response);
die();