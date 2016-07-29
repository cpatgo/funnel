<?php

require_once adesk_admin("functions/list.php");
require_once awebdesk_functions("log.php");

// Email to subscriber or unsubscriber (optin or out)
function mail_opt_send($subscriber, $list, $listids, $subscription_form_id, $opt_info = null, $direction = 'in') {

	if ( isset($GLOBALS['demoMode']) ) return; // check if demo mode is on
	require_once(adesk_admin('functions/optinoptout.php'));
	if ( $direction != 'out' ) $direction = 'in';
	if ( !$opt_info ) $opt_info = $list;
	if ( !$opt_info['opt' . $direction . '_confirm'] ) return;

	$admin = adesk_admin_get();
	$site = adesk_site_get();

	$options = array();

	$options['userid'] = $list['userid'];

	if ( !isset($opt_info['opt' . $direction . '_files']) ) {
		$opt_info = optinoptout_select_prepare($opt_info);
	}
	$options['attach'] = optinoptout_attachments($opt_info['opt' . $direction . '_files']);
	//$options['reply2'] = $list['reply2'];
	//$options['charset'] = $list['charset'];
	//$options['encoding'] = $list['encoding'];
	$options['charset'] = _i18n('utf-8');
	$options['encoding'] = _i18n('quoted-printable');

	// Bounces
	$options['bounce'] = adesk_sql_select_one("email", "#bounce", "id = 1");
	$bso = new adesk_Select();
	if (!isset($GLOBALS["_hosted_account"])) {
		$sqlids = str_replace(",", "','", $listids);
		$bso->push("AND l.listid IN ('$sqlids')");
	}
	require_once adesk_admin("functions/campaign.select.php");
	$bounces = campaign_list_bounces($bso);
	if ( $bounces ) {
		$randombounce = array_rand($bounces);
		$options['bounce'] = $randombounce['email'];
	} else {
		$randombounce = adesk_sql_select_row("SELECT * FROM #bounce b, #bounce_list l WHERE b.id = l.bounceid AND l.listid = '$list[id]'");
		if ( $randombounce ) $options['bounce'] = $randombounce['email'];
	}
	if ( isset($GLOBALS['_hosted_account']) ) {
		$options['bounce'] = str_replace("@", "-0@", $options['bounce']);
	}

	// Headers
	$hso = new adesk_Select();
	$hso->push("AND l.listid IN (" . $list['id'] . ")");
	$options['headers'] = campaign_list_headers($hso);

	$header = $footer = $header_alt = $footer_alt = "";

	// Format
	if ($opt_info["opt" . $direction . "_format"] == "mime") {
		$header_alt = ($admin["brand_header_text"]) ? $admin["brand_header_text_value"] : "";
		$altBody = $header_alt . $opt_info["opt" . $direction . "_text"] . $footer_alt;
		$footer_alt = ($admin["brand_footer_text"]) ? $admin["brand_footer_text_value"] : "";
		if ( isset($GLOBALS['__hosted_footer_text']) ) {
			$tmpcontent = $header_alt . $altBody . $footer_alt;
			$unsubLink = adesk_site_plink('surround.php?nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid&funcml=unsub2');
			//$abuseLink = adesk_site_plink('index.php?action=abuse&nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid');
			$hasUnsub = adesk_str_instr($unsubLink, $tmpcontent);
			//$hasAbuse = adesk_str_instr($abuseLink, $tmpcontent);
			$hasSender = adesk_str_instr('%SENDER-INFO%', $tmpcontent);
			if ( !$hasSender or !$hasUnsub ) {
				$footer_alt .= hosted_footer_personalize($GLOBALS['__hosted_footer_text']);
			}
		}
		$options['altBody'] = subscriber_personalize($subscriber, $listids, $subscription_form_id, $altBody, ( $direction == 'in' ? 'sub' : 'unsub' ));

		$header = ($admin["brand_header_html"]) ? $admin["brand_header_html_value"] : "";
		$body = $opt_info["opt" . $direction . "_html"];
		$footer = ($admin["brand_footer_html"]) ? $admin["brand_footer_html_value"] : "";
		if ( isset($GLOBALS['__hosted_footer_html']) ) {
			$tmpcontent = $header . $body . $footer;
			$unsubLink = adesk_site_plink('surround.php?nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid&funcml=unsub2');
			//$abuseLink = adesk_site_plink('index.php?action=abuse&nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid');
			$hasUnsub = adesk_str_instr($unsubLink, $tmpcontent);
			//$hasAbuse = adesk_str_instr($abuseLink, $tmpcontent);
			$hasSender = adesk_str_instr('%SENDER-INFO%', $tmpcontent);
			if ( !$hasSender or !$hasUnsub ) {
				$footer .= hosted_footer_personalize($GLOBALS['__hosted_footer_html']);
			}
		}
	}
	elseif ($opt_info["opt" . $direction . "_format"] == "text") {
		$header = ($admin["brand_header_text"]) ? $admin["brand_header_text_value"] : "";
		$body = $opt_info["opt" . $direction . "_text"];
		$footer = ($admin["brand_footer_text"]) ? $admin["brand_footer_text_value"] : "";
		if ( isset($GLOBALS['__hosted_footer_text']) ) {
			$tmpcontent = $header . $body . $footer;
			$unsubLink = adesk_site_plink('surround.php?nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid&funcml=unsub2');
			//$abuseLink = adesk_site_plink('index.php?action=abuse&nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid');
			$hasUnsub = adesk_str_instr($unsubLink, $tmpcontent);
			//$hasAbuse = adesk_str_instr($abuseLink, $tmpcontent);
			$hasSender = adesk_str_instr('%SENDER-INFO%', $tmpcontent);
			if ( !$hasSender or !$hasUnsub ) {
				$footer .= hosted_footer_personalize($GLOBALS['__hosted_footer_text']);
			}
		}
	}
	else {
		$header = ($admin["brand_header_html"]) ? $admin["brand_header_html_value"] : "";
		$body = $opt_info["opt" . $direction . "_html"];
		$footer = ($admin["brand_footer_html"]) ? $admin["brand_footer_html_value"] : "";
		if ( isset($GLOBALS['__hosted_footer_html']) ) {
			$tmpcontent = $header . $body . $footer;
			$unsubLink = adesk_site_plink('surround.php?nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid&funcml=unsub2');
			//$abuseLink = adesk_site_plink('index.php?action=abuse&nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid');
			$hasUnsub = adesk_str_instr($unsubLink, $tmpcontent);
			//$hasAbuse = adesk_str_instr($abuseLink, $tmpcontent);
			$hasSender = adesk_str_instr('%SENDER-INFO%', $tmpcontent);
			if ( !$hasSender or !$hasUnsub ) {
				$footer .= hosted_footer_personalize($GLOBALS['__hosted_footer_html']);
			}
		}
	}

/*if your optin/optout messages are in MIME format, make sure $body is an array*/
	if($opt_info["opt" . $direction . "_format"] == "mime")
	{
		$body = array(
			'html' => $header . $body . $footer,
			'text' => $header_alt . $altBody . $footer_alt,
		);

		$body = mail_sender_info($list["id"], $body);

		// Personalization tags
		$body['html'] = subscriber_personalize($subscriber, $listids, $subscription_form_id, $body['html'], ( $direction == 'in' ? 'sub' : 'unsub' ));
		$body['text'] = subscriber_personalize($subscriber, $listids, $subscription_form_id, $body['text'], ( $direction == 'in' ? 'sub' : 'unsub' ));
	}
	else
	{
		$body = $header . $body . $footer;
		$body = mail_sender_info($list["id"], $body);

		// Personalization tags
		$body = subscriber_personalize($subscriber, $listids, $subscription_form_id, $body, ( $direction == 'in' ? 'sub' : 'unsub' ));
		$body = str_replace("<br />", "\n", $body);
	}


	$senderheader = false;
	if (isset($GLOBALS["_hosted_account"]))
		$senderheader = true;

	if ($senderheader && $site['onbehalfof'] && isset($GLOBALS["domain"])) {
		$info = $_SESSION[$GLOBALS["domain"]];
		$host = (string)adesk_sql_select_one("SELECT host FROM #mailer WHERE id = '1'");

		if (preg_match('/(astirx.com|acemserv.com|acems\d.com)$/', $host)) {
			//$xra = $info['rsid'] ? 'Please report abuse by forwarding this entire message to abuse@acemserv.com' : 'Please report abuse at http://www.awebdesk.com/contact/?type=abuse';
			$xra = 'Please report abuse at http://www.awebdesk.com/contact/?type=abuse';
			if ( $info['rsid'] ) $xra = 'Please report abuse by forwarding this entire message to abuse@acemserv.com';
			$h = sprintf('<%s@%s>', $info["account"], $host);
			if ( trim($opt_info["opt" . $direction . "_from_name"]) ) $h = '"' . trim($opt_info["opt" . $direction . "_from_name"]) . '" ' . $h;
			$options['headers'][] = array('name' => "Sender", 'value' => $h);
			$options['headers'][] = array('name' => "X-Sender", 'value' => $h);
			$options['headers'][] = array('name' => "X-Report-Abuse", 'value' => $xra);
		}
	}

	$subject = subscriber_personalize($subscriber, $listids, $subscription_form_id, $opt_info["opt" . $direction . "_subject"], ( $direction == 'in' ? 'sub' : 'unsub' ));
	adesk_mail_send($opt_info["opt" . $direction . "_format"], $opt_info["opt" . $direction . "_from_name"], $opt_info["opt" . $direction . "_from_email"], $body, $subject, $subscriber["email"], $subscriber["first_name"].' '.$subscriber["last_name"], $options);

}

