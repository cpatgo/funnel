<?php

require_once adesk_admin("functions/campaign.php");

class campaign_new_result_assets extends AWEBP_Page {

	function campaign_new_result_assets() {
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

		$smarty->assign("content_template", "campaign_new_result.htm");

		$campaignid = (int)adesk_http_param("id");

		if ($campaignid < 1)
			adesk_http_redirect("desk.php");
		if ( !isset($_SESSION["campaign_save_result"][$campaignid]) )
			adesk_http_redirect("desk.php");

		campaign_save_markpos("result", $campaignid);

		$isEdit = false;
		$showAllMessages = false;
		$finalstatus = 'finished';

		$campaign = campaign_select_row($campaignid);
		if (!$campaign)
			adesk_http_redirect("desk.php");

		// figure out what panel to show
		$sqlnow = adesk_sql_select_row("SELECT NOW() as tstamp", array("tstamp"));
		$sendnow = ( in_array($campaign['type'], array('single', /*'recurring',*/ 'split', 'text')) && $campaign['sdate'] <= (string)$sqlnow["tstamp"] );
		if ( in_array($campaign['type'], array('single', 'split', 'text')) ) {
			$finalstatus = $sendnow && $this->admin['send_approved'] ? 'sent' : 'scheduled';
		} elseif ( in_array($campaign['type'], array('recurring', 'deskrss')) ) {
			$finalstatus = 'scheduled';
		} else {//if ( in_array($campaign['type'], array('responders', 'reminders', 'special')) ) {
			$finalstatus = 'finished';
		}

		// assign all presets
		$smarty->assign('campaignid', $campaignid);
		$smarty->assign('campaign', $campaign);
		$smarty->assign("isEdit", $isEdit);
		$smarty->assign("showAllMessages", $showAllMessages);
		$smarty->assign("finalstatus", $finalstatus);

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

		//adesk_smarty_submitted($smarty, $this);
		//if ( isset($_SESSION["campaign_save_result"][$campaignid]) ) {
		$smarty->assign("formSubmitted", true);
		$smarty->assign("submitResult", $_SESSION["campaign_save_result"][$campaignid]);
		//unset($_SESSION["campaign_save_result"][$campaignid]); // comment this line to test the page by refreshing it
		//}

	}

/*
	function formProcess(&$smarty) {
		campaign_save();
		campaign_save_after();

		if ($GLOBALS["campaign_save_id"] > 0)
			adesk_http_redirect("desk.php?action=campaign_new_result&id=$GLOBALS[campaign_save_id]");
	}
*/

}

?>
