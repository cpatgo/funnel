<?php

require_once(awebdesk_classes('page.php'));
require_once adesk_admin("functions/subscriber.php");
require_once adesk_admin("functions/campaign.php");
require_once adesk_admin("functions/list.php");
require_once awebdesk_classes("pagination.php");

class archive_assets extends AWEBP_Page {
	function archive_assets() {
		$this->pageTitle = _p("Archive");
		parent::AWEBP_Page();
		$this->getParams();
	}

	function getParams() {
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		if (!$this->site["general_public"] && $_SERVER['REQUEST_METHOD'] != 'GET') {
				adesk_smarty_redirect($smarty, $this->site["p_link"] . "/manage/");
		}

		$listid = ( $_SESSION['nlp'] and !is_array($_SESSION['nlp']) ) ? (int)$_SESSION['nlp'] : 0;

		if ($listid == 0) {

			// Main Archive page: Viewing lists

			$filter_array = list_filter_post();
			$filterid = $filter_array["filterid"];
			$smarty->assign('filterid', $filterid);

			$total = 2;
			$count = 2;
			$display_per_page = 25;

			$paginator = new Pagination($total, $count, $display_per_page, 0, 'unused');
			$paginator->ajaxURL = $GLOBALS["site"]["p_link"] . "/awebdeskapi.php";
			$paginator->ajaxAction = 'list.list_select_array_paginator_public';
			$smarty->assign('paginator', $paginator);
		}
		else {

			// Campaigns within a list

			//$_SESSION['nlp'] = $listid;

			$list_stringid = adesk_http_param('list_stringid');
			$smarty->assign("list_stringid", $list_stringid);

			$list = list_select_row($listid, false);
			$smarty->assign("list", $list);

			$so = new adesk_Select;

			$_POST["status"] = array(1,2,3,4,5);
			$filterArray = campaign_filter_post();
			$filter = $filterArray['filterid'];
			if ($filter > 0) {
				$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '{$this->admin['id']}' AND sectionid = 'campaign'");
				$so->push($conds);
			}

			$smarty->assign("filterid", $filter);

			// get count
			$so->count();
			$total = (int)adesk_sql_select_one(campaign_select_query($so));
			$count = $total;
			$display_per_page = 25;

			$paginator = new Pagination($total, $count, $display_per_page, 0, 'unused');
			$paginator->ajaxURL = $GLOBALS["site"]["p_link"] . "/awebdeskapi.php";
			$paginator->ajaxAction = 'campaign.campaign_select_array_paginator_public';
			$smarty->assign('paginator', $paginator);
		}

		$smarty->assign("listid", $listid);
		$listfilter = ( isset($_SESSION['nlp']) ? $_SESSION['nlp'] : null );
		$smarty->assign("listfilter", $listfilter);

		$smarty->assign("content_template", "archive.htm");

		if (isset($_GET["rss"])) {
			$this->rss($listid);
		}
	}

	function rss($listid) {

		$site = adesk_site_get();

		require_once awebdesk_functions("rss.php");

		$so = new adesk_Select;

		if ($listid != 0) {
			$list = list_select_row($listid, false);
			$title = _p("Public messages from List") . " '" . $list["name"] . "'";
			$description = _p("Public messages from List") . " '" . $list["name"] . "'";
			$so->push("AND l.listid = '$listid'");
		}
		else {
			// Pull all public list ids
			$listids = array_keys(list_get_all());

			$listids = implode("','", $listids);

			$so->push("AND l.listid IN ('$listids')");

			$title = _p("Public messages from all Lists");
			$description = _p("Public messages from all Lists");
		}

		$so->push("AND c.public = 1");
		$so->push("AND ( c.type IN ('responder', 'reminder') OR ( c.type != 'special' AND c.status = 5 ) )");

		$so->orderby("c.sdate DESC");
		$so->limit("0, 10");

		$campaigns = campaign_select_array($so);
		$items = array();

		foreach ($campaigns as $v) {
			// Call again since campaign_select_array() doesn't pull fullinfo
			$campaign = campaign_select_prepare($v, true);

			// Just the first message
			if ( count($campaign["messages"]) > 0 ) {
				$message = $campaign["messages"][0];

				$items[] = array(
					"title"       => $message["subject"],
					"link"        => $site["p_link"] . "/index.php?action=message&c=" . $v["id"] . "&message=" . $message["id"],
					"description" => adesk_str_preview(($message['format'] != 'text' ? $message["html"] : $message["text"] )),
					"pubDate"     => ( $campaign['sdate'] ? gmstrftime("%a, %d %m %Y %H:%M:%S GMT", strtotime($campaign['sdate'])) : '' ),
				);
			}
		}

		$rss = array(
			"title"       => $title,
			"link"        => $site["p_link"] . "/index.php?action=archive" . ( $listid != 0 ? '&nl=' . $listid : '' ),
			"description" => $description,
			//"pubDate"     => gmstrftime("%a, %d %m %Y %H:%M:%S GMT"),
			//"language"    => _i18n("utf-8"),
			"item"        => $items
		);

		adesk_rss_echo($rss);
	}
}

?>