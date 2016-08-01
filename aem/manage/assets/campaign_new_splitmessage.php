<?php

require_once adesk_admin("functions/campaign.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");

class campaign_new_splitmessage_assets extends AWEBP_Page {

	function campaign_new_splitmessage_assets() {
		$this->pageTitle = _a("Create a New Campaign");
		//$this->sideTemplate = "side.message.htm";
		$this->AWEBP_Page();

		$this->campaign = array();
		$this->lists = array();
	}

	function process(&$smarty) {
		$this->setTemplateData($smarty);

		if (!$this->admin["pg_message_add"] && !$this->admin["pg_message_edit"]) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		$smarty->assign("content_template", "campaign_new_splitmessage.htm");

		$campaignid = (int)adesk_http_param("id");

		if ($campaignid < 1)
			adesk_http_redirect("desk.php");

		campaign_save_markpos("splitmessage", $campaignid);

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
		} else {
			adesk_http_redirect("desk.php?action=campaign_new");
		}

		// Fetch this campaign's lists
		$lists = adesk_sql_select_list("SELECT listid FROM #campaign_list WHERE campaignid = '$campaignid'");
		$liststr = implode("','", $lists);

		$mid = adesk_http_param("m");

		# Check if it's a new message.
		if ($mid == "new") {
			$this->newmessage($campaign, $lists, 0);
			exit;
		}

		# Ok, it's not.  But see if we have ANY messages first.  If not, pretend that m=new.
		$any = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #campaign_message WHERE campaignid = '$campaignid'");
		if ($any == 0) {
			$this->newmessage($campaign, $lists, 0);
			exit;
		}

		# We have at least one message, so let's hope m was one of them.
		$mid = (int)$mid;

		if ($mid > 0) {
			$exists = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #campaign_message WHERE messageid = '$mid' AND campaignid = '$campaignid'");

			# Pretend we didn't pass m at all.
			if (!$exists)
				$mid = 0;
		}

		if ($mid > 0) {
			# If we get here, we know that m was passed, it's > 0 and it's a real message.

			if (adesk_http_param("del")) {
				$this->deletemessage($campaign, $mid);
				exit;
			}
		}

		if ($mid == 0) {
			# Find a message to use--we'll pick the first one.
			$mid = (int)adesk_sql_select_one("SELECT messageid FROM #campaign_message WHERE campaignid = '$campaignid' LIMIT 1");
		}

		$message = adesk_sql_select_row("SELECT * FROM #message WHERE id = '$mid'");
		$smarty->assign("message", $message);

		if (isset($message["htmlfetch"]) && substr($message["html"], 0, 6) == "fetch:") {
			$smarty->assign("fetch", $message["htmlfetch"]);
			$smarty->assign("fetchurl", substr($message["html"], 6));
		} else {
			$smarty->assign("fetch", "now");
			$smarty->assign("fetchurl", "http://");
		}

		# Before we continue, let's fix all percentages.
		$this->fixpercentages($campaign["id"]);

