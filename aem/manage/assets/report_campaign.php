<?php

require_once adesk_admin("functions/report_campaign.php");
require_once adesk_admin("functions/campaign.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("page.php");
require_once awebdesk_classes("pagination.php");

require_once adesk_admin("functions/open.php");
require_once adesk_admin("functions/forward.php");
require_once adesk_admin("functions/bounce_data.php");
require_once adesk_admin("functions/bounce_management.php");
require_once adesk_admin("functions/list_field.php");
require_once adesk_admin("functions/filter.php");


class report_campaign_assets extends AWEBP_Page {

	function report_campaign_assets() {
		$this->pageTitle = _a("Campaign Reports");
		$this->sideTemplate = "";
		$this->AWEBP_Page();
	}

	function bouncesetup($campaignid) {
		$campaignid = intval($campaignid);
		$c          = adesk_sql_select_one("
			SELECT
				COUNT(*)
			FROM
				#bounce b,
				#bounce_list l
			WHERE
				b.id = l.bounceid
			AND
				b.`type` != 'none'
			AND
				l.listid IN (
					SELECT
						cl.listid
					FROM
						#campaign_list cl
					WHERE
						cl.campaignid = '$campaignid'
				)
		");

		return $c > 0;
	}

	function canaccess($campaign) {
		if ( !$campaign ) return false;
		foreach ($campaign["lists"] as $list) {
			if (in_array($list["id"], $this->admin["lists"]))
				return true;
		}
		return false;
	}

	function process(&$smarty) {
		if (!$this->admin["pg_reports_campaign"]) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		$smarty->assign("h1_style", "");
		if (isset($_GET["print"]) && $_GET["print"] == 1) {
			$smarty->assign("uselistfilter", 0);
			$smarty->assign("usemainmenu", 0);
			$smarty->assign("usehelplink", 0);
			$smarty->assign("useacctlinks", 0);
			$smarty->assign("useresendlink", 0);
			$smarty->assign("h1_style", "margin: 0;");
			$this->sideTemplate = "";
		}

		$smarty->assign('isShared', false);

		$this->setTemplateData($smarty);

		$campaignid = intval(adesk_http_param('id'));
		$filterid   = intval(adesk_http_param("filterid"));
		$export     = adesk_http_param("export");
		$hash       = strval(adesk_http_param("hash"));

		$s = (string)trim(adesk_http_param("s"));
		$smarty->assign("subscriberhash", $s);

		$smarty->assign("bouncesetup", $this->bouncesetup($campaignid));

		if ($export && $campaignid > 0) {
			adesk_http_header_attach("export.csv", 0, "text/csv");
			echo $this->export($export, $campaignid, $filterid);
			exit;
		}

		$smarty->assign("content_template", "report_campaign.htm");

		$admin = adesk_admin_get();

		$campaign = campaign_select_row($campaignid);

		if (!$this->canaccess($campaign)) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		if ($campaign['status'] == 0) {
		  // if Draft, redirect to Create Campaign process
		  adesk_http_redirect('desk.php?action=campaign_new&id=' . $campaignid);
		}

		$mlist    = adesk_sql_select_array("
			SELECT
				cm.messageid,
				cm.spamcheck_score,
				cm.spamcheck_max,
				IF(m.format = 'mime' OR m.format = 'text', 1, 0) AS a_hastext,
				IF(m.format = 'mime' OR m.format = 'html', 1, 0) AS a_hashtml,
				IF(( m.subject IS NULL OR m.subject = '' ), m.html, m.subject) AS `subject`,
				IF(( m.name IS NULL OR m.name = '' ), m.html, m.name) AS `name`
			FROM
				#campaign_message cm,
				#message m
			WHERE
				cm.campaignid = '$campaignid'
			AND cm.messageid  = m.id
		");

		if (count($mlist) < 1)
			$campaign["a_messageid"] = 0;
		else
			$campaign["a_messageid"] = $mlist[0]["messageid"];

		if ($campaign["filterid"] > 0)
			$campaign["filter"] = filter_select_row($campaign["filterid"]);

		$smarty->assign("campaign", $campaign);
		$smarty->assign("messages", $mlist);

		// Update group permission table to reflect the current campaign viewed
		// We then use this information to decide whether to show link on startup
		$groupids = implode(",", $admin["groups"]);
		$sql = adesk_sql_update("#group", array("pg_startup_reports" => $campaignid), "id IN ($groupids)");

		# Opens

		$total = 0;
		$count = 0;

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=open');
		$paginator->ajaxURL .= "?hash=$hash";
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'open.open_select_array_paginator';
		$smarty->assign('paginator_open', $paginator);

		# Forwards

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=forward');
		$paginator->ajaxURL .= "?hash=$hash";
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'forward.forward_select_array_paginator';
		$smarty->assign('paginator_forward', $paginator);

		# Bounces

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=bounce_data');
		$paginator->ajaxURL .= "?hash=$hash";
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'bounce_data.bounce_data_select_array_paginator';
		$smarty->assign('paginator_bounce', $paginator);

		# Unsubscriptions

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=subscriber');
		$paginator->ajaxURL .= "?hash=$hash";
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'unsubscriber.unsubscriber_select_array_paginator';
		$smarty->assign('paginator_unsub', $paginator);

		# Unopens

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=unopen');
		$paginator->ajaxURL .= "?hash=$hash";
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'unopen.unopen_select_array_paginator';
		$smarty->assign('paginator_unopen', $paginator);

		# Links

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=link');
		$paginator->ajaxURL .= "?hash=$hash";
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'link.link_select_array_paginator';
		$smarty->assign('paginator_link', $paginator);

		# Link info

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=link');
		$paginator->ajaxURL .= "?hash=$hash";
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'linkinfo.linkinfo_select_array_paginator';
		$smarty->assign('paginator_linkinfo', $paginator);

		# Updates

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=update');
		$paginator->ajaxURL .= "?hash=$hash";
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'update.update_select_array_paginator';
		$smarty->assign('paginator_update', $paginator);

		# Social Sharing

		$paginator = new Pagination($total, $count, 20, 0, 'desk.php?action=socialsharing');
		$paginator->ajaxURL .= "?hash=$hash";
		$paginator->allowLimitChange = true;
		$paginator->ajaxAction = 'socialsharing.socialsharing_select_array_paginator';
		$smarty->assign('paginator_socialsharing', $paginator);

		$logFile = ( adesk_admin_ismain() and file_exists(adesk_cache_dir('campaign-' . $campaignid . '.log')) );
		$smarty->assign('logFile', $logFile);

		$status_array = campaign_statuses();
		$smarty->assign('status_array', $status_array);

		$type_array = campaign_type();
		$type_array['special'] = _a("Autoresponder sent on creation.");
		$smarty->assign('type_array', $type_array);

		//$webcopy = $this->site['p_link'] . '/forward3.php?l=' . $campaign['lists'][0]['id'] . '&c=' . $campaignid . '&m=' . $campaign['messages'][0]['id'] . '&s=0';
		$webcopy = $this->site['p_link'] . '/index.php?action=social&c=' . md5($campaignid) . '.' . $campaign['messages'][0]['id'];
		$webcopy_seo = $this->site['p_link'] . "/social/" . md5($campaignid) . "." . $campaign['messages'][0]['id'] . "/like";
		$smarty->assign('webcopy', $webcopy);
		$smarty->assign('webcopy_seo', $webcopy_seo);

		require_once adesk_admin('functions/personalization.php');
		require_once adesk_admin('functions/socialsharing.php');
		// used for "Social Share" link in campaign reports section - the individual icons
		$socialsharing_sources = personalization_social_networks();
		foreach ($socialsharing_sources as $source) {
			// get all the external social share links, IE: twitter.com?share...
			// we then use these in the social.share.inc.htm template
			$ref = ($source == "stumbleupon") ? "referral" : "ref";
			if ( isset($campaign['messages'][0]) ) {
				$process_link = socialsharing_process_link($campaignid, $campaign['messages'][0]['id'], 0, $webcopy . "&" . $ref . "=" . $source);
				$process_link = $process_link[0];
				$smarty->assign('shareURL_' . $source . '_external', $process_link);
			}
		}

		$smarty->assign('socialsharing_enabled', false);
		if ( function_exists('curl_init') && function_exists('hash_hmac') && (int)PHP_VERSION > 4 ) {
			$smarty->assign('socialsharing_enabled', true);
			require_once adesk_admin("functions/socialsharing.php");
			$socialsharing_data = socialsharing_data_cache_write($campaignid, $campaign['messages'][0]['id'], $webcopy);
			$smarty->assign('webcopy_facebook_bitly', $socialsharing_data["facebook_bitly"]);
			$smarty->assign('webcopy_twitter_bitly', $socialsharing_data["twitter_bitly"]);
		}

		// fetch campaign sender
		$senduser = adesk_sql_select_row("SELECT username, first_name, last_name FROM #user WHERE id = '$campaign[userid]'");
		if ( $senduser ) {
			$senduser['fullname'] = $senduser['first_name'] . ' ' . $senduser['last_name'];
		}
		$smarty->assign('senduser', $senduser);
	}

	//If you want this function to find first_name and last_name as well, you must also provide $campaignid
	function export_fields(&$so, $fieldids, $subscribercol, $campaignid = null) {
		$i    = 0;
		$rval = array();

		if($campaignid)
		{
			$lists = adesk_sql_select_list("SELECT listid FROM #campaign_list WHERE campaignid = '$campaignid'");

			$so->slist[] = "(SELECT first_name FROM #subscriber_list WHERE subscriberid = $subscribercol AND listid = $lists[0] LIMIT 1) AS first_name";
			$rval[] = "first_name";
			$so->slist[] = "(SELECT last_name FROM #subscriber_list WHERE subscriberid = $subscribercol AND listid = $lists[0] LIMIT 1) AS last_name";
			$rval[] = "last_name";
		}

		foreach ($fieldids as $fid) {
			$so->slist[] = "(SELECT fv.val FROM #list_field_value fv WHERE fv.fieldid = '$fid' AND fv.relid = $subscribercol) AS subscriberfield$i";
			$rval[] = "subscriberfield$i";
			$i++;
		}

		return $rval;
	}

	function export($mode, $campaignid, $filterid) {
		$so    = new adesk_Select;
		$admin = $GLOBALS["admin"];
		if ($filterid > 0) {
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filterid' AND userid = '$admin[id]' AND sectionid = 'open'");
			$so->push($conds);
		}

		$fields      = list_field_getfields($campaignid);
		$fieldids    = adesk_array_extract($fields, "id");
		$fieldtitles = adesk_array_extract($fields, "title");


		switch ($mode) {
			case "open":
				require_once adesk_admin("functions/open.php");
				$so->push("AND l.messageid = '0'");	# Don't count opens from specific messages.
				$aliases = $this->export_fields($so, $fieldids, "ld.subscriberid", $campaignid);
				$rows = $this->custom_fields_check_blanks(open_select_array($so, null, $campaignid));
				return adesk_array_csv(
					$rows,
					array_merge(array(_a("Email"), _a("Date"), _a("# Times"), _a("First Name"), _a("Last Name")), $fieldtitles),
					array_merge(array("email", "tstamp", "times"/*, "first_name", "last_name"*/), $aliases)
				);
			case "unopen":
				require_once adesk_admin("functions/unopen.php");
				$_GET["id"] = $campaignid;
				$aliases = $this->export_fields($so, $fieldids, "l.subscriberid", $campaignid);
				$rows = $this->custom_fields_check_blanks(unopen_select_array($so, null));
				return adesk_array_csv(
					$rows,
					array_merge(array(_a("Email"), _a("Date"), _a("First Name"), _a("Last Name")), $fieldtitles),
					array_merge(array("email", "tstamp"/*, "first_name", "last_name"*/), $aliases)
				);
			case "link":
				require_once adesk_admin("functions/link.php");
				$_GET["id"] = $campaignid;
				$rows = $this->custom_fields_check_blanks(link_select_array($so, null));
				return adesk_array_csv(
					$rows,
					array(_a("Name"), _a("Link"), _a("Unique Clicks"), _a("Total Clicks")),
					array("name", "link", "a_unique", "a_total")
				);
			case "linkinfo":
				require_once adesk_admin("functions/linkinfo.php");
				$linkid     = intval(adesk_http_param("linkid"));
				$_GET["id"] = $linkid;
				$aliases = $this->export_fields($so, $fieldids, "ld.subscriberid", $campaignid);
				$rows = $this->custom_fields_check_blanks(linkinfo_select_array($so, null));
				return adesk_array_csv(
					$rows,
					array_merge(array(_a("Email"), _a("Date"), _a("# Times"), _a("First Name"), _a("Last Name")), $fieldtitles),
					array_merge(array("email", "tstamp", "times"/*, "first_name", "last_name"*/), $aliases)
				);
			case "forward":
				require_once adesk_admin("functions/forward.php");
				$aliases = $this->export_fields($so, $fieldids, "f.subscriberid", $campaignid);
				$rows = $this->custom_fields_check_blanks(forward_select_array($so, null, $campaignid));
				return adesk_array_csv(
					$rows,
					array_merge(array(_a("Email"), _a("Date"), _a("# Times"), _a("Message"), _a("First Name"), _a("Last Name")), $fieldtitles),
					array_merge(array("email_from", "tstamp", "a_times", "brief_message"/*, "first_name", "last_name"*/), $aliases)
				);
			case "bounce":
				require_once adesk_admin("functions/bounce_data.php");
				$aliases = $this->export_fields($so, $fieldids, "b.subscriberid", $campaignid);
				$rows = $this->custom_fields_check_blanks(bounce_data_select_array($so, null, $campaignid));
				return adesk_array_csv(
					$rows,
					array_merge(array(_a("Email"), _a("Date"), _a("Code"), _a("Type"), _a("Description"), _a("First Name"), _a("Last Name")), $fieldtitles),
					array_merge(array("email", "tstamp", "code", "type", "descript"/*, "first_name", "last_name"*/), $aliases)
				);
			case "unsub":
				require_once adesk_admin("functions/unsubscriber.php");
				$aliases = $this->export_fields($so, $fieldids, "l.subscriberid", $campaignid);
				$rows = $this->custom_fields_check_blanks(unsubscriber_select_array($so, null, $campaignid));
				return adesk_array_csv(
					$rows,
					array_merge(array(_a("Email"), _a("Date"), _a("Reason"), _a("First Name"), _a("Last Name")), $fieldtitles),
					array_merge(array("email", "udate", "unsubreason"/*, "first_name", "last_name"*/), $aliases)
				);
			case "update":
				require_once adesk_admin("functions/update.php");
				$aliases = $this->export_fields($so, $fieldids, "u.subscriberid", $campaignid);
				$rows = $this->custom_fields_check_blanks(update_select_array($so, null, $campaignid));
				return adesk_array_csv(
					$rows,
					array_merge(array(_a("Email"), _a("Date"), _a("First Name"), _a("Last Name")), $fieldtitles),
					array_merge(array("a_email", "tstamp"/*, "first_name", "last_name"*/), $aliases)
				);
			case "socialsharing":
				require_once adesk_admin("functions/socialsharing.php");
				$rows = socialsharing_data_cache_read(null, $campaignid, "all", true);
				return adesk_array_csv(
					$rows,
					array(_a("Source"), _a("Name"), _a("Content"), _a("Published")),
					array("source", "name", "content", "published")
				);
		}

	}

	function custom_fields_check_blanks($rows) {
		foreach ( $rows as $rowid => $row ) {
			foreach ( $row as $k => $v ) {
				if ( substr($k, 0, strlen('subscriberfield')) == 'subscriberfield' ) {
					$rows[$rowid][$k] = adesk_custom_fields_check_blank($v);
				}
			}
		}
		return $rows;
	}
}

?>
