<?php

require_once adesk_admin("functions/template.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");

class builder_templates_assets extends AWEBP_Page {
	function builder_templates_assets() {
		$this->pageTitle = _a("Builder Templates");
		$this->AWEBP_Page();
	}

	function process(&$smarty) {
		$this->setTemplateData($smarty);
		if (!in_array('3', $this->admin['groups'])) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		$so = new adesk_Select;
		$templates = adesk_sql_select_array("SELECT * FROM #builder_templates");

		$smarty->assign('templateList', $templates);
		$smarty->assign("content_template", "builder_templates.htm");
	}
}

?>