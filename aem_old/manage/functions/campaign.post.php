<?php

define("CAMPAIGN_STATUS_DRAFT", 0);
define("CAMPAIGN_STATUS_SCHEDULED", 1);
define("CAMPAIGN_STATUS_SENDING", 2);
define("CAMPAIGN_STATUS_PAUSED", 3);
define("CAMPAIGN_STATUS_STOPPED", 4);
define("CAMPAIGN_STATUS_COMPLETED", 5);
define("CAMPAIGN_STATUS_DISABLED", 6);
define("CAMPAIGN_STATUS_PENDING_APPROVAL", 7);

require_once awebdesk_functions("log.php");

function campaign_nextid() {
	return (int)adesk_sql_select_one("SELECT maxcampaignid FROM #backend LIMIT 1") + 1;
}

function campaign_updatenextid($newmax) {
	$newmax = (int)$newmax;
	adesk_sql_query("UPDATE #backend SET maxcampaignid = '$newmax'");
}

function campaign_insert_post() {
	$admin = adesk_admin_get();

	if (isset($_GLOBALS["_hosted_account"])) {
		if ($_SESSION[$GLOBALS["domain"]]["down4"] != "nobody")
			return;
	}

	// find basic campaign info
	$data = campaign_prepare_post();

	$data['ary']['id'] = campaign_nextid();
	$data['ary']['=ip4']   = "INET_ATON('$_SERVER[REMOTE_ADDR]')";
	$data['ary']['=cdate'] = 'NOW()';
	$data['ary']['userid'] = $admin['id'];
	$data['ary']['source'] = 'web';

	// insert the campaign
	$sql = adesk_sql_insert("#campaign", $data['ary']);
	if ( !$sql ) {
		return adesk_ajax_api_result(false, adesk_sql_error());
		return adesk_ajax_api_result(false, _a("Campaign could not be added."));
	}

	$id = adesk_sql_insert_id();
	campaign_updatenextid($id);

	if (isset($GLOBALS["_hosted_account"])) {
		adesk_log("Logging IP address in campaign_insert_post: ip=$_SERVER[REMOTE_ADDR] campaignid=$id");
	}

	if (isset($data['actionid']) && $data['actionid'] > 0) {
		$campname = $data['ary']['name'];
		$actname  = adesk_sql_escape(sprintf("%s - %s", $campname, _a("read")));
		adesk_sql_query("UPDATE #subscriber_action SET name = '$actname', campaignid = '$id', linkid = 0 WHERE id = '$data[actionid]'");
	}



	// insert list relations
	foreach ( $data['lists'] as $l ) {
		if ( $l > 0 ) {
			$arr = array(
				'id' => 0,
				'campaignid' => $id,
				'listid' => $l,
				'userid' => $admin['id'],
				'list_amt' => 0,
			);
			adesk_sql_insert('#campaign_list', $arr);
		}
	}
	// copy mailer info
	$methodslist = implode("','", $admin['methods']);
	adesk_sql_query("
		INSERT INTO
			#campaign_mailer
		(
			id, campaignid, mailerid
		)
			SELECT
				0 AS `id`,
				$id AS `campaignid`,
				`id` AS `mailerid`
			FROM
				#mailer
			WHERE
				`id` IN ('$methodslist')
	");

	// insert message relations
	foreach ( $data['messages'] as $m ) {
		if ( $m > 0 ) {
			foreach ( $data['lists'] as $l ) {
				if ( $l = (int)$l && !adesk_sql_select_one('=COUNT(*)', '#message_list', "`messageid` = '$m' AND `listid` = '$l'") ) {
					$arr = array(
						'id' => 0,
						'messageid' => $m,
						'listid' => $l,
						'userid' => $admin['id'],
					);
					adesk_sql_insert('#message_list', $arr);
				}
			}
			$arr = array(
				'id' => 0,
				'campaignid' => $id,
				'messageid' => $m,
				'percentage' => isset($data['ratios'][$m]) ? $data['ratios'][$m] : 0,
				'sourcesize' => 0,
			);
			adesk_sql_insert('#campaign_message', $arr);
		}
	}

	// insert links relations
	foreach ( $data['links'] as $k => $l ) {
		if ( $l != '' ) {
			$ref = '';
			if ( adesk_str_instr('/index.php?action=social&c=', $l) || adesk_str_instr('/we.php?c=', $l) ) {
				if ( adesk_str_instr('&facebook=like', $l) ) {
					// if it's the facebook "like" link, we consider it the same as facebook share link (as far as "ref" that is)
					$ref = "facebook";
				}
				// find ref
				// match any occurrence of "&ref=whatever" or "&referral=whatever"
				$param_str = preg_match("/&ref[a-z]*=[a-z]+/", $l, $matches);
				if ( isset($matches[0]) ) list(,$ref) = explode('=', $matches[0]);
			}
			$arr = array(
				'id' => 0,
				'campaignid' => $id,
				'messageid' => $data['linkmessages'][$k],
				'link' => message_link_internal($l),
				'name' => $data['linknames'][$k],
				'ref' => $ref,
				'tracked' => 1,
			);
			if ( adesk_sql_insert('#link', $arr) && $k >= 0 ) {
				$linkid = (int)adesk_sql_insert_id();
				$actid  = (int)$data['linkactions'][$k];
				if ($actid > 0) {
					$campname = $data['ary']['name'];
					$actname  = adesk_sql_escape(sprintf("%s - %s (%s)", $campname, _a("link"), $l));
					adesk_sql_query("UPDATE #subscriber_action SET name = '$actname', campaignid = '$id', linkid = '$linkid' WHERE id = '$actid'");
				}
				/* (REMOVEME)
				foreach ( $data['actions'][$k] as $a ) {
					$arr = array(
						'id' => 0,
						'linkid' => $linkid,
						'action' => $a['action'],
						'value' => $a['value'],
					);
					adesk_sql_insert('#link_action', $arr);
				}
				 */
			}
		}
	}

	// if reached the end of wizard
	if ( adesk_http_param('final') ) {
		// send now?
		$sendnow = ( in_array($data['ary']['type'], array('single', /*'recurring',*/ 'split', 'text')) && $data['ary']['sdate'] <= (string)adesk_sql_select_one("SELECT NOW()") );
		if ( $sendnow ) {
			campaign_init($id, false);
		} else {
			// set campaign status
			adesk_sql_update_one('#campaign', 'status', CAMPAIGN_STATUS_SCHEDULED, "id = '$id'");
			// if responder, try to deal with old subscribers (trigger a new campaign)
			if ( $data['ary']['type'] == 'responder' and adesk_http_param('responder_do_oldies') ) {
				$oldies = adesk_http_param('respondold');
				if ( $oldies != 'no' ) campaign_responder_oldies($id);
			}
		}

		if ( isset($GLOBALS['_hosted_account']) ) {
			require(dirname(dirname(__FILE__)) . '/manage/campaign.add.inc.php');
		}

		return adesk_ajax_api_saved(_a("Campaign"), array('id' => $id));
	} else {
		//return adesk_ajax_api_autosaved(_a("Campaign"), array('id' => $id));
		return adesk_ajax_api_saved(_a("Campaign"), array('id' => $id));
	}
}

function campaign_update_post() {
	if ( !isset($_POST["id"]) ) {
		return adesk_ajax_api_result(false, _a("Campaign not provided."));
	}
	$id = intval($_POST["id"]);
	$admin = adesk_admin_get();

	// find basic campaign info
	$data = campaign_prepare_post();
	$data['ary']['source'] = 'web';

	$sql = adesk_sql_update("#campaign", $data['ary'], "id = '$id'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Campaign could not be updated."));
	}

	if (isset($data['actionid']) && $data['actionid'] > 0)
		adesk_sql_query("UPDATE #subscriber_action SET campaignid = '$id', linkid = 0 WHERE id = '$data[actionid]'");

	// delete old that are now deselected
	$c = campaign_select_row($id, true, true, true);
	if ( $c ) {
		foreach ( $c['lists'] as $v ) {
			if ( !in_array($v['id'], $data['lists']) ) {
				adesk_sql_delete('#campaign_list', "campaignid = '$id' AND listid = '$v[id]'");
			}
		}
		foreach ( $c['messages'] as $v ) {
			if ( !in_array($v['id'], $data['messages']) ) {
				adesk_sql_delete('#campaign_message', "campaignid = '$id' AND messageid = '$v[id]'");
			}
		}
		foreach ( $c['tlinks'] as $v ) {
			if ( !in_array($v['link'], $data['links']) ) {
				adesk_sql_delete('#link', "id = '$v[id]'");
				adesk_sql_delete('#link_action', "linkid = '$v[id]'");
			}
		}
	}

	// insert list relations
	foreach ( $data['lists'] as $l ) {
		if ( $l > 0 ) {
			$exists = adesk_sql_select_one('=COUNT(*)', '#campaign_list', "campaignid = '$id' AND listid = '$l'");
			if ( !$exists ) {
				$arr = array(
					'id' => 0,
					'campaignid' => $id,
					'listid' => $l,
					'userid' => $admin['id'],
					'list_amt' => 0,
				);
				adesk_sql_insert('#campaign_list', $arr);
			}
		}
	}
	// copy mailer info - done during insert, no editing of these

	// insert message relations
	foreach ( $data['messages'] as $m ) {
		if ( $m > 0 ) {
			foreach ( $data['lists'] as $l ) {
				if ( $l = (int)$l && !adesk_sql_select_one('=COUNT(*)', '#message_list', "`messageid` = '$m' AND `listid` = '$l'") ) {
					$arr = array(
						'id' => 0,
						'messageid' => $m,
						'listid' => $l,
						'userid' => $admin['id'],
					);
					adesk_sql_insert('#message_list', $arr);
				}
			}
			$exists = adesk_sql_select_one('=COUNT(*)', '#campaign_message', "campaignid = '$id' AND messageid = '$m'");
			if ( $exists ) {
				adesk_sql_update_one('#campaign_message', 'percentage', $data['ratios'][$m], "campaignid = '$id' AND messageid = '$m'");
			} else {
				$arr = array(
					'id' => 0,
					'campaignid' => $id,
					'messageid' => $m,
					'percentage' => $data['ratios'][$m],
					'sourcesize' => 0,
				);
				adesk_sql_insert('#campaign_message', $arr);
			}
		}
	}

	// insert links relations
	foreach ( $data['links'] as $k => $l ) {
		if ( $l != '' ) {
			$esc = adesk_sql_escape(message_link_internal($l));
			$linkid = (int)adesk_sql_select_one('id', '#link', "campaignid = '$id' AND messageid = '{$data['linkmessages'][$k]}' AND link = '$esc'");
			if ( $linkid > 0 ) {
				adesk_sql_update_one('#link', 'name', $data['linknames'][$k], "id = '$linkid'");
				// remove all old actions first so we can re-add them
				adesk_sql_delete('#link_action', "linkid = '$linkid'");
			} else {
				$ref = '';
				if ( adesk_str_instr('/index.php?action=social&c=', $l) ) {
					if ( adesk_str_instr('&ref=', $l) ) list(,$ref) = explode('&ref=', $l);
				}
				$arr = array(
					'id' => 0,
					'campaignid' => $id,
					'messageid' => $data['linkmessages'][$k],
					'link' => message_link_internal($l),
					'name' => $data['linknames'][$k],
					'ref' => $ref,
					'tracked' => 1,
				);
				$sql = adesk_sql_insert('#link', $arr);
				if ($sql) {
					$linkid = (int)adesk_sql_insert_id();
					// sometimes $k is -1, and that key doesn't exist in $data['linkactions'], so check before we assign it below
					$actid  = ( isset($data['linkactions'][$k]) ) ? (int)$data['linkactions'][$k] : 0;
					if ($actid > 0)
						adesk_sql_query("UPDATE #subscriber_action SET campaignid = '$id', linkid = '$linkid' WHERE id = '$actid'");
				}
			}
		}
	}

	// clear all message sources for this campaign
	campaign_source_clear($id, null, null);

	// if reached the end of wizard
	if ( adesk_http_param('final') ) {
		// send now?
		$sendnow = ( in_array($data['ary']['type'], array('single', /*'recurring',*/ 'split', 'text')) && $data['ary']['sdate'] <= (string)adesk_sql_select_one("SELECT NOW()") );
		if ( $sendnow ) {
			campaign_init($id, false);
		} else {
			// set campaign status
			adesk_sql_update_one('#campaign', 'status', CAMPAIGN_STATUS_SCHEDULED, "id = '$id'");
			// if responder, try to deal with old subscribers (trigger a new campaign)
			if ( $data['ary']['type'] == 'responder' and adesk_http_param('responder_do_oldies') ) {
				$oldies = adesk_http_param('respondold');
				if ( $oldies != 'no' ) campaign_responder_oldies($id);
			}
		}
		return adesk_ajax_api_saved(_a("Campaign"), array('id' => $id));
	} else {
		//return adesk_ajax_api_autosaved(_a("Campaign"), array('id' => $id));
		return adesk_ajax_api_saved(_a("Campaign"), array('id' => $id));
	}
}

