<?php 

$set = setcookie('referral',$referral,time() + (86400 * 180), '/');

printf('Cookie: %s', $set);