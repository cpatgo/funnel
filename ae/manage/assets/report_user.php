<?php

require_once adesk_admin("functions/report_user.php");
require_once adesk_admin("functions/report_group.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
class report_user_assets extends AWEBP_Page {

	function report_user_assets() {
		$this->pageTitle = _a("User Reports");
		$this->sideTemplate = "";
		$this->AWEBP_Page();
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		if (!$this->admin["pg_reports_user"]) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}
		$smarty->assign("side_content_template", "side.report.htm");

		$smarty->assign("content_template", "report_user.htm");

		if (isset($_GET["print"]) && $_GET["print"] == 1) {
			$smarty->assign("uselistfilter", 0);
			$smarty->assign("usemainmenu", 0);
			$smarty->assign("usehelplink", 0);
			$smarty->assign("useacctlinks", 0);
			$smarty->assign("useresendlink", 0);
			$this->sideTemplate = "";
		}

		// find provided group
		$gid = (int)adesk_http_param('id');
		if ( !adesk_admin_ismaingroup() ) {
			// reset the group
			list($gid) = each($this->admin['groups']);
		}
		$group = false;
		if ( $gid ) {
			require_once(awebdesk_functions('group.php'));
			$group = adesk_group_select_row($gid);
		}
		$smarty->assign('gid', $gid);
		$smarty->assign('group', $group);

		$so = new adesk_Select;

		// list filter
		$filter     = (int)adesk_http_param("filterid");
		$filterName = ( $group ? 'report_user' : 'report_group' );
		if ( $filter == 0 ) {
			$filterArray = ( $group ? report_user_filter_post() : report_group_filter_post() );
			$filter = $filterArray['filterid'];
		}
		if ( $filter > 0 ) {
			$so = select_filter_comment_parse($so, $filter, $filterName);
		}

		$smarty->assign("filterid", $filter);
		$smarty->assign("assets", $filterName);
		$smarty->assign("listfilter", ( isset($_SESSION['nla']) ? $_SESSION['nla'] : null ));
		$smarty->assign("datefilter", ( isset($_SESSION['report_user_datetime']) ? $_SESSION['report_user_datetime'] : 'all' ));
		$smarty->assign("datefrom", ( isset($_SESSION['report_user_datetimefrom']) ? $_SESSION['report_user_datetimefrom'] : adesk_CURRENTDATE ));
		$smarty->assign("dateto", ( isset($_SESSION['report_user_datetimeto']) ? $_SESSION['report_user_datetimeto'] : adesk_CURRENTDATE ));

		if ( adesk_http_param_exists("export") ) {
			$this->export($so, $group, $filter);
		}

		if ( $group ) {
			// add conditions here
			// ...

			// fetch counts
			$so->count();
			$total = (int)adesk_sql_select_one(report_user_select_query($so, $gid));
			$count = $total;

			// setup paginator
			$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=report_user&id=' . $gid);
			$paginator->allowLimitChange = true;
			$paginator->ajaxAction = 'report_user.report_user_select_array_paginator';
			$smarty->assign('paginator', $paginator);
		} else {
			// add conditions here
			// ...

			// fetch counts
			$so->count();
			$total = (int)adesk_sql_select_one(report_group_select_query($so));
			$count = $total;

			// setup paginator
			$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=report_user');
			$paginator->allowLimitChange = true;
			$paginator->ajaxAction = 'report_group.report_group_select_array_paginator';
			$smarty->assign('paginator', $paginator);
		}
	}

	function export($so, $group/*, $filterid*/) { // filter already assigned

		adesk_http_header_attach("export.csv", 0, "text/csv");

		if ( $group ) {
			// user list export
			require_once adesk_admin("functions/report_user.php");
			$rows = report_user_select_array($so, null, $group['id'], $filterid = 0);
			foreach ( $rows as $k => $v ) {
				$rows[$k]['epd'] = round($v['epd'], 2);
			}
			echo adesk_array_csv(
				$rows,
				array(_a("Username"), _a("# Campaigns"), _a("# Emails"), _a("Avg. Emails/Day")),
				array("username", "campaigns", "emails", "epd")
			);
		} else {
			// group list export
			require_once adesk_admin("functions/report_group.php");
			$titles = array(
				'day' => _a("%s per day"),
				'week' => _a("%s per week"),
				'month' => _a("%s per month"),
				'month1st' => _a("%s per calendar month (counting from the 1st)"),
				'monthcdate' => _a("%s per calendar month (counting from the user's creation day)"),
				'year' => _a("%s per year"),
				'ever' => _a("%s total")
			);
			$rows = report_group_select_array($so, null, $filterid = 0);
			foreach ( $rows as $k => $v ) {
				$rows[$k]['epd'] = round($v['epd'], 2);
				if ( $v['limit_mail'] ) {
					$rows[$k]['emaillimit'] = sprintf($titles[$v['limit_mail_type']], $v['limit_mail']);
				} else {
					$rows[$k]['emaillimit'] = _a("N/A");
				}
			}
			echo adesk_array_csv(
				$rows,
				array(_a("Group"), _a("# Campaigns"), _a("# Emails"), _a("Avg. Emails/Day"), _a("Limits")),
				array("title", "campaigns", "emails", "epd", "emaillimit")
			);
		}

		exit;
	}
}

?>
