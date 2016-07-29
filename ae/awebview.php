<?php
if (!@ini_get("zlib.output_compression")) @ob_start("ob_gzhandler");

define('AWEBVIEW', true);
if ( !isset($_GET['useauth']) ) define('AWEBP_USER_NOAUTH', true);

// require main include file
require_once('manage/awebdeskend.inc.php');
require_once adesk_admin("functions/message.php");
require_once adesk_admin("functions/campaign.php");
require_once(awebdesk_functions('ajax.php'));

// Preload the language file
adesk_lang_get( !isset($_GET['useauth']) ? 'admin' : 'public' );


$campaignid = (int)adesk_http_param('c');
$messageid = (int)adesk_http_param('m');
$hash = trim((string)adesk_http_param('s'));
$email = _a('_t.e.s.t_@example.com');
if ( $hash != '' ) {
	$subscriber = subscriber_exists($hash, 0, 'hash');
	if ( $subscriber ) {
		$email = $subscriber['email'];
	}
}

$type = trim((string)adesk_http_param('previewtype')); if ( $type != 'html' ) $type = 'text';

$attachid = (int)adesk_http_param('a');
$attachhash = trim((string)adesk_http_param('h'));

require_once adesk_admin("functions/campaign.php");
$campaign = campaign_select_row($campaignid, true, true, true);

/*
// If the campaign is private, and it's being viewed on the public side
if ( !(int)$campaign["public"] && !isset($_GET['useauth']) ) {
	$txt = ( !isset($_GET['useauth']) ? _a('Private message.') : _p('Private message.') );
	echo $txt;
	exit();
}
*/
$source = adesk_sql_select_one("SELECT id FROM #campaign_source WHERE campaignid = '$campaignid' AND messageid = '$messageid' AND type = 'preview'");
$source = campaign_source($source);

if ($source == "") {
	$source = campaign_quick_send($email, $campaignid, $messageid, "mime", 'preview'); // call spamcheck to get message source that we can parse
	if ( !is_array($source) ) {
		$in = array(
			"id" => 0,
			"campaignid" => $campaignid,
			"messageid" => $messageid,
			"type" => 'preview',
			"len" => strlen($source),
		);
		adesk_sql_insert("#campaign_source", $in);
		$sourceid = (int)adesk_sql_insert_id();

		campaign_source_save($sourceid, $source, $in["len"]);
	}
}

if ( is_array($source) ) {
	// handle error here; this is ajax_result array in this case
	echo $source['message'];
	exit;
}

if (isset($_GET['useauth']) && isset($_GET["source"]) && $_GET["source"] == 1) {
	echo "<pre>$source</pre>";
	exit;
}

// get message structure
$structure = adesk_mail_extract($source);
if ( !$structure ) {
	$txt = ( !isset($_GET['useauth']) ? _a('Message could not be previewed.') : _p('Message could not be previewed.') );
	$error = adesk_ajax_api_result(false, $txt);
	// handle error here; this is ajax_result array in this case (or doesn't have to be)
	echo $error['message'];
	exit;
}

// we need these items from the email source
$filter = array(
	'subject',
	//'body',
	'parts',
	//'ctype',
	'from',
	//'from_name',
	//'from_email',
	'to',
	//'to_email',
	//'to_name',
	'attachments',
	//'structure',
);
$r = adesk_mail_extract_components($structure, $filter);
//$r['from'] = htmlentities($r['from']);
$r['from'] = str_replace('<', '&lt;', $r['from']);
$r['from'] = str_replace('>', '&gt;', $r['from']);
$r['to'] = str_replace('<', '&lt;', $r['to']);
$r['to'] = str_replace('>', '&gt;', $r['to']);

// this array should have only 'parts' element, that has element we need
if ( !isset($r['parts'][$type]) or !$r['parts'][$type] ) {
	// handle error here
	$txt = ( !isset($_GET['useauth']) ? _a('Message type not found.') : _p('Message type not found.') );
	echo $txt;
	exit;
}

// attachment download check
if ( adesk_http_param_exists('a') and isset($r['attachments'][$attachid]) ) {
	$attach = $r['attachments'][$attachid];
	$filehash = md5($campaignid . $attachid . $attach['name'] . $attach['size']);
	if ( $filehash != $attachhash ) {
		// handle error here
		$txt = ( !isset($_GET['useauth']) ? _a('Attachment not found.') : _p('Attachment not found.') );
		echo $txt;
		exit;
	}
	// produce the attachment
	adesk_http_header_attach($attach['name'], $attach['size']);
	echo $attach['data'];
	exit;
}


