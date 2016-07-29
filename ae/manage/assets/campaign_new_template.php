<?php

require_once adesk_admin("functions/campaign.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");

class campaign_new_template_assets extends AWEBP_Page {

	function campaign_new_template_assets() {
		$this->pageTitle = _a("Create a New Campaign");
		//$this->sideTemplate = "side.message.htm";
		$this->AWEBP_Page();
	}

	function process(&$smarty) {
		$admin = adesk_admin_get();
		$this->setTemplateData($smarty);

		if (!$this->admin["pg_message_add"] && !$this->admin["pg_message_edit"]) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		$smarty->assign("content_template", "campaign_new_template.htm");

		$lists = list_select_array();

		$campaignid = (int)adesk_http_param("id");

		if ($campaignid < 1)
			adesk_http_redirect("desk.php");

		campaign_save_markpos("template", $campaignid);

		$isEdit = false;
		$showAllMessages = false;

		adesk_smarty_submitted($smarty, $this);

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

		# Go to the message assets if we've already created one.
		$c = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #campaign_message WHERE campaignid = '$campaignid'");
		if ($c > 0) {
			$smarty->assign("hasmessage", 1);
		} else {
			$smarty->assign("hasmessage", 0);
		}

		# tags
		
		
				$admin = adesk_admin_get();
		
		$uid = $admin['id'];
	if($uid != 1 ){
	$lists = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");

//sandeep
	//get the lists of users
	
	@$liststr = implode("','",$lists);
	}
	else
	{
	@$liststr = implode("','", $admin["lists"]);
	}
		
		
		
		
		//$liststr = implode("','", $admin["lists"]);
		$templatelist = adesk_sql_select_list("SELECT templateid FROM #template_list WHERE listid IN ('0', '$liststr')");
		$templatestr = implode("','", $templatelist);
		$tags = adesk_sql_select_array("
			SELECT
				t.id,
				t.tag,
				(SELECT COUNT(*) FROM #template_tag r WHERE r.tagid = t.id AND r.templateid IN ('$templatestr')) AS `count`
			FROM
				#tag t
			ORDER BY
				`count`
		");
		$smarty->assign("tags", $tags);

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
		campaign_save();
		campaign_save_after();

		if ($GLOBALS["campaign_save_id"] > 0)
			adesk_http_redirect("desk.php?action=campaign_new_template&id=$GLOBALS[campaign_save_id]");
	}
}

?>
