<?php

require_once awebdesk_classes("select.php");
require_once dirname(__FILE__) . '/bounce_log.php';

function bounce_pipe_find($structure) {

	$bounce = array();
	//find To: header
	$to = "";
	if ( $structure and isset($structure->headers) and isset($structure->headers['to']) ) {
		$to = adesk_mail_extract_email($structure->headers['to']);
	}
	$to_esc = adesk_sql_escape($to);

	//query database and find hard/soft bounce settings for that email address
	$bounce['limit_hard'] = adesk_sql_select_one("limit_hard", "#bounce", "email='$to_esc' AND type='pipe'");
	$bounce['limit_soft'] = adesk_sql_select_one("limit_soft", "#bounce", "email='$to_esc' AND type='pipe'");
	$bounce['id']         = adesk_sql_select_one("id", "#bounce", "email='$to_esc' AND type='pipe'");
	$bounce['email'] = $to;
	return $bounce;

}

function bounce_management_select_query(&$so) {
	if ( !adesk_admin_ismain() && !isset($GLOBALS["_hosted_account"]) ) {
		$admin = adesk_admin_get();
		if ( $admin['id'] != 1 ) {
			if ( !isset($so->permsAdded) ) {
				$so->permsAdded = 1;
				$so->push("AND l.listid IN ('" . implode("', '", $admin['lists']) . "')");
			}
		}
	}
	return $so->query("
		SELECT
			b.*,
			COUNT(l.id) as lists
		FROM
			#bounce b
		LEFT JOIN
			#bounce_list l
		ON
			b.id = l.bounceid
		WHERE
			[...]
		GROUP BY
			b.id
	");
}

function bounce_management_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND b.id = '$id'");

	$r = adesk_sql_select_row(bounce_management_select_query($so));
	if ( $r ) {
		$r['pass'] = base64_decode($r['pass']);
		$cond = '';
		if ( !adesk_admin_ismain() ) {
			$admin = adesk_admin_get();
			if ( $admin['id'] != 1 ) {
				//$admin['lists'][0] = 0;
				$cond = "AND l.listid IN ('" . implode("', '", $admin['lists']) . "')";
			}
		}
		$r['lists'] = implode('-', adesk_sql_select_list("SELECT listid FROM #bounce_list l WHERE l.bounceid = '$id' $cond"));
	}
	return $r;
}

function bounce_management_select_row_ajax($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND b.id = '$id'");

	if (!adesk_admin_ismaingroup())
		$so->push("AND b.id != '1'");

	$r = adesk_sql_select_row(bounce_management_select_query($so));
	if ( $r ) {
		$r['pass'] = base64_decode($r['pass']);
		$cond = '';
		if ( !adesk_admin_ismain() ) {
			$admin = adesk_admin_get();
			if ( $admin['id'] != 1 ) {
				//$admin['lists'][0] = 0;
				$cond = "AND l.listid IN ('" . implode("', '", $admin['lists']) . "')";
			}
		}
		$r['lists'] = implode('-', adesk_sql_select_list("SELECT listid FROM #bounce_list l WHERE l.bounceid = '$id' $cond"));
	}
	return $r;
}

function bounce_management_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND b.id IN ('$ids')");
	}
	return adesk_sql_select_array(bounce_management_select_query($so));
}

