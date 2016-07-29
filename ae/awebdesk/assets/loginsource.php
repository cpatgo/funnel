<?php

require_once awebdesk_functions("loginsource.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");

class loginsource_assets extends AWEBP_Page {

	function loginsource_assets() {
		$this->pageTitle = _a("Login Sources");
		$this->AWEBP_Page();
	}

	function process(&$smarty) {

		if ( !adesk_admin_ismaingroup() || isset($GLOBALS['_hosted_account']) ) {
			adesk_smarty_noaccess($smarty, $this);
			return;
		}

		adesk_loginsource_sync();
		$smarty->assign("content_template", "loginsource.htm");

		$so = new adesk_Select;
		$so->count();
		$total = (int)adesk_sql_select_one(adesk_loginsource_select_query($so));
		$count = $total;

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=loginsource');
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'loginsource!adesk_loginsource_select_array_paginator';
		$smarty->assign('paginator', $paginator);
		$this->setTemplateData($smarty);

		require_once awebdesk_functions("group.php");
		$so = new adesk_Select;
		$so->push("AND id != 1");	# Skip the visitor group
		$groups = adesk_sql_select_array(adesk_group_select_query($so, false));
		$smarty->assign("groups", $groups);

		if (adesk_ihook_exists("acg_loginsource_assets"))
			$smarty = adesk_ihook("acg_loginsource_assets", $smarty);
	}
}

?>
