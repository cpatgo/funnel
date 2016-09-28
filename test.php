<?php 

$set = setcookie('referral',$referral,time() + (86400 * 180), '/');

die($set);