function bounce_management_select_array_paginator($id, $sort, $offset, $limit, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'bounce_management'");
		$so->push($conds);
	}

	$so->count();
	$total = (int)adesk_sql_select_one(bounce_management_select_query($so));

	switch ($sort) {
		default:
		case "01":
			$so->orderby("email"); break;
		case "01D":
			$so->orderby("email DESC"); break;
		case "02":
			$so->orderby("host"); break;
		case "02D":
			$so->orderby("host DESC"); break;
		case "03":
			$so->orderby("user"); break;
		case "03D":
			$so->orderby("user DESC"); break;
		case "04":
			$so->orderby("lists"); break;
		case "04D":
			$so->orderby("lists DESC"); break;
	}

	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	if (!adesk_admin_ismaingroup())
		$so->push("AND b.id != 1");		// Don't show the default bounce row for non-Admin Group users
	$rows = bounce_management_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function bounce_management_filter_post() {
	$whitelist = array("email", "host", "user");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "bounce_management",
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

	if ( isset($_POST['listid']) ) {
		if ( defined('AWEBVIEW') ) {
			$_SESSION['nlp'] = $_POST['listid'];
		} else {
			$_SESSION['nla'] = $_POST['listid'];
		}
	}
	$nl = null;
	if ( isset($_SESSION['nlp']) and defined('AWEBVIEW') ) {
		$nl = $_SESSION['nlp'];
	} elseif ( isset($_SESSION['nla']) ) {
		$nl = $_SESSION['nla'];
	}
	if ( $nl ) {
		if ( is_array($nl) ) {
			if ( count($nl) > 0 ) {
				$ids = implode("', '", array_map('intval', $nl));
				$ary['conds'] .= "AND l.listid IN ('$ids') ";
			} else {
				if ( defined('AWEBVIEW') ) {
					unset($_SESSION['nlp']);
				} else {
					unset($_SESSION['nla']);
				}
			}
		} else {
			$listid = (int)$nl;
			if ( $listid > 0 ) {
				$ary['conds'] .= "AND l.listid = '$listid' ";
			} else {
				if ( defined('AWEBVIEW') ) {
					unset($_SESSION['nlp']);
				} else {
					unset($_SESSION['nla']);
				}
			}
		}
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
			sectionid = 'bounce_management'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

function bounce_management_insert_post() {
	// find parents
	$lists = array();
	if ( isset($_POST['p']) and is_array($_POST['p']) and count($_POST['p']) > 0 ) {
		$lists = array_diff(array_map('intval', $_POST['p']), array(0));
	}
	if ( !count($lists) ) {
		return adesk_ajax_api_result(false, _a("You did not select any lists."));
	}

	$admin = adesk_admin_get();
	$ary = bounce_management_prepare_post();
	$ary['id'] = 0;
	$ary['userid'] = (int)$admin['id'];

	// perform checks
	if ( $ary['type'] != 'none' ) {
		if ( !adesk_str_is_email($ary['email']) ) {
			return adesk_ajax_api_result(false, _a("Email Address is not valid."));
		}
	}
	if ( $ary['type'] == 'pop3' ) {
		if ( $ary['host'] == '' ) {
			return adesk_ajax_api_result(false, _a("Host name not entered."));
		}
		if ( $ary['user'] == '' ) {
			return adesk_ajax_api_result(false, _a("Account username not entered."));
		}
		if ( $ary['port'] == 0 ) $ary['port'] = 110;
		require_once(awebdesk_functions('pop3.php'));
		$ary['method'] = adesk_pop3_method_find('', $ary['host'], $ary['port'], $ary['user'], $ary['pass']);
		if ( $ary['method'] == '' ) {
			return adesk_ajax_api_result(false, _a("POP3 Connection could not be established."));
		}
		$ary['pass'] = base64_encode($ary['pass']);
	} else {
		$ary['host'] = $ary['user'] = $ary['pass'] = '';
		$ary['port'] = 110;
		$ary['emails_per_batch'] = 120;
		if ( $ary['type'] != 'pipe' ) {
			$ary['email'] = '';
			$ary['limit_hard'] = 3;
			$ary['limit_soft'] = 6;
		}
	}

	$sql = adesk_sql_insert("#bounce", $ary);
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Bounce Setting could not be added."));
	}
	$id = adesk_sql_insert_id();

	// list relations
	foreach ( $lists as $l ) {
		if ( $l > 0 ) adesk_sql_insert('#bounce_list', array('id' => 0, 'bounceid' => $id, 'listid' => $l));
	}
	// list relations
	$cond = implode(', ', $lists);
	$admincond = '';
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		$admincond = "AND listid IN ('" . implode("', '", $admin['lists']) . "')";
	}
	adesk_sql_delete('#bounce_list', "bounceid = '$id' AND listid NOT IN ($cond) $admincond");

	return adesk_ajax_api_added(_a("Bounce Setting"), array('id' => $id, 'email' => $ary['email']));
}

