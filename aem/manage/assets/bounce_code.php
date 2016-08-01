<?php

require_once adesk_admin("functions/bounce_code.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
class bounce_code_assets extends AWEBP_Page {

	function bounce_code_assets() {
		$this->pageTitle = _a("Bounce Codes");
		$this->sideTemplate = "";
		$this->AWEBP_Page();
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		if ( !adesk_admin_ismain() ) {
			// assign template
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		if ( isset($GLOBALS['_hosted_account']) ) {
			// assign template
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		$smarty->assign("content_template", "bounce_code.htm");

		$so = new adesk_Select;
		$so->count();
		$total = (int)adesk_sql_select_one(bounce_code_select_query($so));
		$count = $total;

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=bounce_code');
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'bounce_code.bounce_code_select_array_paginator';
		$smarty->assign('paginator', $paginator);

		$sections = array(
			array("col" => "b.code", "label" => _a("Bounce Code")),
			array("col" => "b.match", "label" => _a("Matching String")),
			array("col" => "b.type", "label" => _a("Bounce Type")),
			array("col" => "b.descript", "label" => _a("Description")),
		);
		$smarty->assign("search_sections", $sections);
	}
}

?>
