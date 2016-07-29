<?php

require_once awebdesk_classes("select.php");
require_once dirname(__FILE__) . '/emailaccount_log.php';

function emailaccount_select_query(&$so) {
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		$uid = $admin['id'];
		if ( $admin['id'] > 1 ) {
			if ( !isset($so->permsAdded) ) {
				$so->permsAdded = 1;
				
				if($uid != 1 ) {
				$lists2 = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");
					$so->push("AND l.listid IN ('" . implode("', '", $lists2) . "')");
					
				}
				else
				{
					
					$so->push("AND l.listid IN ('" . implode("', '", $admin['lists']) . "')");
				}
				
				
				
				//$so->push("AND l.listid IN ('" . implode("', '", $admin['lists']) . "')");
			}
		}
	}
	return $so->query("
		SELECT
			e.*,
			COUNT(l.id) AS lists
		FROM
			#emailaccount e
		LEFT JOIN
			#emailaccount_list l
		ON
			e.id = l.emailid
		WHERE
			[...]
		GROUP BY
			e.id
	");
}

function emailaccount_select_prepare($row) {
	if ( !$row ) return $row;
	if ( $row['pass'] != '' ) $row['pass'] = base64_decode($row['pass']);
	$cond = '';
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		if ( $admin['id'] != 1 ) {
			//$admin['lists'][0] = 0;
			$cond = "AND l.listid IN ('" . implode("', '", $admin['lists']) . "')";
		}
	}
	$row['lists'] = adesk_sql_select_list("SELECT l.listid FROM #emailaccount_list l WHERE l.emailid = '$row[id]' $cond");
	$row['listslist'] = implode('-', $row['lists']);
	return $row;
}

function emailaccount_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND e.id = '$id'");

	$r = adesk_sql_select_row(emailaccount_select_query($so));
	if ( $r ) {
		$r = emailaccount_select_prepare($r);
		$r['lists'] = implode("-", $r['lists']);
	}
	return $r;
}

function emailaccount_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND e.id IN ('$ids')");
	}
	return adesk_sql_select_array(emailaccount_select_query($so));
}

function emailaccount_select_array_paginator($id, $sort, $offset, $limit, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'emailaccount'");
		$so->push($conds);
	}

	$so->count();
	$total = (int)adesk_sql_select_one(emailaccount_select_query($so));

	switch ($sort) {
		default:
		case "01":
			$so->orderby("e.action"); break;
		case "01D":
			$so->orderby("e.action DESC"); break;
		case "02":
			$so->orderby("email"); break;
		case "02D":
			$so->orderby("email DESC"); break;
		case "03":
			$so->orderby("host"); break;
		case "03D":
			$so->orderby("host DESC"); break;
		case "04":
			$so->orderby("user"); break;
		case "04D":
			$so->orderby("user DESC"); break;
		case "05":
			$so->orderby("lists"); break;
		case "05D":
			$so->orderby("lists DESC"); break;
	}

	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = emailaccount_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function emailaccount_filter_post() {
	$whitelist = array("e.type", "e.action", "e.email", "e.host", "e.user");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "emailaccount",
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
			if (!in_array($sect, $whitelist)) {
				continue;
			}
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
			sectionid = 'emailaccount'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

function emailaccount_insert_post() {
	// find parents
	if ( isset($_POST['p']) and is_array($_POST['p']) and count($_POST['p']) > 0 ) {
		$lists = array_map('intval', $_POST['p']);
	} else {
		return adesk_ajax_api_result(false, _a("You did not select any lists."));
	}

	$admin = adesk_admin_get();
	$ary = emailaccount_prepare_post();
	$ary['id'] = 0;
	$ary['userid'] = (int)$admin['id'];

	// perform checks
	if ( !adesk_str_is_email($ary['email']) ) {
		return adesk_ajax_api_result(false, _a("Email Address is not valid."));
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
	}

	if ( $ary['filterfield'] != 'body' ) $ary['filterfield'] = 'subject';
	if ( !in_array($ary['filtercond'], array('contains', 'notcontains', 'equals', 'equals')) ) $ary['filtercond'] = 'contains';

	$sql = adesk_sql_insert("#emailaccount", $ary);
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Email Account could not be added."));
	}

	$id = adesk_sql_insert_id();

	// list relations
	foreach ( $lists as $l ) {
		if ( $l > 0 ) adesk_sql_insert('#emailaccount_list', array('id' => 0, 'emailid' => $id, 'listid' => $l));
	}
	return adesk_ajax_api_added(_a("Email Account"));
}

