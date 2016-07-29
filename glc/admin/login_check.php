<?php
session_start();
ini_set('display_errors','off');
include('../config.php');

$username = mysqli_real_escape_string($con, $_REQUEST['username']);
$password = mysqli_real_escape_string($con, $_REQUEST['password']);

$sql = "select * from admin where username='".$username."' && password='".sha1($password)."' ";

$sql1=mysqli_query($GLOBALS["___mysqli_ston"], $sql);
$count = mysqli_num_rows($sql1);
if($count > 0)
{	
	$_SESSION['dennisn_admin_name']=$_REQUEST['username'];
	$_SESSION['dennisn_admin_email']=$_REQUEST['email'];
	$_SESSION['dennisn_admin_login']=1;
	
	echo "<script type=\"text/javascript\">";
	echo "window.location = \"index.php\"";
	echo "</script>";
}
else
{
	echo "<script type=\"text/javascript\">";
	echo "window.location = \"login.php?err=1\"";
	echo "</script>";
}

?>