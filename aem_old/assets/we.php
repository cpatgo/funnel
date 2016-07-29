<?php

require_once(awebdesk_classes('page.php'));
require_once adesk_admin("functions/bitly.php");

class social_assets extends AWEBP_Page {
	function social_assets() {
		$this->pageTitle = _p("Social Share");
		parent::AWEBP_Page();
		$this->getParams();
	}

	function getParams() {
	}

	function process(&$smarty) {
		$this->setTemplateData($smarty);

		$smarty->assign("messagebody", "");

		$chash = trim((string)adesk_http_param('c'));
		if ( !$chash or !adesk_str_instr('.', $chash)) {
			adesk_http_redirect(adesk_site_plink());
		}

		list($campaignhash, $messageid) = explode('.', $chash);

		$esc = adesk_sql_escape($campaignhash);
		$campaignid = (int)adesk_sql_select_one("id", "#campaign", "MD5(id) = '$esc'");
		if ( !$campaignid ) {
			adesk_http_redirect(adesk_site_plink());
		}

		$listid = (int)adesk_sql_select_one("listid", "#campaign_list", "campaignid = '$campaignid'");

		$campaign = campaign_select_row($campaignid);

		if ( !$campaign /*or !$campaign['public']*/ ) {
			adesk_http_redirect(adesk_site_plink());
		}

		// get campaign's message
		$messagekey = 0;
		if ( $messageid ) {
			foreach ( $campaign['messages'] as $k => $v ) {
				if ( $v['id'] == $messageid ) {
					$messagekey = $k;
					break;
				}
			}
		}

		$message = $campaign['messages'][$messagekey];
		$messageid = $message['id'];

		$webcopy = $this->site['p_link'] . '/index.php?action=social&c=' . md5($campaignid) . '.' . $messageid;
		$smarty->assign('webcopy', $webcopy);

		$bitly = bitly_lookup($campaignid, $messageid, '');
		$bitly_facebook = bitly_lookup($campaignid, $messageid, 'facebook');

		if ($bitly == "")
			$bitly = urlencode($webcopy);

		$smarty->assign("bitly", $bitly);
		$smarty->assign("bitly_facebook", $bitly_facebook);
		$smarty->assign("campaign", $campaign);
		$smarty->assign("subscriber", false);

		require_once adesk_admin('functions/personalization.php');
		require_once adesk_admin('functions/socialsharing.php');
		// used for social share icons at the top of the page - the individual icons outside of the message contents
		$socialsharing_sources = personalization_social_networks();
		foreach ($socialsharing_sources as $source) {
			// get all the external social share links, IE: twitter.com?share...
			// we then use these in the social.share.inc.htm template
			$ref = ($source == "stumbleupon") ? "referral" : "ref";
			$process_link = socialsharing_process_link($campaignid, $messageid, 0, $webcopy . "&" . $ref . "=" . $source);
			$process_link = $process_link[0];
			$smarty->assign('shareURL_' . $source . '_external', $process_link);
		}

		$type = "html";
		if ( $message['format'] != 'mime' and $message['format'] != $type ) $type = $message['format'];

		// "send" an email
		require_once(awebdesk_functions('ajax.php'));

		$source = adesk_sql_select_one("SELECT id FROM #campaign_source WHERE campaignid = '$campaign[id]' AND messageid = '$message[id]' AND `type` = 'preview'");
		$source = campaign_source($source);
		if ($source == "") {
			$source = campaign_quick_send(_a("_t.e.s.t_@example.com"), $campaign["id"], $message["id"], $type, 'preview'); // call spamcheck to get message source that we can parse
			if ( !is_array($source) ) {
				$in = array(
					"id" => 0,
					"campaignid" => $campaign['id'],
					"messageid" => $message['id'],
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

		// get message structure
		$structure = adesk_mail_extract($source);
		if ( !$structure ) {
			$txt = ( !isset($_GET['useauth']) ? _a('Message could not be previewed.') : _p('Message could not be previewed.') );
			// handle error here; this is ajax_result array in this case (or doesn't have to be)
			echo $txt;
			exit;
		}
		// we need these items from the email source
		$filter = array(
			'subject',
			//'body',
			'parts',
			'ctype',
			'charset',
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

		// this array should have only 'parts' element, that has element we need
		if ( !isset($r['parts'][$type]) or !$r['parts'][$type] ) {
			// handle error here
			$txt = ( !isset($_GET['useauth']) ? _a('Message type not found.') : _p('Message type not found.') );
			echo $txt;
			exit;
		}

		# The contents of adesk_mail_extract_components must be encoded for the current
		# page.  They're not necessarily in UTF-8, either; they'll be encoded in whatever
		# the original message was configured with.  We need to make sure everything
		# lines up or the message here will not display correctly.
		$r["subject"] = adesk_utf_conv($r["charset"], _i18n("utf-8"), $r["subject"]);

		if (isset($r["parts"][$type . "_charset"])) {
			$r["parts"][$type] = adesk_utf_conv($r["parts"][$type . "_charset"], _i18n("utf-8"), $r["parts"][$type]);
		}

		$body = $r['parts'][$type];

		//Re-assign message subject here, in case it is personalized
		$message['subject'] = $r['subject'];
		$smarty->assign("message", $message);

		$img = "<img src=\"{$this->site['p_link']}/lt.php?nl=$listid&c=$campaign[id]&m=$message[id]&l=open\" border=\"0\" />";
		$body = str_replace($img, '', $body);
		// print it
		$smarty->assign('messagebody', $body);

		$charset = $r['parts'][$type.'_charset'];
		$mimetype = $type == 'text' ? 'plain' : 'html';
		header("Content-Type: text/$mimetype; charset=$charset");

		$smarty->display("social.htm");
		assets_complete($this->site);
		exit;
	}
}

?>
