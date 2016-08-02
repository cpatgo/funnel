<?php

require_once awebdesk_functions("log.php");

if ( !defined("awebdesk_ACPDATE_NOSTRFTIME") ) define("awebdesk_ACPDATE_NOSTRFTIME", 1);

// include process hooks
require_once(adesk_admin('functions/process.php'));
require_once adesk_admin("functions/list.php");
require_once adesk_admin("functions/list_field.php");
require_once adesk_admin("functions/subscriber_import.php");
require_once adesk_admin("functions/campaign.php");

adesk_ihook_define("acg_user_delete", "ihook_acg_user_delete");
adesk_ihook_define("acg_user_delete_multi", "ihook_acg_user_delete_multi");
adesk_ihook_define("acg_user_select_row", "ihook_acg_user_select_row");
adesk_ihook_define("acg_user_update_post", "ihook_acg_user_update_post");

adesk_ihook_define("adesk_cron_assets_pre", "ihook_adesk_cron_assets_pre");
adesk_ihook_define("adesk_about_assets_pre", "ihook_adesk_about_assets_pre");

function ihook_adesk_cron_assets_pre($smarty) {
	$smarty->assign("side_content_template", "side.settings.htm");
	return $smarty;
}

function ihook_adesk_about_assets_pre($smarty) {
	$smarty->assign("side_content_template", "side.settings.htm");
	return $smarty;
}

adesk_ihook_define("adesk_sync_sidemenu", "ihook_adesk_sync_sidemenu");

adesk_ihook_define("adesk_user_select_query_conditions", "ihook_adesk_user_select_query_conditions");
adesk_ihook_define("adesk_user_assets_pre", "ihook_adesk_user_assets_pre");
adesk_ihook_define("adesk_user_permission", "ihook_adesk_user_permission");

adesk_ihook_define("adesk_user_account_update", "ihook_adesk_user_account_update");
adesk_ihook_define("adesk_user_account_settings", "ihook_adesk_user_account_settings");
adesk_ihook_define("adesk_user_account_additional", "ihook_adesk_user_account_additional");

adesk_ihook_define("adesk_group_assets_post", "ihook_adesk_user_assets_pre");	# this is intentional; they do the same thing
adesk_ihook_define("adesk_group_permission", "ihook_adesk_group_permission");

adesk_ihook_define("adesk_group_insert_post", "ihook_adesk_group_insert_post");
adesk_ihook_define("adesk_group_relations", "ihook_adesk_group_relations");
adesk_ihook_define("adesk_group_canaccess", "ihook_adesk_group_canaccess");
adesk_ihook_define("adesk_group_delete_relational_post", "ihook_adesk_group_delete_relational_post");
adesk_ihook_define("adesk_group_select_row", "ihook_adesk_group_select_row");
adesk_ihook_define("adesk_group_select_array", "ihook_adesk_group_select_array");
adesk_ihook_define("adesk_group_select_query_condition", "ihook_adesk_group_select_query_condition");

adesk_ihook_define('adesk_admin_get_query', 'ihook_adesk_admin_get_query');
adesk_ihook_define('adesk_admin_get_query_local', 'ihook_adesk_admin_get_query_local');
adesk_ihook_define('adesk_admin_get_post',  'ihook_adesk_admin_get_post');
adesk_ihook_define('adesk_admin_get_noauth', 'ihook_adesk_admin_get_noauth');

adesk_ihook_define('adesk_site_get_post',  'ihook_adesk_site_get_post');

adesk_ihook_define("adesk_mailconn_vars", "ihook_adesk_mailconn_vars");
adesk_ihook_define("adesk_mailconn_row", "ihook_adesk_mailconn_row");
adesk_ihook_define("adesk_mailconn_save", "ihook_adesk_mailconn_save");

adesk_ihook_define('adesk_sync_permission',  'ihook_adesk_sync_permission');
adesk_ihook_define('adesk_sync_tplvars',  'ihook_adesk_sync_tplvars');
adesk_ihook_define('adesk_sync_relations',  'ihook_adesk_sync_relations');
adesk_ihook_define('adesk_sync_destinations_template',  'ihook_adesk_sync_destinations_template');
adesk_ihook_define('adesk_sync_header_template',  'ihook_adesk_sync_header_template');
adesk_ihook_define('adesk_sync_fields',  'ihook_adesk_sync_fields');
adesk_ihook_define('adesk_sync_custom_fields',  'ihook_adesk_sync_custom_fields');
adesk_ihook_define('adesk_sync_options',  'ihook_adesk_sync_options');
adesk_ihook_define('adesk_sync_row',  'ihook_adesk_sync_row');
adesk_ihook_define('adesk_sync_row_report',  'ihook_adesk_sync_row_report');
adesk_ihook_define('adesk_sync_prepare_post',  'ihook_adesk_sync_prepare_post');
adesk_ihook_define('adesk_sync_delete_all',  'ihook_adesk_sync_delete_all');
adesk_ihook_define('adesk_sync_after_delete',  'ihook_adesk_sync_after_delete');

//adesk_ihook_define('adesk_mail_send_message', 'ihook_adesk_mail_send_message');
//adesk_ihook_define('adesk_mail_send_mail', 'ihook_adesk_mail_send_mail');

adesk_ihook_define('adesk_calendar_day'   , 'ihook_calendar_day');
adesk_ihook_define('adesk_calendar_month'   , 'ihook_calendar_month');
adesk_ihook_define('adesk_calendar_link'   , 'ihook_calendar_link');

adesk_ihook_define('adesk_updater_prepend'              , 'ihook_adesk_updater_prepend');
adesk_ihook_define('adesk_updater_version'              , 'ihook_adesk_updater_version');
adesk_ihook_define('adesk_updater_post'                 , 'ihook_adesk_updater_post');


adesk_ihook_define('adesk_mailer_delete'              , 'ihook_adesk_mailer_delete');

adesk_ihook_define('adesk_widget_bars', 'ihook_adesk_widget_bars');

adesk_ihook_define('acg_loginsource_assets', 'ihook_acg_loginsource_assets');


adesk_ihook_define('adesk_upload_js_addon', 'ihook_adesk_upload_js_addon');

function ihook_adesk_sync_sidemenu() {
	return "side.subscriber.htm";
}

function ihook_acg_user_delete($absid, $extra) {
	if (!$extra)
		return;

	$absid  = intval($absid);
	$userid = adesk_sql_select_one("SELECT id FROM #user WHERE absid = '$absid'");
	$listids = adesk_sql_select_list("SELECT id FROM #list WHERE userid = '$userid'");

	foreach ($listids as $listid)
		list_delete($listid);

	# If there are any campaigns we own but that have no list at all (we never
	# passed step 2), just delete them.  They're unreachable anyway.
	$orphans = adesk_sql_select_list("SELECT id FROM #campaign WHERE userid = '$userid'");
	foreach ($orphans as $cid) {
		$c = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #campaign_list WHERE campaignid = '$cid'");
		if ($c == 0)
			campaign_delete($cid);
	}

	# What's left are campaigns which are otherwise reachable (they have some
	# lists).  We'll just reassign the user to admin.
	adesk_sql_update_one("#campaign", "userid", 1, "userid = '$userid'");
}

function ihook_acg_user_delete_multi($absids, $extra) {
	if (!$extra)
		return;

	$absids = array_map("intval", explode(",", $absids));

	foreach ($absids as $absid) {
		$userid = adesk_sql_select_one("SELECT id FROM #user WHERE absid = '$absid'");
		$listids = adesk_sql_select_list("SELECT id FROM #list WHERE userid = '$userid'");

		foreach ($listids as $listid)
			list_delete($listid);

		adesk_sql_update_one("#campaign", "userid", 1, "userid = '$userid'");
	}
}

function ihook_acg_user_select_row($ary) {
	# We use select row here because we might need to select additional
	# information in the future.

	#$ary["active"] = $row["active"];
	return $ary;
}

function ihook_acg_user_update_post($userid, $update = false) {
	$userid = intval($userid);

	if ( !$update ) {
		$ary = array(
			'=sdate' => 'NOW()'
		);

		if (isset($GLOBALS["site"])) {
			$ary["local_zoneid"] = $GLOBALS["site"]["local_zoneid"];
		}
		# $userid here is the actual user table's record id.
		adesk_sql_update("#user", $ary, "`id` = '$userid'");
	}

	# Update their permissions.
	require_once adesk_admin("functions/user.php");
	user_rebuild_permissions($userid);
}

function ihook_adesk_group_insert_post($ary) {
	# This function handles both inserts and updates to groups.  It's really
	# here to grab extra data from the post and put it into $ary if it belongs
	# in the group table.

	$ary["unsubscribelink"]       = intval(isset($_POST["unsubscribelink"]));
	$ary["optinconfirm"]          = intval(isset($_POST["optinconfirm"]));
	$ary["p_admin"]               = 1;	# It is always the case that a user in AwebDeskis an "admin" user.
	$ary["pg_user_add"]           = intval(isset($_POST["pg_user_add"]));
	$ary["pg_user_edit"]          = intval(isset($_POST["pg_user_edit"]));
	$ary["pg_user_delete"]        = intval(isset($_POST["pg_user_delete"]));
	$ary["pg_list_add"]           = intval(isset($_POST["pg_list_add"]));
	$ary["pg_list_edit"]          = intval(isset($_POST["pg_list_edit"]));
	$ary["pg_list_delete"]        = intval(isset($_POST["pg_list_delete"]));
	$ary["pg_list_opt"]           = intval(isset($_POST["pg_list_opt"]));
	$ary["pg_list_headers"]       = intval(isset($_POST["pg_list_headers"]));
	$ary["pg_list_emailaccount"]  = intval(isset($_POST["pg_list_emailaccount"]));
	$ary["pg_list_bounce"]        = intval(isset($_POST["pg_list_bounce"]));
	$ary["pg_message_add"]        = intval(isset($_POST["pg_message_add"]));
	$ary["pg_message_edit"]       = intval(isset($_POST["pg_message_edit"]));
	$ary["pg_message_delete"]     = intval(isset($_POST["pg_message_delete"]));
	$ary["pg_message_send"]       = intval(isset($_POST["pg_message_send"]));
	$ary["pg_subscriber_add"]     = intval(isset($_POST["pg_subscriber_add"]));
	$ary["pg_subscriber_edit"]    = intval(isset($_POST["pg_subscriber_edit"]));
	$ary["pg_subscriber_delete"]  = intval(isset($_POST["pg_subscriber_delete"]));
	$ary["pg_subscriber_import"]  = intval(isset($_POST["pg_subscriber_import"]));
	$ary["pg_subscriber_export"]  = intval(isset($_POST["pg_subscriber_export"]));
	$ary["pg_subscriber_sync"]    = intval(isset($_POST["pg_subscriber_sync"]));
	$ary["pg_subscriber_approve"] = intval(isset($_POST["pg_subscriber_approve"]));
	$ary["pg_subscriber_filters"] = intval(isset($_POST["pg_subscriber_filters"]));
	$ary["pg_subscriber_actions"] = intval(isset($_POST["pg_subscriber_actions"]));
	$ary["pg_subscriber_fields"]  = intval(isset($_POST["pg_subscriber_fields"]));
	$ary["pg_form_add"]           = intval(isset($_POST["pg_form_add"]));
	$ary["pg_form_edit"]          = intval(isset($_POST["pg_form_edit"]));
	$ary["pg_form_delete"]        = intval(isset($_POST["pg_form_delete"]));
	$ary["pg_template_add"]       = intval(isset($_POST["pg_template_add"]));
	$ary["pg_template_edit"]      = intval(isset($_POST["pg_template_edit"]));
	$ary["pg_template_delete"]    = intval(isset($_POST["pg_template_delete"]));
	$ary["pg_reports_campaign"]   = intval(isset($_POST["pg_reports_campaign"]));
	$ary["pg_reports_list"]       = intval(isset($_POST["pg_reports_list"]));
	$ary["pg_reports_user"]       = intval(isset($_POST["pg_reports_user"]));
	$ary["pg_reports_trend"]      = intval(isset($_POST["pg_reports_trend"]));
	$ary["=sdate"]                = 'NOW()';
	if ( isset($GLOBALS['_hosted_account']) ) {
		$ary['req_approval']          = 1;
		$ary['req_approval_1st']      = 2;
		$ary['req_approval_notify']   = base64_decode('YWJ1c2VAYXdlYmRlc2suY29t');
		//$ary['abuseratio']            = 2;
		//$ary['forcesenderinfo']       = 1;
	} else {
		$ary['req_approval']          = (int)isset($_POST["req_approval"]);
		$ary['req_approval_1st']      = ( $ary['req_approval'] ? (int)$_POST["req_approval_1st"]    : 2 );
		$ary['req_approval_notify']   = ( $ary['req_approval'] ? (string)$_POST["req_approval_notify"] : '' );
	}

	return $ary;
}