function mail_responder_send($subscriber, $listids, $type = 'subscribe') {
	require_once adesk_admin("functions/campaign.php");

	if ( $type != 'unsubscribe' ) $type = 'subscribe';

	if ( !is_array($listids) ) {
		$listids = array_diff(array_map('intval', explode(',', $listids)), array(0));
	}
	if ( count($listids) > 0 ) {
		$listslist = implode("','", $listids);

		$so = new adesk_Select();
		$so->push("AND l.listid IN ('$listslist')");
		$so->push("AND c.type = 'responder'");
		$so->push("AND c.status IN (1, 5)"); // scheduled or completed (not draft, sending, stopped, paused, etc...)
		$so->push("AND c.responder_offset = 0");
		$so->push("AND c.responder_type = '$type'");
		$campaigns = campaign_select_array($so);

		foreach ( $campaigns as $k => $v ) {
			$campaign = campaign_select_prepare($v, true);
			campaign_send(null, $campaign, $subscriber, 'send');
		}
	}
}

function mail_campaign_send_last($subscriber, $listids) {
	require_once adesk_admin("functions/campaign.php");

	if ( !is_array($listids) ) {
		$listids = array_diff(array_map('intval', explode(',', $listids)), array(0));
	}
	if ( count($listids) == 0 ) return 0;
	# If we just added this subscriber (which is likely), then their filter records won't
	# match anything.  We'll need to analyze them.
	filter_analyze_subscriber_inlist($subscriber["id"], $listids);

	$listslist = implode("','", $listids);
	$so = new adesk_Select();
	$so->push("AND l.listid IN ('$listslist')"); // in subscriber's lists
	$so->push("AND c.type NOT IN ('responder', 'reminder', 'special', 'split')"); // real campaigns only
	$so->push("AND c.status = 5"); // completed only
	$so->orderby("c.sdate DESC");
	//dbg(campaign_select_query($so));
	$campaigns = campaign_select_array($so);

	$sent = 0;
	foreach ( $campaigns as $k => $v ) {
		if ($v["filterid"] > 0 && !filter_matches($subscriber["id"], $v["filterid"]))
			continue;
		$campaign = campaign_select_prepare($v, true);
		$sent += (int)campaign_send(null, $campaign, $subscriber, 'send');
		if ( $sent ) break;
	}
	return $sent;
}