function bounce_management_update_post() {
	$id = intval($_POST["id"]);

	if ( $id > 1 ) {
		// find parents
		$lists = array();
		if ( isset($_POST['p']) and is_array($_POST['p']) and count($_POST['p']) > 0 ) {
			$lists = array_diff(array_map('intval', $_POST['p']), array(0));
		}
		if ( !count($lists) ) {
			return adesk_ajax_api_result(false, _a("You did not select any lists."));
		}
	}

	$ary = bounce_management_prepare_post();

	// perform checks
	if ( $ary['type'] != 'none' ) {
		if ( !adesk_str_is_email($ary['email']) ) {
			return adesk_ajax_api_result(false, _a("Email Address is not valid."));
		}
	}
	if ( $ary['type'] == 'pop3' ) {
		if ( $ary['host'] == '' ) {
			return adesk_ajax_api_result(false, _a("Host name not entered."));
		}
		if ( $ary['user'] == '' ) {
			return adesk_ajax_api_result(false, _a("Account username not entered."));
		}
		if ( $ary['port'] == 0 ) $ary['port'] = 110;
		require_once(awebdesk_functions('pop3.php'));
		$ary['method'] = adesk_pop3_method_find('', $ary['host'], $ary['port'], $ary['user'], $ary['pass']);
		if ( $ary['method'] == '' ) {
			return adesk_ajax_api_result(false, _a("POP3 Connection could not be established."));
		}
		$ary['pass'] = base64_encode($ary['pass']);
	} else {
		$ary['host'] = $ary['user'] = $ary['pass'] = '';
		$ary['port'] = 110;
		$ary['emails_per_batch'] = 120;
		if ( $ary['type'] != 'pipe' ) {
			$ary['email'] = '';
			$ary['limit_hard'] = 3;
			$ary['limit_soft'] = 6;
		}
	}

	$sql = adesk_sql_update("#bounce", $ary, "id = '$id'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Bounce Setting could not be updated."));
	}

	if ( $id > 1 ) {
		// list relations
		$cond = implode(', ', $lists);
		$admincond = '';
		if ( !adesk_admin_ismain() ) {
			$admin = adesk_admin_get();
			$admincond = "AND listid IN ('" . implode("', '", $admin['lists']) . "')";
		}
		adesk_sql_delete('#bounce_list', "bounceid = '$id' AND listid NOT IN ($cond) $admincond");
		foreach ( $lists as $l ) {
			if ( $l > 0 ) {
				if ( !adesk_sql_select_one('=COUNT(*)', '#bounce_list', "bounceid = '$id' AND listid = '$l'") )
					adesk_sql_insert('#bounce_list', array('id' => 0, 'bounceid' => $id, 'listid' => $l));
			}
		}
	}

	// check if any lists are orphans and assign them to bounce=1
	adesk_sql_query("DELETE FROM #bounce_list WHERE `bounceid` NOT IN ( SELECT `id` FROM #bounce )");
	adesk_sql_query("DELETE FROM #bounce_list WHERE `listid` NOT IN ( SELECT `id` FROM #list ) AND `listid` > 0");
	$query = "
		INSERT INTO
			#bounce_list
		(`id`, `bounceid`, `listid`)
		SELECT
			0 AS `id`,
			1 AS `bounceid`,
			l.id AS `listid`,
		FROM
			#list l
		LEFT JOIN
			#bounce_list b
		ON
			l.id = b.listid
		WHERE
			b.id IS NULL
	";
	$sql = adesk_sql_query($query);

	if (bounce_management_newbounces($id) > 0)
		bounce_management_reapply($id);

	return adesk_ajax_api_updated(_a("Bounce Setting"));
}

function bounce_management_delete($id) {
	$id = intval($id);
	if ( $id < 2 ) {
		return adesk_ajax_api_result(false, _a("This Bounce Setting cannot be deleted."));
	}
	adesk_sql_delete('#bounce', "id = '$id'");
	adesk_sql_delete('#bounce_list', "bounceid = '$id'");
	return adesk_ajax_api_deleted(_a("Bounce Setting"));
}

