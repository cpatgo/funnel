<?php

require_once adesk_admin("functions/emailaccount.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
class emailaccount_assets extends AWEBP_Page {

	function emailaccount_assets() {
		$this->pageTitle = _a("Subscriptions by Email");
		$this->AWEBP_Page();
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		if (!$this->admin["pg_list_emailaccount"]) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		if ( isset($GLOBALS['_hosted_account']) and !adesk_admin_ismaingroup() ) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		if ( list_get_cnt() == 0 ) {
			$smarty->assign('content_template', 'nolists.htm');
			return;
		}

		$smarty->assign("content_template", "emailaccount.htm");
		$smarty->assign("side_content_template", "side.list.htm");

		$so = new adesk_Select;

		// list filter
		$filterArray = emailaccount_filter_post();
		$filter = $filterArray['filterid'];
		if ($filter > 0) {
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '{$this->admin['id']}' AND sectionid = 'emailaccount'");
			$so->push($conds);
		}
		$smarty->assign("filterid", $filter);
		$smarty->assign("listfilter", ( isset($_SESSION['nla']) ? $_SESSION['nla'] : null ));

		// get count
		$so->count();
		$total = (int)adesk_sql_select_one(emailaccount_select_query($so));
		$count = $total;

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=emailaccount');
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'emailaccount.emailaccount_select_array_paginator';
		$smarty->assign('paginator', $paginator);

		$sections = array(
			array("col" => "e.email", "label" => _a("Email Address")),
			array("col" => "e.type", "label" => _a("Type")),
			array("col" => "e.action", "label" => _a("Action")),
			array("col" => "e.host", "label" => _a("Host")),
			array("col" => "e.user", "label" => _a("User")),
		);
		$smarty->assign("search_sections", $sections);

		// hosted vars
		$hosted_domain = '';
		if ( isset($GLOBALS['_hosted_account']) ) {
			$hosted_domain = isset($_SESSION[$GLOBALS['domain']]['account']) ? $_SESSION[$GLOBALS['domain']]['account'] : $GLOBALS['domain'];
		}
		$smarty->assign('hosted_domain', $hosted_domain);
	}
}

?>
