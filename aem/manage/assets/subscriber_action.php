<?php

require_once adesk_admin("functions/subscriber_action.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
require_once adesk_admin("functions/campaign.php");

class subscriber_action_assets extends AWEBP_Page {

	function subscriber_action_assets() {
		$this->pageTitle = _a("Subscriber Actions");
		$this->AWEBP_Page();
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		if (!permission("pg_subscriber_actions")) {
			adesk_smarty_noaccess($smarty);
			return;
		}

		$smarty->assign("content_template", "subscriber_action.htm");
		$smarty->assign("side_content_template", "side.list.htm");

		$so = new adesk_Select;

		// list filter
		$filterArray = subscriber_action_filter_post();
		$filter = $filterArray['filterid'];
		if ($filter > 0) {
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '{$this->admin['id']}' AND sectionid = 'subscriber_action'");
			$so->push($conds);
		}
		$smarty->assign("filterid", $filter);
		$smarty->assign("listfilter", ( isset($_SESSION['nla']) ? $_SESSION['nla'] : null ));

		// get count
		$so->count();
		$total = (int)adesk_sql_select_one(subscriber_action_select_query($so));
		$count = $total;

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=subscriber_action');
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'subscriber_action.subscriber_action_select_array_paginator';
		$smarty->assign('paginator', $paginator);

		$sections = array(
			array("col" => "r.name", "label" => _a("Name")),
		);

		$smarty->assign("search_sections", $sections);

		# Basically lifted from the campaign_new assets.
		$so = new adesk_Select();
		$so->push("AND (
			( c.type IN ('responder', 'reminder') AND c.status IN (1, 6) )
			OR
			( c.type IN ('single', 'recurring', 'split', 'deskrss', 'text') AND c.status NOT IN (0, 1, 6, 7) )
		)"); // all of them!
		$so->orderby("c.sdate, c.name"); // sort by sending date
		$campaigns = campaign_select_array($so);
		$smarty->assign("campaigns", $campaigns);

		$fields = list_get_fields(array(), true); // no list id's, but global
		$smarty->assign("fields", $fields);
	}
}

?>
