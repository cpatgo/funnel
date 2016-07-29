<?php

require_once awebdesk_functions("ajax.php");
require_once awebdesk_functions("htmltext.php");
require_once adesk_admin("functions/html.php");
require_once adesk_admin("functions/personalization.php");
require_once adesk_admin("functions/message.php");

$GLOBALS["campaign_save_id"] = 0;
$GLOBALS["campaign_save_order"] = array(
	"type",
	"list",
	"template",
	"message",
	"text",
	"splitmessage",
	"splittext",
	"summary",
	"result",
);

# This is sort of a hack; we try to determine if someone hit the back button by looking at our position
# now versus their old position.
$GLOBALS["campaign_save_backbutton"] = false;
$GLOBALS["campaign_save_after"] = 'nothing';

// Save result (needed since we are redirecting at the end).
if (!isset($_SESSION["campaign_save_result"]))
	$_SESSION["campaign_save_result"] = array();

# Where we are.
if (!isset($_SESSION["campaign_save_position"]))
	$_SESSION["campaign_save_position"] = array();

function campaign_save() {
	$step   = (string)adesk_http_param("step");
	$after  = (string)adesk_http_param("aftersave");
	$id     = (int)adesk_http_param("id");

	if (!$step) {
		# We need to know what we're saving.
		return;
	}

	if ($id > 0)
		$GLOBALS["campaign_save_id"] = $id;

	$GLOBALS["campaign_save_after"] = $after;

	switch ($step) {
		case "type":
			campaign_save_type();
			break;

		case "list":
			campaign_save_list();
			break;

		case "template":
			campaign_save_template();
			break;

		case "message":
			campaign_save_message();
			break;

		case "text":
			campaign_save_text();
			break;

		case "splitmessage":
			campaign_save_splitmessage();
			break;

		case "splittext":
			campaign_save_splittext();
			break;

		case "summary":
			campaign_save_summary();
			break;

		default:
			break;
	}
}

function campaign_save_markpos($which, $campaignid) {
	if (!isset($_SESSION["campaign_save_position"][$campaignid]))
		$_SESSION["campaign_save_position"][$campaignid] = "";

	$oldpos = $_SESSION["campaign_save_position"][$campaignid];
	$index  = array_search($oldpos, $GLOBALS["campaign_save_order"]);
	$newindex = array_search($which, $GLOBALS["campaign_save_order"]);

	if ($index !== false && $newindex !== false) {
		# We may have hit the back button.

		if ($index == ($newindex + 1)) {
			# Looks like we did.
			$GLOBALS["campaign_save_backbutton"] = true;
		}
	}

	# Save the position in the laststep field.
	if ($campaignid > 0) {
		$campaignid = (int)$campaignid;
		$current = (string)adesk_sql_select_one("SELECT laststep FROM #campaign WHERE id = '$campaignid'");

		$cindex = array_search($current, $GLOBALS["campaign_save_order"]);

		if ($cindex === false || $cindex < $newindex) {
			$up = array(
				"laststep" => $which,
			);

			adesk_sql_update("#campaign", $up, "id = '$campaignid'");
		}
	}

	$_SESSION["campaign_save_position"][$campaignid] = $which;
}

function campaign_save_wentback() {
	return $GLOBALS["campaign_save_backbutton"];
}

function campaign_save_default($name, $type) {
	$admin = adesk_admin_get();

	$ins = array(
		"id" => campaign_nextid(),
		"name" => $name,
		"type" => $type,
		"userid" => $admin["id"],
		"laststep" => "type",
		"=ip4" => "INET_ATON('$_SERVER[REMOTE_ADDR]')",
		"=cdate" => "NOW()",
		"tracklinksanalytics" => 0,
		"trackreadsanalytics" => 0,
	);

	if (adesk_http_param("debug"))
		$ins["mailer_log_file"] = 4;

	$r = adesk_sql_insert("#campaign", $ins);

	if (!$r) {
		adesk_log("Couldn't create campaign: " . adesk_sql_error());
		exit;
	}

	$GLOBALS["campaign_save_id"] = $id = adesk_sql_insert_id();
	campaign_updatenextid($id);

	if ( isset($GLOBALS['_hosted_account']) ) {
		require(dirname(dirname(__FILE__)) . '/manage/campaign.add.inc.php');
	}

}

function campaign_save_error($message, $campaignid = null, $arr = array()) {
	if ( is_null($campaignid) ) $campaignid = $GLOBALS["campaign_save_id"];
	$_SESSION["campaign_save_result"][$campaignid]["succeeded"] = false;
	$_SESSION["campaign_save_result"][$campaignid]["message"] = $message;
	if ( count($arr) ) $_SESSION["campaign_save_result"][$campaignid] = array_merge($_SESSION["campaign_save_result"][$campaignid], $arr);
}

function campaign_save_result($message, $campaignid = null, $arr = array()) {
	if ( is_null($campaignid) ) $campaignid = $GLOBALS["campaign_save_id"];
	$_SESSION["campaign_save_result"][$campaignid]["succeeded"] = true;
	$_SESSION["campaign_save_result"][$campaignid]["message"] = $message;
	if ( count($arr) ) $_SESSION["campaign_save_result"][$campaignid] = array_merge($_SESSION["campaign_save_result"][$campaignid], $arr);
}

# Redirect functions

function campaign_save_after() {
	$step  = (string)adesk_http_param("step");
	$after = (string)adesk_http_param("aftersave");

	$id    = (int)$GLOBALS["campaign_save_id"];

	# Nothing we can do if we have no id.
	if ($id == 0)
		$after = "exit";

	// handle form processing
	if ( isset($_SESSION["campaign_save_result"][$id]) ) {
		if ( !$_SESSION["campaign_save_result"][$id]["succeeded"] ) {
			$after = "nothing";
		}
	}

	switch ($after) {
		default:
		case "nothing":
			break;

		case "next":
			campaign_save_next($step);
			break;

		case "back":
			campaign_save_back($step);

		case "exit":
			adesk_http_redirect("desk.php");
			break;
	}
}

function campaign_save_next($step) {
	$id = $GLOBALS["campaign_save_id"];
	$order = $GLOBALS["campaign_save_order"];

	$campaign = adesk_sql_select_row("SELECT * FROM #campaign WHERE id = '$id'");
	$text = ($campaign["type"] == "split") ? "splittext" : "text";
	$message = ($campaign["type"] == "split") ? "splitmessage" : "message";

	$index = array_search($step, $order);
	if ($index !== false) {
		if ($index < (count($order) - 1)) {
			# Some exceptions may be in order.

			switch ($step) {
				default:
					break;

				case "list":
					# We may need to skip to message, or to text, based on our
					# campaign type and on if we've chosen a base
					# message/template.

					# Automatically go to the text assets, regardless of tpl/msg
					if ($campaign["type"] == "text")
						adesk_http_redirect("desk.php?action=campaign_new_$text&id=$id");

					$hasmessage = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #campaign_message WHERE campaignid = '$id'");
					if ($campaign["basetemplateid"] > 0 || $campaign["basemessageid"] > 0 || $hasmessage)
						adesk_http_redirect("desk.php?action=campaign_new_$message&id=$id");

					break;

				case "template":
					adesk_http_redirect("desk.php?action=campaign_new_$message&id=$id");
					break;

				case "text":
					adesk_http_redirect("desk.php?action=campaign_new_summary&id=$id");
					break;

				case "splitmessage":
					if (!$campaign["managetext"])
						adesk_http_redirect("desk.php?action=campaign_new_summary&id=$id");
					break;
			}

			$nextstep = $order[$index + 1];
			adesk_http_redirect("desk.php?action=campaign_new_$nextstep&id=$id");
		}
	}
}

