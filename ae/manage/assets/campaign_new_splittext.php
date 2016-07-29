<?php

require_once adesk_admin("functions/campaign.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
require_once awebdesk_functions("htmltext.php");

class campaign_new_splittext_assets extends AWEBP_Page {

	function campaign_new_splittext_assets() {
		$this->pageTitle = _a("Create a New Campaign");
		$this->AWEBP_Page();
	}

	function process(&$smarty) {
		$admin = adesk_admin_get();
		$this->setTemplateData($smarty);

		if (!$this->admin["pg_message_add"] && !$this->admin["pg_message_edit"]) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		$smarty->assign("content_template", "campaign_new_splittext.htm");

		$campaignid = (int)adesk_http_param("id");

		if ($campaignid < 1)
			adesk_http_redirect("desk.php");

		campaign_save_markpos("splittext", $campaignid);

		$isEdit = false;
		$showAllMessages = false;

		adesk_smarty_submitted($smarty, $this);
		if ( isset($_SESSION["campaign_save_result"][$campaignid]) ) {
			$smarty->assign("formSubmitted", true);
			$smarty->assign("submitResult", $_SESSION["campaign_save_result"][$campaignid]);
			unset($_SESSION["campaign_save_result"][$campaignid]);
		}

		$row = campaign_select_row($campaignid);
		if ( $row ) {
			// use this campaign
			$campaign = $row;
			// campaign info
			if ( in_array($row['status'], array(0, 1, 3, 6, 7)) and !adesk_http_param('use') ) { // if not sending or completed
				// statuses that can be reused are : draft, scheduled, (while sending?) paused, stopped
				$campaign['id'] = $row['id']; // edit this campaign allowed
				$campaign['status'] = $row['status']; // reuse the same status
				if ( $row['status'] != 0 ) $isEdit = true;
			} else {
				adesk_http_redirect("desk.php?action=campaign_new&id=$campaignid");
			}

			if ( !$campaign['lists'] ) {
				adesk_http_redirect("desk.php?action=campaign_new_list&id=$campaignid");
			}
		} else {
			adesk_http_redirect("desk.php?action=campaign_new");
		}

		// Fetch this campaign's lists
		$lists = adesk_sql_select_list("SELECT listid FROM #campaign_list WHERE campaignid = '$campaignid'");
		$liststr = implode("','", $lists);

		$mid = adesk_http_param("m");

		# No messages?  We really shouldn't be here.
		$any = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #campaign_message WHERE campaignid = '$campaignid'");
		if ($any == 0)
			adesk_http_redirect("desk.php?action=campaign_new_splitmessage&id=$campaignid");

		# We have at least one message, so let's hope m was one of them.
		$mid = (int)$mid;

		if ($mid > 0) {
			$exists = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #campaign_message WHERE messageid = '$mid' AND campaignid = '$campaignid'");

			# We can't have this; the message must exist already for us to be here.
			if (!$exists)
				adesk_http_redirect("desk.php?action=campaign_new_splitmessage&id=$campaignid");
		} else {
			$mid = (int)adesk_sql_select_one("SELECT messageid FROM #campaign_message WHERE campaignid = '$campaignid' ORDER BY id LIMIT 1");
		}

		$message = message_select_row($mid);
		if ($message["text"] == "")
			$message["text"] = message_html2text($message);
		$smarty->assign("message", $message);

		if (isset($message["textfetch"]) && substr($message["text"], 0, 6) == "fetch:") {
			$smarty->assign("fetch", $message["textfetch"]);
			$smarty->assign("fetchurl", substr($message["text"], 6));
		} else {
			$smarty->assign("fetch", "now");
			$smarty->assign("fetchurl", "http://");
		}

		# Do we have any other messages?  Grab them for any tabs we show.
		$tabs = adesk_sql_select_array("
			SELECT
				cm.messageid AS id,
				cm.percentage,
				cm.percentage * 3 AS percentage300,
				(SELECT m.subject FROM #message m WHERE m.id = cm.messageid) AS subject
			FROM
				#campaign_message cm
			WHERE
				cm.campaignid = '$campaignid'
		");
		$smarty->assign("tabs", $tabs);

		# Figure out what fields to show.
		$fields = adesk_custom_fields_select_nodata_rel("#list_field", "#list_field_rel", "r.relid IN ('0', '$liststr')");

		$smarty->assign("fields", $fields);

		// assign all presets
		$smarty->assign('campaignid', $campaignid);
		$smarty->assign('campaign', $campaign);
		$smarty->assign("isEdit", $isEdit);
		$smarty->assign("showAllMessages", $showAllMessages);

		# Last ditch check; too many subscribers?
		$pastlimit = campaign_subscribers($campaignid, $campaign["filterid"]);
		$smarty->assign("pastlimit", $pastlimit);

		// default debugging
		$debugging = $campaign['mailer_log_file'];
		// custom debugging
		if ( adesk_http_param_exists('debug') ) {
			$debugging = (int)adesk_http_param('debug');
		}
		$smarty->assign("debugging", $debugging);
		$smarty->assign('isDemo', isset($GLOBALS['demoMode']));
	}

	function formProcess(&$smarty) {
		if (adesk_http_param("post_action"))
			$_POST["aftersave"] = "nothing";

		campaign_save();

		if (adesk_http_param("post_action")) {
			$action = adesk_http_param("post_action");
			$m = (int)adesk_http_param("post_m");
			$from = (int)adesk_http_param("post_from");
			$campaignid = (int)adesk_http_param("id");

			$campaign = campaign_select_row($campaignid);

			switch ($action) {
				case "new":
					$lists = adesk_sql_select_list("SELECT listid FROM #campaign_list WHERE campaignid = '$campaignid'");
					$this->newmessage($campaign, $lists, $from);
					break;

				case "del":
					$this->deletemessage($campaign, $m, $from);
					break;

				case "load":
					adesk_http_redirect("desk.php?action=campaign_new_splittext&id=$campaign[id]&m=$m");
					break;

				default:
					break;
			}
		}

		campaign_save_after();

		if ($GLOBALS["campaign_save_id"] > 0) {
			$m = (int)adesk_http_param("messageid");
			adesk_http_redirect("desk.php?action=campaign_new_splittext&id=$GLOBALS[campaign_save_id]&m=$m");
		}
	}
}

?>