// Email to list Admin
function mail_admin_send($subscriber, $lists, $type = 'subscribe') {
	if ( isset($GLOBALS['demoMode']) ) return; // check if demo mode is on
	global $site;
	if ( $type != 'unsubscribe' ) $type = 'subscribe';

	$listids = array();

	foreach ($lists as $list) {
		$listids[] = $list["id"];
	}

	$options = array();
	if ( isset($GLOBALS['_hosted_account']) ) $options['bounce'] = str_replace("@", "-0@", adesk_sql_select_one("email", "#bounce", "id = 1"));
	$options['userid'] = $lists[0]['userid'];

	$fields = subscriber_get_fields($subscriber["id"], $listids);

	// call smarty to make an e-mail body
	require_once(awebdesk_functions('smarty.php'));
	$smarty = new adesk_Smarty('public', true);

	// assign to template
	$smarty->assign('subscriber', $subscriber);
	$smarty->assign('fields', $fields);
	$smarty->assign('lists', $lists);
	$smarty->assign( 'udate_now', date('m/d/Y h:i', strtotime('now')) );
	$text = $smarty->fetch( $type == 'subscribe' ? 'new_subscriber_alert.txt' : 'new_unsubscriber_alert.txt' );

	$r = array();
	$listnames = array();

	foreach ($lists as $v) {
		// Comma-separated list of email addresses from DB table _list.(un)subscription_notify
		$f = $v[ $type == 'subscribe' ? 'subscription_notify' : 'unsubscription_notify' ];
		$arr = explode(",", $f);
		$emails = array_map('trim',$arr);

		$listnames[] = $v["name"];

		// Loop through email address values
		foreach ($emails as $e) {
			//if ( adesk_str_is_email($e) ) $r[$e] = $v['name'] . _a(" Administrator");
			if ( adesk_str_is_email($e) ) $r[] = $e;
		}
	}

	$subscribername = '';
	if ( isset($subscriber['lists']) and isset($subscriber['lists'][$listids[0]]) ) {
		$subscribername = $subscriber['lists'][$listids[0]]['first_name'] . " " . $subscriber['lists'][$listids[0]]['last_name'];
	}

	$options['headers'] = array();
	$senderheader = false;
	if (isset($GLOBALS["_hosted_account"]))
		$senderheader = true;

	if ($senderheader && $site['onbehalfof'] && isset($GLOBALS["domain"])) {
		$info = $_SESSION[$GLOBALS["domain"]];
		$host = (string)adesk_sql_select_one("SELECT host FROM #mailer WHERE id = '1'");

		if (preg_match('/(astirx.com|acemserv.com)$/', $host)) {
			$xra = 'Please report abuse at http://www.awebdesk.com/contact/?type=abuse';
			if ( $info['rsid'] ) $xra = 'Please report abuse by forwarding this entire message to abuse@acemserv.com';
			$h = sprintf('<%s@%s>', $info["account"], $host);
			if ( trim($subscribername) ) $h = '"' . trim($subscribername) . '" ' . $h;
			$options['headers'][] = array('name' => "Sender", 'value' => $h);
			$options['headers'][] = array('name' => "X-Sender", 'value' => $h);
			$options['headers'][] = array('name' => "X-Report-Abuse", 'value' => $xra);
		}
	}

	if ( count($r) > 0 ) {
		if ( $type == 'subscribe' ) {
			$subject = implode(", ", $listnames) . ": " . _p("You have a new subscriber to your list.");
		} else {
			$subject = implode(", ", $listnames) . ": " . _p("A subscriber has been removed from your list.");
		}
		//adesk_mail_send("text", $site["site_name"], $site["emfrom"], $text, $subject, $r, "");
		$to_name = $v['name'] . _a(" Administrator");
		foreach($r as $email)
		{
			//adesk_mail_send("text", $subscriber["first_name"].' '.$subscriber["last_name"], $subscriber["email"], $text, $subject, $email, $to_name, $options);
			adesk_mail_send("text", $subscribername, $subscriber["email"], $text, $subject, $email, $to_name, $options);
		}
	}
}

