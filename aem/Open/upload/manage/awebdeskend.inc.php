<?php

// define paths
$adminPath = dirname(__FILE__);
$publicPath = dirname($adminPath);

// define constants here
if ( !defined('adesk_LANG_NEW'     ) ) define('adesk_LANG_NEW', 1);
if ( !defined('adesk_XML_WRITE_NEW') ) define('adesk_XML_WRITE_NEW', true);
$GLOBALS['adesk_ajax_encoding'] = false;

// fetch app info
require_once($adminPath . '/functions/awebdesk.php');

/*
	CONNECT TO DATABASE SERVER
*/
// including the database connection
if ( file_exists($adminPath . '/config_ex.inc.php') ) {
	require_once($adminPath . '/config_ex.inc.php');
}


/*
	check for requirements
*/
if (!isset($GLOBALS["_hosted_account"])) {
	if ( !is_writable($GLOBALS['adesk_app_path'] . '/cache') ) {
		die("<div style=\"padding:10px; font-size:12px; font-family: Arial, Helvetica, sans-serif; border:4px solid #109309; color:#000; \"><b>Your cache directory does not exist or does not have full write permissions.</b><br />Change the permissions of $GLOBALS[adesk_app_path]/cache so that it has full write access (CHMOD 777 on linux)</div>");
	}
	if ( !is_writable($GLOBALS['adesk_app_path'] . '/cache/public') ) {
		die("<div style=\"padding:10px; font-size:12px; font-family: Arial, Helvetica, sans-serif; border:4px solid #109309; color:#000; \"><b>Your public cache directory does not exist or does not have full write permissions.</b><br />Change the permissions of $GLOBALS[adesk_app_path]/cache/public so that it has full write access (CHMOD 777 on linux)</div>");
	}
	if ( !is_writable($GLOBALS['adesk_app_path'] . '/cache/manage') ) {
		die("<div style=\"padding:10px; font-size:12px; font-family: Arial, Helvetica, sans-serif; border:4px solid #109309; color:#000; \"><b>Your admin cache directory does not exist or does not have full write permissions.</b><br />Change the permissions of $GLOBALS[adesk_app_path]/cache/manage so that it has full write access (CHMOD 777 on linux)</div>");
	}
		if ( !is_writable($GLOBALS['adesk_app_path'] . '/cache/admin') ) {
		die("<div style=\"padding:10px; font-size:12px; font-family: Arial, Helvetica, sans-serif; border:4px solid #109309; color:#000; \"><b>Your admin cache directory does not exist or does not have full write permissions.</b><br />Change the permissions of $GLOBALS[adesk_app_path]/cache/admin so that it has full write access (CHMOD 777 on linux)</div>");
	}
	if ( !is_writable($GLOBALS['adesk_app_path'] . '/images') ) {
		die("<div style=\"padding:10px; font-size:12px; font-family: Arial, Helvetica, sans-serif; border:4px solid #109309; color:#000; \"><b>Your images directory does not exist or does not have full write permissions.</b><br />Change the permissions of $GLOBALS[adesk_app_path]/images so that it has full write access (CHMOD 777 on linux)</div>");
	}
	if ( !is_writable($GLOBALS['adesk_app_path'] . '/images/manage') ) {
		die("<div style=\"padding:10px; font-size:12px; font-family: Arial, Helvetica, sans-serif; border:4px solid #109309; color:#000; \"><b>Your admin images directory does not exist or does not have full write permissions.</b><br />Change the permissions of $GLOBALS[adesk_app_path]/images/manage so that it has full write access (CHMOD 777 on linux)</div>");
	}
}

