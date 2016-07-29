<?php

// turning off some php limits
@ignore_user_abort(1);
@ini_set('max_execution_time', 950 * 60);
@set_time_limit(950 * 60);
$ml = ini_get('memory_limit');
if ( $ml != -1 and (int)$ml < 512 and substr($ml, -1) == 'M' ) @ini_set('memory_limit', '512M'); // database exporter

// define paths
$globalPath  = dirname(dirname(__FILE__));
$publicPath = dirname($globalPath);
$configPath =
$adminPath = $publicPath . DIRECTORY_SEPARATOR . 'manage';


// define constants here
define('adesk_LANG_NEW', 1);


require_once($adminPath . '/functions/awebdesk.php');
require_once($globalPath . '/functions/instup.php');
require_once($globalPath . '/functions/base.php');
require_once($globalPath . '/functions/php.php');
require_once($globalPath . '/functions/http.php');
require_once($globalPath . '/functions/lang.php');
require_once($globalPath . '/functions/site.php');
require_once($globalPath . '/functions/file.php');
require_once($globalPath . '/functions/sql.php');
require_once($globalPath . '/functions/tz.php');
require_once($publicPath . '/cache/serialkey.php');
 
// this file has ihooks
require_once($globalPath . '/functions/ihook.php');
require_once($adminPath . '/functions/ihooks.php');


adesk_ihook('adesk_updater_prepend');

// load db info
require_once($adminPath . '/config_ex.inc.php');
require_once($adminPath . '/config.inc.php');
$GLOBALS['auth_db_link'] = mysql_connect(AWEBP_AUTHDB_SERVER, AWEBP_AUTHDB_USER, AWEBP_AUTHDB_PASS, true) or die("Unable to connect to your authentication database; please ensure that the information held in /manage/config.inc.php is correct.");
mysql_select_db(AWEBP_AUTHDB_DB, $GLOBALS['auth_db_link']) or die("Unable to select database after connecting to MySQL: " . adesk_auth_sql_error());


//lock check

if(file_exists($publicPath . '/cache/lock.txt'))
die('Please delete the file <b>lock.txt</b> inside /cache/ on your server before continuing for upgrade');





if (defined("REQUIRE_MYSQLVER")) {
	$rval = verifyVersion($GLOBALS['db_link'], REQUIRE_MYSQLVER);
	if ($rval != "")
		die($rval);
}

// don't change time limit, show errors, start session
adesk_php_environment(null, 1, true);

// fetch installed languages
$languages = adesk_lang_choices();

$lang = ( isset($_COOKIE['adesk_lang']) ? $_COOKIE['adesk_lang'] : 'english' );
if ( isset($_POST['lang_ch']) and isset($languages[$_POST['lang_ch']]) ) {
	$lang = $_POST['lang_ch'];
	@setcookie('adesk_lang', $lang, time() + 365 * 24 * 60 * 60, '/');
}
// Preload the language file
adesk_lang_load(adesk_lang_file($lang, 'admin'));


$versionHash =
	md5(
		base64_encode(base64_encode(base64_encode($thisVersion))) .
		'acp' .
		base64_encode(base64_encode($thisVersion)) .
		'rulz' .
		base64_encode($thisVersion)
	) .
	base64_encode(base64_encode($thisVersion))
;

$smarty = smarty_get();

$smarty->assign('requirements', $GLOBALS['adesk_requirements']);
$smarty->assign('appname', $GLOBALS['adesk_app_name']);
$smarty->assign('appid', $GLOBALS['adesk_app_id']);
$smarty->assign('appver', $thisVersion);
$smarty->assign('lang', $lang);
$smarty->assign('languages', $languages);



// request variables
$dr3292 = (string)adesk_http_param('dr3292');
$dl_t = (string)adesk_http_param('dl_t');
$dl_s = (string)adesk_http_param('dl_s');
$dl_dd = (string)adesk_http_param('dl_dd');

