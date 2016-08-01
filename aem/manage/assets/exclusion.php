<?php

require_once adesk_admin("functions/exclusion.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
class exclusion_assets extends AWEBP_Page {

	function exclusion_assets() {
		$this->pageTitle = _a("Exclusion List");
		$this->AWEBP_Page();
	}

	function process(&$smarty) {
		$admin = $GLOBALS["admin"];

		if (!$admin["pg_list_edit"]) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		$this->setTemplateData($smarty);

		if ( !$this->admin['pg_subscriber_delete'] ) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		if ( list_get_cnt() == 0 ) {
			$smarty->assign('content_template', 'nolists.htm');
			return;
		}

		$smarty->assign("content_template", "exclusion.htm");
		$smarty->assign("side_content_template", "side.subscriber.htm");

		$so = new adesk_Select;

		// list filter
		$filterArray = exclusion_filter_post();
		$filter = $filterArray['filterid'];
		if ($filter > 0) {
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '{$this->admin['id']}' AND sectionid = 'exclusion'");
			$so->push($conds);
		}
		$smarty->assign("filterid", $filter);
		$smarty->assign("listfilter", ( isset($_SESSION['nla']) ? $_SESSION['nla'] : null ));

		// get count
		$so->count();
		$total = (int)adesk_sql_select_one(exclusion_select_query($so));
		$count = $total;

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=exclusion');
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'exclusion.exclusion_select_array_paginator';
		$smarty->assign('paginator', $paginator);

		$sections = array(
			array("col" => "email", "label" => _a("Email Matching Pattern")),
		);
		$smarty->assign("search_sections", $sections);
	}
}

?>
