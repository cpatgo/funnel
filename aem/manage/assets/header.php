<?php

require_once adesk_admin("functions/header.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
class header_assets extends AWEBP_Page {

	function header_assets() {
		$this->pageTitle = _a("Custom Email Headers");
		$this->AWEBP_Page();
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		if (!$this->admin["pg_list_headers"]) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		if ( list_get_cnt() == 0 ) {
			$smarty->assign('content_template', 'nolists.htm');
			return;
		}

		$smarty->assign("content_template", "header.htm");
		$smarty->assign("side_content_template", "side.list.htm");

		$so = new adesk_Select;

		// list filter
		$filterArray = header_filter_post();
		$filter = $filterArray['filterid'];
		if ($filter > 0) {
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '{$this->admin['id']}' AND sectionid = 'header'");
			$so->push($conds);
		}
		$smarty->assign("filterid", $filter);
		$smarty->assign("listfilter", ( isset($_SESSION['nla']) ? $_SESSION['nla'] : null ));

		// get count
		$so->count();
		$total = (int)adesk_sql_select_one(header_select_query($so));
		$count = $total;

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=header');
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'header.header_select_array_paginator';
		$smarty->assign('paginator', $paginator);

		$sections = array(
			array("col" => "h.name", "label" => _a("Header Title")),
			array("col" => "h.header", "label" => _a("Header Name")),
			array("col" => "h.header", "label" => _a("Header Value")),
		);

		$fields = list_get_fields(array(), true); // no list id's, but global
		$smarty->assign("fields", $fields);

		$smarty->assign("search_sections", $sections);
	}
}

?>
