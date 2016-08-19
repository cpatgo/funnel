<?php
if(!session_id()) session_start();
require_once adesk_admin("functions/subscriber.php");
require_once adesk_admin("functions/optinoptout.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
class subscriber_assets extends AWEBP_Page {

	function subscriber_assets() {
		$this->pageTitle = _a("Subscribers");
		$this->AWEBP_Page();
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		if (!permission("pg_subscriber_add") && !permission("pg_subscriber_edit") && !permission("pg_subscriber_delete")) {
			adesk_smarty_noaccess($smarty);
			return;
		}

		if ( list_get_cnt() == 0 ) {
			$smarty->assign('content_template', 'nolists.htm');
			return;
		}

		$smarty->assign("content_template", "subscriber.htm");
		$smarty->assign("side_content_template", "side.subscriber.htm");

		$so = new adesk_Select;

		// subscriber search
		$query = trim((string)adesk_http_param('q'));
		if ( $query ) $_POST["qsearch"] = $query;

		// list filter
		$filterArray = subscriber_filter_post();
		$filter = $filterArray['filterid'];
		$filter_content = ""; // used to pre-populate search box

		if (adesk_http_param("filterid")) {
			if (adesk_http_param("search")) {
				$filter = (int)adesk_http_param("search");
				if (adesk_http_param_exists("content")) $filter_content = urldecode( adesk_http_param("content") );
			} else {
				$filterArray = subscriber_filter_segment(adesk_http_param("filterid"));
				$filter = $filterArray["filterid"];
			}
			$segmentid = intval(adesk_http_param("filterid"));
			$smarty->assign("segmentname", adesk_sql_select_one("SELECT name FROM #filter WHERE id = '$segmentid'"));
			$smarty->assign("segmentid", $segmentid);
		}

		$smarty->assign("filter_content", $filter_content);

		if ($filter > 0) {
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '{$this->admin['id']}' AND sectionid = 'subscriber'");
			$so->push($conds);
		}

		$smarty->assign("filterid", $filter);
		$smarty->assign("listfilter", ( isset($_SESSION['nla']) ? $_SESSION['nla'] : null ));
		$smarty->assign("statfilter", ( isset($_SESSION['Aawebdesk_subscriber_status']) ? $_SESSION['Aawebdesk_subscriber_status'] : 1 ));

		// get count
		$so->count('DISTINCT(l.subscriberid)');
		$total = (int)adesk_sql_select_one(subscriber_select_query($so));
		$count = $total;

		$paginator = new Pagination($total, $count, $this->admin['subscribers_per_page'], 0, 'desk.php?action=subscriber');
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'subscriber.subscriber_select_array_paginator';
		$smarty->assign('paginator', $paginator);

		$sections = array(
			array("col" => "s.email", "label" => _a("Email")),
			array("col" => "l.first_name", "label" => _a("First Name")),
			array("col" => "l.last_name", "label" => _a("Last Name")),
		);
		$smarty->assign("search_sections", $sections);

		$fields_listfilter = ( isset($_SESSION['nla']) ) ? $_SESSION['nla'] : $GLOBALS["admin"]["lists"];

		$fields = list_get_fields($fields_listfilter, true); // no list id's, but global
		$smarty->assign("fields", $fields);

		$so = new adesk_Select();
		$so->push("AND o.optin_confirm = 1");
		$optins = optinoptout_select_array($so);
		$smarty->assign("optins", $optins);


		$lists = list_get_all(false, true, null);
		foreach ( $lists as $k => $v ) {
			$lists[$k]['existingresponders'] = (int)adesk_sql_select_one("
				SELECT
					COUNT(*)
				FROM
					#campaign c,
					#campaign_list l
				WHERE
					c.id = l.campaignid
				AND
					l.listid = '$v[id]'
				AND
					c.status != 0
				AND
					c.sdate < NOW()
				AND
					c.type = 'responder'
			");
			$lists[$k]['existingcampaigns'] = (int)adesk_sql_select_one("
				SELECT
					COUNT(*)
				FROM
					#campaign c,
					#campaign_list l
				WHERE
					c.id = l.campaignid
				AND
					l.listid = '$v[id]'
				AND
					c.status != 0
				AND
					c.sdate < NOW()
				AND
					c.type IN ('single', 'recurring', 'deskrss', 'split', 'text')
			");
		}
		$listsCnt = count($lists);
		$smarty->assign('subscriberLists', $lists);
		$smarty->assign('subscriberListsCnt', $listsCnt);

		$smarty->assign("selected_list_id", $_SESSION['selected_list_id']);

	}
}

?>
