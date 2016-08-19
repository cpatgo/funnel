<?php

// require_once adesk_admin("functions/list.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
require_once adesk_admin("functions/template.php");
class home_assets extends AWEBP_Page {

	function home_assets() {
		$this->pageTitle = _a("Getting Started With the GLC E-Markter");
		$this->AWEBP_Page();
	}

	function process(&$smarty) {
		$this->setTemplateData($smarty);

		if (!$this->admin["pg_message_add"] && !$this->admin["pg_message_edit"]) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		$smarty->assign("content_template", "home.htm");
	}
}

?>