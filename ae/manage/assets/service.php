<?php

require_once adesk_admin("functions/service.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
class service_assets extends AWEBP_Page {

	function service_assets() {
		$this->pageTitle = _a("External Services");
		$this->sideTemplate = "side.settings.htm";
		$this->AWEBP_Page();
	}

	function process(&$smarty) {
		$this->setTemplateData($smarty);

		if ( !adesk_admin_ismaingroup() ) {
			adesk_smarty_noaccess($smarty);
			return;
		}

		if (adesk_site_hosted_rsid()) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		$smarty->assign("content_template", "service.htm");

		$user_password = adesk_sql_select_one("SELECT password FROM aweb_globalauth WHERE username = '" . $GLOBALS['admin']['username'] . "'");
		$user_password = md5($user_password);
		$smarty->assign("user_password", $user_password);

		$so = new adesk_Select;
		$so->count();
		$total = (int)adesk_sql_select_one(service_select_query($so));
		$count = $total;

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=service');
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'service.service_select_array_paginator';
		$smarty->assign('paginator', $paginator);

	}
}

?>
