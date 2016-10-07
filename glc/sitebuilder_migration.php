<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/glc/config.php');
if(!isset($_GET['do'])) printf('<script type="text/javascript">window.location="%s/glc/admin";</script>', GLC_URL);

define('ENVIRONMENT', 'production');
define('BASEPATH', 'abc');
require_once($_SERVER['DOCUMENT_ROOT'].'/sitebuilder/application/config/database.php');

$class_user = getInstance('Class_User');
$users = $class_user->get_users();

//Connect to sitebuilder's db
$sitebuilder_con = mysqli_connect($db['default']['hostname'],$db['default']['username'],$db['default']['password'],$db['default']['database']);

foreach($users as $key => $value) {
		
	// Users table.
    $data = array(
    	'ip_address' => $_SERVER['REMOTE_ADDR'],
        'username'   => $value['username'],
        'password'   => $value['password'],
        'email'      => $value['email'],
        'created_on' => time(),
        'last_login' => time(),
        'active'     => 1,
        'first_name' => $value['f_name'],
		'last_name'  => $value['l_name']
    );
    $sql_array = $class_user->array_to_sql($data);
    $sql_user = sprintf('INSERT INTO users (%s) VALUES (%s)', $sql_array['keys'], $sql_array['values']);
    $user_inserted = mysqli_query($sitebuilder_con, $sql_user);

    if($user_inserted):
    	$user_id = mysqli_insert_id($sitebuilder_con);
    	$sql_group = sprintf('INSERT INTO users_groups (user_id, group_id) VALUES (%d, 2)', $user_id);
    	$group_inserted = mysqli_query($sitebuilder_con, $sql_group);

    	if($group_inserted):
	    	printf("USER ID: %d, %s has been registered to Sitebuilder<br>", $value['id_user'], $value['username']);
	    else:
	    	printf("USER ID: %d, %s failed registering user group to Sitebuilder<br>", $value['id_user'], $value['username']);
	    endif;
	else:
		printf("USER ID: %d, %s failed registering to Sitebuilder<br>", $value['id_user'], $value['username']);
    endif;
}