<?php

require_once awebdesk_functions("processes.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
class processes_assets extends AWEBP_Page {

	function processes_assets() {
		$this->pageTitle = _a("Current Processes");
		$this->sideTemplate = $GLOBALS['adesk_sidemenu_settings'];
		$this->AWEBP_Page();
	}

	function process(&$smarty) {

		$smarty->assign("content_template", "processes.htm");

		$so = new adesk_Select;

		// check for passed status
		if ( isset($_GET['status']) and $_GET['status'] ) $_POST['status'] = $_GET['status'];

		// list filter
		$filterArray = adesk_processes_filter_post();
		$filter = $filterArray['filterid'];
		if ($filter > 0) {
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '{$this->admin['id']}' AND sectionid = 'processes'");
			$so->push($conds);
		} else {
			$so->push("AND `completed` < `total`"); // active = DEFAULT
			$so->push("AND `ldate` IS NOT NULL"); // active, STALLED INCLUDED
			//$so->push("AND UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(`ldate`) < 4 * 60"); // active BUT NOT STALLED
		}
		$smarty->assign("filterid", $filter);
		$smarty->assign("actionfilter", '');
		$smarty->assign("statusfilter", 'active');

		$total = (int)adesk_process_select_count($so);
		$count = $total;

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=processes');
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'processes!adesk_processes_select_array_paginator';
		$smarty->assign('paginator', $paginator);

		$sections = array(
			array("col" => "action", "label" => _a("Process Command")),
			array("col" => "data", "label" => _a("Process Data")),
		);
		$smarty->assign("search_sections", $sections);


		$smarty->assign("spawn", (bool)adesk_http_param('spawn'));

		// get all actions of this app
		$actions = adesk_ihook('adesk_process_actions');
		$smarty->assign("actions", $actions);

		$this->setTemplateData($smarty);
	}
}

?>
