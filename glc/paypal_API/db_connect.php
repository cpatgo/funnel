<?php

// connects to database
$dbhost = "localhost";
$dbuser = "mahendra";
$dbpass = "mahendra123";

$conn = ($GLOBALS["___mysqli_ston"] = mysqli_connect($dbhost,  $dbuser,  $dbpass)) or die ('Error connecting to mysql');

$dbname = "colorsofyourhealth_business";
((bool)mysqli_query($GLOBALS["___mysqli_ston"], "USE " . $dbname));

//ini_set('display_errors',0);
//ini_set('log_errors',1);
//error_reporting(E_ALL);
?>