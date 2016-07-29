<?php

require_once adesk_admin("functions/campaign.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");

class campaign_assets extends AWEBP_Page {

	function campaign_assets() {
		$this->pageTitle = _a("Campaigns");
		//$this->sideTemplate = "side.message.htm";
		$this->AWEBP_Page();
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		$reportsOnly = (bool)adesk_http_param('reports');
		$smarty->assign("reportsOnly", $reportsOnly);

		if ( $reportsOnly ) {
			$smarty->assign("side_content_template", "side.report.htm");
			if (!permission("pg_reports_campaign")) {
				$smarty->assign('content_template', 'noaccess.htm');
				return;
			}
			elseif ( list_get_cnt() == 0 ) {
				$smarty->assign('content_template', 'nolists.htm');
				return;
			}
		} else {
			$smarty->assign("side_content_template", "side.campaign.htm");
			if (!$this->admin["pg_message_add"] && !$this->admin["pg_message_edit"] && !$this->admin["pg_message_delete"] && !$this->admin["pg_message_send"]) {
				$smarty->assign('content_template', 'noaccess.htm');
				return;
			}
			elseif ( list_get_cnt() == 0 ) {
				$smarty->assign('content_template', 'nolists.htm');
				return;
			}
		}

		$smarty->assign("content_template", "campaign.htm");

		$so = new adesk_Select;

		if ( $reportsOnly ) {
			// all except for drafts
			$_POST['status'] = array(1, 2, 3, 4, 5, 6, 7);
		}

		// list filter
		$filterArray = campaign_filter_post();
		$filter = $filterArray['filterid'];
		if ($filter > 0) {
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '{$this->admin['id']}' AND sectionid = 'campaign'");
			$so->push($conds);
		}
		$smarty->assign("filterid", $filter);
		$smarty->assign("listfilter", ( isset($_SESSION['nla']) ? $_SESSION['nla'] : null ));

		$so->count();
		$total = (int)adesk_sql_select_one(campaign_select_query($so));
		$count = $total;

		$smarty->assign('campaignscnt', (int)adesk_sql_select_one("SELECT COUNT(*) FROM #campaign"));

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=campaign' . ( $reportsOnly ? '&reports=1' : '' ));
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'campaign.campaign_select_array_paginator';
		$smarty->assign('paginator', $paginator);

		$so = new adesk_Select();
		$so->count('DISTINCT(l.subscriberid)');
		$subs = (int)adesk_sql_select_one(subscriber_select_query($so));
		$smarty->assign('subscriberscnt', $subs);

		$sections = array(
			array("col" => "c.name", "label" => _a("Name")),
			array("col" => "_message_subject", "label" => _a("Subject(s)")),
			array("col" => "_message_from", "label" => _a("From Name/Email")),
			array("col" => "_message_body", "label" => _a("Email Body")),
		);
		$smarty->assign("search_sections", $sections);

		$statuses = campaign_statuses();
		$smarty->assign("statuses", $statuses);

		$types = campaign_types();
		$smarty->assign("types", $types);

		$recur_intervals = campaign_recur_intervals();
		$smarty->assign("recur_intervals", $recur_intervals);
	}
}

?>