function campaign_save_back($step) {
	$id = $GLOBALS["campaign_save_id"];
	$order = $GLOBALS["campaign_save_order"];

	$campaign = adesk_sql_select_row("SELECT * FROM #campaign WHERE id = '$id'");
	$text = ($campaign["type"] == "split") ? "splittext" : "text";
	$message = ($campaign["type"] == "split") ? "splitmessage" : "message";

	$index = array_search($step, $order);
	if ($index !== false) {
		if ($index > 0) {
			# Check for exceptions.

			switch ($step) {
				default:
					break;

				case "summary":
					if ($campaign["type"] == "text" || $campaign["managetext"] == 1)
						adesk_http_redirect("desk.php?action=campaign_new_$text&id=$id");

					adesk_http_redirect("desk.php?action=campaign_new_$message&id=$id");

					break;

				case "text":
					# Skip message; go directly back to list.
					if ($campaign["type"] == "text")
						adesk_http_redirect("desk.php?action=campaign_new_list&id=$id");

					break;

				case "splitmessage":
				case "message":
					# If we're here, we've chosen a base template or message.
					# There's no need to go back to the template screen.
					adesk_http_redirect("desk.php?action=campaign_new_list&id=$id");
					break;

				case "splittext":
					$messageid = (int)adesk_http_param("messageid");
					adesk_http_redirect("desk.php?action=campaign_new_$message&id=$id&m=$messageid");

				case "summary":
					if ($campaign["type"] == "text")
						adesk_http_redirect("desk.php?action=campaign_new_text&id=$id");

					if ($campaign["type"] == "split")
						adesk_http_redirect("desk.php?action=campaign_new_splitmessage&id=$id");

					adesk_http_redirect("desk.php?action=campaign_new_message&id=$id");
					break;
			}

			$nextstep = $order[$index - 1];

			if ($index > 1)
				adesk_http_redirect("desk.php?action=campaign_new_$nextstep&id=$id");
			else
				adesk_http_redirect("desk.php?action=campaign_new&id=$id");
		}
	}
}

# Save-step functions

function campaign_save_type() {
	$name = trim((string)adesk_http_param("name"));
	$type = (string)adesk_http_param("type");

	// error checks
	if ( $GLOBALS["campaign_save_after"] == 'next' ) {
		if ( !in_array($type, array('single','recurring','split','responder','reminder','special','deskrss','text')) ) {
			campaign_save_error(_a("Please select the campaign type first."));
			return;
		}
		if ( !$name ) {
			campaign_save_error(_a("Please enter the name of your campaign name first."));
			return;
		}
	}

	if ($GLOBALS["campaign_save_id"] == 0) {
		campaign_save_default($name, $type);
	} else {
		$up = array(
			"name" => $name,
			"type" => $type,
		);

		// Still use recurring if that's what we already are.  Unset willrecur if we're changing.
		$origtype = adesk_sql_select_one("SELECT `type` FROM #campaign WHERE id = '$GLOBALS[campaign_save_id]'");
		if ($origtype == "recurring") {
		   	if ($type == "single")
				$up["type"] = $origtype;
			else
				$up["willrecur"] = 0;
		}

		if ($type != "split" && $origtype == "split") {
			// if changing from split to something else
			// make sure there is only one message in #campaign_message
			$messages = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #campaign_message WHERE campaignid = '$GLOBALS[campaign_save_id]'");
			if ($messages > 1) {
				// delete all messages, but one
				$delete = adesk_sql_query( "DELETE FROM #campaign_message WHERE campaignid = '$GLOBALS[campaign_save_id]' LIMIT " . ($messages - 1) );
			}
		}

		if ($origtype == "responder" || $origtype == "deskrss" || $origtype == "reminder")
			$up["=sdate"] = "NULL";

		if ($type == "text")
			$up["managetext"] = 1;

		adesk_sql_update("#campaign", $up, "id = '$GLOBALS[campaign_save_id]'");
	}
}

function campaign_save_list() {
	global $admin;

	$id = $GLOBALS["campaign_save_id"];

	if ($id < 1)
		return;

	$listids = adesk_http_param_forcearray("listid");
	$filterid = (int)adesk_http_param("filterid");

	$listids = array_diff(array_map('intval', $listids), array(0));

	if ( !adesk_admin_ismaingroup() ) {
		$listids = array_intersect($listids, array_values($admin['lists']));
	}

	// error checks
	if ( $GLOBALS["campaign_save_after"] == 'next' ) {
		if ( !count($listids) ) {
			campaign_save_error(_a("Please select at least one list to send this campaign to."), $id);
			return;
		}
		if ( $filterid and !filter_select_row($filterid) ) {
			campaign_save_error(_a("The segment you have selected does not seem to be valid."), $id);
			return;
		}
	}

	# Update the lists for this campaign.
	adesk_sql_query("DELETE FROM #campaign_list WHERE campaignid = '$id'");

	foreach ($listids as $listid) {
		$ins = array(
			"campaignid" => $id,
			"listid" => $listid,
		);

		adesk_sql_insert("#campaign_list", $ins);

		# If there are any list relations for the message, change them here.
		$messages = adesk_sql_select_list("SELECT messageid FROM #campaign_message WHERE campaignid = '$id'");
		foreach ($messages as $mid) {
			adesk_sql_query("DELETE FROM #message_list WHERE messageid = '$mid'");
			$ins = array(
				"messageid" => $mid,
				"listid" => $listid,
			);

			adesk_sql_insert("#message_list", $ins);
		}
	}

	$lhs = adesk_http_param_forcearray("filter_group_cond_lhs");
	if (count($lhs) > 1) {
		$rval = filter_insert_post();

		if (isset($rval["id"]) && $rval["id"] > 0)
			$filterid = $rval["id"];
	}

	$up = array(
		"filterid" => $filterid,
	);

	adesk_sql_update("#campaign", $up, "id = '$id'");

	# Last ditch check; force them to go back if the count is zero.
	if (campaign_subscribers_fetch($listids, $filterid) < 1)
		adesk_http_redirect("desk.php?action=campaign_new_list&id=$id&nobody=1");
}