function emailaccount_update_post() {
	if ( isset($_POST['p']) and is_array($_POST['p']) and count($_POST['p']) > 0 ) {
		$lists = array_map('intval', $_POST['p']);
	} else {
		return adesk_ajax_api_result(false, _a("You did not select any lists."));
	}

	$ary = emailaccount_prepare_post();
	$id = intval($_POST["id"]);

	// perform checks
	if ( !adesk_str_is_email($ary['email']) ) {
		return adesk_ajax_api_result(false, _a("Email Address is not valid."));
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
	}

	if ( $ary['filterfield'] != 'body' ) $ary['filterfield'] = 'subject';
	if ( !in_array($ary['filtercond'], array('contains', 'notcontains', 'equals', 'equals')) ) $ary['filtercond'] = 'contains';

	adesk_sql_update("#emailaccount", $ary, "id = '$id'");

	// list relations
	$cond = implode("', '", $lists);
	$admincond = '';
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		$admincond = "AND listid IN ('" . implode("', '", $admin['lists']) . "')";
	}
	adesk_sql_delete('#emailaccount_list', "emailid = '$id' AND listid NOT IN ('$cond') $admincond");
	foreach ( $lists as $l ) {
		if ( $l > 0 ) {
			if ( !adesk_sql_select_one('=COUNT(*)', '#emailaccount_list', "emailid = '$id' AND listid = '$l'") )
				adesk_sql_insert('#emailaccount_list', array('id' => 0, 'emailid' => $id, 'listid' => $l));
		}
	}

	return adesk_ajax_api_updated(_a("Email Account"));
}

function emailaccount_delete($id) {
	$id = intval($id);
	$admincond = '';
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		$admincond = "AND listid IN ('" . implode("', '", $admin['lists']) . "')";
	}
	adesk_sql_delete('#emailaccount_list', "emailid = '$id' $admincond");
	if ( adesk_sql_select_one('=COUNT(*)', '#emailaccount_list', "emailid = '$id'") == 0 ) {
		adesk_sql_delete('#emailaccount', "id = '$id'");
	}
	return adesk_ajax_api_deleted(_a("Email Account"));
}

function emailaccount_delete_multi($ids, $filter = 0) {
	if ( $ids == '_all' ) {
		$tmp = array();
		$so = new adesk_Select();
		$filter = intval($filter);
		if ($filter > 0) {
			$admin = adesk_admin_get();
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'emailaccount'");
			$so->push($conds);
		}
		$all = emailaccount_select_array($so);
		foreach ( $all as $v ) {
			$tmp[] = $v['id'];
		}
	} else {
		$tmp = array_map("intval", explode(",", $ids));
	}
	foreach ( $tmp as $id ) {
		$r = emailaccount_delete($id);
	}
	return $r;
}

function emailaccount_prepare_post() {
	return array(
		'type' => (string)adesk_http_param('type'),
		'action' => (string)adesk_http_param('action'),
		'email' => trim((string)adesk_http_param('email')),
		'host' => trim((string)adesk_http_param('host')),
		'port' => (int)adesk_http_param('port'),
		'user' => trim((string)adesk_http_param('user')),
		'pass' => trim((string)adesk_http_param('pass')),
		'method' => '',
		'emails_per_batch' => (int)adesk_http_param('emails_per_batch'),
		'filteruse' => (int)adesk_http_param('filteruse'),
		'filterfield' => (string)adesk_http_param('filterfield'),
		'filtercond' => (string)adesk_http_param('filtercond'),
		'filterval' => (string)adesk_http_param('filterval'),
	);
}


function emailaccount_run($id, $isTest = 1) {
	$id = (int)$id;
	$r = array(
		'id' => $id,
		'istest' => $isTest,
		'method' => ''
	);
	$row = emailaccount_select_row($id);
	if ( !$row ) {
		return adesk_ajax_api_result(false, _a("Email Account not found."), $r);
	}
	require_once(awebdesk_functions('pop3.php'));
	$r['method'] = adesk_pop3_method_find($row['method'], $row['host'], $row['port'], $row['user'], $row['pass']);
	if ( $r['method'] == '' ) {
		return adesk_ajax_api_result(false, _a("POP3 Connection could not be established."), $r);
	}
	if ( $r['method'] != $row['method'] ) {
		adesk_sql_update_one('#emailaccount', 'method', $r['method'], "`id` = '$id'");
	}
	return adesk_ajax_api_result(true, _a("Email Account successfully connected."), $r);
}



function emailaccount_process($id = 0) {
	if ( !$id ) $id = null;
	adesk_ihook_define("adesk_pop3_parse", "emailaccount_parse");
	adesk_ihook_define("adesk_pop3_error", "emailaccount_parse_error");
	$emails = emailaccount_select_array(null, $id);
	foreach ( $emails as $row ) {
		// store the lists for this email address into global array
		$GLOBALS['__email_row'] = emailaccount_select_prepare($row);
		// this will fetch the messages and process them right away
		// the ihook is written to store all results into a global array
		$GLOBALS['__email_result'] = array();
		adesk_pop3_fetch($row['host'], $row['port'], $row['user'], base64_decode($row['pass']), $row['method'], $row['emails_per_batch']);
		// do something with result messages?
		//dbg($GLOBALS['__email_result'], 1);
	}
	// do something with result messages?
	//dbg($GLOBALS['__email_result'], 1);
}