function ihook_adesk_group_relations($id) {
	$id  = intval($id);
	$delete_group_limit = adesk_sql_query("DELETE FROM #group_limit WHERE groupid = '$id'");

	$ary = array(
		"groupid"             => $id,
		"limit_mail"          => (adesk_http_param("group_limit_mail_checkbox") != "") ? intval(adesk_http_param("limit_mail")) : 0,
		"limit_mail_type"     => (adesk_http_param("group_limit_mail_checkbox") != "") ? adesk_http_param("limit_mail_type") : 'month',
		"limit_subscriber"    => (adesk_http_param("group_limit_subscriber_checkbox") != "") ? intval(adesk_http_param("limit_subscriber")) : 0,
		"limit_list"          => (adesk_http_param("group_limit_list_checkbox") != "") ? intval(adesk_http_param("limit_list")) : 0,
		"limit_campaign"      => (adesk_http_param("group_limit_campaign_checkbox") != "") ? intval(adesk_http_param("limit_campaign")) : 0,
		"limit_campaign_type" => (adesk_http_param("group_limit_campaign_checkbox") != "") ? adesk_http_param("limit_campaign_type") : 'month',
		"limit_attachment"    => (adesk_http_param("group_limit_attachment_checkbox") != "") ? intval(adesk_http_param("limit_attachment")) : -1,
		"limit_user"          => (adesk_http_param("group_limit_user_checkbox") != "") ? intval(adesk_http_param("limit_user")) : 0,
		"abuseratio"          => ( adesk_http_param_exists("abuseratio") ? (int)adesk_http_param("abuseratio") : 4 ), // check the comment below
		"forcesenderinfo"     => (int)adesk_http_param_exists("forcesenderinfo"),
	);

	if ( isset($GLOBALS['_hosted_account']) ) {
		$ary['abuseratio'] = 2; // we should comment out this one and the line above so it doesn't revert the change made from public complaint assets
		$ary['forcesenderinfo'] = 1;
		$ary["limit_attachment"] = 1;
	}
	//dbg($ary);
	adesk_sql_insert("#group_limit", $ary);

	adesk_sql_query("DELETE FROM #list_group WHERE groupid = '$id'");
	if (isset($_POST["lists"])) {
		$lists = $_POST["lists"];

		foreach ($lists as $listid) {
			$ary = array(
				"listid"  => intval($listid),
				"groupid" => $id,
			);

			adesk_sql_insert("#list_group", $ary);
		}
	}

	adesk_sql_query("DELETE FROM #group_mailer WHERE groupid = '$id'");
	if (isset($GLOBALS['_hosted_account'])) {
		$ary = array(
			"groupid" => $id,
			"mailerid" => 1,
		);

		adesk_sql_insert("#group_mailer", $ary);
	} elseif (isset($_POST["sendmethods"])) {
		$methods = ( is_array($_POST["sendmethods"][0]) ) ? $_POST["sendmethods"][0] : explode(",", $_POST["sendmethods"][0]);

		foreach ($methods as $mailerid) {
			$ary = array(
				"groupid"  => $id,
				"mailerid" => intval($mailerid),
			);

			adesk_sql_insert("#group_mailer", $ary);
		}
	}

	// regenerate all users of this group
	$users = adesk_sql_select_box_array("SELECT userid, userid FROM #user_group WHERE groupid = '$id'");
	foreach ( $users as $userid ) {
		user_rebuild_permissions($userid);
	}

	// Update branding info
	$from = 0;
	if (isset($GLOBALS["admin"]) && count($GLOBALS["admin"]["groups"]) > 0)
		$from = current($GLOBALS["admin"]["groups"]);
	$group_branding_update = branding_select_row($id, $from);
}