function campaign_save_template() {
	$id = $GLOBALS["campaign_save_id"];

	if ($id < 1)
		return;

	# Don't do anything.
	if ($GLOBALS["campaign_save_after"] == "back")
		return;

	$up = array(
		"basetemplateid" => (int)adesk_http_param("basetemplateid"),
		"basemessageid" => (int)adesk_http_param("basemessageid"),
	);

	adesk_sql_update("#campaign", $up, "id = '$id'");

	# Remove any previous message content if it exists.  Necessary if someone
	# wants to start over with a new template.
	adesk_sql_delete("#campaign_message", "campaignid = '$id'");
}

function campaign_save_message() {
	$id = $GLOBALS["campaign_save_id"];

	if ( $id < 1 ) {
		campaign_save_error(_a("Unknown error occurred. Please try repeating your action. We apologize for this inconvenience..."));
		return;
	}

	$mid = (int)adesk_sql_select_one("SELECT messageid FROM #campaign_message WHERE campaignid = '$id' LIMIT 1");

	# Well, this is weird.
	if ( $mid < 1 ) {
		campaign_save_error(_a("Unknown error occurred. Please try repeating your action. We apologize for this inconvenience..."), $id);
		return;
	}

	$campaign = adesk_sql_select_row("SELECT * FROM #campaign WHERE id = '$id'");
	# Well, this is weird.
	if ( !$campaign ) {
		campaign_save_error(_a("Unknown error occurred. Please try repeating your action. We apologize for this inconvenience..."), $id);
		return;
	}
	$message = adesk_sql_select_row("SELECT * FROM #message WHERE id = '$mid'");

	$embed  = (int)adesk_http_param("embed_images");

	# Update campaign.
	$up = array(
		"managetext" => (int)adesk_http_param("managetext"),
		"embed_images" => $embed,
	);

	$managetext = $up["managetext"];	# Need this later.

	adesk_sql_update("#campaign", $up, "id = '$id'");

	# Update the attachment rows, if any.
	$attach = adesk_http_param_forcearray("attach");

	if (count($attach) > 0 && !$embed) {
		$attach = array_map('intval', $attach);
		$attachstr = implode("','", $attach);
		$up = array(
			"messageid" => $mid,
		);

		adesk_sql_update("#message_file", $up, "id IN ('$attachstr')");
	} else {
		adesk_sql_delete("#message_file", "messageid = '$mid'");
	}

	# Update message.
	$up = array(
		"fromname" => trim((string)adesk_http_param("fromname")),
		"fromemail" => trim((string)adesk_http_param("fromemail")),
		"reply2" => trim((string)adesk_http_param("reply2")),
		"subject" => trim((string)adesk_http_param("subject")),
		"html" => (string)adesk_http_param("html"),
		"format" => "mime",
	);

	// Update campaign.
	$campup = array();

	$fetchurl = trim((string)adesk_http_param("fetchurl"));
	$fetch    = adesk_http_param("fetch");

	if ($fetch == "send" && $fetchurl != "http://" && strlen($fetchurl) > 8) {
		# 8 in case they use "https"
		$up["htmlfetch"] = campaign_save_fetchmethod($id, $fetch, $fetchurl);
		$up["html"] = "fetch:" . $fetchurl;
		$message["htmlfetchurl"] = $fetchurl;
	} else {
		# First, we know we're not doing any fetch-at-send, so kill any recurring settings.
		adesk_sql_query("UPDATE #campaign SET willrecur = 0, `type` = 'single', `recurring` = 'day1' WHERE id = '$id' AND `type` = 'recurring'");

		# Fix any HTML problems in the message.
		$up["htmlfetch"] = "now";
		$up["html"] = adesk_str_strip_malicious($up["html"]);
		$up["html"] = campaign_save_fixchars($up["html"]);
		$up["html"] = html_savefix($up["html"]);
		$message["htmlfetchurl"] = "";
	}

	if ( $GLOBALS["campaign_save_after"] == 'next' ) {

		// perform checks
		if ( !$up["fromname"] ) {
			campaign_save_error(_a("Please enter sender name before proceeding to the next step."), $id);
			return;
		}
		if ( !adesk_str_is_email($up["fromemail"]) ) {
			campaign_save_error(_a("Please enter a valid sender email address before proceeding to the next step."), $id);
			return;
		}
		if ( $up["reply2"] and !adesk_str_is_email($up["reply2"]) ) {
			campaign_save_error(_a("Please enter a valid reply-to email address before proceeding to the next step."), $id);
			return;
		}
		if ( $up["htmlfetch"] == "now" && !$up["subject"] ) {
			campaign_save_error(_a("Please enter a message subject before proceeding to the next step."), $id);
			return;
		}
		if ( !adesk_str_strip_tags($up["html"]) && $up["html"] == adesk_str_strip_tag_short($up["html"], "img") ) {
			campaign_save_error(_a("It seems like you haven't entered any message content. Please enter some content before proceeding to the next step."), $id);
			return;
		}

		// deskrss checks
		if ( $campaign['type'] == 'deskrss' ) {
			$campup['deskrss_url'] = trim((string)adesk_http_param("deskrss_url"));
			$campup['deskrss_items'] = (int)adesk_http_param("deskrss_items");
			if ( !$campup['deskrss_items'] ) $campup['deskrss_items'] = 10;
			// check if url is provided
			if ( !adesk_str_is_url($campup['deskrss_url']) ) {
				campaign_save_error(_a("You do not have an RSS feed URL entered into the 'Insert your RSS feed' field. Please make sure that you entered a RSS feed URL and placed it into your message before proceeding to the next step."), $id);
				return;
			}

			$content = $up['html'];
			// check if exactly one rss feed is found
			preg_match_all('/%RSS-FEED\|/', $content, $feed_opening);
			preg_match_all('/%RSS-FEED%/', $content, $feed_closing);
			preg_match_all('/%RSS-LOOP\|/', $content, $loop_opening);
			preg_match_all('/%RSS-LOOP%/', $content, $loop_closing);
			// check if ONLY ONE of feed opening and closing tags is found
			if ( count($feed_opening[0]) != 1 || count($feed_closing[0]) != 1 ) {
				campaign_save_error(_a("You do not have RSS personalization tags included into your messsage, or you have more than one RSS feed referenced. Please make sure that you have one RSS feed placed into your message before proceeding to the next step."), $id);
				return;
			}
			// check if NO loop tags are found
			if ( count($loop_opening[0]) == 0 || count($loop_closing[0]) == 0 ) {
				campaign_save_error(_a("It seems like you haven't entered any LOOP tags to display your RSS feed items. Please add LOOP tags to your message before proceeding to the next step."), $id);
				return;
			}
			// check if any mismatched tags are found
			if ( count($feed_opening[0]) != count($feed_closing[0]) || count($loop_opening[0]) != count($loop_closing[0]) ) {
				campaign_save_error(_a("It seems like you haven't entered valid RSS personalization tags. Some tags seem to be missing, and your RSS feed would not be properly displayed. Please correct this before proceeding to the next step."), $id);
				return;
			}
		}
	}

	// convert html to text
	if (!$managetext || adesk_sql_select_one("laststep", "#campaign", "id = '$id'") == 'message')
		$up["text"] = message_html2text(array_merge($message, $up));

	$sql = adesk_sql_update("#message", $up, "id = '$mid'");
	if ( !$sql ) {
		campaign_save_error(_a("Message could not be saved due to a database error. Please contact support."), $id);
		return;
	}
	if ( $campup ) adesk_sql_update("#campaign", $campup, "id = '$id'");
	// clear all message sources for this campaign
	campaign_source_clear(null, $mid, null);

	# Extract any links.
	$msg = message_select_row($mid);
	if ( $msg['htmlfetch'] == 'send' and $message['htmlfetchurl'] ) {
		$msg['html'] = adesk_http_get($message['htmlfetchurl'], "UTF-8");
		$msg['html'] = message_link_resolve($msg['html'], $message['htmlfetchurl']);
	}
	$msg["html"] = personalization_basic($msg["html"], $msg["subject"]);
	$links = message_extract_links($msg);

	// add read tracker rows
	$links[] = array(
	  "link" => "open",
	  "title" => "",
	);
	$links[] = array(
	  "link" => "open",
	  "messageid" => 0,
	  "title" => "",
	);

	$saved = array();
	$new_links = array();

	foreach ($links as $link) {
		if ($link["link"] != "open") {
			// $links contains duplicates - one for HTML and one for Text.
			// Only proceed with one instance of the link, so it uses the title attribute for the link name
			if ( !in_array($link["link"], $new_links) ) {
				$new_links[] = $link["link"];
			} else {
				continue;
			}
		}

		$messageid = ( isset($link["messageid"]) ) ? $link["messageid"] : $mid;

		$esc = adesk_sql_escape(message_link_internal($link["link"]));
		$linkid = (int)adesk_sql_select_one('id', '#link', "campaignid = '$id' AND messageid = '$messageid' AND link = '$esc'");

		if (!$linkid) {
			$ins = array(
				"id" => 0,
				"campaignid" => $id,
				"messageid" => $messageid,
				"link" => $link["link"],
				"name" => $link["title"],
				"ref" => "",
				"tracked" => 1,
			);

			$ins = campaign_save_fixlinkname($ins);

			$sql = adesk_sql_insert("#link", $ins);
			$linkid = adesk_sql_insert_id();
		} else {
			// update the name (from title="whatever" attribute)
			$up = array(
				"name" => $link["title"],
			);
			$sql = adesk_sql_update("#link", $up, "id = '$linkid'");
			if ( !$sql ) {
			}
		}

		$saved[] = $linkid;
	}

	# Remove old links.
	$savedstr = implode("','", $saved);
	adesk_sql_delete("#link", "campaignid = '$id' AND messageid = '$mid' AND id NOT IN ('$savedstr') AND link != 'open'");

	campaign_save_readtracking($id, $mid);
}

