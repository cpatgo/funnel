<?php
if (!@ini_get("zlib.output_compression")) @ob_start("ob_gzhandler");

// require main include file
require_once(dirname(__FILE__) . '/awebdeskend.inc.php');
require_once(awebdesk_functions('smarty.php'));

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

$campaign_type = adesk_sql_select_one("SELECT type FROM #campaign WHERE id = '$cid' LIMIT 1");

$email = ( isset($_GET['email']) ? $_GET['email'] : '' );
if ( !adesk_str_is_email($email) ) $email = _a('_t.e.s.t_@example.com');

if (!$mid)
	$mid = (int)adesk_sql_select_one("SELECT id FROM #campaign_message WHERE campaignid = '$cid' LIMIT 1");

$r = adesk_sql_select_one("SELECT id FROM #campaign_source WHERE campaignid = '$cid' AND messageid = '$mid' AND type = 'preview'");
$r = campaign_source($r);
if ($r == "") {
	$r = campaign_quick_send($email, $cid, $mid, 'mime', 'preview');
	if ( !is_array($r) ) {
		$in = array(
			"id" => 0,
			"campaignid" => $cid,
			"messageid" => $mid,
			"type" => 'preview',
			"len" => strlen($r),
		);
		adesk_sql_insert("#campaign_source", $in);
		$sourceid = (int)adesk_sql_insert_id();

		campaign_source_save($sourceid, $r, $in["len"]);
	}
}
if ( !is_array($r) ) {
	// get message structure
	$source = $r;
	$structure = adesk_mail_extract($source);
	if ( $structure ) {
		$filter = array(
			'subject',
			//'body',
			'parts',
			'ctype',
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
		foreach ( $parsedcampaign['attachments'] as $k => $v ) {
			$filehash = md5($cid . $k . $v['name'] . $v['size']);
			//$link = sprintf('download.php?c=%s&a=%s&h=%s', $cid, $k, $filehash);
			//$link = sprintf('awebview.php?c=%s&m=%s&s=%s&type=%s&a=%s&h=%s', $cid, $messageid, $hash, $type, $k, $filehash);
			//$link = $_SERVER['REQUEST_URI'] . sprintf('&a=%s&h=%s', $k, $filehash);
			$link = "$_SERVER[REQUEST_URI]&a=$k&h=$filehash";
			$parsedcampaign['attachments'][$k]['hash'] = $filehash;
			$parsedcampaign['attachments'][$k]['link'] = $link;
		}
	}
}



// Smarty Template system setup
$smarty = new adesk_Smarty('admin');



// assigning smarty reserved vars
$smarty->assign('site', $site);
$smarty->assign('admin', $admin);

// get page params
$smarty->assign('public', !adesk_str_instr('/manage/', $_SERVER['REQUEST_URI']));

$smarty->assign('campaign_type', $campaign_type);
$smarty->assign('parsedcampaign', $parsedcampaign);
$smarty->assign('source', $source);

$smarty->assign("campaignid", $cid);
$smarty->assign("messageid", $mid);

// Just assign the campaign already.
$campaign = campaign_select_row($cid);
$smarty->assign("campaign", $campaign);

require_once(awebdesk_functions('browser.php'));
$smarty->assign('ieCompatFix', adesk_browser_ie_compat());

// loading the main template
$smarty->display('iframe.preview.htm');

?>
