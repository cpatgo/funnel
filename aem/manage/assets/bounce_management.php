<?php

require_once adesk_admin("functions/bounce_management.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");

class bounce_management_assets extends AWEBP_Page {

	function bounce_management_assets() {
		$this->pageTitle = _a("Bounce Settings");
		$this->AWEBP_Page();
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		if (!$this->admin["pg_list_bounce"] || isset($GLOBALS["_hosted_account"])) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		$smarty->assign("content_template", "bounce_management.htm");
		$smarty->assign("side_content_template", "side.list.htm");

		$so = new adesk_Select;

		// list filter
		$filterArray = bounce_management_filter_post();
		$filter = $filterArray['filterid'];
		if ($filter > 0) {
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '{$this->admin['id']}' AND sectionid = 'bounce_management'");
			$so->push($conds);
		}
		$smarty->assign("filterid", $filter);
		$smarty->assign("listfilter", ( isset($_SESSION['nla']) ? $_SESSION['nla'] : null ));

		// get count
		$so->count();
		$total = (int)adesk_sql_select_one(bounce_management_select_query($so));
		$count = $total;

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=bounce_management');
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'bounce_management.bounce_management_select_array_paginator';
		$smarty->assign('paginator', $paginator);

		$sections = array(
			array("col" => "email", "label" => _a("E-mail Address")),
			array("col" => "host", "label" => _a("POP3 Host Name")),
			array("col" => "user", "label" => _a("POP3 Username")),
		);

		$smarty->assign("search_sections", $sections);
	}
}

?>
