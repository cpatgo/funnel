<?php

require_once adesk_admin("functions/optinoptout.php");
require_once adesk_admin("functions/form.php");
require_once adesk_admin("functions/template.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
class optinoptout_assets extends AWEBP_Page {

	function optinoptout_assets() {
		$this->pageTitle = _a("Email Confirmation Sets");
		$this->AWEBP_Page();
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		if (!$this->admin["pg_list_opt"]) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		$smarty->assign("content_template", "optinoptout.htm");
		$smarty->assign("side_content_template", "side.list.htm");

		$so = new adesk_Select;

		// list filter
		$filterArray = optinoptout_filter_post();
		$filter = $filterArray['filterid'];
		if ($filter > 0) {
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '{$this->admin['id']}' AND sectionid = 'optinoptout'");
			$so->push($conds);
		}
		$smarty->assign("filterid", $filter);
		$smarty->assign("listfilter", ( isset($_SESSION['nla']) ? $_SESSION['nla'] : null ));

		// get count
		$so->count();
		$total = (int)adesk_sql_select_one(optinoptout_select_query($so));
		$count = $total;

		//$liststr = implode("','", $GLOBALS['admin']['lists']);
		//$lists = adesk_sql_select_array("SELECT id, name FROM #list WHERE id IN ('$liststr')");
		//changes by sandeep on 3rd July 2012
		$userid = $this->admin['id'];
		$lists = adesk_sql_select_array("SELECT id, name FROM #list WHERE userid = '$userid'");
		
		$smarty->assign("lists", $lists);

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=optinoptout');
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'optinoptout.optinoptout_select_array_paginator';
		$smarty->assign('paginator', $paginator);

		$sections = array(
			array("col" => "name", "label" => _a("Email Confirmation Set")),
			array("col" => "optin_from_name", "label" => _a("Opt-In From Name")),
			array("col" => "optin_from_email", "label" => _a("Opt-In From Email")),
			array("col" => "optin_subject", "label" => _a("Opt-In Subject")),
			array("col" => "optin_text", "label" => _a("Opt-In Text")),
			array("col" => "optin_html", "label" => _a("Opt-In HTML")),
			array("col" => "optout_from_name", "label" => _a("Opt-Out From Name")),
			array("col" => "optout_from_email", "label" => _a("Opt-Out From Email")),
			array("col" => "optout_subject", "label" => _a("Opt-Out Subject")),
			array("col" => "optout_text", "label" => _a("Opt-Out Text")),
			array("col" => "optout_html", "label" => _a("Opt-Out HTML")),
		);
		$smarty->assign("search_sections", $sections);

		$fields = list_get_fields(array(), true); // no list id's, but global
		$smarty->assign("fields", $fields);

		$so = new adesk_Select();
		$so->select(array('t.id', 't.userid', 't.name', 't.subject', 't.categoryid', 't.preview_mime'));
		$templates = template_select_array($so);
		$smarty->assign("templates", $templates);
		$smarty->assign("templatesCnt", count($templates));
	}
}

?>