function campaign_save_text() {
	$id = $GLOBALS["campaign_save_id"];

	if ( $id < 1 ) {
		campaign_save_error(_a("Unknown error occurred. Please try repeating your action. We apologize for this inconvenience..."));
		return;
	}

	$mid = (int)adesk_sql_select_one("SELECT messageid FROM #campaign_message WHERE campaignid = '$id' LIMIT 1");

	# Well, this is weird.
	if ( $mid < 1 ) {
		campaign_save_error(_a("Unknown error occurred. Please try repeating your action. We apologize for this inconvenience..."), $id);
		return;
	}

	# Update the attachment rows, if any.
	$attach = adesk_http_param_forcearray("attach");

	if (count($attach) > 0) {
		$attach = array_map('intval', $attach);
		$attachstr = implode("','", $attach);
		$up = array(
			"messageid" => $mid,
		);

		adesk_sql_update("#message_file", $up, "id IN ('$attachstr')");
	} else {
		adesk_sql_delete("#message_file", "messageid = '$mid'");
	}

	# Update message.
	$up = array(
		"text" => (string)adesk_http_param("text"),
	);

	$fetchurl = trim((string)adesk_http_param("fetchurl"));
	$fetch    = adesk_http_param("fetch");

	if ($fetch == "send" && $fetchurl != "http://" && strlen($fetchurl) > 8) {
		# 8 in case they use "https"
		$up["textfetch"] = campaign_save_fetchmethod($id, $fetch, $fetchurl);
		$up["text"] = "fetch:" . $fetchurl;
	} else {
		# First, we know we're not doing any fetch-at-send, so kill any recurring settings.
		adesk_sql_query("UPDATE #campaign SET willrecur = 0, `type` = 'single', `recurring` = 'day1' WHERE id = '$id' AND `type` = 'recurring'");

		$up["text"] = campaign_save_fixchars($up["text"]);
	}

	# Only update these if the fields exist--and they would exist only in a text-only campaign.
	if (adesk_http_param("fromname")) {
		$up["fromname"] = (string)adesk_http_param("fromname");
	}

	if (adesk_http_param("fromemail")) {
		$up["fromemail"] = (string)adesk_http_param("fromemail");
	}

	if (adesk_http_param("reply2")) {
		$up["reply2"] = (string)adesk_http_param("reply2");
	}

	if (adesk_http_param("subject")) {
		$up["subject"] = (string)adesk_http_param("subject");
	}

	$type = adesk_sql_select_one("SELECT `type` FROM #campaign WHERE id = '$id'");
	if ($type == "text")
		$up["format"] = "text";
	else
		$up["format"] = "mime";

	if ( $GLOBALS["campaign_save_after"] == 'next' ) {
		if ( isset($up["fromemail"]) and !$up["fromname"] ) {
			campaign_save_error(_a("Please enter sender name before proceeding to the next step."), $id);
			return;
		}
		if ( isset($up["fromemail"]) and !adesk_str_is_email($up["fromemail"]) ) {
			campaign_save_error(_a("Please enter a valid sender email address before proceeding to the next step."), $id);
			return;
		}
		if ( isset($up["reply2"]) and !adesk_str_is_email($up["reply2"]) ) {
			campaign_save_error(_a("Please enter a valid reply-to email address before proceeding to the next step."), $id);
			return;
		}
		if ( (isset($up["textfetch"]) && $up["textfetch"] == "now") && isset($up["subject"]) && !$up["subject"] ) {
			campaign_save_error(_a("Please enter a message subject before proceeding to the next step."), $id);
			return;
		}
		if ( !trim($up["text"]) ) {
			campaign_save_error(_a("It seems like you haven't entered any message content. Please enter some content before proceeding to the next step."), $id);
			return;
		}
	}

	adesk_sql_update("#message", $up, "id = '$mid'");
	// clear all message sources for this campaign
	campaign_source_clear(null, $mid, null);

	# Extract any links.
	$msg = message_select_row($mid);
	if ( $msg['textfetch'] == 'send' and isset($up['textfetchurl']) and $up['textfetchurl'] ) {
		$msg['text'] = adesk_http_get($up['textfetchurl'], "UTF-8");
		$msg['text'] = message_link_resolve($msg['text'], $up['textfetchurl']);
	}
	$msg["text"] = personalization_basic($msg["text"], $msg["subject"]);
	$links = message_extract_links($msg);
	$saved = array();
	$new_links = array();

	foreach ($links as $link) {
		if ($link["link"] != "open") {
			// $links contains duplicates - one for HTML and one for Text.
			// Only proceed with one instance of the link, so it uses the title attribute for the link name
			if ( !in_array($link["link"], $new_links) ) {
				$new_links[] = $link["link"];
			} else {
				continue;
			}
		}

		$messageid = ( isset($link["messageid"]) ) ? $link["messageid"] : $mid;

		$esc = adesk_sql_escape(message_link_internal($link["link"]));
		$linkid = (int)adesk_sql_select_one('id', '#link', "campaignid = '$id' AND messageid = '$messageid' AND link = '$esc'");

		if (!$linkid) {
			$ins = array(
				"id" => 0,
				"campaignid" => $id,
				"messageid" => $messageid,
				"link" => $link["link"],
				"name" => $link["title"],
				"ref" => "",
				"tracked" => 1,
			);

			$ins = campaign_save_fixlinkname($ins);

			$sql = adesk_sql_insert("#link", $ins);
			$linkid = adesk_sql_insert_id();
		} else {
			// update the name (from title="whatever" attribute)
			$up = array(
				"name" => $link["title"],
			);
			$sql = adesk_sql_update("#link", $up, "id = '$linkid'");
			if ( !$sql ) {
			}
		}

		$saved[] = $linkid;
	}

	# Remove old links.
	$savedstr = implode("','", $saved);
	adesk_sql_delete("#link", "campaignid = '$id' AND messageid = '$mid' AND id NOT IN ('$savedstr') AND link != 'open'");

	campaign_save_readtracking($id, $mid);
}

