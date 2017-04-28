<?php
ob_start();
session_start();
//set timezone
date_default_timezone_set('Europe/London');
//database credentials
define('DBHOST','localhost');
define('DBUSER','identifz_one');
define('DBPASS','Pl71791!197321');
define('DBNAME','identifz_glc_1min_one');
//application address
define('DIR','http://1min.identifz.com/one/');
define('SITEEMAIL','noreply@1min.identifz.com');
try {
	//create PDO connection
	$db = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
	//show error
    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
    exit;
}
//include the user class, pass in the database connection
include('classes/user.php');
include('classes/phpmailer/mail.php');
$user = new User($db);
?>