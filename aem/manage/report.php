<?php
if (!@ini_get("zlib.output_compression")) @ob_start("ob_gzhandler");

// require main include file
require_once(dirname(__FILE__) . '/awebdeskend.inc.php');
require_once adesk_admin("functions/campaign.php");
require_once awebdesk_functions("smarty.php");
require_once awebdesk_functions("assets.php");
require_once awebdesk_classes("page.php");

// Preload the language file
adesk_lang_get('admin');

$GLOBALS["admin"] = adesk_admin_get_totally_unsafe(1);

#$listid = intval(adesk_http_param('nla'));
$campid = intval(adesk_http_param("ca"));
$mesgid = intval(adesk_http_param("mi"));
$hash   = strval(adesk_http_param("hash"));
$email  = strval(adesk_http_param("email"));
$action = strval(adesk_http_param("action"));

# Backwards compatibility; treat campaigns as messages.
if ($campid == 0)
	$campid = $mesgid;

$campaign = campaign_select_row($campid);

if (!$campaign) {
	echo _a("Improper Shared Report usage.");
	exit;
}

if (count($campaign["lists"]) < 1) {
	echo _a("Improper Shared Report campaign.");
	exit;
}

$listid = $campaign["lists"][0]["id"];

if ($action == "")
	$action = "report_campaign";

if (!$listid || !$campid || !$hash || !$email) {
	header("Location: desk.php");
	exit;
}

$ourhash = awebdesk_reporthash($campid, $listid, $email);

if ($hash != $ourhash) {
	header("Location: desk.php");
	exit;
}

if ( !isset($_SESSION["awebdesk_sharedreport_hashes"]) ) $_SESSION["awebdesk_sharedreport_hashes"] = array();
$_SESSION["awebdesk_sharedreport_hashes"][$ourhash] = $campid;

$_GET["action"] = $action;
$_GET["id"]     = $campid;

# Smarty

$smarty = new adesk_Smarty("admin");

# Don't show the dropdown list filter.
$smarty->assign("uselistfilter", 0);
$smarty->assign("usemainmenu", 0);
$smarty->assign("usehelplink", 0);
$smarty->assign("usesharelink", 0);
$smarty->assign("useacctlinks", 0);
$smarty->assign("useresendlink", 0);

# A fair amount of the following code was borrowed from desk.php.
$smarty->assign('pageTitle', _i18n("Administration Home Page - Your AEM"));
$header_lines = array(
	'<style type="text/css">{literal}body { background: white !important; padding: 20px !important; } #adesk_loading_text, #adesk_result_text, #adesk_error_text { padding: 0 8px 3px 8px !important; }{/literal}</style>',
	'<meta name="robots" content="noindex, nofollow" />',
);
$smarty->assign('header_lines', $header_lines);
$smarty->assign('nla', $listid);
$smarty->assign('nl', $listid);



if ( !isset($_SESSION['_adesk_disablespawning']) ) $_SESSION['_adesk_disablespawning'] = 0;
$smarty->assign("close2limit", 0);
$smarty->assign("close2subscriberlimit", 0);
$smarty->assign("close2maillimit", 0);
$smarty->assign("__ishosted", isset($GLOBALS['_hosted_account']));
$smarty->assign("abusers", array());
$smarty->assign("abuserscnt", 0);
$smarty->assign("approvals", array());
$smarty->assign("approvalscnt", 0);
$smarty->assign("down4maint", $site['general_maint']);
$smarty->assign("canAddSubscriber",1);
$smarty->assign("canAddSubscriberHosted",1);
$smarty->assign("canSendCampaignHosted",1);
$smarty->assign("hosted_down4", (isset($GLOBALS['_hosted_account']) ? $_SESSION[$GLOBALS["domain"]]["down4"] : 'nobody'));
$smarty->assign("hostedaccount", (isset($GLOBALS['_hosted_account']) ? $_SESSION[$GLOBALS["domain"]] : false));
$smarty->assign("creditbased", 0);
$smarty->assign("admin_limit_subscriber", 0);
$smarty->assign("admin_limit_mail", 0);

require_once(awebdesk_functions('browser.php'));
$smarty->assign('ieCompatFix', adesk_browser_ie_compat());

$processor = adesk_assets_find($smarty, $action, true);
$processor->process($smarty);

# Disable side menu for shared reports.  This needs to go after the process method call, which is
# what actually assigns this variable.
$smarty->assign("side_content_template", "");

$smarty->assign("isShared", true);

# Change the title to include the campaign name.
$smarty->assign("pageTitle", $campaign["name"] . " " . _a("Campaign Report"));

$smarty->assign("style_list", "");
$smarty->assign("style_subscriber", "");
$smarty->assign("style_campaign", "");
$smarty->assign("style_integration", "");
$smarty->assign("style_reports", "");
$smarty->assign("style_settings", "");

$selected = 'style="background:url(images/manage_nav_bg.gif);"';

# Set the menu classes
switch ($action) {
	case "list":
	case "list_field":
	case "subscriber_action":
	case "header":
	case "filter":
	case "emailaccount":
	case "optinoptout":
	case "bounce_management":
		$smarty->assign("style_list", $selected);
		break;

	case "subscriber":
	case "subscriber_import":
	case "subscriber_view":
	case "exclusion":
	case "personalization":
	case "batch":
		$smarty->assign("style_subscriber", $selected);
		break;

	case "campaign_new":
	case "campaign":
	case "message":
	case "template":
		$smarty->assign("style_campaign", $selected);
		break;

	case "form":
		$smarty->assign("style_integration", $selected);
		break;

	case "report_list":
	case "report_user":
	case "report_campaign":
	case "report_trend_read":
	case "report_trend_client":
		$smarty->assign("style_reports", $selected);
		break;

	case "settings":
	case "user":
	case "user_field":
	case "group":
	case "loginsource":
	case "processes":
	case "sync":
	case "database":
	case "cron":
	case "about":
		$smarty->assign("style_settings", $selected);
		break;

	default:		# No menu should be selected
		break;
}

// setting the KB Help site name
$site["site_name_kb"] = base64_encode($site["site_name"]);

// pass main info to smarty
$smarty->assign('site', $site);
$smarty->assign('admin', $admin);
$smarty->assign('languages', $languages);

$smarty->assign('action', $action);
$smarty->assign('thisURL', adesk_http_geturl());
$smarty->assign('plink', adesk_site_plink());

require_once adesk_admin('functions/versioning.php');
$smarty->assign('build', $thisBuild);
$smarty->assign('demoMode');

if (isset($_GET["print"]) && $_GET["print"] == 1)
	$smarty->display("printmain.htm");
else
	$smarty->display('main.htm');

?>