function campaign_save_splitmessage() {
	$id = $GLOBALS["campaign_save_id"];

	if ( $id < 1 ) {
		campaign_save_error(_a("Unknown error occurred. Please try repeating your action. We apologize for this inconvenience..."));
		return;
	}

	if ($GLOBALS["campaign_save_after"] == "next") {
		$c = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #campaign_message WHERE campaignid = '$id'");
		if ($c < 2) {
			campaign_save_error(_a("Not enough messages. Please add another message before continuing."));
			return;
		}
	}

	$mid = (int)adesk_http_param("messageid");

	# Well, this is weird.
	if ( $mid < 1 ) {
		campaign_save_error(_a("Unknown error occurred. Please try repeating your action. We apologize for this inconvenience..."), $id);
		return;
	}
	$message = adesk_sql_select_row("SELECT * FROM #message WHERE id = '$mid'");

	$embed  = (int)adesk_http_param("embed_images");

	# Update campaign.
	$up = array(
		"managetext" => (int)adesk_http_param("managetext"),
		"split_type" => adesk_http_param("splittype"),
		"split_offset" => (int)adesk_http_param("splitoffset"),
		"split_offset_type" => adesk_http_param("splitoffsettype"),
		"embed_images" => $embed,
	);

	$managetext = $up["managetext"];	# Need this later.

	if ($up["split_type"] == "winner") {
		$up["split_type"] = adesk_http_param("splitwinnertype");
	}

	adesk_sql_update("#campaign", $up, "id = '$id'");

	# Update the split ratios.
	$percids = adesk_http_param_forcearray("splitmessageid");
	$perc = adesk_http_param_forcearray("splitratio");

	if (count($percids) == count($perc)) {

		if ( count($perc) < 2 ) {
			campaign_save_error(_a("Unknown error occurred. It seems like you are trying to send only one message as a Split Test campaign. You need two or more messages to send a Split Test campaign."), $id);
			return;
		}

		if ( $up["split_type"] != "even" ) {
			$perc = array_map('intval', $perc);
			$totalperc = array_sum($perc);
			if ( $totalperc > 99 ) { // can't be 100 if winner
				campaign_save_error(_a("You need to allocate less than 100% of your subscribers so that a winner message can be selected, then sent to remaining subscribers."), $id);
				return;
			}
			if ( count($percids) != count(array_diff($perc, array(0))) ) {
				campaign_save_error(_a("You can not have zero (0) percent of subscribers allocated to a message, as then that message will not be sent to anyone."), $id);
				return;
			}

		} else {
			// reset always to equal amounts
			foreach ( $perc as $k => $v ) $perc[$k] = 1 / count($percids);
		}

		for ($i = 0; $i < count($percids); $i++) {
			$up = array(
				"percentage" => $perc[$i],
			);

			$percid = (int)$percids[$i];
			adesk_sql_update("#campaign_message", $up, "campaignid = '$id' AND messageid = '$percid'");
		}
	}

	# Update the attachment rows, if any.
	$attach = adesk_http_param_forcearray("attach");

	if (count($attach) > 0 && !$embed) {
		$attach = array_map('intval', $attach);
		$attachstr = implode("','", $attach);
		$up = array(
			"messageid" => $mid,
		);

		adesk_sql_update("#message_file", $up, "id IN ('$attachstr')");
	} else {
		adesk_sql_delete("#message_file", "messageid = '$mid'");
	}

	# Update message.
	$up = array(
		"fromname" => trim((string)adesk_http_param("fromname")),
		"fromemail" => trim((string)adesk_http_param("fromemail")),
		"reply2" => trim((string)adesk_http_param("reply2")),
		"subject" => trim((string)adesk_http_param("subject")),
		"html" => (string)adesk_http_param("html"),
		"format" => "mime",
	);

	$fetchurl = trim((string)adesk_http_param("fetchurl"));
	$fetch    = adesk_http_param("fetch");

	if ($fetch == "send" && $fetchurl != "http://" && strlen($fetchurl) > 8) {
		# 8 in case they use "https"
		$up["htmlfetch"] = campaign_save_fetchmethod($id, $fetch, $fetchurl);
		$up["html"] = "fetch:" . $fetchurl;
		$message["htmlfetchurl"] = $fetchurl;
	} else {
		# First, we know we're not doing any fetch-at-send, so kill any recurring settings.
		adesk_sql_query("UPDATE #campaign SET willrecur = 0, `type` = 'single', `recurring` = 'day1' WHERE id = '$id' AND `type` = 'recurring'");

		$up["htmlfetch"] = "now";
		# Fix any HTML problems in the message.
		$up["html"] = adesk_str_strip_malicious($up["html"]);
		$up["html"] = campaign_save_fixchars($up["html"]);
		$up["html"] = html_savefix($up["html"]);
		$message["htmlfetchurl"] = "";
	}

	if ( $GLOBALS["campaign_save_after"] == 'next' ) {

		// perform checks
		if ( !$up["fromname"] ) {
			campaign_save_error(_a("Please enter sender name before proceeding to the next step."), $id);
			return;
		}
		if ( !adesk_str_is_email($up["fromemail"]) ) {
			campaign_save_error(_a("Please enter a valid sender email address before proceeding to the next step."), $id);
			return;
		}
		if ( $up["reply2"] and !adesk_str_is_email($up["reply2"]) ) {
			campaign_save_error(_a("Please enter a valid reply-to email address before proceeding to the next step."), $id);
			return;
		}
		if ( $up["htmlfetch"] == "now" && !$up["subject"] ) {
			campaign_save_error(_a("Please enter a message subject before proceeding to the next step."), $id);
			return;
		}
		if ( !adesk_str_strip_tags($up["html"]) && $up["html"] == adesk_str_strip_tag_short($up["html"], "img") ) {
			campaign_save_error(_a("It seems like you haven't entered any message content. Please enter some content before proceeding to the next step."), $id);
			return;
		}

	}

	// convert html to text
	if (!$managetext || in_array(adesk_sql_select_one("laststep", "#campaign", "id = '$id'"), array('splitmessage', 'splittext')))
	//if (!$managetext)
		$up["text"] = message_html2text(array_merge($message, $up));

	adesk_sql_update("#message", $up, "id = '$mid'");
	// clear all message sources for this campaign
	campaign_source_clear(null, $mid, null);

	# Extract any links.
	$msg = message_select_row($mid);
	if ( $msg['htmlfetch'] == 'send' and $message['htmlfetchurl'] ) {
		$msg['html'] = adesk_http_get($msg['htmlfetchurl'], "UTF-8");
		$msg['html'] = message_link_resolve($msg['html'], $message['htmlfetchurl']);
	}
	$msg["html"] = personalization_basic($msg["html"], $msg["subject"]);
	$links = message_extract_links($msg);
	$saved = array();
	$new_links = array();

	foreach ($links as $link) {
		if ($link["link"] != "open") {
			// $links contains duplicates - one for HTML and one for Text.
			// Only proceed with one instance of the link, so it uses the title attribute for the link name
			if ( !in_array($link["link"], $new_links) ) {
				$new_links[] = $link["link"];
			} else {
				continue;
			}
		}

		$messageid = ( isset($link["messageid"]) ) ? $link["messageid"] : $mid;

		$esc = adesk_sql_escape(message_link_internal($link["link"]));
		$linkid = (int)adesk_sql_select_one('id', '#link', "campaignid = '$id' AND messageid = '$messageid' AND link = '$esc'");

		if (!$linkid) {
			$ins = array(
				"id" => 0,
				"campaignid" => $id,
				"messageid" => $messageid,
				"link" => $link["link"],
				"name" => $link["title"],
				"ref" => "",
				"tracked" => 1,
			);

			$ins = campaign_save_fixlinkname($ins);

			$sql = adesk_sql_insert("#link", $ins);
			$linkid = adesk_sql_insert_id();
		} else {
			// update the name (from title="whatever" attribute)
			$up = array(
				"name" => $link["title"],
			);
			$sql = adesk_sql_update("#link", $up, "id = '$linkid'");
			if ( !$sql ) {
			}
		}

		$saved[] = $linkid;
	}

	# Remove old links.
	$savedstr = implode("','", $saved);
	adesk_sql_delete("#link", "campaignid = '$id' AND messageid = '$mid' AND id NOT IN ('$savedstr') AND link != 'open'");

	campaign_save_readtracking($id, $mid);
}

