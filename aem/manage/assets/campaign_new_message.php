<?php

require_once adesk_admin("functions/campaign.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");

class campaign_new_message_assets extends AWEBP_Page {

	function campaign_new_message_assets() {
		$this->pageTitle = _a("Create a New Campaign");
		//$this->sideTemplate = "side.message.htm";
		$this->AWEBP_Page();
	}

	function process(&$smarty) {
		$this->setTemplateData($smarty);

		if (!$this->admin["pg_message_add"] && !$this->admin["pg_message_edit"]) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		$smarty->assign("content_template", "campaign_new_message.htm");

		$lists = list_select_array();

		$campaignid = (int)adesk_http_param("id");

		if ($campaignid < 1)
			adesk_http_redirect("desk.php");

		campaign_save_markpos("message", $campaignid);

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

		if ( $campaign['type'] == 'text' ) {
			adesk_http_redirect("desk.php?action=campaign_new_text&id=$campaignid");
		}

		// Fetch this campaign's lists
		$lists = adesk_sql_select_list("SELECT listid FROM #campaign_list WHERE campaignid = '$campaignid'");
		$liststr = implode("','", $lists);

		# How many messages do we have?
		$messagelist = adesk_sql_select_list("SELECT messageid FROM #campaign_message WHERE campaignid = '$campaignid'");

		if (count($messagelist) > 1 || $campaign['type'] == 'split') {
			# It's a split-test campaign.  For now, redirect to desk.php.
			adesk_http_redirect("desk.php?action=campaign_new_splitmessage&id=$campaignid");
		} elseif (count($messagelist) == 1) {
			# Single-message campaign; grab $message based on the id and use it for our form inputs.
			$message = adesk_sql_select_row("SELECT * FROM #message WHERE id = '$messagelist[0]'");
		} elseif (count($messagelist) == 0) {
			# We don't have a message yet; let's create one.
			$adminlists = implode("','", $this->admin["lists"]);
			$lastmid = (int)adesk_sql_select_one("SELECT messageid FROM #message_list WHERE listid IN ('$adminlists') ORDER BY messageid DESC LIMIT 1");
			$message = adesk_sql_select_row("SELECT * FROM #message WHERE id = '$lastmid'");

			if ($message) {
				if ($message["fromname"] == "")
					$message["fromname"] = $this->admin["fullname"];

				if ($message["fromemail"] == "")
					$message["fromemail"] = $this->admin["email"];

				# Wipe out the old subject.
				$message["subject"] = "";
			} else {
				$message = array(
					"fromname" => $this->admin["fullname"],
					"fromemail" => $this->admin["email"],
					"reply2" => '',//$this->admin["email"],
					"subject" => "",
				);
			}

			if ($campaign["basetemplateid"] > 0) {
				$message["html"] = (string)adesk_sql_select_one("SELECT content FROM #template WHERE id = '$campaign[basetemplateid]'");
			} elseif ($campaign["basemessageid"] > 0) {
				$message["html"] = (string)adesk_sql_select_one("SELECT html FROM #message WHERE id = '$campaign[basemessageid]'");
			} else {
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
			if ( !isset($message['name']) or !$message['name'] ) {
				$message['name'] = $message['subject'];
			}

			if (!adesk_sql_insert("#message", $message)) {
				dbg($message,1);
				dbg(adesk_sql_error());
				die("didn't work out");
			}

			$mid = adesk_sql_insert_id();
			$ins = array(
				"campaignid" => $campaign["id"],
				"messageid"  => $mid,
				"percentage" => 0,
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
		}


		$smarty->assign("message", $message);

		if (isset($message["htmlfetch"]) && substr($message["html"], 0, 6) == "fetch:") {
			$smarty->assign("fetch", $message["htmlfetch"]);
			$smarty->assign("fetchurl", substr($message["html"], 6));
		} else {
			$smarty->assign("fetch", "now");
			$smarty->assign("fetchurl", "http://");
		}

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
		campaign_save();
		campaign_save_after();

		if ($GLOBALS["campaign_save_id"] > 0)
			adesk_http_redirect("desk.php?action=campaign_new_message&id=$GLOBALS[campaign_save_id]");
	}
}

?>
