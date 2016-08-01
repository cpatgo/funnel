<?php

require_once adesk_admin("functions/personalization.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
class personalization_assets extends AWEBP_Page {

	function personalization_assets() {
		$this->pageTitle = _a("Sender Sersonalization");
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
		$smarty->assign("content_template", "personalization.htm");

		$so = new adesk_Select;

		// list filter
		$filterArray = personalization_filter_post();
		$filter = $filterArray['filterid'];
		if ($filter > 0) {
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '{$this->admin['id']}' AND sectionid = 'personalization'");
			$so->push($conds);
		}
		$smarty->assign("filterid", $filter);
		$smarty->assign("listfilter", ( isset($_SESSION['nla']) ? $_SESSION['nla'] : null ));

		// get count
		$so->count();
		$total = (int)adesk_sql_select_one(personalization_select_query($so));
		$count = $total;

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=personalization');
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'personalization.personalization_select_array_paginator';
		$smarty->assign('paginator', $paginator);

		$sections = array(
			array("col" => "tag", "label" => _a("Personalization Tag")),
			array("col" => "name", "label" => _a("Personalization Name")),
			array("col" => "format", "label" => _a("Format")),
			array("col" => "content", "label" => _a("Content")),
		);
		$smarty->assign("search_sections", $sections);

		$fields = list_get_fields(array(), true); // no list id's, but global
		$smarty->assign("fields", $fields);
	}
}

?>
