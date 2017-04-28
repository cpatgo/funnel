<?php
include_once("inc/facebook.php"); //include facebook SDK
######### Facebook API Configuration ##########
$appId = '151666725359015'; //Facebook App ID
$appSecret = '4f82b43a168d47df88194797721f05ec'; // Facebook App Secret
$homeurl = 'http://1min.identifz.com/glc/facebook_login_with_php/';  //return to home
$fbPermissions = 'email';  //Required facebook permissions

//Call Facebook API
$facebook = new Facebook(array(
  'appId'  => $appId,
  'secret' => $appSecret

));
$fbuser = $facebook->getUser();
?>