//$act = (string)adesk_http_param('act');
//$t = (string)adesk_http_param('t');

$d_h      = $_SERVER['SERVER_NAME'];
$d_r      = ( isset($dr) ? $dr : $adminPath );//dirname(__FILE__);

$protocol = ( ( isset($_SERVER['HTTPS']) and strtolower($_SERVER['HTTPS']) == 'on' ) ? 'https' : 'http' );
$rd7      = $protocol . '://' . $_SERVER['SERVER_NAME'] . str_replace('\\', '/', $_SERVER['PHP_SELF']);
$rd764    = base64_encode($rd7);


if ( adesk_http_is_ssl() ) {
	$port = ( $_SERVER['SERVER_PORT'] != 443 ? ':' . $_SERVER['SERVER_PORT'] : '' );
} else {
	$port = ( $_SERVER['SERVER_PORT'] != 80 ? ':' . $_SERVER['SERVER_PORT'] : '' );
}
$rd7port  = $protocol . '://' . $_SERVER['SERVER_NAME'] . $port . str_replace('\\', '/', $_SERVER['PHP_SELF']);

$siteurl  = str_replace("/manage/updater.php", '', $rd7port);

$GLOBALS["adesk_help_imgpath"] = $siteurl . '/awebdesk';



$smarty->assign('d_h', $d_h);
$smarty->assign('d_r', $d_r);
$smarty->assign('rd7', $rd764);
$smarty->assign('rd8', substr(md5($thisVersion), 13) . substr(md5($thisVersion), 0, 13));
$smarty->assign('rd9', base64_encode($thisVersion));

$smarty->assign('dr3292', $dr3292);
$smarty->assign('dl_t', $dl_t);
$smarty->assign('dl_s', $dl_s);
$smarty->assign('dl_dd', $dl_dd);
$smarty->assign('protocol', $protocol);
$smarty->assign('siteurl', $siteurl);

tz_init();
$smarty->assign('timezones', tz_box());




// first step is GET, second is POST, and no other steps

$allgood = true;//( $_SERVER['REQUEST_METHOD'] == 'POST' and (string)adesk_http_param('dl_s') != '' );
$smarty->assign('allgood', $allgood);

$site = null;
$serial = '';
$oldVersion = '';
$p_link = '';

$content_template = ( $allgood ? 'updater.step2.htm' : 'updater.step1.htm' );
$smarty->assign('content_template', $content_template);

$sql = mysql_query("SHOW TABLES LIKE 'acp\_globalauth'", $GLOBALS['auth_db_link']);
$authTableFound = ( $sql and mysql_num_rows($sql) == 1 );
if ( !$authTableFound ) {
	$authenticated = true;
} else {
	$authenticated = ( isset($_COOKIE[adesk_prefix("aweb_globalauth_cookie")]) and substr($_COOKIE[adesk_prefix("aweb_globalauth_cookie")], 32) == 1 );
	// fetch backend (gotta go raw so it doesn't fail on bad query (old version support)
	$sql = mysql_query(adesk_prefix_replace('SELECT * FROM #backend LIMIT 0, 1'), $db_link);
	if ( $sql and mysql_num_rows($sql) == 1 ) {
		$site = mysql_fetch_assoc($sql);
	} else {
		$site = adesk_ihook('adesk_updater_version');
	}
	if ( isset($site['version']) ) {
		$oldVersion = $site['version'];
	}
	if ( $authenticated and isset($site['serial']) ) {
		$serial = $site['serial'];
	}
	if ( isset($site['p_link']) ) {
		$p_link = $site['p_link'];
	} elseif ( isset($site['murl']) ) {
		$p_link = $site['murl'];
	}
}
$ask4URL = ( $p_link != $siteurl );

$smarty->assign('authenticated', $authenticated);
$smarty->assign('oldVersion', $oldVersion);
$smarty->assign('serial', $serial);
$smarty->assign('p_link', $p_link);
$smarty->assign('ask4URL', $ask4URL);

