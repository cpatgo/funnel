<?php
if (!@ini_get("zlib.output_compression")) @ob_start("ob_gzhandler");

define('AWEBVIEW', true);
define('AWEBP_USER_NOAUTH', true);

// require main include file
require_once(dirname(__FILE__) . '/manage/awebdeskend.inc.php');

# Check to see if we need to unsubscribe or confirm

// require everything we need
require_once(awebdesk_functions('assets.php'));
require_once(awebdesk_functions('smarty.php'));
require_once(awebdesk_functions('http.php'));
require_once(awebdesk_classes('page.php'));
require_once awebdesk_functions("interface.php");

adesk_interface_utfconvert_gp("utf-8");

// Preload the language file
adesk_lang_get('public');

$rss_indicator = "&rss=1";

if ( $site['general_url_rewrite'] ) {
	$rwCheck = adesk_php_rewrite_check();
	if ( $rwCheck['configured'] ) {
		require_once(adesk_admin('functions/rewrite.php'));
		rewrite_url();
		$rss_indicator = "/rss";
	} else {
		$site['general_url_rewrite'] = 0;
	}
}

// get action
$action = adesk_http_param('action');

// Smarty Template system setup
$smarty = new adesk_Smarty('public');
$smarty->assign("public", true);

if (!$action && !$site["general_public"]) {
	adesk_smarty_redirect($smarty, $site["p_link"] . "/manage/");
}

/*
if ($site["general_passprotect"] && !$admin) {
	$action = "login";
}
*/



/*
	check if real url is the same as settings url
*/
// get settings url
$confurl  = adesk_site_plink();
// get real url
$realurl = $fullurl = adesk_http_geturl();
//$realurl = $fullurl = str_replace('msrdjevic', 'localhost', adesk_http_geturl());
// clean up real url to get base url
$realurl = preg_replace('/\/index.php(.*)$/', '', $realurl);
$realurl = preg_replace('/(\/\?.*)$/', '', $realurl);
// if base urls do not match
if ( rtrim($confurl, '/') != substr(rtrim($realurl, '/'), 0, strlen(rtrim($confurl, '/'))) ) {
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


foreach ( $languages as $k => $v ) {
	if ( adesk_str_instr('(partial)', strtolower($k)) ) {
		unset($languages[$k]);
	}
}


// dealing with error messages
$mesgcode = '';
// get error message if defined
if ( isset($_GET['mesgcode']) ) $mesgcode = $_GET['mesgcode'];
$smarty->assign('mesgcode', $mesgcode);

# Check to see if we allow you to go to the registration assets while pass-protected

#if ($site["admin_public_registration"])
#	$preg = '/^login|register$/';
#else
	$preg = '/^login$/';

// pass main info to smarty
if(($site["acpow"])){ $site["acpow"] = base64_decode($site["acpow"]); } else { $site["acpow"] = 'Email Marketing'; }

$smarty->assign("is_admin", adesk_admin_isadmin());
$smarty->assign("is_protected", $site["general_passprotect"] && !adesk_admin_isuser() && !adesk_admin_isadmin());
$smarty->assign("protected_action_ok", preg_match($preg, $action));


$smarty->assign_by_ref('site', $site);
$smarty->assign_by_ref('admin', $admin);
$smarty->assign_by_ref('languages', $languages);
$smarty->assign('plink', adesk_site_plink());
$smarty->assign('thisURL', adesk_http_geturl());
$smarty->assign('thisURLenc', adesk_b64_encode(adesk_http_geturl()));
$smarty->assign('action', $action);
$smarty->assign("rss_indicator", $rss_indicator);
$selected = 'style="background:url(\'images/nav_selected_bg.gif\'); background-repeat: repeat-x; background-position: top; background-color:#fff;" class="selected"';
$smarty->assign("selected", $selected);

$smarty->assign("confirmation", isset($_GET['codes']));


$smarty->assign('__ishosted', isset($GLOBALS['_hosted_account']));


/*
	LIST FILTERS
	can filter by: campaign, list(s), user, group
*/
if ( adesk_http_param_exists('c') ) {
	$campaignid = (int)adesk_http_param('c');
	$_SESSION['nlp'] = adesk_sql_select_list("SELECT listid FROM #campaign_list WHERE campaignid = '$campaignid'");
	if (adesk_http_param_exists('nl')) {
		$xnl = (int)adesk_http_param('nl');
		if(!$_SESSION['nlp']) $_SESSION['nlp'] = array();
		if($xnl && in_array($xnl, $_SESSION['nlp']))	$_SESSION['nlp'] = $xnl;
	}
} elseif ( adesk_http_param_exists('ul') ) {
	$id = (int)adesk_http_param('ul');
	if ( $id ) {
		$list = adesk_sql_select_list("SELECT id FROM #list WHERE userid = '$id'");
		if ( $list ) $_SESSION['nlp'] = $list;
	}
} elseif ( adesk_http_param_exists('gl') ) {
	$id = (int)adesk_http_param('gl');
	if ( $id > 2 ) {
		$list = adesk_sql_select_list("
			SELECT
				l.id
			FROM
				#list l,
				#user_group u
			WHERE
				u.groupid = '$id'
			AND
				u.userid = l.userid
		");
		if ( $list ) $_SESSION['nlp'] = $list;
	}
} elseif ( adesk_http_param_exists('nl') ) {
	$_SESSION['nlp'] = (int)adesk_http_param('nl');
}
require(adesk_admin('functions/inc.branding.public.php'));
// apply design
require_once(adesk_admin('functions/design.php'));
$admin = design_template_personalize($smarty, $admin, 'public');
//dbg($site,1);dbg($admin);

$nl = ( isset($_SESSION['nlp']) ? $_SESSION['nlp'] : null );
$lists = list_get_all(true, false, $_SESSION['nlp']);
$listsCnt = count($lists);
$smarty->assign('listsList', $lists);
$smarty->assign('listsListCnt', $listsCnt);
$smarty->assign('nl', $nl);


# $_ will be shorthand for $site.p_link

$smarty->assign("_", $site["p_link"]);
$smarty->assign("seo_url_prefix", ( isset($GLOBALS['seo_url_prefix']) ? $GLOBALS['seo_url_prefix'] : '' ));

require_once(awebdesk_functions('browser.php'));
$smarty->assign('ieCompatFix', adesk_browser_ie_compat());

$processor = adesk_assets_find($smarty, $action, false);
$processor->process($smarty);

$smarty->assign('action', $action);
$smarty->assign('demoMode', isset($demoMode));

$template = 'main.htm';
if ( in_array($action, array('approve', 'complaint', 'social')) ) $template = 'iframe.main.htm';
if ( isset($_GET['lists']) and isset($_GET['codes']) ) $template = 'iframe.main.htm';
$smarty->display($template);

assets_complete($site);

?>
