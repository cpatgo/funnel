<?php
if (!@ini_get("zlib.output_compression")) @ob_start("ob_gzhandler");

// require main include file
require_once(dirname(__FILE__) . '/awebdeskend.inc.php');
require_once(awebdesk_functions('process.php'));
require_once(awebdesk_functions('import.php'));
require_once awebdesk_functions("log.php");

// turning off some php limits
@ignore_user_abort(1);
@ini_set('max_execution_time', 950 * 60);
@set_time_limit(950 * 60);
$ml = ini_get('memory_limit');
if ( $ml != -1 and (int)$ml < 128 and substr($ml, -1) == 'M') @ini_set('memory_limit', '128M');
set_include_path('.');
@set_magic_quotes_runtime(0);



$action = basename(adesk_http_param('action'));
$test = (bool)adesk_http_param('test');
$relid = /*(int)*/adesk_http_param('relid'); // relation id




/*
	== permission checks go here! ==
*/
if ( !adesk_admin_isadmin() ) {
	echo 'You are not logged in.';
	exit;
}
// figure out state
$submitted = $_SERVER['REQUEST_METHOD'] == 'POST';
if ( !$submitted ) {
	echo 'Improper usage.';
	exit;
}
if ( !file_exists(adesk_admin("functions/$action.php")) ) {
	echo 'Invalid action';
	exit;
}

// Preload the language file
adesk_lang_get('admin');

// include hooks
require_once(adesk_admin("functions/$action.php"));

/*
if (isset($_POST["import_text"]))
	$_POST["import_text"] = adesk_utf_conv(_i18n("utf-8"), "UTF-8", $_POST["import_text"]);
*/
if ( !isset($_SESSION['subscriber_importer']) ) {
	echo 'Nothing to import -- please try again.';
	exit;
}

$_POST['destination'] = $_SESSION['subscriber_importer']['status'];
$_POST['relid'] = $_SESSION['subscriber_importer']['lists'];
$_POST['updateexisting'] = $_SESSION['subscriber_importer']['update'];
$_POST['lastmessage'] = $_SESSION['subscriber_importer']['sendlast'];
$_POST['userid'] = $admin['id'];
$r = subscriber_import_run(array_merge($_POST, $_SESSION['subscriber_importer']), $test, 0, true); // this runs importer

//dbg($r);

?>
