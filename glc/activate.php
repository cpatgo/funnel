<?php
session_start();
// This file will perform ajax requests for activating free user account
require_once(dirname(__FILE__).'/config.php');
if(!isset($_GET) && !isset($_GET['token'])) printf('<script type="text/javascript">window.location="%s";</script>', GLC_URL);

$data = explode('-', $_GET['token']);
$user_id = base64_decode($data[1]);
$token_time = $data[0];
$pww = base64_decode($data[2]);

if($data[0] < time()) printf('<script type="text/javascript">window.location="%s/glc/login.php?err=2";</script>', GLC_URL);

$user = getInstance('Class_User');
//Activate user account
$activate = $user->activate_user($user_id);
$user_data = $user->get_user($user_id);
$user_data[0]['password'] = $pww;

//Auto login user
glc_auto_login($user_id, $user_data[0]);
//Redirect user to dashboard
printf('<script type="text/javascript">window.location="%s/myhub";</script>', GLC_URL);