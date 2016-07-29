<?php

require_once adesk_admin("functions/campaign.php");
//require_once adesk_admin("functions/filter.php");
//require_once adesk_admin("functions/message.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
require_once awebdesk_functions("ajax.php");

class campaign_use_assets extends AWEBP_Page {

	function campaign_use_assets() {
		$this->pageTitle = _a("Reuse an Existing Campaign");
		//$this->sideTemplate = "side.message.htm";
		$this->AWEBP_Page();
	}

	function process(&$smarty) {
		$this->setTemplateData($smarty);

		if (!$this->admin["pg_message_add"] && !$this->admin["pg_message_edit"]) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		if ( !withinlimits('campaign', $this->admin["campaigns_sent"] + 1) ) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		$smarty->assign("content_template", "campaign_use.htm");

		// check if form is submitted
		adesk_smarty_submitted($smarty, $this);

		$campaignid = (int)adesk_http_param('copyid');
		$action = (string)adesk_http_param('filter');
		if ( $action != 'unread' ) $action = 'newsub';

		// decide new hidden filter's action
		$params = array('type' => $action);

		$campaign = false;
		if ( $campaignid > 0 ) {
			$campaign = campaign_select_row($campaignid, true, false);
		}
		if ( !$campaign ) {
			adesk_http_redirect('desk.php?action=campaign');
		}

		// reset campaign info based on action
		if ( $action == 'unread' ) {
			//
			$params['campaignid'] = $campaignid;
		} elseif ( $action == 'newsub' ) {
			//
			$params['sdate'] = $campaign['sdate'];
		}

		// fetch current campaign filter
		$campaign['filter'] = filter_select_row($campaign['filterid']);
		$params["filterid"] = $campaign["filterid"];
		$params["filter"]   = $campaign["filter"];

		if (!filter_allows_campaignuse($params["filterid"])) {
			return;
		}

		// create a new hidden filter
		$filter = filter_hidden($params);

		if ($params["type"] == "newsub" || $params["type"] == "unread") {
			$campaign["filterid"] = $filter;
			$campaign["filter"]   = filter_select_row($filter);
		}

		// list filter
		$listfilter = ( isset($_SESSION['nla']) ? $_SESSION['nla'] : null );

		$lists = explode('-', $campaign['listslist']);

		// get new subscribers count (with new filter applied)
		$total = campaign_subscribers_fetch($lists, $filter, $fetchCount = 1);
//dbg($campaign);

		// assign all presets
		$smarty->assign('campaignid', $campaignid);
		$smarty->assign('mode', $action);
		$smarty->assign('filter', $filter);
		$smarty->assign('total', $total);
		$smarty->assign('campaign', $campaign);
		$smarty->assign('listfilter', $listfilter);
	}

	function formProcess(&$smarty) {
		// result is 0 if campaign is not initialized
		$r = array(
			'id' => 0,
			'use' => (int)adesk_http_param('id'),
			'filter' => (int)$_POST["filter"],	# Believe it or not, we also pass a GET version of filter.
			'name' => adesk_http_param('name'),
		);
		// fetch old campaign
		$campaign = campaign_select_row($r['use']);
		if ( !$campaign ) return adesk_ajax_api_result(false, _a("Campaign not provided."), $r);
		// assign name
		if ( $r['name'] ) {
			$campaign['name'] = $r['name'];
		} else {
			$r['name'] = $campaign['name'];
		}
		// assign new filter
		$campaign['filterid'] = $r['filter'];
		// set current time as sending date
		if ( isset($campaign['sdate']) ) unset($campaign['sdate']);
		if ( isset($campaign['ldate']) ) unset($campaign['ldate']);
		// set status to scheduled
		$campaign['status'] = 1;
		$r['id'] = campaign_copy($campaign, array('=sdate' => 'NOW()'));
		if ( $r['id'] == 0 ) {
			return adesk_ajax_api_result(false, _a("Campaign could not be copied."), $r);
		} else {
			// initiate a new campaign
			campaign_init($r['id'], false);
			return adesk_ajax_api_result(true, _a("Campaign has been initiated."), $r);
		}
	}

}

?>