function bounce_management_delete_multi($ids, $filter = 0) {
	if ( $ids == '_all' ) {
		$tmp = array();
		$so = new adesk_Select();
		$filter = intval($filter);
		if ($filter > 0) {
			$admin = adesk_admin_get();
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'bounce_management'");
			$so->push($conds);
		}
		$all = bounce_management_select_array($so);
		foreach ( $all as $v ) {
			$tmp[] = $v['id'];
		}
	} else {
		$tmp = array_map("intval", explode(",", $ids));
	}
	foreach ( $tmp as $id ) {
		$r = bounce_management_delete($id);
	}
	return $r;
}

function bounce_management_prepare_post() {
	return array(
		'type' => (string)adesk_http_param('type'),
		'email' => trim((string)adesk_http_param('email')),
		'host' => trim((string)adesk_http_param('host')),
		'port' => (int)adesk_http_param('port'),
		'user' => trim((string)adesk_http_param('user')),
		'pass' => trim((string)adesk_http_param('pass')),
		'method' => '',
		'limit_hard' => (int)adesk_http_param('limit_hard'),
		'limit_soft' => (int)adesk_http_param('limit_soft'),
		'emails_per_batch' => (int)adesk_http_param('emails_per_batch')
	);
}

function bounce_management_run($id, $isTest = 1) {
	$id = (int)$id;
	$r = array(
		'id' => $id,
		'istest' => $isTest,
		'method' => ''
	);
	$row = bounce_management_select_row($id);
	if ( !$row ) {
		return adesk_ajax_api_result(false, _a("Bounce Setting not found."), $r);
	}
	require_once(awebdesk_functions('pop3.php'));
	$r['method'] = adesk_pop3_method_find($row['method'], $row['host'], $row['port'], $row['user'], $row['pass']);
	if ( $r['method'] == '' ) {
		return adesk_ajax_api_result(false, _a("POP3 Connection could not be established."), $r);
	}
	if ( $r['method'] != $row['method'] ) {
		adesk_sql_update_one('#bounce', 'method', $r['method'], "`id` = '$id'");
	}
	return adesk_ajax_api_result(true, _a("Bounce Setting successfully connected."), $r);
}

