<?PHP
define('AWEBVIEW', true);

/*

	THERE IS A COPY OF THIS FILE IN assets_INIT.PHP
	ALL UPDATES MADE TO THE LOGIN PROCESS SHOULD ALSO BE ADDED TO THAT FILE

*/

// require main include file
require_once(dirname(__FILE__) . '/awebdeskend.inc.php');
require_once awebdesk_functions("tz.php");

// require the rest of things we need
require_once awebdesk_functions("smarty.php");
require_once awebdesk_functions("loginsource.php");
require_once awebdesk_classes("loginsource.php");

 
//Security Lock check  new in v 6.3.0
 if(!file_exists(adesk_basedir() . '/cache/lock.txt')) {			  
$content = "Install/Upgrader Lock";
$fp = fopen(adesk_basedir() . "/cache/lock.txt","wb");
fwrite($fp,$content);
fclose($fp);
}
///security check finishes . Now carry on as normal.
// if form not posted properly, redirect
if ( $_SERVER['REQUEST_METHOD'] != 'POST' or !isset($_POST['user']) or !isset($_POST['pass']) ) {
	header('Location: index.php');
	exit;
}

$_POST['user'] = trim((string)$_POST['user']);
$_POST['pass'] = trim((string)$_POST['pass']);

$adminMask = substr($_POST['user'], 0, 6) == 'admin|';
if ( $adminMask ) {
	$mask = substr($_POST['user'], 6);
	$_POST['user'] = 'admin';
}

adesk_loginsource_sync();
$source = adesk_loginsource_determine($_POST['user'], $_POST['pass'], 1);

if ($source !== false) {
	$GLOBALS["loginsource"] = new $source["_classname"]($source);
} else {
	die("This should never happen.");
}


// Preload the language file
adesk_lang_get('admin');


/*
Check for numerous login failures
*/
$ip = adesk_sql_escape($_SERVER['REMOTE_ADDR']);
$attempts = (int)adesk_sql_select_one("=COUNT(*)", "#user_b_log", "ip = '$ip' AND CONCAT(`date`, ' ', `time`) >= SUBDATE(NOW(), INTERVAL 5 MINUTE)");
if ( $attempts > 3 ) {
	// too many login attempts (more than 3) in last 5 minutes!

// Smarty Template system setup
$smarty = new adesk_Smarty('admin');


require_once(awebdesk_functions('browser.php'));
$smarty->assign('ieCompatFix', adesk_browser_ie_compat());




// pass main info to smarty
$smarty->assign('site', $site);
$smarty->assign('jsSite', adesk_array_keys_remove($site, array('serial', 'av', 'avo', 'ac', 'smpass')));
$smarty->assign('admin', $admin);
$smarty->assign('jsAdmin', adesk_array_keys_remove($admin, array('password')));
$smarty->assign('languages', $languages);

$smarty->assign('thisURL', adesk_http_geturl());
$smarty->assign('plink', adesk_site_plink());

$smarty->assign('action', 'login');

	$template = 'index.failed.htm';
	$smarty->assign('content_template', $template);
	// loading the main template
	$smarty->display('index.htm');
	exit;
}





$user	= $_POST['user'];
//$pass	= md5(smart_escape($_POST['pass']));
$pass	= $_POST['pass'];


$landingPage = '';
if ( isset($_POST['idt']) ) {
	if ( $_POST['idt'] != '' ) {
		$idt = adesk_b64_decode($_POST['idt']);
		if ( substr($idt, 0, 4) == 'http' ) $landingPage = $idt;
	}
}

$authenticated = adesk_auth_login_plain($user, $pass, isset($_POST['rm']));
//dbg($_POST);


if ( $authenticated ) {
	if ( $adminMask ) {
		// log in as this user instead
		$maskesc = adesk_auth_escape($mask);
		$pass2 = adesk_sql_select_one('password', 'aweb_globalauth', "`username` = '$maskesc'", true);
		adesk_auth_logout();
		unset($admin);
		adesk_session_drop_cache();
		$authenticated = adesk_auth_login_md5($masksc, $pass2, false);
		if ( !$authenticated ) {
			//Setting error message
			$addOn = ( $landingPage != '' ? '&redir=' . adesk_b64_encode($landingPage) : '' );
			do_redirect('index.php?error_mesg=invalidlogin1' . $addOn);
		}
	}
	adesk_session_drop_cache();
	unset($admin);
	$admin = adesk_admin_get();
	$localID = $admin['id'];

 

	// Update database with current date/time for tracking of users last login
	adesk_sql_update_one("#user", "=last_login", "NOW()", "id = '$localID'");
	adesk_sql_update("#user", array("=ldate" => "NOW()", "=ltime" => "NOW()"), "id = '$localID'");
	mysql_query("UPDATE aweb_globalauth SET last_login = NOW() WHERE id = '$admin[absid]'", $GLOBALS['auth_db_link']);
 


	// clear cache
	$_SESSION['_cached'] = null;
	
	// Redirecting
	do_redirect(( $landingPage != '' ? $landingPage : 'desk.php' ));
// User or pass is invalid
} else {
	//logging this attempt
	$host = @gethostbyaddr($ip);
	$pass = @base64_encode($pass);
	adesk_sql_query("INSERT INTO #user_b_log (user, pass, ip, host, time, date) VALUES ('$user', '$pass', '$ip', '$host', CURTIME(), CURDATE())");
	//Setting error message
	$addOn = ( $landingPage != '' ? '&redir=' . adesk_b64_encode($landingPage) : '' );
	do_redirect('index.php?error_mesg=invalidlogin2' . $addOn);
}












//REDIRECT COPIED FROM - http://groups-beta.google.com/group/comp.lang.php/browse_thread/thread/33d398d70befb36d/d1608a537399b711?q=cookie+and+header(%22Location&_done=%2Fgroups%3Fq%3Dcookie+and+header(%22Location%26&_doneTitle=Back+to+Search&&d#d1608a537399b711
function do_redirect ( $url ) {
	global $SERVER_SOFTWARE, $_SERVER;
	if ( empty ( $SERVER_SOFTWARE ) )
		$SERVER_SOFTWARE = $_SERVER["SERVER_SOFTWARE"];

	//echo "SERVER_SOFTWARE = $SERVER_SOFTWARE <BR>"; exit;

	if ( substr ( $SERVER_SOFTWARE, 0, 5 ) == "Micro" ) {
		echo "<html><HEAD><TITLE>Redirect</TITLE>" .
			 "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=$url\"></head><body>" .
			 "Redirecting to ... <a href=\"" . $url .
			 "\">here</a>.</body></html>.\n";
	} else {
		Header ( "Location: $url" );
		echo "<html><head><title>Redirect</title></head><body>" .
		"Redirecting to ... <a href=\"" . $url .
		"\">here</a>.</body></html>.\n";
	}
	exit;
}




?>
