<?php

require_once awebdesk_classes("select.php");

function abuse_select_query(&$so) {
	return $so->query("
		SELECT
			g.id,
			g.title,
			g.descript,
			l.abuseratio,
			( SELECT SUM(c.amt) FROM #campaign_count c WHERE c.groupid = g.id ) AS sent,
			( SELECT COUNT(*)   FROM #abuse          a WHERE a.groupid = g.id ) AS abuses
		FROM
			#group g,
			#group_limit l
		WHERE
		[...]
		AND
			g.id = l.groupid
		AND
			g.id > 2
	");
	/*
		AND
			( SELECT SUM(c.amt) FROM #campaign_count c WHERE c.groupid = g.id ) > 0
		AND
			l.abuseratio <
				( SELECT COUNT(*) FROM #abuse a WHERE a.groupid = g.id )
			/
				( SELECT SUM(c.amt) FROM #campaign_count c WHERE c.groupid = g.id )
			* 100
	*/
}

function abuse_select_prepare($row) {
	$row['ratio'] = ( $row['sent'] ? number_format($row['abuses'] / $row['sent'] * 100, 2) : 0 );
	$row['hash'] = md5($row['id'] . $row['title'] . $row['descript']);
	return $row;
}

function abuse_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND g.id = '$id'");

	$r = adesk_sql_select_row(abuse_select_query($so));
	if ( $r ) $r = abuse_select_prepare($r);
	return $r;
}

function abuse_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map('intval', $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND g.id IN ('$ids')");
	}
	$rows = adesk_sql_select_array(abuse_select_query($so));
	foreach ( $rows as $k => $v ) $rows[$k] = abuse_select_prepare($v);
	return $rows;
}

function abuse_select_array_paginator($id, $sort, $offset, $limit, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$so->count();
	$total = (int)adesk_sql_select_one(abuse_select_query($so));

	switch ($sort) {
	}

	switch ($sort) {
		default:
		case "01":
			$so->orderby("g.title"); break;
		case "01D":
			$so->orderby("g.title DESC"); break;
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = abuse_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}
/*
function abuse_insert_post() {
	$ary = array(
	);

	$sql = adesk_sql_insert("#abuse", $ary);
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Abuse Complaint could not be added."));
	}
	$id = adesk_sql_insert_id();

	return adesk_ajax_api_added(_a("Abuse Complaint"));
}

function abuse_update_post() {
	$ary = array(
	);

	$id = intval($_POST["id"]);
	$sql = adesk_sql_update("#abuse", $ary, "id = '$id'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Abuse Complaint could not be updated."));
	}

	return adesk_ajax_api_updated(_a("Abuse Complaint"));
}
*/
function abuse_delete($id) {
	$id = intval($id);
	adesk_sql_query("DELETE FROM #abuse WHERE id = '$id'");
	abuse_delete_relations(array($id));
	return adesk_ajax_api_deleted(_a("Abuse Complaint"));
}

function abuse_delete_multi($ids) {
	if ($ids == "_all") {
		adesk_sql_query("TRUNCATE TABLE #abuse");
		abuse_delete_relations(null);
		return;
	}
	$tmp = array_map("intval", explode(",", $ids));
	$ids = implode("','", $tmp);
	adesk_sql_query("DELETE FROM #abuse WHERE id IN ('$ids')");
	abuse_delete_relations($tmp);
	return adesk_ajax_api_deleted(_a("Abuse Complaint"));
}

function abuse_delete_relations($ids) {
	if ($ids === null) {		# delete all
	} else {
	}
}


function abuse_list($groupid, $hash) {
	require_once(awebdesk_functions('group.php'));
	$groupid = (int)$groupid;

	// check group/abuse
	$group = adesk_group_select_row($groupid);
	if ( !$group ) return adesk_ajax_api_result(false, _a("Group not provided."));
	$abuse = abuse_select_row($groupid);
	if ( !$abuse or $abuse['hash'] != $hash ) return adesk_ajax_api_result(false, _a("Abuse Group not provided."));

	return adesk_sql_select_array("SELECT * FROM #abuse WHERE `groupid` = '$groupid'");
}

function abuse_reset($groupid, $hash) {
	require_once(awebdesk_functions('group.php'));
	$groupid = (int)$groupid;
	if ( !$groupid ) return adesk_ajax_api_result(false, _a("Group not provided."));

	// check group/abuse
	$group = adesk_group_select_row($groupid);
	if ( !$group ) return adesk_ajax_api_result(false, _a("Group not provided."));
	$abuse = abuse_select_row($groupid);
	if ( !$abuse or $abuse['hash'] != $hash ) return adesk_ajax_api_result(false, _a("Abuse Group not provided."));

	adesk_sql_delete('#abuse', "`groupid` = '$groupid'");
	return adesk_ajax_api_deleted(_a("Abuse Complaints"));
}

function abuse_notify() {
	require_once(awebdesk_functions('group.php'));
	require_once(awebdesk_functions('mail.php'));
	$to = adesk_http_param('to');
	if ( !is_array($to) or !count($to) ) return adesk_ajax_api_result(false, _a("No recipients provided."));
	$from_name = (string)adesk_http_param('from_name');
	$from_mail = (string)adesk_http_param('from_mail');
	$subject = (string)adesk_http_param('subject');
	$message = (string)adesk_http_param('message');
	$hash = (string)adesk_http_param('hash');
	$options = array();
	$options['userid'] = 1;
	if ( !$from_mail or !$subject or !$message ) return adesk_ajax_api_result(false, _a("Notification data not provided."));

	// check group/abuse
	$groupid = (int)adesk_http_param('id');
	$group = adesk_group_select_row($groupid);
	if ( !$group ) return adesk_ajax_api_result(false, _a("Group not provided."));
	$abuse = abuse_select_row($groupid);
	if ( !$abuse or $abuse['hash'] != $hash ) return adesk_ajax_api_result(false, _a("Abuse Group not provided."));

	// get recipients from TO
	$users = user_get($to);
	if ( !count($users) ) return adesk_ajax_api_result(false, _a("No recipients were found."));

	$recipients = array();
	foreach ( $users as $k => $v ) {
		$recipients[$v['email']] = $v['first_name'] . ' ' . $v['last_name'];
	}
	// send an email
	$sent = adesk_mail_send('text', $from_name, $from_mail, $message, $subject, $recipients, $options);
	if ( !$sent ) return adesk_ajax_api_result(false, _a("Notification e-mail could not be sent."));

	return adesk_ajax_api_result(true, _a("Notification e-mail sent."));
}

function abuse_update($groupid, $hash, $abuseratio = 4) {
	require_once(awebdesk_functions('group.php'));
	$groupid = (int)$groupid;
	$abuseratio = (int)$abuseratio;
	if ( isset($GLOBALS['_hosted_account']) ) $abuseratio = 4;
	$group = adesk_group_select_row($groupid);
	if ( !$group ) return adesk_ajax_api_result(false, _a("Group not provided."));
	$abuse = abuse_select_row($groupid);
	if ( !$abuse or $abuse['hash'] != $hash ) return adesk_ajax_api_result(false, _a("Abuse Group not provided."));

	// update the abuse ratio
	$sql = adesk_sql_update_one('#group_limit', 'abuseratio', $abuseratio, "`groupid` = '$groupid'");
	if ( !$sql ) return adesk_ajax_api_result(false, _a("Abuse Ratio could not be updated."));

	return adesk_ajax_api_updated(_a("Abuse Ratio"));
}

function abuse_complaint($subscriber, $campaign, $mid = 0, $listid = 0) {
	global $site;
	require_once(awebdesk_functions('ajax.php'));
	// unsubscribe the subscriber
	if ( $campaign['id'] ) {
		$campaignlists = adesk_sql_select_list("SELECT listid FROM #campaign_list WHERE campaignid = '$campaign[id]'");
	} else {
		$eml = adesk_sql_escape($subscriber['email']);
		$campaignlists = adesk_sql_select_list("SELECT listid FROM #subscriber s, #subscriber_list l WHERE s.id = l.subscriberid AND s.email = '$eml'");
	}
	$unsubscribe = subscriber_unsubscribe($subscriber['id'], $subscriber['email'], $campaignlists, _p("Abuse Reported."), $fid = 0, $campaign['id'], $mid, true);
	//subscriber_delete($subscriber['id'], explode('-', $campaign['listslist']));
	if ( isset($GLOBALS['_hosted_account']) ) {
		require(dirname(dirname(__FILE__)) . '/manage/exclusion.add.inc.php');
	}
	$userOrig = adesk_admin_get();
	$userBefore = adesk_admin_get_totally_unsafe($campaign['userid']);
	// this is a useful check in case we already removed this user
	if ( $userBefore ) {
		// add his abuse report
		$insert = array(
			'id' => 0,
			'=rdate' => 'NOW()',
			'listid' => $listid,
			'campaignid' => $campaign['id'],
			'messageid' => $mid,
			'userid' => $campaign['userid'],
			'groupid' => (int)adesk_sql_select_one('groupid', '#user_group', "`userid` = '$campaign[userid]'"),
			'subscriberid' => $subscriber['id'],
			'email' => $subscriber['email'],
		);
		adesk_sql_insert('#abuse', $insert);
		// do something else here?
		$userAfter = adesk_admin_get_totally_unsafe($campaign['userid']);
		if ( !$userBefore['abuseratio_overlimit'] and $userAfter['abuseratio_overlimit'] ) {
			$admin = adesk_admin_get_totally_unsafe(1);
			if ( $admin and $admin['email'] ) {
				$options = array();
				$options['userid'] = 1;
				$abuse = abuse_select_row(current($userAfter['groups']));
				$viewlink = adesk_site_plink("index.php?action=complaint&g=$abuse[id]&h=$abuse[hash]");
				$managelink = adesk_site_alink("desk.php?action=abuse");
				$vars = array(
					'campaign' => $campaign,
					'user' => $userAfter,
					'viewlink' => $viewlink,
					'managelink' => $managelink,
					'abuse' => $abuse,
				);
				$message = adesk_mail_prepare('abuse_notify', $vars);
				$subject = sprintf(_p("User %s has been suspended due to abuse reports"), $userAfter['username']);
				$from_name = $site['site_name'];
				$from_mail = $site['emfrom'];
				if ( isset($GLOBALS['_hosted_account']) ) {
					/*
					if ( isset($_SESSION[$GLOBALS["domain"]]) ) {
						$from_mail = $_SESSION[$GLOBALS["domain"]]['email'];
					}
					*/
					$from_mail = base64_decode('bm9yZXBseUBhY3RpdmVjYW1wYWlnbi5jb20=');
					$headers = "From: <$from_mail>";
					$body = ( is_array($message['body']) ? $message['body']['text'] : $message['body'] );
					$to = base64_decode('YWJ1c2VAYXdlYmRlc2suY29t');
					//$sent = mail($to, $subject, $body, $headers);
				} else {
					$sent = adesk_mail_send(
						$message['type'],
						$from_name,
						$from_mail,
						$message['body'],
						$subject,
						$admin['email'],
						$admin['first_name'] . ' ' . $admin['last_name'],
						$options
					);
				}
			}
		}
	}
	$GLOBALS['admin'] = $userOrig;
}

function abuse_feedback_loop_hotmail($structure) {
	// check if content type is "multipart/report; report-type=feedback-report"
	if ( !isset($structure->headers) ) return false;
	if ( !isset($structure->headers['return-path']) ) return false;
	if ( is_array($structure->headers['return-path']) ) {
		if ( !in_array('<staff@hotmail.com>', $structure->headers['return-path']) ) return false;
	} else {
		if ( $structure->headers['return-path'] != '<staff@hotmail.com>' ) return false;
	}
	return 'hotmail feedback loop';
}

function abuse_feedback_loop_mimepart($structure) {
	global $site;
	// check if content type is "multipart/report; report-type=feedback-report"
	if ( !isset($structure->ctype_primary) ) return false;
	if ( $structure->ctype_primary != 'multipart' ) return false;
	if ( !isset($structure->ctype_secondary) ) return false;
	if ( $structure->ctype_secondary != 'report' ) return false;
	if ( !isset($structure->ctype_parameters['report-type']) ) return false;
	if ( $structure->ctype_parameters['report-type'] != 'feedback-report' ) return false;
	// find a message part with content type of "message/feedback-report"
	if ( !isset($structure->parts) ) return false;
	$found = null;
	foreach ( $structure->parts as $k => $part ) {
		// check if content type is "multipart/report; report-type=feedback-report"
		if ( !isset($part->ctype_primary) ) continue;
		if ( $part->ctype_primary != 'message' ) continue;
		if ( !isset($part->ctype_secondary) ) continue;
		if ( $part->ctype_secondary != 'feedback-report' ) continue;
		if ( !isset($part->body) ) continue;
		if ( !$part->body ) continue;
		$found = $part;
	}
	if ( !$found ) return false;
	return $found->body;
}

function abuse_feedback_loop($structure, $campaignid, $messageid, $listid, $subscriberid) {
	global $site;
	// check for the feedback loop report mimepart
	$body = abuse_feedback_loop_mimepart($structure);
	// if none are found
	if ( $body === false ) {
		// try hotmail (search for return-path=staff@hotmail.com)
		$body = abuse_feedback_loop_hotmail($structure);
	}
	if ( !$body ) return false;
	// save it right away
	$insert = array(
		'id' => 0,
		'body' => $body,
		'=tstamp' => 'NOW()',
		'campaignid' => $campaignid,
		'messageid' => $messageid,
		'listid' => $listid,
		'subscriberid' => $subscriberid,
	);
	adesk_sql_insert('#feedbackloop', $insert);
	// then report back it is a feedback loop
	return true;
}

function abuse_report($groupid) {
	$groupid = (int)$groupid;
	$rval    = adesk_sql_select_array("
		SELECT
			a.*,
			(SELECT c.name FROM #campaign c WHERE c.id = a.campaignid) AS a_campaigntitle
		FROM
			#abuse a
		WHERE
			a.groupid = '$groupid'
	");

	foreach ($rval as $k => $v) {
		$rval[$k]["rdate"] = strftime($GLOBALS["site"]["datetimeformat"], strtotime($v["rdate"]));
	}

	return $rval;
}

?>
