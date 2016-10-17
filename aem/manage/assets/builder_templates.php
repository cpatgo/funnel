<?php

require_once adesk_admin("functions/database.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
require_once awebdesk_functions("file.php");

class builder_templates extends AWEBP_Page {
	function builder_templates() {
		$this->pageTitle = _a("Builder Templates");
		$this->AWEBP_Page();
		$this->admin = $GLOBALS["admin"];
	}

	function process(&$smarty) {
		$this->setTemplateData($smarty);
		if (isset($GLOBALS["_hosted_account"])) {
			adesk_smarty_noaccess($smarty, $this);
			return;
		}

		$so = new adesk_Select;
		$templates = adesk_sql_select_array("SELECT * FROM #funnel_campaign");

		$smarty->assign('templateList', $templates);
		$smarty->assign("content_template", "builder_templates.htm");

	}
}

?>