<?php

// require_once adesk_admin("functions/campaign.php");
//require_once adesk_admin("functions/filter.php");
//require_once adesk_admin("functions/message.php");
require_once adesk_admin("functions/template.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");

class landingpage_new_assets extends AWEBP_Page {

	function campaign_new_assets() {
		$this->pageTitle = _a("Create a New Landing Page");
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

		$smarty->assign("content_template", "landingpage_new.htm");
	}
}

?>
