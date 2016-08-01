<?php

require_once adesk_admin("functions/filter.php");
require_once adesk_admin("functions/campaign.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
class filter_assets extends AWEBP_Page {

	function filter_assets() {
		$this->pageTitle = _a("List Segments");
		$this->AWEBP_Page();
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		if ( list_get_cnt() == 0 ) {
			$smarty->assign('content_template', 'nolists.htm');
			return;
		}

		if (!permission("pg_subscriber_filters")) {
			adesk_smarty_noaccess($smarty);
			return;
		}

		$smarty->assign("side_content_template", "side.list.htm");
		$smarty->assign("content_template", "filter.htm");

		$so = new adesk_Select;

		// list filter
		$filterArray = filter_filter_post();
		$filter = $filterArray['filterid'];
		if ($filter > 0) {
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '{$this->admin['id']}' AND sectionid = 'filter'");
			$so->push($conds);
		}
		$smarty->assign("filterid", $filter);
		$smarty->assign("listfilter", ( isset($_SESSION['nla']) ? $_SESSION['nla'] : null ));

		$so->count();
		$total = (int)adesk_sql_select_one(filter_select_query($so));
		$count = $total;

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=filter');
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'filter.filter_select_array_paginator';
		$smarty->assign('paginator', $paginator);

		$sections = array(
			array("col" => "name", "label" => _a("Name")),
		);
		$smarty->assign("search_sections", $sections);

		$fields     = list_get_fields($GLOBALS["admin"]["lists"], true); # Get all subscriber fields.
		$fieldsbyid = array();
		foreach ($fields as $fk => $fv) {
			$lists    = adesk_sql_select_list("SELECT name FROM #list WHERE id IN (SELECT relid FROM #list_field_rel WHERE fieldid = '$fv[id]')");

			if (count($lists) == 0)
				$listname = " (GLOBAL)";
			else
				$listname = " (" . adesk_str_shorten(implode(", ", $lists), 16) . ")";

			$fields[$fk]["title"] .= $listname;
			$fieldsbyid[$fv["id"]] = $fields[$fk];
		}

		$smarty->assign("filter_fields", $fieldsbyid);

		$so = new adesk_Select();
		$so->push("AND c.type != 'special'");
		$campaigns = campaign_select_array();
		$smarty->assign("campaigns", $campaigns);
	}
}

?>