function emailaccount_parse_hosted($structure, $source) {
	$settings = array(
		'email' => '',
	);
	// extract these email message components
	$filter = array(
		'subject',
		'body',
		//'parts',
		//'ctype',
		//'from',
		'from_name',
		'from_email',
		//'to',
		'to_email',
		'to_name',
		//'attachments',
		//'structure',
	);
	$arr = adesk_mail_extract_components($structure, $filter);
	if ( !isset($arr['from_email']) ) {
		return emailaccount_parse_log('from-missing', $settings, $source);
	}

	$toemail = $arr['to_email'];
	$expl    = explode("@", $toemail);

	if (count($expl) < 2) {
		return emailaccount_parse_log("invalid-to-noatsymbol", $settings, $source);
	}

	$expl    = explode("-", $expl[0]);

	if (count($expl) < 3) {
		return emailaccount_parse_log("invalid-to-nohyphens", $settings, $source);
	}

	$campaignid   = (int)$expl[1];
	$subscriberid = (string)$expl[2];

	if ($subscriberid < 1 || $campaignid < 1) {
		return emailaccount_parse_log("invalid-to-badid", $settings, $source);
	}

	// extract email and full name
	$settings['email'] = $email = (string)adesk_sql_select_one("SELECT email FROM #subscriber WHERE hash = '$subscriberid'");
	if ( !adesk_str_is_email($email) ) {
		return emailaccount_parse_log('from-email', $settings, $source);
	}

	if ( $arr['from_name'] == $arr['from_email'] ) $arr['from_name'] = '';
	$name = explode(' ', $arr['from_name'], 2);
	if ( !isset($name[1]) ) $name[1] = '';
	$first_name = $name[0];
	$last_name = $name[1];

	# Figure out which lists to use based on the campaign id.
	$lists = adesk_sql_select_list("SELECT DISTINCT listid FROM #campaign_list WHERE campaignid = '$campaignid'");

	// (un)subscribe the user!
	require_once(awebdesk_functions('ajax.php'));
	$r = subscriber_unsubscribe($subscriberid, $email, $lists, null, $subscription_form_id = 0, $campaignid, 0);

	// store it to log
	$r['parselogid'] = emailaccount_parse_log('', $settings, $source);
	// save result into $GLOBALS['__email_result']
	//$GLOBALS['__email_result'] = $r;
	return true;
}

function emailaccount_parse($structure, $source) {
	$settings = array(
		'email' => '',
	);
	// get emailaccount config array
	if ( !isset($GLOBALS['__email_row']) ) $GLOBALS['__email_row'] = emailaccount_pipe_find($structure);
	$emailaccount = $GLOBALS['__email_row'];
	if ( !$emailaccount ) {
		return emailaccount_parse_log('emailaccount-missing', $settings, $source);
	}
	// extract these email message components
	$filter = array(
		'subject',
		'body',
		//'parts',
		//'ctype',
		//'from',
		'from_name',
		'from_email',
		//'to',
		'to_email',
		'to_name',
		//'attachments',
		//'structure',
	);
	$arr = adesk_mail_extract_components($structure, $filter);
	if ( !isset($arr['from_email']) ) {
		return emailaccount_parse_log('from-missing', $settings, $source);
	}
	// extract email and full name
	$settings['email'] = $email = $arr['from_email'];
	if ( $arr['from_name'] == $arr['from_email'] ) $arr['from_name'] = '';
	$name = explode(' ', $arr['from_name'], 2);
	if ( !isset($name[1]) ) $name[1] = '';
	$first_name = $name[0];
	$last_name = $name[1];
	if ( !adesk_str_is_email($email) ) {
		return emailaccount_parse_log('from-email', $settings, $source);
	}
	// filter check for current email account
	if ( $emailaccount['filteruse'] ) {
		$matched = true;
		if ( isset($arr[$emailaccount['filterfield']]) ) {
			$matched = false;
			$field = strtolower($arr[$emailaccount['filterfield']]);
			$value = strtolower($emailaccount['filterval']);
			if ( adesk_str_instr('contains', $emailaccount['filtercond']) ) { // contains
				$matched = adesk_str_instr($value, $field);
			} else { // equals
				$matched = $value == $field;
			}
			// not
			if ( adesk_str_instr('not', $emailaccount['filtercond']) ) $matched = !$matched;
		}
		// filter not matched
		if ( !$matched ) {
			return emailaccount_parse_log('filter-mismatch', $settings, $source);
		}
	}
	// extract lists from current email account
	$lists = $emailaccount['lists'];
	// (un)subscribe the user!
	require_once(awebdesk_functions('ajax.php'));
	if ( $emailaccount['action'] == 'unsub' ) {
		$r = subscriber_unsubscribe(0, $email, $lists, null, $subscription_form_id = 0, 0, 0);
	} elseif ( $emailaccount['action'] == 'sub' ) {
		$r = subscriber_subscribe($id = 0, $email, $first_name, $last_name, $lists, $subscription_form_id = 0, $fields = array(), false);
	} else {
		return emailaccount_parse_log($emailaccount['action'], $settings, $source);
	}
	// store it to log
	$r['parselogid'] = emailaccount_parse_log('', $settings, $source);
	// save result into $GLOBALS['__email_result']
	//$GLOBALS['__email_result'] = $r;
	return true;
}

