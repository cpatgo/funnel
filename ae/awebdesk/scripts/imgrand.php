<?php

header('P3P: CP="NON BUS INT NAV COM ADM CON CUR IVA IVD OTP PSA PSD TEL SAM"');

@session_start();

/* C A P T C H A */
$alphanum = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';

// generate the verication code
$rand = substr(str_shuffle($alphanum), 0, 5);

// create the hash for the random number and put it in the session
if ( !isset($_SESSION['image_random_value']) ) $_SESSION['image_random_value'] = array();
$_SESSION['image_random_value'][md5($rand)] = time();

// choose one of four background images
$bgNum = rand(1, 4);

$image = imagecreatefromjpeg(dirname(dirname(__FILE__)) . "/media/random_background$bgNum.jpg");

// the text color is black
$textColor = imagecolorallocate ($image, 0, 0, 0);

// write the random number
imagestring($image, 5, 5, 8, $rand, $textColor);

// send several headers to make sure the image is not cached
// taken directly from the PHP Manual

// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

// always modified
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

// HTTP/1.0
header("Pragma: no-cache");

// send the content type header so the image is displayed properly
header('Content-type: image/jpeg');

// send the image to the browser
imagejpeg($image);

// destroy the image to free up the memory
imagedestroy($image);

?>
