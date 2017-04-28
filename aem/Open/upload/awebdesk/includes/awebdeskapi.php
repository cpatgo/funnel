<?php

if ( !defined('adesk_API_REMOTE') ) define('adesk_API_REMOTE', 0);
if ( !defined('adesk_API_REMOTE_OUTPUT') ) define('adesk_API_REMOTE_OUTPUT', 'xml');

// get params
$action = (string)adesk_http_param('f');

if ( $action == '' ) {
    adesk_api_error(_a("No command."));
	exit;
} elseif ( !isset($allowed[$action]) ) {
    adesk_api_error(sprintf(_a("Unknown command: %s"), $action));
	exit;
}

$actionFile = adesk_admin("api/$action.php"); // api files for both public and admin side are in manage/api!
$actionFunc = ($allowed[$action] != '' ? $allowed[$action] : $action);

if (!file_exists($actionFile)) {
	if (strpos($action, ".") !== false) {	# file.function
		$tmp = explode(".", $action);
		$actionFile = adesk_admin("functions/$tmp[0].php");
		$actionFunc = $tmp[1];
	} elseif (strpos($action, "!") !== false) {	# file!adesk_function
		$tmp = explode("!", $action);
		$actionFile = awebdesk_functions("$tmp[0].php");
		$actionFunc = $tmp[1];
	} else {								# the function name is the same as the filename
		$actionFile = adesk_admin("functions/$action.php");
	}
}

if ( /*$allowed[$action] == '' and*/ !file_exists($actionFile) ) {
	adesk_api_error(_a("Action File does not exist, Corrupted installation. Reupload all the files."));
	exit;
}

// require the api function file
require_once($actionFile);

if ( !function_exists($actionFunc) ) {
	adesk_api_error(_a("Action Function does not exist, Corrupted installation. Reupload all the files."));
	exit;
}

// declare requested action only
adesk_ajax_declare($action, $actionFunc);

adesk_api_run();

?>