function campaign_create() {
	if (isset($_GLOBALS["_hosted_account"])) {
		if ($_SESSION[$GLOBALS["domain"]]["down4"] != "nobody")
			return;
	}

	// gather important data
	if ( !isset($_POST['p']) ) $_POST['p'] = array();
	$lists = array_diff(array_map('intval', (array)$_POST['p']), array(0));
	$messages = array_diff(array_map('intval', (array)adesk_http_param('m')), array(0));
	$methods = array_diff(array_map('intval', (array)adesk_http_param('s')), array(0));
	$links = array_map('adesk_b64_decode', (array)adesk_http_param('linkurl'));
	$linknames = (array)adesk_http_param('linkname');
	$linkmessages = (array)adesk_http_param('linkmessage');

	if ( is_array($messages) and isset($messages[0]) ) {
		if ( count($messages) == 1 ) {
			$messages = array($messages[0] => 100);
		} else {
			$tmp = array();
			foreach ( $messages as $k => $v ) $tmp[$v] = 100 / count($messages);
			$messages = $tmp;
		}
	}

	foreach ($methods as $mailerid) {
		if (!awebdesk_exists("#mailer", $mailerid))
			return adesk_ajax_api_result(false, _a("Invalid mailer id in s parameter"));
	}

	foreach ($messages as $messageid => $ratio) {
		if (!awebdesk_exists("#message", $messageid))
			return adesk_ajax_api_result(false, _a("Invalid message id in m parameter"));
	}

	foreach ($lists as $listid) {
		if (!awebdesk_exists("#list", $listid))
			return adesk_ajax_api_result(false, _a("Invalid list id in p parameter"));
	}

	// obtain links from message (you should not have to pass them individually anymore)
	foreach ( $messages as $k => $v ) {
		$message = message_select_row($k, implode(',', $lists));
		if ( $message ) {
			foreach ($message["links"] as $link_row) {
				// if not already in array of links parameter, add it
				if ( !in_array($link_row["link"], $links) ) {
					$links[] = $link_row["link"];
					$linknames[] = $link_row["title"];
					$linkmessages[] = $k;
				}
			}
		}
	}

	$admin = $GLOBALS['admin'];

	// fetch campaign info
	$blank = adesk_sql_default_row('#campaign');
	$blank_row = array();
	foreach ($blank as $field => $value) {
		if ($field != 'ip4') {
			$blank_row[$field] = $value;
		}
	}
	$blank = $blank_row;
	$blank["=ip4"] = "INET_ATON('$_SERVER[REMOTE_ADDR]')";
	foreach ( $blank as $k => $v ) {
		if ( $k != 'id' ) {
			if ( adesk_http_param_exists($k) ) {
				$val = adesk_http_param($k);
				if ( preg_match('/^\d+$/', $v) and !preg_match('/^\d+$/', $val) ) {
					$val = (int)$val;
				}
				if ($k == "tracklinks" && $val == "all") $val = "mime";
				$blank[$k] = $val;
			}
		}
	}
	if ( isset($GLOBALS['_hosted_account']) ) {
		$blank['bounceid'] = -1;
	}

	$blank['bounceid'] = (int)$blank['bounceid'];
	$blank['filterid'] = (int)$blank['filterid'];

	if ($blank["filterid"] > 0 && !awebdesk_exists("#filter", $blank["filterid"]))
		return adesk_ajax_api_result(false, _a("Invalid filterid"));
	if ($blank["bounceid"] > 0 && !awebdesk_exists("#bounce_management", $blank["bounceid"]))
		return adesk_ajax_api_result(false, _a("Invalid bounceid"));
	if ( !count($messages) ) {
	  return adesk_ajax_api_result(false, _a("Campaign requires a message ID."));
	}
	if ( $blank['type'] == 'split' ) {
		if ( count($messages) < 2 ) {
			return adesk_ajax_api_result(false, _a("Split test campaigns need at least two messages."));
		}
	} else {
		if ( count($messages) > 1 ) {
			return adesk_ajax_api_result(false, _a("Only split test campaigns can be sent with more than one message."));
		}
	}

	$blank["source"] = "api";
	$blank["userid"] = $admin["id"];
	$blank["cdate"] = adesk_CURRENTDATETIME;
	$d = @strtotime($blank['sdate']);
	if ( !$d or $d == -1 ) {
		return adesk_ajax_api_result(false, _a('Invalid send date provided.'));
	} else {
		$blank['sdate'] = date("Y-m-d H:i:s", $d);
	}
	if ( !isset($blank['ldate']) || !$blank['ldate'] ) {
		unset($blank['ldate']);
		$blank['=ldate'] = 'NULL';
	}
	if ( !$blank['reminder_last_cron_run'] ) {
		unset($blank['reminder_last_cron_run']);
		$blank['=reminder_last_cron_run'] = 'NULL';
	}
	/*
		CHECKING FOR RELATIONS
	*/
	// if no lists, dont even set it as draft -- this is not allowed
	if ( !count($lists) ) {
		return adesk_ajax_api_result(false, _a('You did not provide any lists.'));
		$blank['status'] = 0;
	}
	/*
		CHECKING FOR ENUMS
	*/
	$wl = array('single', 'recurring', 'split', 'responder', 'reminder', 'special', 'deskrss', 'text');
	if ( !in_array($blank['type'], $wl) ) {
		return adesk_ajax_api_result(false, _a('You did not provide a valid campaign type.'));
	}
	if ( !in_array($blank['tracklinks'], array('mime', 'html', 'text')) ) $blank['tracklinks'] = 'none';
	if ( $blank['type'] == 'simple' ) {
		// simple campaign
	} elseif ( $blank['type'] == 'recurring' ) {
		$wl = array(/*'hour0', 'hour1', 'hour2', 'hour6', 'hour12',*/ 'day1', 'day2', 'week1', 'week2', 'month1', 'month2', 'quarter1', 'quarter2', 'year1', 'year2');
		if ( !in_array($blank['recurring'], $wl) ) {
			return adesk_ajax_api_result(false, _a('You did not provide a valid recurring interval.'));
		}
	} elseif ( $blank['type'] == 'split' ) {
		$wl = array('even', 'read', 'click');
		if ( !in_array($blank['split_type'], $wl) ) {
			return adesk_ajax_api_result(false, _a('You did not provide a valid split campaign type.'));
		}
		$wl = array('hour', 'day', 'week', 'month');
		if ( !in_array($blank['split_offset_type'], $wl) ) {
			return adesk_ajax_api_result(false, _a('You did not provide a valid duration for split winner calculation.'));
		}
	} elseif ( $blank['type'] == 'responder' ) {
		//if ( $blank['responder_type'] != 'unsubscribe' ) $data['responder_type'] = 'subscribe';
	} elseif ( $blank['type'] == 'reminder' ) {
		$wl = array('month_day', 'year_month_day');
		if ( !in_array($blank['reminder_type'], $wl) ) {
			return adesk_ajax_api_result(false, _a('You did not provide a valid format for a field needed for auto-reminders.'));
		}
		$wl = array('day', 'week', 'month', 'year');
		if ( !in_array($blank['reminder_offset_type'], $wl) ) {
			return adesk_ajax_api_result(false, _a('You did not provide a valid offset type for auto-reminders.'));
		}
		$wl = array('+', '-');
		if ( !in_array($blank['reminder_offset_sign'], $wl) ) {
			return adesk_ajax_api_result(false, _a('You did not provide a valid offset sign for auto-reminders.'));
		}
	} elseif ( $blank['type'] == 'special' ) {
	} elseif ( $blank['type'] == 'deskrss' ) {
		$wl = array('hour0', 'hour1', 'hour2', 'hour6', 'hour12', 'day1', 'day2', 'week1', 'week2', 'month1', 'month2', 'quarter1', 'quarter2', 'year1', 'year2');
		if ( !in_array($blank['deskrss_interval'], $wl) ) {
			return adesk_ajax_api_result(false, _a('You did not provide a valid recurring interval.'));
		}
	}
	/*
		INSERT NEW CAMPAIGN
	*/
	$blank["id"] = campaign_nextid();
	$done = adesk_sql_insert("#campaign", $blank);
	if ( !$done ) {
		return adesk_ajax_api_result(false, _a('Campaign could not be added.'), array('sqlerror' => adesk_sql_error()));
	}
	$id = (int)adesk_sql_insert_id();
	campaign_updatenextid($id);

	// insert lists
	foreach ( $lists as $l ) {
		if ( $l = (int)$l ) {
			$arr = array(
				'id' => 0,
				'campaignid' => $id,
				'listid' => $l,
				'userid' => $admin['id'],
				'list_amt' => 0,
			);
			adesk_sql_insert('#campaign_list', $arr);
		}
	}
	// copy mailer info
	$methodslist = implode("','", $admin['methods']);
	adesk_sql_query("
		INSERT INTO
			#campaign_mailer
		(
			id, campaignid, mailerid
		)
			SELECT
				0 AS `id`,
				$id AS `campaignid`,
				`id` AS `mailerid`
			FROM
				#mailer
			WHERE
				`id` IN ('$methodslist')
	");

	// insert messages
	foreach ( $messages as $k => $v ) {
		if ( $k = (int)$k ) {
			foreach ( $lists as $l ) {
				if ( $l = (int)$l && !adesk_sql_select_one('=COUNT(*)', '#message_list', "`messageid` = '$k' AND `listid` = '$l'") ) {
					$arr = array(
						'id' => 0,
						'messageid' => $k,
						'listid' => $l,
						'userid' => $admin['id'],
					);
					adesk_sql_insert('#message_list', $arr);
				}
			}
			if ( $blank['type'] != 'split' or $blank['split_type'] == 'even' ) $v = 100;
			$arr = array(
				'id' => 0,
				'campaignid' => $id,
				'messageid' => $k,
				'percentage' => (float)$v,
				'sourcesize' => 0,
			);
			adesk_sql_insert('#campaign_message', $arr);
			if ( $blank['type'] != 'split' or $blank['split_type'] == 'even' ) break;
		}
	}

	// insert links
	if ( (int)adesk_http_param('trackreads') && !in_array('open', $links) ) {
		// add "read" link
		//$linkactions[] = 0;
		$links[] = 'open';
		$linknames[] = '';
		$linkmessages[] = 0;
	}
	foreach ( $links as $k => $l ) {
		//if link exists (aint empty)
		if ( $l != '' ) {
			$ref = '';
			if ( adesk_str_instr('/index.php?action=social&c=', $l) ) {
				if ( adesk_str_instr('&ref=', $l) ) list(,$ref) = explode('&ref=', $l);
			}
			$arr = array(
				'id' => 0,
				'campaignid' => $id,
				'messageid' => $linkmessages[$k],
				'link' => message_link_internal($l),
				'name' => $linknames[$k],
				'ref' => $ref,
				'tracked' => 1,
			);
			if ( adesk_sql_insert('#link', $arr) ) {
				$linkid = (int)adesk_sql_insert_id();
				/*
				$actid  = (int)$linkactions[$k];
				if ($actid > 0) {
					$campname = $data['ary']['name'];
					$actname  = adesk_sql_escape(sprintf("%s - %s (%s)", $campname, _a("link"), $l));
					adesk_sql_query("UPDATE #subscriber_action SET name = '$actname', campaignid = '$id', linkid = '$linkid' WHERE id = '$actid'");
				}
				*/
				/* (REMOVEME)
				foreach ( $actions[$k] as $a ) {
					$arr = array(
						'id' => 0,
						'linkid' => $linkid,
						'action' => $a['action'],
						'value' => $a['value'],
					);
					adesk_sql_insert('#link_action', $arr);
				}
				 */
			}
		}
	}

	// send now?
	$sendnow = ( in_array($blank['type'], array('single', /*'recurring',*/ 'split', 'text')) && $blank['sdate'] <= (string)adesk_sql_select_one("SELECT NOW()") && $blank['status'] );
	if ( $sendnow ) {
		campaign_init($id, false);
	} else {
		// set campaign status
		$status = (int)adesk_http_param("status");

		if ($status !== 1)
			$status = 0;

		adesk_sql_update_one('#campaign', 'status', $status, "id = '$id'");
		// if responder, try to deal with old subscribers (trigger a new campaign)
		if ( $status and $blank['type'] == 'responder' and adesk_http_param('responder_do_oldies') ) {
			$oldies = adesk_http_param('respondold');
			if ( $oldies != 'no' ) campaign_responder_oldies($id);
		}
	}

	if ( isset($GLOBALS['_hosted_account']) ) {
		require(dirname(dirname(__FILE__)) . '/manage/campaign.add.inc.php');
	}

	return adesk_ajax_api_saved(_a("Campaign"), array('id' => $id));
}

function campaign_prepare_post() {
	$r = array();
	// find basic campaign info
	$r['ary'] = campaign_prepare_post_ary();
	$r['actionid'] = adesk_http_param("actionid");

	// find parents
	$r['lists'] = array();
	$p = adesk_http_param('p');
	if ( is_array($p) and count($p) > 0 ) {
		$r['lists'] = array_map('intval', $p);
	} else {
		$r["lists"] = array((int)adesk_http_param("p"));
	}

	// find messages
	$r['messages'] = array();
	$r['ratios'] = array();
	$messageid = adesk_http_param('messageid');
	$splitratio = adesk_http_param('splitratio');
	if ( is_array($messageid) ) {
		$r['messages'] = array_map('intval', $messageid);
	} else {
		if ( (int)$messageid > 0 ) {
			$r['messages'] = array((int)$messageid);
		}
	}
	if ( count($r['messages']) > 0 ) {
		if ( $r['ary']['type'] == 'split' ) {
			if ( $r['ary']['split_type'] != 'even' and is_array($splitratio) /*and count($splitratio) == count($r['messages'])*/ ) {
				$r['ratios'] = $splitratio;
			} else {
				$perc = round(100 / count($r['messages']));
				foreach ( $r['messages'] as $m ) {
					$r['ratios'][$m] = $perc;
				}
				// complement
				$r['ratios'][$m] += 100 - ( round(100 / count($r['messages'])) * count($r['messages']) );
			}
		} else {
			$r['ratios'][$r['messages'][0]] = 100;
		}
	}

	// find links and actions
	$r['linkactions'] = array();
	$r['links'] = array();
	$r['linknames'] = array();
	$r['linkmessages'] = array();
	$linkurl = adesk_http_param('linkurl');
	$linkname = adesk_http_param('linkname');
	$linkmessage = adesk_http_param('linkmessage');
	$linkactions = adesk_http_param("linkaction");
	if ( $r['ary']['tracklinks'] != 'none' || $r['ary']['tracklinksanalytics'] ) {
		if ( is_array($linkurl) and count($linkurl) > 0 ) {
			$r['links'] = array_map('trim', array_map('adesk_b64_decode', $linkurl));
			if ( is_array($linkname) ) {
				$r['linknames'] = $linkname;
			}
			if ( is_array($linkmessage) ) {
				$r['linkmessages'] = $linkmessage;
			}
			if ( is_array($linkactions) ) {
				$r['linkactions'] = $linkactions;
			}
		}
	}
	if ( $r['ary']['trackreads'] and !in_array('open', $r['links']) ) {
		// add "read" link
		$r['linkactions'][] = 0;
		$r['links'][] = 'open';
		$r['linknames'][] = '';
		$r['linkmessages'][] = 0;
	}

	foreach ( $r['messages'] as $mid ) {
		$message = message_select_row($mid, implode(',', $r['lists']));
		if ( !$message ) continue;
		foreach ( $message['links'] as $link ) {
			// check if already added
			foreach ( $r['links'] as $k => $v ) {
				if ( $v == $link['link'] and $mid == $r['linkmessages'][$k] ) {
					// found already for this message
					continue(2);
				}
			}
			if (
				( (string)adesk_http_param('tracklinks') == 'all' and count($r['links']) == $r['ary']['trackreads'] )
			)
			{
				$r['linkactions'][] = 0;
				$r['links'][] = $link['link'];
				$r['linknames'][] = '';
				$r['linkmessages'][] = $mid;
			}
		}
	}

	/*
	if ( $r['ary']['trackreads'] != 'none' || $r['ary']['trackreadsanalytics'] ) {
		$r['links'][] = 'open';
		if ( is_array($linkurl) and count($linkurl) > 0 ) {
			$r['links'] = array_map('trim', array_map('adesk_b64_decode', $linkurl));
			if ( is_array($actions) ) {
				foreach ( $actions as $k => $v ) {
					$r['actions'][$k] = array();
					if ( $v != '' ) {
						$arr1 = explode('|**|', $v);
						foreach ( $arr1 as $a ) {
							$arr2 = array();
							list($arr2['action'], $arr2['value']) = explode('*||*', $a);
							$r['actions'][$k][] = $arr2;
						}
					}
				}
			}
			if ( is_array($linkname) ) {
				$r['linknames'] = $linkname;
			}
			if ( is_array($linkmessage) ) {
				$r['linkmessages'] = $linkmessage;
			}
		}
	}
	*/
	return $r;
}

function campaign_prepare_post_ary() {
	$step = (int)adesk_http_param('step');
	// filter id
	$filterid = (int)adesk_http_param('filterid');
	if ( !adesk_http_param_exists('usefilter') ) $filterid = 0;
	// bounce id
	$bounceid = (int)adesk_http_param('bounceid');
	if ( !adesk_http_param_exists('usebounce') ) $bounceid = -1;
	if ( isset($GLOBALS['_hosted_account']) ) {
		$bounceid = -1;
	}
	// send date
	$sdate = adesk_http_param('sdate');
	# Fix up the dates so that they match the MySQL format.
	$sdate = strtotime($sdate);
	$sdate -= (adesk_date_offset_hour() * 3600);
	$sdate = strftime("%Y-%m-%d %H:%M:%S", $sdate);
	// link tracking
	$trackreads = (int)adesk_http_param_exists('trackreads');
	$tracklinks = (string)adesk_http_param('tracklinks');
	if ( $tracklinks != 'none' ) {
		if ( $step < 4 ) {
			// any links found
			$links = adesk_http_param('linkurl');
			if ( !is_array($links) or count($links) == 0 ) {
				$tracklinks = 'none';
			} else {
				$links = array_map('adesk_b64_decode', $links);
			}
		}
		if ( $tracklinks != 'none' ) {
			if ( $tracklinks == 'all' ) {
				$tracklinks = 'mime';
			} else {
				$tracklinks = adesk_http_param('tracklinksformat' . adesk_http_param('tracklinks')); // crazyness :)
			}
		}
	}
	if ( !in_array($tracklinks, array('mime', 'html', 'text')) ) $tracklinks = 'none';
	if ( $tracklinks == 'none' and $trackreads ) $tracklinks = 'html';
	// unsub link
	$htmlUnsubMissing = true;
	$textUnsubMissing = true;
	$r = array(
		'type' => trim((string)adesk_http_param('campaign_type')),
		'filterid' => $filterid,
		'bounceid' => $bounceid,
		'name' => trim((string)adesk_http_param('campaign_name')),
		'sdate' => $sdate,
		'public' => (int)adesk_http_param_exists('public'),
		'tracklinks' => $tracklinks,
		'tracklinksanalytics' => (int)adesk_http_param_exists('use_analytics_link'),
		'trackreads' => $trackreads,
		'trackreadsanalytics' => (int)adesk_http_param_exists('use_analytics_read'),
		'tweet' => (int)adesk_http_param_exists('tweet'),
		'facebook' => (int)adesk_http_param_exists('facebook'),
		//'analytics_campaign_name' => '',
		'embed_images' => (int)adesk_http_param_exists('embed_images'),
		'htmlunsub' => (adesk_http_param('includeunsub') == 'yes' and $htmlUnsubMissing) ? 1 : 0,
		'htmlunsubdata' => adesk_str_fixtinymce(trim((string)adesk_http_param('includeunsubhtml'))),
		'textunsub' => (adesk_http_param('includeunsub') == 'yes' and $textUnsubMissing) ? 1 : 0,
		'textunsubdata' => trim((string)adesk_http_param('includeunsubtext')),
		'mailer_log_file' => (int)adesk_http_param('debugging'),
		'total_amt' => 0,
	);
	$wl = array('single', 'recurring', 'split', 'responder', 'reminder', 'special', 'deskrss', 'text');
	if ( !in_array($r['type'], $wl) ) $r['type'] = 'single';
	if ( $r['type'] == 'recurring' ) {
		$r['recurring'] = trim((string)adesk_http_param('recurragain'));
		$wl = array(/*'hour0', 'hour1', 'hour2', 'hour6', 'hour12',*/ 'day1', 'day2', 'week1', 'week2', 'month1', 'month2', 'quarter1', 'quarter2', 'year1', 'year2');
		if ( !in_array($r['recurring'], $wl) ) $r['recurring'] = 'year1';
	} elseif ( $r['type'] == 'split' ) {
		if ( adesk_http_param('schedule') == 'now' ) $r['sdate'] = (string)adesk_sql_select_one("SELECT NOW()");
		$r['split_type'] = trim((string)adesk_http_param('splittype'));
		if ( $r['split_type'] == 'winner' ) {
			$r['split_type'] = trim((string)adesk_http_param('splitwinnertype'));
		}
		$wl = array('even', 'read', 'click');
		if ( !in_array($r['split_type'], $wl) ) $r['split_type'] = 'even';
		$r['split_offset'] = (int)adesk_http_param('splitoffset');
		$r['split_offset_type'] = trim((string)adesk_http_param('splitoffsettype'));
		$wl = array('hour', 'day', 'week', 'month');
		if ( !in_array($r['split_offset_type'], $wl) ) $r['split_offset_type'] = 'hour';
	} elseif ( $r['type'] == 'responder' ) {
		$r['public'] = 0;
		$r['responder_offset'] = (int)adesk_http_param('responder_offset');
		//if ( $r['responder_type'] != 'unsubscribe' ) $r['responder_type'] = 'subscribe';
		$r['sdate'] = (string)adesk_sql_select_one("SELECT NOW()");
	} elseif ( $r['type'] == 'reminder' ) {
		$r['public'] = 0;
		$r['reminder_field'] = trim((string)adesk_http_param('reminder_field'));
		$r['reminder_format'] = trim((string)adesk_http_param('reminder_format'));
		$r['reminder_type'] = trim((string)adesk_http_param('reminder_type'));
		if ( $r['reminder_type'] != 'month_day' ) $r['reminder_type'] = 'year_month_day';
		$r['reminder_offset'] = (int)adesk_http_param('reminder_offset');
		$r['reminder_offset_type'] = trim((string)adesk_http_param('reminder_offset_type'));
		$wl = array('day', 'week', 'month', 'year');
		if ( !in_array($r['reminder_offset_type'], $wl) ) $r['reminder_offset_type'] = 'day';
		$r['reminder_offset_sign'] = trim((string)adesk_http_param('reminder_offset_sign'));
		if ( $r['reminder_offset_sign'] != '-' ) $r['reminder_offset_sign'] = '+';
		$r['sdate'] = (string)adesk_sql_select_one("SELECT NOW()");
	} elseif ( $r['type'] == 'deskrss' ) {
		$r['deskrss_interval'] = trim((string)adesk_http_param('deskrssagain'));
		$wl = array('hour0', 'hour1', 'hour2', 'hour6', 'hour12', 'day1', 'day2', 'week1', 'week2', 'month1', 'month2', 'quarter1', 'quarter2', 'year1', 'year2');
		if ( !in_array($r['deskrss_interval'], $wl) ) $r['deskrss_interval'] = 'year1';
	} elseif ( $r['type'] == 'special' ) {
		$r['public'] = 0;
	} else { // single
		if ( adesk_http_param('schedule') == 'now' ) {
			$r['sdate'] = (string)adesk_sql_select_one("SELECT NOW()");
		}
	}
	return $r;
}

function campaign_post2prepared($data = null) {
	if ( !$data ) $data = campaign_prepare_post();
	// campaign info
	$row = $data['ary'];
	//$row['id'] = $row['realcid'] = (int)adesk_http_param('id');
	$row['id'] = $row['realcid'] = 0; // from post, it's always assumed 0
	$row['total_amt'] = 0;
	$row['send_amt'] = 0;
	$row['cdate'] =
	$row['sdate'] = (string)adesk_sql_select_one("SELECT NOW()");
	$row['ldate'] = null;
	if ( isset($GLOBALS['_hosted_account']) ) {
		$row['htmlunsub'] =
		$row['textunsub'] = 0;
		$row['htmlunsubdata'] =
		$row['textunsubdata'] = '';
		$row['bounceid'] = -1;
	}
	// set user permissions
	$cond = '';
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		if ( $admin['id'] != 1 ) {
			$cond = "AND l.id IN ('" . implode("', '", $admin['lists']) . "')";
		}
	}
	$listslist = implode("','", $data['lists']);
	// fetch all lists it belongs to
	$row['lists'] = adesk_sql_select_array("
		SELECT
			*,
			id AS listid,
			0 AS campaignid,
			0 AS list_amt,
			0 AS relid
		FROM
			#list l
		WHERE
			l.id IN ('$listslist')
		$cond
	");
	if ( !$row['lists'] ) $row['lists'] = array();
	// calculate list limits
	$row['p_duplicate_send']     = 0;
	$row['p_embed_image']        = 0;
	$row['p_use_scheduling']     = 0;
	$row['p_use_tracking']       = 0;
	$row['p_use_analytics_read'] = 0;
	$row['p_use_analytics_link'] = 0;
	$row['p_use_twitter'] = 0;
	$row['p_use_facebook'] = 0;
	$lists = array();
	foreach ( $row['lists'] as $l ) {
		$lists[] = $l['id'];
		if ( $l['p_duplicate_send'] )     $row['p_duplicate_send']     = $l['p_duplicate_send'];
		if ( $l['p_embed_image'] )        $row['p_embed_image']        = $l['p_embed_image'];
		if ( $l['p_use_tracking'] )       $row['p_use_tracking']       = $l['p_use_tracking'];
		if ( $l['p_use_analytics_read'] ) $row['p_use_analytics_read'] = $l['p_use_analytics_read'];
		if ( $l['p_use_analytics_link'] ) $row['p_use_analytics_link'] = $l['p_use_analytics_link'];
		if ( $l['p_use_twitter'] )        $row['p_use_twitter']        = $l['p_use_twitter'];
		if ( $l['p_use_facebook'] )       $row['p_use_facebook']       = $l['p_use_facebook'];
	}
	$row['listslist'] = implode('-', $lists);
	// fetch all fields (for those lists only, globals should be prefetched elsewhere)
	$row['fields'] = list_get_fields($lists, false);
	// fetch all messages that belong to this campaign
	$row['ratios'] = $data['ratios'];
	$msgsfound_html = 0;
	$msgsfound_text = 0;
	$unsubsfound_html = 0;
	$unsubsfound_text = 0;
	$row['messages'] = message_select_array(null, $data['messages'], implode(',', $lists));
	foreach ( $row['messages'] as $k => $v ) {
		$row['messages'][$k]['percentage'] = $row['ratios'][$v['id']];
		// look for unsubscribe links so that we can hide the footer if all are found
		if ( $v['format'] != 'text' ) {
			// do htmls
			$msgsfound_html++;
			$unsubsfound_html += (int)( adesk_str_instr('%UNSUBSCRIBELINK%', $v['html']) or adesk_str_instr('/surround.php?nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid&funcml=unsub2', $v['html']) );
		}
		if ( $v['format'] != 'html' ) {
			// do texts
			$msgsfound_text++;
			$unsubsfound_text += (int)( adesk_str_instr('%UNSUBSCRIBELINK%', $v['text']) or adesk_str_instr('/surround.php?nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid&funcml=unsub2', $v['text']) );
		}
	}
	if ( $unsubsfound_html == $msgsfound_html ) {
		$row['htmlunsub'] = 0;
	}
	if ( $unsubsfound_text == $msgsfound_text ) {
		$row['textunsub'] = 0;
	}
	$row['messageslist'] = implode('-', array_keys($row['ratios']));
	// fetch all links for parsing
	$row['tlinks'] = array();
	// insert links relations
	foreach ( $data['links'] as $k => $l ) {
		if ( $l != '' ) {
			$linkid = count($row['tlinks']);
			$row['tlinks'][$linkid] = array(
				'id' => 0,
				'campaignid' => 0,
				'messageid' => $data['linkmessages'][$k],
				'link' => $l,
				'name' => $data['linknames'][$k],
				'actions' => array(),
			);
			# FIXME
		}
	}
	return $row;
}

function campaign_delete($id) {
	$id = intval($id);
	$admincond = '';
	$campaign = campaign_select_row($id);
	if ( !$campaign ) {
		return adesk_ajax_api_result(false, _a("Campaign not found."));
	}

	adesk_sql_query("
		INSERT INTO
			#campaign_deleted
		SELECT * FROM #campaign WHERE id = '$id'
	");

	adesk_sql_delete('#campaign', "id = '$id'");

	// if campaign was not yet approved, remove the approval queue
	adesk_sql_delete("#approval", "campaignid = '$id' AND approved = 0");

	// now remove all campaigns that are special and have this campaign as their "realcid"
	$relcamps = adesk_sql_select_list("SELECT id FROM #campaign WHERE realcid = '$id' AND type = 'special'");
	foreach ( $relcamps as $rcid ) {
		campaign_delete($rcid);
	}

	// if campaign was sending at the time, we gotta do additional queries here
	adesk_sql_query("DROP TABLE `#x$campaign[sendid]`");
	$serialized = adesk_sql_escape(serialize($id));
	adesk_sql_delete('#process', "`action` = 'campaign' AND `data` = '$serialized'");


	return adesk_ajax_api_result(true, _a("Campaign deleted."));
}

function campaign_delete_multi($ids, $filter = 0) {
	if ( $ids == '_all' ) {
		$tmp = array();
		$so = new adesk_Select();
		$filter = intval($filter);
		if ($filter > 0) {
			$admin = adesk_admin_get();
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'campaign'");
			$so->push($conds);
		}
		$all = campaign_select_array($so);
		foreach ( $all as $v ) {
			$tmp[] = $v['id'];
		}
	} else {
		$tmp = array_map("intval", explode(",", $ids));
	}
	foreach ( $tmp as $id ) {
		$r = campaign_delete($id);
	}
	return adesk_ajax_api_result(true, _a("Campaigns deleted."));
}

// switch cron job's status on/off
function campaign_status($id, $status) {
	$id = (int)$id;
	$status = (int)$status;
	$campaign = campaign_select_row($id);
	if ( !$campaign ) {
		return adesk_ajax_api_result(false, _a("Campaign not found."));
	}
	if ( $status == 2 or $status == 3 ) {
		require_once(awebdesk_functions('processes.php'));
		$campaign['processid'] = campaign_processid($campaign['id'], 'active');
		return adesk_processes_trigger($campaign['processid'], ( $status == 3 ? 'pause' : 'resume' ));
	} else {
		// update the field
		$sql = adesk_sql_update_one("#campaign", 'status', $status, "id = '$id'");
		if ( !$sql ) {
			return adesk_ajax_api_result(false, _a("Campaign could not be updated."));
		}
	}
	// additional status commands
	if ( $status == 4 ) { // stop
		// remove the temp table
		adesk_sql_query("DROP TABLE `#x$campaign[sendid]`");
		// remove the process
		$serialized = adesk_sql_escape(serialize($id));
		adesk_sql_delete('#process', "`action` = 'campaign' AND `data` = '$serialized'");
		// mark campaign as cleaned up
		adesk_sql_update_one("#campaign", 'mail_cleanup', 1, "id = '$id'");
		$countid = (int)adesk_sql_select_one("id", "#campaign_count", "campaignid = '$id' ORDER BY id DESC");
		// if campaign is already in the sending stage
		if ( $campaign['mail_transfer'] ) {
			// set a new count
			adesk_sql_update_one("#campaign_count", 'amt', $campaign['send_amt'], "id = '$countid'");
		} else {
			// remove this campaign from counts
			adesk_sql_update_one("#campaign_count", 'amt', 0, "id = '$countid'");
		}
	}
	// return result
	return adesk_ajax_api_updated(_a("Campaign"));
}

function campaign_update_splittotal($campaignid, $total) {
	$campaignid = intval($campaignid);
	$ms         = adesk_sql_select_array("
		SELECT
			*
		FROM
			#campaign_message
		WHERE
			campaignid = '$campaignid'
	");

	if ($total == 0)
		$total = (int)adesk_sql_select_one("SELECT total_amt FROM #campaign WHERE id = '$campaignid'");

	$counter = $total;
	foreach ($ms as $m) {
		$ratio = $m["percentage"] / 100.0;
		$quot  = ceil($total * $ratio);
		if ($counter > $quot) {
			$counter -= $quot;
			adesk_sql_query("UPDATE #campaign_message SET total_amt = $quot WHERE id = '$m[id]'");
		} else  {
			$quot = $counter;
			adesk_sql_query("UPDATE #campaign_message SET total_amt = $quot WHERE id = '$m[id]'");
			break;
		}
	}
}

function campaign_update_splitsend($campaignid, $total) {
	$campaignid = intval($campaignid);
	$winner     = 0;
	$send       = 0;
	$ms         = adesk_sql_select_array("
		SELECT
			*
		FROM
			#campaign_message
		WHERE
			campaignid = '$campaignid'
	");

	if ($total == 0) {
		$row = adesk_sql_select_row("
			SELECT
				send_amt,
				total_amt,
				split_winner_awaiting,
				split_winner_messageid
			FROM
				#campaign
			WHERE
				id = '$campaignid'
		");
		if ($row) {
			$total = $row["total_amt"];
			$send  = $row["send_amt"];
			$overall = $row["total_amt"];
			$winner = $row["split_winner_messageid"];

			if (($row["split_winner_awaiting"] > 0 || $winner > 0) && $overall > $total)
				$total = $overall;
		}
	}

	$counter = $total;
	foreach ($ms as $m) {
		$ratio = $m["percentage"] / 100.0;
		$quot  = ceil($total * $ratio);
		# If this is the winner, then the send may still be going on for this message; we should
		# reflect that the total is a ratio of the send rather than of the overall total.
		if ($m["id"] == $winner)
			$quot = ceil($send * $ratio);

		if ($counter > $quot) {
			$counter -= $quot;
			adesk_sql_query("UPDATE #campaign_message SET send_amt = $quot WHERE id = '$m[id]'");
		} else  {
			$quot = $counter;
			adesk_sql_query("UPDATE #campaign_message SET send_amt = $quot WHERE id = '$m[id]'");
			break;
		}
	}
}

function campaign_copy($campaign, $data = array(), $reusemessage = false) {
	if ( !$campaign ) return 0;
	// create default campaign array (will use it as whitelist for new campaign)
	$blank = adesk_sql_default_row('#campaign');
	// make clean insert array
	$insert = array();
	foreach ( $blank as $k => $v ) {
		if ( isset($campaign[$k]) ) $insert[$k] = $campaign[$k];
	}
	if ( isset($GLOBALS['_hosted_account']) ) {
		$insert['bounceid'] = -1;
	}
	$insert = array_merge($insert, $data);
	if ( isset($insert['cdate']) ) {
		unset($insert['cdate']);
	}
	$insert['=cdate'] = 'NOW()';
	// add it as new id
	$insert['id'] = campaign_nextid();
	// reset sending engine counters
	$insert['send_amt'] =
	$insert['total_amt'] =
	$insert['mail_transfer'] =
	$insert['mail_send'] =
	$insert['mail_cleanup'] =
	$insert['opens'] =
	$insert['uniqueopens'] =
	$insert['linkclicks'] =
	$insert['uniquelinkclicks'] =
	$insert['subscriberclicks'] =
	$insert['forwards'] =
	$insert['uniqueforwards'] =
	$insert['hardbounces'] =
	$insert['softbounces'] =
	$insert['unsubscribes'] =
	$insert['unsubreasons'] = 0;
	$insert['source'] = "copy";
	// other custom campaign info should already be prepared in $campaign array
	//...
	$wl = array('single', 'recurring', 'split', 'responder', 'reminder', 'special', 'deskrss', 'text');
	if ( !in_array($insert['type'], $wl) ) {
		$insert['type'] = 'single';
	}
	$wl = array(/*'hour0', 'hour1', 'hour2', 'hour6', 'hour12',*/ 'day1', 'day2', 'week1', 'week2', 'month1', 'month2', 'quarter1', 'quarter2', 'year1', 'year2');
	if ( !in_array($insert['recurring'], $wl) ) {
		$insert['recurring'] = 'year1';
	}
	$wl = array('hour0', 'hour1', 'hour2', 'hour6', 'hour12', 'day1', 'day2', 'week1', 'week2', 'month1', 'month2', 'quarter1', 'quarter2', 'year1', 'year2');
	if ( !in_array($insert['deskrss_interval'], $wl) ) {
		$insert['deskrss_interval'] = 'year1';
	}
	// then copy the campaign as a new one
	if ( !adesk_sql_insert("#campaign", $insert) ) return 0;
	$id = adesk_sql_insert_id();
	campaign_updatenextid($id);
	// copy list relations
	adesk_sql_query("
		INSERT INTO
			#campaign_list
		(
			id, campaignid, listid, userid, list_amt
		)
			SELECT
				0 AS id,
				$id AS campaignid,
				listid,
				userid,
				0 AS list_amt
			FROM
				#campaign_list
			WHERE
				campaignid = '$campaign[id]'
	");
	// copy mailer info
	adesk_sql_query("
		INSERT INTO
			#campaign_mailer
		(
			id, campaignid, mailerid
		)
			SELECT
				0 AS `id`,
				$id AS `campaignid`,
				`mailerid`
			FROM
				#campaign_mailer
			WHERE
				`campaignid` = '$campaign[id]'
	");
	$messages = adesk_sql_select_box_array("SELECT messageid, messageid FROM #campaign_message WHERE campaignid = '$campaign[id]'");
	// create default campaign array (will use it as whitelist for new campaign)
	$blankmsg = adesk_sql_default_row('#message');
	$keysarr = array_keys($blankmsg);
	$valsarr = $keysarr;
	$valsarr[0] = "0 AS `id";
	$keys = "`" . implode("`, `", $keysarr) . "`";
	$vals = implode("`, `", $valsarr) . "`";
	foreach ( $messages as $k => $v ) {
		if ( $reusemessage ) {
			$v = $messages[$k] = $k;
		} else {
			// copy the message
			adesk_sql_query("
				INSERT INTO
					#message
				(
					$keys
				)
					SELECT
						$vals
					FROM
						#message
					WHERE
						id = '$k'
			");
			$v = $messages[$k] = (int)adesk_sql_insert_id();

			// copy message relations
			adesk_sql_query("
				INSERT INTO
					#message_list
				(
					id, messageid, listid, userid
				)
					SELECT
						0 AS id,
						$v AS messageid,
						listid,
						userid
					FROM
						#message_list
					WHERE
						messageid = '$k'
			");
		}
		adesk_sql_query("
			INSERT INTO
				#campaign_message
			(
				id, messageid, campaignid, percentage, sourcesize
			)
				SELECT
					0 AS id,
					$v AS messageid,
					$id AS campaignid,
					percentage,
					sourcesize
				FROM
					#campaign_message
				WHERE
					campaignid = '$campaign[id]'
				AND
					messageid = '$k'
		");
		// copy rss relations
		adesk_sql_query("
			INSERT INTO
				#rssfeed
			(
				id, campaignid, messageid, url, type, lastcheck, howmany
			)
				SELECT
					0 AS id,
					$id AS campaignid,
					$v AS messageid,
					url,
					type,
					lastcheck,
					howmany
				FROM
					#rssfeed
				WHERE
					campaignid = '$campaign[id]'
				AND
					messageid = '$k'
		");

		// copy message attachments
		message_copy_attach($k, $v);
	}

	// copy link relations
	$links = adesk_sql_select_box_array("SELECT id, messageid FROM #link WHERE campaignid = '$campaign[id]'");
	if ( count($links) > 0 ) {
		foreach ( $links as $l => $mid ) {
			$mid = isset($messages[$mid]) ? $messages[$mid] : $mid; // get the new message id
			// links
			adesk_sql_query("
				INSERT INTO
					#link
					SELECT
						0 AS id,
						$id AS campaignid,
						$mid AS messageid,
						link,
						name,
						ref,
						tracked
					FROM
						#link
					WHERE
						id = '$l'
			");
			$relid = adesk_sql_insert_id();

			# Subscriber actions
			$actions = adesk_sql_select_list("SELECT id FROM #subscriber_action WHERE linkid = '$l'");

			if (count($actions) > 0) {
				foreach ($actions as $actionid) {
					adesk_sql_query("
						INSERT INTO
							#subscriber_action
						SELECT
							0 AS id,
							filterid,
							listid,
							$id AS campaignid,
							$relid AS linkid,
							name,
							`type`
						FROM
							#subscriber_action
						WHERE
							id = '$actionid'
					");

					$newactionid = adesk_sql_insert_id();

					adesk_sql_query("
						INSERT INTO
							#subscriber_action_part
						SELECT
							0 AS id,
							$newactionid AS actionid,
							act,
							targetid,
							targetfield,
							param
						FROM
							#subscriber_action_part
						WHERE
							actionid = '$actionid'
					");
				}
			}
		}
	}

	return $id;
}

function campaign_share() {
	$addrto     = strval(adesk_http_param("addrto"));
	$addrfrom   = strval(adesk_http_param("addrfrom"));
	$nameto     = strval(adesk_http_param("nameto"));
	$namefrom   = strval(adesk_http_param("namefrom"));
	$subject    = strval(adesk_http_param("subject"));
	$message    = strval(adesk_http_param("message"));
	$campaignid = intval(adesk_http_param("campaignid"));
	$arr = array('link' => '');

	if ($addrto != "" && $addrfrom != "" && $subject != "" && $message != "") {
		$campaign = campaign_share_get($campaignid, $addrto);
		$message  = str_replace('%REPORTLINK%', $campaign["sharelink"], $message);
		$admin = adesk_admin_get();
		$options = array();
		if($admin) $options['userid'] = $admin['id'];
		if ( !isset($GLOBALS['demoMode']) ) { // check if demo mode is on
			adesk_mail_send("text", $namefrom, $addrfrom, $message, $subject, $addrto, $nameto, $options);
		}
		$arr['link'] = $campaign['sharelink'];
		return adesk_ajax_api_result(true, _a("Shared report sent"), $arr);
	} else {
		return adesk_ajax_api_result(false, _a("Shared report could not be sent"), $arr);
	}
}

function campaign_rebuild_source($source) {
	$struct  = adesk_mail_extract_components(adesk_mail_extract($source));
	$headers = array();

	$headers[] = sprintf("Return-Path: %s", $struct["structure"]->headers["return-path"]);
	$headers[] = sprintf("To: %s", $struct["to"]);
	$headers[] = sprintf("From: %s", $struct["from"]);

	if (isset($struct->reply_to))
		$headers[] = sprintf("Reply-To: %s", $struct["reply_to"]);

	$headers[] = sprintf("Subject: %s", $struct["subject"]);
	$headers[] = sprintf("Date: %s", $struct["structure"]->headers["date"]);

	$headers[] = "MIME-Version: 1.0";
	$headers[] = sprintf("Content-Type: %s/%s;", $struct["structure"]->ctype_primary, $struct["structure"]->ctype_secondary);

	# This is a hack in case we have no ctype_parameters but we do (!!) have MIME parts.  This default
	# will probably never need to be used.

	$bound = "_=_swift-297934d6e971334c2c6.94225942_=_";

	foreach ($struct["structure"]->ctype_parameters as $param => $v) {
		if ($param == "boundary")
			$bound = $v;

		$headers[] = sprintf(" %s=\"%s\"", $param, $v);
	}

	$headers[] = "X-Priority: 3";
	$headers[] = "X-MSMail-Priority: Normal";
	$headers[] = "X-MimeOLE: Produced by SwiftMailer 3.3.2_4";

	$headers[] = sprintf("X-mid: %s", $struct["structure"]->headers["x-mid"]);
	$headers[] = "X-Mailer: AEM";
	$headers[] = "User-Agent: AEM";
	$headers[] = sprintf("X-Sender: %s", $struct["structure"]->headers["return-path"]);
	$headers[] = sprintf("List-Unsubscribe: %s", $struct["structure"]->headers["list-unsubscribe"]);
	$headers[] = sprintf("Message-ID: %s", $struct["structure"]->headers["message-id"]);

	$body = array();

	$body[] = "This is a message in multipart MIME format.  Your mail client should not";
	$body[] = "be displaying this. Consider upgrading your mail client to view this";
	$body[] = "message correctly.";

	foreach ($struct["structure"]->parts as $part) {
		$body[] = "--" . $bound;
		$body[] = sprintf("Content-Type: %s", $part->headers["content-type"]);
		$body[] = sprintf("Content-Transfer-Encoding: %s", $part->headers["content-transfer-encoding"]);
		$body[] = "";
		$body[] = adesk_utf_conv($part->ctype_parameters["charset"], "UTF-8", $part->body);
	}

	if (count($struct["structure"]->parts) > 0)
		$body[] = "--" . $bound;

	$rval  = implode("\n", $headers);
	$rval .= "\n\n";
	$rval .= implode("\n", $body);

	return $rval;
}

function campaign_reminder_compile_post() {
	return array(
		"compile" => campaign_reminder_compile(
			(string)adesk_http_param("field"),
			(string)adesk_http_param("sign"),
			(int)adesk_http_param("offset"),
			(string)adesk_http_param("type")
		)
	);
}

function campaign_reminder_compile($field, $sign, $offset, $type) {
	# Some sanity checks.
	if ($sign != "+" && $sign != "-")
		$sign = "+";

	$offset = (int)$offset;

	switch ($type) {
		default:
		case "day":
			$type = "DAY";
			break;

		case "week":
			$type = "WEEK";
			break;

		case "month":
			$type = "MONTH";
			break;

		case "year":
			$type = "YEAR";
			break;
	}

	switch ($field) {
		case "sdate":
			$field = _a("Subscription Date");
			break;

		case "cdate":
			$field = _a("Creation Date");
			break;

		default:
			$field = (int)$field;
			$field = (string)adesk_sql_select_one("SELECT title FROM #list_field WHERE id = '$field'");
			break;
	}

	$t_offset = adesk_date_offset_hour();
	$site = adesk_site_get();

	$today = (string)adesk_sql_select_one("SELECT CURDATE()");

	$time = strtotime($today);
	$today = strftime($site["dateformat"], $time + ($t_offset * 3600));

	$thatday = (string)adesk_sql_select_one($q = "SELECT CURDATE() $sign INTERVAL $offset $type");

	$time = strtotime($thatday);
	$thatday = strftime($site["dateformat"], $time + ($t_offset * 3600));

	return sprintf(_a("Example: When this campaign runs tomorrow (%s) it will look for subscribers with a %s matching (%s)",
		$today,
		$field,
		$thatday
	));
}

/*
$boundary = preg_match('/Content-Type: multipart/alternative; boundary=(.*)/', $source);
// first part is til the boundary header
// second part is from the boundary header to the end of the header
// third part is from the end of headers til the end of "this is a mime"
// fourth part is text-based
// sixth part is html-based
$parts = explode($boundary, $source, 6);
$source = implode($boundary, $parts) . "\n\n$boundary--";
*/
?>
