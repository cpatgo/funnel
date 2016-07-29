<?php
require_once("config.php");
include("function/functions.php");	
$username = $_REQUEST['real_parent'];
if($username == "" || user_exist($username) == 0){
	echo json_encode(array("Please check your Enroller's Username. As Entered, this enroller does not exist in our system. If the problem persists, please contact Support."));
} else {
	echo false;
}
?>