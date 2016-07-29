<?php

//require_once adesk_admin("functions/report_user.php");
require_once adesk_admin("functions/report_list.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
class report_list_assets extends AWEBP_Page {

	function report_list_assets() {
		$this->pageTitle = _a("List Reports");
		$this->sideTemplate = "";
		$this->AWEBP_Page();
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		if (!$this->admin["pg_reports_list"]) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}
		$smarty->assign("side_content_template", "side.report.htm");
		$smarty->assign("content_template", "report_list.htm");

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
		$smarty->assign('lid', $lid);
		$smarty->assign('list', $list);

		$so = new adesk_Select;

		// list filter
		$filter     = (int)adesk_http_param("filterid");
		//$filterName = ( $list ? 'report_list2' : 'report_list' );
		$filterName = 'report_list';
		if ( $filter == 0 ) {
			//$filterArray = ( $list ? report_list2_filter_post() : report_list_filter_post() );
			$filterArray = report_list_filter_post();
			$filter = $filterArray['filterid'];
		}
		if ( $filter > 0 ) {
			$so = select_filter_comment_parse($so, $filter, $filterName);
		}

		$smarty->assign("filterid", $filter);
		$smarty->assign("datefilter", ( isset($_SESSION['report_list_datetime']) ? $_SESSION['report_list_datetime'] : 'all' ));
		$smarty->assign("datefrom", ( isset($_SESSION['report_list_datetimefrom']) ? $_SESSION['report_list_datetimefrom'] : adesk_CURRENTDATE ));
		$smarty->assign("dateto", ( isset($_SESSION['report_list_datetimeto']) ? $_SESSION['report_list_datetimeto'] : adesk_CURRENTDATE ));

		if ( adesk_http_param_exists("export") ) {
			$this->export($so, $list, $filter);
		}

		if ( $list ) {
			// add conditions here
			// ...

			// fetch counts
			$so->count();
			//dbg(adesk_prefix_replace(report_list_select_query($so, $lid)));
			$total = (int)adesk_sql_select_one(report_list_select_query($so, $lid));
			$count = $total;

			// setup paginator
			$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=report_list&id=' . $lid);
			$paginator->allowLimitChange = true;
			$paginator->ajaxAction = 'report_list.report_list_select_array_paginator';
			$smarty->assign('paginator', $paginator);
		} else {
			// add conditions here
			// ...

			// fetch counts
			$so->count();
			$total = (int)adesk_sql_select_one(report_list_select_query($so));
			$count = $total;

			// setup paginator
			$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=report_list');
			$paginator->allowLimitChange = true;
			$paginator->ajaxAction = 'report_list.report_list_select_array_paginator';
			$smarty->assign('paginator', $paginator);
		}
	}

	function export($so, $list/*, $filterid*/) { // filter already assigned

		adesk_http_header_attach("export.csv", 0, "text/csv");

		if ( $list ) {
			// user list export
			require_once adesk_admin("functions/report_list.php");
			$rows = report_list_select_array($so, $list['id'], $filterid = 0);
			foreach ( $rows as $k => $v ) {
				$rows[$k]['epd'] = round($v['epd'], 2);
			}
			echo adesk_array_csv(
				$rows,
				//array(_a("List"), _a("# Campaigns"), _a("# Emails"), _a("Avg. Emails/Day")),
				//array("name", "campaigns", "emails", "epd")
				array(_a("List"), _a("# Confirmed Subscribers"), _a("# Unconfirmed Subscribers"), _a("# Unsubscribed"), _a("# Bounces"), _a("# Emails"), _a("Avg. Emails/Day")),
				array("name", "subscribed", "unconfirmed", "unsubscribed", "bounced", "emails", "epd")
			);
		} else {
			// group list export
			require_once adesk_admin("functions/report_list.php");
			$rows = report_list_select_array($so, null, $filterid = 0);
			foreach ( $rows as $k => $v ) {
				$rows[$k]['epd'] = round($v['epd'], 2);
			}
			echo adesk_array_csv(
				$rows,
				//array(_a("Name"), _a("# Campaigns"), _a("# Emails"), _a("Avg. Emails/Day")),
				//array("name", "campaigns", "emails", "epd")
				array(_a("List"), _a("# Confirmed Subscribers"), _a("# Unconfirmed Subscribers"), _a("# Unsubscribed"), _a("# Bounces"), _a("# Emails"), _a("Avg. Emails/Day")),
				array("name", "subscribed", "unconfirmed", "unsubscribed", "bounced", "emails", "epd")
			);
		}

		exit;
	}
}

?>
