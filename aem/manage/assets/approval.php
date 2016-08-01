<?php

require_once adesk_admin("functions/approval.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
class approval_assets extends AWEBP_Page {

	function approval_assets() {
		$this->pageTitle = _a("Campaign Approval Queue");
		$this->sideTemplate = "";
		$this->AWEBP_Page();
	}

	function process(&$smarty) {
		$this->setTemplateData($smarty);
		$smarty->assign("content_template", "approval.htm");

		$so = new adesk_Select;
		$so->count();
		$total = (int)adesk_sql_select_one(approval_select_query($so));
		$count = $total;

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=approval');
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'approval.approval_select_array_paginator';
		$smarty->assign('paginator', $paginator);

		$sections = array(
			array("col" => "sdate", "label" => _a("Submit Date")),
		);
		$smarty->assign("search_sections", $sections);

	}
}

?>