$fromLabel    = ( !isset($_GET['useauth']) ? _a('From:') : _p('From:') );
$toLabel      = ( !isset($_GET['useauth']) ? _a('To:') : _p('To:') );
$subjectLabel = ( !isset($_GET['useauth']) ? _a('Subject:') : _p('Subject:') );
$attachLabel  = ( !isset($_GET['useauth']) ? _a('Attachments:') : _p('Attachments:') );


$charset = $r['parts'][$type.'_charset'];
if ( !$charset ) $charset = _i18n('utf-8');
//$mimetype = $type == 'text' ? 'plain' : 'html';
header("Content-Type: text/html; charset=$charset");

if ( $type == 'text' ) {
/*
	//header('Content-Type: text/plain');

	header("Pragma: private");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	header("Content-Type: text/plain");
	header("Content-Disposition: inline");
	//header("Content-Transfer-Encoding:­ binary");
	header("Content-Length: " . strlen($r['parts'][$type]));
*/
	echo '<style>body, p, div, td, li { font-family: "Courier New", Courier; font-size: 12px; }</style>';
	$r['parts'][$type] = nl2br(str_replace(' ', '&nbsp;', $r['parts'][$type]));

	echo "$fromLabel $r[from]<br />";
	echo "$toLabel $r[to]<br />";
	echo "$subjectLabel $r[subject]<br />";
	if ( count($r['attachments']) ) {
		echo "$attachLabel";
		foreach ( $r['attachments'] as $k => $v ) {
			$filehash = md5($campaignid . $k . $v['name'] . $v['size']);
			//$link = sprintf('download.php?c=%s&a=%s&h=%s', $campaignid, $k, $filehash);
			//$link = sprintf('preview.php?c=%s&m=%s&s=%s&type=%s&a=%s&h=%s', $campaignid, $messageid, $hash, $type, $k, $filehash);
			//$link = $_SERVER['REQUEST_URI'] . sprintf('&a=%s&h=%s', $k, $filehash);
			$link = "$_SERVER[REQUEST_URI]&a=$k&h=$filehash";
			echo ' <a href="' . $link . '">' . $v['name'] . ' (' . adesk_file_humansize($v['size']) . ')</a>';
		}
		echo "<br />";
	}
	echo "<hr /><br />";
} elseif (!isset($_GET["s"]) && !isset($_GET["fromsocial"])) {
	# We need to print out the headers but in a div...
	echo '<div style="background:#ffffff; padding-bottom:10px; font-size:11px; font-family:Arial; color:#666666; border-bottom:1px solid #cccccc;">';
	echo "  <div>$fromLabel $r[from]</div>";
	echo "  <div>$toLabel $r[to]</div>";
	echo "  <div>$subjectLabel $r[subject]</div>";
	if ( count($r['attachments']) ) {
		echo "  <div>$attachLabel ";
		foreach ( $r['attachments'] as $k => $v ) {
			$filehash = md5($campaignid . $k . $v['name'] . $v['size']);
			//$link = sprintf('download.php?c=%s&a=%s&h=%s', $campaignid, $k, $filehash);
			//$link = sprintf('preview.php?c=%s&m=%s&s=%s&type=%s&a=%s&h=%s', $campaignid, $messageid, $hash, $type, $k, $filehash);
			//$link = $_SERVER['REQUEST_URI'] . sprintf('&a=%s&h=%s', $k, $filehash);
			$link = "$_SERVER[REQUEST_URI]&a=$k&h=$filehash";
			echo '<a href="' . $link . '" style="margin-left: 4px;">' . $v['name'] . ' (' . adesk_file_humansize($v['size']) . ')</a>';
		}
		echo "</div>";
	}
	echo "</div>";
}

if (isset($_GET["overlay"]) && $_GET["overlay"] == 1) {
	$mesg = message_select_row($messageid);
	$camp = campaign_select_row($campaignid, true, true, true);
	echo ($over = message_overlay($mesg, $r['parts'][$type], $campaignid));
	exit;
}

//dbg(headers_list(),1);

$content = $r['parts'][$type];

if (isset($GLOBALS["_hosted_account"])) {
	if (!$hash || isset($_GET["fromsocial"]))
		$content = str_replace(_a("Unsubscribe"), "", $content);
}

$content = adesk_str_strip_tag($content, 'script');
$content = adesk_str_strip_tag($content, 'applet');
$content = adesk_str_strip_tag($content, 'iframe');
$content = adesk_str_strip_tag($content, 'ilayer');
$content = adesk_str_strip_tag($content, 'layer');

echo $content;

?>
