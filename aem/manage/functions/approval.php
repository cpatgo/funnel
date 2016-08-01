<?php

require_once awebdesk_classes("select.php");

#$GLOBALS['subscribers4approval'] = isset($GLOBALS['_hosted_account']) ? 20 : 0;
$GLOBALS['subscribers4approval'] = 0;	# For testing purposes, we need this to ALWAYS generate an approval.

function approval_select_query(&$so) {
	return $so->query("
		SELECT
			a.*,
			( SELECT c.name FROM #campaign c WHERE c.id = a.campaignid ) AS campaignname,
			( SELECT g.title FROM #group g WHERE g.id = a.groupid ) AS groupname,
			( SELECT COUNT(suba.id) FROM #approval suba WHERE suba.userid = a.userid AND suba.approved = 1 ) AS approvals
		FROM
			#approval a
		WHERE
		[...]
	");
}

function approval_select_prepare($row) {
	$row['hash'] = md5($row['id'] . 'acp' . $row['campaignid'] . 'ac' . $row['groupid'] . 'a' . $row['userid']);
	$row['username'] = user_get($row['userid'], true);
	return $row;
}

function approval_select_row($id, $field = 'id') {
	if ( !in_array($field, array('campaignid', 'userid', 'groupid')) ) $field = 'id';
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND a.$field = '$id'");
	if ( $field != 'id' ) {
		//$so->orderby("a.adate DESC");
		$so->orderby("a.approved ASC");
		//$so->orderby("a.approved ASC, a.adate DESC");
	}

	$r = adesk_sql_select_row(approval_select_query($so));
	if ( $r ) $r = approval_select_prepare($r);

	return $r;
}

function approval_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map('intval', $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND a.id IN ('$ids')");
	}
	$rows = adesk_sql_select_array(approval_select_query($so));
	if ( $rows ) {
		foreach ( $rows as $k => $v ) $rows[$k] = approval_select_prepare($v);
	}
	return $rows;
}

function approval_select_array_paginator($id, $sort, $offset, $limit, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;
	$so->push("AND a.approved = 0");

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'approval'");
		$so->push($conds);
	}

	$so->count();
	$total = (int)adesk_sql_select_one(approval_select_query($so));

	switch ($sort) {
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = approval_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function approval_delete($id) {
	$id = intval($id);
	$campaignid = adesk_sql_select_one('campaignid', '#approval', "`id` = '$id'");
	adesk_sql_query("DELETE FROM #approval WHERE id = '$id'");
	approval_delete_relations(array($campaignid));
	return adesk_ajax_api_deleted(_a("Campaign Approval"));
}

function approval_delete_multi($ids) {
	if ($ids == "_all") {
		adesk_sql_query("TRUNCATE TABLE #approval");
		approval_delete_relations(null);
		return;
	}
	$tmp = array_map("intval", explode(",", $ids));
	$ids = implode("','", $tmp);
	$campaignids = adesk_sql_select_list("SELECT campaignid FROM #approval WHERE `id` IN ('$ids')");
	adesk_sql_query("DELETE FROM #approval WHERE id IN ('$ids')");
	approval_delete_relations($campaignids);
	return adesk_ajax_api_deleted(_a("Campaign Approval"));
}

function approval_delete_relations($ids) {
	foreach ( $ids as $id ) {
		campaign_delete($id);
	}
}

function approval_needed($admin = null) {
	if ( isset($GLOBALS['_hosted_account']) ) return false;
	if ( is_null($admin) ) $admin = adesk_admin_get();
	// if approval is not needed
	if ( !$admin['req_approval'] ) return false;
	// if we should approve only the first X
	if ( $admin['req_approval_1st'] > 0 ) {
		if ( $admin['req_approval_1st'] <= approval_count($admin['groups'], 1) ) return false;
	}
	return true;
}

function approval_count($groups = array(), $approved = null) {
	$conds = array();
	if ( count($groups) ) {
		$list = implode("', '", $groups);
		$conds[] = "`groupid` IN ('$list')";
	}
	if ( !is_null($approved) ) {
		$approved = (int)$approved;
		$conds[] = "`approved` = '$approved'";
	}
	return (int)adesk_sql_select_one('=COUNT(*)', '#approval', implode(" AND ", $conds));
}

function approval_add($campaign, $user = null) {
	if ( is_null($user) ) {
		$origAdmin = adesk_admin_get();
		$user = adesk_admin_get_totally_unsafe($campaign['userid']);
		$GLOBALS['admin'] = $origAdmin;
	}
	if ( !isset($user['groups']) ) {
		$user['groups'] = user_get_groups($user['id']);
	}
	$groupid = current($user['groups']);
	if ( adesk_sql_select_one("=COUNT(*)", "#approval", "campaignid = '$campaign[id]' AND approved = 0") ) {
		/* it should NEVER get here, but somehow it does! */
		//adesk_sql_update("#approval", array('approved' => 0, '=adate' => 'NULL'), "campaignid = '$campaign[id]'");
		return false;
	} else {
		adesk_sql_delete("#approval", "campaignid = '$campaign[id]'");
		$insert = array(
			'id' => 0,
			'campaignid' => $campaign['id'],
			'userid' => $user['id'],
			'groupid' => $groupid,
			'approved' => 0,
			'adminid' => 0,
			'=sdate' => 'NOW()',
			'=adate' => 'NULL',
		);
		$done = adesk_sql_insert('#approval', $insert);
		if ( !$done ) {
			return false;
		}
		$id = (int)adesk_sql_insert_id();
	}
	return $id;
}

function approval_notify($campaign, $user = null) {
	global $site;
	require_once(awebdesk_functions('mail.php'));
	if ( is_null($user) ) {
		$origAdmin = adesk_admin_get();
		$user = adesk_admin_get_totally_unsafe($campaign['userid']);
		$GLOBALS['admin'] = $origAdmin;
	}

	// checks if there's no approval || notification involved
	if ( !$user['req_approval'] ) return 0;
	if ( !$user['req_approval_notify'] ) return 0;

	// email sending vars
	$from_name = $site['site_name'];
	$from_mail = $site['emfrom'];
	$subject = _a('A new campaign needs approval in order to be sent');
	$recipients = $user['req_approval_notify'];
	$to_name = _a('Campaign Approval Admin');

	$approval = approval_select_row($campaign['id'], 'campaignid');

	// campaign view & approve link
	$approvelink = adesk_site_plink("index.php?action=approve&a=$approval[id]&c=$campaign[id]&h=$approval[hash]");

	// call smarty to make an e-mail body
	$vars = array(
		'campaign' => $campaign,
		'user' => $user,
		'approvelink' => $approvelink,
	);
	$message = adesk_mail_prepare('campaign_approval', $vars);

	// send an email
	if ( isset($GLOBALS['_hosted_account']) ) {
		require(dirname(dirname(__FILE__)) . '/manage/approve.add.inc.php');
	} else {
		$options = array();
		$options['userid'] = $user['id'];
		$sent = adesk_mail_send($message['type'], $from_name, $from_mail, $message['body'], $subject, $recipients, $to_name, $options);
	}
	return $sent;
}

function approval_approve($approvalid, $hash) {
	global $admin;

	// get the approval
	$approval = approval_select_row($approvalid);
	if ( !$approval or $approval['approved'] or $approval['hash'] != $hash ) {
		return adesk_ajax_api_result(false, _a("Campaign Approval not found."));
	}

	// get the campaign
	$campaign = campaign_select_row($approval['campaignid']);
	if ( !$campaign ) {
		return adesk_ajax_api_result(false, _a("Campaign not found."));
	}

	// update the approval
	$ary = array(
		'approved' => 1,
		'adminid' => $admin['id'],
		'=adate' => 'NOW()',
	);
	$sql = adesk_sql_update("#approval", $ary, "id = '$approvalid'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Campaign Approval could not be approved."));
	}

	if (isset($GLOBALS["_hosted_account"])) {
		require adesk_admin("manage/approval.addrwhitelist.php");
	}

	// hard set the campaign status back to sending
	adesk_sql_update_one('#campaign', 'status', 2, "id = '$campaign[id]'");

	$campaign['processid'] = campaign_processid($campaign['id'], 'active');

	// try to spawn it
	if ( $campaign['processid'] ) {
		require_once awebdesk_functions("process.php");

		adesk_sql_query("UPDATE #campaign SET ldate = ldate - INTERVAL 15 MINUTE WHERE id = '$campaign[id]'");
		adesk_sql_query("UPDATE #process SET ldate = ldate - INTERVAL 15 MINUTE WHERE id = '$campaign[processid]'");

		$process = adesk_process_get($campaign['processid']);
		adesk_process_spawn($process);
	}

	return adesk_ajax_api_result(true, _a("Campaign Approved."));
}

function approval_decline_only($approvalid, $hash) {
	global $admin;

	// get the approval
	$approval = approval_select_row($approvalid);
	if ( !$approval or $approval['approved'] or $approval['hash'] != $hash ) {
		return adesk_ajax_api_result(false, _a("Campaign Approval not found."));
	}

	// get the campaign
	$campaign = campaign_select_row($approval['campaignid']);
	if ( !$campaign ) {
		return adesk_ajax_api_result(false, _a("Campaign not found."));
	}

	// remove the approval row
	adesk_sql_delete("#approval", "`id` = '$approvalid'");

	// re-draft the campaign
	$sql = adesk_sql_update_one('#campaign', 'status', 0, "`id` = '$campaign[id]'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Campaign could not be declined."));
	}

	$sql = adesk_sql_update_one('#campaign', 'laststep', 'summary', "`id` = '$campaign[id]'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Campaign could not be declined."));
	}

	// remove the process
	$campaign['processid'] = campaign_processid($campaign['id'], 'active');
	adesk_process_remove($campaign['processid']);

	// try to drop the temporary table
	$table = adesk_prefix('x' . $campaign['sendid']);
	adesk_sql_query("DROP TABLE IF EXISTS `$table`");

	// remove the count row
	adesk_sql_delete("#campaign_count", "processid = '$campaign[processid]'");

	return adesk_ajax_api_result(true, _a("Campaign declined."));
}