function emailaccount_parse_error($structure, $source) {
	$settings = array(
		'email' => '',
	);
	emailaccount_parse_log('structure', $settings, $source);
}

function emailaccount_parse_log($error, $settings, $source = null) {
	if ( !isset($GLOBALS['__email_row']) ) $GLOBALS['__email_row'] = emailaccount_pipe_find(adesk_mail_extract($source));
	// get emailaccount config array
	$emailaccount = $GLOBALS['__email_row'];
	if ( !$emailaccount ) {
		$emailaccount = array('id' => 0);
	}
	// print out the result
	if ( adesk_POP3_DEBUG ) {
		if ( $error ) {
			$errorStrings = emailaccount_parse_log_errors();
			$msg = ( isset($errorStrings[$error]) ? $errorStrings[$error] : $error );
			if ( $settings['email'] ) {
				adesk_flush(sprintf(_a('Email %s NOT parsed as (un)subscription! Error: %s'), $settings['email'], $msg) . '<br />');
			} else {
				adesk_flush(sprintf(_a('Email appears to be an improperly structured (un)subscription message. Error: %s'), $msg) . '<br />');
			}
		} else {
			adesk_flush(sprintf(_a('Email %s parsed as (un)subscription.'), $settings['email']) . '<br />');
		}
	}
	// just drop the message unless we store errors
	if ( $GLOBALS['site']['log_error_source'] ) {
		if ( $error ) store_email_error('emailaccount', $error, $source);
	}
	$arr = array(
		'id' => 0,
		'=tstamp' => 'NOW()',
		'emailid' => $emailaccount['id'],
		'email' => $settings['email'],
		'error' => $error,
		'source' => $source,
	);
	$sql = adesk_sql_insert('#emailaccount_log', $arr);
	if ( !$sql ) return 0;
	$id = adesk_sql_insert_id();
	// try to delete more than 50
	$total = (int)adesk_sql_select_one('=COUNT(*)', '#emailaccount_log', "`emailid` = '$emailaccount[id]'") - 50;
	if ( $total > 0 ) {
		adesk_sql_query("DELETE FROM #emailaccount_log WHERE `emailid` = '$emailaccount[id]' ORDER BY `tstamp` ASC LIMIT $total");
	}
	return $id;
}

function emailaccount_parse_log_errors() {
	return array(
		'structure' => _a('The (un)subscription email does not seem to have an email structure we can read and parse.'),
		'from-missing' => _a('FROM header is completely missing!'),
		'from-email' => _a('FROM header does not contain a valid email address.'),
		'filter-mismatch' => _a('Email did not satisfy the filter condition.'),
		'emailaccount-missing' => _a('Email account was not found to handle this email.'),
		'' => _a('Successfully parsed as (un)subscription.'),
	);
}

function emailaccount_log($id) {
	$r = array(
		'cnt' => 0,
		'log' => array()
	);
	//$admin = adesk_admin_get();
	if ( $id = (int)$id ) {
		$r['log'] = adesk_sql_select_array("SELECT * FROM #emailaccount_log WHERE emailid = '$id' ORDER BY tstamp DESC");
		$r['cnt'] = count($r['log']);
	}
	return $r;
}

function emailaccount_pipe_find($structure) {
	//find To: header
	$to = adesk_mail_extract_email($structure->headers['to']);

	//query database and find hard/soft bounce settings for that email address
	$toesc = adesk_sql_escape($to);
	$so = new adesk_Select();
	$so->push("AND email = '$toesc'");
	$row = adesk_sql_select_row(emailaccount_select_query($so));
	if ( !$row ) {
		$so->clear();
		$row = adesk_sql_select_row(emailaccount_select_query($so));
	}

	$row = emailaccount_select_prepare($row);

	return $row;

}

?>
