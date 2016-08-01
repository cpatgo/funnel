<?php

require_once awebdesk_functions("privatemessage.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");

class privatemessage_assets extends AWEBP_Page {

	function privatemessage_assets() {
		$this->pageTitle = _a("Private Messages");
		$this->sideTemplate = "";
		$this->AWEBP_Page();
	}

	function process(&$smarty) {

		$smarty->assign("content_template", "privatemessage.htm");

		if (isset($_GET["rss"]) && intval($_GET["rss"]) === 1)
			$this->rss();

		$so = new adesk_Select;

		$userid = (int)adesk_http_param('uid');

		if ($userid) {
			$user = adesk_auth_record_id($userid);
			$smarty->assign("username", $user["username"]);
		}
		else {
			$smarty->assign("username", '');
		}

		// default list filter
		$_POST["privatemessage_filter"] = "user_to";
		$filterArray = adesk_privatemessage_filter_post();
		$filter = $filterArray['filterid'];
		if ($filter > 0) {
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '{$this->admin['id']}' AND sectionid = 'emailaccount'");
			$so->push($conds);
		} else {
			$so->push("AND ( p.user_from = '{$this->admin['id']}' OR p.user_to = '{$this->admin['id']}' )");
		}
		$smarty->assign("filterid", $filter);

		// get the filter ID for "sent" view - so we know when we're not filtering to Inbox or Sent view
		$_POST["privatemessage_filter"] = "user_from";
		$filterArray = adesk_privatemessage_filter_post();
		$filter_sent = $filterArray['filterid'];
		$smarty->assign("filterid_sent", $filter_sent);

		$so->count();
		$total = (int)adesk_sql_select_one(adesk_privatemessage_select_query($so));
		$count = $total;

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=privatemessage');
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'privatemessage!adesk_privatemessage_select_array_paginator';
		$smarty->assign('paginator', $paginator);

		$sections = array(
			array("col" => "title", "label" => _a("Subject")),
			array("col" => "content", "label" => _a("Content")),
		);

		$smarty->assign("search_sections", $sections);

		$user_md5 = md5($this->admin["id"] . $this->admin["email"]);
		$smarty->assign("user_md5", $user_md5);

		$this->setTemplateData($smarty);

		if (isset($_GET["export"]))
			$this->export($_GET["export"]);
	}

	function export($type) {

		$site = adesk_site_get();

		$qry = new adesk_Select();

		if ($type == 1) {
			// Inbox
			$filename = "Inbox";
			$qry->push("AND user_to = '{$this->admin['id']}'");
		}
		else {
			// Sent
			$filename = "Sent";
			$qry->push("AND user_from = '{$this->admin['id']}'");
		}

		$sql = $qry->query("
			SELECT
				p.id AS 'ID', p.cdate AS 'Sent Date', p.title AS 'Title', p.content AS 'Content',
				u1.absid AS 'user_from_moreinfo',
				u2.absid AS 'user_to_moreinfo'
			FROM
				#privmsg p,
				#user u1,
				#user u2
			WHERE
				[...]
				AND p.user_from = u1.id
				AND p.user_to = u2.id
		");

		$results = adesk_sql_select_array($sql);

		$privatemessages = array();

		foreach($results as $k => $v) {
			$privatemessages[$k]["ID"] = $v["ID"];
			$privatemessages[$k]["Sent Date"] = $v["Sent Date"];
			$privatemessages[$k]["Title"] = $v["Title"];
			$privatemessages[$k]["Content"] = $v["Content"];

			$from_info = adesk_auth_record_id($v["user_from_moreinfo"]);
			$to_info = adesk_auth_record_id($v["user_to_moreinfo"]);

			$privatemessages[$k]["From Username"] = $from_info["username"];
			$privatemessages[$k]["From Name"] = $from_info["first_name"] . " " . $from_info["last_name"];
			$privatemessages[$k]["To Username"] = $to_info["username"];
			$privatemessages[$k]["To Name"] = $to_info["first_name"] . " " . $to_info["last_name"];
		}

		if (adesk_http_param("export")) {
			header("Content-Type: text/csv");
			adesk_http_header_attach($filename . ".csv");
			echo adesk_array_csv($privatemessages, array("ID","Sent Date","Title","Content","From Username","From Name","To Username","To Name"));
			exit;
		}
	}

	function rss() {
		require_once awebdesk_functions("rss.php");

		$so = new adesk_Select();
		$so->limit("0, 10");
		$so->orderby(adesk_privatemessage_sort());
		$messages = adesk_privatemessage_select_array($so);
		$items    = array();

		foreach ( $messages as $row ) {
			$items[] = array(
				"title"       => $row['title'],
				"link"        => adesk_site_rwlink(array('action' => 'privatemessage', 'id' => $row['id'])),
				"description" => adesk_str_preview($row['content']),
				"pubDate"     => gmstrftime("%a, %d %m %Y %H:%M:%S GMT", strtotime($row['cdate'])),
			);
		}

		$rss = array(
			"title"       => sprintf(_p("%s Private Messages"), $this->site['site_name']),
			"link"        => adesk_site_rwlink(array('action' => 'privatemessage')),
			"description" => sprintf(_p("Private Messages published at %s."), $this->site['site_name']),
			"pubDate"     => gmstrftime("%a, %d %m %Y %H:%M:%S GMT"),
			"language"    => _i18n("utf-8"),
			"item"        => $items
		);

		adesk_rss_echo($rss);
	}
}

?>
