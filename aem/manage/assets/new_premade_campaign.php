<?php

// require_once adesk_admin("functions/funnel_campaign.php");
require_once adesk_admin("functions/template.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");

class new_funnel_campaign_assets extends AWEBP_Page {

	function new_funnel_campaign_assets() {
		$this->pageTitle = _a("Create a Premade Campaign");
		//$this->sideTemplate = "side.message.htm";
		$this->AWEBP_Page();
	}

	function process(&$smarty) {
		$this->setTemplateData($smarty);

		if (!$this->admin["pg_message_add"] && !$this->admin["pg_message_edit"]) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		// get global custom fields
		$fields = list_get_fields(array(), true); // no list id's, but global
		$smarty->assign("optional_fields", $fields);

		$smarty->assign("content_template", "new_premade_campaign.htm");
	}
}

?>
