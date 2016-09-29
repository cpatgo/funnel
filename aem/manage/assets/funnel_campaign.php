<?php

// require_once adesk_admin("functions/list.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
require_once adesk_admin("functions/template.php");
class funnel_campaign_assets extends AWEBP_Page {

	function funnel_campaign_assets() {
		$this->pageTitle = _a("My Campaigns");
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

		$smarty->assign("content_template", "funnel_campaign.htm");

		$so = new adesk_Select;
		$query = sprintf("SELECT * FROM awebdesk_funnel_campaign WHERE user_id = %d", $this->admin['id']);
		$funnels = adesk_sql_select_array($query);

		$smarty->assign("funnels", $funnels);
	}
}

?>