<?php
$timeout=10000;
$timeout = $timeout * 60; // Converts minutes to seconds
	 
$logout_redirect_url = "logout.php"; // Set logout URL
if (isset($_SESSION['start_time'])) {
    $elapsed_time = time() - $_SESSION['start_time'];
   if ($elapsed_time >= $timeout) {
       // session_destroy();
        header("Location: $logout_redirect_url");
    }
}
$_SESSION['start_time'] = time();
?>