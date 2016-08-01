<?php

require_once adesk_admin("functions/campaign.php");
require_once adesk_admin("functions/subscriber.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");

class campaign_new_summary_assets extends AWEBP_Page {

	function campaign_new_summary_assets() {
		$this->pageTitle = _a("Create a New Campaign");
		//$this->sideTemplate = "side.message.htm";
		$this->AWEBP_Page();
	}

	function process(&$smarty) {
		$this->setTemplateData($smarty);
		$admin = adesk_admin_get();

		if (!$this->admin["pg_message_add"] && !$this->admin["pg_message_edit"]) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		$smarty->assign("content_template", "campaign_new_summary.htm");

		$campaignid = (int)adesk_http_param("id");

		if ($campaignid < 1)
			adesk_http_redirect("desk.php");

		campaign_save_markpos("summary", $campaignid);

		$isEdit = false;
		$showAllMessages = false;

		adesk_smarty_submitted($smarty, $this);
		if ( isset($_SESSION["campaign_save_result"][$campaignid]) ) {
			$smarty->assign("formSubmitted", true);
			$smarty->assign("submitResult", $_SESSION["campaign_save_result"][$campaignid]);
			unset($_SESSION["campaign_save_result"][$campaignid]);
		}

		$row = campaign_select_row($campaignid);
		if ( $row ) {
			// use this campaign
			$campaign = $row;
			// campaign info
			if ( in_array($row['status'], array(0, 1, 3, 6, 7)) and !adesk_http_param('use') ) { // if not sending or completed
				// statuses that can be reused are : draft, scheduled, (while sending?) paused, stopped
				$campaign['id'] = $row['id']; // edit this campaign allowed
				$campaign['status'] = $row['status']; // reuse the same status
				if ( $row['status'] != 0 ) $isEdit = true;
			} else {
				adesk_http_redirect("desk.php?action=campaign_new&id=$campaignid");
			}

			if ( !$campaign['lists'] ) {
				adesk_http_redirect("desk.php?action=campaign_new_list&id=$campaignid");
			}
			if ( !$campaign['messages'] ) {
				$cp = $campaign['type'] == 'text' ? 'text' : 'template';
				adesk_http_redirect("desk.php?action=campaign_new_$cp&id=$campaignid");
			}
		} else {
			adesk_http_redirect("desk.php?action=campaign_new");
		}

		# List names
		$listids = adesk_sql_select_list("SELECT listid FROM #campaign_list WHERE campaignid = '$campaignid'");
		$liststr = implode("','", $listids);
		$listnames = adesk_sql_select_list("SELECT name FROM #list WHERE id IN ('$liststr')");
		$smarty->assign("listnames", implode(", ", $listnames));

		# Figure out of we need to allow Google Analytics
		$showgread = 0;
		$showglink = 0;
		foreach ($listids as $listid) {
			$res = adesk_sql_select_row("SELECT p_use_analytics_read, p_use_analytics_link FROM #list WHERE id = '$listid' AND analytics_ua != ''");

			if ($res) {
				if ($res["p_use_analytics_read"] && !$showgread)
					$showgread = 1;

				if ($res["p_use_analytics_link"] && !$showglink)
					$showglink = 1;
			}

			if ($showgread && $showglink)
				break;
		}

		$smarty->assign("showgread", $showgread);
		$smarty->assign("showglink", $showglink);

		// Check if we are supposed to use social sharing funcs
		$pass = function_exists('curl_init') && function_exists('hash_hmac') && (int)PHP_VERSION > 4;
		$pass_twitter = false;
		$pass_facebook = false;
		if ( $pass ) {
			$pass_twitter = (int)adesk_sql_select_one("=COUNT(*)", "#list", "id IN ('$liststr') AND twitter_token != '' AND twitter_token_secret != ''");
			$pass_facebook = (int)adesk_sql_select_one("=COUNT(*)", "#list", "id IN ('$liststr') AND facebook_session IS NOT NULL AND facebook_session != ''");
			if ( !$pass_twitter ) $campaign['tweet'] = 0;
			if ( !$pass_facebook ) $campaign['facebook'] = 0;
		} else {
			$campaign['tweet'] = $campaign['facebook'] = 0;
		}
		$smarty->assign('isShareable', $pass);
		$smarty->assign("isTweetable", $pass_twitter);
		$smarty->assign("isFacebookable", $pass_facebook);

		// can we offer archiving
		$isForPublic = (bool)adesk_sql_select_one("=COUNT(*)", "#list", "id IN ('$liststr') AND private = 0");
		if ( !$isForPublic ) $campaign['public'] = 0;
		$smarty->assign("isForPublic", $isForPublic);

		# Segment name
		$smarty->assign("segmentname", "");
		if ($campaign["filterid"] > 0)
			$smarty->assign("segmentname", (string)adesk_sql_select_one("SELECT name FROM #filter WHERE id = '$campaign[filterid]'"));

		# Subscriber total
		$count = 0;
		if ($campaign["filterid"] > 0) {
			$sql = filter_compile($campaign["filterid"]);
			$so  = new adesk_Select;
			$so->push("AND l.listid IN ('$liststr')");
			$so->push("AND l.status = 1");

			if ($sql != "")
				$so->push("AND $sql");

			$so->count('DISTINCT(l.subscriberid)');
			$count = (int)adesk_sql_select_one(subscriber_select_query($so));
		} else {
			$so  = new adesk_Select;
			$so->push("AND l.listid IN ('$liststr')");
			$so->push("AND l.status = 1");

			$so->count('DISTINCT(l.subscriberid)');
			$count = (int)adesk_sql_select_one(subscriber_select_query($so));
		}
		$smarty->assign("subtotal", $count);

		# Messages + Links
		$messagelist = adesk_sql_select_list("SELECT messageid FROM #campaign_message WHERE campaignid = '$campaignid'");
		$messagestr = implode("','", $messagelist);
		$messages = adesk_sql_select_array("SELECT id, subject, htmlfetch, textfetch, html, text FROM #message WHERE id IN ('$messagestr')");

		$linkcount = 0;
		$smarty->assign("hasfetch", 0);
		$smarty->assign("hasrss", 0);

		foreach ($messages as $k => $msg) {
			if (in_array($msg["htmlfetch"], array("send", "cust")) || in_array($msg["textfetch"], array("send", "cust")))
				$smarty->assign("hasfetch", 1);

			if (adesk_str_instr("%RSS-FEED%", $msg["html"]) || adesk_str_instr("%RSS-FEED%", $msg["text"]))
				$smarty->assign("hasrss", 1);

			$messages[$k]["links"] = adesk_sql_select_array("SELECT * FROM #link WHERE campaignid = '$campaignid' AND messageid = '$msg[id]' AND link != 'open'");
			foreach ($messages[$k]["links"] as $l => $v) {
				$linkcount++;
				$actionid = $messages[$k]["links"][$l]["actionid"] = (int)adesk_sql_select_one("SELECT id FROM #subscriber_action WHERE linkid = '$v[id]'");
				$messages[$k]["links"][$l]["actioncount"] = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #subscriber_action_part WHERE actionid = '$actionid'");
			}
		}

		$smarty->assign("messages", $messages);
		$smarty->assign("linkcount", $linkcount);

		# All the other junk we need for subscriber actions.  Campaigns, (full) lists and fields.
		$lcampaigns = adesk_sql_select_array("SELECT DISTINCT c.id, c.name FROM #campaign c, #campaign_list l WHERE c.id = l.campaignid AND l.listid IN ('$liststr') ORDER BY c.id DESC LIMIT 100");
		$smarty->assign("campaigns", $lcampaigns);
//sandeep
		$admin = adesk_admin_get();
		
		$uid = $admin['id'];
	if($uid != 1 ){
	$lists = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");

//sandeep
	//get the lists of users
	
	@$adminliststr = implode("','",$lists);
	}
	else
	{
	@$adminliststr = implode("','", $admin["lists"]);
	}
		
	//	$adminliststr = implode("','", $liststr);

		$llists = adesk_sql_select_array("SELECT id, name FROM #list WHERE id IN ('$adminliststr') ORDER BY name LIMIT 100");
		$smarty->assign("lists", $llists);
		$lfields = adesk_sql_select_array("SELECT DISTINCT f.id, f.title FROM #list_field f, #list_field_rel r WHERE f.id = r.fieldid AND r.relid IN ('0', '$liststr') ORDER BY f.title");
		$smarty->assign("fields", $lfields);

		# Figure out our read-action id (possibly 0 if we have none).
		$readactionid = (int)adesk_sql_select_one("SELECT id FROM #subscriber_action WHERE campaignid = '$campaignid' AND linkid = '0'");
		$smarty->assign("readactionid", $readactionid);
		$smarty->assign("readactioncount", (int)adesk_sql_select_one("SELECT COUNT(*) FROM #subscriber_action_part WHERE actionid = '$readactionid'"));

		# Our time zone offset.
		$smarty->assign("tzoffset", tz_gmtoffset($admin["local_zoneid"]));

		# To avoid having a huge list of options for the schedule taking up space in the template, here are some hours/minutes arrays.
		$hours = array();
		$minutes = array();

		for ($i = 0; $i < 24; $i++)
			$hours[] = sprintf("%02d", $i);

		for ($i = 0; $i < 60; $i++)
			$minutes[] = sprintf("%02d", $i);

		$smarty->assign("hours", $hours);
		$smarty->assign("minutes", $minutes);

		if ($campaign["schedule"] && $campaign["sdate"] != "") {
			# Fake like the default times are the ones in the campaign.
			$time = strtotime($campaign["sdate"]);
			if ($time !== false && $time > 0) {
				if ( !in_array($campaign["type"], array("responder", "reminder")) && $time < time() ) {
					$time = time();
					$campaign["schedule"] = 0;
				}
				$smarty->assign("currenthour", date("H", $time));
				$smarty->assign("currentminute", date("i", $time));
				$smarty->assign("currentdate", date("Y/m/d", $time));
			} else {
				# The current hour, minute, date.
				$rval = adesk_sql_select_row("SELECT NOW() AS tstamp", array("tstamp"));
				$time = strtotime($rval["tstamp"]);
				$smarty->assign("currenthour", date("H", $time));
				$smarty->assign("currentminute", date("i", $time));
				$smarty->assign("currentdate", date("Y/m/d", $time));
				$campaign["schedule"] = 0;
			}
		} else {
			# The current hour, minute, date.
			$rval = adesk_sql_select_row("SELECT NOW() AS tstamp", array("tstamp"));
			$time = strtotime($rval["tstamp"]);
			$smarty->assign("currenthour", date("H", $time));
			$smarty->assign("currentminute", date("i", $time));
			$smarty->assign("currentdate", date("Y/m/d", $time));
			$campaign["schedule"] = 0;
		}

		# Figure out the default reminder string.
		$smarty->assign("reminder_example", campaign_reminder_compile($campaign["reminder_field"], $campaign["reminder_offset_sign"], $campaign["reminder_offset"], $campaign["reminder_offset_type"]));

		// assign all presets
		$smarty->assign('campaignid', $campaignid);
		$smarty->assign('campaign', $campaign);
		$smarty->assign("isEdit", $isEdit);
		$smarty->assign("showAllMessages", $showAllMessages);
		//$smarty->assign("spamcheck", ( plugin_emailcheck() ? $this->site['serial'] : '' ));

		# Last ditch check; too many subscribers?
		$pastlimit = campaign_subscribers($campaignid, $campaign["filterid"]);
		$smarty->assign("pastlimit", $pastlimit);

		// default debugging
		$debugging = $campaign['mailer_log_file'];
		// custom debugging
		if ( adesk_http_param_exists('debug') ) {
			$debugging = (int)adesk_http_param('debug');
		}
		$smarty->assign("debugging", $debugging);
		$smarty->assign('isDemo', isset($GLOBALS['demoMode']));

		$recur_intervals = campaign_recur_intervals();
		$smarty->assign("recur_intervals", $recur_intervals);
	}

	function formProcess(&$smarty) {
		campaign_save();
		campaign_save_after();

		if ($GLOBALS["campaign_save_id"] > 0)
			adesk_http_redirect("desk.php?action=campaign_new_summary&id=$GLOBALS[campaign_save_id]");

	}
}

?>
