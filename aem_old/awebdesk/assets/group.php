<?php

require_once awebdesk_functions("group.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
class group_assets extends AWEBP_Page {

	function group_assets() {
		$this->pageTitle = _a("Groups");
		$this->sideTemplate = "";
		$this->AWEBP_Page();
		if ( adesk_site_isknowledgebuilder() and !adesk_site_isstandalone() ) {
			adesk_http_redirect($this->site['p_link2'] . '/manage/desk.php?action=group');
		}
	}

	function process(&$smarty) {
		$this->setTemplateData($smarty);

		$smarty = adesk_ihook("adesk_group_assets_post", $smarty);

		if ($this->admin["id"] != 1 && !$smarty->getvar("_group_can_add") && !$smarty->getvar("_group_can_edit") && !$smarty->getvar("_group_can_delete")) {
			adesk_smarty_noaccess($smarty);
			return;
		}

		$smarty->assign("content_template", "group.htm");

		$so = new adesk_Select;
		$so->count();
		$total = adesk_sql_select_one(adesk_group_select_query($so));
		$count = $total;

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=group');
		$paginator->ajaxAction = 'group!adesk_group_select_array_paginator';
		$smarty->assign('paginator', $paginator);

		$sections = array(
			array("col" => "title", "label" => _a("Titles")),
			array("col" => "descript", "label" => _a("Descriptions")),
		);

		$smarty->assign("search_sections", $sections);
	}
}

?>