function campaign_save_splittext() {
	$id = $GLOBALS["campaign_save_id"];

	if ( $id < 1 ) {
		campaign_save_error(_a("Unknown error occurred. Please try repeating your action. We apologize for this inconvenience..."));
		return;
	}

	$mid = (int)adesk_http_param("messageid");

	# Well, this is weird.
	if ( $mid < 1 ) {
		campaign_save_error(_a("Unknown error occurred. Please try repeating your action. We apologize for this inconvenience..."), $id);
		return;
	}

	# Update message.
	$up = array(
		"text" => (string)adesk_http_param("text"),
	);

	$fetchurl = trim((string)adesk_http_param("fetchurl"));
	$fetch    = adesk_http_param("fetch");

	if ($fetch == "send" && $fetchurl != "http://" && strlen($fetchurl) > 8) {
		# 8 in case they use "https"
		$up["textfetch"] = campaign_save_fetchmethod($id, $fetch, $fetchurl);
		$up["text"] = "fetch:" . $fetchurl;
	} else {
		# First, we know we're not doing any fetch-at-send, so kill any recurring settings.
		adesk_sql_query("UPDATE #campaign SET willrecur = 0, `type` = 'single', `recurring` = 'day1' WHERE id = '$id' AND `type` = 'recurring'");

		$up["text"] = campaign_save_fixchars($up["text"]);
	}

	if ( $GLOBALS["campaign_save_after"] == 'next' ) {
		/*
		if ( isset($up["fromemail"]) and !$up["fromname"] ) {
			campaign_save_error(_a("Please enter sender name before proceeding to the next step."), $id);
			return;
		}
		if ( isset($up["fromemail"]) and !adesk_str_is_email($up["fromemail"]) ) {
			campaign_save_error(_a("Please enter a valid sender email address before proceeding to the next step."), $id);
			return;
		}
		if ( isset($up["reply2"]) and !adesk_str_is_email($up["reply2"]) ) {
			campaign_save_error(_a("Please enter a valid reply-to email address before proceeding to the next step."), $id);
			return;
		}
		*/
		if ( isset($up["subject"]) and !$up["subject"] ) {
			campaign_save_error(_a("Please enter a message subject before proceeding to the next step."), $id);
			return;
		}
		if ( !trim($up["text"]) ) {
			campaign_save_error(_a("It seems like you haven't entered any message content. Please enter some content before proceeding to the next step."), $id);
			return;
		}
	}

	adesk_sql_update("#message", $up, "id = '$mid'");
	// clear all message sources for this campaign
	campaign_source_clear(null, $mid, null);

	# Extract any links.
	$msg = message_select_row($mid);
	if ( $msg['textfetch'] == 'send' and isset($up['textfetchurl']) and $up['textfetchurl'] ) {
		$msg['text'] = adesk_http_get($up['textfetchurl'], "UTF-8");
		$msg['text'] = message_link_resolve($msg['text'], $up['textfetchurl']);
	}
	$msg["text"] = personalization_basic($msg["text"], $msg["subject"]);
	$links = message_extract_links($msg);
	$saved = array();
	$new_links = array();

	foreach ($links as $link) {
		if ($link["link"] != "open") {
			// $links contains duplicates - one for HTML and one for Text.
			// Only proceed with one instance of the link, so it uses the title attribute for the link name
			if ( !in_array($link["link"], $new_links) ) {
				$new_links[] = $link["link"];
			} else {
				continue;
			}
		}

		$messageid = ( isset($link["messageid"]) ) ? $link["messageid"] : $mid;

		$esc = adesk_sql_escape(message_link_internal($link["link"]));
		$linkid = (int)adesk_sql_select_one('id', '#link', "campaignid = '$id' AND messageid = '$messageid' AND link = '$esc'");

		if (!$linkid) {
			$ins = array(
				"id" => 0,
				"campaignid" => $id,
				"messageid" => $messageid,
				"link" => $link["link"],
				"name" => $link["title"],
				"ref" => "",
				"tracked" => 1,
			);

			$ins = campaign_save_fixlinkname($ins);

			$sql = adesk_sql_insert("#link", $ins);
			$linkid = adesk_sql_insert_id();
		} else {
			// update the name (from title="whatever" attribute)
			$up = array(
				"name" => $link["title"],
			);
			$sql = adesk_sql_update("#link", $up, "id = '$linkid'");
			if ( !$sql ) {
			}
		}

		$saved[] = $linkid;
	}

	# Remove old links.
	$savedstr = implode("','", $saved);
	adesk_sql_delete("#link", "campaignid = '$id' AND messageid = '$mid' AND id NOT IN ('$savedstr') AND link != 'open'");

	campaign_save_readtracking($id, $mid);
}

