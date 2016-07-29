<?php

require_once adesk_admin("functions/template.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
class template_assets extends AWEBP_Page {

	function template_assets() {
		$this->pageTitle = _a("Templates");
		$this->AWEBP_Page();
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		if (!$this->admin["pg_template_add"] && !$this->admin["pg_template_edit"] && !$this->admin["pg_template_delete"]) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		if ( list_get_cnt() == 0 ) {
			$smarty->assign('content_template', 'nolists.htm');
			return;
		}
		$smarty->assign("side_content_template", "side.campaign.htm");
		$smarty->assign("content_template", "template.htm");

		$so = new adesk_Select;

		// list filter
		$filterArray = template_filter_post();
		$filter = $filterArray['filterid'];
		if ($filter > 0) {
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '{$this->admin['id']}' AND sectionid = 'template'");
			$so->push($conds);
		}
		$smarty->assign("filterid", $filter);
		$smarty->assign("listfilter", ( isset($_SESSION['nla']) ? $_SESSION['nla'] : null ));

		// get count
		//$so->count();
		//$total = (int)adesk_sql_select_one(template_select_query($so, 0));
		// Using template_select_query() strips out the JOIN stuff, but still passes "WHERE l.listid = ...", so total is always 0
		$total = (int)adesk_sql_num_rows(adesk_sql_query("SELECT COUNT(*) as count FROM #template t INNER JOIN #template_list l ON t.id = l.templateid WHERE l.listid != 0 GROUP BY l.templateid"));
		$count = $total;

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=template');
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'template.template_select_array_paginator';
		$smarty->assign('paginator', $paginator);

		$sections = array(
			array("col" => "name", "label" => _a("Template Name")),
			array("col" => "content", "label" => _a("Content")),
		);
		$smarty->assign("search_sections", $sections);

		// clear out any temporary template preview or import files in cache folder
		$cache_clear = template_cache_clear();

		$fields = list_get_fields(array(), true); // no list id's, but global
		$smarty->assign("fields", $fields);
	}
}

?>
