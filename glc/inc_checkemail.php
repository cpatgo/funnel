<?php
require_once("config.php");
include("function/functions.php");	
$email = $_REQUEST['email'];
if(useremail_exist($email) > 0 || useremail_exist1($email) > 0){	
	echo json_encode(array("Another member has already signed up using that e-mail address. Please select another e-mail address."));
} else {
	echo false;
}
exit;
?>