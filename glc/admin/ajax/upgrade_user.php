<?php
// This file will perform ajax requests for getting user in the database
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
if(!isset($_POST)) printf('<script type="text/javascript">window.location="%s/glc/admin";</script>', GLC_URL);

//Ajax request
parse_str($_POST['fields'], $fields);

$user_class = getInstance('Class_User');
$membership_class = getInstance('Class_Membership');

$user = $user_class->get_user($fields['user_id']);
$membership = $user_class->user_membership($fields['user_id']);
$new_membership = $membership_class->get_membership($fields['level']);

//Upgrade user
$upgrade_user = $user_class->upgrade_user($user[0], $membership[0], $fields['level']);
$update_membership = $user_class->update_membership($fields['user_id'], $fields['level']);

//Save upgrade details
$data = array(
    'user_id' 				=> $fields['user_id'],
    'current_membership'    => $membership[0]['initial'],
    'upgrade_membership'    => $new_membership[0]['id'],
    'requested_date'        => date('Y-m-d H:i'),
    'upgraded_date'         => date('Y-m-d H:i'),
    'payment_method'        => $fields['payment_method'],
    'transaction_id'        => $fields['transaction_id'],
    'status'                => 1
);
$upgrade_id = $membership_class->insert_upgrade_membership($data);


//Update wordpress membership
$upgrade_wp_membership = $user_class->wp_update_membership($membership[0]['membership']);
$upgrade_wp_membership = $user_class->wp_update_membership($new_membership[0]['membership'], $membership[0]['membership'], $user[0]['email']);

$response = array('type' => 'success', 'message' => 'Successfully upgraded.');
echo json_encode($response);
die();