function ihook_adesk_group_canaccess($userid, $groups) {
	$groupstr = implode("','", $groups);
	$userid   = (int)$userid;
	$rs       = adesk_sql_query($q = "
		SELECT
			g.limit_user,
			(SELECT COUNT(*) FROM #user_group ug WHERE ug.groupid = g.groupid AND ug.userid != '$userid') AS a_users
		FROM
			#group_limit g
		WHERE
			g.groupid IN ('$groupstr')
	");

	while ($row = adesk_sql_fetch_assoc($rs)) {
		if ($row["limit_user"] > 0 && $row["a_users"] + 1 > $row["limit_user"])
			return false;
	}

	return true;
}

function ihook_adesk_group_delete_relational_post($id, $alt) {
	$id = intval($id);
	adesk_sql_query("DELETE FROM #group_limit WHERE groupid = '$id'");
	adesk_sql_query("DELETE FROM #branding WHERE groupid = '$id'");
}

function ihook_adesk_group_select_row($row) {
	if ( !$row ) return false;

	$lim = adesk_sql_select_row("SELECT * FROM #group_limit WHERE groupid = '$row[id]'");
	unset($lim["id"]);
	unset($lim["groupid"]);

	$row = array_merge($lim, $row);

	$row["lists"] = implode(",", adesk_sql_select_list("SELECT listid FROM #list_group WHERE groupid = '$row[id]'"));
	$row["sendmethods"] = implode(",", adesk_sql_select_list("SELECT mailerid FROM #group_mailer WHERE groupid = '$row[id]'"));

	if ($GLOBALS["site"]["v5full_resell"]) {
		if ($lim["limit_mail"] <= 50 && $lim["limit_mail_type"] == "ever")
			$row["a_istrial"] = _a("(Trial)");
	}
	//limit hack sandeep
$admin22 = adesk_admin_get();
$uid = $admin22['id'];
	// calculate abuse ratio
	$row['abuses_reported'] = (int)adesk_sql_select_one('=COUNT(*)', '#abuse', "`groupid` = '$row[id]'");
	$row['emails_sent'] = (int)adesk_sql_select_one('=SUM(amt)', '#campaign_count', "`userid` = '$uid'");
	if ( $row['emails_sent'] and $GLOBALS['site']['mail_abuse'] ) {
		$row['abuseratio_current'] = number_format($row['abuses_reported'] / $row['emails_sent'] * 100, 2);
	} else {
		$row['abuseratio_current'] = 0;
	}
	if ( $row['emails_sent'] > 10 ) {
		$row['abuseratio_overlimit'] = (int)( $row['abuseratio_current'] > $row['abuseratio'] );
	} else {
		$row['abuseratio_overlimit'] = 0;
	}

	// get members of this group
	$row['users'] = array();
	if ( $row['abuseratio_overlimit'] ) {
		$users = adesk_sql_select_list("SELECT userid FROM #user_group WHERE groupid = '$row[id]'");
		$row['users'] = user_get($users);
	}

	return $row;
}

function ihook_adesk_group_select_array($rows) {
	foreach ($rows as $k => $row) {
		$lim = adesk_sql_select_row("SELECT * FROM #group_limit WHERE groupid = '$row[id]'");
		if ($GLOBALS["site"]["v5full_resell"]) {
			if ($lim["limit_mail"] <= 50 && $lim["limit_mail_type"] == "ever")
				$rows[$k]["a_istrial"] = _a("(Trial)");
		}
	}

	return $rows;
}

function ihook_adesk_group_select_query_condition() {
	# Exclude the Visitor group (id=1) and User group (id=2).
	return "AND id != 1 AND id != 2";
}

function ihook_adesk_admin_get_query($userId) {
    return adesk_sql_query("SELECT * FROM #user WHERE absid = '$userId'");
}

function ihook_adesk_admin_get_query_local($userId) {
    return adesk_sql_query("SELECT * FROM #user WHERE id = '$userId'");
}

function ihook_adesk_admin_get_post($a) {
	// fetch his group and all permissions he has access to
	if ( !$a ) return $a;

	# TinyMCE's image manager needs this
	if ($a["id"] != 0) {
		$_SESSION["ACIsLoggedIn"] = true;
		if (isset($GLOBALS['_hosted_account']))
			$_SESSION["ACRootPath"] = "/images/" . $_SESSION[$GLOBALS["domain"]]["account"] . "/" . basename($a['username']);
		else
			$_SESSION["ACRootPath"] = adesk_base("images/" . basename($a['username']));
	}

	if (isset($_SESSION["ACRootPath"])) {
		$path  = $_SESSION["ACRootPath"];
		$mcith = $path . "/mcith";

		if (!file_exists($path)) {
			@mkdir($path);
			@chmod($path, 0777);
		}

		if (!file_exists($mcith)) {
			@mkdir($mcith);
			@chmod($mcith, 0777);
		}
	}

	// here we should deal with groups
	$a['groups'] = user_get_groups($a['id']);
	$groups = implode("', '", $a['groups']);
	// here we should deal with allowed categories
	$a['lists'] = adesk_sql_select_box_array("SELECT listid, listid FROM #list_group WHERE groupid IN ('$groups')");
	$a['methods'] = adesk_sql_select_box_array("SELECT mailerid, mailerid FROM #group_mailer WHERE groupid IN ('$groups')");
	// set limits
	$a['limit_user'] =
	$a['limit_list'] =
	$a['limit_subscriber'] =
	$a['limit_mail'] = 1;
	$a['limit_mail_type'] = '';
	$a['limit_campaign'] = 1;
	$a['limit_campaign_type'] = '';
	$a['limit_attachment'] = -1;
	// this is a hack for "unsubscribelink" and "optinconfirm" fields cuz they don't start with p_ or pg_
	$a['unsubscribelink'] =
	$a['optinconfirm'] = 1;
	$a['abuseratio'] =
	$a['forcesenderinfo'] =
	$a['req_approval'] =
	$a['req_approval_1st'] = 0;
	$a['req_approval_notify'] = '';
	// here we should deal with global permissions
	$sql = adesk_sql_query("SELECT * FROM #group g, #group_limit gl WHERE g.id IN ('$groups') AND gl.groupid = g.id");
	while ( $row = mysql_fetch_assoc($sql) ) {
		// set limits
		// users limit
		if ( $row['limit_user'] == 0 ) {
			$a['limit_user'] = 0;
		} elseif ( $a['limit_user'] != 0 and $row['limit_user'] > $a['limit_user'] ) {
			$a['limit_user'] = $row['limit_user'];
		}
		// lists limit
		if ( $row['limit_list'] == 0 ) {
			$a['limit_list'] = 0;
		} elseif ( $a['limit_list'] != 0 and $row['limit_list'] > $a['limit_list'] ) {
			$a['limit_list'] = $row['limit_list'];
		}
		// subscribers limit
		if ( $row['limit_subscriber'] == 0 ) {
			$a['limit_subscriber'] = 0;
		} elseif ( $a['limit_subscriber'] != 0 and $row['limit_subscriber'] > $a['limit_subscriber'] ) {
			$a['limit_subscriber'] = $row['limit_subscriber'];
		}
		// campaigns limit
		if ( $row['limit_campaign'] == 0 ) {
			$a['limit_campaign'] = 0;
		} elseif ( $a['limit_campaign'] != 0 and $row['limit_campaign'] > $a['limit_campaign'] ) {
			$a['limit_campaign'] = $row['limit_campaign'];
		}
		if ( $a['limit_campaign_type'] != 'ever' ) $a['limit_campaign_type'] = $row['limit_campaign_type'];
		// emails limit
		if ( $row['limit_mail'] == 0 ) {
			$a['limit_mail'] = 0;
		} elseif ( $a['limit_mail'] != 0 and $row['limit_mail'] > $a['limit_mail'] ) {
			$a['limit_mail'] = $row['limit_mail'];
		}
		if ( $a['limit_mail_type'] != 'ever' ) $a['limit_mail_type'] = $row['limit_mail_type'];
		// attachments limit
		if ( $row['limit_attachment'] == -1 ) {
			$a['limit_attachment'] = -1;
		} elseif ( /*$a['limit_attachment'] != -1 and */$row['limit_attachment'] > $a['limit_attachment'] ) {
			$a['limit_attachment'] = $row['limit_attachment'];
		}
		// loop through all permissions in every group
		foreach ( $row as $k => $v ) {
			// looking for perms only
			if ( substr($k, 0, 3) == 'pg_' or substr($k, 0, 2) == 'p_' ) {
				// if not set, or has NO ACCESS, overwrite
				if ( !isset($a[$k]) or !$a[$k] ) {
					$a[$k] = $row[$k];
				}
			}
		}
		// this is a hack for fields that don't start with p_ or pg_
		if ( !$row['unsubscribelink'] ) $a['unsubscribelink'] = $row['unsubscribelink'];
		if ( !$row['optinconfirm'] ) $a['optinconfirm'] = $row['optinconfirm'];
		if ( !$row['abuseratio'] or $row['abuseratio'] > $a['abuseratio'] ) $a['abuseratio'] = $row['abuseratio'];
		if ( isset($row['forcesenderinfo']) && $row['forcesenderinfo'] ) $a['forcesenderinfo'] = $row['forcesenderinfo'];
		if ( $row['req_approval'] ) {
			$a['req_approval'] = $row['req_approval'];
			if ( $row['req_approval_1st'] > $a['req_approval_1st'] ) {
				$a['req_approval_1st'] = $row['req_approval_1st'];
			}
			if ( $row['req_approval_notify'] ) {
				$old = array_diff(array_map('trim', explode(',', $a['req_approval_notify']  )), array(''));
				$new = array_diff(array_map('trim', explode(',', $row['req_approval_notify'])), array(''));
				$res = array_unique(array_merge($new, $old));
				$a['req_approval_notify'] = implode(',', $res);
			}
		}
	}
	if ( isset($GLOBALS['_hosted_account']) ) {
		$a['req_approval']          = 1;
		$a['req_approval_1st']      = 2;
		$a['req_approval_notify']   = base64_decode('YWJ1c2VAYXdlYmRlc2suY29t');
		$a['abuseratio']            = 2;
		$a['forcesenderinfo']       = 1;
	}
	if ( $a['limit_campaign_type'] == '' ) $a['limit_campaign_type'] = 'ever';
	if ( $a['limit_mail_type']     == '' ) $a['limit_mail_type']     = 'ever';
	// ABSOLUTE admin switch (#1)
	if ( $a['id'] == 1 ) {
		foreach ( $a as $k => $v ) {
			if ( substr($k, 0, 2) == 'p_' ) $a[$k] = 1;
			if ( substr($k, 0, 3) == 'pg_' and $k != 'pg_startup_gettingstarted' ) $a[$k] = 1;
		}
	}

	// license subscribers limit
	global $site;
	if ( $site['v5full'] and $site['subscribersMax'] ) {
		if ( $site['subscribersMax'] < $a['limit_subscriber'] or !$a['limit_subscriber'] ) {
			$a['limit_subscriber'] = $site['subscribersMax'];
		}
		//if ( $a['id'] != 1 ) $a['limit_subscriber'] /= $site['adminsMax'];
	}

	// get branding settings
	require_once(adesk_admin('functions/branding.php'));
	if (count($a['groups']) > 0) {
		$branding = branding_select_row(current($a['groups']));
		unset($branding['id']);unset($branding['groupid']);$branding['version'] = !$branding['version'];
		foreach ( $branding as $k => $v ) $a['brand_' . $k] = $v;
	}
	// get message counts
	$a['campaigns_sent'] = limit_count($a, 'campaign', false);
	$a['campaigns_sent_total'] = limit_count($a, 'campaign', true);
	$a['emails_sent'] = limit_count($a, 'mail', false);
	$a['emails_sent_total'] = limit_count($a, 'mail', true);
	$a['emails_sent_total_formatted'] = number_format($a['emails_sent_total']);

	// calculate abuse ratio
	$a['abuses_reported'] = (int)adesk_sql_select_one('=COUNT(*)', '#abuse', "`groupid` IN ('$groups')");
	//$a['emails_sent'] = (int)adesk_sql_select_one('=SUM(amt)', '#campaign_count', "`groupid` IN ('$groups')");
	if ( $a['emails_sent_total'] ) {
		$a['abuseratio_current'] = number_format($a['abuses_reported'] / $a['emails_sent_total'] * 100, 2);
	} else {
		$a['abuseratio_current'] = 0;
	}
	if ( $a['emails_sent_total'] > 10 ) {
		$a['abuseratio_overlimit'] = (int)( $a['abuseratio_current'] > $a['abuseratio'] );
	} else {
		$a['abuseratio_overlimit'] = 0;
	}

	// is approval needed for sending his campaigns?
	$a['send_approved'] = !approval_needed($a);

	$GLOBALS['admin'] = $a; // stupid hack
	return $a;
}

function ihook_adesk_admin_get_noauth() {
	$guest = array_merge(
		adesk_sql_default_row('aweb_globalauth', true),
		adesk_sql_default_row('#user')
	);
	$guest['fullname'] = '';
	$guest['campaigns_sent'] =
	$guest['campaigns_sent_total'] =
	$guest['emails_sent'] =
	$guest['emails_sent_total'] = 0;
	// here we should deal with groups
	$guest['groups'] = array(1 => 1);
	// here we should deal with allowed lists
	$guest['lists'] = adesk_sql_select_box_array("SELECT listid, listid FROM #list_group WHERE groupid = 1");
	// here we should deal with global permissions
	$sql = adesk_sql_query("SELECT * FROM #group WHERE id = 1");
	while ( $row = mysql_fetch_assoc($sql) ) {
		// loop through all permissions in every group
		foreach ( $row as $k => $v ) {
			// looking for perms only
			if ( substr($k, 0, 3) == 'pg_' or substr($k, 0, 2) == 'p_' ) {
				// if not set, or has NO ACCESS, overwrite
				if ( !isset($guest[$k]) or !$guest[$k] ) {
					$guest[$k] = $row[$k];
				}
			}
		}
	}

	// get branding settings
	require_once(adesk_admin('functions/branding.php'));
	$branding = branding_select_row(3); // site defaults are in admin group
	unset($branding['id']);unset($branding['groupid']);$branding['version'] = !$branding['version'];
	foreach ( $branding as $k => $v ) $guest['brand_' . $k] = $v;

	// gather cookie info here
	$site = adesk_site_get();
	$guest['lang'] = ( isset($_COOKIE['adesk_lang']) ? $_COOKIE['adesk_lang'] : $site['lang'] );
	if ( isset($_COOKIE['adesk_default_dashboard']) )
		$guest['default_dashboard'] =  $_COOKIE['adesk_default_dashboard'];
	if ( isset($_COOKIE['adesk_lists_per_page']) )
		$guest['lists_per_page'] = (int)$_COOKIE['adesk_lists_per_page'];
	if ( isset($_COOKIE['adesk_comments_per_page']) )
		$guest['messages_per_page'] = (int)$_COOKIE['adesk_messages_per_page'];

	return $guest;
}

function ihook_adesk_site_get_post($site) {
	$rs = adesk_sql_query("
		SELECT
			m.type AS stype,
			m.host AS smhost,
			m.port AS smport,
			m.user AS smuser,
			m.pass AS smpass,
			m.encrypt AS smenc,
			m.pop3b4smtp AS smpop3b4,
			m.threshold AS smthres,
			m.frequency AS sdfreq,
			m.pause AS sdnum,
			m.limit AS sdlim,
			m.limitspan AS sdspan,
				m.dotfix AS sddotfix,
				m.sent AS sdsent,
			'' AS awebdesk_bounce
		FROM
			`#mailer` m
		WHERE
			m.current = 1
	");
	// check if system is up to date
	/* 27th sept 2012 sandeep
	if ( !$rs or !mysql_num_rows($rs) ) {
		$path = ( ( isset($_SERVER['REQUEST_URI']) and strpos($_SERVER['REQUEST_URI'], '/manage/') !== false ) ? '' : 'manage/' );
		echo 'Please run <a href="' . $path . 'updater.php" rel="nofollow">updater.php</a> to update this product.';
		exit;
	} 
	*/
	$row = mysql_fetch_assoc($rs);
		/* 27th sept 2012 sandeep $site = array_merge($site, $row); */

	$site["isAEM"] = true;
	// get default branding settings
/*
	// old code - fetch defaults from database
	require_once(adesk_admin('functions/branding.php'));
	$branding = branding_select_row(3); // site defaults are in admin group
	unset($branding['id']);unset($branding['groupid']);$branding['version'] = !$branding['version'];
	foreach ( $branding as $k => $v ) $site['brand_' . $k] = $v;
	$site['site_name'] = $branding['site_name'];
	$site['site_logo'] = $branding['site_logo'];
*/
	// new code - try to take from admin
	if ( isset($GLOBALS['admin']) ) {
		$admin = $GLOBALS['admin'];
		foreach ( $admin as $k => $v ) {
			if ( substr($k, 0, 6) == 'brand_' ) $site[$k] = $v;
		}
		$site['site_name'] = $site['brand_site_name'] = $admin['brand_site_name'];
		$site['site_logo'] = $site['brand_site_logo'] = $admin['brand_site_logo'];
	}
	// common settings
	$site['pspell'] = function_exists('pspell_suggest');
	$site['gd'] = function_exists('gd_info');
	$site['zip'] = function_exists('gzcompress');

	// debugging
	$debugging = 0;
	if ( isset($GLOBALS['mailer_log_file']) ) {
		// use engine file setting
		$site['mailer_log_file'] = (int)$GLOBALS['mailer_log_file'];
	} else {
		// save backend setting as "engine"
		if (isset($site["mailer_log_file"]))
			$GLOBALS['mailer_log_file'] = (int)$site['mailer_log_file'];
	}

	// switch to HTTPS if SSL is requested
	if ( adesk_http_is_ssl() ) {
		if ( preg_match('/^http:\/\//i', $site['p_link']) ) {
			$site['p_link'] = preg_replace('/^http:\/\//i', 'https://', $site['p_link']);
		}
	} else {
		if ( preg_match('/^https:\/\//i', $site['p_link']) ) {
			$site['p_link'] = preg_replace('/^https:\/\//i', 'http://', $site['p_link']);
		}
	}

	/*
		HOSTED CHECK
	*/
	$site['p_link_precname'] = $site['p_link'];
	// if it is hosted

	if ( isset($GLOBALS['_hosted_account']) ) {
		$site['mail_abuse'] = 1;
		// break the db url
		$tmp = parse_url($site['p_link']);
		if ( !isset($tmp['host']) ) {
			$tmp['scheme'] = ( adesk_http_is_ssl() ? 'https' : 'http' );
			$tmp['host'] = $GLOBALS["domain"];
			$site['p_link'] = (
				$tmp['scheme'] .
				'://' .
				$tmp['host'] .
				( substr($site['p_link'], 0, 1) == '/' ? '' : '/' ) .
				$site['p_link']
			);
		}
		$dbhost = $tmp['host'];
		// version that uses server value
		$enhost = $GLOBALS["domain"];
		// if db host is different than http_host
		if ( strtolower($enhost) != strtolower($dbhost) ) {
			/* we need to modify the db host */
			// if environment points to cname
			if ( strtolower($enhost) == strtolower($GLOBALS['_hosted_cname']) ) {
				// replace the db url with hosted cname domain
				$site['p_link'] = str_replace('://' . $dbhost, '://' . $GLOBALS['_hosted_cname'], $site['p_link']);
			// if environment points to internal
			} elseif ( strtolower($enhost) == strtolower($GLOBALS['_hosted_account']) ) {
				// replace the db url with hosted internal domain
				$site['p_link'] = str_replace('://' . $dbhost, '://' . $GLOBALS['_hosted_account'], $site['p_link']);
			} else {
				// the domain is neither internal nor cname
			}
		}
	}

	if (function_exists("session_load"))
		session_load($site);
	return $site;
}

function ihook_adesk_mailconn_row($row) {
	$row['groups'] = adesk_sql_select_box_array("SELECT groupid, groupid FROM #group_mailer WHERE mailerid = '$row[id]'");
	$row['groupslist'] = implode(',', $row['groups']);
	return $row;
}

function ihook_adesk_mailconn_vars($smarty) {
	require_once(awebdesk_functions('group.php'));
	$smarty->assign('innertemplate', 'mailer.form.htm');
	// get groups
	$so = new adesk_Select;
	//$so->push("AND id > 1");				# Exclude the Visitors group
	$so->push("AND p_admin = 1");				# Exclude the non-admin groups
	$so->orderby("title");
	$groups = adesk_group_select_array($so);
	$smarty->assign('groupsList', $groups);
	return $smarty;
}

function ihook_adesk_mailconn_save($id, $arr) {
	$add = ( isset($arr['id']) and $arr['id'] == 0 );
	// assign mailers to groups
	$groups = array_diff(array_map('intval', (array)adesk_http_param('p')), array(0));
	$groupslist = implode(',', $groups);
	$r = array(
		'groups' => $groups,
		'groupslist' => $groupslist,
	);
	//if ( count($groups) == 0 ) return $r;
	if ( !$add ) {
		// remove all old group mailer relations
		adesk_sql_delete('#group_mailer', "mailerid = '$id'");
	}
	// add any campaign/group mailers
	foreach ( $groups as $g ) {
		$insert = array(
			'id' => 0,
			'groupid' => $g,
			'mailerid' => $id,
		);
		adesk_sql_insert('#group_mailer', $insert);
	}
	return $r;
}

function ihook_adesk_sync_permission() {
	$admin = adesk_admin_get();
	return $admin['pg_subscriber_sync'];
	//return adesk_admin_ismain();
}

function ihook_adesk_sync_relations() {
	$lists = list_get_all();
	$r = array();
	foreach ( $lists as $k => $v ) {
		$r[$k] = $v['name'];
	}
	return $r;
}

function ihook_adesk_sync_destinations_template() {
	if ( (int)adesk_sql_select_one('=COUNT(*)', '#subscriber_import') > 100000 ) {
		adesk_sql_query("TRUNCATE TABLE #subscriber_import");
	} else {
		adesk_sql_delete('#subscriber_import', "`tstamp` < SUBDATE(NOW(), INTERVAL 3 DAY)");
	}
	return 'subscriber_import.inc.htm';
}

function ihook_adesk_sync_header_template() {
	return 'sync.header.inc.htm';
}

function ihook_adesk_sync_fields($relids, $destination = null) {
	if ( is_null($destination) ) $destination = 1;
	if ( (int)adesk_http_param('destination') == 3 ) {// the only custom condition: global exclusion list
		return array(
			array(
				'id' => 'DNI',
				'name' => _a("Do not synchronize this field"),
				'type' => '',
				'req' => false,
			),
			array(
				'id' => 'email',
				'name' => _a("Email Pattern"),
				'type' => '',
				'req' => true,
			),
		);
	}
	if ( !is_array($relids) ) $relids = array_map('intval', explode(',', $relids));
	$nameRequired = false;
	$lists = list_select_array(null, $relids, '');
	foreach ( $lists as $l ) {
		if ( $l['require_name'] ) {
			$nameRequired = true;
			break;
		}
	}
	return array(
		array(
			'id' => 'DNI',
			'name' => _a("Do not synchronize this field"),
			'type' => '',
			'req' => false,
		),
		array(
			'id' => 'email',
			'name' => _a("Email Address"),
			'type' => 'email',
			'req' => true,
		),
		array(
			'id' => 'first_name',
			'name' => _a("First Name"),
			'type' => '',
			'req' => false/*$nameRequired*/,
		),
		array(
			'id' => 'last_name',
			'name' => _a("Last Name"),
			'type' => '',
			'req' => false/*$nameRequired*/,
		),
		array(
			'id' => 'cdate',
			'name' => _a("Create Date"),
			'type' => 'datetime',
			'req' => false,
		),
		array(
			'id' => 'ip',
			'name' => _a("IP Address"),
			'type' => 'ip',
			'req' => false,
		),
		array(
			'id' => 'ua',
			'name' => _a("Computer Info"),
			'type' => '',
			'req' => false,
		),
	);
}

function ihook_adesk_sync_custom_fields($relids, $destination = null) {
	if ( is_null($destination) ) $destination = 1;
	// the only custom condition: global exclusion list
	if ( (int)adesk_http_param('destination') == 3 ) return array();
	if ( !is_array($relids) ) $relids = array_map('intval', explode(',', $relids));
	if ( $relids == array() ) return array();
	$relids[] = 0;
	$relids = implode("','", $relids);
	return adesk_custom_fields_select_nodata_rel('#list_field', '#list_field_rel', "r.relid IN ('$relids')");
}

// returns a list of options they can choose for sync to perform on every item
function ihook_adesk_sync_options() {
	$admin = adesk_admin_get();

	/*if ( $relid == 0 ) */
	$relid = null;
	$optinRequired = 0;
	$optoutRequired = 0;
	$sendLastMsg = 0;
	$hasSent = (int)adesk_sql_select_one('=COUNT(*)', '#campaign', "status != 0 AND cdate < NOW()");
	/*
	$lists = list_select_array(null, $relid, 'optinout');
	foreach ( $lists as $k => $v ) {
		if ( $v['optin_confirm'] ) $optinRequired = 1;
		if ( $v['optout_confirm'] ) $optoutRequired = 1;
		if ( $v['send_last_broadcast'] ) $sendLastMsg = 1;
	}
	*/

	return array(
		array(
			'id' => 'optin',
			'name' => _a("Send Opt-In Confirmation Emails"),
			'descript' => _a("By checking this box, users will be required to confirm their subscription by clicking on a link that will be e-mailed to them. If you check this box, ensure that you have your confirmation mailings turned on and set to what you prefer. Also verify that opt-in / out settings are turned on and set to what you prefer."),
			'hidden' => (int)( !$admin['optinconfirm'] or !$optinRequired ), // if optins are needed or we have to force optin, don't hide it
			'checked' => 1,
			'disabled' => $admin['optinconfirm']
		),
		array(
			'id' => 'optout',
			'name' => _a("Send Opt-Out Confirmation Emails"),
			'descript' => _a("By checking this box, users will be required to confirm their unsubscription by clicking on a link that will be e-mailed to them. If you check this box, ensure that you have your confirmation mailings turned on and set to what you prefer. Also verify that opt-in / out settings are turned on and set to what you prefer."),
			'hidden' => 1/*(int)!$optoutRequired*/,
			//'checked' => 1
		),
		array(
			'id' => 'skipbounced',
			'name' => _a("Do not import previously bounced addresses"),
			'descript' => _a("By checking this box, all e-mail addresses that have previously been unsubscribed due to numerous bounces in the past will not be imported."),
			'checked' => 1
		),
		array(
			'id' => 'updateexisting',
			'name' => _a("Update existing subscribers"),
			'descript' => _a("By checking this box, any subscribers in this import process which are already present in the system will be updated with any new subscriber information that is found during the import process. If this box is not checked, those subscribers will be skipped during the import (their subscriber details will not be updated)."),
			'checked' => 1
		),
/*		array(
			'id' => 'instantresponder',
			'name' => _a("Send instant autoresponders when importing"),
			'descript' => '',
		),
		array(
			'id' => 'noresponders',
			'name' => _a("Do not send any future autoresponders"),
			'descript' => '',
		),
*/		array(
			'id' => 'lastmessage',
			'name' => _a("Send the last broadcast campaign when importing"),
			'descript' => _a("When this option is checked the last campaign you sent will be sent to each subscriber as they are imported."),
			'checked' => (int)$sendLastMsg,
			'hidden' => !$hasSent
		),
	);
}

/*
	result codes:
		'succeeded' => 0,
		'failed' => 2,
		'bounced' => 4,
		'duplicated' => 8,
		'unsubscribed' => 16,
		'excluded' => 32,
		('blocked' => 64,)
*/
function ihook_adesk_sync_row($cfg, $row, $test = false) {
	//dbg($cfg,1);
	if ( isset($GLOBALS['_hosted_account']) ) {
		$cfg['skipbounced'] = 1;
		$cfg['skipunsub'] = 1;
	}
	if (isset($cfg["skipbounced"]) && $cfg["skipbounced"]) {
		$cfg["import_option_skipbounced"] = 1;
		$cfg["sync_option_skipbounced"] = 1;
	}

	$cfg["import_option_skipunsub"] = 1;
	$cfg["sync_option_skipunsub"] = 1;

	if (isset($cfg["updateexisting"]) && $cfg["updateexisting"]) {
		$cfg["import_option_updateexisting"] = 1;
		$cfg["sync_option_updateexisting"] = 1;
		$cfg["update"] = 1;
	}
	if (isset($cfg["lastmessage"]) && $cfg["lastmessage"]) {
		$cfg["import_option_lastmessage"] = 1;
		$cfg["sync_option_lastmessage"] = 1;
	}
	if (isset($cfg["optin"]) && $cfg["optin"]) {
		$cfg["import_option_optin"] = 1;
		$cfg["sync_option_optin"] = 1;
	}
	if ( !isset($cfg['sendresponder']) ) $cfg['sendresponder'] = 0;

	$r = array(
		'succeeded' => false,
		'message' => '',
		'code' => 0,
		'id' => 0
	);

 	adesk_sync_log_store("Checking for {$GLOBALS['admin']['username']}'s subscriber limits...");
 	if ( !subscriber_add_valid() ) {
 		$r['message'] = _a('Subscriber Limit Exceeded.');
 		$r['code'] = 2;
 		return $r;
 	}

 	//adesk_sync_log_store("[debug] {$GLOBALS['admin']['username']}: {$GLOBALS['admin_subscribers_count']} / {$GLOBALS['admin']['limit_subscriber']}[/debug]");

 	adesk_sync_log_store("Starting new row...");

	// find sync values
	$email = ( isset($cfg['fieldslist']['email']) && isset($row[$cfg['fieldslist']['email']]) ? trim($row[$cfg['fieldslist']['email']]) : '' );
	$first_name = ( isset($cfg['fieldslist']['first_name']) && isset($row[$cfg['fieldslist']['first_name']]) ? $row[$cfg['fieldslist']['first_name']] : '' );
	$last_name = ( isset($cfg['fieldslist']['last_name']) && isset($row[$cfg['fieldslist']['last_name']]) ? $row[$cfg['fieldslist']['last_name']] : '' );
	$cdate = ( isset($cfg['fieldslist']['cdate']) && isset($row[$cfg['fieldslist']['cdate']]) ? $row[$cfg['fieldslist']['cdate']] : adesk_getCurrentDateTime() );
	$ip = ( isset($cfg['fieldslist']['ip']) && isset($row[$cfg['fieldslist']['ip']]) ? trim($row[$cfg['fieldslist']['ip']]) : '127.0.0.1' );
	$ua = ( isset($cfg['fieldslist']['ua']) && isset($row[$cfg['fieldslist']['ua']]) ? $row[$cfg['fieldslist']['ua']] : null );
	if ( !adesk_str_is_ip($ip) ) $ip = '127.0.0.1';

	if (isset($cfg["sourcecharset"])) {
		$charset = $cfg["sourcecharset"];
		if ($charset != "" && strtoupper($charset) != "UTF-8") {
			$first_name = adesk_utf_conv($charset, "UTF-8", $first_name);
			$last_name  = adesk_utf_conv($charset, "UTF-8", $last_name);
		}
	}

	adesk_sync_log_store("Found email '$email'.");
	// find custom fields sync|import values
	$customfields = array();
	foreach ( $cfg['fieldslist'] as $k => $v ) {
		if ( !isset($cfg['fieldslist'][$k]) ) {
			continue;
		}
		if ( !isset($row[$cfg['fieldslist'][$k]]) ) {
			$row[$cfg['fieldslist'][$k]] = '';
		}
		if ( substr($k, 0, 2) == '_f' ) {
			$key = (int)substr($k, 2);
			$customfields[$key] = $row[$cfg['fieldslist'][$k]];
		} elseif ( substr($k, 0, 6) == 'field_' ) {
			$key = (int)substr($k, 6);
			$customfields[$key] = $row[$cfg['fieldslist'][$k]];
		} elseif ( (int)$k ) {
			$key = (int)$k;
			$customfields[$key] = $row[$cfg['fieldslist'][$k]];
		}
	}

	// find relids (relation lists)
	if ( isset($GLOBALS['_adesk_sync_lists']) ) {
		$lists = $GLOBALS['_adesk_sync_lists'];
	} else {
		adesk_sync_log_store("Fetching lists to import into...");
		$so = new adesk_Select;
		$so->slist = array('l.id', 'l.name', 'l.require_name', 'l.p_duplicate_subscribe', 'l.optinoptout');
		$GLOBALS['_adesk_sync_lists'] =
		$lists = list_select_array($so, $cfg['relid'], 'optinout', true);
		adesk_sync_log_store("Destination lists fetched.");

	}

	// find custom field default values and use those, too.
	$listids = array();
	foreach ($lists as $l) {
		$listids[] = $l["id"];
	}

	// now get bounce limits
	if ( !isset($GLOBALS['_import_bounces']) ) {
		$listslist = implode("', '", $listids);
		$GLOBALS['_import_bounces'] = array(
			'hard' => (int)adesk_sql_select_one("
				SELECT
					MIN(b.limit_hard)
				FROM
					#bounce b,
					#bounce_list l
				WHERE
					b.id = l.bounceid
				AND
					l.listid IN ('$listslist')
				AND
					b.limit_hard > 0
			"),
			'soft' => (int)adesk_sql_select_one("
				SELECT
					MIN(b.limit_soft)
				FROM
					#bounce b,
					#bounce_list l
				WHERE
					b.id = l.bounceid
				AND
					l.listid IN ('$listslist')
				AND
					b.limit_soft > 0
			"),
		);
		adesk_sync_log_store("Bounce limits fetched.");
	}

	$defaults = list_field_getdefaults($listids);

	foreach ($defaults as $dk => $dv) {
		if (!isset($customfields[$dk]))
			$customfields[$dk] = $dv;
	}
	adesk_sync_log_store(var_export($customfields, true));

	foreach ($customfields as $fieldid => $new_value) {
		$field_type = adesk_sql_select_one("SELECT `type` FROM #list_field WHERE id = '$fieldid'");
		// if it's the Date Field type, make it SQL-format
		// make sure there is at least one integer in the string, otherwise it can't be a date
		if ( $field_type == 9 && $new_value != "" && preg_match("/[0-9]+/", $new_value) ) {
			$date_value = date("Y-m-d", strtotime($new_value));
			$customfields[$fieldid] = $date_value;
			adesk_sync_log_store("Custom Date Field found: converted to SQL-format: " . $date_value);
		}
	}

	// check row -- this has the added benefit of confirming that the address being added to the
	// exclusion list is an exact match (which the default of wildcard=0 would therefore
	// represent).
	//return array( 'message' => $email, 'code' => 2 );
	if ( $cfg['destination'] != 3 ) {
		// only run "is valid email" check if they are NOT importing as excluded,
		// otherwise they should be allowed to have patterns for excluded emails
		if ( !subscriber_import_is_email($email) ) {
			$r['message'] = _a('Empty row - No Email Address.');
			$r['code'] = 2;
			return $r;
		}
	}
	adesk_sync_log_store("Row is confirmed to have an email address.");
	// extract list settings
	$nameRequired = false;
	$allowDuplicates = false;
	foreach ( $lists as $l ) {
		if ( $l['require_name'] ) $nameRequired = true;
		if ( $l['p_duplicate_subscribe'] && isset($cfg["isimported"]) ) $allowDuplicates = true;
	}
	if ( $cfg['destination'] != 3 ) {
		// check if name is required
		if ( $nameRequired and $first_name == '' and $last_name == '' ) {
			$r['message'] = _a('Empty row - No Name (it is required).');
			$r['code'] = 2;
			return $r;
		}
		adesk_sync_log_store("Empty name check completed.");
		// check if it is on exclusion list
		if ( exclusion_match($email, $cfg['relid']) ) {
			$r['message'] = _a('Row skipped - It is on exclusion list.');
			$r['code'] = 32;
			return $r;
		}
		adesk_sync_log_store("Exclusion list check completed.");
	}
	$table = ( $cfg['destination'] == 3 ? '#exclusion' : '#subscriber' );
	$field = ( $cfg['destination'] == 3 ? 'exclusionid' : 'subscriberid' );
	$datefield = ( $cfg['destination'] == 2 ? 'udate' : 'sdate' );
	// check for existence
	$emailEsc = adesk_sql_escape($email);
	if ( $cfg['destination'] != 3 ) {
		if ( isset($cfg['import_option_skipbounced']) or isset($cfg['sync_option_skipbounced']) ) {
			if ( $GLOBALS['_import_bounces']['hard'] and adesk_sql_select_one('=COUNT(*)', '#bounce_data', "email = '$emailEsc' AND type = 'hard'") > 0 ) {
				$r['message'] = _a('This subscriber has been removed in the past due to numerous hard bounces.');
				$r['code'] = 4;
				return $r;
			}
			if ( $GLOBALS['_import_bounces']['soft'] and adesk_sql_select_one('=COUNT(*)', '#bounce_data', "email = '$emailEsc' AND type = 'soft'") > 0 ) {
				$r['message'] = _a('This subscriber has been removed in the past due to numerous soft bounces.');
				$r['code'] = 4;
				return $r;
			}
		}
		adesk_sync_log_store("Row is confirmed not to have bounced in the past.");

		// if skipping unsubscribed ones
		if ( isset($GLOBALS['_hosted_account']) ) {
			$foundinhosted = (int)adesk_sql_select_one("=COUNT(*)", "#unsublog", "email = '$emailEsc'");
			if ( $foundinhosted ) {
				$r['message'] = _a('This subscriber has unsubscribed in the past, therefore is skipped.');
				$r['code'] = 16;
				return $r;
			}
		}
	}
	// stuff to deal with
	//$optins = array();
	$responders = array();
	$campaigns = array();
	// find id
	$sql = adesk_sql_query("
		SELECT
			t.*
		FROM
			$table t
		WHERE
			t.email = '$emailEsc'
		LIMIT 1
	");
	$subscriberFound = ( mysql_num_rows($sql) == 1 );

	if ( $subscriberFound && !$allowDuplicates ) {
		$values = mysql_fetch_assoc($sql);
		adesk_sync_log_store("Existing subscriber found! (#$values[id])");

		# If bounced_date is set in $values, but empty, that means it's technically NULL in the
		# table.  Unfortunately, it can't BE blank when we later update the row, because as a
		# date type a blank string is unacceptable.  Better unset it now.
		#
		# We're doing in_array checks because it's possible for the key to be
		# in $values but for the value to be literally null, in which case an
		# isset() call would return false despite the key's existence.
		if ( in_array("bounced_date", array_keys($values)) and !$values["bounced_date"] )
			unset($values["bounced_date"]);

		if ( in_array("socialdata_lastcheck", array_keys($values)) and !$values["socialdata_lastcheck"] )
			unset($values["socialdata_lastcheck"]);

		$id = $values['id'];
//		$values = array();
		// standard fields
		$values['email'] = $email;
		if ( $cfg['destination'] != 3 ) {
			// IP
			// user agent
			if ( isset($cfg['fieldslist']['ua']) ) {
				$values['ua']= $ua;
			}
			// subscriber hash
			$values['=hash'] = "MD5(CONCAT(id, email))";
		}

		if( isset($cfg["import_option_updateexisting"]) || isset($cfg["sync_option_updateexisting"]) ) {
			// do update
			$r['succeeded'] = ( $test ? true : adesk_sql_update($table, $values, "id = '$id'") );
			if ( !$r['succeeded'] ) {
				$r['message'] = sprintf(_a('Error %d: %s'), adesk_sql_error_number(), adesk_sql_error());
				$r['code'] = 2;
				return $r;
			}
			$r['succeeded'] = false;
			adesk_sync_log_store("Existing subscriber (general) info saved.");
		}
		else
		{
			adesk_sync_log_store("Existing subscriber general info NOT saved.");
		}

		// now save custom fields, if the checkbox was checked
		$cf = array();
		if ( isset($cfg['update']) ) {
			adesk_sync_log_store("now saving custom fields, if the checkbox was checked: " . $cfg['update']);
			adesk_sync_log_store("Fetching old subscriber's custom field values...");
			$existingfields = subscriber_select_field_dataids($values["id"]);
			$existingfieldvalues = subscriber_get_fields($values["id"], $listids);
			//adesk_sync_log_store(print_r($existingfields,1));
			foreach ( $customfields as $k => $v ) {
				//adesk_sync_log_store($k . " Existing: " . $existingfieldvalues[$k]['val']);
				//adesk_sync_log_store($k . " Default: " . $defaults[$k]);

				$cf["$k," . ( isset($existingfields[$k]) ? $existingfields[$k] : 0 )] = $v;
			}
			adesk_sync_log_store("Old custom field values fetched.");
		}

		$counter = 0; //to keep track of whether or not subscribers are added to any new lists
		// now do relations
		foreach ( $lists as $l ) {
			adesk_sync_log_store("Processing subscriber for list '$l[name]'...");
			// find rel
			$sql = adesk_sql_query("
				SELECT
					t.*
				FROM
					{$table}_list t
				WHERE
					t.$field = '$id'
				AND
					t.listid = '$l[id]'
				LIMIT 1
			");
			$found = ( mysql_num_rows($sql) == 1 );
			if ( $found ) {

				adesk_sync_log_store("Subscriber found in list. Updating his list info...");

				$relvalues = mysql_fetch_assoc($sql);
				$relid = $relvalues['id'];
				$relvalues[$field] = $id;
				$relvalues['listid'] = $l['id'];
				// sync id
				$relvalues['sync'] = $cfg['process_id'];
				// if skipping unsubscribed ones
				if ( isset($cfg['import_option_skipunsub']) or isset($cfg['sync_option_skipunsub']) ) {
					if ( $cfg['destination'] != 3 and $relvalues['status'] == 2 ) {
						adesk_sync_log_store("Subscriber skipped - it was unsubscribed in the past!");
						if ( count($lists) == 1 ) {
							$r['message'] = _a('This subscriber has unsubscribed in the past, therefore is skipped.');
							$r['code'] = 16;
							return $r;
						} else {
							continue;
						}
					}
				}
				if ( isset($cfg['import_option_skipbounced']) or isset($cfg['sync_option_skipbounced']) ) {
					if ( $cfg['destination'] != 3 and $relvalues['status'] == 3 ) {
						adesk_sync_log_store("Subscriber skipped - it bounced in the past!");
						if ( count($lists) == 1 ) {
							$r['message'] = _a('This subscriber has bounced in the past, therefore is skipped.');
							$r['code'] = 16;
							return $r;
						} else {
							continue;
						}
					}
				}
				if ( $cfg['destination'] != 3 ) {
					// name - only update if it's been mapped - otherwise leave the name alone
					if ($first_name) $relvalues['first_name'] = $first_name;
					if ($last_name) $relvalues['last_name'] = $last_name;
					unset($relvalues['udate']);
					if ( $cfg['destination'] == 2 ) {
						$relvalues['=udate'] = 'NOW()';
					}
					unset($relvalues[$datefield]);
					// if initial status is greater than current status, push them to that one. otherwise leave their status alone
					// "if he's unsubscribed, neither active nor unconfirmed should happen, if he's confirmed, unconfirmed shouldn't happen"
					$relvalues['status'] = ( (int)$cfg['destination'] > $relvalues['status'] ) ? (int)$cfg['destination'] : $relvalues['status'];
					//$relvalues['responder'] = 1;
				}

				if ( isset($cfg['fieldslist']['ip']) ) $relvalues['=ip4'] = "INET_ATON('$ip')"; // do not update this info unless provided

				# These must also be unset if they are present but blank.
				if ( isset($relvalues['sdate']) ) {
					if ($relvalues["sdate"] == "")
						unset($relvalues["sdate"]);
				}
				if ( isset($relvalues['udate']) ) {
					if ($relvalues["udate"] == "")
					{
						unset($relvalues["udate"]);
					}
				}

			  if( isset($cfg["import_option_updateexisting"]) || isset($cfg["sync_option_updateexisting"]) ) {
				// do update
				$done = ( $test ? true : adesk_sql_update($table . '_list', $relvalues, "id = '$relid'") );
				adesk_sync_log_store("Subscriber updated in list.");
			  }
			  else
			  {
				// do update of sync id only
				$done = ( $test ? true : adesk_sql_update_one($table . '_list', 'sync', $cfg['process_id'], "id = '$relid'") );
				  adesk_sync_log_store("Subscriber NOT updated in list.");
			  }
			if ( !$done ) {
				$r['message'] = sprintf(_a('Error %d: %s'), adesk_sql_error_number(), adesk_sql_error());
				$r['code'] = 2;
				return $r;
			}
			} else {
				# Hack this on so any new custom fields get saved.
				$cfg["import_option_updateexisting"] = 1;

				$relid = 0;
				$relvalues = array();
				$relvalues['id'] = 0;
				$relvalues[$field] = $id;
				$relvalues['listid'] = $l['id'];
				// sync id
				$relvalues['sync'] = $cfg['process_id'];
				//$relvalues['status'] = (int)$cfg['destination'];
				if ( $cfg['destination'] != 3 ) {
					$relvalues['status'] = (int)$cfg['destination'];
					// name
					$relvalues['first_name'] = $first_name;
					$relvalues['last_name'] = $last_name;
					$relvalues['formid'] = 0;
					if ( (string)$cdate == '0000-00-00 00:00:00' ) {
						$relvalues['=' . $datefield] = 'NULL';
					} elseif ( preg_match('/^\d{10}$/', (string)$cdate) ) {
						$relvalues['='.$datefield] = "FROM_UNIXTIME('$cdate')";
					} elseif ( $realdate = strtotime((string)$cdate) ) {
						$cdate = date('Y-m-d H:i:s', $realdate);
						$relvalues[$datefield] = $cdate;
					} else {
						$relvalues['='.$datefield] = 'NOW()';
					}
					$relvalues['responder'] = (int)$cfg['sendresponder'];
					$relvalues['sourceid'] = isset($cfg['isimported']) ? 1 : 2;
				}
				$done = ( $test ? true : adesk_sql_insert($table . '_list', $relvalues) );
				if ( !$done ) {
					$r['message'] = sprintf(_a('Error %d: %s'), adesk_sql_error_number(), adesk_sql_error());
					$r['code'] = 2;
					return $r;
				}
				adesk_sync_log_store("Subscriber added to the list.");
				$counter++; //this will be incremented if a pre-existing subscriber is added to a new list successfully

				// collect new ID
				if ( !$test ) {
					$relid = $relvalues['id'] = adesk_sql_insert_id();

					// if going into unconfirmed
					if ( $cfg['destination'] != 3 and $relvalues['status'] == 0 ) {
						// only available for downloaded users - otherwise hosted users can't have status = 0 (unconfirmed)
						if ( !isset($GLOBALS['_hosted_account']) ) {
							// if optins should be sent
							//if ( isset($cfg['import_option_optin']) or isset($cfg['sync_option_optin']) ) {
								// if this list needs optins
								if ( $l['optin_confirm'] ) {
									mail_opt_send(subscriber_select_row($id), $l, $l['id'], 0, $l, 'in');
									adesk_sync_log_store("Opt-in email sent out!");
								}
							//}
						}
					} elseif ( $cfg['destination'] != 3 and $relvalues['status'] === 1 ) { // if going into confirmed
						// if we are sending responders at all
						if ( $cfg['sendresponder'] ) {
							// if we should mark any responders as sent
							if ( isset($cfg['sentresponders']) ) {
								if ( !is_array($cfg['sentresponders']) ) {
									$cfg['sentresponders'] = explode(',' , $cfg['sentresponders']);
								}
								foreach ( $cfg['sentresponders'] as $c ) {
									if ( $c = (int)$c ) {
										$insert = array(
											'id' => 0,
											'subscriberid' => $id,
											'listid' => (int)$l['id'],
											'campaignid' => (int)$c,
											'messageid' => 0, // he didn't really get it
											'=sdate' => 'NOW()',
										);
										adesk_sql_insert('#subscriber_responder', $insert);
									}
								}
								adesk_sync_log_store("Sent autoresponders entered.");
							}
							// if we should send any responders
							if ( isset($cfg['instantresponder']) ) {
								$responders[] = $l['id'];
							}
						}
						// if we should send any campaigns
						if ( isset($cfg['import_option_lastmessage']) or isset($cfg['sync_option_lastmessage']) ) {
							$campaigns[] = $l['id'];
						}
					}

				}
			}
		}
		adesk_sync_log_store("Subscriber processed for this list.");


		if ( !$test ) {

			if( isset($cfg["import_option_updateexisting"]) || isset($cfg["sync_option_updateexisting"]) ) {

				adesk_sync_log_store("Saving subscriber's custom field values... (IF portion)");
				adesk_sync_log_store( "IF portion: " . print_r($cf,1) );
				adesk_custom_fields_update_data($cf, '#list_field_value', 'fieldid', array('relid' => $id));
				adesk_sync_log_store("Custom field values saved.");
				// send responders
				$type = ( (int)$cfg['destination'] == 2 ? 'unsubscribe' : 'subscribe' );
				if ( count($responders) > 0 ) {
					mail_responder_send(subscriber_select_row($id), $responders, $type);
					adesk_sync_log_store("Instant autoresponders sent out!");
				}
				// if we should send the last broadcast message
				if ( isset($cfg['import_option_lastmessage']) or isset($cfg['import_option_lastmessage']) ) {
					mail_campaign_send_last(subscriber_select_row($id), $campaigns);
					adesk_sync_log_store("Last Broadcasts sent out!");
				}
			}
		}


		if($counter==0)
		{
			if( !isset($cfg["import_option_updateexisting"]) && !isset($cfg["sync_option_updateexisting"]) ) {
				// We only get here if the subscriber:
				//   a. Already exists in the system
				//   b. Was not added to any new lists
				//   c. Was not updated due to that option being turned off
				adesk_sync_log_store("Existing subscriber NOT updated and NOT added to any new lists.");
				$r['message'] = _a('Existing subscriber details NOT updated.');
				$r['code'] = 25;
				return $r;
			}
		} else {
			subscriber_add_increment();
		}
	} else {
		adesk_sync_log_store("Subscriber not found in the system! Adding a fresh one...");
		adesk_sync_log_store("Subscriber ready to be added to this list.");
		$id = 0;
		$values = array();
		$values['id'] = 0;
		$values['email'] = $email;
		if ( $cfg['destination'] != 3 ) {
			// date
			if ( preg_match('/\d{4}-\d{2}-\d{2}/', (string)$cdate) ) {
				$values['cdate'] = date('Y-m-d H:i:s', strtotime($cdate));
			} elseif ( preg_match('/^\d{10}$/', (string)$cdate) ) {
				$values['=cdate'] = "FROM_UNIXTIME('$cdate')";
			} elseif ( $realdate = strtotime((string)$cdate) ) {
				$cdate = date('Y-m-d H:i:s', $realdate);
				$values['cdate'] = $cdate;
			} else {
				$values['=cdate'] = 'NOW()';
				//$values['cdate'] = $cdate;
			}
			// IP
			#$values['=ip'] = "INET_ATON('$ip')";
			// user agent
			if ( $ua ) {
				$values['ua']= $ua;
			} else {
				$values['=ua'] = "NULL";
			}
			// subscriber hash
			//$values['=hash'] = "MD5(CONCAT(id, email))";
		}
		$r['succeeded'] = ( $test ? true : adesk_sql_insert($table, $values) );
		if ( !$r['succeeded'] ) {
			$r['message'] = sprintf(_a('Error %d: %s'), adesk_sql_error_number(), adesk_sql_error());
			$r['code'] = 2;
			return $r;
		}

		$r['succeeded'] = false;
		// collect new ID
		if ( !$test ) $id = $values['id'] = adesk_sql_insert_id();
		adesk_sync_log_store("Subscriber saved under ID #$id.");

		// update same record with hash, now that we have the ID
		adesk_sql_update_one('#subscriber', '=hash', 'MD5(CONCAT(id, email))', "`id` = '$id'");

		// now save custom fields
		$cf = array();
		foreach ( $customfields as $k => $v ) {

			if(!isset($v) || $v=="")
			{
				if(isset($defaults[$k])) $v = $defaults[$k];
			}

			$cf["$k,0"] = $v;
		}
		// now do relations
		foreach ( $lists as $l ) {
			adesk_sync_log_store("Adding subscriber to list '$l[name]'...");
			$relid = 0;
			$relvalues = array();
			$relvalues['id'] = 0;
			$relvalues[$field] = $id;
			$relvalues['listid'] = $l['id'];
			// sync id
			$relvalues['sync'] = $cfg['process_id'];
			//$relvalues['status'] = (int)$cfg['destination'];
			if ( $cfg['destination'] != 3 ) {
				$relvalues['status'] = (int)$cfg['destination'];
				// name
				$relvalues['first_name'] = $first_name;
				$relvalues['last_name'] = $last_name;
				$relvalues['=ip4'] = "INET_ATON('$ip')";
				$relvalues['formid'] = 0;
				if ( (string)$cdate == '0000-00-00 00:00:00' ) {
					$relvalues['=' . $datefield] = 'NULL';
				} elseif ( preg_match('/^\d{10}$/', (string)$cdate) ) {
					$relvalues['='.$datefield] = "FROM_UNIXTIME('$cdate')";
				} elseif ( $realdate = strtotime((string)$cdate) ) {
					$cdate = date('Y-m-d H:i:s', $realdate);
					$relvalues[$datefield] = $cdate;
				} else {
					$relvalues['='.$datefield] = 'NOW()';
				}
				$relvalues['responder'] = (int)( isset($cfg['sendresponders']) ? $cfg['sendresponders'] : $cfg['sendresponder'] );
				$relvalues['sourceid'] = isset($cfg['isimported']) ? 1 : 2;
			}
			$done = ( $test ? true : adesk_sql_insert($table . '_list', $relvalues) );
			if ( !$done ) {
				$r['message'] = sprintf(_a('Error %d: %s'), adesk_sql_error_number(), adesk_sql_error());
				$r['code'] = 2;
				return $r;
			}
			adesk_sync_log_store("Subscriber added to list.");
			// collect new ID
			if ( !$test ) {
				$relid = $relvalues['id'] = adesk_sql_insert_id();
				// if going into unconfirmed
				if ( $cfg['destination'] != 3 and $relvalues['status'] == 0 ) {
					// only available for downloaded users - otherwise hosted users can't have status = 0 (unconfirmed)
					if ( !isset($GLOBALS['_hosted_account']) ) {
						// if optins should be sent
						//if ( isset($cfg['import_option_optin']) or isset($cfg['sync_option_optin']) ) {
							// if this list needs optins
							if ( $l['optin_confirm'] ) {
								mail_opt_send(subscriber_select_row($id), $l, $l['id'], 0, null, 'in');
								adesk_sync_log_store("Opt-in email sent out!");
							}
						//}
					}
				} elseif ( $cfg['destination'] != 3 and $relvalues['status'] === 1 ) { // if going into confirmed
					// if we are sending responders at all
					if ( $cfg['sendresponder'] ) {
						if ( !isset($cfg['sentresponders']) ) $cfg['sentresponders'] = array();
						if ( !isset($cfg['sentresponders']) ) $cfg['sentresponders'] = array();
						if ( !is_array($cfg['sentresponders']) ) {
							$cfg['sentresponders'] = explode(',' , $cfg['sentresponders']);
						}
						// if we should mark any responders as sent
						if ( isset($cfg['sentresponders']) ) {
							foreach ( $cfg['sentresponders'] as $c ) {
								if ( $c = (int)$c ) {
									$insert = array(
										'id' => 0,
										'subscriberid' => $id,
										'listid' => (int)$l['id'],
										'campaignid' => (int)$c,
										'messageid' => 0, // he didn't really get it
										'=sdate' => 'NOW()',
									);
									adesk_sql_insert('#subscriber_responder', $insert);
								}
							}
							adesk_sync_log_store("Sent responders entered.");
						}
						// if we should send any responders
						if ( isset($cfg['instantresponder']) ) {
							$responders[] = $l['id'];
						}
					}
					// if we should send any campaigns
					if ( isset($cfg['import_option_lastmessage']) or isset($cfg['sync_option_lastmessage']) ) {
						$campaigns[] = $l['id'];
					}
				}
			}
		}


		if ( !$test ) {
			adesk_sync_log_store("Saving subscriber's custom field values... (ELSE portion)");
			adesk_sync_log_store("ELSE portion " . print_r($cf,1));
			adesk_custom_fields_update_data($cf, '#list_field_value', 'fieldid', array('relid' => $id));
			adesk_sync_log_store("Custom field values saved.");
			// send responders
			$type = ( (int)$cfg['destination'] == 2 ? 'unsubscribe' : 'subscribe' );
			if ( count($responders) > 0 ) {
				mail_responder_send(subscriber_select_row($id), $responders, $type);
				adesk_sync_log_store("Instant autoresponders sent out!");
			}
			// if we should send the last broadcast message
			if ( isset($cfg['import_option_lastmessage']) or isset($cfg['import_option_lastmessage']) ) {
				mail_campaign_send_last(subscriber_select_row($id), $campaigns);
				adesk_sync_log_store("Last Broadcasts sent out!");
			}
		}

		subscriber_add_increment();
	}
	// now do extra stuff here only if not test
	adesk_sync_log_store("\n\nRow Processed!\n");
	if ( $test ) {
		$r['message'] = _a("OK to Import");
	} else {
		$r['message'] = sprintf(_a("Imported under ID: %d"), $id);

		if ( isset($GLOBALS['_hosted_account']) ) {
			require(dirname(dirname(__FILE__)) . '/manage/subscriber.add.inc.php');
		}

	}
	$r['succeeded'] = true;

	return $r;
}


function ihook_adesk_sync_delete_all($cfg) {
	//return null; // like it doesn't exists
	if ( $cfg['destination'] < 3 ) {
		$sql = adesk_sql_query("SELECT subscriberid FROM #subscriber_list WHERE sync != '$cfg[process_id]' AND listid = '$cfg[relid]' AND status = '$cfg[destination]'");
		$cnt = adesk_sql_num_rows($sql);
		adesk_sync_log_comment(sprintf(_a('Deleting %s subscribers not affected by this process...'), $cnt));
		$delids = array();
		while ( $row = mysql_fetch_assoc($sql) ) {
			subscriber_delete($row['subscriberid'], array($cfg['relid']), false);
			/*if ( $cnt < 10000 ) */$delids[] = $row['subscriberid'];
		}
		/*if ( $cnt < 10000 ) */adesk_sync_log_comment(sprintf(_a('Deleted subscribers with IDs: %s.'), implode(', ', $delids)));
	}
}

function ihook_adesk_sync_row_report($row) {
/*
	result codes:
		'succeeded' => 0,
		'failed' => 2,
		'bounced' => 4,
		'duplicated' => 8,
		'unsubscribed' => 16,
		'excluded' => 32,
		('blocked' => 64,)

	$r = array(
		'failed' => array(),
		'bounced' => array(),
		'duplicated' => array(),
		'unsubscribed' => array(),
		'excluded' => array(),
		('blocked' => array(),)
	);
*/
	switch ( $row['code'] ) {
		case 4:
			return 'bounced';

		case 8:
			return 'duplicated';

		case 16:
			return 'unsubscribed';

		case 16:
			return 'excluded';

		//case 64:
		//	return 'blocked';

		case 32:
			return 'excluded';

		case 2:
		default:
			return 'failed';
	}
}

function ihook_adesk_sync_tplvars() {
/*	$relids = null;
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		if ( $admin['id'] != 1 ) {
			$relids = implode(',', $admin['lists']);
		}
	}
*/	require_once(dirname(__FILE__) . '/subscriber_import.php');
	$r = import_relid_change($relids = null, 'subscribe');
	return $r;
}

function ihook_adesk_sync_prepare_post($values) {
	// to subscribed, unsubscribed, unconfirmed, or exclusion
	$values['destination'] = (int)adesk_http_param('destination');
	$values['sendresponder'] = (int)adesk_http_param('sendresponder');
	$values['instantresponder'] = (int)adesk_http_param('instantresponder');
	$arr = (array)adesk_http_param('sentresponders');
	$values['sentresponders'] = implode(',', array_diff(array_map('intval', $arr), array(0)));

	$values['delete_all']  = (int)isset($_POST["sync_option_delete_all"]);
	$values['skipbounced'] = (int)isset($_POST["sync_option_skipbounced"]);
	$values['skipunsub']   = 1;
	$values['updateexisting']   = (int)isset($_POST["sync_option_updateexisting"]);
	$values['lastmessage'] = (int)isset($_POST["sync_option_lastmessage"]);
	$values['optin'] = (int)adesk_http_param("sync_option_optin");

	return $values;
}

function ihook_adesk_sync_after_delete($syncid) {
  $syncids = explode(",", $syncid);
  foreach ($syncids as $syncid) {
	  $cond = adesk_sync_process_cond($syncid);
	  adesk_sql_delete('#process', $cond);
  }
}

function ihook_adesk_user_assets_pre($smarty) {

	$smarty->assign("group_usersettings_header", true);
	$smarty->assign("user_usersettings_header", true);

	$smarty->assign("side_content_template", "side.settings.htm");
	$smarty->assign("group_file", "group.inc.htm");
	$smarty->assign("_group_can_add", permission("pg_group_add"));
	$smarty->assign("_group_can_edit", permission("pg_group_edit"));
	$smarty->assign("_group_can_delete", permission("pg_group_delete"));
	$smarty->assign("_user_can_add", permission("pg_user_add"));
	$smarty->assign("_user_can_edit", permission("pg_user_edit"));
	$smarty->assign("_user_can_delete", permission("pg_user_delete"));

	$lso = new adesk_Select;
	$sso = new adesk_Select;
	$admin = $GLOBALS["admin"];

	if ($admin["id"] > 1) {
	
	//sandeep
	
	$admin   = adesk_admin_get();
	        $uid = $admin['id'];
	if($uid != 1 ){
	$lists = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");


	//get the lists of users
	
	@$lstr = implode("','",$lists);
	}
	else
	{
	@$lstr = implode("','", $admin["lists"]);
	}

	
	
	
		$lstr = implode("','", $admin["lists"]);
		$mstr = implode("','", $admin["methods"]);
		$lso->push("AND id IN ('$lstr')");
		$sso->push("AND id IN ('$mstr')");
	}

	$lso->orderby('name ASC');
	$sso->orderby('type, host ASC');

	# The below code is just for the group assets.
	$smarty->assign("lists", adesk_sql_select_array($lso->query("SELECT id, name FROM #list WHERE [...]")));
	$smarty->assign("sendmethods", adesk_sql_select_array($sso->query("SELECT id, host, type FROM #mailer WHERE [...]")));
	return $smarty;
}

function ihook_adesk_group_permission($key) {
	require_once dirname(__FILE__) . "/permission.php";

	# Just to be safe...

	if ($key != "add" && $key != "edit" && $key != "delete")
		$key = "edit";

	return permission("pg_group_$key");
}

function ihook_adesk_user_permission($key) {
	require_once dirname(__FILE__) . "/permission.php";

	# Just to be safe...

	if ($key != "add" && $key != "edit" && $key != "delete")
		$key = "edit";

	return permission("pg_user_$key");
}

function ihook_adesk_user_account_settings() {
	return 'account.settings.htm';
}
function ihook_adesk_user_account_additional() {
	return 'account.additional.htm';
}
function ihook_adesk_user_account_update() {

	if ($_POST["pass"] != $_POST["pass_r"]) {
		return _a("Please confirm both password fields match");
	}

	$update = array(
		'default_dashboard'       =>  'classic',
		'default_mobdashboard'       =>  $_POST['default_mobdashboard'],
		'lists_per_page'       => (int)$_POST['lists_per_page'],
		'messages_per_page'    => (int)$_POST['messages_per_page'],
		'subscribers_per_page' => (int)$_POST['subscribers_per_page'],
		"editorsize_w"         => (int)$_POST["editorsize_w"],
		"editorsize_h"         => (int)$_POST["editorsize_h"],
		'autoupdate'           => (int)$_POST['autoupdate'],
		'autosave'             => (int)isset($_POST['autosave']) * 60,
	);
	if ( !$update['editorsize_w'] ) {
		$update['editorsize_w'] = '100%';
	} else {
		$update['editorsize_w'] .= ( substr($_POST['editorsize_w'], -1) == '%' ? '%' : 'px' );
	}
	if ( !$update['editorsize_h'] ) {
		$update['editorsize_h'] = '600px';
	} else {
		$update['editorsize_h'] .= ( substr($_POST['editorsize_h'], -1) == '%' ? '%' : 'px' );
	}
	return $update;
}



/*
	MAIL IHOOKS
*/

$GLOBALS['_adesk_mailer_attach'] = '';
$GLOBALS['_adesk_mailer_options'] = array();


function ihook_adesk_mail_send_message($message, $to, $from, $options) {
	$attach = '';//$GLOBALS['_adesk_mailer_attach'];
	// fetch global config
	$site = ( isset($GLOBALS['site']) ? $GLOBALS['site'] : ota_version() );
	// recreate options array if some values are missing
	$options = ota_fix_ihook_mail_options($options);
	$email = $to->getAddress();
	$children = $message->listChildren();
	// encoding
	if ( isset($options['msgData']['encoding']) and $options['msgData']['encoding'] != '' ) {
		$message->setEncoding($options['msgData']['encoding']);
		foreach ( $children as $c ) {
			$k =& $message->getChild($c);
			if ( strtolower(get_class($k)) == 'swift_message_part' ) {
				$k->setEncoding($options['msgData']['encoding']);
			}
		}
	}
	// charset
	if ( isset($options['msgData']['charset']) and $options['msgData']['charset'] != '' ) {
		$message->setCharset($options['msgData']['charset']);
		$message->headers->setCharset($options['msgData']['charset']);
		foreach ( $children as $c ) {
			$k =& $message->getChild($c);
			if ( strtolower(get_class($k)) == 'swift_message_part' ) {
				$k->setCharset($options['msgData']['charset']);
			}
		}
	}
	// set REPLY-TO field
	if ( isset($options['msgData']['reply2']) and $options['msgData']['reply2'] != '' and $email != $options['msgData']['reply2'] ) {
		$message->setReplyTo($options['msgData']['reply2']);
	}
	// add attachments
	if ( $attach != '' ) {
		// require DataBaseFile class
		require_once(dirname(__FILE__) . '/DatabaseAttachedFile.php');
		// grab all matches from attach string
		preg_match_all('/, (\d+) ,/', $attach, $matches);
		// for every match
		foreach ( $matches[1] as $att ) {
			$fileID = (int)$att;
			if ( $fileID != 0 ) {
				// require PEAR and DataBaseFile classes for this
				$file = new DatabaseAttachedFile();
				$loaded = $file->load($fileID);
				if ( $loaded ) {
					$message->attach(new Swift_Message_Attachment($file->getContent(), $file->getRName()));
				}
			}
		}
	}
	// Looking for attachments and inserting if needed
	//foreach ( $options['attach'] as $att ) {
		//$message->attach(new Swift_Message_Attachment(new Swift_File($att), adesk_file_basename($att)));
	//}
	// set x-mid header
	if ( isset($options['messageID']) and $options['messageID'] > 0 ) {
		$awebdesk_xmid = base64_encode($email . ' , m' . $options['messageID']);
		$message->headers->set('X-mid', $awebdesk_xmid);
	} elseif ( isset($options['respondID']) and $options['respondID'] > 0 ) {
		$awebdesk_xmid = base64_encode($email . ' , a' . $options['respondID']);
		$message->headers->set('X-mid', $awebdesk_xmid);
	} elseif ( isset($options['mtbl']['id']) ) {
		$awebdesk_xmid = base64_encode($email . ' , m' . $options['mtbl']['id']);
		$message->headers->set('X-mid', $awebdesk_xmid);
	}
	// set x-mailer header
	if ( trim(_i18n('AEM')) != '' ) {
		$message->headers->set('X-Mailer', _i18n('AEM'));
	}
	// set CUSTOM HEADERS
	if ( $options['listID'] and !isset($options['customHeaders']) ) {
		require_once(adesk_admin('functions/header.php'));
		$so = new adesk_Select();
		$so->push("AND l.listid = '$options[listID]'");
		$customHeaders = header_select_array($so);
		foreach ( $customHeaders as $header ) {
			$headerArr = explode(': ', $header['header']);
			if ( isset($headerArr[1]) ) {
				$headerKey = trim(array_shift($headerArr));
				if ( $headerKey != '' ) {
					$message->headers->set($headerKey, implode(': ', $headerArr));
				}
			}
		}
	} elseif ( isset($options['customHeaders']) ) {
		foreach ( $options['customHeaders'] as $header ) {
			$headerArr = explode(': ', $header['header']);
			if ( isset($headerArr[1]) ) {
				$headerKey = trim(array_shift($headerArr));
				if ( $headerKey != '' ) {
					$message->headers->set($headerKey, implode(': ', $headerArr));
				}
			}
		}
	}
	return $message;
}

function ihook_adesk_mail_send_mail($mail, $email, $from, $options) {
	$attach = '';//$GLOBALS['_adesk_mailer_attach'];
	// recreate options array if some values are missing
	$options = ota_fix_ihook_mail_options($options);
	// set PROPERTIES
	$mail->Encoding = ( ( isset($options['msgData']['encoding']) and $options['msgData']['encoding'] != '' ) ? $options['msgData']['encoding'] : _i18n("quoted-printable")  );
	$mail->CharSet  = ( ( isset($options['msgData']['charset'])  and $options['msgData']['charset']  != '' ) ? $options['msgData']['charset']  : _i18n("utf-8") );
	// set REPLY-TO field
	if ( isset($options['msgData']['reply2']) and $options['msgData']['reply2'] != '' ) {
		if ( $options['msgData']['reply2'] != $options['msgData']['mfrom'] ) {
			$mail->AddReplyTo($options['msgData']['reply2'], $options['msgData']['mfromn']);
		}
	}
	// Looking for attachments and inserting if needed
	if ( $attach != '' ) {
		// require DataBaseFile class
		require_once(dirname(__FILE__) . '/DatabaseAttachedFile.php');
		// grab all matches from attach string
		preg_match_all('/, (\d+) ,/', $attach, $matches);
		// for every match
		foreach ( $matches[1] as $att ) {
			$fileID = (int)$att;
			if ( $fileID != 0 ) {
				// require PEAR and DataBaseFile classes for this
				$file = new DatabaseAttachedFile();
				$loaded = $file->load($fileID);
				if ( $loaded ) {
					$mail->AddStringAttachment($file->getContent(), $file->getRName(), 'base64', $file->getType());
				}
			}
		}
	}
	// Looking for attachments and inserting if needed
	//foreach ( $options['attach'] as $att ) $mail->AddAttachment($att);
	$mail->WordWrap = 0;
	$mail->Timeout = 10;
	// set x-mid header
	if ( isset($options['messageID']) and $options['messageID'] > 0 ) {
		$awebdesk_xmid = base64_encode($email . ' , m' . $options['messageID']);
		$mail->AddCustomHeader("X-mid: $awebdesk_xmid");
	} elseif ( isset($options['respondID']) and $options['respondID'] > 0 ) {
		$awebdesk_xmid = base64_encode($email . ' , a' . $options['respondID']);
		$mail->AddCustomHeader("X-mid: $awebdesk_xmid");
	} elseif ( isset($options['mtbl']['id']) ) {
		$awebdesk_xmid = base64_encode($email . ' , m' . $options['mtbl']['id']);
		$mail->AddCustomHeader("X-mid: $awebdesk_xmid");
	}
	// set CUSTOM HEADERS
	if ( $options['listID'] and !isset($options['customHeaders']) ) {
		$so = new adesk_Select();
		$so->push("AND l.listid IN (" . (int)$options['listID'] . ", 0)");
		$customHeaders = adesk_sql_select_array(header_select_array($so));
		foreach ( $customHeaders as $header ) {
			$mail->AddCustomHeader($header['name'] . ': ' . $header['value']);
		}
	} elseif ( isset($options['customHeaders']) ) {
		foreach ( $options['customHeaders'] as $header ) {
			if ( is_array($header) ) {
				$mail->AddCustomHeader($header['name'] . ': ' . $header['value']);
			} else {
				$mail->AddCustomHeader($header);
			}
		}
	}
	return $mail;
}

function ihook_adesk_upload_js_addon($action, $result) {
	if ( $action == 'message_fetch' ) {
		if ( $result['succeeded'] ) {
			return 'window.parent.message_form_upload("' . $result['id'] . '");';
		}
	} elseif ( $action == 'template_import' || $action == 'design_upload' ) {
		return "
			window.parent.$('upload_check_" . $result['id'] . "').hide();
		";
	} elseif ( $action == 'template_preview' ) {
		$return = "";
		if ( isset($result['cache_filename']) ) {
			$return = "

				// this dom (remember, this is an iframe we are currently in)
				if ( !$('cache_filename') ) {
				  var cache_filename_input = document.createElement('input');
				  cache_filename_input.type = 'hidden';
				  cache_filename_input.name = 'cache_filename';
				  cache_filename_input.id = 'cache_filename';
				  cache_filename_input.value = '" . $result['cache_filename'] . "';
				  document.forms[0].appendChild(cache_filename_input);
				}
				else {
					$('cache_filename').value = '" . $result['cache_filename'] . "';
				}

			  // parent dom
			  // name of the file in the cache folder, where we store it temporarily
				window.parent.$('template_preview_cache_filename').value = '" . $result['cache_filename'] . "';
				window.parent.$('template_preview_cache_filename_mimetype').value = '" . $result['cache_filename_mimetype'] . "';
				window.parent.template_preview_display(0, '" . $result['cache_filename'] . "', '../cache/');
			";
		}
		return $return;
	} elseif ( $action == 'branding_upload' ) {
		if ( $result['succeeded'] ) {
			return '
				window.parent.$("logo_source").value = "url";
				window.parent.branding_toggle_source("url");
				window.parent.$("branding_image_div").className = "";
				window.parent.$("branding_image").src = "../images/manage/' . $result['id'] . '";
				window.parent.$("branding_url").value = window.parent.$("branding_image").src;
			';
		}
	}
	return '';
}



function ihook_calendar_day($sql_date) {
	$data = array();
	$data['events'] = array();
	$data['tasks'] = array();
	//$data['events'] = adesk_sql_select_array("SELECT * FROM #calendar_event WHERE (sdate LIKE '$sql_date%') OR (edate LIKE '$sql_date%') OR (sdate < '$sql_date' AND edate > '$sql_date')");
	//$data['tasks'] = adesk_sql_select_array("SELECT * FROM #task WHERE ddate LIKE '$sql_date%'");
	return $data;
}


function ihook_calendar_month($from, $to) {
	$data = array();
	$data['events'] = array(
		array(
		'sdate' => '2009-02-09',
		'edate' => '2009-02-10',
		'title' => 'yeeeey',
		'content' => 'this works',
		//'content' => 'this works',
		)
	);
	$data['tasks'] = array();
	$data['events'] = adesk_sql_select_array("SELECT *, name AS title, ldate AS edate FROM #campaign WHERE (sdate >= '$from' AND sdate < '$to') OR (ldate >= '$from' AND ldate < '$to') OR (sdate < '$to' AND ldate > '$from')");
	//$data['events'] = adesk_sql_select_array("SELECT * FROM #calendar_event WHERE (sdate >= '$from' AND sdate < '$to') OR (edate >= '$from' AND edate < '$to') OR (sdate < '$to' AND edate > '$from')");
	//$data['tasks'] = adesk_sql_select_array("SELECT * FROM #task WHERE ddate >= '$from' AND ddate < '$to'");
	return $data;
}


function ihook_calendar_link() {
	return '';
}

function ihook_adesk_user_select_query_conditions() {
	$admin = $GLOBALS["admin"];
	$rval  = "";

	# I know it's a magic number; 3 is the Admin group.
	if (!in_array(3, array_keys($admin["groups"]))) {
		$gstr    = implode("','", $admin["groups"]);
		$list    = adesk_sql_select_list("
			SELECT
				u.absid
			FROM
				#user u,
				#user_group g
			WHERE
				g.groupid IN ('$gstr')
			AND u.id = g.userid
		");
		$liststr = implode("','", $list);
		$rval = "AND absid IN ('$liststr')";
	}

	return $rval;
}


function ihook_adesk_updater_prepend() {
	// here we check for old engine file style
	// Set up to connect to the existing KB database, where we'll create some tables
	$data = adesk_file_get(adesk_admin("config_ex.inc.php"));
	$lines = preg_split("/\r?\n/", $data);
	$host = "";
	$username = "";
	$password = "";
	$database = "";
	foreach ( $lines as $line ) {
		if ( preg_match('/mysql_connect\s?\("([^"]*)","([^"]*)","([^"]*)"/', $line, $matches) or preg_match("/mysql_connect\s?\('([^']*)','([^']*)','([^']*)'/", $line, $matches) ) {
			$host = $matches[1];
			$username = $matches[2];
			$password = $matches[3];
		} elseif ( preg_match('/mysql_select_db\s?\("([^"]*)"/', $line, $matches) or preg_match("/mysql_select_db\s?\('([^']*)'/", $line, $matches) ) {
			$database = $matches[1];
		}
	}
	if ( $username == '' ) {
		reset($lines);
		foreach ( $lines as $line ) {
			if ( preg_match('/GLOBALS\[\'AWEBP_DB_HOST\'\] = "([^"]*)";/', $line, $matches) ) {
				$host = $matches[1];
			} elseif ( preg_match('/GLOBALS\[\'AWEBP_DB_USER\'\] = "([^"]*)";/', $line, $matches) ) {
				$username = $matches[1];
			} elseif ( preg_match('/GLOBALS\[\'AWEBP_DB_PASS\'\] = "([^"]*)";/', $line, $matches) ) {
				$password = $matches[1];
			} elseif ( preg_match('/GLOBALS\[\'AWEBP_DB_DATABASE\'\] = "([^"]*)";/', $line, $matches) ) {
				$database = $matches[1];
			}
		}
	}
	if ( $username == '' ) {
		reset($lines);
		foreach ( $lines as $line ) {
			if ( preg_match("/_DB_HOST', '([^']*)'/", $line, $matches) ) {
				$host = $matches[1];
			} elseif ( preg_match("/_DB_USER', '([^']*)'/", $line, $matches) ) {
				$username = $matches[1];
			} elseif ( preg_match("/_DB_PASS', '([^']*)'/", $line, $matches) ) {
				$password = $matches[1];
			} elseif ( preg_match("/_DB_NAME', '([^']*)'/", $line, $matches) ) {
				$database = $matches[1];
			}
		}
	}

	if ( !$username and !$password and !$database and !$host ) {
		die("This install can not be upgraded since the installation information is missing. Please verify that your /manage/config_ex.inc.php file has working database information in it before continuing.");
	}

	if ( !adesk_str_instr('db_link', $data) ) {
		writeEngine($host, $username, $password, $database) or die('Could not update /manage/config_ex.inc.php file. Please ensure the file is writable.');
	}
	// authentication file
	if ( !file_exists(adesk_admin("config.inc.php")) or filesize(adesk_admin("config.inc.php")) < 10 ) {
		writeAuth($host, $username, $password, $database) or die('Could not update /manage/config.inc.php file. Please ensure the file is writable.');
	}
	// something else here?
	$oldCrons = array('cron_bounce', 'cron_pop', 'cron_responder', 'cron_sync', 'cron_backup', 'sql/update_database.php');
	foreach ( $oldCrons as $v ) {
		if ( file_exists(adesk_admin($v . '.php')) ) {
			die('Please delete the file manage/' . $v . '.php and then refresh this page to continue.');
		}
	}
}

/*
	Get the version number based on the type of system we're in
*/
function ihook_adesk_updater_version() {
	// Set Default version number
	$site = null;
	$sql = mysql_query("SELECT * FROM Aawebdesk_backend LIMIT 1", $GLOBALS['db_link']);
	if ( $sql and mysql_num_rows($sql) == 1 ) {
		$site = mysql_fetch_assoc($sql);
		$GLOBALS['adesk_updater_backend'] = 'Aawebdesk_';
	} else {
		$GLOBALS['adesk_updater_backend'] = '#';
	}
	return $site;
}

function ihook_adesk_updater_post($site, $update) {
	if ( isset($site['p_link']) and isset($update['p_link']) ) {
		if ( $site['p_link'] != $update['p_link'] ) {
			$from = adesk_sql_escape($site['p_link']);
			$to   = adesk_sql_escape($update['p_link']);

			// replacein:
			// messages
			$arr = array(
				'=text' => "REPLACE(text, '$from', '$to')",
				'=html' => "REPLACE(html, '$from', '$to')",
			);
			adesk_sql_update("#message", $arr);
			adesk_sql_update("#message_archive", $arr);
			// templates
			adesk_sql_update_one("#template", "=content", "REPLACE(content, '$from', '$to')");
			// optins/outs
			$arr = array(
				'=optin_text'  => "REPLACE(optin_text, '$from', '$to')",
				'=optin_html'  => "REPLACE(optin_html, '$from', '$to')",
				'=optout_text' => "REPLACE(optout_text, '$from', '$to')",
				'=optout_html' => "REPLACE(optout_html, '$from', '$to')",
			);
			adesk_sql_update("#optinoptout", $arr);
			// subscription forms (redirection messages)
			$arr = array(
				'=sub1_value'   => "REPLACE(sub1_value, '$from', '$to')",
				'=sub2_value'   => "REPLACE(sub2_value, '$from', '$to')",
				'=sub3_value'   => "REPLACE(sub3_value, '$from', '$to')",
				'=sub4_value'   => "REPLACE(sub4_value, '$from', '$to')",
				'=unsub1_value' => "REPLACE(unsub1_value, '$from', '$to')",
				'=unsub2_value' => "REPLACE(unsub2_value, '$from', '$to')",
				'=unsub3_value' => "REPLACE(unsub3_value, '$from', '$to')",
				'=unsub4_value' => "REPLACE(unsub4_value, '$from', '$to')",
				'=up1_value'    => "REPLACE(up1_value, '$from', '$to')",
				'=up2_value'    => "REPLACE(up2_value, '$from', '$to')",
			);
			adesk_sql_update("#form", $arr);
			// sender personalizations
			adesk_sql_update_one("#personalization", "=content", "REPLACE(content, '$from', '$to')");
		}
	}
}

function ihook_adesk_mailer_delete() {
	// if we deleted a current connection, define 1 as current
	if ( !adesk_sql_select_one('=COUNT(*)', '#mailer', "`current` = 1") ) {
		adesk_sql_update_one('#mailer', 'current', 1, "`id` = 1");
	}
}

function ihook_adesk_widget_bars() {
	return array(
		'admin' => array(
			'admin_startup' => _a("Startup"),
			'admin_subscriber' => _a("View Subscriber"),
		),
		'public' => array(
/*			'public_startup_left' => _a("Startup (Left)"),
			'public_startup_right' => _a("Startup (Right)"),
			'public_submit_left' => _a("Submit Ticket (Left)"),
			'public_submit_right' => _a("Submit Ticket (Right)"),
			'public_lookup_left' => _a("Ticket Lookup (Left)"),
			'public_lookup_right' => _a("Ticket Lookup (Right)"),
			'public_ticket_left' => _a("View Ticket (Left)"),
			'public_ticket_right' => _a("View Ticket (Right)"),
			'public_kb_left' => _a("Content (Left)"),
			'public_kb_right' => _a("Content (Right)"),
			'public_troubleshooter_left' => _a("Troubleshooter (Left)"),
			'public_troubleshooter_right' => _a("Troubleshooter (Right)"),
			'public_downloads_left' => _a("Downloads (Left)"),
			'public_downloads_right' => _a("Downloads (Right)"),
			'public_account_left' => _a("Account Page (Left)"),
			'public_account_right' => _a("Account Page (Right)"),
			'public_register_left' => _a("User Registration (Left)"),
			'public_register_right' => _a("User Registration (Right)"),
*/		),
	);
}

function ihook_acg_loginsource_assets($smarty) {
	$smarty->assign("loginsource_usersettings_header", true);
	$smarty->assign("side_content_template", "side.settings.htm");

	return $smarty;
}

?>
