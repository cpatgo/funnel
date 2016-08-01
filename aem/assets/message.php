<?php

require_once(awebdesk_classes('page.php'));
require_once adesk_admin("functions/message.php");
require_once adesk_admin("functions/campaign.php");
require_once adesk_admin("functions/list.php");
require_once awebdesk_classes("pagination.php");

class message_assets extends AWEBP_Page {
	function message_assets() {
		$this->pageTitle = _p("Message");
		parent::AWEBP_Page();
		$this->getParams();
	}

	function getParams() {
	}

	function process(&$smarty) {

		//$this->setTemplateData($smarty);
		$smarty->assign("content_template", "message.htm");

		$listfilter = ( isset($_SESSION['nlp']) ? $_SESSION['nlp'] : null );

		$id = (int)adesk_http_param('c');
		$listid = (int)adesk_http_param('l');
		$messageid = (int)adesk_http_param('m');
		$hash = trim((string)adesk_http_param('s'));
		$subscriber = ( $hash ? subscriber_exists($hash, ( is_null($listfilter) ? 0 : $listfilter ), 'hash') : false );
		$mode = adesk_http_param('mode');

		$smarty->assign("mode", $mode);
		$smarty->assign("hash", $hash);
		$smarty->assign("subscriber", $subscriber);
		$smarty->assign('messagebody', '');

		$campaign = false;

		$type = ( $mode == 'text' ? 'text' : 'html' );

		if ($id > 0) {
			/*
			$branding = adesk_sql_select_row("
				SELECT
					b.*
				FROM
					#user_group ug,
					#campaign c,
					#branding b
				WHERE
					c.id = '$id'
				AND
					c.userid = ug.userid
				AND
					ug.groupid = b.groupid
			");
			if ( $branding ) {
				if ( !isset($this->admin['groups'][$branding['groupid']]) ) {
					unset($branding['id']);unset($branding['groupid']);$branding['version'] = !$branding['version'];
					foreach ( $branding as $k => $v ) $this->admin['brand_' . $k] = $v;
					$this->site['site_name'] = $this->site['brand_site_name'] = $this->admin['brand_site_name'];
					$this->site['site_logo'] = $this->site['brand_site_logo'] = $this->admin['brand_site_logo'];
				}
			}
			//$_SESSION['c'] = $id;
			*/

			$campaign = campaign_select_row($id);
			if ( !$campaign ) {
				adesk_http_redirect(adesk_site_plink() . '?err=cm');
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

			if ( $campaign["public"] or $subscriber ) {
				$smarty->assign("campaign", $campaign);
				$smarty->assign("message", $message);
			}

			$webcopy = $this->site['p_link'] . '/index.php?action=social&c=' . md5($id) . '.' . $messageid;
			$smarty->assign('webcopy', $webcopy);

			require_once adesk_admin('functions/personalization.php');
			require_once adesk_admin('functions/socialsharing.php');
			// used for social share icons at the top of the page - the individual icons outside of the message contents
			$socialsharing_sources = personalization_social_networks();
			foreach ($socialsharing_sources as $source) {
				// get all the external social share links, IE: twitter.com?share...
				// we then use these in the social.share.inc.htm template
				$process_link = socialsharing_process_link($id, $messageid, 0, $webcopy . "&ref=" . $source);
				$process_link = $process_link[0];
				$smarty->assign('shareURL_' . $source . '_external', $process_link);
			}

			if ( $subscriber and $mode != 'full' ) {
				if ( $message['format'] != 'mime' and $message['format'] != $type ) $type = $message['format'];

				// "send" an email
				require_once(awebdesk_functions('ajax.php'));

				$source = campaign_quick_send($subscriber['email'], $id, $messageid, $type, 'preview'); // call spamcheck to get message source that we can parse


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
				$r['to'] = str_replace('<', '&lt;', $r['to']);
				$r['to'] = str_replace('>', '&gt;', $r['to']);

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

				$img = "<img src=\"{$this->site['p_link']}/lt.php?nl=$listid&c=$id&m=$messageid&s=$hash&l=open\" border=\"0\" />";
				$body = str_replace($img, '', $body);
				// print it
				$smarty->assign('messagebody', $body);
				$smarty->assign("campaignid", $id);
				$smarty->assign("messageid", $messageid);

				$charset = $r['parts'][$type.'_charset'];
				$mimetype = ($type == 'text') ? 'plain' : 'html';
				header("Content-Type: text/$mimetype; charset=$charset");

				$smarty->display('webcopy.htm');
				assets_complete($this->site);
				exit;
			}
		}

		$smarty->assign("listfilter", $listfilter);

		if ( $campaign and ( $campaign["public"] or $subscriber ) ) {
			$smarty->assign("campaignid", $id);
			$smarty->assign("messageid", $messageid);
			$this->pageTitle = $message['subject'];

			if(!$subscriber) //adding this code to make sure subjects are personalized with example data on public archive/social share pages
			{
				$email = _a("_t.e.s.t_@example.com");
				$source = adesk_sql_select_one("SELECT id FROM #campaign_source WHERE campaignid = '$id' AND messageid = '$messageid' AND `type` = 'preview'");
				$source = campaign_source($source);
				if ($source == "") {
					$source = campaign_quick_send($email, $id, $messageid, $mode, 'preview');
					if ( !is_array($source) ) {
						$in = array(
							"id" => 0,
							"campaignid" => $id,
							"messageid" => $messageid,
							"type" => 'preview',
							"len" => strlen($source),
						);
						adesk_sql_insert("#campaign_source", $in);
						$sourceid = (int)adesk_sql_insert_id();

						campaign_source_save($sourceid, $source, $in["len"]);
					}
				}
				$structure = adesk_mail_extract($source);
				$r = adesk_mail_extract_components($structure, array("subject", "parts"));
				$this->pageTitle = $r['subject'];
				$message['subject'] = $r['subject'];

				$charset = $r['parts'][$type.'_charset'];
				$mimetype = ($type == 'text') ? 'plain' : 'html';
				header("Content-Type: text/$mimetype; charset=$charset");

				$smarty->assign("message", $message);
			}

		}
		else {
			$smarty->assign("campaignid", 0);
			$smarty->assign("messageid", $messageid);
		}

		$this->setTemplateData($smarty);
		$smarty->assign("public", $campaign ? (int)$campaign["public"] : 0);
	}

}

?>
