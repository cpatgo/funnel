<?php

require_once(awebdesk_classes('page.php'));
require_once adesk_admin("functions/message.php");
require_once adesk_admin("functions/campaign.php");
require_once adesk_admin("functions/mail.php");

class forward_assets extends AWEBP_Page {
	function forward_assets() {
		$this->pageTitle = _p("Forward to a Friend");
		parent::AWEBP_Page();
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		$listfilter = ( isset($_SESSION['nlp']) ? $_SESSION['nlp'] : null );

		$listid = (int)adesk_http_param('nl');
		$campaignid = (int)adesk_http_param('c');
		$messageid = (int)adesk_http_param('m');
		$hash = trim((string)adesk_http_param('s'));

		$campaign = adesk_sql_select_row("SELECT * FROM #campaign WHERE id = '$campaignid'");

		$smarty->assign("view_link", $GLOBALS["site"]["p_link"] . "/forward3.php?nl=" . $listid . "&c=" . $campaignid . "&m=" . $messageid . "&s=" . $hash);
		$smarty->assign("view_link2", $GLOBALS["site"]["p_link"] . "/index.php?action=forward&nl=" . $listid . "&c=" . $campaignid . "&m=" . $messageid . "&s=" . $hash);

		$subscriber = subscriber_exists($hash, 0, 'hash');
		$list = adesk_sql_select_row("SELECT * FROM #list WHERE id = '$listid'");

		$found = (int)adesk_sql_select_one('=COUNT(*)', '#forward', "subscriberid = '$subscriber[id]' AND campaignid = '$campaignid' AND messageid = '$messageid'");
		if ( !$found ) {
			adesk_sql_update_one("#campaign", "=uniqueforwards", "uniqueforwards + 1", "id = '$campaignid'");
			adesk_sql_update_one("#campaign_deleted", "=uniqueforwards", "uniqueforwards + 1", "id = '$campaignid'");
		}

		// Coming from "Forward to a Friend" page
		if ( adesk_http_param('mode') == "forward" ) {

			$friend_emails = adesk_http_param("to_email");
			$friend_names  = adesk_http_param("to_name");
			if ( !is_array($friend_emails) ) $friend_emails = array();
			if ( !is_array($friend_names ) ) $friend_names  = array();

			$forwards = array();
			$allgood = true;
			//foreach ( $friend_array as $v ) {
			foreach ( $friend_emails as $k => $email ) {
				$email = trim($email);
				if ( $email ) {
					if ( !isset($friend_names[$k]) ) $friend_names[$k] = '';
					if ( adesk_str_is_email($email) ) {
						$name = $friend_names[$k];
						$user_ip = $_SERVER['REMOTE_ADDR'];
						// Save to DB
						$ary = array(
							"subscriberid" => $subscriber["id"],
							"campaignid" => $campaignid,
							"messageid" => $messageid,
							"email_from" => (string)adesk_http_param('from_email'),
							"name_from" => (string)adesk_http_param('from_name'),
							"email_to" => $email,
							"name_to" => $name,
							"brief_message" => adesk_http_param('custom_message'),
							"=tstamp" => "NOW()",
							"=ip" => "INET_ATON('$user_ip')",
						);
						$sql = adesk_sql_insert("#forward", $ary);
						// update campaign
						adesk_sql_update_one("#campaign", "=forwards", "forwards + 1", "id = '$campaignid'");
						adesk_sql_update_one("#campaign_deleted", "=forwards", "forwards + 1", "id = '$campaignid'");

						subscriber_action_dispatch("forward", $subscriber, null, $campaign, null);

						// send an email
						$res = mail_forward_send($ary['email_from'], $ary['name_from'], $email, $name, adesk_http_param('message'));
						if ( !$res['succeeded'] ) {
							$allgood = false;
						}
						$forwards[] = $res;
					} else {
						$allgood = false;
						$forwards[] = adesk_ajax_api_result( false, _a("Invalid Email."), array('email' => $to_email));
					}
				}
			}

			$smarty->assign("mail_forward_good", $allgood);
			$smarty->assign("mail_forward_results", $forwards);
			$smarty->assign("mail_forward_results_count", count($forwards));

			$smarty->assign("mail_forward_send", true);
		} else {
			$smarty->assign("mail_forward_send", false);
		}

		/*if ( !$campaign ) {
			// naaah, they shouldn't be allowed to forward deleted campaigns
			//$campaign = adesk_sql_select_row("SELECT * FROM #campaign_deleted WHERE id = '$campaignid'");
		}
		if ( !$campaign ) {
			die('here'); // handle 'campaign not found' error here
		}*/

		$message_exists = adesk_sql_select_row("SELECT * FROM #message WHERE id = '$messageid'");
		$messages = adesk_sql_select_box_array("SELECT messageid, percentage FROM #campaign_message WHERE campaignid = '$campaignid'");
		// if message is not selected, get first
		if (!isset($messageid) || $messageid == 0 || !isset($messages[$messageid]) ) {
			$messageid = key($messages);
		}
		$message = adesk_sql_select_row("SELECT * FROM #message WHERE id = '$messageid'");

		if ($subscriber && $campaign && $message_exists && $list) {
			$smarty->assign("valid", true);
		}
		else {
			$smarty->assign("valid", false);
		}

		$smarty->assign("listid", $listid);
		$smarty->assign("subscriber", $subscriber);
		$smarty->assign("hash", $hash);
		$smarty->assign("campaign", $campaign);
		$smarty->assign("campaignid", $campaignid);
		$smarty->assign("message", $message);
		$smarty->assign("messageid", $messageid);

		$smarty->assign("listfilter", $listfilter);

		$smarty->assign("content_template", "forward.htm");
	}
}

?>
