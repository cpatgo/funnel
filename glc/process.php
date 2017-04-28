<?php 
// process.php
if( file_exists('edata_transact.php') ) {
	require_once('edata_transact.php');
	// echo 'successfully added.';
}
else{
	die('file not found.');
}

if( isset($POST['edata_submit']) ) {
	 $result = array('result' => 'success', 'message' => 'test');
     die(json_encode($result));   
      // $result = array('result' => 'success', 'message' => sprintf('%s/glc/login.php?msg=User Registration Successfully Completed for %s Membership!', GLC_URL, $membership));
      //                               die(json_encode($result)); 
     header("Location: http://1min.identifz.com/successful-signup/");  
}