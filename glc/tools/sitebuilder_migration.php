<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/glc/config.php');

// if(!isset($_GET['do'])) printf('<script type="text/javascript">window.location="%s/glc/admin";</script>', GLC_URL);

$user_class = getInstance('Class_User');
$users = get_users("orderby=ID");

// print_r($users);
// die('fin');

foreach($users as $key => $value) {
	// printf('%s - %s', $key, $value);
	$glc_user = $user_class->get_by_username($value->data->user_login);
	$glc_user = $glc_user[0];
	print_r($glc_user);
	
}





/* notes 
get all users from WP/matrix
put all user + password + email in temp table
import to site builder db




*/
