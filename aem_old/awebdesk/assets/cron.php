<?php

require_once awebdesk_functions("cron.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");

class cron_assets extends AWEBP_Page {

	function cron_assets() {
		$this->pageTitle = _a("Cron Manager/Monitor");
		$this->sideTemplate = $GLOBALS['adesk_sidemenu_settings'];
		$this->AWEBP_Page();
	}

	function process(&$smarty) {
		$this->setTemplateData($smarty);
		$smarty->assign("content_template", "cron.htm");

		if (adesk_ihook_exists("adesk_cron_assets_pre"))
			$smarty = adesk_ihook("adesk_cron_assets_pre", $smarty);

		// check for privileges first!
		if ( !adesk_admin_ismaingroup() || ( isset($GLOBALS['_hosted_account']) and !isset($_SESSION['adesk_arc_login']) ) ) {
			// assign template
			adesk_smarty_noaccess($smarty, $this);
			return;
		}

		$so = new adesk_Select;
		$so->count();
		$total = (int)adesk_sql_select_one(adesk_cron_select_query($so));
		$count = $total;

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=cron');
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'cron!adesk_cron_select_array_paginator';
		$smarty->assign('paginator', $paginator);

		$sections = array(
			array("col" => "stringid", "label" => _a("Cron Identifier")),
			array("col" => "name", "label" => _a("Cron Name")),
			array("col" => "descript", "label" => _a("Cron Description")),
			array("col" => "filename", "label" => _a("Cron File")),
		);
		$smarty->assign("search_sections", $sections);

		// day of week
		$weekdays = array(-1 => _a("Every day of the week"));

		for ( $i = 0; $i < 7; $i++ ) $weekdays[$i] = adesk_date_dayofweek($i);
		$smarty->assign("weekdays", $weekdays);
		// day of month
		$monthdays = array(-1 => _a("Every day of the month"));
		for ( $i = 1; $i < 32; $i++ ) $monthdays[$i] = $i;
		$smarty->assign("monthdays", $monthdays);
		// hours
		$hours = array(-1 => '*');
		for ( $i = 0; $i < 24; $i++ ) $hours[$i] = $i;
		$smarty->assign("hours", $hours);
		// hours
		$minutes1 = array(-1 => '*');
		$minutes2 = array(-2 => '-');
		for ( $i = 0; $i < 60; $i++ ) $minutes1[$i] = $minutes2[$i] = $i;
		$smarty->assign("minutes1", $minutes1);
		$smarty->assign("minutes2", $minutes2);

		$smarty->assign("cronBasePath", adesk_basedir());

		// figure out server type
		$smarty->assign("isWindows", strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');

		// figure out which ones are protected ones
		$smarty->assign('cron_protected', $GLOBALS['adesk_cron_protected']);
	}
}

?>
