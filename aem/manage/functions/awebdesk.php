<?php
/**
 * This file defines (or reads) application settings in regards to awebdesk library
 * It should be included first before engine file
 * It is designed to be included more than once
 * For some uses, it needs to be loaded again
 */


// application folder (usualy public interface), absolute path
$GLOBALS['adesk_app_path'] = dirname(dirname(dirname(__FILE__)));  // out of functions and admin

// define awebdesk folder path (so they can change it in engine if needed) ;-)
if ( !isset($GLOBALS['adesk_library_path']) ) {
	$GLOBALS['adesk_library_path'] = $GLOBALS['adesk_app_path'] . '/awebdesk';
}

if (!isset($_SERVER['REQUEST_URI'])) {
	if (!isset($_SERVER["QUERY_STRING"]))	# This is bad.
		$_SERVER["REQUEST_URI"] = $_SERVER["PHP_SELF"];
	else
		$_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
}

// define awebdesk folder path (so they can change it in engine if needed) ;-)
if ( !isset($GLOBALS['adesk_library_url']) and isset($_SERVER['REQUEST_URI']) ) {
	$uri = preg_replace('/(\?.*)/', '', $_SERVER['REQUEST_URI']);
	$libPath = str_replace('\\', '/', $GLOBALS['adesk_library_path']);
	$prodName = basename($GLOBALS['adesk_app_path']);
	if ( strpos($uri, "/$prodName/") !== false ) {
		$uriRoot = preg_replace('/(.*)(\/' . preg_quote($prodName, '/') . ')(\/.*)/', '$1$2', $uri);
	} else {
		$uriRoot = '';
	}
	$GLOBALS['adesk_library_url'] = $uriRoot . '/awebdesk';
	if ( $GLOBALS['adesk_library_path'] != $GLOBALS['adesk_app_path'] . '/awebdesk' ) {
		// completely different awebdesk URL, figure it out here
	}
	//$docRoot = str_replace('\\', '/', $GLOBALS['adesk_app_path']);
	//$docRoot = preg_replace('/(\/' . preg_quote($prodName, '/') . '.*)/', '', $docRoot);
	//$GLOBALS['adesk_library_url'] = substr($libPath, strlen($docRoot));
}

// define database prefix (so they can change it in engine if needed) ;-)
if ( !isset($GLOBALS['adesk_prefix_use']) ) {
	$GLOBALS['adesk_prefix_use'] = 'awebdesk_';
}


// full application name
$GLOBALS['adesk_app_name'] = 'Email Marketing Software';

// internal application id
$GLOBALS['adesk_app_id'] = 'AEM';

$GLOBALS['adesk_editor_rootpath'] = "images/" . ( isset($GLOBALS['_hosted_account']) ? $GLOBALS['_hosted_account'] . '/' : '' ) . "__USER__";

// if our database uses UTF-8 as its encoding
$GLOBALS['adesk_app_utf8'] = true;

// current version of the application
include(dirname(__FILE__) . '/versioning.php');
$GLOBALS['adesk_app_version'] = $thisVersion;

// current version of the awebdesk library soon?
$GLOBALS['adesk_library_version'] = '0.9.9';


/*
	allowed awebdesk assetss
*/

// for public side
$GLOBALS['adesk_assets_whitelist'] = array(
);

// for admin side
$GLOBALS['adesk_assets_whitelist_admin'] = array(
	'about',
	'cron',
	'errorslog',
	'group',
	'loginsource',
	'processes',
	'sync',
	'user',
	'widget',
	'serverinfo',
);


$GLOBALS['adesk_cron_protected'] = 9;

// branding variable will get set after arrays are initialized
if ( isset($GLOBALS['admin']) ) {
	$GLOBALS['adesk_branding_array'] =& $GLOBALS['admin'];
}

// branding array
$GLOBALS['adesk_branding_table'] = 'branding';


// sending options: table and fields in it
$GLOBALS['adesk_mail_engine'] = ( isset($GLOBALS['useOLDengine']) ? 'mailer' : 'swift' ); // since we let them tweak it in engine, it on the top of function_global.php we change this if needed
$GLOBALS['adesk_mail_table'] = 'mailer';

// database sync options: table
$GLOBALS['adesk_sync_table'] = 'sync';


// side menus
$GLOBALS['adesk_sidemenu_settings'] = '';

// folders that must be writable
$GLOBALS['adesk_writables'] = array(
	dirname(dirname(__FILE__)) . '/cache/manage',
	dirname(dirname(__FILE__)) . '/images',
);

// minimum requirements
$GLOBALS['adesk_requirements'] = array(
	'php' => '4.3',
	'mysql' => '4.1'
);

// needed functions
$GLOBALS['adesk_functions'] = array(
	//'mail'
);

if (!defined("LANGFILES_UTF8"))
	define("LANGFILES_UTF8", 1);

if ( !defined("adesk_IMPORT_LOGTABLE") ) define("adesk_IMPORT_LOGTABLE", '#subscriber_import');
if ( !defined("adesk_SYNC_LOGTABLE"  ) ) define("adesk_SYNC_LOGTABLE"  , '#subscriber_import');

// we have our own swift/php mailers here
//if ( !defined('SWIFT_ABS_PATH') ) define('SWIFT_ABS_PATH', $GLOBALS['adesk_library_path'] . '/swiftmailer/php' . (int)PHP_VERSION);
if ( !defined('SWIFT_ABS_PATH') ) define('SWIFT_ABS_PATH', $GLOBALS['adesk_library_path'] . '/swiftmailer/php4');
//if ( !defined('SWIFT_ABS_PATH') ) define('SWIFT_ABS_PATH', dirname(__FILE__) . '/mailer');
if ( !defined('MAILER_ABS_PATH') ) define('MAILER_ABS_PATH', dirname(__FILE__) . '/sender.php');

if ( !function_exists('_awebdesk_fixrootpath') ) {
function _awebdesk_fixrootpath() {
	# Necessary kludge to fix images/__USER__ if the global $admin variable doesn't exist (which
	# it probably won't) earlier in the file.
	if (isset($GLOBALS["admin"]))
		$GLOBALS['adesk_editor_rootpath'] = str_replace("__USER__", $GLOBALS["admin"]["username"], $GLOBALS["adesk_editor_rootpath"]);
}
}
?>
