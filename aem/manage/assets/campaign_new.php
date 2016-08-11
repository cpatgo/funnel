<?php

require_once adesk_admin("functions/campaign.php");
//require_once adesk_admin("functions/filter.php");
//require_once adesk_admin("functions/message.php");
require_once adesk_admin("functions/template.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");

class campaign_new_assets extends AWEBP_Page {

	function campaign_new_assets() {
		$this->pageTitle = _a("Create a New Campaign");
		//$this->sideTemplate = "side.message.htm";
		$this->AWEBP_Page();
	}

	function process(&$smarty) {
		$this->setTemplateData($smarty);

		if (adesk_http_param("copyid")) {
			$copy = (int)adesk_http_param("copyid");
			$campaign = campaign_select_row($copy);

			if ($campaign) {
				$newid = campaign_copy($campaign);
				if ($newid > 0) {
					# Fix some things the $data param can't handle.
					$up = array(
						"laststep" => "",
						"status" => 0,
						"=ldate" => "NULL",
						"=sdate" => "NULL",
						"=cdate" => "NOW()",
					);
					adesk_sql_update("#campaign", $up, "id = '$newid'");
					adesk_http_redirect("desk.php?action=campaign_new&id=$newid");
				}
			}
		}

		campaign_save_markpos("type", 0);

		if (!$this->admin["pg_message_add"] && !$this->admin["pg_message_edit"]) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		if ( list_get_cnt() == 0 ) {
			$smarty->assign('content_template', 'nolists.htm');
			return;
		}

		$smarty->assign("content_template", "campaign_new.htm");

		$campaignid = (int)adesk_http_param("id");

		// default campaign array (create new)
		$campaign = campaign_new();
		$campaign['mailer_log_file'] = $this->site['mailer_log_file'];

		$isEdit = false;
		$showAllMessages = false;

		if ( $campaignid > 0 ) {
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
					$campaign['id'] = 0; // edit this campaign not allowed
					$campaign['status'] = 0; // set status to draft
				}
			}
		}

		// assign all presets
		$smarty->assign('campaignid', $campaignid);
		$smarty->assign('campaign', $campaign);
		$smarty->assign("isEdit", $isEdit);
		$smarty->assign("showAllMessages", $showAllMessages);

		// default debugging
		$debugging = $campaign['mailer_log_file'];
		// custom debugging
		if ( adesk_http_param_exists('debug') ) {
			$debugging = (int)adesk_http_param('debug');
		}
		$smarty->assign("debugging", $debugging);
		$smarty->assign('isDemo', isset($GLOBALS['demoMode']));

		adesk_smarty_submitted($smarty, $this);
		if ( isset($_SESSION["campaign_save_result"][$campaignid]) ) {
			$smarty->assign("formSubmitted", true);
			$smarty->assign("submitResult", $_SESSION["campaign_save_result"][$campaignid]);
			unset($_SESSION["campaign_save_result"][$campaignid]);
		}
	}

	function formProcess(&$smarty) {
		campaign_save();
		campaign_save_after();

		if ($GLOBALS["campaign_save_id"] > 0)
			adesk_http_redirect("desk.php?action=campaign_new&id=$GLOBALS[campaign_save_id]");
	}
}

?>
