<?php

require_once awebdesk_classes("select.php");
require_once awebdesk_functions("process.php");

function list_select_query(&$so, $ignoreperms = false) {
	$admin = adesk_admin_get();
	$uid = 1;
	if ( $admin['id'] > 1 ) {
		$lists = implode("', '", $admin["lists"]);
		$so->push("AND l.id IN ('$lists')");
		$uid = $admin['id'];
	}
	if ( $admin and $admin['id'] > 0 and !$ignoreperms ) {
		return $so->query("
			SELECT
				*,
				( SELECT COUNT(DISTINCT(s.subscriberid)) FROM #subscriber_list s WHERE s.listid = l.id AND s.status = 1 ) AS subscribers,
				( SELECT COUNT(cl.id) FROM #campaign_list cl, #campaign c WHERE cl.campaignid = c.id AND c.status != 0 AND cl.listid = l.id ) AS campaigns,
				( SELECT IF(SUM(cl.list_amt) IS NULL, 0, SUM(cl.list_amt)) FROM #campaign_list cl, #campaign c WHERE cl.campaignid = c.id AND c.status != 0 AND cl.listid = l.id ) AS emails,
				l.id AS id,
				l.userid AS luserid
			FROM
				#list l,
				#user_p p
			WHERE
			[...]
			AND
				p.userid = '$uid'
			AND
				p.listid = l.id
		");
	} else {
		return $so->query("
			SELECT
				*,
				l.id AS listid,
				'1' AS groupid,
				0 AS subscribers,
				( SELECT COUNT(cl.id) FROM #campaign_list cl, #campaign c WHERE cl.campaignid = c.id AND c.status != 0 AND c.public = 1 AND cl.listid = l.id ) AS campaigns,
				0 AS emails
			FROM
				#list l
			WHERE
			[...]
		");
		/*
		return $so->query("
			SELECT
				*,
				( SELECT COUNT(DISTINCT(s.subscriberid)) FROM #subscriber_list s WHERE s.listid = l.id ) AS subscribers,
				( SELECT COUNT(cl.id) FROM #campaign_list cl, #campaign c WHERE cl.campaignid = c.id AND c.status != 0 AND c.public = 1 AND cl.listid = l.id ) AS campaigns,
				( SELECT IF(SUM(cl.list_amt) IS NULL, 0, SUM(cl.list_amt)) FROM #campaign_list cl, #campaign c WHERE cl.campaignid = c.id AND c.status != 0 AND cl.listid = l.id ) AS emails,
				l.id AS id
			FROM
				#list l,
				#list_group p
			WHERE
			[...]
			AND
				p.groupid = '1'
			AND
				p.listid = l.id
		");
		*/
	}
}

function list_select_row($id, $full = true) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND l.id = '$id'");
//dbg(list_select_query($so));
	$r = adesk_sql_select_row(list_select_query($so), array('cdate'/*, 'edate'*/));
	if ( $r and $full ) {
		$r['fields'] = list_get_fields($id, false);
		$r['groups'] = list_get_groups($id);
		$r['groupsCnt'] = count($r['groups']);
		$group_optinconfirm_tally = 0;
		foreach($r['groups'] as $group) {
			$group_optinconfirm_tally += $group['optinconfirm'];
		}
		$r['require_optin'] = ($group_optinconfirm_tally) ? 1 : 0;
		$optinoptout = adesk_sql_select_row("SELECT * FROM #optinoptout WHERE id = '$r[optinoptout]'");
		// if optin info is not found
		if ( !$optinoptout ) {
			// fallback to default
			$r['optinoptout'] = 1;
			$optinoptout = adesk_sql_select_row("SELECT * FROM #optinoptout WHERE id = '$r[optinoptout]'");
		}
		foreach ( $optinoptout as $k => $v ) {
			if ( substr($k, 0, 3) != 'opt' ) $k = 'opt' . $k;
			$r[$k] = $v;
		}
		$r['bounces'] = list_get_bounces($id);
		$r['analytics_domains_list'] = ( $r['analytics_domains'] == '' ? array() : explode("\n", $r['analytics_domains']) );

		// check for facebook session
		$r["facebook_oauth_login_url"] = $r["facebook_oauth_logout_url"] = $r["facebook_oauth_me"] = "";
		// make sure it's not coming from "Subscribe by email" pipe, otherwise Facebook class stuff won't work
		$pipe = !isset($_SERVER['REMOTE_ADDR']);
		$facebook_pass = ( function_exists('curl_init') && function_exists('hash_hmac') && (int)PHP_VERSION > 4 && !$pipe );
		if ($facebook_pass) {
			$facebook_oauth = list_facebook_oauth_init();
			if ( isset($_SESSION["facebook_oauth_perms"]) ) $_REQUEST["perms"] = $_SESSION["facebook_oauth_perms"];
			if ( isset($_SESSION["facebook_oauth_selected_profiles"]) ) $_REQUEST["selected_profiles"] = $_SESSION["facebook_oauth_selected_profiles"];
			if ( isset($_SESSION["facebook_oauth_installed"]) ) $_REQUEST["installed"] = $_SESSION["facebook_oauth_installed"];
			if ( isset($_SESSION["facebook_oauth_session"]) ) {
			  $_REQUEST["session"] = $_SESSION["facebook_oauth_session"];
			}
			else {
			  // clear it out here, so it doesn't show in new lists, or lists that dont have facebook setup yet
			  $_SESSION['facebook_oauth_session'] = '';
			}
			$facebook_oauth_session = list_facebook_oauth_getsession($facebook_oauth, $id);
			$facebook_oauth_me = null;
			if ($facebook_oauth_session) {
				$facebook_oauth_me = list_facebook_oauth_me($facebook_oauth, $facebook_oauth_session);
				// make sure it's an array
				if ( $facebook_oauth_me && is_object($facebook_oauth_me) ) $facebook_oauth_me = get_object_vars($facebook_oauth_me);
				$facebook_oauth_logout_url = list_facebook_oauth_geturl($facebook_oauth, $facebook_oauth_session, "", "", $GLOBALS["site"]["p_link"] . "/manage/desk.php?action=list&facebook_logout=" . $id . "&formid=" . $id);
				$r["facebook_oauth_logout_url"] = $facebook_oauth_logout_url;
				// if $facebook_oauth_me returns an error, it's possible the user changed their Facebook password after already authenticating in EM.
				// in that case, return $r as it looks now (including the logout link).
				if ( isset($facebook_oauth_me["error"]) && $facebook_oauth_me["error"] == 1 ) {
					return $r;
				}
			}
			$facebook_oauth_login_url = list_facebook_oauth_geturl($facebook_oauth, $facebook_oauth_session, "user_status,publish_stream,offline_access,email", $GLOBALS["site"]["p_link"] . "/manage/desk.php?action=list&formid=" . $id, "");
			$r["facebook_oauth_login_url"] = $facebook_oauth_login_url;
			$r["facebook_oauth_me"] = $facebook_oauth_me;
		}
	}
	return $r;
}

function list_select_array($so = null, $ids = null, $additional = '', $ignoreperms = false) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND l.id IN ('$ids')");
	}
	//dbg(adesk_sql_select_array(list_select_query($so)));
	$r = adesk_sql_select_array(list_select_query($so, $ignoreperms), array('cdate'/*, 'edate'*/));
	if ( $additional != '' ) {
		foreach ( $r as $k => $v ) {
			// email confirmation set
			if ( adesk_str_instr('optinout', $additional) ) {
				if ( $v['optinoptout'] == 0 ) $v['optinoptout'] = 1;
				$optinoptout = adesk_sql_select_row("SELECT * FROM #optinoptout WHERE id = '$v[optinoptout]'");
				if ( !$optinoptout ) {
					$v['optinoptout'] = 1;
					$optinoptout = adesk_sql_select_row("SELECT * FROM #optinoptout WHERE id = '$v[optinoptout]'");
				}
				foreach ( $optinoptout as $k2 => $v2 ) {
					if ( substr($k2, 0, 3) != 'opt' ) $k2 = 'opt' . $k2;
					$r[$k][$k2] = $v2;
				}
			}
		}
	}

	foreach ( $r as $k => $v ) {
		$r[$k]["url"] = list_url($v);
	}

	return $r;
}

function list_select_array_paginator_public($id, $sort, $offset, $limit, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$so->push("AND l.private = 0");

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'list'");
		$so->push($conds);
	}

	$so->count();
	$so->greedy = true;
	$total = (int)adesk_sql_select_one(list_select_query($so));

	switch ($sort) {
		default:
		case "01":
			$so->orderby("name"); break;
		case "01D":
			$so->orderby("name DESC"); break;
		case "02":
			$so->orderby("subscribers"); break;
		case "02D":
			$so->orderby("subscribers DESC"); break;
		case "03":
			$so->orderby("campaigns"); break;
		case "03D":
			$so->orderby("campaigns DESC"); break;
		case "04":
			$so->orderby("emails"); break;
		case "04D":
			$so->orderby("emails DESC"); break;
	}

	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = list_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function list_select_array_paginator($id, $sort, $offset, $limit, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'list'");
		$so->push($conds);
	}

	$so->count();
	$so->greedy = true;
	$total = (int)adesk_sql_select_one(list_select_query($so));

	switch ($sort) {
		default:
		case "01":
			$so->orderby("name"); break;
		case "01D":
			$so->orderby("name DESC"); break;
		case "02":
			$so->orderby("subscribers"); break;
		case "02D":
			$so->orderby("subscribers DESC"); break;
		case "03":
			$so->orderby("campaigns"); break;
		case "03D":
			$so->orderby("campaigns DESC"); break;
		case "04":
			$so->orderby("emails"); break;
		case "04D":
			$so->orderby("emails DESC"); break;
	}

	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = list_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function list_filter_post() {
	$whitelist = array("name"/*, "descript", "from_name", "from_email", "reply2"*/);

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "list",
		"conds" => "",
		"=tstamp" => "NOW()",
	);

	if (isset($_POST["qsearch"]) && !isset($_POST["content"])) {
		$_POST["content"] = $_POST["qsearch"];
	}

	if (isset($_POST["content"]) and $_POST['content'] != '') {
		$content = adesk_sql_escape($_POST["content"], true);
		$conds = array();

		if (!isset($_POST["section"]) || !is_array($_POST["section"]))
			$_POST["section"] = $whitelist;

		foreach ($_POST["section"] as $sect) {
			if (!in_array($sect, $whitelist))
				continue;
			$conds[] = "$sect LIKE '%$content%'";
		}

		$conds = implode(" OR ", $conds);
		$ary["conds"] = "AND ($conds) ";
	}

	if ( isset($_POST["private"]) ) {
		$private = (int)(bool)$_POST["private"];
		$ary["conds"] .= "AND l.private = '$private' ";
	}

	if ( defined("AWEBVIEW") and isset($_SESSION['nlp']) ) {
		if ( is_array($_SESSION['nlp']) ) {
			$listslist = implode("', '", array_map('intval', $_SESSION['nlp']));
			$ary["conds"] .= "AND l.id IN ('$listslist') ";
		}
	}

	if ( $ary['conds'] == '' ) return array("filterid" => 0);

	$conds_esc = adesk_sql_escape($ary['conds']);
	$filterid = adesk_sql_select_one("
		SELECT
			id
		FROM
			#section_filter
		WHERE
			userid = '$ary[userid]'
		AND
			sectionid = 'list'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

function list_select_list($ids, $filters = array()) {

	if ( !$ids && !$filters ) return $ids;

	$r = array();
	$conds = array();

	// filters from API
	if ($filters) {
		$whitelist = array("name");
		foreach ($filters as $k => $v) {
			if (!in_array($k, $whitelist)) {
				continue;
			}
			if ($k == "name") $conds[] = "AND name LIKE '%" . adesk_sql_escape($v, true) . "%'";
		}
	}

	if ($ids && $ids != "all") {
		$ids = explode(",", $ids);
		$ids = implode("','", $ids);
		$conds[] = "AND id IN ('" . $ids . "')";
	}

	// first pull just the ID's for Lists that match the conds
	$ids = adesk_sql_select_list( "SELECT id FROM #list WHERE 1 " . implode(" ", $conds) );

	// then loop through each ID and pull the full List row
	foreach ( $ids as $id ) {
		if ( $v = list_select_row($id) ) $r[] = $v;
	}

	return $r;
}

function list_insert_post() {
	if (!permission("pg_list_add"))
		return adesk_ajax_api_nopermission(_a("add lists"));

	// user access
	$admin = adesk_admin_get();

	$id = 0;
	$ary = list_post_prepare($id);

	if ( $ary['name'] == '' ) {
		return adesk_ajax_api_result(false, _a("List Name can not be empty."));
	}

	if (isset($ary['twitter_verify_credentials']) && !$ary['twitter_verify_credentials']) {
		return adesk_ajax_api_result(false, _a("Could not authenticate your account on Twitter."));
	}
	unset($ary['twitter_verify_credentials']);

	if ( !$admin['pg_list_add'] or !withinlimits('list', limit_count($admin, 'list', true) + 1) ) {
		return adesk_ajax_api_result(false, _a("You do not have permissions to add any more lists."));
	}

	if ( isset($GLOBALS['_hosted_account']) or $admin['forcesenderinfo'] ) {
		if ( !$ary['sender_name'] or !$ary['sender_addr1'] or !$ary['sender_zip'] or !$ary['sender_city'] or !$ary['sender_country'] ) {
			return adesk_ajax_api_result(false, _a("Sender Information is required and needs to be entered."));
		}
		if (
			adesk_str_is_email($ary['sender_name']) or
			adesk_str_is_email($ary['sender_addr1']) or
			adesk_str_is_email($ary['sender_addr2']) or
			adesk_str_is_email($ary['sender_zip']) or
			adesk_str_is_email($ary['sender_city']) or
			adesk_str_is_email($ary['sender_country'])
		) {
			return adesk_ajax_api_result(false, _a("Sender Information needs to contain postal, not e-mail address."));
		}
		/*
		if (
			adesk_str_is_url($ary['sender_name']) or
			adesk_str_is_url($ary['sender_addr1']) or
			adesk_str_is_url($ary['sender_addr2']) or
			adesk_str_is_url($ary['sender_zip']) or
			adesk_str_is_url($ary['sender_city']) or
			adesk_str_is_url($ary['sender_country'])
		) {
			return adesk_ajax_api_result(false, _a("Sender Information needs to contain postal, not web address."));
		}
		*/
	}

	$sql = adesk_sql_insert("#list", $ary);
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("List could not be added.") . adesk_sql_error());
	}
	// collect id
	$id = adesk_sql_insert_id();

	//Save list id to session
	$_SESSION['selected_list_id'] = $id;

	// email confirmation set
	$ins = array(
		"listid" => $id,
		"emailconfid" => 1,
	);
	adesk_sql_insert("#optinoptout_list", $ins);

	// bounce
	$bounces = adesk_http_param('bounceid');
	if ( !is_array($bounces) or count($bounces) == 0 ) {
		$bounces = array(1);
	}

	if ( count($bounces) == 1 and isset($bounces[0]) ) $bounces = array_diff(array_map('intval', explode(",", $bounces[0])), array(0));
	if ( count($bounces) == 0 ) {
		$bounces = array(1);
	}

	foreach ( $bounces as $b ) {
		$bary = array(
			'id' => 0,
			'bounceid' => $b,
			'listid' => $id,
		);
		$sql = adesk_sql_insert("#bounce_list", $bary);
		if ( !$sql ) {
			// rollback
			adesk_sql_delete('#list', "id = '$id'");
			return adesk_ajax_api_result(false, _a("List could not be added.") . adesk_sql_error());
		}
	}

	// update groups for this list (remove, then insert)
	$groups = array();
	$gperms = array();
	// set admin group always
	$groups[3] = 3;
	$gperms[3] = list_group_default_permissions(1); // all to true
	// if i'm not in admin (#3) group
	if ( !isset($admin['groups'][3]) ) {
		// add my group(s) here
		foreach ( $admin['groups'] as $g ) {
			$groups[$g] = $g;
			$gperms[$g] = array();
			// all to group defaults
			$group = adesk_sql_select_row("SELECT * FROM #group WHERE `id` = '$g'");
			foreach ( $group as $k => $v ) {
				if ( substr($k, 0, 3) == 'pg_' ) {
					$gperms[$g]['p_' . substr($k, 3)] = $v;
				}
			}
		}
	}
	// if list is not set to private, add "visitor" (#1) group
	if ( !adesk_http_param_exists('private') ) {
		$groups[1] = 1;
		$gperms[1] = list_group_default_permissions(0); // all to false
	}
	list_update_user_permissions($groups, $id, $gperms);

	if ( isset($GLOBALS['_hosted_account']) ) {
		require(dirname(dirname(__FILE__)) . '/manage/list.add.inc.php');
	}

	// rebuild admin's permissions
	adesk_session_drop_cache();
	$GLOBALS['admin'] = adesk_admin_get();
	$lists = list_get_all(true);
	return adesk_ajax_api_added(_a("List"), array("id" => $id));
}

function list_update_post() {
	if (!permission("pg_list_edit"))
		return adesk_ajax_api_nopermission(_a("delete lists"));

	// user access
	$admin = adesk_admin_get();

	$id = intval(adesk_http_param("id"));
	$ary = list_post_prepare($id);

	if ( $ary['name'] == '' ) {
		return adesk_ajax_api_result(false, _a("List Name can not be empty."));
	}

	if (isset($ary['twitter_verify_credentials']) && !$ary['twitter_verify_credentials']) {
		return adesk_ajax_api_result(false, _a("Could not authenticate your account on Twitter."));
	}
	unset($ary['twitter_verify_credentials']);

	if ( isset($GLOBALS['_hosted_account']) or $admin['forcesenderinfo'] ) {
		if ( !$ary['sender_name'] or !$ary['sender_addr1'] or !$ary['sender_zip'] or !$ary['sender_city'] or !$ary['sender_country'] ) {
			return adesk_ajax_api_result(false, _a("Sender Information is required and needs to be entered."));
		}
		if (
			adesk_str_is_email($ary['sender_name']) or
			adesk_str_is_email($ary['sender_addr1']) or
			adesk_str_is_email($ary['sender_addr2']) or
			adesk_str_is_email($ary['sender_zip']) or
			adesk_str_is_email($ary['sender_city']) or
			adesk_str_is_email($ary['sender_country'])
		) {
			return adesk_ajax_api_result(false, _a("Sender Information needs to contain postal, not e-mail address."));
		}
		/*
		if (
			adesk_str_is_url($ary['sender_name']) or
			adesk_str_is_url($ary['sender_addr1']) or
			adesk_str_is_url($ary['sender_addr2']) or
			adesk_str_is_url($ary['sender_zip']) or
			adesk_str_is_url($ary['sender_city']) or
			adesk_str_is_url($ary['sender_country'])
		) {
			return adesk_ajax_api_result(false, _a("Sender Information needs to contain postal, not web address."));
		}
		*/
	}

	$sql = adesk_sql_update("#list", $ary, "id = '$id'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("List could not be updated.") . adesk_sql_error());
	}

	// clear all message sources for this list
	campaign_source_clear(null, null, $id);

	// bounce
	$bounces = adesk_http_param('bounceid');
	if ( !is_array($bounces) or count($bounces) == 0 ) {
		$bounces = array(1);
	}
	// http_param('bounceid') comes across as array( 0 => 1,3,7,2 ), where '1,3,7,2' is the selected bounce ID's from the multi-select list.
	// shouldn't it come across as: array( 0 => 1, 1 => 3, 2 => 7, etc ) ?
	if ( count($bounces) == 1 ) $bounces = array_diff(array_map('intval', explode(",", $bounces[0])), array(0));
	if ( count($bounces) == 0 ) {
		$bounces = array(1);
	}
	adesk_sql_delete("#bounce_list", "listid = '$id'");
	foreach ( $bounces as $b ) {
		$bary = array(
			'id' => 0,
			'bounceid' => $b,
			'listid' => $id,
		);
		$sql = adesk_sql_insert("#bounce_list", $bary);
		if ( !$sql ) {
			return adesk_ajax_api_result(false, _a("List could not be saved.") . adesk_sql_error());
		}
	}

/*
	// user access
	// update groups for this list (remove, then insert)
	$groups = adesk_http_param('g');
	if ( !is_array($groups) ) $groups = array(3 => 3);
	if ( !isset($groups[3]) ) $groups[3] = 3;
	if ( !adesk_http_param_exists('private') ) $groups[1] = 1;
	$gperms = adesk_http_param('gp');
	if ( !is_array($gperms) ) $gperms = array();
	list_update_user_permissions($groups, $id, $gperms);
*/
	// user access
	// update group=1 (visitors) if list is marked as private
	// empty array sets all perms to false, and null deletes it
/*
	list_update_group_permissions(1, $id, ( !$ary['private'] ? array() : null )); // update visitor group
	list_update_group_permissions(3, $id, null); // update admin group
	if ( $gid != 3 ) list_update_group_permissions($gid, $id, null); // update user's group
*/
	// update groups for this list (remove, then insert)
	$groups = array();
	$gperms = array();
	// set admin group always
	$groups[3] = 3;
	$gperms[3] = list_group_default_permissions(1); // all to true
	// if i'm not in admin (#3) group
	if ( !isset($admin['groups'][3]) ) {
		// add my group(s) here
		foreach ( $admin['groups'] as $g ) {
			$groups[$g] = $g;
			$gperms[$g] = array();
			// all to group defaults
			$group = adesk_sql_select_row("SELECT * FROM #group WHERE `id` = '$g'");
			foreach ( $group as $k => $v ) {
				if ( substr($k, 0, 3) == 'pg_' ) {
					$gperms[$g]['p_' . substr($k, 3)] = $v;
				}
			}
		}
	}
	// if list is not set to private, add "visitor" (#1) group
	if ( !adesk_http_param_exists('private') ) {
		$groups[1] = 1;
		$gperms[1] = list_group_default_permissions(0); // all to false
	}
	list_update_user_permissions($groups, $id, $gperms);

	// clear it here upon saving, so new lists don't inherit previous list Facebook settings
	$_SESSION['facebook_oauth_session'] = '';

	// rebuild admin's permissions
	adesk_session_drop_cache();
	$GLOBALS['admin'] = adesk_admin_get();
	$lists = list_get_all(true);
	return adesk_ajax_api_updated(_a("List"));
}

function list_delete($id) {
	if (!permission("pg_list_delete"))
		return adesk_ajax_api_nopermission(_a("delete lists"));

	if (!withindeletelimits()) {
		return adesk_ajax_api_result(false, _a("You cannot delete any more subscribers or lists in this billing period"), array("pastlimit" => 1));
	}

	$id = intval($id);
	$clist = adesk_sql_select_array("
		SELECT
			c.campaignid,
			( SELECT COUNT(*) FROM #campaign_list subc WHERE c.campaignid = subc.campaignid AND subc.listid != '$id' ) AS used
		FROM
			#campaign_list c
		WHERE
			c.listid = '$id'
	");
	foreach ( $clist as $c ) {
		if ( $c['used'] ) {
			adesk_sql_delete('#campaign_list', "listid = '$id'");

		} else {
			require_once(adesk_admin('functions/campaign.php'));
			campaign_delete($c['campaignid']);
		}
	}
	/*
	$mlist = adesk_sql_select_array("
		SELECT
			m.messageid,
			( SELECT COUNT(*) FROM #message_list subm WHERE m.messageid = subm.messageid AND m.listid != '$id' ) AS usedinlist,
			( SELECT COUNT(*) FROM #campaign_message subc WHERE m.messageid = subc.messageid ) AS usedincamp
		FROM
			#message_list m
		WHERE
			m.listid = '$id'
	");
	foreach ( $mlist as $m ) {
		if ( !$m['usedincampaign'] ) {
			if ( $m['usedinlist'] ) {
				adesk_sql_delete('#message_list', "listid = '$id'");
			} else {
				require_once(adesk_admin('functions/message.php'));
				message_delete($m['messageid']);
			}
		} else {
			// was used in campaigns
		}
	}
	*/
	adesk_sql_delete('#bounce_list', "listid = '$id'");
	adesk_sql_delete('#emailaccount_list', "listid = '$id'");
	adesk_sql_delete('#exclusion_list', "listid = '$id'");
	adesk_sql_delete('#filter_list', "listid = '$id'");
	adesk_sql_delete('#form_list', "listid = '$id'");
	adesk_sql_delete('#header_list', "listid = '$id'");
	adesk_sql_delete('#list_field_rel', "relid = '$id'");
	adesk_sql_delete('#list_group', "listid = '$id'");
	#adesk_sql_delete('#message_list', "listid = '$id'");
	adesk_sql_delete('#personalization_list', "listid = '$id'");
	//adesk_sql_delete('#subscriber_list', "listid = '$id'");
	adesk_sql_delete('#subscriber_action', "listid = '$id' OR ( acton IN ('subscribe', 'unsubscribe') AND value = '$id' )");
	adesk_sql_delete('#template_list', "listid = '$id'");
	adesk_sql_delete('#user_p', "listid = '$id'");
	adesk_sql_delete('#list', "id = '$id'");
	adesk_sql_delete('#optinoptout_list', "listid = '$id'");
	adesk_sql_delete('#sync', "relid = '$id'");

	$admin = adesk_admin_get();
	$total = adesk_sql_select_one("SELECT COUNT(*) FROM #subscriber_list WHERE listid = '$id'");

	$ins = array(
		"userid"     => $admin["id"],
		"rnd"        => rand(),
		"action"     => "removeall",
		"total"      => $total,
		"completed"  => 0,
		"percentage" => 0,
		"data"       => serialize(array("conds" => "AND l.listid = '$id'", "lists" => array($id))),
		"=cdate"     => "NOW()",
		"ldate"     => "0000-00-00 00:00:00",
	);

	adesk_sql_insert("#process", $ins);
	adesk_process_spawn(array('id' => (int)adesk_sql_insert_id(), 'stall' => 5 * 60));
	//adesk_process_respawn(null, false);

	return adesk_ajax_api_result(true, _a("Your list has been deleted. It may take a couple minutes for changes to take place."));
}

function list_delete_multi($ids, $filter = 0) {
	if (!permission("pg_list_delete"))
		return adesk_ajax_api_nopermission(_a("delete lists"));

	if ( $ids == '_all' ) {
		$tmp = array();
		$so = new adesk_Select();
		$filter = intval($filter);
		if ($filter > 0) {
			$admin = adesk_admin_get();
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'list'");
			$so->push($conds);
		}
		$all = list_select_array($so);
		foreach ( $all as $v ) {
			$tmp[] = $v['id'];
		}
	} else {
		$tmp = array_map("intval", explode(",", $ids));
	}
	foreach ( $tmp as $id ) {
		$r = list_delete($id);
		if (isset($r["pastlimit"]))
			break;
	}

	return $r;
}

function list_delete_messages($ids) {
	adesk_sql_delete('#message_list', "listid IN ('$ids')");
	$mesgids = adesk_sql_select_list("
		SELECT
			ml.messageid
		FROM
			#message_list ml
		WHERE
			(SELECT _l.id FROM #list _l WHERE _l.id = ml.listid) IS NULL
	");

	# If any messages are now orphaned, that is they no longer have any list associated
	# with them, they should be deleted.

	message_delete_multi(implode(",", $mesgids));
}

function list_get_fields($id, $global = true) {
	# Before we do anything, convert the list of ids to array and clean it up
	if ( !is_array($id) ) {
		$id = str_replace(' ', '', (string)$id);
		$id = explode(',', $id);
	}
	$id = array_diff(array_map('intval', $id), array(0));
	# Before we check for $global, cut down the list of ids to what we're allowed.

	// visitor, not logged in, for example: viewing "web copy," which needs to convert pers tags even if the list is private,
	// and therefore not in $GLOBALS["admin"]["lists"], causing $id to be empty and the query below to return nothing.
	if ($GLOBALS["admin"]["id"] == 0) {
		$id = implode(", ", $id);
	}
	else {
		if ( isset($GLOBALS["admin"]["groups"][3]) ) {
			$id = implode(", ", $id);
		} else {
			$id = implode(", ", array_intersect($GLOBALS["admin"]["lists"], $id));
		}
	}

	if ( $global ) {
		$id .= ( $id != '' ? ', 0' : '0' );
	}
	if ( $id == '' ) $id = '-999'; // dummy
	return adesk_custom_fields_select_nodata_rel('#list_field', '#list_field_rel', "r.relid IN ($id)");
}



function list_get_all($forceFetch = false, $lite = false, $ids = null) {
	if ( is_null(adesk_php_global_get('_Aawebdesk_lists')) or $forceFetch ) {
		$r = array();
		$so = new adesk_Select;
		$so->orderby("name");
		if($lite){
			$so->slist = array(
					'l.id',
					'l.stringid',
					'l.name',
			);
			$so->remove = false;
		}
		if ( !is_null($ids) ) {
			if ( !is_array($ids) ) $ids = array_diff(array_map('intval', explode(',', $ids)), array(0));
			if ( count($ids) ) {
				$admin = adesk_admin_get();
				$ids = implode("', '", $admin['lists']);
				//sandeep $ids = implode("', '", $ids);
				$so->push("AND l.id IN ('$ids')");
			}
		}

		$sql = adesk_sql_query(list_select_query($so));
		if ( !$sql or mysql_num_rows($sql) == 0 ) return $r;
		while ( $row = mysql_fetch_assoc($sql) ) {
			//$row['url'] = list_url($row);
			//$row['preview'] = adesk_str_preview($row['descript']);
			$r[$row['id']] = $row;
		}
		adesk_php_global_set('_Aawebdesk_lists', $r);
	}
	return adesk_php_global_get('_Aawebdesk_lists');
}

function list_get_cnt($ids = null) {
	$so = new adesk_Select;
	if ( !is_null($ids) ) {
		if ( !is_array($ids) ) $ids = array_diff(array_map('intval', explode(',', $ids)), array(0));
		if ( count($ids) ) {
			$ids = implode("', '", $ids);
			$so->push("AND l.id IN ('$ids')");
		}
	}
	$so->count();
	$so->greedy = true;
	return (int)adesk_sql_select_one(list_select_query($so));
}

function list_get_one($id) {
	$all = list_get_all();
	if ( !isset($all[$id]) ) return null;
	return $all[$id];
}

function list_get_bounces($id) {
	if ( $id == 0 ) {
		$bounces = adesk_sql_select_array("SELECT * FROM #bounce WHERE id = 1");
	} else {
		$bounces = adesk_sql_select_array("SELECT b.* FROM #bounce b, #bounce_list l WHERE l.listid = '$id' AND l.bounceid = b.id");
	}
	if ( count($bounces) > 0 ) {
		return $bounces;
	}
	if ( $id == 0 ) {
		die('Corrupted installation. Please contact support.');
	} else {
		// list's bounce info (for unknown reason) not found
		// remove any old references and add default reference to avoid this loop next time
		adesk_sql_delete('#bounce_list', "listid = '$id'");
		adesk_sql_insert('#bounce_list', array('id' => 0, 'listid' => $id, 'bounceid' => 1));
	}
	return list_get_bounces(0);
}

function list_personalizations($so) {
	require_once(adesk_admin('functions/personalization.php'));
	$r = adesk_array_groupby(adesk_array_unique(personalization_select_array($so, null), 'tag'), 'format');
	if ( !$r ) $r = array();
	if ( !isset($r['html']) ) $r['html'] = array();
	if ( !isset($r['text']) ) $r['text'] = array();
	return $r;
}


function list_get_groups($id) {
	$r = array();
	$query = "
		SELECT
			*,
			g.id AS id
		FROM
			#group g,
			#list_group p
		WHERE
			p.listid = '$id'
		AND
			p.groupid = g.id
		ORDER BY
			title ASC
	";
	$sql = adesk_sql_query($query);
	if ( !$sql or mysql_num_rows($sql) == 0 ) return $r;
	while ( $row = mysql_fetch_assoc($sql) ) {
		$r[$row['id']] = $row;
	}
	return $r;
}


function list_post_prepare($id) {
	/*
	// ENUM whitelist
	$enums = array(
		'lang' => array_keys(adesk_lang_choices()),
		'limit_mail_type' => array('day','week','month','month1st','monthcdate','year','ever')
	);
	*/
	$admin = adesk_admin_get();
	$site = adesk_site_get();
	$r = array();
	if ( $id == 0 ) $r['userid'] = (int)$admin['id'];
	if ( adesk_admin_ismaingroup() and (int)adesk_http_param('userid') ) {
		$r['userid'] = (int)adesk_http_param('userid');
	}
	// general list settings
	$r['name'] = (string)adesk_http_param('name');
	$r['=cdate'] = "NOW()";
	$r['stringid'] = (string)adesk_http_param('stringid');
	if ( $r['stringid'] == '' ) $r['stringid'] = $r['name'];
	$r['stringid'] = adesk_sql_find_next_index('#list', 'stringid', adesk_str_urlsafe($r['stringid']), "AND id != '$id'");
	//$r['descript'] = (string)adesk_http_param('descript');
	//$r['from_name'] = (string)adesk_http_param('from_name');
	//$r['from_email'] = (string)adesk_http_param('from_email');
	//$r['reply2'] = (string)adesk_http_param('reply2');
	// list permissions
	$r['p_use_tracking'] = 1;//(int)adesk_http_param_exists('p_use_tracking');
	$r['p_use_analytics_read'] = (int)adesk_http_param_exists('p_use_analytics_read');
	$r['p_use_analytics_link'] = (int)adesk_http_param_exists('p_use_analytics_link');

	$twitter_facebook_pass = ( function_exists('curl_init') && function_exists('hash_hmac') && (int)PHP_VERSION > 4 );
	$r['p_use_twitter'] = ($twitter_facebook_pass) ? (int)adesk_http_param_exists('p_use_twitter') : 0;
	if ( !(int)adesk_http_param_exists('p_use_twitter') ) {
		$r['twitter_token'] = "";
		$r['twitter_token_secret'] = "";
	}
	$facebook_session = adesk_sql_select_one("SELECT facebook_session FROM #list WHERE id = '$id'");
	$facebook_pass = ($site["facebook_app_id"] && $site["facebook_app_secret"] && $facebook_session);
	$r['p_use_facebook'] = ($twitter_facebook_pass && $facebook_pass) ? (int)adesk_http_param_exists('p_use_facebook') : 0;
	if ( !(int)adesk_http_param_exists('p_use_facebook') ) {
		$r['facebook_session'] = "";
	}

	$r['analytics_source'] = (string)adesk_http_param('analytics_source');
	$r['analytics_ua'] = (string)adesk_http_param('analytics_ua');
	$r['p_embed_image'] = 1;//(int)adesk_http_param_exists('p_embed_image');
	$r['carboncopy'] = adesk_str_emaillist((string)adesk_http_param('carboncopy'));
	$r['private'] = (int)adesk_http_param_exists('private'); // this is just a cached value; lists that don't allow access to visitors are unavailable on public side
	$r['p_duplicate_send'] = (int)adesk_http_param_exists('p_duplicate_send');
	$r['p_duplicate_subscribe'] = (int)adesk_http_param_exists('p_duplicate_subscribe');
	$r['require_name'] = (int)adesk_http_param_exists('require_name');
	$r['get_unsubscribe_reason'] = (int)adesk_http_param_exists('get_unsubscribe_reason');
	$r['send_last_broadcast'] = (int)adesk_http_param_exists('send_last_broadcast');
	$r['p_use_captcha'] = (int)adesk_http_param_exists('p_use_captcha');
	$r['subscription_notify'] = adesk_str_emaillist((string)adesk_http_param('subscription_notify'));
	$r['unsubscription_notify'] = adesk_str_emaillist((string)adesk_http_param('unsubscription_notify'));
	$r['to_name'] = (string)adesk_http_param('to_name');
	$r['optinoptout'] = (int)adesk_http_param('optid');
	if ( $r['optinoptout'] == 0 ) $r['optinoptout'] = 1;
	/*
	special values
	*/
	// id
	if ( $id == 0 ) $r['id'] = 0;
	/*
	// edate
	$edate = trim((string)adesk_http_param('edate'));
	if ( !preg_match('/^\d{4}-\d{2}-\d{2}$/', $edate) ) {
		$r['=edate'] = 'NULL';
	} else {
		$r['edate'] = $edate;
	}
	*/
	// analytics_source
	if ( $r['analytics_source'] == '' ) $r['analytics_source'] = $r['name'];
	// analytics_ua
	if ( !preg_match('/^UA-\d+-\d+$/', $r['analytics_ua']) ) $r['analytics_ua'] = '';
	// analytics_domain
	$domains = array_map('trim', (array)adesk_http_param('analytics_domains'));
	$r['analytics_domains'] = ( ( count($domains) == 1 and !$domains[0] ) ? '' : implode("\n", $domains) );

	// sender info
	$r['sender_name'] = trim((string)adesk_http_param('sender_name'));
	$r['sender_addr1'] = trim((string)adesk_http_param('sender_addr1'));
	$r['sender_addr2'] = trim((string)adesk_http_param('sender_addr2'));
	$r['sender_city'] = trim((string)adesk_http_param('sender_city'));
	$r['sender_zip'] = trim((string)adesk_http_param('sender_zip'));
	$r['sender_state'] = trim((string)adesk_http_param('sender_state'));
	$r['sender_country'] = trim((string)adesk_http_param('sender_country'));
	$r['sender_phone'] = trim((string)adesk_http_param('sender_phone'));
	
	//additonal list owners
	$r['additional_owners'] = trim((string)adesk_http_param('additional_owners'));

	return $r;
}


/*
	Update groups for this list (remove, then insert)
*/
function list_update_user_permissions($groups, $listID, $gperms) {
	// delete all old references!!!
	adesk_sql_delete('#list_group', "`listid` = '$listID' AND (groupid='1' || groupid='3')");
	// loop through selected perms and add them all
	
	
	
	
	
	
	
	
	
	
	foreach ( $groups as $gid => $group ) {
		$relvalues = list_group_permissions($gid, $listID, $gperms);
		// insert every group
		adesk_sql_insert('#list_group', $relvalues);
		// now update group if needed
		list_group_update($relvalues);
	}
	// rebuild user (group) permissions for this list
	list_rebuild_user_permissions($listID);
}

/*
	Update 1 group for this list (remove, then insert)
*/
function list_update_group_permissions($groupID, $listID, $gperms = array()) {
	// delete old reference!!!
	adesk_sql_delete('#list_group', "`listid` = '$listID' AND `groupid` = '$groupID'");
	// null means delete this group
	if ( !is_null($gperms) ) {
		// get perms array
		$relvalues = list_group_permissions($groupID, $listID, $gperms);
		// insert group
		adesk_sql_insert('#list_group', $relvalues);
		// now update group if needed
		list_group_update($relvalues);
	}
	// rebuild user (group) permissions for this list
	list_rebuild_user_permissions($listID);
}

function list_group_permissions($groupID, $listID, $gperms) {
	$r = array();
	$r['id'] = 0;
	$r['groupid'] = $groupID;
	$r['listid'] = $listID;
	//$r['p_list_add'] = ( isset($gperms[$groupID]['p_list_add']) ? (int)$gperms[$groupID]['p_list_add'] : 0 );
	//$r['p_list_edit'] = ( isset($gperms[$groupID]['p_list_edit']) ? (int)$gperms[$groupID]['p_list_edit'] : 0 );
	//$r['p_list_delete'] = ( isset($gperms[$groupID]['p_list_delete']) ? (int)$gperms[$groupID]['p_list_delete'] : 0 );
	$r['p_list_sync'] = ( isset($gperms[$groupID]['p_list_sync']) ? (int)$gperms[$groupID]['p_list_sync'] : 0 );
	$r['p_list_filter'] = ( isset($gperms[$groupID]['p_list_filter']) ? (int)$gperms[$groupID]['p_list_filter'] : 0 );
	$r['p_message_add'] = ( isset($gperms[$groupID]['p_message_add']) ? (int)$gperms[$groupID]['p_message_add'] : 0 );
	$r['p_message_edit'] = ( isset($gperms[$groupID]['p_message_edit']) ? (int)$gperms[$groupID]['p_message_edit'] : 0 );
	$r['p_message_delete'] = ( isset($gperms[$groupID]['p_message_delete']) ? (int)$gperms[$groupID]['p_message_delete'] : 0 );
	$r['p_message_send'] = ( isset($gperms[$groupID]['p_message_send']) ? (int)$gperms[$groupID]['p_message_send'] : 0 );
	$r['p_subscriber_add'] = ( isset($gperms[$groupID]['p_subscriber_add']) ? (int)$gperms[$groupID]['p_subscriber_add'] : 0 );
	$r['p_subscriber_edit'] = ( isset($gperms[$groupID]['p_subscriber_edit']) ? (int)$gperms[$groupID]['p_subscriber_edit'] : 0 );
	$r['p_subscriber_delete'] = ( isset($gperms[$groupID]['p_subscriber_delete']) ? (int)$gperms[$groupID]['p_subscriber_delete'] : 0 );
	$r['p_subscriber_import'] = ( isset($gperms[$groupID]['p_subscriber_import']) ? (int)$gperms[$groupID]['p_subscriber_import'] : 0 );
	$r['p_subscriber_approve'] = ( isset($gperms[$groupID]['p_subscriber_approve']) ? (int)$gperms[$groupID]['p_subscriber_approve'] : 0 );
	return $r;
}

function list_group_default_permissions($val) {
	$r = array();
	//$r['p_list_add'] = ( isset($gperms[$groupID]['p_list_add']) ? (int)$gperms[$groupID]['p_list_add'] : 0 );
	//$r['p_list_edit'] = ( isset($gperms[$groupID]['p_list_edit']) ? (int)$gperms[$groupID]['p_list_edit'] : 0 );
	//$r['p_list_delete'] = ( isset($gperms[$groupID]['p_list_delete']) ? (int)$gperms[$groupID]['p_list_delete'] : 0 );
	$r['p_list_sync'] =
	$r['p_list_filter'] =
	$r['p_message_add'] =
	$r['p_message_edit'] =
	$r['p_message_delete'] =
	$r['p_message_send'] =
	$r['p_subscriber_add'] =
	$r['p_subscriber_edit'] =
	$r['p_subscriber_delete'] =
	$r['p_subscriber_import'] =
	$r['p_subscriber_approve'] = $val;
	return $r;
}

function list_group_update($listGroup) {
	$gid = $listGroup['groupid'];
	// now fetch this group
	$group = adesk_sql_select_row("SELECT * FROM #group WHERE `id` = '$gid'");
	// then check if any global setting is off while this is set to on (gotta switch it then)
	foreach ( $listGroup as $k => $v ) {
		// if setting is on
		if ( substr($k, 0, 2) == 'p_' and $v == 1 ) {
			$field = 'pg_' . substr($k, 2);
			// and global setting is NOT on
			if ( isset($group[$field]) and !$group[$field] ) {
				// then set global option to on
				adesk_sql_update_one('#group', $field, 1, "`id` = '$gid'");
			}
		}
	}
}

/*
	used for deleting and by list_update_user_permissions
*/
function list_rebuild_user_permissions($id) {
	// first clear out old cache
	adesk_sql_delete('#user_p', "`listid` = '$id'");
	// then grab all list's groups
	$groups = list_get_groups($id);
	// then all users with access
	$users = group_get_users(array_keys($groups));
	$user_p = adesk_sql_default_row('#user_p');
	// now loop through all users that have some access
	//foreach ( $users as $userID => $user ) {
		// and fetch each one's permissions (groups)
		$admin = adesk_admin_get();
        $uid = $admin['id'];
		$perms = user_get_groups($uid);
		// we will add a row for each user
		$values = array();
		$values['id'] = 0;
		$values['listid'] = $id;
		$values['userid'] = $uid;
	if($values['userid'] != 1)
	{ 
		// now loop through all list's groups
		foreach ( $groups as $groupID => $group ) {
			// if users is member of this group, start replacing his permissions
			if ( isset($perms[$groupID]) ) {
				// loop through permissions
				foreach ( $group as $k => $v ) {
					if ( substr($k, 0, 2) == 'p_' ) {
						// if already set to ALLOW, don't do anything
						if ( isset($values[$k]) and $values[$k] ) continue;
						// set this value instead
						if ( isset($user_p[$k]) ) $values[$k] = ( $uid == 1 ? 1 : $v );
					}
				}
			}
		}
		//dbg($values, 1);
		// and add this user
	 	adesk_sql_insert('#user_p', $values);// or die(adesk_sql_error());
		
		
		
		
		
		
		
	}	
	
	
	
	
		//process additional users
	
	//find out ids of additonal owners
	// first pull just the ID's for Lists that match the conds
	$add_owners = adesk_sql_select_one( "SELECT additional_owners FROM #list WHERE id = '$id'");

   //comma to array
   $listarray = explode(',', $add_owners); //split string into array seperated by ','
foreach($listarray as $listvalue) //loop over values
{   

    $values['userid'] = $listvalue;
	//restricted permissions only
	$values['p_admin'] = 1;
	$values['p_list_filter'] = 1;
	$values['p_message_add'] = 1;
	$values['p_message_edit'] = 1;
	$values['p_message_delete'] = 1;
	$values['p_message_send'] = 1;
	
	//insert them
	adesk_sql_insert('#user_p', $values);
}

	 
		//admin
		
//admin
		
		// we will add a row for admin now
		$values = array();
		$values['id'] = 0;
		$values['listid'] = $id;
		$values['userid'] = 1;
		// now loop through all list's groups
		foreach ( $groups as $groupID => $group ) {
			// if users is member of this group, start replacing his permissions
			if ( isset($perms[$groupID]) ) {
				// loop through permissions
				foreach ( $group as $k => $v ) {
					if ( substr($k, 0, 2) == 'p_' ) {
						// if already set to ALLOW, don't do anything
						if ( isset($values[$k]) and $values[$k] ) continue;
						// set this value instead
						if ( isset($user_p[$k]) ) $values[$k] = ( $uid == 1 ? 1 : $v );
					}
				}
			}
		}
		//dbg($values, 1);
		// and add this user
		adesk_sql_insert('#user_p', $values);// or die(adesk_sql_error());
		
		
		
	
	
	
	
		
		
		
		
		
	//}
}


function list_field_order($relid, $ids, $orders) {
	$relid      = (int)$relid;
	$ary_ids    = explode(',', $ids);
	$ary_orders = explode(',', $orders);
	if ( count($ary_ids) != count($ary_orders) )
		return adesk_ajax_error(_a("The ids and order numbers do not match."));
	$sql = "";
	for ( $i = 0; $i < count($ary_ids); $i++ ) {
		$id     = (int)$ary_ids[$i];
		$ary    = array('dorder' => (int)$ary_orders[$i]);
		adesk_sql_update("#list_field_rel", $ary, "`fieldid` = '$id' AND `relid` = '$relid'");
	}
	return array('succeeded' => true);
}

function list_field_update($subscriberID, $lists, $global = true) {
	$r = array('fields' => array());
	$arr = ( $lists ? array_map('intval', explode('-', $lists)) : array() );
	if ( (int)$subscriberID ) { // EDIT
		$r['fields'] = subscriber_get_fields((int)$subscriberID, $arr);
	} else { // ADD
		$r['fields'] = list_get_fields($arr, $global);
	}
	require_once(adesk_admin('functions/personalization.php'));
	$pso = new adesk_Select();
	$ids = implode("','", $arr);
	$pso->push("AND l.listid IN ('$ids')");
	$r['personalizations'] = list_personalizations($pso);
	$r['sentcampaigns'] = 0;
	if ( $subscriberID ) {
		$cso = new adesk_Select();
		$cso->push("AND l.listid IN ('$ids')");
		$cso->push("AND c.type IN ('single', 'recurring', 'split', 'deskrss', 'text')");
		$cso->push("AND c.status = 5");
		$cso->push("AND c.filterid = 0");
		// what about if the campaign has filter he doesn't match
		//2do
		$cso->count();
		$r['sentcampaigns'] = (int)adesk_sql_select_one(campaign_select_query($cso));
	}
	return $r;
}

function list_valid($list) {
	if ( !$list ) return false;
	// initialize a stack for fetched admins
	if ( !isset($GLOBALS['_listadmins']) ) $GLOBALS['_listadmins'] = array();
	$origAdmin = adesk_admin_get();
	// if not fetched already, add him to the stack
	if ( !isset($GLOBALS['_listadmins'][$list['userid']]) ) {
		// fetch this list's admin user
		$admin = adesk_admin_get_totally_unsafe($list['userid']);
		if ( !$admin ) $admin = adesk_admin_get_totally_unsafe(1);
		// fetch his subscribers limit
		$admin['subscribers_count'] = limit_count($admin, 'subscriber');
		// save him to the stack
		$GLOBALS['_listadmins'][$list['userid']] = $admin;
	}
	// get reference to type less
	$admin =& $GLOBALS['_listadmins'][$list['userid']]; // reference
	// check if next subscriber can be added
	$valid = withinlimits('subscriber', $admin['subscribers_count'] + 1, $admin);
	// if it is valid, add this subscriber right away to his current subscriber's count
	if ( $valid ) $admin['subscribers_count']++;
	$GLOBALS['admin'] = $origAdmin;
	return $valid;
}

function list_copy() {
	$id = intval(adesk_http_param("id"));
	if ($id < 1)
		return;

	$admin = adesk_admin_get();

	if ( !$admin['pg_list_add'] or !withinlimits('list', limit_count($admin, 'list', true) + 1) ) {
		return;
	}

	# Copy the list first.
	$rs = adesk_sql_query("SELECT * FROM #list WHERE id = '$id'");
	$list = adesk_sql_fetch_assoc($rs);

	unset($list["id"]);
	unset($list["cdate"]);
	//unset($list["edate"]);

	$list["name"]        = _a("Copy of") . " " . $list["name"];
	$list["stringid"]    = adesk_str_urlsafe(_a("Copy of")) . "-" . $list["stringid"];
	$list["=cdate"]      = "NOW()";
	//$list["=edate"]      = "NOW()";
	$list["optinoptout"] = "1";

	adesk_sql_insert("#list", $list);
	$newid = adesk_sql_insert_id();

	# And copy the user_p table.
	$userpary = adesk_sql_select_array("SELECT * FROM #user_p WHERE listid = '$id'");

	foreach ($userpary as $userp) {
		unset($userp["id"]);

		$userp["listid"] = $newid;
		adesk_sql_insert("#user_p", $userp);
	}

	# And copy the group table.
	$groupary = adesk_sql_select_array("SELECT * FROM #list_group WHERE listid = '$id'");
	foreach ($groupary as $group) {
		unset($group["id"]);
		$group["listid"] = $newid;
		adesk_sql_insert("#list_group", $group);
	}

	# Now copy any of the optional components...

	if (adesk_http_param("copy_bounce")) {
		adesk_sql_query("
			INSERT INTO #bounce_list
				(bounceid, listid)
			SELECT
				s.bounceid, '$newid'
			FROM
				#bounce_list s
			WHERE
				s.listid = '$id'
		");
	}

	if (adesk_http_param("copy_exclusion")) {
		adesk_sql_query("
			INSERT INTO #exclusion_list
				(exclusionid, listid, userid, sync)
			SELECT
				s.exclusionid, '$newid', s.userid, s.sync
			FROM
				#exclusion_list s
			WHERE
				s.listid = '$id'
		");
	}

	if (adesk_http_param("copy_filter")) {
		adesk_sql_query("
			INSERT INTO #filter_list
				(filterid, listid)
			SELECT
				s.filterid, '$newid'
			FROM
				#filter_list s
			WHERE
				s.listid = '$id'
		");
	}

	if (adesk_http_param("copy_form")) {
		adesk_sql_query("
			INSERT INTO #form_list
				(formid, listid)
			SELECT
				s.formid, '$newid'
			FROM
				#form_list s
			WHERE
				s.listid = '$id'
		");
	}

	if (adesk_http_param("copy_header")) {
		adesk_sql_query("
			INSERT INTO #header_list
				(headerid, listid)
			SELECT
				s.headerid, '$newid'
			FROM
				#header_list s
			WHERE
				s.listid = '$id'
		");
	}

	if (adesk_http_param("copy_personalization")) {
		adesk_sql_query("
			INSERT INTO #personalization_list
				(persid, listid)
			SELECT
				s.persid, '$newid'
			FROM
				#personalization_list s
			WHERE
				s.listid = '$id'
		");
	}

	if (adesk_http_param("copy_subscriber")) {
		adesk_sql_query("
			INSERT INTO #subscriber_list
			(
				subscriberid,
				listid,
				formid,
				sdate,
				udate,
				status,
				responder,
				sync,
				first_name,
				last_name,
				sourceid
			)
			SELECT
				s.subscriberid,
				'$newid' AS listid,
				s.formid,
				s.sdate,
				s.udate,
				s.status,
				s.responder,
				s.sync,
				s.first_name,
				s.last_name,
				sourceid
			FROM
				#subscriber_list s
			WHERE
				s.listid = '$id'
		");
	}

	if (adesk_http_param("copy_template")) {
		adesk_sql_query("
			INSERT INTO #template_list
				(templateid, listid)
			SELECT
				s.templateid, '$newid'
			FROM
				#template_list s
			WHERE
				s.listid = '$id'
		");
	}

	if (adesk_http_param("copy_field")) {
		adesk_sql_query("
			INSERT INTO #list_field_rel
				(fieldid, relid, dorder)
			SELECT
				s.fieldid, '$newid', s.dorder
			FROM
				#list_field_rel s
			WHERE
				s.relid = '$id'
		");
	}
}

function list_get_by_stringid($stringid) {
	$str = adesk_sql_escape($stringid);
	return (int)adesk_sql_select_one('id', '#list', "stringid = '$str'");
}

function list_url($list) {
	global $site;
	// use absolute URL?
	$base = $site['p_link'];
	// remove trailing slash if exists
	if ( substr($base, -1) == '/' ) $base = substr($base, 0, -1);
	// working array always starts with a base, without trailing slash
	$arr = array($base);

	if ( !$site['general_url_rewrite'] or !isset($list['stringid']) ) {
		$arr[] = 'index.php?action=archive&nl=' . $list['id'];
	} else {
		$arr[] = 'archive';
		$arr[] = $list['stringid'];
	}
	// return an url
	return implode('/', $arr);
}

function list_twitter_oauth_init($token = null, $token_secret = null) {
	$site = adesk_site_get();
	require_once awebdesk_classes("oauth.php");
	require_once awebdesk_classes("oauth_twitter.php");
	if (!$token && !$token_secret) {
	  if ($site["twitter_consumer_key"] == "JsjUb8QUaCg0fUDRfxnfcg") $site["twitter_consumer_key"] = "xuezwkFT39aJKr50Z1qM9g";
	  if ($site["twitter_consumer_secret"] == "ufR6occzeroEg4QzDYDZbqL8vMC8bji1a7c8oAYVM") $site["twitter_consumer_secret"] = "RY7Xcn3utS3dlAz5XV2fAWDsBg9vDAZOXqkdIgYM";
	}
	$oauth = new TwitterOAuth($site["twitter_consumer_key"], $site["twitter_consumer_secret"], $token, $token_secret);
	return $oauth;
}

function list_twitter_oauth_getrequesttoken($listid, $init) {
  $site = adesk_site_get();
	$twitter_oauth_request = $init->getRequestToken($site["p_link"] . "/manage/desk.php?action=list&id=" . $listid);
	//dbg($twitter_oauth_request);
	foreach ($twitter_oauth_request as $k => $v) {
  	if ( preg_match("/(error|failed)/i", $k) || preg_match("/(error|failed)/i", $v) ) {
  	  // trying to capture any errors when retreiving the request token.
  	  // usually an error happens when using invalid twitter consumer keys (such as when the twitter application is set up wrong)
  	  $twitter_oauth_request = array("error" => _a("There was an error with your Twitter application keys"));
  	}
	}
	return $twitter_oauth_request;
	//return array( "oauth_token" => $twitter_oauth_request['oauth_token'], "oauth_token_secret" => $twitter_oauth_request['oauth_token_secret'] );
}

function list_twitter_oauth_getregisterurl($init, $request) {
	$twitter_oauth_register_url = $init->adesk_getAuthorizeURL($request);
	return $twitter_oauth_register_url;
}

function list_twitter_oauth_getaccesstoken() {
	$listid = intval(adesk_http_param("id"));
	$request_token = adesk_http_param("twitter_oauth_request_token");
	$request_token_secret = adesk_http_param("twitter_oauth_request_token_secret");
	$pin = adesk_http_param("twitter_oauth_pin");
	$oauth = list_twitter_oauth_init($request_token, $request_token_secret);
	$request = $oauth->getAccessToken($pin);
	$savetodb = adesk_http_param("savetodb");
	if ( (int)$savetodb ) adesk_sql_query("UPDATE #list SET p_use_twitter = 1, twitter_token = '$request[oauth_token]', twitter_token_secret = '$request[oauth_token_secret]' WHERE id = $listid LIMIT 1");
	// check if any other list has different Twitter tokens - if so we provide them an option to update all lists to match this current Twitter account tokens
	$diff = adesk_sql_select_one("COUNT(*)", "#list", "(twitter_token != '$request[oauth_token]' OR twitter_token_secret != '$request[oauth_token_secret]')");
	return array( "oauth_token" => $request["oauth_token"], "oauth_token_secret" => $request["oauth_token_secret"], "diff" => $diff );
}

function list_twitter_oauth_verifycredentials($token, $token_secret) {
	require_once awebdesk_functions("json.php");
	$oauth = list_twitter_oauth_init($token, $token_secret);
	$credentials = $oauth->get("account/verify_credentials");
	if ( isset($credentials->error) ) {

	  // try new app keys, if they are using the old ones
	  if ($GLOBALS["site"]["twitter_consumer_key"] == "JsjUb8QUaCg0fUDRfxnfcg") $GLOBALS["site"]["twitter_consumer_key"] = "xuezwkFT39aJKr50Z1qM9g";
	  if ($GLOBALS["site"]["twitter_consumer_secret"] == "ufR6occzeroEg4QzDYDZbqL8vMC8bji1a7c8oAYVM") $GLOBALS["site"]["twitter_consumer_secret"] = "RY7Xcn3utS3dlAz5XV2fAWDsBg9vDAZOXqkdIgYM";
    $oauth = list_twitter_oauth_init($token, $token_secret);
    $credentials = $oauth->get("account/verify_credentials");

    // if there is still an error after trying with both sets of consumer keys, return the error
    if ( isset($credentials->error) ) {
	    return array( "error" => $credentials->error );
    }
	}
	// check if any other list has different Twitter tokens - if so we provide them an option to update all lists to match this current Twitter account tokens
	$diff = adesk_sql_select_one("=COUNT(*)", "#list", "(twitter_token != '$token' OR twitter_token_secret != '$token_secret')");
	return array( "screen_name" => $credentials->screen_name, "diff" => $diff );
}

// mirrors tokens from one list to all other lists
function list_twitter_token_mirror() {
	$listid = intval($_POST["id"]);
	$list = list_select_row($listid);
	$ary = array(
		"p_use_twitter" => 1,
		"twitter_token" => $list["twitter_token"],
		"twitter_token_secret" => $list["twitter_token_secret"],
	);
	$update = adesk_sql_update("#list", $ary, "id != '$listid'");
	return array('succeeded' => true);
}

// created this when we moved away from the PIN-based approach
function list_twitter_oauth_init2($listid, $token = null, $token_secret = null, $verifier = null) {
	$site = adesk_site_get();
	require_once awebdesk_classes("oauth.php");
	require_once awebdesk_classes("oauth_twitter.php");
	//if (!$token && !$token_secret) {
	  // authenticating for the first time; adjust keys
	  if ($site["twitter_consumer_key"] == "JsjUb8QUaCg0fUDRfxnfcg") $site["twitter_consumer_key"] = "xuezwkFT39aJKr50Z1qM9g";
	  if ($site["twitter_consumer_secret"] == "ufR6occzeroEg4QzDYDZbqL8vMC8bji1a7c8oAYVM") $site["twitter_consumer_secret"] = "RY7Xcn3utS3dlAz5XV2fAWDsBg9vDAZOXqkdIgYM";
	//}
	$oauth = new TwitterOAuth($site["twitter_consumer_key"], $site["twitter_consumer_secret"], $token, $token_secret);
	if (!$token && !$token_secret) {
	  // have not authorized yet
	  $request = list_twitter_oauth_getrequesttoken($listid, $oauth);
	  if ( isset($request["error"]) ) {
	    return array( "error" => $request["error"] );
    }
	  $_SESSION["twitter_oauth_token_secret"] = $request["oauth_token_secret"];
	  $register_url = list_twitter_oauth_getregisterurl($oauth, $request);
	  return array("register_url" => $register_url);
	}
	else {
    // have already authorized
    $access = $oauth->getAccessToken($verifier);
    $sql = adesk_sql_query("UPDATE #list SET p_use_twitter = 1, twitter_token = '$access[oauth_token]', twitter_token_secret = '$access[oauth_token_secret]' WHERE id = $listid LIMIT 1");
	}
}

function list_facebook_oauth_init() {
	$site = adesk_site_get();
	require_once awebdesk_classes("facebook.php");
	$facebook = new Facebook( array("appId" => $site["facebook_app_id"], "secret" => $site["facebook_app_secret"], "cookie" => false) );
	return $facebook;
}

function list_facebook_oauth_getsession($init, $listid = 0) {
	$session = null;
	// first check to see if we have it cached for the particular list
	if ($listid) $session = adesk_sql_select_one("SELECT facebook_session FROM #list WHERE id = '$listid'");
	if ($session) {
		// found in #list table
		$session = unserialize($session);
		$session = $init->setSession($session);
	}
	else {
		// see if the cookie is set
		$session = $init->getSession();
		if ($session) {
			// if cookie is set, save this to #list table
			$session = serialize($session);
			adesk_sql_query("UPDATE #list SET facebook_session = '$session' WHERE id = $listid LIMIT 1");
			$session = unserialize($session);
		}
	}
	return $session;
}

function list_facebook_oauth_me($init, $session) {
	$me = null;
	if ($session) {
      require_once awebdesk_functions("facebook.php");
      $me = facebook_oauth_me($init);
	}
	return $me;
}

function list_facebook_oauth_geturl($init, $session, $login_perms, $login_url, $logout_url) {
	$site = adesk_site_get();
	$me = list_facebook_oauth_me($init, $session);
	if ($me) {
		return $init->getLogoutUrl( array("next" => $logout_url) );
	}
	return $init->getLoginUrl( array("req_perms" => $login_perms, "next" => $login_url, "cancel_url" => $login_url) );
}

?>
