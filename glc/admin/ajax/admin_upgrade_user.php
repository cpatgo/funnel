<?php
// This file will perform ajax requests for getting user in the database
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
if(!isset($_POST)) printf('<script type="text/javascript">window.location="%s/glc/admin";</script>', GLC_URL);

//Ajax request
parse_str($_POST['fields'], $fields);

$user_class = getInstance('Class_User');
$email_class = getInstance('Class_Email');
$membership_class = getInstance('Class_Membership');

$user = $user_class->get_user($fields['user_id']);
$membership = $user_class->user_membership($fields['user_id']);
$new_membership = $membership_class->get_membership($fields['level']);

//Upgrade user
$upgrade_user = $user_class->upgrade_user($user[0], $membership[0], $fields['level']);
$update_membership = $user_class->update_membership($fields['user_id'], $fields['level']);

//Update wordpress membership
$upgrade_wp_membership = $user_class->wp_update_membership($new_membership[0]['membership'], $membership[0]['membership'], $user[0]['email']);

//Update upgrade status
$update_upgrade_status = $membership_class->update_upgrade_membership($fields['upgrade_id']);

//Send email about upgrade
$email_class->upgrade_membership($user[0], $membership[0]['membership'], $new_membership[0]['membership']);

$response = array('type' => 'success', 'message' => 'Successfully upgraded.');
echo json_encode($response);
die();