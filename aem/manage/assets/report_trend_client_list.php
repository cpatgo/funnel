<?php

//require_once adesk_admin("functions/report_user.php");
require_once adesk_admin("functions/report_trend_client_list.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
class report_trend_client_list_assets extends AWEBP_Page {

	function report_trend_client_list_assets() {
		$this->pageTitle = _a("Email Clients Trend");
		$this->sideTemplate = "";
		$this->AWEBP_Page();
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		if (!$this->admin["pg_reports_trend"]) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}
		$smarty->assign("side_content_template", "side.report.htm");

		$smarty->assign("content_template", "report_trend_client_list.htm");

		if (isset($_GET["print"]) && $_GET["print"] == 1) {
			$smarty->assign("uselistfilter", 0);
			$smarty->assign("usemainmenu", 0);
			$smarty->assign("usehelplink", 0);
			$smarty->assign("useacctlinks", 0);
			$smarty->assign("useresendlink", 0);
			$this->sideTemplate = "";
		}

		// find provided group
		$lid = (int)adesk_http_param('id');
		$list = false;
		if ( $lid ) {
			$list = list_select_row($lid, false);
		}
		if ( !$list ) {
			adesk_http_redirect('desk.php?action=report_trend_client');
		}
		$smarty->assign('lid', $lid);
		$smarty->assign('list', $list);

		$so = new adesk_Select;

		// list filter
		$filter     = (int)adesk_http_param("filterid");
		$filterName = 'report_trend_client_list';
		if ( $filter == 0 ) {
			$filterArray = report_trend_client_list_filter_post();
			$filter = $filterArray['filterid'];
		}
		if ( $filter > 0 ) {
			$so = select_filter_comment_parse($so, $filter, $filterName);
		}

		$smarty->assign("filterid", $filter);
		$smarty->assign("datefilter", ( isset($_SESSION['report_trend_client_list_datetime']) ? $_SESSION['report_trend_client_list_datetime'] : 'all' ));
		$smarty->assign("datefrom", ( isset($_SESSION['report_trend_client_list_datetimefrom']) ? $_SESSION['report_trend_client_list_datetimefrom'] : adesk_CURRENTDATE ));
		$smarty->assign("dateto", ( isset($_SESSION['report_trend_client_list_datetimeto']) ? $_SESSION['report_trend_client_list_datetimeto'] : adesk_CURRENTDATE ));

		if ( adesk_http_param_exists("export") ) {
			$this->export($so, $list, $filter);
		}

		// add conditions here
		// ...

		// fetch counts
		$so->count();
		//dbg(adesk_prefix_replace(report_trend_client_list_select_query($so, $lid)));
		$total = (int)adesk_sql_select_one(report_trend_client_list_select_query($so, $lid));
		$count = $total;

		// setup paginator
		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=report_trend_client_list&id=' . $lid);
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'report_trend_client_list.report_trend_client_list_select_array_paginator';
		$smarty->assign('paginator', $paginator);
	}

	function export($so, $list/*, $filterid*/) { // filter already assigned

		adesk_http_header_attach("export.csv", 0, "text/csv");

		// user list export
		require_once adesk_admin("functions/report_trend_client_list.php");
		$rows = report_trend_client_list_select_array($so, $list['id'], $filterid = 0);
		/*
		foreach ( $rows as $k => $v ) {
			$rows[$k]['epd'] = round($v['epd'], 2);
		}
		*/
		echo adesk_array_csv(
			$rows,
			array(_a("Email Client"), _a("Hits"), _a("Percentage")),
			array("name", "hits", "perc")
		);

		exit;
	}
}

?>
