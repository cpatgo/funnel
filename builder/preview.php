<?php
require_once(dirname(dirname(__FILE__)).'/glc/config.php');
if(!session_id()) session_start();
$user_class = getInstance('Class_User');
		
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$filename = "elements/preview_".generateRandomString(20).".html";

// Insert user id and filename to builder table
$data = array(
	'user_id' 		=> $_SESSION['dennisn_user_id'],
	'filename' 		=> $filename,
	'date_created' 	=> date('Y-m-d H:i:s')
);
$user_class->insert_builder($data);

$previewFile = fopen($filename, "w");

fwrite($previewFile, stripslashes($_POST['page']));

fclose($previewFile);

header('Location: '.$filename);


?>