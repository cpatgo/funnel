<?php

/*
	ATTENTION:

	YOU CAN NOT INCLUDE THIS FILE UNTIL YOU CHECK FOR PHP5 REQUIREMENT !
*/


function facebook_oauth_me($init) {
	try {
		$me = $init->api("/me");
	}
	catch (FacebookApiException $e) {
		$me = array( "error" => 1, "message" => $e->getMessage() );
	}
	return $me;
}

function facebook_available() {
	$site = adesk_site_get();

	return $site["facebook_app_id"] != "" && $site["facebook_app_secret"] != "";
}

?>
