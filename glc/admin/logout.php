<?php
session_start();
//ini_set('display_errors','off');
include("../config.php");
include("condition.php");
include_once($_SERVER['DOCUMENT_ROOT'].'/wp-config.php');
wp_logout();
if(isset($_SESSION['temp_dennisn_user_login']))
{
	session_unset();
	((is_null($___mysqli_res = mysqli_close($con))) ? false : $___mysqli_res);
	echo '<script type="text/javascript">' . "\n";
	echo 'window.location="login.php";';
	echo '</script>';
}
else
{
	session_unset();
	((is_null($___mysqli_res = mysqli_close($con))) ? false : $___mysqli_res);
	echo '<script type="text/javascript">' . "\n";
	echo 'window.location="login.php";';
	echo '</script>'; 
}