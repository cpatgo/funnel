<?php
session_start();
ini_set('display_errors','off');
include("config.php");
include("condition.php");
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');


// burn AWEBDESK session
if (isset($_COOKIE['awebdesk_aweb_globalauth_cookie'])) {
	// adesk_auth_define_cookie();
    // setcookie(adesk_AUTH_COOKIE, "", time() - 3600, "/");
    // unset($_COOKIE[adesk_AUTH_COOKIE]);
    unset($_COOKIE['awebdesk_aweb_globalauth_cookie']);
    setcookie('awebdesk_aweb_globalauth_cookie', '', time() - 3600, '/');
    setcookie('awebdesk_aweb_globalauth_cookie', null, -1, '/');
}

if(isset($_SESSION['temp_dennisn_user_login']))
{
	// die('alert');

	session_unset();
	((is_null($___mysqli_res = mysqli_close($con))) ? false : $___mysqli_res);
	header('Location: /');
	echo '<script type="text/javascript">' . "\n";
	echo 'window.location="/";';
	echo '</script>';

	

	// burn WP Session
	wp_logout();


}
else
{
	// die('alert');
	
	session_unset();
	((is_null($___mysqli_res = mysqli_close($con))) ? false : $___mysqli_res);
	header('Location: /');
	echo '<script type="text/javascript">' . "\n";
	echo 'window.location="/";';
	echo '</script>'; 
	wp_logout();	
}