function bounce_management_newbounces($id) {
	$id      = (int)$id;
	$bounce  = bounce_management_select_row($id);
	$listids = adesk_sql_select_list("SELECT listid FROM #bounce_list WHERE bounceid = '$id'");
	$liststr = implode("','", $listids);

	$count   = (int)adesk_sql_select_one("
		SELECT
			COUNT(*)
		FROM
			#bounce_data
		WHERE
			listid IN ('$liststr')
		AND
			((`type` = 'hard' AND counted >= '$bounce[limit_hard]') OR (`type` = 'soft' AND counted >= '$bounce[limit_soft]'))
	");

	return $count;
}

function bounce_management_reapply($id) {
	# If changes to bounce settings would cause addresses to be removed that wouldn't have been
	# removed in the past, do so now.

	$id      = (int)$id;
	$bounce  = bounce_management_select_row($id);
	$listids = adesk_sql_select_list("SELECT listid FROM #bounce_list WHERE bounceid = '$id'");
	$liststr = implode("','", $listids);

	$rs      = adesk_sql_query("
		SELECT
			*
		FROM
			#bounce_data
		WHERE
			listid IN ('$liststr')
		AND
			((`type` = 'hard' AND counted >= '$bounce[limit_hard]') OR (`type` = 'soft' AND counted >= '$bounce[limit_soft]'))
	");

	$lastdate = "0000-00-00";

	while ($row = adesk_sql_fetch_assoc($rs)) {
		$clists = adesk_sql_select_box_array("SELECT listid, listid FROM #campaign_list WHERE campaignid = '$row[campaignid]'");
		$sub = subscriber_exists($row["email"], $clists);

		if (!$sub)
			continue;

		$duplicate = ($sub["bounced_date"] == $lastdate);
		if ($duplicate)
			continue;

		if ($row["type"] == "hard" && $sub["bounced_hard"] + 1 < $bounce["limit_hard"]) {
			$update = array(
				'=bounced_hard' => 'bounced_hard + 1',
				'bounced_date' => ($lastdate = $row["tstamp"]),
			);
			adesk_sql_update('#subscriber', $update, "id = '$sub[id]'");
		} elseif ($row["type"] == "soft" && $sub["bounced_soft"] + 1 < $bounce["limit_soft"]) {
			$update = array(
				'=bounced_soft' => 'bounced_soft + 1',
				'bounced_date' => ($lastdate = $row["tstamp"]),
			);
			adesk_sql_update('#subscriber', $update, "id = '$sub[id]'");
		} else {
			$update = array(
				'status' => 3,
				'=udate' => "NOW()"
			);
			adesk_sql_update("#subscriber_list", $update, "subscriberid = '$sub[id]'");
		}
	}
}

function bounce_management_process($id = 0) {
	if ( !$id ) $id = null;
	adesk_ihook_define("adesk_pop3_parse", "bounce_management_parse");
	adesk_ihook_define("adesk_pop3_error", "bounce_management_parse_error");
	$so = new adesk_Select();
	$so->push("AND `type` = 'pop3'");
	$bounces = bounce_management_select_array($so, $id);
	$GLOBALS['bouncecodes'] = bounce_code_select_array();
	/*
	// for testing: custom bounce code
	$GLOBALS['bouncecodes'][] = array(
		'id' => 990,
		'code' => '9.9.0',
		'match' => 'DNS Error: Domain name not found',
		'type' => 'hard',
		'descript' => 'DNS Error: Domain name not found'
	);
	*/
	foreach ( $bounces as $row ) {
		$GLOBALS['bouncecfg'] = $row;
		// this will fetch the messages and process them right away
		// the ihook is written to store all results into a global array
		$GLOBALS['__bounce_result'] = array();
		adesk_pop3_fetch($row['host'], $row['port'], $row['user'], base64_decode($row['pass']), $row['method'], $row['emails_per_batch']);
		// do something with result messages?
		//dbg($GLOBALS['__bounce_result'], 1);
	}
}

function bounce_management_dontblock($reason) {
	return strpos($reason, "att.net/block") !== false;
}

function bounce_management_parse($structure, $source) {
	global $site;
	$settings = array(
		'subscriberid' => 0,
		'campaignid' => 0,
		'messageid' => 0,
		'codeid' => 0,
		'email' => '',
	);

	// first check for X-mid header
	preg_match('/^X-mid: (.*)\s*$/im', $source, $matches);
	if ( !isset($matches[1]) ) return bounce_management_parse_log('x-mid-value', $settings, $source);
	$xmid = base64_decode($matches[1]);
	if ( !in_string(' , ', $xmid) ) return bounce_management_parse_log('x-mid-elements', $settings, $source);
	// break the header
	$arr = explode(' , ', $xmid);
	// collect: 0=email address, 1=campaignid, 2=messageid
	if ( !isset($arr[2]) ) $arr[2] = ' m0 '; // we are now pushing both message and mailing ids
	$settings['email']      = $email      = $arr[0];
	$settings['campaignid'] = $campaignid = (int)substr(trim($arr[1]), 1);
	$settings['messageid']  = $messageid  = (int)substr(trim($arr[2]), 1);
	// if campaign is zero, its a test or similar, just drop it
	if ( $campaignid == 0 ) return bounce_management_parse_log('x-mid-campaignid', $settings, $source);
	$campaign = adesk_sql_select_row("SELECT * FROM #campaign WHERE id = '$campaignid'");
	// should we stop if campaign is not found?
	if ( !$campaign ) {
		$campaign = adesk_sql_select_row("SELECT * FROM #campaign_deleted WHERE id = '$campaignid'");
	}
	if ( !$campaign ) return bounce_management_parse_log('x-mid-campaign', $settings, $source);
	// get campaign's lists
	$lists = adesk_sql_select_box_array("SELECT listid, listid FROM #campaign_list WHERE campaignid = '$campaignid'");
	$listid = ( count($lists) ? current($lists) : 0 );
	// find the message
	$message = false;
	if ( $messageid ) $message = adesk_sql_select_row("SELECT * FROM #message WHERE id = '$messageid'");
	// fetch the subscriber in question
	$subscriber = subscriber_exists($email, $lists);
	$foundSubscriber = true;
	if ( !$subscriber ) {
		$foundSubscriber = false;
		$subscriber = subscriber_dummy(_a('_t.e.s.t_@example.com'), $listid);
	}
	$settings['subscriberid'] = $subscriberid = (int)$subscriber['id'];

	// abuse, feedback loop by return path
	require_once adesk_admin("functions/abuse.php");
	if ( abuse_feedback_loop($structure, $campaignid, $messageid, $listid, $subscriberid) ) {
		// record this abuse (it will also unsubscribe him and notify main admin if sender is overlimit)
		if ( $site['mail_abuse'] ) {
			abuse_complaint($subscriber, $campaign, $messageid, $listid);
		} else {
			// just unsubscribe the subscriber
			$campaignlists = adesk_sql_select_list("SELECT listid FROM #campaign_list WHERE campaignid = '$campaign[id]'");
			$unsubscribe = subscriber_unsubscribe($subscriber['id'], $subscriber['email'], $campaignlists, _p("Abuse Reported."), $fid = 0, $campaign['id'], $messageid, true);
		}
		// we're done here
		return true;
	}

	if ( !$foundSubscriber ) return bounce_management_parse_log('x-mid-subscriber', $settings, $source);

	/* continue with regular bounce management */

	// try to find a bounce code
	$code = null;

	if (!isset($structure->parts)) {
		if (isset($GLOBALS["_hosted_account"])) {
			if (adesk_str_instr("blocked by", $source) || adesk_str_instr("blocked using", $source) || adesk_str_instr("att.net/block", $source)) {
				return bounce_management_parse_log("ip-blacklist", $settings, $source);
			}

			if (adesk_str_instr("delivery time expired", $source)) {
				return bounce_management_parse_log("timeout", $settings, $source);
			}

			if (adesk_str_instr("too many connections", $source)) {
				return bounce_management_parse_log("too-many-connections", $settings, $source);
			}
		}

		foreach ( $GLOBALS['bouncecodes'] as $b ) {
			if ( adesk_str_instr($b['match'], $source) ) {
				$code = $b;
				break;
			}
		}
	} else {
		foreach ($structure->parts as $part) {
			if (!isset($part->ctype_primary) || !isset($part->body) || ($part->ctype_primary != "text" && $part->ctype_primary != "message"))
				continue;

			if (isset($GLOBALS["_hosted_account"])) {
				if (adesk_str_instr("blocked by", $part->body) || adesk_str_instr("blocked using", $part->body) || adesk_str_instr("att.net/block", $part->body)) {
					return bounce_management_parse_log("ip-blacklist", $settings, $source);
				}

				if (adesk_str_instr("delivery time expired", $part->body)) {
					return bounce_management_parse_log("timeout", $settings, $source);
				}

				if (adesk_str_instr("too many connections", $part->body)) {
					return bounce_management_parse_log("too-many-connections", $settings, $source);
				}
			}

			foreach ( $GLOBALS['bouncecodes'] as $b ) {
				if ( adesk_str_instr($b['match'], $part->body) ) {
					$code = $b;
					break;
				}
			}
		}
	}

	if ( !$code ) {
		if (isset($GLOBALS["_hosted_account"])) {
			$code = array(
				"id"       => 999,
				"code"     => "9.9.9",
				"match"    => "anything on hosted",
				"type"     => "hard",
				"descript" => "This will match any bounce that we receive but have no precise match for",
			);

			# Do not return here--we still want to parse this as a hard bounce, whatever it is.
			bounce_management_parse_log('x-mid-code', $settings, $source);
		} else {
			return bounce_management_parse_log('x-mid-code', $settings, $source);
		}
	}
	$settings['codeid'] = $code['id'];

	// get bounce config array
	if ( !isset($GLOBALS['bouncecfg']) ) $GLOBALS['bouncecfg'] = bounce_pipe_find($structure);
	$bounce = $GLOBALS['bouncecfg'];

	// check if we counted him for this campaign
	$countIt = !(int)adesk_sql_select_one('=COUNT', '#bounce_data', "`campaignid` = '$campaignid' AND `subscriberid` = '$subscriberid'");
	// check if it is a duplicate
	$duplicate = ( $subscriber['bounced_date'] == adesk_CURRENTDATE );

	// if we're not counting it as a new bounce, and is duplicate
	if ( !$countIt and $duplicate ) {
		// then drop it
		return bounce_management_parse_log('x-mid-duplicate', $settings, $source);
	}

	// add this bounce to campaign if first one
	if ( !$countIt ) return true;

	// store it to log
	$parselogid = bounce_management_parse_log('', $settings, $source);

	$insert = array(
		'id'            => 0,
		'email'         => $email,
		'subscriberid'  => $subscriberid,
		'listid'        => (int)$subscriber['listid'],
		'campaignid'    => $campaignid,
		'messageid'     => $messageid,
		'=tstamp'       => "NOW()",
		'type'          => $code['type'],
		'code'          => $code['code'],
		'counted'       => (int)$countIt,
	);

	# Try to find a reason for the bounce in the status header, if it's available in the DSN.
	$match = array();
	if (preg_match('/^Status:\s+\d+\.\d+\.\d+\s+(.*)$/im', $source, $match)) {
		$insert["reason"] = trim(trim($match[1]), "()");
	}

	if (isset($GLOBALS["_hosted_account"]) && isset($insert["reason"])) {
		# AT&T
		if (strpos($insert["reason"], "att.net/block") !== false) {
			$ins = array(
				"code" => 1,
				"=tstamp" => "NOW()",
			);

			adesk_sql_insert("#delay", $ins);

			$ins = array(
				"code" => 1,
				"=tstamp" => "NOW()",
				"reason" => "att block initiated on $email ($insert[reason])",
			);

			adesk_sql_insert("#delay_log", $ins);
		}
	}

	adesk_sql_insert('#bounce_data', $insert);
	$bounced_id = adesk_sql_insert_id();

	$bouncefield = "hardbounces";
	if ($code["type"] == "soft")
		$bouncefield = "softbounces";

	adesk_sql_query("
		UPDATE
			#campaign
		SET
			`$bouncefield` = `$bouncefield` + 1
		WHERE
			id = '$campaignid'
	");
	// try this as well, doesn't hurt
	adesk_sql_query("
		UPDATE
			#campaign_deleted
		SET
			`$bouncefield` = `$bouncefield` + 1
		WHERE
			id = '$campaignid'
	");

	adesk_sql_query("
		UPDATE
			#campaign_message
		SET
			`$bouncefield` = `$bouncefield` + 1
		WHERE
			campaignid = '$campaignid'
		AND
			messageid = '$messageid'
	");

	// add this bounce to subscriber if first one today
	if ( $duplicate ) return true;
	if ( $code['type'] == 'hard' and $subscriber['bounced_hard'] + 1 < $bounce['limit_hard'] ) { // hard bounce
		$update = array(
			'=bounced_hard' => 'bounced_hard + 1',
			'=bounced_date' => 'CURDATE()',
		);
		adesk_sql_update('#subscriber', $update, "id = '$subscriberid'");
	} elseif ( $code['type'] == 'soft' and $subscriber['bounced_soft'] + 1 < $bounce['limit_soft'] ) { // soft bounce
		$update = array(
			'=bounced_soft' => 'bounced_soft + 1',
			'=bounced_date' => 'CURDATE()',
		);
		adesk_sql_update('#subscriber', $update, "id = '$subscriberid'");
	} else {
		// limit reached, mark the subscriber as bounced
		//adesk_sql_update_one("#subscriber_list", "status", 3, "id = '$subscriberid'");
		$update = array(
			'status' => 3,
			'=udate' => "NOW()"
		);
		adesk_sql_update("#subscriber_list", $update, "subscriberid = '$subscriberid'");
		// limit reached, delete the subscriber
		//subscriber_delete($subscriber['id']);
	}
	// save result into $GLOBALS['__bounce_result']
	//dbg($GLOBALS['__bounce_result'], 1);
	return true;
}

function bounce_management_parse_error($structure, $source) {
	$settings = array(
		'subscriberid' => 0,
		'campaignid' => 0,
		'messageid' => 0,
		'codeid' => 0,
		'email' => '',
	);
	bounce_management_parse_log('structure', $settings, $source);
}

function bounce_management_parse_log($error, $settings, $source = null) {
	// get bounce config array
	if ( !isset($GLOBALS['bouncecfg']) ) $GLOBALS['bouncecfg'] = bounce_pipe_find(adesk_mail_extract($source));
	$bounce = $GLOBALS['bouncecfg'];
	// print out the result
	if ( adesk_POP3_DEBUG ) {
		if ( $error ) {
			$errorStrings = bounce_management_parse_log_errors();
			$msg = ( isset($errorStrings[$error]) ? $errorStrings[$error] : $error );
			if ( $settings['email'] ) {
				adesk_flush(sprintf(_a('Email %s NOT parsed as bounce! Error: %s'), $settings['email'], $msg) . '<br />');
			} else {
				adesk_flush(sprintf(_a('Email appears to be an improperly structured bounce message. Error: %s'), $msg) . '<br />');
			}
		} else {
			adesk_flush(sprintf(_a('Email %s parsed as bounced.'), $settings['email']) . '<br />');
		}
	}
	// just drop the message unless we store errors
	if ( $GLOBALS['site']['log_error_source'] ) {
		if ( $error ) store_email_error('bounce', $error, $source);
	}
	// add to bounce log
	$arr = array(
		'id' => 0,
		'=tstamp' => 'NOW()',
		'bounceid' => $bounce['id'],
		'subscriberid' => $settings['subscriberid'],
		'campaignid' => $settings['campaignid'],
		'messageid' => $settings['messageid'],
		'codeid' => $settings['codeid'],
		'email' => $settings['email'],
		'error' => $error,
		'source' => $source,
	);
	$sql = adesk_sql_insert('#bounce_log', $arr);
	if ( !$sql ) return 0;
	$id = adesk_sql_insert_id();
	// try to delete more than 50
	$total = (int)adesk_sql_select_one('=COUNT(*)', '#bounce_log', "`bounceid` = '$bounce[id]'") - 50;
	if ( $total > 0 ) {
		adesk_sql_query("DELETE FROM #bounce_log WHERE `bounceid` = '$bounce[id]' ORDER BY `tstamp` ASC LIMIT $total");
	}
	return $id;
}

function bounce_management_parse_log_errors() {
	return array(
		'structure' => _a('The bounce email does not seem to have an email structure we can read and parse.'),
		'x-mid-value' => _a('X-mid header not found.'),
		'x-mid-elements' => _a('X-mid header does not seem to be valid. Perhaps the campaign was sent using an old version?'),
		'x-mid-campaignid' => _a('Campaign ID not found (probably a test campaign).'),
		'x-mid-campaign' => _a('Campaign not found.'),
		'x-mid-code' => _a('Bounce code not found.'),
		'x-mid-subscriber' => _a('Subscriber with given email address is not found'),
		'x-mid-duplicate' => _a('Subscriber appears to have already bounces during this campaign.'),
		'' => _a('Successfully parsed as bounce.'),
	);
}

function bounce_management_log($id) {
	$r = array(
		'cnt' => 0,
		'log' => array()
	);
	//$admin = adesk_admin_get();
	if ( $id = (int)$id ) {
		$so = new adesk_Select();
		$so->push("AND b.bounceid = '$id'");
		$so->orderby("b.tstamp DESC");
		$r['log'] = bounce_log_select_array($so);
		if($r['log']) $r['cnt'] = count($r['log']);
	}
	return $r;
}

?>
