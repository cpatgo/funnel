<?php
if ( isset($_REQUEST['referral']) ) {
	$referral = $_REQUEST['referral'];

	echo $referral;
}
else {
	die('no referral indicated.');
}

