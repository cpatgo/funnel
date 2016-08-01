<?php
// require main include file
require_once(dirname(__FILE__) . '/awebdeskend.inc.php');

/*
	WHITELIST
*/
$allowed = array(
	"open_pie" => true,
	"client_pie" => true,
	"link_bydate" => true,
	"link_byhour" => true,
	"read_bydate" => true,
	"read_byhour" => true,
	"read_byweek" => true,
	"subscribed_bydate" => true,
	"unsubscribed_bydate" => true,
	"emails_bydate" => true,
	"campaigns_bydate" => true,
);

$shareallowed = array(
	"link_bydate" => true,
	"link_byhour" => true,
	"read_bydate" => true,
	"read_byhour" => true,
	"open_pie" => true,
);

// Preload the language file
adesk_lang_get('admin');

if (isset($_GET["hash"]) && isset($_SESSION["awebdesk_sharedreport_hashes"]) && isset($_SESSION["awebdesk_sharedreport_hashes"][$_GET["hash"]])) {
	$_GLOBALS["admin"] = adesk_admin_get_totally_unsafe(1);
	$allowed = $shareallowed;
} else {
	// check for basic admin privileges
	if ( !adesk_admin_isadmin() ) {
		exit;
	}
}

// require ajax include
require_once awebdesk_includes("graph.php");

?>
