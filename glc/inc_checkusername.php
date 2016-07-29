<?php
require_once("config.php");
include("function/functions.php");	
$username = $_REQUEST['username'];

if(user_exist($username) > 0 || user_exist1($username) > 0){
	echo json_encode(array("Another member has already signed up using that username. Please select another username."));
} else {
	echo false;
}
