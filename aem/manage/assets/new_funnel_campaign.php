<?php

// require_once adesk_admin("functions/funnel_campaign.php");
require_once adesk_admin("functions/template.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");

class new_funnel_campaign_assets extends AWEBP_Page {

	function new_funnel_campaign_assets() {
		$this->pageTitle = _a("Create a New Funnel Campaign");
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

		$smarty->assign("content_template", "new_funnel_campaign.htm");
	}
}

?>
