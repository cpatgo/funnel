<?php

require_once adesk_admin("functions/campaign.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");

class campaign_new_list_assets extends AWEBP_Page {

	function campaign_new_list_assets() {
		$this->pageTitle = _a("Create a New Campaign");
		//$this->sideTemplate = "side.message.htm";
		$this->AWEBP_Page();
	}

	function process(&$smarty) {
		$this->setTemplateData($smarty);

		if (!$this->admin["pg_message_add"] && !$this->admin["pg_message_edit"]) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		if ( list_get_cnt() == 0 ) {
			$smarty->assign('content_template', 'nolists.htm');
			return;
		}

		$smarty->assign("content_template", "campaign_new_list.htm");

		$so = new adesk_Select;
		$so->orderby("name");
		$lists = list_select_array($so);
		foreach ($lists as $k => $v) {
			$lists[$k]["count"] = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #subscriber_list WHERE listid = '$v[id]' AND status = 1");
			$lists[$k]["count"] = number_format($lists[$k]["count"]);
		}

		$smarty->assign("lists", $lists);

		$so2 = new adesk_Select();
		$so2->limit("0, 100");
		$campaigns = campaign_select_array($so2);
		$smarty->assign("campaigns", $campaigns);

		$campaignid = (int)adesk_http_param("id");

		if ($campaignid < 1)
			adesk_http_redirect("desk.php");

		campaign_save_markpos("list", $campaignid);

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
		} else {
			adesk_http_redirect("desk.php?action=campaign_new");
		}

		// assign all presets
		$smarty->assign('campaignid', $campaignid);
		$smarty->assign('campaign', $campaign);
		$smarty->assign("isEdit", $isEdit);
		$smarty->assign("showAllMessages", $showAllMessages);

		// Fetch this campaign's lists
		$lists = adesk_sql_select_list("SELECT listid FROM #campaign_list WHERE campaignid = '$campaignid'");
		$liststr = implode("','", $lists);

		# Figure out what fields to show.
		$fields = adesk_custom_fields_select_nodata_rel("#list_field", "#list_field_rel", "r.relid IN ('0', '$liststr')");
		$smarty->assign("filter_fields", $fields);

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
		campaign_save();
		campaign_save_after();

		if ($GLOBALS["campaign_save_id"] > 0)
			adesk_http_redirect("desk.php?action=campaign_new_list&id=$GLOBALS[campaign_save_id]");

	}
}

?>