function campaign_save_summary() {
	$id = (int)adesk_http_param("id");

	// fetch campaign
	$campaign = adesk_sql_select_row("SELECT * FROM #campaign WHERE id = '$id'");
	if ( !$campaign ) {
		campaign_save_error(_a("Campaign not found."), $id);
		return;
	}
	// refetch all dependencies (google analytics, autoposting, tracking, public, etc)
	$listids = adesk_sql_select_list("SELECT listid FROM #campaign_list WHERE campaignid = '$id'");
	$liststr = implode("','", $listids);

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
	// can we offer archiving
	$isForPublic = (bool)adesk_sql_select_one("=COUNT(*)", "#list", "id IN ('$liststr') AND private = 0");

	// tracking
	$links = adesk_http_param_forcearray('linkname');
	$track = adesk_http_param_forcearray('linktracked');
	//$tracking = (int)adesk_http_param('tracking');
	$trackreads = (int)adesk_http_param('trackreads');
	$tracklinks = adesk_http_param('tracklinks') ? ( adesk_http_param('linksselectall') ? 'all' : 'mime' ) : 'none';

	// define update array
	$up = array(
		"public" => (int)adesk_http_param("public"),
		"tweet"  => (int)adesk_http_param("tweet"),
		"facebook" => (int)adesk_http_param("facebook"),
		"willrecur" => (int)adesk_http_param("willrecur"),
		"trackreads" => $trackreads,
		"tracklinks" => $tracklinks,
		"trackreadsanalytics" => (int)adesk_http_param("trackreadsanalytics"),
		"tracklinksanalytics" => (int)adesk_http_param("tracklinksanalytics"),
		"schedule" => (int)adesk_http_param("schedule"),
		"responder_existing" => (int)adesk_http_param("responder_existing"),
		"responder_type" => "subscribe",
		"responder_offset" => ((int)adesk_http_param("respondday") * 24) + (int)adesk_http_param("respondhour"),
		"reminder_field" => adesk_http_param("reminder_field"),
		"reminder_format" => adesk_http_param("reminder_format"),
		"reminder_type" => adesk_http_param("reminder_type"),
		"reminder_offset" => (int)adesk_http_param("reminder_offset"),
		"reminder_offset_type" => adesk_http_param("reminder_offset_type"),
		"reminder_offset_sign" => adesk_http_param("reminder_offset_sign"),
		"recurring" => adesk_http_param("recurring"),
		"deskrss_interval" => adesk_http_param("deskrss_interval"),
	);

	// now fix it
	if ( !$pass_facebook ) $up["facebook"] = 0;
	if ( !$pass_twitter ) $up["tweet"] = 0;
	if ( !$showglink or $up['tracklinks'] == 'none' ) $up["tracklinksanalytics"] = 0;
	if ( !$showgread or !$up['trackreads'] ) $up["trackreadsanalytics"] = 0;
	if ( !$isForPublic ) $up['public'] = 0;
	if ( !$up["deskrss_interval"] ) $up['deskrss_interval'] = 'day1';

	$type = $campaign["type"];

	if ($up["willrecur"]) {
		if ($type == "single")
			$up["type"] = "recurring";
	} else {
		if ($type == "recurring")
			$up["type"] = "single";
	}

	# This would be the "exactly" option, which is essentially +0
	if ($up["reminder_offset_sign"] == '=')
		$up["reminder_offset_sign"] = '+';

	# Schedule time
	$date = adesk_http_param("scheduledate");
	$hour = adesk_http_param("schedulehour");
	$minute = adesk_http_param("scheduleminute");

	if ($up["schedule"] && $date != "") {
		$sdate = sprintf("%s %d:%d:00", date("Y-m-d", strtotime($date)), $hour, $minute);
		$sdate = strtotime($sdate) - (adesk_date_offset_hour() * 3600);
		if ($campaign["status"] == 0 || $campaign["status"] == 1 || (in_array($campaign["type"], array("single", "split", "recurring", "text", "deskrss")) && $sdate > time()))
			$up["sdate"] = date("Y-m-d H:i:s", $sdate);
		else
			$up["schedule"] = 0;
	} else {
		$up["=sdate"] = "NOW()";
		//$up["schedule"] = 0;
	}

	if ( $GLOBALS["campaign_save_after"] == 'next' ) {
		if ( 0 && $someError ) {
			campaign_save_error(_a("Some error occurred..."), $id);
			return;
		}
	}

	adesk_sql_update("#campaign", $up, "id = '$id'");

	// save the links
	foreach ($links as $linkid => $linkname) {
		$linkid = (int)$linkid;
		$up2 = array(
			"name" => $linkname,
			"tracked" => (int)( $up['tracklinks'] != 'none' && isset($track[$linkid]) ),
		);
		adesk_sql_update("#link", $up2, "id = '$linkid'");
	}

	if ($tracklinks == "none") {
	  // make sure "open" links are set to tracked=0, in this case
	  $up3 = array(
      "tracked" => 0,
	  );
	  adesk_sql_update("#link", $up3, "campaignid = '$id' AND link = 'open'");
	}

	// save read tracking
	adesk_sql_update_one("#link", "tracked", $up['trackreads'], "campaignid = '$id' AND link = 'open'");

	// refetch the campaign
	$campaign = adesk_sql_select_row("SELECT * FROM #campaign WHERE id = '$id'");

	// initiate a campaign
	if ( $GLOBALS["campaign_save_after"] == 'next' ) {
		//dbg("campaign_init($id);");
		// send now?
		$sendnow = ( in_array($campaign['type'], array('single', /*'recurring',*/ 'split', 'text')) && $campaign["status"] == 0 && $campaign['sdate'] <= (string)adesk_sql_select_one("SELECT NOW()") );
		if ( $sendnow ) {
			campaign_init($id, false);
		} elseif (in_array($campaign["type"], array("single", "split", "text")) && ($campaign["status"] == 3 || $campaign["status"] == 4)) {
			# This campaign is either paused or stopped; try to resume.
			adesk_sql_update_one("#campaign", "status", CAMPAIGN_STATUS_SENDING, "id = '$id'");
		} else {
			// set campaign status
			adesk_sql_update_one('#campaign', 'status', CAMPAIGN_STATUS_SCHEDULED, "id = '$id'");
			// if responder, try to deal with old subscribers (trigger a new campaign)
			if ( $campaign['type'] == 'responder' and $campaign['responder_existing'] ) {
				campaign_responder_oldies($id);
			}
		}
		//campaign_save_result(_a("Campaign Saved."), $id, array('sent' => $sendnow));
		campaign_save_result("", $id, array('sent' => $sendnow));
	}
}

