<?php

require_once awebdesk_classes("select.php");

function mailer_select_query(&$so) {
	return $so->query("
		SELECT * FROM #mailer m WHERE [...]
	");
}

function mailer_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND m.id = '$id'");

	$row = adesk_sql_select_row(mailer_select_query($so));
	$row['pass'] = base64_decode($row['pass']);
	return $row;
}

function mailer_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map('intval', $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND m.id IN ('$ids')");
	}
	$rows = adesk_sql_select_array(mailer_select_query($so));
	return $rows;
}

function mailer_select_array_paginator($id, $sort, $offset, $limit, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'mailer'");
		$so->push($conds);
	}

	$so->count();
	$total = (int)adesk_sql_select_one(mailer_select_query($so));

	switch ($sort) {
		default:
			$so->orderby("sort_order ASC");
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$so->limit("$offset, $limit");
	$rows = mailer_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function mailer_filter_post() {
	$whitelist = array("name", "host", "user");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "mailer",
		"conds" => "",
		"=tstamp" => "NOW()",
	);

	if (isset($_POST["qsearch"]) && !isset($_POST["content"])) {
		$_POST["content"] = $_POST["qsearch"];
	}

	if (isset($_POST["content"]) and $_POST["content"] != "") {
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
	if ( $ary['conds'] == '' ) return array('filterid' => 0);

	$conds_esc = adesk_sql_escape($ary["conds"]);
	$filterid = adesk_sql_select_one("
		SELECT
			id
		FROM
			#section_filter
		WHERE
			userid = '$ary[userid]'
		AND
			sectionid = 'mailer'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

function mailer_insert() {
	$ary = mailer_prepare_post();
	$ary['corder'] = 999;

	if ( isset($GLOBALS['_hosted_account']) ) {
		return adesk_ajax_api_result(false, "Mail Connection can not be added on hosted service.");
	}

	$sql = adesk_sql_insert("#mailer", $ary);
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Mail Connection could not be added."));
	}
	$id = adesk_sql_insert_id();

	// assign mailers to groups
	$groups = array_diff(array_map('intval', (array)adesk_http_param('p')), array(0));
	if ( count($groups) == 0 ) $groups = array(3);
	$groupslist = implode(',', $groups);
	$ary = array_merge($ary, array(
		'id' => $id,
		'groups' => $groups,
		'groupslist' => $groupslist,
	));
	if ( $ary['type'] and $ary['pass'] ) $ary['pass'] = base64_decode($ary['pass']);
	// add any campaign/group mailers
	foreach ( $groups as $g ) {
		$insert = array(
			'id' => 0,
			'groupid' => $g,
			'mailerid' => $id,
		);
		adesk_sql_insert('#group_mailer', $insert);
	}

	return adesk_ajax_api_added(_a("Mail Connection"), $ary);
}

function mailer_update() {
	$ary = mailer_prepare_post();

	if ( isset($GLOBALS['_hosted_account']) ) {
		return adesk_ajax_api_result(false, "Mail Connection can not be modified on hosted service.");
	}

	$id = intval($_POST["id"]);
	$sql = adesk_sql_update("#mailer", $ary, "id = '$id'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Mail Connection could not be updated."));
	}

	// assign mailers to groups
	$groups = array_diff(array_map('intval', (array)adesk_http_param('p')), array(0));
	if ( count($groups) == 0 ) $groups = array(3);
	$groupslist = implode(',', $groups);
	$ary = array_merge($ary, array(
		'id' => $id,
		'groups' => $groups,
		'groupslist' => $groupslist,
	));
	if ( $ary['type'] and $ary['pass'] ) $ary['pass'] = base64_decode($ary['pass']);
	// remove all old group mailer relations
	adesk_sql_delete('#group_mailer', "mailerid = '$id'");
	// add any campaign/group mailers
	foreach ( $groups as $g ) {
		$insert = array(
			'id' => 0,
			'groupid' => $g,
			'mailerid' => $id,
		);
		adesk_sql_insert('#group_mailer', $insert);
	}

	return adesk_ajax_api_updated(_a("Mail Connection"), $ary);
}

function mailer_prepare_post() {
	// build an insert array from inputs
	$ary = array(
		'name' => trim((string)adesk_http_param('smname')),
		'type' => (int)adesk_http_param('send'),
		'host' => trim((string)adesk_http_param('smhost')),
		'port' => (int)adesk_http_param('smport'),
		'user' => trim((string)adesk_http_param('smuser')),
		'pass' => trim((string)adesk_http_param('smpass')),
		'encrypt' => (int)adesk_http_param('smenc'),
		'pop3b4smtp' => (int)adesk_http_param_exists('smpop3b4'),
		'threshold' => (int)adesk_http_param('smthres'),
		'frequency' => (int)adesk_http_param('sdfreq'),
		'pause' => (int)adesk_http_param('sdnum'),
		'limit' => (int)adesk_http_param('sdlim'),
		'limitspan' => (string)adesk_http_param('sdspan'),
	);

	// if Sending Speed is set to "Send without limitations"
	if ( adesk_http_param('ltype') == 'dontstop' ) $ary['pause'] = $ary['limit'] = $ary['frequency'] = 0;
	// if Sending Speed is set to "Limit number of emails to send for a specific time period"
	if ( adesk_http_param('ltype') == 'lim' ) $ary['pause'] = $ary['frequency'] = 0;
	// if Sending Speed is set to "Enable sending throttling and pausing"
	if ( adesk_http_param('ltype') == 'sd' ) $ary['limit'] = 0;

	// filter the whitelist
	if ( $ary['type'] != 1 ) $ary['type'] = 0;
	if ( !$ary['port'] ) $ary['port'] = 25;
	if ( !$ary['threshold'] ) $ary['threshold'] = 50;
	if ( $ary['limitspan'] != 'hour' ) $ary['limitspan'] = 'day';

	// do not allow over 4 minutes of pause
	if ( $ary['pause'] > 4 * 60 ) $ary['pause'] = 4 * 60;

	if ( !$ary['name'] ) {
		$ary['name'] = $ary['type'] ? $ary['host'] : _a('Mail()');
	}

	if ( $ary['type'] and $ary['pass'] ) $ary['pass'] = base64_encode($ary['pass']);

	return $ary;
}