function approval_decline() {
	global $site;

	$approvalid = (int)adesk_http_param('id');
	$hash = (string)adesk_http_param('hash');
	$from_mail = adesk_http_param('from_mail');
	$from_name = adesk_http_param('from_name');
	$to_mail = adesk_http_param('to_mail');
	$to_name = adesk_http_param('to_name');
	$subject = adesk_http_param('subject');
	$message = adesk_http_param('message');

	// get approval
	$approval = approval_select_row($approvalid);
	if ( !$approval or $approval['approved'] or $approval['hash'] != $hash ) {
		return adesk_ajax_api_result(false, _a("Campaign Approval not found."));
	}

	// get the campaign
	$campaign = campaign_select_row($approval['campaignid']);
	if ( !$campaign ) {
		return adesk_ajax_api_result(false, _a("Campaign not found."));
	}

	// remove the approval row
	adesk_sql_delete("#approval", "`id` = '$approvalid'");

	// re-draft the campaign
	$sql = adesk_sql_update_one('#campaign', 'status', 0, "`id` = '$approval[campaignid]'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Campaign could not be declined."));
	}

	$sql = adesk_sql_update_one('#campaign', 'laststep', 'summary', "`id` = '$campaign[id]'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Campaign could not be declined."));
	}

	// remove the process
	$campaign['processid'] = campaign_processid($campaign['id'], 'active');
	adesk_process_remove($campaign['processid']);

	// try to drop the temporary table
	$table = adesk_prefix('x' . $campaign['sendid']);
	adesk_sql_query("DROP TABLE IF EXISTS `$table`");

	// remove the count row
	adesk_sql_delete("#campaign_count", "processid = '$campaign[processid]'");

	$options = array();
	$options['userid'] = 1;

	// notify admin
	if ( $to_mail ) {
		$sent = adesk_mail_send('text', $from_name, $from_mail, $message, $subject, $to_mail, $to_name, $options);
		if ( !$sent ) {
			return adesk_ajax_api_result(false, _a("Campaign was declined, but creator was not notified."));
		}
	}

	return adesk_ajax_api_result(true, _a("Campaign Declined."));
}

if (isset($GLOBALS["_hosted_account"])) {
	require_once adesk_admin("manage/approval.php");
}

?>