		# Do we have any other messages?  Grab them for any tabs we show.
		$tabs = adesk_sql_select_array("
			SELECT
				cm.messageid AS id,
				cm.percentage,
				cm.percentage * 3 AS percentage300,
				(SELECT m.subject FROM #message m WHERE m.id = cm.messageid) AS subject
			FROM
				#campaign_message cm
			WHERE
				cm.campaignid = '$campaignid'
		");
		$smarty->assign("tabs", $tabs);

		# Figure out the winner ratio stuff
		$winnerval = 100;
		foreach ($tabs as $tab) {
			$winnerval -= $tab["percentage"];
		}

		$winnerval = max($winnerval, 0);
		$winnerpx = $winnerval * 3;

		$smarty->assign("winnerval", $winnerval);
		$smarty->assign("winnerpx", $winnerpx);

		# Figure out what fields to show.
		$fields = adesk_custom_fields_select_nodata_rel("#list_field", "#list_field_rel", "r.relid IN ('0', '$liststr')");

		$smarty->assign("fields", $fields);

		# Any attached files?
		$rs = adesk_sql_query($q = "SELECT * FROM #message_file WHERE messageid = '$message[id]'");
		$files = array();
		while ($row = adesk_sql_fetch_assoc($rs)) {
			$row["humansize"] = adesk_file_humansize($row["size"]);
			$files[$row["id"]] = $row;
		}

		$smarty->assign("files", $files);

		// assign all presets
		$smarty->assign('campaignid', $campaignid);
		$smarty->assign('campaign', $campaign);
		$smarty->assign("isEdit", $isEdit);
		$smarty->assign("showAllMessages", $showAllMessages);

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
	}

	function formProcess(&$smarty) {
		if (adesk_http_param("post_action"))
			$_POST["aftersave"] = "nothing";

		campaign_save();

		if (adesk_http_param("post_action")) {
			$action = adesk_http_param("post_action");
			$m = (int)adesk_http_param("post_m");
			$from = (int)adesk_http_param("post_from");
			$campaignid = (int)adesk_http_param("id");

			$campaign = campaign_select_row($campaignid);

			switch ($action) {
				case "new":
					$lists = adesk_sql_select_list("SELECT listid FROM #campaign_list WHERE campaignid = '$campaignid'");
					$this->newmessage($campaign, $lists, $from);
					break;

				case "del":
					$this->deletemessage($campaign, $m, $from);
					break;

				case "load":
					adesk_http_redirect("desk.php?action=campaign_new_splitmessage&id=$campaign[id]&m=$m");
					break;

				default:
					break;
			}
		}

		campaign_save_after();

		if ($GLOBALS["campaign_save_id"] > 0) {
			$m = (int)adesk_http_param("messageid");
			adesk_http_redirect("desk.php?action=campaign_new_splitmessage&id=$GLOBALS[campaign_save_id]&m=$m");
		}
	}

	function newmessage($campaign, $lists, $fromid) {
		$campaignid = $campaign["id"];
		$adminlists = implode("','", $this->admin["lists"]);
		$lastcid = (int)adesk_sql_select_one("SELECT campaignid FROM #campaign_list WHERE listid IN ('$adminlists') ORDER BY campaignid DESC LIMIT 1");

		if ($fromid) {
			$message = adesk_sql_select_row("SELECT * FROM #message WHERE id = '$fromid'");

			if ($message) {
				if ($message["fromname"] == "")
					$message["fromname"] = $this->admin["fullname"];

				if ($message["fromemail"] == "")
					$message["fromemail"] = $this->admin["email"];
			} else {
				$message = array(
					"fromname" => $this->admin["fullname"],
					"fromemail" => $this->admin["email"],
					"reply2" => '',//$this->admin["email"],
					"subject" => $campaign["name"],
				);
			}
		} elseif ($lastcid > 0) {
			$lastmid = (int)adesk_sql_select_one("SELECT messageid FROM #campaign_message WHERE campaignid = '$lastcid' ORDER BY messageid DESC LIMIT 1");
			$message = adesk_sql_select_row("SELECT * FROM #message WHERE id = '$lastmid'");

			if ($message) {
				if ($message["fromname"] == "")
					$message["fromname"] = $this->admin["fullname"];

				if ($message["fromemail"] == "")
					$message["fromemail"] = $this->admin["email"];
			} else {
				$message = array(
					"fromname" => $this->admin["fullname"],
					"fromemail" => $this->admin["email"],
					"reply2" => '',//$this->admin["email"],
					"subject" => $campaign["name"],
				);
			}
		} else {
			$message = array(
				"fromname" => $this->admin["fullname"],
				"fromemail" => $this->admin["email"],
				"reply2" => '',//$this->admin["email"],
				"subject" => $campaign["name"],
			);
		}

		if ($campaign["basetemplateid"] > 0 && !$fromid) {
			$message["html"] = (string)adesk_sql_select_one("SELECT content FROM #template WHERE id = '$campaign[basetemplateid]'");
		} elseif ($campaign["basemessageid"] > 0 && !$fromid) {
			$message["html"] = (string)adesk_sql_select_one("SELECT html FROM #message WHERE id = '$campaign[basemessageid]'");
		} elseif (!$fromid) {
			# Shouldn't happen, but...
			$message["html"] = "";
		}
		$message["html"] = adesk_str_strip_malicious($message["html"]);

		/*
		# Fix reply-to, if it's blank.
		if ($message["reply2"] == "")
			$message["reply2"] = $message["fromemail"];
		*/

		// set standard control fields
		$message['id'] = 0;
		$message['=cdate'] = 'NOW()';
		unset($message['cdate']);
		$message['=mdate'] = 'NOW()';
		unset($message['mdate']);
		$message['userid'] = $this->admin['id'];
		if ( !isset($message['format']) ) {
			$message['format'] = 1 /* check here if they selected text as well? */ ? 'mime' : 'html';
		}

		if ($message["subject"] == "")
			$message["subject"] = $campaign["name"];
		if ( !isset($message['name']) or !$message['name'] ) {
			$message['name'] = $message['subject'];
		}

		if (!adesk_sql_insert("#message", $message)) {
			dbg($message,1);
			dbg(adesk_sql_error());
			die("didn't work out");
		}

		$mid = adesk_sql_insert_id();
		$totalmsgs = count($campaign['messages']);
		if ( $campaign['split_type'] == 'even' ) {
			// fetch the total
			$percentage = 1 / ( $totalmsgs + 1 );
			adesk_sql_update_one("#campaign_message", "percentage", $percentage, "campaignid = '$campaign[id]'");
		} else {
			$sum = (int)adesk_sql_select_one("=SUM(percentage)", "#campaign_message", "campaignid = '$campaign[id]'");
			if ( $sum < 90 ) {
				$percentage = $totalmsgs > 5 ? 5 : 10;
			} else {
				$percentage = 99 - $sum;
			}
		}

		$ins = array(
			"campaignid" => $campaign["id"],
			"messageid"  => $mid,
			"percentage" => $percentage,
		);

		adesk_sql_insert("#campaign_message", $ins);

		foreach ( $lists as $listid ) {
			$ins = array(
				"listid" => $listid,
				"messageid"  => $mid,
			);
			adesk_sql_insert("#message_list", $ins);
		}

		if ($campaign["basemessageid"] > 0) {
			message_copy_attach($campaign["basemessageid"], $mid);
		}

		# Ok--all done.  Redirect to the page with the correct mid.
		adesk_http_redirect("desk.php?action=campaign_new_splitmessage&id=$campaignid&m=$mid");
	}

	function deletemessage($campaign, $mid, $fromid) {
		adesk_sql_delete("#campaign_message", "messageid = '$mid' AND campaignid = '$campaign[id]'");

		if ($fromid == $mid) {
			# Deleting ourselves?
			adesk_http_redirect("desk.php?action=campaign_new_splitmessage&id=$campaign[id]");
		}

		# Otherwise, stay on the original message.
		adesk_http_redirect("desk.php?action=campaign_new_splitmessage&id=$campaign[id]&m=$fromid");
	}

	function fixpercentages($campaignid) {
		$rs = adesk_sql_query("SELECT * FROM #campaign_message WHERE campaignid = '$campaignid'");
		$type = adesk_sql_select_one("SELECT split_type FROM #campaign WHERE id = '$campaignid'");
		$zero = 0;
		$allotment = 100;
		$messages = array();
		$total = adesk_sql_num_rows($rs);

		if ($type == "even") {
			$parcel = floor($allotment / $total);

			while ($row = adesk_sql_fetch_assoc($rs)) {
				$row["percentage"] = 0;
				$messages[] = $row;
			}
		} else {
			while ($row = adesk_sql_fetch_assoc($rs)) {
				if ($row["percentage"] == 0) {
					$zero++;
				} else {
					$allotment -= $row["percentage"];
				}

				$messages[] = $row;
			}

			if ($zero == 0) {
				# We're fine.
				return;
			}

			if ($total > 6)
				$parcel = 5;
			else
				$parcel = 10;
		}

		foreach ($messages as $msg) {
			if ($msg["percentage"] == 0) {
				$up = array(
					"percentage" => min($allotment, $parcel),
				);

				$allotment -= $parcel;

				if ($parcel > $allotment && $allotment > 0) {
					$up["percentage"] += $allotment;
					$allotment = 0;
				}

				adesk_sql_update("#campaign_message", $up, "id = '$msg[id]'");
			}

			if ($allotment <= 0)
				break;
		}
	}
}

?>
