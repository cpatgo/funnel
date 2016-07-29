<?php

if (!@ini_get("zlib.output_compression")) @ob_start("ob_gzhandler");

// require main include file
require_once(dirname(__FILE__) . '/awebdeskend.inc.php');
require_once(awebdesk_functions('smarty.php'));
require_once(awebdesk_functions('emailawebview.php'));
require_once(adesk_admin('functions/campaign.send.php'));
require_once(awebdesk("scripts/emailawebview.php"));

/*
	== permission checks go here! ==
*/
if ( !adesk_admin_isadmin() ) {
	echo 'You are not logged in.';
	exit;
}

// Preload the language file
adesk_lang_get('admin');

// collect input
$cid = (int)adesk_http_param('c');
$mid = (int)adesk_http_param('m');
//$sid = (int)adesk_http_param('s');

$parsedcampaign = null;
$source = null;
$r = null;
$html = '';
$html_modified = '';

$campaign_row = adesk_sql_select_row("SELECT * FROM #campaign WHERE id = '$cid' LIMIT 1");
$message_row = adesk_sql_select_row("SELECT * FROM #message WHERE id = '$mid' LIMIT 1");

$GLOBALS["emailpreview_clients2check"] = array("msoutlook2007", "msoutlook2000_2003", "googlegmail", "mshotmail", "yahoomail", "applemail", "lotusnotes85", "applemail2");

// default client
$client_filter = "msoutlook2007";

$show = adesk_http_param('showhtml');

if ( $show && isset($_SESSION['emailpreview_html_modified']) && isset($_SESSION['emailpreview_html_modified'][$show]) ) {
	echo ($campaign_row["type"] == "text") ? nl2br($_SESSION['emailpreview_html_modified'][$show]) : $_SESSION['emailpreview_html_modified'][$show];
	exit;
}


$email = (string)trim(adesk_http_param('email'));
if ( !adesk_str_is_email($email) ) $email = _a('_t.e.s.t_@example.com');

// check if currently saved message ID is different from the one they chose - for example, editing an autoresponder campaigns' message.
// if so, campaign_quick_send will fail to find anything, so push into the campaign_temp_send section
if ($cid && $cid != -1) {
	$oldm = adesk_sql_select_list("SELECT messageid FROM #campaign_message WHERE campaignid = '$cid'");
	if (!in_array($mid, $oldm)) $cid = 0;
}

if ( $cid and $cid != -1 ) {
	$r = campaign_quick_send($email, $cid, $mid, 'mime', 'preview');
} elseif ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	if ( $cid == -1 ) {
		$r = campaign_quick_send(
			$email,
			-1,
			0, //$mid,
			'html',
			'preview' // call spamcheck to get message source that we can parse
		);
	} else {
		$r = campaign_temp_send(
			$email,
			$mid,
			'html',
			'preview' // call spamcheck to get message source that we can parse
		);
	}
}

if ( !is_null($r) ) {
	if ( !is_array($r) ) {
		// get message structure
		$source = $r;
		$structure = adesk_mail_extract($source);
		if ( $structure ) {
			$filter = array(
				'subject',
				'body',
				'parts',
				'ctype',
				'charset',
				//'from',
				'from_name',
				'from_email',
				//'to',
				'to_email',
				'to_name',
				'attachments',
				//'structure',
			);
			$parsedcampaign = adesk_mail_extract_components($structure, $filter);

			# The contents of adesk_mail_extract_components must be encoded for the current
			# page.  They're not necessarily in UTF-8, either; they'll be encoded in whatever
			# the original message was configured with.  We need to make sure everything
			# lines up or the message here will not display correctly.
			$parsedcampaign["subject"] = adesk_utf_conv($parsedcampaign["charset"], _i18n("utf-8"), $parsedcampaign["subject"]);

			if (isset($parsedcampaign["parts"]["html_charset"])) {
				$parsedcampaign["parts"]["html"] = adesk_utf_conv($parsedcampaign["parts"]["html_charset"], _i18n("utf-8"), $parsedcampaign["parts"]["html"]);
			}

			$parsedcampaign["parts"]["html"] = adesk_str_strip_tag_short($parsedcampaign["parts"]["html"], 'meta');

			// parse the content
			$html = ($campaign_row["type"] == "text") ? nl2br($parsedcampaign["parts"]["text"]) : $parsedcampaign["parts"]["html"];
			if ($message_row["htmlfetch"] == "send") $html = adesk_str_strip_malicious($html);
			adesk_emailpreview_check($html);

			// save the content that will be shown
			$_SESSION['emailpreview_html_modified'] = array();

			// now extract all client's result outputs
			$firstWithIssues = null;
			foreach ( $GLOBALS["emailpreview_clients2check"] as $v ) {
				$_SESSION['emailpreview_html_modified'][$v] = $GLOBALS['emailpreview_clients'][$v]["html_result"]["html_modified"];
				if ( is_null($firstWithIssues) and $GLOBALS['emailpreview_clients'][$v]['html_result']['issuescnt'] ) {
					$firstWithIssues = $v;
				}
			}

			// save the content that will be shown
			//$_SESSION['emailpreview_html_modified'] = $html_modified = $GLOBALS['emailpreview_clients'][$client_filter]["html_result"]["html_modified"];

			// force a client to be used
			//$_GET["client"] = "mshotmail";

			// get preferred client
			if ( isset($_GET["client"]) and in_array($_GET["client"], $GLOBALS["emailpreview_clients2check"]) ) {
				$client_filter = $_GET["client"];
			} elseif ( !is_null($firstWithIssues) ) {
				$client_filter = $firstWithIssues;
			}

			// save the content that will be shown on first load
			$html_modified = $_SESSION['emailpreview_html_modified'][$client_filter];
			if ($campaign_row["type"] == "text") $html_modified = nl2br($html_modified);
		}
	}
}


//dbg($GLOBALS['emailpreview_clients']);

$client_filter_name = $GLOBALS['emailpreview_clients'][$client_filter]["vendor"] . " " . $GLOBALS['emailpreview_clients'][$client_filter]["software"] . " " . $GLOBALS['emailpreview_clients'][$client_filter]["version"];


// Smarty Template system setup
$smarty = new adesk_Smarty('admin');

$smarty->assign('campaignid', $cid);
$smarty->assign('messageid', $mid);
$smarty->assign('html', adesk_str_htmlspecialchars( trim($html) ) );
$smarty->assign('html_modified', adesk_str_htmlspecialchars( trim($html_modified) ) );
$smarty->assign('clients', $GLOBALS['emailpreview_clients']);
$smarty->assign('client_filter', $client_filter);
$smarty->assign('client_filter_name', $client_filter_name);
$smarty->assign('clients2check', $GLOBALS["emailpreview_clients2check"]);
$smarty->assign('location', $GLOBALS['emailpreview_locations']);
$smarty->assign('selectors', $GLOBALS['emailpreview_selectors']);
$smarty->assign('properties', $GLOBALS['emailpreview_properties']);
$smarty->assign('campaignParsed', !is_null($r));
$smarty->assign('campaign', campaign_select_row($cid));

// assigning smarty reserved vars
$smarty->assign('site', $site);
$smarty->assign('admin', $admin);

// get page params
$smarty->assign('public', !adesk_str_instr('/manage/', $_SERVER['REQUEST_URI']));

$smarty->assign('parsedcampaign', $parsedcampaign);
$smarty->assign('source', $source);

// loading the main template
$smarty->display('emailpreview.htm');

?>
