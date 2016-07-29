<?php
// require main include file
require_once(dirname(__FILE__) . '/awebdeskend.inc.php');

require_once(awebdesk_functions('export.php'));
//require_once(awebdesk_functions('smarty.php'));

if ( !adesk_admin_isadmin() ) {
	echo 'You are not logged in.';
	exit;
}

// turning off some php limits
@ignore_user_abort(1);
@ini_set('max_execution_time', 950 * 60);
@set_time_limit(950 * 60);
$ml = ini_get('memory_limit');
if ( $ml != -1 and (int)$ml < 128 and substr($ml, -1) == 'M') @ini_set('memory_limit', '128M');
set_include_path('.');
@set_magic_quotes_runtime(0);

// change language if not found
if ( !isset($languages[$admin['lang']]) ) $admin['lang'] = 'english';

// Preload the language file
adesk_lang_get('admin');

// get vars
$action     = basename((string)adesk_http_param('action'));
$type       = (string)adesk_http_param('type');
$fileName   = (string)adesk_http_param('filename');
// $id, $sort, $offset, $limit, $filter
$sort       = (string)adesk_http_param('sort');
$id         = (int)adesk_http_param('id');
$offset     = (int)adesk_http_param('offset');
$limit      = (int)adesk_http_param('limit');
$filter     = (int)adesk_http_param('filter');
$segmentid  = (int)adesk_http_param("segmentid");
$delimiter  = (string)adesk_http_param('delimiter');
$wrapper    = (string)adesk_http_param('wrapper');
$what       = (string)adesk_http_param("what");

if ( !$delimiter ) $delimiter = ',';
if ( !$wrapper ) $wrapper   = '"';

$fields     = explode(',', trim((string)adesk_http_param('fields')));

if ( $what == "list" ) {
	# We want everyone; wipe out the limit and offset.
	$limit  = 999999999;
	$offset = 0;
}

// check type
if ( !in_array($type, array('xml', 'xls', 'csv', 'html')) ) $type = 'xml';

# Action whitelist
if ( !in_array($action, array("subscriber", "exclusion", "template")) ) $action = "subscriber";

// get assets
$file = adesk_admin('functions/' . $action . '.php');
if ( !$action or !file_exists($file) ) {
	echo _a('Action not supported.');
	exit;
}
require_once($file);

// get processor
$function = $action . '_export';
if ( !function_exists($function) ) {
	echo _a('Method not supported.');
	exit;
}

// set the filename
if ( !$fileName ) $fileName = adesk_str_urlsafe($action . '-' . date('Ymd'));

// this is a custom case (exporting one template)
if ( $action == 'template' ) {
	$function($id, $type);
	exit;
	// end the script execution here (in case function didn't stop it)!
}

// for every function, we need:
// $id, $sort, $offset, $limit, $filter
// used in subscriber/exclusion
// should return a result set ideally
$export = $function($fields, $sort, $offset, $limit, $filter, $segmentid);
if ( !$export or !isset($export['rs']) or !$export['rs'] or !adesk_sql_num_rows($export['rs']) ) {
	echo _a('Data not found.');
	exit;
}

// send headers
adesk_export_headers($type, $fileName);

adesk_export_print($export, $type, $wrapper, $delimiter);

exit;

?>
