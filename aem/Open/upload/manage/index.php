<?php

if (!@ini_get("zlib.output_compression")) @ob_start("ob_gzhandler");

define('AWEBVIEW', true);


// require main include file
require_once(dirname(__FILE__) . '/awebdeskend.inc.php');

// require template engine
require_once(awebdesk_functions('smarty.php'));

/*
	DEBUGGING AREA
*/
adesk_interface_debug();

if ( adesk_http_param_exists('disablespawning') ) $_SESSION['_adesk_disablespawning'] = (int)adesk_http_param('disablespawning');
if ( !isset($_SESSION['_adesk_disablespawning']) ) $_SESSION['_adesk_disablespawning'] = 0;



// get action
$action = adesk_http_param('action');

if (isset($GLOBALS['_hosted_account']) && $_SESSION[$GLOBALS["domain"]]["down4"] == "reverify") {
	if ($action != "reverify")
		$action = "reverify";
}

// admin check
if ( $action == 'logout' and adesk_admin_isauth() ) {
	adesk_auth_logout();
	unset($admin);


//Version check new in AEM 6.3.0
		require_once(adesk_admin('functions/versioning.php'));
		$vapiurl = "http://customers.awebdesk.com/api/index.php?m=license&q=get_license_info&api_key=6512bd43d9caa6e02c990b0a82652dca&php=y";
		$phpv=@file_get_contents($vapiurl);
        $aemversion=unserialize($phpv);
		$latestversion =   $aemversion['version_decimal'];
		$curversion = $thisUpdater[0];
if($latestversion>$curversion) {
// if(!file_exists(adesk_basedir() . '/cache/newversion.txt')) {			  
$content = $latestversion;
$fp = fopen(adesk_basedir() . "/cache/newversion.txt","wb");
fwrite($fp,$content);
fclose($fp);
//}
}
///version check finishes . Now carry on as normal.



	if (isset($_SESSION["Aawebdesk_subscriber_status"]))
			unset($_SESSION["Aawebdesk_subscriber_status"]);

	$admin = adesk_admin_get();
	foreach ( $admin as $k => $v ) {
		if ( substr($k, 0, 6) == 'brand_' ) $site[$k] = $v;
	}
	$site['site_name'] = $site['brand_site_name'] = $admin['brand_site_name'];
	$site['site_logo'] = $site['brand_site_logo'] = $admin['brand_site_logo'];
	$_GET['error_mesg'] = 'logout';
}

if ( adesk_admin_isadmin() ) {
	header('Location: desk.php');
	exit;
}



// Preload the language file
adesk_lang_get('admin');




// Smarty Template system setup
$smarty = new adesk_Smarty('admin');



/*
	check if real url is the same as settings url
*/
// get settings url
$confurl  = adesk_site_plink();
// get real url
$realurl = $fullurl = adesk_http_geturl();
//$realurl = $fullurl = str_replace('msrdjevic', 'localhost', adesk_http_geturl());
// clean up real url to get base url
$realurl = preg_replace('/\/manage\/index.php(.*)$/', '', $realurl);
$realurl = preg_replace('/\/manage\/(.*)$/', '', $realurl);
$realurl = preg_replace('/(\/\?.*)$/', '', $realurl);
// if base urls do not match
if ( rtrim($realurl, '/') != rtrim($confurl, '/') ) {
	// and haven't redirected cuz of this already
	if ( !adesk_http_param('domainredirect') ) {
		// replace the real url with settings one
		$redirect2  = $confurl . str_replace($realurl, '', $fullurl);
		// add redirect mark
		$redirect2 .= ( adesk_str_instr('?', $redirect2) ? '&' : '?' );
		$redirect2 .= 'domainredirect=1';
		// and redirect
		adesk_http_redirect($redirect2);
	} else {
		// already redirected due to bad server url configuration, complain?
		echo '<div style="background:#333333; color:#ffffff; padding:10px; font-size:11px; font-weight:bold;">';
		echo 'There appears to be a problem with your server.  ';
		echo 'Your server reports ' . $realurl . ' as the application URL while you are accessing it using ' . $confurl;
		echo '</div>';
	}
}

// browser check for login screen: check if < IE8
$ua = ( isset($_SERVER['HTTP_USER_AGENT']) ? (string)$_SERVER['HTTP_USER_AGENT'] : '' );
$browser_alert = false;
$browser_version = false;
 



// dealing with error messages
$error_mesg = '';
// get error message if defined
if ( isset($_GET['error_mesg']) ) $error_mesg = $_GET['error_mesg'];
$smarty->assign('error_mesg', $error_mesg);


// pass main info to smarty
if(($site["acpow"])){ $site["acpow"] = base64_decode($site["acpow"]); } else { $site["acpow"] = 'Email Marketing'; }

$smarty->assign('site', $site);
$smarty->assign('admin', $admin);
$smarty->assign('languages', $languages);

$smarty->assign('thisURL', adesk_http_geturl());
$smarty->assign('plink', adesk_site_plink());

$smarty->assign('action', $action);

$smarty->assign('__ishosted', isset($GLOBALS['_hosted_account']));
$smarty->assign("hostedaccount", (isset($GLOBALS['_hosted_account']) ? $_SESSION[$GLOBALS["domain"]] : false));

// set default template if no action found
$template = 'index.login.htm';

require_once(awebdesk_functions('browser.php'));
$smarty->assign('ieCompatFix', adesk_browser_ie_compat());

// actions switch
if ( $action == 'account_lookup' ) {
	// if data is posted, try to find a user
	if ( $_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['user']) ) {
		$message = adesk_admin_lookup($_POST['user'], $_POST['email']);
		$smarty->assign('message', $message);
		$template = 'index.message.htm';
	} elseif ( isset($_GET['r']) and isset($_GET['r2']) ) {
		$message = adesk_admin_resetpass($_GET['r'], $_GET['r2']);
		$smarty->assign('message', $message);
		$template = 'index.message.htm';
	} else {
		$template = 'index.lookup.htm';
	}
// else: action is login form
} else {
	// pass vars needed for hidden fields
	$idt = isset($_GET['redir']) ? $_GET['redir'] : '';
	$smarty->assign('idt', $idt);
}



// assign inner template filename to smarty
$smarty->assign('content_template', $template);

/*
	DEMO SWITCH
*/
$smarty->assign('demoMode', isset($demoMode));

// loading the main template
$smarty->display('index.htm');

assets_complete($site);

?>