function mailer_delete($id) {
	if ( isset($GLOBALS['_hosted_account']) ) {
		return adesk_ajax_api_result(false, "Mail Connection can not be deleted on hosted service.");
	}

	$id = intval($id);
	adesk_sql_query("DELETE FROM #mailer WHERE id = '$id'");
	// if we deleted a current connection, define 1 as current
	if ( !adesk_sql_select_one('=COUNT(*)', '#mailer', "`current` = 1") ) {
		adesk_sql_update_one('#mailer', 'current', 1, "1 LIMIT 1");
	}
	return adesk_ajax_api_deleted(_a("Mail Connection"));
}

function mailer_delete_multi($ids, $filter = 0) {
	if ( $ids == '_all' ) {
		$tmp = array();
		$so = new adesk_Select();
		$filter = intval($filter);
		if ($filter > 0) {
			$admin = adesk_admin_get();
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'mailer'");
			$so->push($conds);
		}
		$all = mailer_select_array($so);
		foreach ( $all as $v ) {
			$tmp[] = $v['id'];
		}
	} else {
		$tmp = array_map("intval", explode(",", $ids));
	}
	foreach ( $tmp as $id ) {
		$r = mailer_delete($id);
	}
	return $r;
}

function mailer_send($mailer, $email) {
	require_once(awebdesk_functions('mail.php'));

	$mailer['pass'] = base64_encode($mailer['pass']);

	$site =& $GLOBALS['site'];
	//$to_name = $r['email'];
	if ( isset($site['site_name']) ) {
		$from_name = $site['site_name'];
	} elseif ( isset($site['sname']) ) {
		$from_name = $site['sname'];
	} else {
		$from_name = $_SERVER['SERVER_NAME'];
	}
	if ( isset($site['emfrom']) ) {
		$from_email = $site['emfrom'];
	} elseif ( isset($site['awebdesk_from']) ) {
		$from_email = $site['awebdesk_from'];
	} else {
		$from_email = 'test@' . $_SERVER['SERVER_NAME'];
	}

	$to_name = '';
	$options = array(
		'bounce' => $site['awebdesk_bounce'],
		'attach' => array(),
		'headers' => array(),
		'reply2' => '',
		'priority' => 3, // 3-normal, 1-low, 5-high
		'encoding' => _i18n("quoted-printable"),
		'charset' => _i18n("utf-8"),
	);

	$subject = _a("Mail Sending Options Test");
	$body = sprintf(_a("If you have received this email, that means that Mail Sending Options %s are set properly."), $mailer['name']);

	$sent = adesk_mail_send_raw(
		'text',
		$from_name,
		$from_email,
		$body,
		$subject,
		$email,
		$to_name = '',
		$mailer['type'],
		$mailer['host'],
		$mailer['port'],
		$mailer['user'],
		$mailer['pass'],
		$mailer['encrypt'],
		$mailer['pop3b4smtp'],
		$options
	);
	return $sent;
}

function mailer_test($id, $email) {
	if ( !adesk_admin_ismain() ) {
		return adesk_ajax_api_result(false, _a("You do not have permissions to perform this action."));
	}

	$mailer = mailer_select_row($id);
	if ( !$mailer ) {
		return adesk_ajax_api_result(false, _a("Mailer not found."));
	}
	if ( !adesk_str_is_email($email) ) {
		return adesk_ajax_api_result(false, _a("Please enter a valid email address."));
	}

	$presend = adesk_microtime_get();
	$sent = mailer_send($mailer, $email);
	if ( !$sent ) {
		return adesk_ajax_api_result(false, _a("Email could not be sent. Please check your connection settings for errors."));
	}
	$postsend = adesk_microtime_get();
	$sendtime = $postsend - $presend;

	return adesk_ajax_api_result(true, _a("Email successfully sent. Please check the Inbox, the email should arrive soon..."), array('sendtime' => $sendtime));
}

function mailer_test_post() {
	dbg('2do');

	$email = adesk_http_param('to_email');
	if ( !adesk_str_is_email($email) ) {
		return adesk_ajax_api_result(false, _a("Please enter a valid email address."));
	}

	$sent = mailer_send($_POST, $email);
	if ( !$sent ) {
		return adesk_ajax_api_result(false, _a("Email could not be sent. Please check your connection settings for errors."));
	}

	return adesk_ajax_api_result(true, _a("Email successfully sent. Please check the Inbox, the email should arrive soon..."));
}

function mailer_sort($order) {
	$mailers = array_diff(array_map('intval', explode(',', $order)), array(0));
	if ( !$mailers ) {
		return adesk_ajax_api_result(false, _a("Mailers not provided."));
	}

	foreach ( $mailers as $k => $v ) {
		adesk_sql_update_one("#mailer", "corder", $k + 1, "id = '$v'");
	}

	return adesk_ajax_api_result(true, _a("Mailers order updated."));
}

?>