// Forward to a Friend
function mail_forward_send($from_email, $from_name, $to_email, $to_name, $message) {
	require_once(awebdesk_functions('ajax.php'));

	global $site;

	// check email validity
	if ( !adesk_str_is_email($from_email) ) {
		return adesk_ajax_api_result(false, _p("From email is not valid."), array('email' => $to_email));
	}

	// check email validity
	if ( !adesk_str_is_email($to_email) ) {
		return adesk_ajax_api_result(false, _p("To email is not valid."), array('email' => $to_email));
	}

	$admin = adesk_admin_get();

	$header = $footer = "";

	$header = ($admin["brand_header_text"]) ? $admin["brand_header_text_value"] : "";
	$footer = ($admin["brand_footer_text"]) ? $admin["brand_footer_text_value"] : "";
	if ( isset($GLOBALS['__hosted_footer_text']) ) {
		$tmpcontent = $header . $header . $footer;
		$unsubLink = adesk_site_plink('surround.php?nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid&funcml=unsub2');
		//$abuseLink = adesk_site_plink('index.php?action=abuse&nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid');
		$hasUnsub = adesk_str_instr($unsubLink, $tmpcontent);
		//$hasAbuse = adesk_str_instr($abuseLink, $tmpcontent);
		$hasSender = adesk_str_instr('%SENDER-INFO%', $tmpcontent);
		if ( !$hasSender or !$hasUnsub ) {
			$footer .= hosted_footer_personalize($GLOBALS['__hosted_footer_text']);
		}
	}

	$options = array();
	if ( isset($GLOBALS['_hosted_account']) ) $options['bounce'] = str_replace("@", "-0@", adesk_sql_select_one("email", "#bounce", "id = 1"));

	$listid = (adesk_http_param('nl') ? (int)adesk_http_param('nl') : null);

	if($listid) {
		$userid = adesk_sql_select_one("userid", "#list", "id='$listid'");
		$options['userid'] = $userid;
	}
	elseif($admin['id'] && $admin['id'] != 0) {
		$options['userid'] = $admin['id'];
	}
	else {
		$options['userid'] = 1;
	}

	$options['headers'] = array();
	$senderheader = false;
	if (isset($GLOBALS["_hosted_account"]))
		$senderheader = true;

	if ($senderheader && $site['onbehalfof'] && isset($GLOBALS["domain"])) {
		$info = $_SESSION[$GLOBALS["domain"]];
		$host = (string)adesk_sql_select_one("SELECT host FROM #mailer WHERE id = '1'");

		if (preg_match('/(astirx.com|acemserv.com)$/', $host)) {
			$xra = 'Please report abuse at http://www.awebdesk.com/contact/?type=abuse';
			if ( $info['rsid'] ) $xra = 'Please report abuse by forwarding this entire message to abuse@acemserv.com';
			$h = sprintf('<%s@%s>', $info["account"], $host);
			if ( $from_name ) $h = '"' . trim($from_name) . '" ' . $h;
			$options['headers'][] = array('name' => "Sender", 'value' => $h);
			$options['headers'][] = array('name' => "X-Sender", 'value' => $h);
			$options['headers'][] = array('name' => "X-Report-Abuse", 'value' => $xra);
		}
	}

	$subject = sprintf(_p("%s thought you may be interested in this mailing."), $from_name);

	$body = $header;
	$body .= _p("To: ") . $to_name . "\n";
	$body .= _p("From: ") . $from_name . "\n\n";
	$body .= $message . "\n\n";
	$body .= $footer;

	if ( !isset($GLOBALS['demoMode']) ) { // check if demo mode is on
		adesk_mail_send("text", $from_name, $from_email, $body, $subject, $to_email, $to_name, $options);
	}

	return adesk_ajax_api_result(true, _p("Email Sent."), array('email' => $to_email));
}

function mail_sender_info($listid, $body) {
	$list = list_select_row($listid);

	if (!$list || !$list["sender_name"]) {
		return $body;
	}

	# Run these checks on content that has HTML tags stripped out, just in case someone tries to
	# stick %SENDER-INFO% in an HTML comment or somewhere else that it might not be displayed.
	# Literally, run strip_tags everywhere, even if we're not sure or think it shouldn't have
	# tags.

	if (is_string($body)) {
	   	if (adesk_str_instr("%SENDER-INFO%", strip_tags($body)))
			return $body;
		else
			return $body . "\n\n%SENDER-INFO%";
	} elseif (is_array($body)) {
		if (!adesk_str_instr("%SENDER-INFO%", strip_tags($body["html"])))
			$body["html"] = str_replace("</body>", "<br>\n<br>\n%SENDER-INFO%</body>", $body["html"]);
		if (!adesk_str_instr("%SENDER-INFO%", strip_tags($body["text"])))
			$body["text"] .= "\n\n%SENDER-INFO%";

		return $body;
	} else {
		# Huh?
		return $body;
	}
}

?>
