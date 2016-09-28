<?php 

$set = setcookie('referral',$referral,time() + (86400 * 180), '/');

if ($set){
	printf('Cookie: %s', $set);	
}
else {
	print 'No Cookie Set!';
}