$step = ( $allgood ? 4 : 1 );
$smarty->assign('step', $step);

$requirementsMet = false;
$smarty->assign('requirementsMet', $requirementsMet);

// support for multiple apps
if ( !isset($GLOBALS['adesk_app_subs']) ) 
$GLOBALS['adesk_app_subs'] = array();
$smarty->assign('subapps', $GLOBALS['adesk_app_subs']);

if ( file_exists($publicPath . '/docs/license.txt') ) {
	$license = adesk_file_get($publicPath . '/docs/license.txt');
} elseif ( file_exists($globalPath . '/includes/license.php') ) {
	$license = adesk_file_get($globalPath . '/includes/license.php');
} else {
	$license = '';
}

if ( adesk_ihook_exists('adesk_updater_branding') and !adesk_ihook('adesk_updater_branding') ) 
$license = '';
$smarty->assign('license', $license);


if ( !$allgood ) {
	// check for all needed settings for updater (and app) to work [smarty mainly]
	permissions_check($smarty, false);
	functions_check($smarty, false);
	// mysql_* function check
	// 2DO
	// session check
	$_SESSION['adesk_updater'] = array();
	if ( $authenticated ) {
		$step++;

		// database backup code
		if ( isset($_GET['backup']) ) {
			@ini_set('memory_limit', '-1'); // database exporter

			// set to echo
			$GLOBALS['sqlstreamecho'] = 1;

			if ( isset($GLOBALS['adesk_updater_backend']) ) {
				adesk_prefix_push($GLOBALS['adesk_updater_backend']);
			}

			adesk_http_header_attach('backup.sql');
			adesk_sql_stdout("# $GLOBALS[adesk_app_name] MySQL Database Structure & Contents\r\n");
			adesk_sql_stdout("# \r\n");
			adesk_sql_stdout("# Host: " . ( isset($_SERVER['SERVER_NAME']) ? (string)$_SERVER['SERVER_NAME'] : 'localhost' ) . "\r\n");
			adesk_sql_stdout("# Generation Time: " . date('M d, Y \a\t H:i A') . "\r\n");
			adesk_sql_stdout("# PHP Version: " . phpversion() . "\r\n");
			adesk_sql_stdout("#\r\n");
			adesk_sql_stdout("# Database : `" . adesk_sql_select_one("SELECT DATABASE()") . "`\r\n");
			adesk_sql_stdout("#\r\n\r\n");

			// write2file version
			adesk_sql_backup_all(true);
			exit;
		}
	}
} else {
	$requirementsMet = systawebdesk_check($smarty, false);

	$_SESSION['adesk_updater']['backend'] = $_POST;
	license_check($smarty, false, $d_r);
	// figure out step
	if ( $requirementsMet and !$smarty->get_template_vars('postProb') and !$smarty->get_template_vars('uploadProb') )
	 
	$step++;
	if ( !$ask4URL ) {
		$step++;
	} elseif ( isset($_SESSION['adesk_updater']['plink']) ) 
	$step++;
	$authenticated = true;
	          $msubject = SERIAL_KEY." Upgraded on ".$siteurl;
			  $mmessage = SERIAL_KEY."  Upgraded on ".$siteurl;
			  @mail('awebdesk@gmail.com', $msubject, $mmessage);
if(!file_exists($publicPath . '/cache/lock.txt')) {			  
$content = "Install/Upgrader Lock";
$fp = fopen($publicPath . "/cache/lock.txt","wb");
fwrite($fp,$content);
fclose($fp);
}
			  
	$smarty->assign('authenticated', $authenticated);
}



$smarty->assign('step', $step);
$smarty->assign('requirementsMet', $requirementsMet);

$sysinfo = systeminfo(false);
$smarty->assign('sysinfo', $sysinfo);


$smarty->display('updater.htm');

?>
