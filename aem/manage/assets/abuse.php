<?php

require_once adesk_admin("functions/abuse.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
class abuse_assets extends AWEBP_Page {

	function abuse_assets() {
		$this->pageTitle = _a("Abuse Complaints");
		$this->sideTemplate = "";
		$this->AWEBP_Page();
	}

	function process(&$smarty) {
		$this->setTemplateData($smarty);
		$smarty->assign("content_template", "abuse.htm");

		if ( isset($GLOBALS['_hosted_account']) and !isset($_SESSION['adesk_arc_login']) ) {
			adesk_http_redirect(adesk_site_alink());
		}

		$so = new adesk_Select;
		$so->count();
		$total = (int)adesk_sql_select_one(abuse_select_query($so));
		$count = $total;

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=abuse');
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'abuse.abuse_select_array_paginator';
		$smarty->assign('paginator', $paginator);

	}
}

?>
