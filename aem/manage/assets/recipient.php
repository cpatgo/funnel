<?php

require_once adesk_admin("functions/recipient.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
class recipient_assets extends AWEBP_Page {

	function recipient_assets() {
		$this->pageTitle = _a("Campaign Recipients");
		$this->sideTemplate = "side.subscriber.htm";
		$this->AWEBP_Page();
	}

	function process(&$smarty) {
		$this->setTemplateData($smarty);
		$smarty->assign("content_template", "recipient.htm");

		// get send id
		$sid = (int)adesk_http_param('id');
		if ( $sid < 1 ) {
			adesk_http_redirect(adesk_site_alink());
		}
		// check if X table exists
		$sql = adesk_sql_query("SHOW TABLES LIKE 'em\_x$sid'");
		if ( !$sql or !mysql_num_rows($sql) ) {
			adesk_http_redirect(adesk_site_alink());
		}
		$smarty->assign('sid', $sid);

		$so = new adesk_Select;
		$so->count();
		$total = (int)adesk_sql_select_one(recipient_select_query($so, $sid));
		$count = $total;

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=recipient');
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'recipient.recipient_select_array_paginator';
		$smarty->assign('paginator', $paginator);

		$sections = array(
			array("col" => "email", "label" => _a("Email Address")),
			array("col" => "name", "label" => _a("Subscriber Name")),
			array("col" => "sdate", "label" => _a("Subscribe Date")),
		);
		$smarty->assign("search_sections", $sections);

	}
}

?>