function campaign_save_readtracking($campaignid, $messageid = 0) {

	$id0 = adesk_sql_select_one("id", "#link", "campaignid = '$campaignid' AND messageid = 0 AND link = 'open'");
	$idm = adesk_sql_select_one("id", "#link", "campaignid = '$campaignid' AND messageid = '$messageid' AND link = 'open'");

	$arr = array($id0, $idm);

	// add message 0
	$ins = array(
		"id" => $id0,
		"campaignid" => $campaignid,
		"messageid" => 0,
		"link" => 'open',
		"name" => _a("Read Tracker"),
		//"ref" => "",
		//"tracked" => 1,
	);

	# Do replace in case the id > 0
	adesk_sql_replace("#link", $ins);
	if ( !$id0 ) $id0 = adesk_sql_insert_id();

	// add message $mid
	$ins = array(
		"id" => $idm,
		"campaignid" => $campaignid,
		"messageid" => $messageid,
		"link" => 'open',
		"name" => _a("Read Tracker"),
		//"ref" => "",
		//"tracked" => 1,
	);

	# Do replace in case the id > 0
	adesk_sql_replace("#link", $ins);
	if ( !$idm ) $idm = adesk_sql_insert_id();

	return $id0;
}

function campaign_save_action() {
	$id = (int)adesk_http_param("id");

	$campaignid = (int)adesk_http_param("campaignid");
	$linkid = (int)adesk_http_param("linkid");
	if ( !$linkid ) {
		$linkid = (int)adesk_sql_select_one("id", "#link", "campaignid = '$campaignid' AND messageid = 0 AND link = 'open'");
		if ( !$linkid ) {
			# Uh oh.
			return;
		}
	}

	if ($id > 0) {
		$up = array(
			"id" => $id,
			"campaignid" => $campaignid,
			"linkid" => $linkid,
			"type" => (string)adesk_http_param("type"),
		);

		# Update subscriber_action; remove any existing parts (we'll replace them in a bit).
		adesk_sql_update("#subscriber_action", $up, "id = '$id'");
		adesk_sql_delete("#subscriber_action_part", "actionid = '$id'");
	} else {
		$ins = array(
			"campaignid" => $campaignid,
			"linkid" => $linkid,
			"type" => (string)adesk_http_param("type"),
		);

		# Add new subscriber action row.
		adesk_sql_insert("#subscriber_action", $ins);
		$id = (int)adesk_sql_insert_id();
	}

	if ($id == 0) {
		# Uh oh.
		return;
	}

	# Now save all of the parts.

	$actions = adesk_http_param_forcearray("linkaction");
	$value1  = adesk_http_param_forcearray("linkvalue1");
	$value2  = adesk_http_param_forcearray("linkvalue2");
	$value3  = adesk_http_param_forcearray("linkvalue3");
	$value4  = adesk_http_param_forcearray("linkvalue4");

	foreach ($actions as $action) {
		$v1 = array_shift($value1);
		$v2 = array_shift($value2);
		$v3 = array_shift($value3);
		$v4 = array_shift($value4);

		$ins = array(
			"actionid" => $id,
			"act" => $action,
		);

		switch ($action) {
			case "subscribe":
			case "unsubscribe":
			default:
				$ins["targetid"] = (int)$v1;
				break;

			case "send":
				$ins["targetid"] = (int)$v2;
				break;

			case "update":
				if (preg_match('/^[0-9]+$/', $v3))
					$ins["targetid"] = (int)$v3;
				else
					$ins["targetfield"] = $v3;

				$ins["param"] = $v4;
				break;
		}

		adesk_sql_insert("#subscriber_action_part", $ins);
	}

	return array("count" => count($actions), "linkid" => (int)adesk_http_param("linkid"));
}

function campaign_load_action($id, $campaignid) {
	$id = (int)$id;
	$campaignid = (int)$campaignid;
	$rval = adesk_sql_select_row("SELECT * FROM #subscriber_action WHERE linkid = '$id' AND campaignid = '$campaignid'");

	if (!$rval)
		return array("linkid" => $id, "parts" => array());

	$rval["linkid"] = $id;
	$rval["parts"] = adesk_sql_select_array("SELECT * FROM #subscriber_action_part WHERE actionid = '$rval[id]'");
	return $rval;
}

function campaign_delete_action() {
	$id = (int)adesk_http_param("id");
	$partid = (int)adesk_http_param("partid");
	$actionid = (int)adesk_sql_select_one("SELECT id FROM #subscriber_action WHERE linkid = '$id'");

	if ($partid == 0) {
		# Delete everything.
		adesk_sql_query("DELETE FROM #subscriber_action_part WHERE actionid = '$actionid'");
	} else {
		# Just this one part.
		adesk_sql_query("DELETE FROM #subscriber_action_part WHERE id = '$partid' AND actionid = '$actionid'");
	}

	return array("linkid" => $id);
}

function campaign_save_fixchars($str) {
	$str = str_replace('„', '"', $str);
	$str = str_replace('“', '"', $str);
	$str = str_replace('‘', "'", $str);
	$str = str_replace('’', "'", $str);

	return $str;
}

function campaign_save_fixlinkname($link) {
	if ($link["name"] == "") {
		if (strpos($link["link"], "forward3.php") !== false)
			$link["name"] = _a("Web Copy Link");
		elseif (strpos($link["link"], "forward.php") !== false)
			$link["name"] = _a("Forward to a Friend Link");
		elseif (strpos($link["link"], "forward2.php") !== false)
			$link["name"] = _a("Update Subscriber link");
	}

	return $link;
}

function campaign_save_fetchmethod($campaignid, $fetch, $url) {
	if ($fetch == "send") {
		$campaign = campaign_select_row($campaignid);

		# By default, $campaign will only have list-specific fields.  We'll
		# ignore them and grab all fields, list+global.
		$lists = adesk_array_extract($campaign["lists"], "id");
		$campaign["fields"] = list_get_fields($lists, true);

		# subscriber_dummy doesn't assign the lists property, which we need in
		# subscriber_personalize_get for %LISTID% and some other related
		# things.
		$sub = subscriber_dummy(_a('_t.e.s.t_@example.com'));
		$sub["lists"] = $campaign["lists"];

		$old = $url;
		$new = str_replace(array_keys(subscriber_personalize_get($sub, $campaign)), '', $url);

		# If they differ, then there are pers tags in the URL.  Do fetch-personalized with cust.
		if ($old != $new)
			return "cust";

		# If not, we can get away with fetch-at-send without personalization (which is much faster).
		return "send";
	}

	# If we get here, $fetch is probably "now".  Just return whatever value we have for $fetch.
	return $fetch;
}

?>