// checking to ensure mysql_connect is a valid function
if ( !function_exists('mysql_connect') ) {
	die("<span style=\"font-weight: bold; color: Red;\">The mysql_connect function is not available.</span> This is likely due to MySql not being installed or not being setup properly.  Please contact your web host or server administrator to have MySql installed properly.");
}
if ( !function_exists('version_compare') ) {
	die("<span style=\"font-weight: bold; color: Red;\">The version_compare function is not available.</span> Please contact your web host or server administrator to have PHP installed properly.");
}
// checking to ensure that minimum version of PHP is installed
if ( !( function_exists('version_compare') and version_compare(PHP_VERSION, '4.3.0') > -1 ) ) {
	die("<span style=\"font-weight: bold; color: Red;\">The PHP installed on your server is too old.</span> Please contact your web host or server administrator to upgrade PHP installation on your server.");
}

$prfx = ( ( isset($_SERVER['REQUEST_URI']) and strpos($_SERVER['REQUEST_URI'], '/manage/') !== false ) ? '' : 'manage/' );
// check if system is installed
if ( !isset($db_link) ) {
	if (isset($GLOBALS['_hosted_account'])) {
		echo "Error (1001): I'm sorry, but we're having difficulty connecting to your database.  We're aware of the problem and are hard at work to fix the issue; please contact us if you have any concerns.";
		exit;
	} else {
		echo '<h1>Please run <a href="' . $prfx . 'install.php" style="color:#314605;">install.php</a> to install ' . $GLOBALS['adesk_app_name'] . '.</h1>';
		exit;
	}
}
if ( !$db_link ) {
	$mysql_errno = mysql_errno();
	if ( $mysql_errno == 1203 or $mysql_errno == 1040 ) {
		echo "MySQL Error ($mysql_errno). Too many mysql connections. For more information regarding this error contact your host or system administrator.";
	} else {
		echo "MySQL Server Returned Error #$mysql_errno: " . mysql_error();
	}
	exit;
}

mysql_query("SET NAMES 'utf8'", $db_link);

/*
	LIBRARIES
*/
// require ACP Global functions
require_once($GLOBALS['adesk_library_path'] . '/functions/basic.php');

// require local functions
require_once($adminPath . '/functions/aem.php');
require_once($adminPath . '/functions/ihooks.php');

# Set up the time zones
tz_init();

/*
	TWEAK ENVIRONMENT
*/
adesk_php_environment(30, 2, true);

$ml = ini_get('memory_limit');
if ( $ml != -1 and (int)$ml < 128 and substr($ml, -1) == 'M') @ini_set('memory_limit', '128M'); // database exporter

// always drop cache (instead of: no session shortcuts on main access pages)
adesk_session_drop_cache();





/*
	FETCH SETTINGS/PERMISSIONS/DEFAULTS
*/


// fetch site info
$site = adesk_site_unsafe();

// check if system is updated
if ( !isset($site['version']) or version_compare($site['version'], $thisVersion) < 0 ) {
	if (isset($GLOBALS['_hosted_account'])) {
		echo "Error (1002): I'm sorry, but we're having difficulty connecting to your database.  We're aware of the problem and are hard at work to fix the issue; please contact us if you have any concerns.";
		exit;
	} elseif (!isset($_SERVER["SCRIPT_FILENAME"]) || !preg_match('/mpma.php$/', $_SERVER["SCRIPT_FILENAME"])) {
		echo '<h1>Please run <a href="' . $prfx . 'updater.php" rel="nofollow" style="color:#314605;">updater.php</a> to update ' . $GLOBALS['adesk_app_name'] . '.</h1>';
		exit;
	}
}


// fetch installed languages
$languages = adesk_lang_choices();

// admin check
$admin = adesk_admin_get();
foreach ( $admin as $k => $v ) {
	if ( substr($k, 0, 6) == 'brand_' ) $site[$k] = $v;
}
$site['site_name'] = $site['brand_site_name'] = $admin['brand_site_name'];
$site['site_logo'] = $site['brand_site_logo'] = $admin['brand_site_logo'];

_awebdesk_fixrootpath();

adesk_getCurrentDateTime();

// single sign on
if ( !adesk_admin_isadmin() and isset($_GET['_ssot']) ) {
	// check for the existence of single sign on token
	require_once(awebdesk_functions('singlesignon.php'));
	adesk_sso_token_eval($_GET['_ssot']);
}

?>
