<?php

require_once adesk_admin("functions/filter.php");
require_once adesk_admin("functions/deskrss.php");
require_once adesk_admin("functions/approval.php");
require_once awebdesk_functions("mime.php");
require_once awebdesk_functions("array.php");
require_once(adesk_admin('functions/exclusion.php'));
require_once awebdesk_functions("htmltext.php");
require_once awebdesk_functions("postmarkSpam.php");

function campaign_temp_send($email, $messageid = 0, $type = 'html', $action = 'spamcheck') {
	return campaign_quick_send($email, $campaignid = 0, $messageid, $type, $action);
}

function campaign_ajax_send($email, $campaignid = 0, $messageid = 0, $type = 'html', $action = 'spamcheck') {
	$rval = campaign_quick_send($email, $campaignid, $messageid, $type, $action, true);
	if ( !is_array($rval) ) $rval = array('result' => $rval);
	if ($rval)
		return adesk_ajax_api_result(true, _a("Message sent"), $rval);
	else
		return adesk_ajax_api_result(false, _a("Message not sent"), $rval);
}

function campaign_quick_send($email, $campaignid = 0, $messageid = 0, $type = 'html', $action = 'spamcheck', $inclistamt = false) {
	adesk_php_time_limit(3 * 60);

	// check for testing limits
	if ( isset($GLOBALS['_hosted_account']) and $action == 'test' ) {
		$backend = adesk_sql_select_row("
			SELECT
				`sent_email_test_min_count`,
				`sent_email_test_min_date`,
				`sent_email_test_hr_count`,
				`sent_email_test_hr_date`,
				IF(`sent_email_test_min_date` IS NULL, NULL, UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(`sent_email_test_min_date`)) AS diff_min,
				IF(`sent_email_test_hr_date` IS NULL, NULL, UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(`sent_email_test_hr_date`)) AS diff_hr
			FROM
				#backend
		");
		// check minutes
		if ( $backend['sent_email_test_min_date'] and $backend['diff_min'] < 60 ) { // 60secs = 1min
			if ( $backend['sent_email_test_min_count'] > 5 ) {
				echo "You have exceeded your allowed test messages. Please try again in a couple minutes.";
				exit;
				return false;
			} else {
				adesk_sql_update_one("#backend", "=sent_email_test_min_count", "sent_email_test_min_count + 1");
			}
		} else {
			// just save today's date
			$update = array(
				'=sent_email_test_min_date' => 'NOW()',
				'sent_email_test_min_count' => '1',
			);
			adesk_sql_update("#backend", $update);
		}

		// check days
		if ( $backend['sent_email_test_hr_date'] and $backend['diff_hr'] < 3600 ) { // 3600secs = 1hour
			if ( $backend['sent_email_test_hr_count'] > 100 ) {
				echo "You have exceeded your allowed test messages per hour. Please try again in an hour.";
				exit;
				return false;
			} else {
				adesk_sql_update_one("#backend", "=sent_email_test_hr_count", "sent_email_test_hr_count + 1");
			}
		} else {
			// just save today's date
			$update = array(
				'=sent_email_test_hr_date' => 'NOW()',
				'sent_email_test_hr_count' => '1',
			);
			adesk_sql_update("#backend", $update);
		}
	}


	// recipient
	if ( !adesk_str_is_email($email) ) {
		if ( $email != 'twitter' ) return false;
	}
	// campaign
	if ( $campaignid > 0 ) {
		$campaign = campaign_select_row($campaignid, true);
	} elseif ( $campaignid == -1 ) {
		$campaign = message_post2preparedcampaign();
		$campaignid = 0;
	} else {//if ( $campaignid == 0 ) {
		$campaign = campaign_post2prepared();
	}
	if ( !$campaign['lists'] ) {
		return false;
	}
	// lists
	$lists = array();
	foreach ( $campaign['lists'] as $k => $v ) {
		$lists[] = $v['id'];
	}
	// subscriber
	if ( $email == 'twitter' ) {
		$subscriber = subscriber_exists($email, 0, 'hash');
	} else {
		$subscriber = subscriber_exists($email, $lists);
	}
	// if valid subscriber not found
	if ( !$subscriber ) {
		// create a dummy subscriber
		$subscriber = subscriber_dummy($email, $campaign['lists'][0]['id']);
	}
	// message
	$messagekey = null;
	foreach ( $campaign['messages'] as $k => $v ) {
		if ( $v['id'] == $messageid or $messageid == 0 ) { // if 0 provided, grab the first one
			$messagekey = $k;
			$messageid = $v['id'];
			break;
		}
	}
	if ( is_null($messagekey) ) {
		return false;
	}
	$subscriber['messageid'] = $messageid;
	// figure out format to send
	if ( $campaign['messages'][$messagekey]['format'] == 'mime' ) {
		$campaign['messages'][$messagekey]['format'] = $type;
	}
	$GLOBALS['_sending_charset'] = $campaign['messages'][$messagekey]['charset'];
	// "send" it
	$rval = campaign_send(null, $campaign, $subscriber, $action);

	if ($rval && $inclistamt) {
		foreach ($lists as $listid) {
			$up = array(
				"=list_amt" => "list_amt + 1",
			);

			adesk_sql_update("#campaign_list", $up, "campaignid = '$campaignid' AND listid = '$listid'");
		}
	}

	return $rval;
}

function campaign_spam_emailcheck() {


$r = campaign_quick_send(
		trim((string)adesk_http_param('spamcheckemail')),
		(int)adesk_http_param("campaignid"),
		(int)adesk_http_param('spamcheckemailsplit'),
		trim((string)adesk_http_param('spamcheckemailtype')),
		'spamcheck'
	);
	if ( is_array($r) ) return $r;
	$site = adesk_site_unsafe();
	$ary = array(
		'serial' => $site['serial'],
		'source' => base64_encode($r),
	);
    header("Content-Type: text/xml; charset=utf-8");
	//PostMark SPAM Checker
	//echo ac_http_post('http://emailcheck.example.com/service_xml.php', $ary); // xml version
	echo check_spam_postmark($r);
	exit;
 
}

function campaign_spamcheck($campaignid = 0) {
	$campaignid = (int)$campaignid;
	if ( !$campaignid ) return adesk_ajax_api_result(false, _a("Campaign not found."));

	$messages = adesk_sql_select_box_array("
		SELECT
			m.id,
			m.format
		FROM
			#message m,
			#campaign_message c
		WHERE
			m.id = c.messageid
		AND
			c.campaignid = '$campaignid'
	");
	if ( !$messages ) return adesk_ajax_api_result(false, _a("Message(s) not found."));

	$site = adesk_site_unsafe();
	$ary = array(
		'serial' => $site['serial'],
		'source' => '',
	);

	$r = array();
	foreach ( $messages as $mid => $format ) {
		$src = campaign_source_get((int)$campaignid, $mid);
		if ( is_array($src) ) continue;
//$src = str_replace('Section header', 'pills, credit, viagra, drugs, meds, bad credit, $$$, porn, xxx, sex, virus, ', $src);
		$ary['source'] = base64_encode($src);
		//$rval = adesk_http_post('http://emailcheck.awebdesk.com/service_json.php', $ary); // json version
			$rval = check_spam_postmark($src);
		if ( !$rval ) continue;
		//$parse = json_decode($rval); // json version
		$parse = adesk_xml_read($rval);
		$parse["emailcheck"]['mid'] = $mid;
		$r[] = $parse["emailcheck"];
	}

	if ( count($r) != count($messages) ) {
		// not all calls returned
	}

	return adesk_ajax_api_result(true, _a("SpamCheck completed."), array('messages' => $r));
	
	
 
	 
	
	
}

function campaign_send_emailtest() {
	$r = campaign_quick_send(
		trim((string)adesk_http_param('testemail')),
		(int)adesk_http_param("campaignid"),
		(int)adesk_http_param('testemailsplit'),
		trim((string)adesk_http_param('testemailtype')),
		'test'
	);
	if ( is_array($r) ) return $r;
	return adesk_ajax_api_result($r > 0, $r > 0 ? _a("Test Email Sent") : _a("No Test Emails were sent"), array('sent' => $r));
}

function campaign_inboxpreview($campaignid = 0) {
	require_once(awebdesk_functions('emailawebview.php'));

	$campaignid = (int)$campaignid;
	if ( !$campaignid ) return adesk_ajax_api_result(false, _a("Campaign not found."));

	$messages = adesk_sql_select_box_array("
		SELECT
			m.id,
			m.format
		FROM
			#message m,
			#campaign_message c
		WHERE
			m.id = c.messageid
		AND
			c.campaignid = '$campaignid'
	");
	if ( !$messages ) return adesk_ajax_api_result(false, _a("Message(s) not found."));

	$GLOBALS["emailpreview_clients2check"] = array("msoutlook2007", "msoutlook2000_2003", "googlegmail", "mshotmail", "yahoomail", "applemail", "lotusnotes85", "applemail2");

	$r = array();
	foreach ( $messages as $mid => $format ) {
		require(awebdesk('scripts/emailawebview.php'));
		$src = campaign_source_get((int)$campaignid, $mid);
		if ( is_array($src) ) continue;

		$structure = adesk_mail_extract($src);
		if ( !$structure ) continue;

		$filter = array(
			'subject',
			'body',
			'parts',
			'ctype',
			'charset',
			//'from',
			'from_name',
			'from_email',
			//'to',
			'to_email',
			'to_name',
			'attachments',
			//'structure',
		);
		$parsedcampaign = adesk_mail_extract_components($structure, $filter);

		# The contents of adesk_mail_extract_components must be encoded for the current
		# page.  They're not necessarily in UTF-8, either; they'll be encoded in whatever
		# the original message was configured with.  We need to make sure everything
		# lines up or the message here will not display correctly.
		$parsedcampaign["subject"] = adesk_utf_conv($parsedcampaign["charset"], _i18n("utf-8"), $parsedcampaign["subject"]);

		if (isset($parsedcampaign["parts"]["html_charset"])) {
			$parsedcampaign["parts"]["html"] = adesk_utf_conv($parsedcampaign["parts"]["html_charset"], _i18n("utf-8"), $parsedcampaign["parts"]["html"]);
		}

		$parsedcampaign["parts"]["html"] = adesk_str_strip_tag_short($parsedcampaign["parts"]["html"], 'meta');

		// parse the content
		$html = $parsedcampaign["parts"]["html"];

		adesk_emailpreview_check($html);

		$rval = array(
			'mid' => $mid,
			'issues' => array(),
		);
		foreach ( $GLOBALS["emailpreview_clients2check"] as $client ) {
			$thisone = $GLOBALS["emailpreview_clients"][$client];
			$issues = array_sum($thisone["html_result"]["issuescnt"]);
			//if ( $issues ) $rval['issues'][$client] = $issues;
			if ( $issues ) {
				if ( isset($thisone["quickname"]) ) {
					$cn = $thisone["quickname"];
				} else {
					$cn = $thisone['vendor'] . ' ' . $thisone['software'] . ' ' . $thisone['version'];
				}
				$rval['issues'][] = array(
					'clientid' => $client,
					'clientname' => trim($cn),
					'issues' => $issues,
				);
			}
		}
		$r[] = $rval;
	}

	if ( count($r) != count($messages) ) {
		// not all calls returned
	}

	return adesk_ajax_api_result(true, _a("Inbox Preview completed."), array('messages' => $r));
}

function campaign_preview() {
	$campaignid = (int)adesk_http_param("campaignid");
	$messageid = (int)adesk_http_param("messageid");
	$cond = $messageid > 0 ? "AND messageid = '$messageid'" : '';
	$sourceid = (int)adesk_sql_select_one("SELECT id FROM #campaign_source WHERE campaignid = '$campaignid' $cond");
	$source = campaign_source($sourceid);
	if ( is_array($source) ) return $source;
	// get message structure
	$structure = adesk_mail_extract($source);
	if ( !$structure ) {
		return adesk_ajax_api_result(false, _a('Message could not be previewed.'));
	}
	$filter = array(
		'subject',
		//'body',
		'parts',
		'ctype',
		'from',
		'from_name',
		'from_email',
		'to',
		'to_email',
		'to_name',
		'attachments',
		//'structure',
	);
	$r = adesk_mail_extract_components($structure, $filter);
	// figure out charset
	if ( isset($GLOBALS['_sending_charset']) && $GLOBALS['_sending_charset'] ) {
		$charset_in = strtoupper($GLOBALS['_sending_charset']);
		$charset_out = strtoupper(_i18n('utf-8'));
		if ( $charset_in != $charset_out ) {
			$r = adesk_utf_deepconv($charset_in, $charset_out, $r);
		}
	}
	foreach ( $r['attachments'] as $k => $v ) {
		$filehash = md5(0 . $k . $v['name'] . $v['size']);
		//$link = sprintf('download.php?c=%s&a=%s&h=%s', $campaignid, $k, $filehash);
		//$link = sprintf('awebview.php?c=%s&m=%s&s=%s&type=%s&a=%s&h=%s', $campaignid, $messageid, $hash, $type, $k, $filehash);
		//$link = $_SERVER['REQUEST_URI'] . sprintf('&a=%s&h=%s', $k, $filehash);
		$link = "$_SERVER[REQUEST_URI]&a=$k&h=$filehash";
		$r['attachments'][$k]['hash'] = $filehash;
		$r['attachments'][$k]['link'] = $link;
	}
	$r['source'] = $source;
	return $r;
}

function campaign_subscribers($campaignid, $filter) {
	global $admin;
	// turning off some php limits
	@ignore_user_abort(1);
	@ini_set('max_execution_time', 950 * 60);
	@set_time_limit(950 * 60);

	$campaignid = (int)$campaignid;
	$lists = adesk_sql_select_list("SELECT listid FROM #campaign_list WHERE campaignid = '$campaignid'");

	if ( count($lists) == 0 ) {
		return false;
	}

	$cnt   = (int)campaign_subscribers_fetch($lists, (int)$filter, $fetchCount = 1, $offset = 0, $limit = 0, $campaign = null);
	$valid = withinlimits('mail', $GLOBALS['admin']["emails_sent"] + $cnt);

	if (!$valid)
		return _a("Sending this campaign would put you over your sent-email limit.") . " " . _a("I'm afraid we can't proceed.");

	//$r['bapproved'] = (int)( !$admin['send_approved'] and $r['cnt'] < $GLOBALS['subscribers4approval'] );

	return false;
}

function campaign_subscribers_fetch($lists = array(), $filter = 0, $fetchCount = 1, $offset = 0, $limit = 0, $campaign = null) {
	if ( count($lists) == 0 ) return ( $fetchCount ? 0 : array() );

	$status = 1 + (int)( $campaign and $campaign['type'] == 'responder' and $campaign['responder_type'] == 'unsubscribe' );

	if ($fetchCount == 1 && $filter > 0) {
		$so    = campaign_subscribers_select($lists, 0, $status, !$fetchCount, $campaign);
		$conds = filter_compile($filter);
		$so->push("AND l.subscriberid IN (SELECT s.id FROM #subscriber s WHERE $conds)");
	} else {
		$so = campaign_subscribers_select($lists, $filter, $status, !$fetchCount, $campaign);
	}

	// add list filter
	if ( $fetchCount != 2 ) {
		$so->push("AND l.listid IN (" . implode(',', $lists) . ")");
	}

	// fetch according to what was asked for
	// fetchcount = 0 - return a result set of all subscribers included
	// fetchcount = 1 - total count of all subscribers included
	// fetchcount = 2 - counts of all subscribers included, broken down by list
	if ( $fetchCount == 2 ) {
		$r = array();
		foreach ( $lists as $l ) {
			$so->push("AND l.listid = '$l'");
			$so->count('DISTINCT(l.subscriberid)');
			//$qry = adesk_prefix_replace(subscriber_select_query($so));$r[$l] = (int)adesk_sql_select_one($qry);dbg($qry,1);
			$r[$l] = (int)adesk_sql_select_one(subscriber_select_query($so));
			$index = array_search("AND l.listid = '$l'", $so->conds);
			if ( $index !== false && isset($so->conds[$index]) ) unset($so->conds[$index]);
		}
		return $r;
	} elseif ( $fetchCount == 1 ) {
		$so->count('DISTINCT(l.subscriberid)');
		//$so->count();
		//dbg(adesk_prefix_replace(subscriber_select_query($so)));
		//dbg( (int)adesk_sql_select_one(subscriber_select_query($so)) );
		return (int)adesk_sql_select_one(subscriber_select_query($so));
	} else {
		if ( isset($campaign['sendid']) and $campaign['sendid'] > 0 ) {
			$so->push("AND ( SELECT COUNT(*) FROM #x$campaign[sendid] x WHERE x.subscriberid = s.id ) = 0");
			$offset = 0;
		}
		// set offset/limit
		if ( $limit > 0 or $offset > 0 ) {
			if ( $limit == 0 ) $limit = 999999999;
			$so->limit("$offset, $limit");
		}
		$so->usedInSendingEngine = 1;

		if ($campaign["filterid"] > 0)
			$so->push("AND " . filter_compile($campaign["filterid"]));

 		$qry = adesk_prefix_replace(subscriber_select_query($so));
		campaign_sender_log("Query used:\n$qry");
		if ( isset($campaign['id']) and $campaign['id'] > 0 ) {
			campaign_log_save($campaign);
		}
		//dbg($qry);
		return adesk_sql_query($qry);
	}
}

function campaign_subscribers_select($lists = array(), $filter = 0, $status = 1, $order = true, $campaign = null) {
	$so = new adesk_Select();

	// add exclusion list for exact matches
	// subquery
	$so->push("
		AND
			(
				SELECT
					COUNT(*)
				FROM
					#exclusion e,
					#exclusion_list el,
					#subscriber s
				WHERE
					e.id = el.exclusionid
				AND
					el.listid IN (0, " . implode(',', $lists) . ")
				AND
					s.id = l.subscriberid
				AND
					e.matchtype = 'exact'
				AND
					e.email = s.email
			) = 0
	");

	// joins
	//$so->join('#exclusion_list r', array("r.listid = l.listid"));
	//$so->join('#exclusion e', array("e.id = r.exclusionid"));

	// status switch
	if ( !is_null($status) ) $so->push("AND l.status = $status");

	$filter = intval($filter);
	# Figure out if we need to analyze any filters for the campaign.
	campaign_filterize($filter, false);
	// add filter conditions
	// and join with filter table as well if filter is on
	if ( $filter > 0 ) {
		$so->push("AND " . filter_compile($filter));
	}

	// if campaign is passed, add type-related conditions
	if ( $campaign ) {
		// AUTO-RESPONDER
		if ( $campaign['type'] == 'responder' ) {
			// add responders-allowed check
			$so->push("AND l.responder = 1");
			// add responder-sent check
			// subquery
			$so->push("
				AND
					(
						SELECT
							COUNT(*)
						FROM
							#subscriber_responder r
						WHERE
							r.campaignid = '$campaign[id]'
						AND
							r.subscriberid = l.subscriberid
#						AND
#						(
#							r.listid = 0
#						OR
#							r.listid = l.listid
#						)
					) = 0
			");
			// add timeframe check
			$field = ( $campaign['responder_type'] == 'unsubscribe' ? 'udate' : 'sdate' );
			$so->push("
				AND
					ADDDATE(l.$field, INTERVAL $campaign[responder_offset] HOUR)
					BETWEEN
						SUBDATE(NOW(), INTERVAL 1 HOUR)
					AND
						NOW()
			");
		// AUTO-REMINDER
		} elseif ( $campaign['type'] == 'reminder' ) {
			// add matching date condition
			$match_date = campaign_reminder_match($campaign);
			if ( in_array($campaign['reminder_field'], array('sdate', 'udate')) ) {
				$so->push("AND DATE(l.$campaign[reminder_field]) LIKE '$match_date'");
			} elseif ( $fid = (int)$campaign['reminder_field'] ) { // add custom field date check

				// custom field values are free-form, so check a couple of different date formats
				if ( is_array($match_date) ) {
					$match_date_vals = array();
					foreach ($match_date as $val) {
						$match_date_vals[] = "f.val LIKE '$val'";
					}
					$match_date_clause = "(";
					$match_date_clause .= implode(" OR ", $match_date_vals);
					$match_date_clause .= ")";
				}
				else {
					$match_date_clause = "f.val LIKE '$match_date'";
				}

				// subquery
				$so->push("
					AND
						(
							SELECT
								COUNT(*)
							FROM
								#list_field_value f
							WHERE
								f.fieldid = '$fid'
							AND
								f.relid = l.subscriberid
							AND
								$match_date_clause
						) > 0
				");
			} else {
				$so->push("AND 0"); // dummy for nothing
			}
		}
	}

	if ( $order ) {
		// set sending order
		switch ( $GLOBALS['site']['sdord'] ) {
			case 'asc':
				$so->modify('#subscriber_list l', '#subscriber_list l FORCE INDEX (sdate)');
				$so->orderby("l.sdate ASC"); break;
			case 'desc':
				$so->modify('#subscriber_list l', '#subscriber_list l FORCE INDEX (sdate)');
				$so->orderby("l.sdate DESC"); break;
			case 'rand':
			default:
				//$so->modify('#subscriber_list l', '#subscriber_list l FORCE INDEX (randfield)');
				//$so->orderby("randfield");
				$so->orderby("RAND()");
		}
	}
	return $so;
}

function campaign_subscriber_check($campaign, $subscriber) {
	//campaign_sender_log("Checking if subscriber #$subscriber[id] has a valid email address ($subscriber[email])...");
	//if ( !adesk_str_is_email($subscriber['email']) ) return false;
	# Exclusion list for wildcards.
	campaign_sender_log("Checking if subscriber #$subscriber[id] is on a wildcard exclusion list...");
	return !exclusion_match($subscriber["email"], adesk_array_extract($campaign["lists"], "id"));
}

function campaign_subscriber_prepare($campaign, $subscriber, $iteration) {
	campaign_sender_log("Gathering subscriber info...");
	/*
	// find his list
	$list = null;
	foreach ( $campaign['lists'] as $l ) {
		if ( $l['id'] == $subscriber['listid'] ) $list = $l;
	}
	if ( !$list ) $list = $campaign['lists'][0];
	// check subscriber's name
	if ( $subscriber['first_name'] == '' and $subscriber['last_name'] == '' ) {
		$subscriber['first_name'] = $subscriber['name'] = $list['to_name'];
	}
	*/
	// get fields if missing
	if ( !isset($subscriber['fields']) ) {
		campaign_sender_log("Fetching subscriber fields...");
		$subscriber['fields'] = subscriber_get_fields($subscriber['id'], explode('-', $campaign['listslist']), false);
	}
	// break the email address
	$emailarr = explode('@', $subscriber['email'], 2);
	// if message to send is already provided
	if ( isset($subscriber['messageid']) ) {
		$messageid = $subscriber['messageid'];
	} else {
		campaign_sender_log("Assigning message to subscriber...");
		// figure out which message to send to this subscriber
		$messagesCnt = count($campaign['messages']);
 		if ( $campaign['type'] == 'split' and $messagesCnt > 1 ) { // split
			// calculate the message to send based on ratios and iteration
			if ( $campaign['split_type'] != 'even' ) {
				campaign_sender_log("Preparing subscriber for split mailing...");
				// winner
				$cnt = 0;
				$messageid = 0;
				$breakOut = false;
				foreach ( $campaign['messages'] as $k => $v ) {
					$cnt += round(( $v['percentage'] / 100 ) * $campaign['total_amt']);
					if ( $iteration - 1 < $cnt ) {
						$messageid = $v['id'];
						$breakOut = true;
						break;
					}
				}
				// still didn't found any...
				if ( $breakOut and $messageid == 0 ) $messageid = $campaign['messages'][0]['id'];
				campaign_sender_log("Subscriber will receive message #$messageid.");
			} else {
				// even
				$messageIndex = ( $iteration - 1 ) % $messagesCnt;
				if ( !isset($campaign['messages'][$messageIndex]) ) $messageIndex = 0;
				$messageid = $campaign['messages'][$messageIndex]['id'];
			}
		} else {
			// always send first message
			$messageid = $campaign['messages'][0]['id'];
		}
	}
	campaign_sender_log("Preparing subscriber for mailing...");
	// default subscriber info
	$r = array(
		'id' => 0,
		'subscriberid' => $subscriber['id'],
		'listid' => $subscriber['listid'],
		'messageid' => $messageid, // 0-winner, *-will send this message to that user
		'hash' => $subscriber['hash'],
		'email' => $subscriber['email'],
		'email_user' => $emailarr[0],
		'email_host' => $emailarr[1],
		'first_name' => $subscriber['first_name'],
		'last_name' => $subscriber['last_name'],
		'name' => $subscriber['first_name'] . ' ' . $subscriber['last_name'],
		'ip' => $subscriber['ip4'] == '0.0.0.0' ? $subscriber['ip'] : $subscriber['ip4'],
		'sdate' => $subscriber['sdate'],
		'sent' => 0,
	);
	// additional custom fields for this subscriber
	foreach ( $campaign['fields'] as $f ) {
		$r['f' . $f['id']] = ( isset($subscriber['fields'][$f['id']]) ? $subscriber['fields'][$f['id']]['val'] : '' );
	}
	return $r;
}

function campaign_count_set($id, $campaign, $newcount = 0) {
	if ( !isset($campaign['sdate']) or !$campaign['sdate'] ) $campaign['sdate'] = adesk_CURRENTDATETIME;
	// copy counts
	$insert = array(
		'id' => 0,
		'campaignid' => $id,
		'userid' => $campaign['userid'],
		//'=groupid' => "SELECT groupid FROM #user_group WHERE userid = '$campaign[userid]'",
		'groupid' => (int)adesk_sql_select_one('groupid', '#user_group', "userid = '$campaign[userid]'"),
		'amt' => $newcount,
		'=tstamp' => "NOW()",
	);
	$sql = adesk_sql_insert('#campaign_count', $insert);
	if ( !$sql ) return false;
	$newid = (int)adesk_sql_insert_id();

	return $newid;
}

function campaign_init($id, $append = false) {
	$id = (int)$id;
	$campaign = campaign_select_row($id);
	if ( !$campaign ) return false;

	if (isset($GLOBALS["_hosted_account"])) {
		if ($_SESSION[$GLOBALS["domain"]]["down4"] != "nobody")
			return false;
	}

	$append = in_array($campaign['type'], array('responder', 'reminder', 'special'));

	$origAdmin = adesk_admin_get();

	$admin = adesk_admin_get_totally_unsafe($campaign['userid']);
	if ( !$admin ) return false;

	$total = campaign_subscribers_fetch(explode('-', $campaign['listslist']), $campaign['filterid'], $fetchCount = 1, $offset = 0, $limit = 0, $campaign);
	// exit if no subscribers are found
	if ( $total == 0 ) {
		$GLOBALS['admin'] = $origAdmin;
		return false;
	}
	// count message sources and update counts in db
	foreach ( $campaign['messages'] as $k => $v ) {
		// pretend to send - obtain message source
		$source = campaign_quick_send(_a('_t.e.s.t_@example.com'), $id, $v['id'], $v['format'], 'messagesize');
		if ( is_array($source) ) continue;
		$source = @adesk_utf_conv('iso-8859-1', 'utf-8', $source);
		$size = strlen($source);

		$up = array(
			"sourcesize" => $size,
		);

		adesk_sql_update("#campaign_message", $up, "messageid = '$v[id]' AND campaignid = '$id'");

		$in = array(
			"id" => 0,
			"campaignid" => $id,
			"messageid" => $v['id'],
			"type" => 'original',
			"len" => $size,
		);
		adesk_sql_insert("#campaign_source", $in);
		$srcid = (int)adesk_sql_insert_id();

		campaign_source_save($srcid, $source, $size);
	}
	// set sending/preparing status
	if ( $append ) {
		$update = array(
			'status' => 2,
			'mail_transfer' => 0,
			'mail_send' => 0,
			'mail_cleanup' => 0,
			'send_amt' => $campaign['total_amt'],
			'total_amt' => $campaign['total_amt'] + $total,
			'=ldate' => 'NULL',
		);
	} else {
		$update = array(
			'status' => 2,
			'mail_transfer' => 0,
			'mail_send' => 0,
			'mail_cleanup' => 0,
			'send_amt' => 0,
			'total_amt' => $total,
			'=ldate' => 'NULL',
		);
	}
	#adesk_sql_update('#campaign', $update, "id = '$id'");
	// create a temp table
	$campaign['fields'] = list_get_fields(explode('-', $campaign['listslist']), true); // grab all custom fields
	$customFieldsSQL = '';
	foreach ( $campaign['fields'] as $v ) {
		$customFieldsSQL .= "`f$v[id]` text NOT NULL, ";
	}
	// if we allow sending to duplicates, don't use unique emails
	$uniqueCond = ( !$campaign['p_duplicate_send'] ? ", UNIQUE KEY `email` (`email`)" : '' );
	// recheck the campaign
	$c2 = adesk_sql_select_row("SELECT * FROM `#campaign` WHERE `id` = '$id'"); // get campaign
	if ( !$c2 ) {
		$GLOBALS['admin'] = $origAdmin;
		return false; // check if campaign exists
	}
	if ( $c2['status'] == 2 ) {
		$GLOBALS['admin'] = $origAdmin;
		return false; // check if campaign is in sending phase
	}
	$campaign = array_merge($campaign, $c2);
	// if a campaign is deskrss, check if there's any new feeds here
	if ( $campaign['type'] == 'deskrss' ) {
		// we always have only one message
		$message = $campaign['messages'][0];
		// check html version for rss feeds
		if ( $message['format'] != 'text' ) deskrss_parse($campaign, $message, true, false, 'send');
		// collect the number of found
		$found = $GLOBALS['deskrss_items_found'];
		// if none found
		if ( !$found ) {
			// check text version as well
			if ( $message['format'] != 'html' ) deskrss_parse($campaign, $message, false, false, 'send');
			// collect the number of found
			$found = $GLOBALS['deskrss_items_found'];
		}
		// if none are found
		if ( !$found ) {
			// update ldate here so they know we did check
			$newsenddate = campaign_nextsend($campaign['sdate'], $campaign['deskrss_interval'], false);
			$up = array(
				'sdate' => $newsenddate,
				'=ldate' => 'NOW()',
			);
			adesk_sql_update('#campaign', $up, "`id` = '$id'");
			//adesk_sql_update_one('#campaign', '=ldate', 'NOW()', "`id` = '$id'");
			$GLOBALS['admin'] = $origAdmin;
			return false; // keep waiting
		}
	}
	// check the limits again
	$withinLimit = withinlimits('mail', $admin["emails_sent"] + $total);
	if ( !$withinLimit ) {
		// update ldate here so they know we did check
		adesk_sql_update_one('#campaign', '=ldate', 'NOW()', "`id` = '$id'");
		$GLOBALS['admin'] = $origAdmin;
		return false; // keep waiting
	}
	/*
		FINISHED WITH CHECKS
		INITIALIZE THE CAMPAIGN FOR SENDING
	*/
	$sendid = campaign_count_set($id, $campaign, $total);
	if ( !$sendid ) {
		return false;
	}
	// table name
	$table = adesk_prefix('x' . $sendid);
	// try to drop it
	adesk_sql_query("DROP TABLE IF EXISTS `$table`");
	$engine = "InnoDB";
	if (!adesk_sql_supports_engine("InnoDB"))
		$engine = "MyISAM";
	// construct a query
	$query = "
		CREATE TABLE `$table` (
			`id` int(10) NOT NULL auto_increment,
			`subscriberid` int(10) NOT NULL default '0',
			`listid` int(10) NOT NULL default '0',
			`messageid` int(10) NOT NULL default '0',
			`hash` varchar(32) NOT NULL default '',
			`email` varchar(250) NOT NULL default '',
			`email_user` varchar(250) NOT NULL default '',
			`email_host` varchar(250) NOT NULL default '',
			`first_name` varchar(250) NOT NULL default '',
			`last_name` varchar(250) NOT NULL default '',
			`name` varchar(250) NOT NULL default '',
			`ip` varchar(15) NOT NULL default '',
			`sdate` datetime NULL,
			$customFieldsSQL
			`sent` tinyint(1) NOT NULL default '0',
			PRIMARY KEY  (`id`),
			KEY `subscriberid` (`subscriberid`),
			KEY `sent` (`sent`),
			KEY `messageid` (`messageid`),
			KEY `email_user` (`email_user`),
			KEY `email_host` (`email_host`)
			$uniqueCond
		) ENGINE=$engine DEFAULT CHARSET=utf8
	";
	// creating the temporary table
	$created = adesk_sql_query($query);
	if ( !$created ) {
		$errnum = adesk_sql_error_number();
		$errmsg = adesk_sql_error();
		adesk_flush("Query:\n$query\nProduced #$errnum:\n$errmsg");
		$GLOBALS['admin'] = $origAdmin;
		return false;
	}
	// initiate a process
	require_once(awebdesk_functions('process.php'));
	$processid = adesk_process_create('campaign', $total, $sendid, $init = false, '0000-00-00 00:00:00'/*nulladesk_CURRENTDATETIME*//*$campaign['sdate']*/);
	$update['sendid'] = $sendid;
	adesk_sql_update_one("#campaign_count", "processid", $processid, "id = '$sendid'");
	adesk_sql_update('#campaign', $update, "id = '$id'");
	campaign_update_splittotal($id, $total);
	adesk_process_spawn(array('id' => $processid, 'stall' => 5 * 60));
	// count subscribers and update counts in db
	$counts = campaign_subscribers_fetch(explode('-', $campaign['listslist']), $campaign['filterid'], $fetchCount = 2, $offset = 0, $limit = 0, $campaign);
	foreach ( $counts as $k => $v ) {
		if ( $append ) {
			adesk_sql_update_one('#campaign_list', '=list_amt', "list_amt + $v", "listid = '$k' AND campaignid = '$id'");
		} else {
			adesk_sql_update_one('#campaign_list', 'list_amt', $v, "listid = '$k' AND campaignid = '$id'");
		}
	}
	$GLOBALS['admin'] = $origAdmin;
	return true;
}

function campaign_process($process) {
	if ( isset($GLOBALS['demoMode']) ) return false; // check if demo mode is on

	if (isset($GLOBALS["_hosted_account"])) {
		if ($_SESSION[$GLOBALS["domain"]]["down4"] != "nobody")
			return false;
	}

	$sendid = (int)$process['data']; // extract sending process
	$id = (int)adesk_sql_select_one("campaignid", "#campaign_count", "id = '$sendid'"); // extract campaign to send
	$campaign = campaign_select_row($id, true, false); // get campaign
	if ( !$campaign ) return false; // check if campaign exists
	$admin = adesk_admin_get_totally_unsafe($campaign['userid']);
	if ( !$admin ) return false; // check if user exists
	$campaign['id'] = $id;
	$campaign['sendid'] = $sendid;
	if ( $campaign['status'] != 2 ) return false; // check if campaign is in sending phase
	if ( $campaign['ldate'] ) {
		// overlap check - was active in last two minutes
		if ( adesk_date_sqldiff(adesk_sql_select_one("SELECT NOW()"), $campaign['ldate']) < 2 * 60 ) return false;
	}
	// fetch campaign mailer
	require_once(adesk_admin('functions/mailer/campaignmailer.php'));
	// init the swift mailer's log object
	campaign_log_init($campaign, $process, 'send');
	# Figure out if we need to analyze any filters for the campaign.
	//campaign_filterize((int)$campaign['filterid']); //we need this for transfer, and transfer does it in campaign_subscribers_fetch()

	$resetLDate = false;

	$append = in_array($campaign['type'], array('responder', 'reminder', 'special'));

	// transfer subscribers
	if ( !$campaign['mail_transfer'] ) {
		campaign_sender_log('Preparing the transfer engine...');
		$limit = ( isset($GLOBALS['subsPerRun']) ? (int)$GLOBALS['subsPerRun'] : 0 );
		// fetch all custom fields that will be used
		$campaign['fields'] = list_get_fields(explode('-', $campaign['listslist']), true); // grab all custom fields
		// fetch result set of subscribers to transfer
		campaign_sender_log('Fetching a list of needed subscribers...');

		$countid = (int)adesk_sql_select_one("id", "#campaign_count", "campaignid = '$id' ORDER BY id DESC");

		// refetch the process
		$process = adesk_process_get($process['id']);
		// return if process is already running
		if ( !$process['stall'] or $process['stall'] < 4 * 60 ) return false;
		adesk_process_update($process['id'], false); // save the process in case of stall (long action ran up there)
		$offset = (int)$process['completed'];

		$sql = campaign_subscribers_fetch(explode('-', $campaign['listslist']), $campaign['filterid'], $fetchCount = 0, $offset, $limit, $campaign);
		if ( !$sql ) {
			// todo: what to do here if a list of subscribers returns an error/blank
			$err = adesk_sql_error_number() . ': ' . adesk_sql_error();
			campaign_sender_log("!!! [+] Setting HARD (campaign) STOP: subscriber list could not be fetched ($err) !!!");
			campaign_log_save($campaign);
			return false;
		}
		$cnt = adesk_sql_num_rows($sql);
		campaign_sender_log("Starting a subscriber loop (fetched $cnt in this batch)...\n");
		while ( $row = mysql_fetch_assoc($sql) ) {
			campaign_log_save($campaign);
			// recheck the campaign
			$c2 = adesk_sql_select_row("SELECT * FROM `#campaign` WHERE `id` = '$id'"); // get campaign
			if ( !$c2 ) {
				campaign_sender_log("!\$c2");
				return false; // check if campaign exists
			}
			if ( $c2['status'] != 2 ) {
				campaign_sender_log("\$c2['status'] != 2 (campaign is in sending phase)");
				return false; // check if campaign is in sending phase
			}
			$campaign = array_merge($campaign, $c2);
			// check for partial exclusion list matches
			if ( campaign_subscriber_check($campaign, $row) ) {
				campaign_sender_log("Checking if subscriber #$row[id] is already transferred...");
				// duplicate check
				$found = adesk_sql_select_one('=COUNT(*)', "#x$sendid", "`subscriberid` = '$row[id]'");
				if ( $found ) {
					campaign_sender_log("Skipping this subscriber.");
					campaign_log_save($campaign);
					continue;
				}
				$offset++;
				// transfer the subscriber
				campaign_sender_log("TRANSFERRING SUBSCRIBER $row[email]:");
				$tid = campaign_transfer($campaign, $row, $offset);
				// update process
				adesk_process_update($process['id']);
				$process['completed']++;
				// update campaign
				adesk_sql_update('#campaign', array('=send_amt' => '`send_amt` + 1', '=ldate' => 'NOW()'), "`id` = '$id'");
				campaign_sender_log("Subscriber transferred (#$row[id] => X$tid).\n");
			} else {
				// omit the subscriber -- decrement the total number(s)
				// update process
				$process['total']--;
				if ( !$process['total'] ) {
					return false;
				}
				$process['percentage'] = $process['completed'] / $process['total'];
				$process['remaining'] = $process['total'] - $process['completed'];
				adesk_sql_update('#process', array('total' => $process['total'], 'percentage' => $process['percentage']), "id = '$process[id]'");
				// update campaign
				$campaign['total_amt']--;
				adesk_sql_update('#campaign', array('=total_amt' => '`total_amt` - 1', '=ldate' => 'NOW()'), "`id` = '$id'");
				campaign_sender_log("Subscriber omitted (#$row[id]).\n");
			}
			campaign_log_save($campaign);
		}
		// recheck the campaign
		$c2 = adesk_sql_select_row("SELECT * FROM `#campaign` WHERE `id` = '$id'"); // get campaign
		if ( !$c2 ) {
			campaign_sender_log("!\$c2");
			return false; // check if campaign exists
		}
		if ( $c2['status'] != 2 ) {
			campaign_sender_log("\$c2['status'] != 2 (campaign is in sending phase)");
			return false; // check if campaign is in sending phase
		}
		$campaign = array_merge($campaign, $c2);
		// if last subscriber - all are transfered
		if ( $process['completed'] >= $process['total'] or $limit == 0 ) {
			campaign_sender_log("\n\nTransfer completed!\n");
			// reset the LDate so it can continue with sending right away
			$resetLDate = true;
			// fetch the number of transfered subscribers
			$total_amt = (int)adesk_sql_select_one('=COUNT(*)', '#x' . $sendid);
			$campaign['mail_transfer'] = 1;
			// sent amount will be 0 if starting, and previous total count if appending
			$campaign['send_amt'] = ( $append ? $campaign['total_amt'] - $process['total'] : 0 );
			// sent amount will be the number of transfered, plus the previous total count if appending
			$campaign['total_amt'] = $total_amt + $campaign['send_amt'];
			// update campaign in database
			adesk_sql_update(
				'#campaign',
				array(
					'mail_transfer' => 1,
					'send_amt' => $campaign['send_amt'],
					'total_amt' => $campaign['total_amt']
				),
				"id = '$id'"
			);
			// also update campaign counts
			adesk_sql_update_one('#campaign_count', 'amt', $campaign['total_amt'], "id = '$countid'");
			campaign_update_splittotal($id, $campaign["total_amt"]);
			// reset the process so sending can start
			$process['completed'] =
			$process['percentage'] = 0;
			$process['total'] = $total_amt;
			// and in database
			adesk_sql_update(
				'#process',
				array(
					'completed' => 0,
					'percentage' => 0,
					'total' => $total_amt
				),
				"`id` = '$process[id]'"
			);
			campaign_sender_log('Campaign(/Process) prepared for sending!');

			// approval engine
			$approved = isset($GLOBALS['_hosted_account']) ? false : $admin['send_approved'];
			if ( !$approved ) {
				if ( $cnt < $GLOBALS['subscribers4approval'] and !in_array($campaign['type'], array('responder', 'reminder')) ) {
					$approved = true;
				}
			}
			// if this campaign needs approval
			if ( !$approved ) {
				adesk_sql_update_one("#campaign", "status", CAMPAIGN_STATUS_PENDING_APPROVAL, "id = '$id'");
				$campaign = campaign_select_row($id);
				// add it to the approval queue
				approval_add($campaign, $admin);
				// notify approvers
				approval_notify($campaign, $admin);
				return false;
			}
		}
	}
	campaign_log_save($campaign);
	// recheck the campaign
	$c2 = adesk_sql_select_row("SELECT * FROM `#campaign` WHERE `id` = '$id'"); // get campaign
	if ( !$c2 ) {
		campaign_sender_log("!\$c2");
		return false; // check if campaign exists
	}
	if ( $c2['status'] != 2 ) {
		campaign_sender_log("\$c2['status'] != 2 (campaign is in sending phase)");
		return false; // check if campaign is in sending phase
	}
	$campaign = array_merge($campaign, $c2);
	if ( $resetLDate ) $campaign['ldate'] = null;
	// send campaign
	if ( $campaign['mail_transfer'] and !$campaign['mail_send'] ) {
		adesk_process_update($process['id'], false); // save the process in case of stall (long action ran up there)
		// this function stalls!
		campaign_send($process, $campaign, null, 'send');
		campaign_sender_log("Batch sent.\n");
		// refetch the process
		$process = adesk_process_get($process['id']);
		if ( $process['completed'] >= $process['total'] ) {
			// update campaign
			adesk_sql_update_one('#campaign', 'mail_send', 1, "id = '$id'");
			$campaign['mail_send'] = 1;
		}
	}
	campaign_log_save($campaign);
	// recheck the campaign
	$c2 = adesk_sql_select_row("SELECT * FROM `#campaign` WHERE `id` = '$id'"); // get campaign
	if ( !$c2 ) {
		campaign_sender_log("!\$c2");
		return false; // check if campaign exists
	}
	if ( $c2['status'] != 2 ) {
		campaign_sender_log("\$c2['status'] != 2 (campaign is in sending phase)");
		return false; // check if campaign is in sending phase
	}
	$campaign = array_merge($campaign, $c2);
	// cleanup campaign
	if ( $campaign['mail_send'] and !$campaign['mail_cleanup'] ) {
		campaign_cleanup($campaign);
		//adesk_process_remove($process['id']);
		campaign_log_save($campaign);
	} else {
		campaign_sender_log("This test failed: (\$campaign['mail_send'] and !\$campaign['mail_cleanup'])");
		campaign_log_save($campaign);
		return false;
	}
	return true;
}


// this function handles one subscriber row to transfer into campaign's temporary table
function campaign_transfer($campaign, $subscriber, $iteration) {
	$ary = campaign_subscriber_prepare($campaign, $subscriber, $iteration);
	campaign_sender_log("Transferring the subscriber...");
	$r = adesk_sql_insert('#x' . $campaign['sendid'], $ary);
	$id = 0;
	if ( $r ) {
		$id = adesk_sql_insert_id();
	}
	return $id;
}

// this function prepares the campaign for mailing and calls sending engine
// if process is passed, process will be updated as well after every email sent
// campaign should be a result of campaign_select_row()
// subscriber can be: just an ID, a subscriber array, or an array of subscribers; if not provided, temp table is used
// action can be: send (do the send, update the totals), test (just send a test), spamcheck (to return a message source)
// source, preview,
function campaign_send($process, $campaign, $subscriber = null, $action = 'send') {
	if ( in_array($action, array('send', 'copy')) and isset($GLOBALS['demoMode']) ) return false; // check if demo mode is on

	# Don't send anything if we have no messages
	if (!$campaign["messages"])
		return false;

	if (!isset($GLOBALS["_hosted_account"])) {
		$admin = adesk_admin_get();

		if (isset($admin["unsubscribelink"]) && $admin["unsubscribelink"]) {
			$up = array();
			$up["htmlunsub"] = $campaign["htmlunsub"] = 1;
			$up["textunsub"] = $campaign["textunsub"] = 1;

			$up["htmlunsubdata"] = $campaign['htmlunsubdata'] = _a('<div><a href="%UNSUBSCRIBELINK%">Click here</a> to unsubscribe from future mailings.</div>');
			$up["textunsubdata"] = $campaign['textunsubdata'] = _a('Click here to unsubscribe from future mailings: %UNSUBSCRIBELINK%');

			adesk_sql_update("#campaign", $up, "id = '$campaign[id]'");
		}
	}
	// fetch campaign mailer
	require_once(adesk_admin('functions/mailer/campaignmailer.php'));
	// init the swift mailer's log object
	if (isset($GLOBALS["_hosted_account"])) {
		# Block people with expired accounts from sending
		if (time() > strtotime($_SESSION[$GLOBALS["domain"]]["expire"]))
			return;

		# And people with accounts in any non-normal status
		if ($_SESSION[$GLOBALS["domain"]]["down4"] != "nobody")
			return;
	}
	campaign_log_init($campaign, $process, $action);
	campaign_sender_log('Preparing the sending engine (for ' . $action . ')...');
	// prepare campaign fields here
	$campaign['fields'] = list_get_fields(explode('-', $campaign['listslist']), true); // grab all custom fields
	$batch = new CampaignMailer($campaign, $process, $action);
	/*
	// prepare campaign batch mailer (once!)
	if ( isset($GLOBALS['_swift_batch']) ) {
		// copy scenario
		// reset the campaign, set new action
		$batch =& $GLOBALS['_swift_batch'];
		$batch->action = $action;
	} else {
		$GLOBALS['_swift_batch'] = new CampaignMailer($campaign, $process, $action);
		$batch =& $GLOBALS['_swift_batch'];
	}
	*/
	// if subscriber is used, fetch what's needed for this subscriber and assign him as the sole recipient
	$recipients   = array();
	$subscriberid = ( is_array($subscriber) ? ( isset($subscriber['id']) ? $subscriber['id'] : 0 ) : (int)$subscriber );
	if ( $subscriberid > 0 ) { // subscriber id passed
		if ( !isset($subscriber['id']) ) {
			// fetch subscriber and his fields
			$subscriber = subscriber_select_row($subscriberid);
			if ( !$subscriber ) return false;
		}
	}
	// if subscriber is not passed/fetched
	if ( !$subscriber ) {
		campaign_sender_log('Fetching subscribers from temp table...');
		// fetch all remaining subscribers from the temp table
		$recipients = adesk_sql_query("SELECT * FROM #x$campaign[sendid] WHERE sent = 0 AND messageid != 0");
		if ( !$recipients ) return false;
		if ( adesk_sql_num_rows($recipients) == 0 ) {
			// no users returned, check if it has any remaining emails to send to
			$cnt = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #x$campaign[sendid] WHERE sent = 0");
			if ( !$cnt ) {
				// mark this campaign as completed
				$total = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #x$campaign[sendid]");
				if ( $total == 0 ) {
					// there's actually 0 rows in transfer table and we're already in sending stage
					// 2do: decide whether to switch back to transfer or simply close the campaign
				} else {
					$update = array(
						'send_amt' => $total,
						'total_amt' => $total,
						//'mail_send' => 1, // no need, engine will set this later
					);
					adesk_sql_update('#campaign', $update, "id = '$campaign[id]'");
					campaign_update_splittotal($campaign["id"], $total);
					campaign_update_splitsend($campaign["id"], $total);
					$countid = (int)adesk_sql_select_one("id", "#campaign_count", "campaignid = '$campaign[id]' ORDER BY id DESC");
					adesk_sql_update_one('#campaign_count', 'amt', $total, "id = '$countid'");
					if ( $process ) {
						// mark this process as completed
						$update = array(
							'total' => $total,
							'completed' => $total,
							'percentage' => 100,
						);
						adesk_sql_update('#process', $update, "id = '$process[id]'");
					}
				}
			} else {
				// it has some remaining messages in the queue - WINNER scenario
				if ( $campaign['type'] == 'split' and $campaign['split_type'] != 'even' ) {
					if ( !$campaign['split_winner_awaiting'] ) {
						adesk_sql_update_one('#campaign', 'split_winner_awaiting', 1, "id = '$campaign[id]'");
					}
				}
			}
			// then exit sending
			return false;
		}
		// call sql iterator for subscribers
		$batch->setIterator('mysql');
		$cnt = adesk_sql_num_rows($recipients);
		campaign_sender_log("Fetched $cnt subscribers in this batch.");
	} elseif ( isset($subscriber['email']) ) {
		// grab his id
		$subscriberid = ( isset($subscriber['id']) ? $subscriber['id'] : 0 );
		// prepare subscriber (and fields) here
		if ($campaign["filterid"] > 0 and $action == 'send' ) {
			$matches = filter_matches($subscriberid, $campaign["filterid"]);
		}
		else {
			$matches = true;
		}
		if ($matches) {
			$subscriber = campaign_subscriber_prepare($campaign, $subscriber, $campaign['send_amt'] + 1);
			// add him as the only recipient
			$recipients = array($subscriber);
			// call array iterator for only this subscriber
			$batch->setIterator('array');
			// update campaign total too after sending to this subscriber
			$batch->_campaignUpdater['=total_amt'] = '`total_amt` + 1';
		}
	} elseif ( is_array($subscriber) and count($subscriber) ) {
		// support for multiple subscribers at once (setup as an array)
		$recipients = array();
		$offset = 0;
		campaign_sender_log('Filtering subscribers...');
		foreach ( $subscriber as $k => $row ) {
			# Bring their filter cache up-to-date.
			if ($campaign["filterid"] > 0 and $action == 'send' ) {
				$matches = filter_analyze($row["id"], $campaign["filterid"]);
			}
			else {
				$matches = true;
			}
			if ($matches) {
				$offset++;
				// prepare subscriber (and fields) here
				$recipients[] = campaign_subscriber_prepare($campaign, $row, $campaign['send_amt'] + $offset);
			}
		}
		// call array iterator for only these subscribers
		$batch->setIterator('array');
		// update campaign total too after sending
		$batch->_campaignUpdater['=total_amt'] = '`total_amt` + ' . count($recipients);
		// should build a process here?
		//dbg('2do');
	}
	if ( $action != 'send' ) {
		$batch->_campaignUpdater = array(); // don't update the campaign
	}
	// if not sending a single message, check for last update time
	if ( $subscriberid == 0 and $campaign['ldate'] and $action == 'send' ) {
		// overlap check - was active in last two minutes
		if ( adesk_date_sqldiff(adesk_sql_select_one("SELECT NOW()"), $campaign['ldate']) < 2 * 60 ) return false;
	}

	# Don't bother if we don't have anyone.  If $recipients is a resource (e.g. an SQL query)
	# then count will return 1, so this will work in that case, even though it's clearly not an
	# array then.
	if (count($recipients) == 0)
		return 0;

	if ( $campaign['id'] and !$process['id'] and $action == 'send' ) {
		if ( !campaign_count_set($campaign['id'], $campaign, count($recipients)) ) {
			return 0;
		}
	}

	campaign_sender_log('Start Sending!');
	// this sends the mailing
	return $batch->run($recipients, $action);
}

function campaign_cleanup($campaign) {
	campaign_sender_log('Campaign sending completed. Cleaning up the campaign...');
	// if real send, update lastcheck for all rss feeds
	adesk_sql_update_one('#rssfeed', '=lastcheck', 'NOW()', "`campaignid` = '$campaign[id]'");
	// check if we should set recurring mailing
	$copy = campaign_recurr($campaign);
	// if it was fetched at send, save the current version for message archive
	$archived = campaign_archive($campaign);
	// send copy to admin user(s)
	$sent2 = campaign_carboncopy_send($campaign);
	if ( $sent2 > 0 ) {
		campaign_sender_log("Campaign copy sent to $sent2 admin email addresses.");
	}
	// update campaign
	if ( $campaign['id'] > 0 ) {
		$status = ( in_array($campaign['type'], array('single', 'recurring', 'split', 'deskrss', 'text')) ? 5 : 1 );
		adesk_sql_update('#campaign', array('mail_cleanup' => 1, 'status' => $status, '=ldate' => 'NOW()'), "id = '$campaign[id]'");
		adesk_sql_query("DROP TABLE #x$campaign[sendid]");
	}
	// if campaign is special
	if ( $campaign['type'] == 'special' ) {
		// and uses some other campaign id
		if ( $campaign['realcid'] ) {
			// update sent count of real campaign
			if ( !$campaign['total_amt'] and $campaign['send_amt'] ) $campaign['total_amt'] = $campaign['send_amt'];
			$update = array(
				'=send_amt'  => "send_amt + $campaign[send_amt]",
				'=total_amt' => "total_amt + $campaign[total_amt]",
				'=ldate' => "NOW()",
			);
			adesk_sql_update('#campaign', $update, "id = '$campaign[realcid]'");
			campaign_update_splittotal($campaign["realcid"], $campaign["total_amt"]);
			campaign_update_splitsend($campaign["realcid"], $campaign["send_amt"]);
			// update sent count of real campaign's lists
			$lc = adesk_sql_select_box_array("SELECT `listid`, `list_amt` FROM #campaign_list WHERE `campaignid` = '$campaign[id]'");
			foreach ( $lc as $k => $v ) {
				adesk_sql_update_one('#campaign', '=list_amt', "`list_amt` + $v", "`campaignid` = '$campaign[realcid]' AND `listid` = '$k'");
			}
			$countid = (int)adesk_sql_select_one("id", "#campaign_count", "campaignid = '$campaign[realcid]' ORDER BY id DESC");
			adesk_sql_update_one('#campaign_count', 'amt', $campaign['total_amt'], "`id` = '$countid'");
		}
	}
	// try to tweet it
	$tweeted = campaign_tweet($campaign);
	if ( $tweeted ) {
		campaign_sender_log("Campaign tweeted.");
	} elseif ( $campaign['tweet'] ) {
		//campaign_sender_log("Campaign was NOT tweeted.");
	}
	else {
		campaign_sender_log("Campaign was NOT tweeted.");
	}
	// try to facebook it
	$facebooked = campaign_facebook($campaign);
	if ( $facebooked ) {
		campaign_sender_log("Campaign posted to Facebook.");
	} elseif ( $campaign['facebook'] ) {
		//campaign_sender_log("Campaign was NOT sent to Facebook.");
	}
	else {
		campaign_sender_log("Campaign was NOT sent to Facebook.");
	}
	// done, mark it
	campaign_sender_log("Campaign saved.\n\n\n\nCAMPAIGN COMPLETED\n\n\n");
	/*
	campaign_sender_log(print_r($GLOBALS['dbQueries'], 1));
	$arr = array();
	foreach ($GLOBALS['dbQueries'] as $v) {
		if (!isset($arr[$v])) $arr[$v] = 0;
		$arr[$v]++;
	}
	arsort($arr);
	campaign_sender_log(print_r($arr, 1));
	*/
}

function campaign_carboncopy_send($campaign) {
	if ( !$campaign ) return 0;
	if ( !in_array($campaign['type'], array('single', 'recurring', 'split', 'deskrss', 'text')) ) return 0;
	$i = 0;
	$cnt = count($campaign['messages']);
	$recipients = array();
	foreach ( $campaign['lists'] as $l ) {
		if ( $l['carboncopy'] ) {
			// found some emails
			$emails = explode(',', $l['carboncopy']);
			foreach ( $emails as $k => $v ) {
				$email = trim($v);
				// subscriber
				$subscriber = subscriber_exists($email, $l['id']);
				// if valid subscriber not found
				if ( !$subscriber ) {
					// create a dummy subscriber
					$subscriber = subscriber_dummy($email, $l['id']);
				}
				// message
				$subscriber['messageid'] = $campaign['messages'][$i % $cnt]['id'];
				// assign subscriber
				$recipients[] = $subscriber;
				// increase counter
				$i++;
			}
		}
	}
	//dbg($recipients, 1);
	if ( count($recipients) == 0 ) return 0;
	campaign_sender_log("Sending a copy of this mailing...");
	// send copy to all admins
	return campaign_send(null, $campaign, $recipients, 'copy');
}

function campaign_recurr($campaign) {
	if ( !$campaign ) return 0;
	if ( $campaign['type'] != 'recurring' and $campaign['type'] != 'deskrss' ) return 0;
	$recur = ( $campaign['type'] == 'recurring' ? $campaign['recurring'] : $campaign['deskrss_interval'] );
	$date  = ( $campaign['type'] == 'recurring' ? $campaign['sdate'] : $campaign['sdate'] );
	if ( $campaign['type'] == 'deskrss' and $campaign['sdate'] > $campaign['ldate'] ) $date = $campaign['sdate'];

	$date = campaign_nextsend($date, $recur, true);

	// set the thread id if it wasnt set before (this will happen on a first campaign only, when it is done; future ones will reuse it)
	if ( !$campaign['threadid'] ) {
		adesk_sql_update_one("#campaign", "=threadid", "id", "id = '$campaign[id]'");
		$campaign['threadid'] = $campaign['id'];
	}
	// check if the campaign with this send date is already scheduled to go out
	$alreadyThere = adesk_sql_select_one("=COUNT(*)", "#campaign", "threadid = '$campaign[threadid]' AND sdate = '$date'");
	if ( $alreadyThere ) return 0;

	$campaign['sdate'] = $date;
	// reset the last sending date
	if ( isset($campaign['ldate']) ) unset($campaign['ldate']);
	// set status to scheduled
	$campaign['status'] = 1;
	$newid = campaign_copy($campaign, array('=ldate' => 'NULL', 'schedule' => 1, 'willrecur' => 1)); // return an id of a newly created campaign
	if ( !$newid ) return 0;
	campaign_sender_log("Campaign is set to recur on $campaign[sdate].");
	$campaign['id'] = $newid;
	return $campaign;
}

function campaign_archive($campaign) {
	if ( !$campaign ) return;
	if ( !$campaign['id'] ) return;
	if ( !in_array($campaign['type'], array('single', 'recurring', 'split', 'deskrss', 'text')) ) return;
	campaign_sender_log("Archiving the campaign...");
	// check if any message was fetched customized or @send
	foreach ( $campaign['messages'] as $row ) {
		$update = array();
		if ( isset($row['htmlfetchurl']) && $row['htmlfetchurl'] ) {
			$update['html'] = adesk_http_get($row['htmlfetchurl'], "UTF-8");
			$update['html'] = message_link_resolve($update['html'], $row['htmlfetchurl']);
			if ( $row['subject'] == '' ) {
				// try to find the title
				preg_match('/<title>(.*)<\/title>/i', $update['html'], $matches);
				if ( isset($matches[1]) ) $update['subject'] = $matches[1];
			}
		}
		if ( isset($row['textfetchurl']) && $row['textfetchurl'] ) {
			$update['text'] = adesk_http_get($row['textfetchurl'], "UTF-8");
		}
		// if any were fetched, update the campaign
		if ( count($update) > 0 ) {
			adesk_sql_update('#campaign', $update, "`id` = '$campaign[id]'");
			//adesk_sql_update('#message', $update, "`id` = '$row[id]'");
		}
	}
	campaign_sender_log("Campaign archived.");
}

function campaign_scheduler() {
	// select campaigns that are scheduled
	$so = new adesk_Select();
	// mailing
	$so->push("AND c.type IN ('single', 'recurring', 'split', 'deskrss', 'text')");
	// scheduled
	$so->push("AND c.status = '1'");
	// for now
	$so->push("AND c.sdate <= NOW()");
	// fetch only id's
	$so->slist = array('id', 'name');
	// fetch
	$sql = adesk_sql_query(campaign_select_query($so));
	while ( $row = adesk_sql_fetch_assoc($sql) ) {
		// initiate
		//campaign_sender_log("Initiating Campaign #$row[id] : $row[name]");
		campaign_init($row['id'], false);
	}
}

function campaign_split_winner() {
	// select campaigns that are sent x hours before now
	$so = new adesk_Select();
	// split mailing
	$so->push("AND c.type = 'split'");
	// winner scenario
	$so->push("AND c.split_type != 'even'");
	$so->push("AND c.split_offset > 0");
	// currently sending
	$so->push("AND c.status = '2'");
	$so->push("AND c.ldate IS NOT NULL");
	// fetch
	$query = campaign_select_query($so);
	$sql = adesk_sql_query($query);
	while ( $row = adesk_sql_fetch_assoc($sql) ) {
		// try to find a temp table with some subscribers waiting for winner announcement
		$found = (int)adesk_sql_select_one('=COUNT(*)', '#x' . $row['sendid'], "`messageid` = 0");
		if ( $found ) {
			$date2use = $row['sdate'];
			// get offset since last send
			$sendoffset = adesk_date_sqldiff(adesk_CURRENTDATETIME, $date2use);
			// figure out the number of days in this month
			$y = (int)substr($date2use, 0, 4);
			$m = (int)substr($date2use, 5, 2);
			// if february, check if leap year (28/29 switch), otherwise if it has 31 day or 30
			$month = ( $m == 2 ? 28 + (int)($y % 4 == 0) : 30 + (int)($m % 2) );
			// convert to seconds an offset needed for split announcement
			$splitoffset = (int)$row['split_offset'];
			switch ( $row['split_offset_type'] ) {
				default:
				case 'hour':
					$splitoffset *= 60 * 60;
					break;
				case 'day':
					$splitoffset *= 24 * 60 * 60;
					break;
				case 'week':
					$splitoffset *= 7 * 24 * 60 * 60;
					break;
				case 'month':
					$splitoffset *= $month * 24 * 60 * 60;
					break;
			}
			// enough time has passed, determine a winner
			if ( $splitoffset < $sendoffset ) {
				$cond = ( $row['split_type'] == 'read' ? "l.link IN ('open', '')" : "l.link NOT IN ('open', '')" );
				$query = "
					SELECT
						l.messageid,
						SUM(d.times) AS count
					FROM
						#link l
					LEFT JOIN
						#link_data d
					ON
						l.id = d.linkid
					WHERE
						l.campaignid = '$row[id]'
					AND
						l.messageid != 0
					AND
						$cond
					GROUP BY l.messageid
					#HAVING SUM(d.times) IS NOT NULL
					ORDER BY count DESC
					LIMIT 0, 1
				";
				$sql = adesk_sql_query($query);
				if ( $sql and adesk_sql_num_rows($sql) == 1 ) {
					$message = adesk_sql_fetch_assoc($sql);
					$messageid = $message['messageid'];
				} else {
					// if message is still not determined (no data found)
					// get the first found?
					$campaign = campaign_select_prepare($row, true);
					$messageid = $campaign['messages'][0]['id'];
				}
				// set winner message for the remaining subscribers
				adesk_sql_update_one('#x' . $row['sendid'], 'messageid', $messageid, "`messageid` = 0");
				// save winner to campaign's table
				$update = array(
					'split_winner_messageid' => $messageid,
					'split_winner_awaiting'  => 0,
				);
				adesk_sql_update('#campaign', $update, "`id` = '$row[id]'");

				// update the percentage of a winner
				$addon = 100 - (int)adesk_sql_select_one("=SUM(percentage)", "#campaign_message", "campaignid = '$row[id]'");
				adesk_sql_update_one('#campaign_message', '=percentage', "percentage + $addon", "campaignid = '$row[id]' AND messageid = '$messageid'");
			}
		}
	}
}

function campaign_responder() {
	// fetch all non-instant autoresponder campaigns
	$so = new adesk_Select();
	$so->push("AND c.type = 'responder'");
	$so->push("AND c.status IN (1, 5)"); // scheduled or completed (not draft, sending, stopped, paused, etc...)
	$so->push("AND c.responder_offset != 0");
	// fetch only id's
	$so->slist = array('id', 'name');
	$campaigns = campaign_select_array($so);
//dbg($campaigns);
	if ( count($campaigns) == 0 ) return;
	// loop through responders
	foreach ( $campaigns as $campaign ) {
		// initialize campaign for sending
		//campaign_sender_log("Initiating Campaign #$campaign[id] : $campaign[name]");
		campaign_init($campaign['id'], true);
	}
}

// this function sends newly created campaign to old subscribers
// type can be instant or delayed; delayed uses campaign's responder_offset field
function campaign_responder_oldies($id) {
	$campaign = campaign_select_row($id);
	// set this campaign's id as real id
	$campaign['realcid'] = $campaign['id'];
	// set type as special
	$campaign['type'] = 'special';
	// set sending date to instant
	unset($campaign['sdate']);
	$addon = array();
	$addon['=sdate'] = 'NOW()';
	$newid = campaign_copy($campaign, $addon, true);
	if ( !$newid ) return;
	campaign_init($newid, true);
	return $newid;
}

function campaign_reminder($force = false) {
	//if ( !plugin_autoreminder() ) return;
	// fetch all setup autoreminders
	$so = new adesk_Select();
	$so->push("AND c.type = 'reminder'");
	$so->push("AND c.status IN (1, 5)"); // scheduled or completed (not draft, sending, stopped, paused, etc...)
	/*if ( !$force ) {
		$so->push("AND ( c.reminder_last_cron_run < CURDATE() OR c.reminder_last_cron_run IS NULL )");
	}*/
	// fetch only id's
	$so->slist = array('id', 'name');
	$campaigns = campaign_select_array($so);
//dbg($campaigns);
	if ( count($campaigns) == 0 ) return;
	// loop through reminders
	foreach ( $campaigns as $campaign ) {
		// update this campaign so it doesn't run again today
		adesk_sql_update_one('#campaign', '=reminder_last_cron_run', 'CURDATE()', "id = '$campaign[id]'");
		// initialize campaign for sending
		//campaign_sender_log("Initiating Campaign #$campaign[id] : $campaign[name]");
		campaign_init($campaign['id'], true);
	}
}

function campaign_reminder_match($campaign) {
	$r = '';
	// break the current date
	list($year, $month, $day) = explode('-', adesk_CURRENTDATE);
	// apply offset
	$offset = (int)$campaign['reminder_offset'];
	/*
	$mysqlfunc = ( $campaign['reminder_offset_sign'] == '-' ? 'SUBDATE' : 'ADDDATE' );
	switch ( $campaign['reminder_offset_type'] ) {
		case 'year':
			$new_date = "$mysqlfunc(CURDATE(), INTERVAL $offset YEAR)";
			$year += $offset;
			break;
		case 'month':
			$new_date = "$mysqlfunc(CURDATE(), INTERVAL $offset MONTH)";
			$month += $offset;
			break;
		case 'week':
			$new_date = "$mysqlfunc(CURDATE(), INTERVAL $offset WEEK)";
			$day += $offset * 7;
			break;
		case 'day':
		default:
			$new_date = "$mysqlfunc(CURDATE(), INTERVAL $offset DAY)";
			$day += $offset;
			break;
	}
	*/
	// make sure no one sets a different format when using the internal sdate field
	if ( in_array($campaign['reminder_field'], array('sdate', 'udate')) ) {
		$campaign['reminder_format'] = 'yyyy-mm-dd';
	}

	$offset = (int)$campaign['reminder_offset'];
	if ( $offset > 0 ) {
		if ( $campaign['reminder_offset_sign'] == '-' ) $offset = -$offset;
		switch ( $campaign['reminder_offset_type'] ) {
			case 'year':
				$year += $offset;
				break;
			case 'month':
				$month += $offset;
				break;
			case 'week':
				$day += $offset * 7;
				break;
			case 'day':
			default:
				$day += $offset;
				break;
		}
	}
	// calculate new date
	$new_date = mktime(0, 0, 0, $month, $day, $year);

	// prepare the matching string
	$match_date = '';
	// make sure no one sets a different format when using the internal sdate field
	if ( in_array($campaign['reminder_field'], array('sdate', 'udate')) ) {
		$campaign['reminder_format'] = 'yyyy-mm-dd';
	}
	switch ( $campaign['reminder_format'] ) {
		case 'yyyy-mm-dd':
			if ( $campaign['reminder_type'] == 'year_month_day' ) {
				$match_date = date('Y-m-d', $new_date);
			} elseif ( $campaign['reminder_type'] == 'month_day' ) {
				$match_date = array(
					'%' . date('-m-d', $new_date),
					'%' . date('-m-d', $new_date) . '%',
				);
			}
			break;
		case 'yyyy/mm/dd':
			if ( $campaign['reminder_type'] == 'year_month_day' ) {
				$match_date = date('Y/m/d', $new_date);
			} elseif ( $campaign['reminder_type'] == 'month_day' ) {
				$match_date = '%' . date('/m/d', $new_date);
			}
			break;
		case 'yyyymmdd':
			if ( $campaign['reminder_type'] == 'year_month_day' ) {
				$match_date = date('Ymd', $new_date);
			} elseif ( $campaign['reminder_type'] == 'month_day' ) {
				$match_date = '%' . date('md', $new_date);
			}
			break;
		case 'mm/dd/yyyy':
			if ( $campaign['reminder_type'] == 'year_month_day' ) {
				// capture these formats: 03/04/2000 or 3/4/2000
				$match_date = array(
					date('m/d/Y', $new_date),
					date('n/j/Y', $new_date),
				);
			} elseif ( $campaign['reminder_type'] == 'month_day' ) {
				// capture these formats: 03/04/ or 3/4/
				$match_date = array(
					date('m/d/', $new_date) . '%', // "03/04/"
					date('n/j/', $new_date) . '%', // "3/4/"
				);
			}
			break;
		case 'dd/mm/yyyy':
			if ( $campaign['reminder_type'] == 'year_month_day' ) {
				// capture these formats: 04/03/2000 or 4/3/2000
				$match_date = array(
					date('d/m/Y', $new_date),
					date('j/n/Y', $new_date),
				);
			} elseif ( $campaign['reminder_type'] == 'month_day' ) {
				// capture these formats: 04/03/ or 4/3/
				$match_date = array(
					date('d/m/', $new_date) . '%',
					date('j/n/', $new_date) . '%',
				);
			}
			break;
		case 'dd.mm.yyyy':
			if ( $campaign['reminder_type'] == 'year_month_day' ) {
				$match_date = date('d.m.Y', $new_date);
			} elseif ( $campaign['reminder_type'] == 'month_day' ) {
				$match_date = date('d.m.', $new_date) . '%';
			}
			break;
	}
	return $match_date;
}

function campaign_filterize($filterid, $log = true) {
}

function campaign_recover() {
	$sql = adesk_sql_query("
		SELECT
			`id`
		FROM
			`#campaign`
		WHERE
			`status` = 2
		AND
		(
			`mail_send` = 1
		OR
			(
				`mail_send` = 0
			AND
				`total_amt` = `send_amt`
			)
		)
		AND
			`mail_cleanup` = 0
		AND
			`ldate` < SUBDATE(NOW(), INTERVAL 5 MINUTE)
	");
	if ( !$sql ) return;
	if ( !adesk_sql_num_rows($sql) ) return;
	// fetch campaign mailer
	require_once(adesk_admin('functions/mailer/campaignmailer.php'));
	while ( $row = adesk_sql_fetch_assoc($sql) ) {
		adesk_sql_update_one('#campaign', 'mail_send', 1, "id = '$row[id]'");
		$campaign = campaign_select_row($row['id']);
		$campaign['processid'] = campaign_processid($row['id'], 'any');
		// init the swift mailer's log object
		$process = array(
			'id' => $campaign['processid'],
			'data' => array('id' => $campaign['id']),
			'completed' => $campaign['total_amt'],
			'total' => $campaign['total_amt'],
			'percentage' => 100,
			'stall' => 250, // force pickup
		);
		campaign_log_init($campaign, $process, 'send');
		campaign_cleanup($campaign);
		campaign_log_save($campaign);
	}
}

function campaign_now($id) {
	$id = (int)$id;
	// define returning array
	$r = array('id' => $id);
	// check for campaign id
	if ( !$id ) {
		return adesk_ajax_api_result(false, _a('Campaign for sending not provided.'), $r);
	}
	// check if campaign exists
	$campaign = campaign_select_row($id);
	if ( !$campaign ) {
		return adesk_ajax_api_result(false, _a('Campaign for sending not found.'), $r);
	}
	// check if campaign is scheduled
	if ( $campaign['status'] != 1 ) {
		return adesk_ajax_api_result(false, _a('This campaign is not scheduled, therefore can not be sent now.'), $r);
	}
	// check if campaign is "regular"
	if ( !in_array($campaign['type'], array('single', 'split', 'recurring', 'deskrss', 'text')) ) {
		return adesk_ajax_api_result(false, _a('Only regular scheduled campaigns can be sent now.'), $r);
	}
	// update the campaign's sending date
	$sql = adesk_sql_update_one('#campaign', '=sdate', 'NOW()', "id = '$id'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a('Campaign Sending Date could not be updated.'), $r);
	}
	// init the campaign
	$started = campaign_init($id, false);
	if ( !$started ) {
		return adesk_ajax_api_result(false, _a('Campaign could not be initiated for sending.'), $r);
	}
	return adesk_ajax_api_result(true, _a('Campaign initiated for sending.'), $r);
}

function campaign_tweet($campaign) {
	campaign_sender_log("Attempting to share campaign link on Twitter...");
	$sent = array();
	if ( !$campaign['tweet'] ) {
		campaign_sender_log("!\$campaign[tweet]");
		return;
	}
	require_once(awebdesk('functions/twit.php'));
	foreach ( $campaign['lists'] as $list ) {
		if ( isset($sent[$list['id']]) ) continue;
		if ( !$list['p_use_twitter'] ) continue;
		if ( !$list['twitter_token'] or !$list['twitter_token_secret'] ) continue;
		//$url = campaign_url($campaign, $list['stringid']);
		$mid = $campaign['messages'][0]['id'];
		$url = adesk_site_plink("index.php?action=social&c=" . md5($campaign['id']) . "." . $mid . "&ref=twitter");
		$lnk = adesk_bitly($url);
		if ( !$lnk ) continue;
		$msg = substr($campaign['messages'][0]['subject'], 0, 102);
		campaign_sender_log("Tweet: $msg - $lnk");
		$r = adesk_twit_oauth($list["twitter_token"], $list["twitter_token_secret"], "$msg - $lnk");
		$tweetid = 0;
		if ( is_array($r) and isset($r['id']) and $r['id'] ) {
			$tweetid = (int)$r['id'];
		} elseif ( is_object($r) and isset($r->id) and $r->id ) {
			$tweetid = (int)$r->id;
		}
		if ( $tweetid ) {
			$sent[$list['id']] = $list['id'];
			adesk_sql_update_one('#campaign_list', '=list_amt', 'list_amt + 1', "campaignid = '$campaign[id]' AND listid = '$list[id]'");
			campaign_sender_log("Campaign link shared on Twitter (tweet ID: $tweetid)");
		} else {
			campaign_sender_log("\n[+] Tweet failed!!!\n\n" . print_r($r, 1));
		}
	}
	if ( count($sent) ) {
		// add one more subscriber (we sent it to "twitter" email)
		$update = array(
			'=send_amt' => 'send_amt + 1',
			'=total_amt' => 'total_amt + 1',
		);
		adesk_sql_update('#campaign', $update, "id = '$campaign[id]'");
		adesk_sql_update('#campaign_message', $update, "campaignid = '$campaign[id]'");
	}
}

function campaign_facebook($campaign) {
	campaign_sender_log("Attempting to share campaign link on Facebook...");
	require_once adesk_admin("functions/list.php");
	$admin = adesk_admin_get_totally_unsafe($campaign['userid']);
	$sent = array();
	if ( !$campaign['facebook'] ) {
		campaign_sender_log("!\$campaign[facebook]");
		return;
	}
	$facebook_oauth = list_facebook_oauth_init();
	require_once(awebdesk('functions/twit.php'));
	foreach ( $campaign['lists'] as $list ) {
		if ( isset($sent[$list['id']]) ) {
			campaign_sender_log("isset(\$sent[\$list[id]])");
			continue;
		}
		if ( !$list['p_use_facebook'] ) {
			campaign_sender_log("!\$list[p_use_facebook]");
			continue;
		}
		$mid = $campaign['messages'][0]['id'];
		$url = adesk_site_plink("index.php?action=social&c=" . md5($campaign['id']) . "." . $mid . "&ref=facebook");
		$lnk = adesk_bitly($url);
		if ( !$lnk ) {
			campaign_sender_log("!\$lnk");
			continue;
		}
		$msg = $campaign['messages'][0]['subject'];
		$facebook_oauth_session = list_facebook_oauth_getsession($facebook_oauth, $list['id']);
		$facebook_oauth_me = null;
		if ($facebook_oauth_session) {
			$facebook_oauth_me = list_facebook_oauth_me($facebook_oauth, $facebook_oauth_session);
			if ( !$facebook_oauth_me ) {
				campaign_sender_log("!\$facebook_oauth_me");
				continue;
			}
		}
		else {
			campaign_sender_log("!\$facebook_oauth_session");
			continue;
		}
		campaign_sender_log("Facebook update: $msg - $lnk");
		$status = $facebook_oauth->api( "/me/feed", "POST", array("message" => $msg, "link" => $lnk, "picture" => "") );
		campaign_sender_log("Facebook update done.");
		$sent[$list['id']] = $list['id'];
		adesk_sql_update_one('#campaign_list', '=list_amt', 'list_amt + 1', "campaignid = '$campaign[id]' AND listid = '$list[id]'");
	}
	if ( count($sent) ) {
		// add one more subscriber (we sent it to "twitter" email)
		$update = array(
			'=send_amt' => 'send_amt + 1',
			'=total_amt' => 'total_amt + 1',
		);
		adesk_sql_update('#campaign', $update, "id = '$campaign[id]'");
		adesk_sql_update('#campaign_message', $update, "campaignid = '$campaign[id]'");
	}
}

function campaign_nextsend($date, $recur, $log = false) {
	$sqldate = adesk_sql_select_one("SELECT NOW()");
	if ( $log ) {
		campaign_sender_log("Setting up the next recurring campaign (now: $sqldate ; start: $date)...");
		campaign_sender_log("$date < $sqldate = " . (int)( $date <= $sqldate ));
	}
	while ( $date <= $sqldate ) {
		// figure out the number of days in this month
		$y = (int)substr($date, 0, 4);
		$m = (int)substr($date, 5, 2);
		// if february, check if leap year (28/29 switch), otherwise if it has 31 day or 30
		$month = ( $m == 2 ? 28 + (int)($y % 4 == 0) : 30 + (int)in_array($m, array(1,3,5,7,8,10,12)) );
		// it is recurring, check the recurring data
		switch ( $recur ) {
			case 'hour0':
				$offset = 0.5;
				break;
			case 'hour1':
				$offset = 1;
				break;
			case 'hour2':
				$offset = 2;
				break;
			case 'hour6':
				$offset = 6;
				break;
			case 'hour12':
				$offset = 12;
				break;
			case 'day1':
				$offset = 24;
				break;
			case 'day2':
				$offset = 24 * 2;
				break;
			case 'week1':
				$offset = 24 * 7;
				break;
			case 'week2':
				$offset = 24 * 7 * 2;
				break;
			case 'month1':
				$offset = 24 * $month;
				break;
			case 'month2':
				$offset = 24 * $month * 2;
				break;
			case 'quarter1':
				$offset = 24 * $month * 3;
				break;
			case 'quarter2':
				$offset = 24 * $month * 3 * 2;
				break;
			case 'year1':
				$offset = 24 * 365;
				break;
			case 'year2':
				$offset = 24 * 365 * 2;
				break;
			default:
				$offset = 24 * 365; // 1 year by default
		}
		// we use current date and add offset in HOURS
		$dateint = adesk_date_parse($date, $offset);
		// set the sending date
		$date = date('Y-m-d H:i:s', $dateint);
		if ( $log ) campaign_sender_log("Next recurring date: $date ($dateint)");
	}
	return $date;
}

function campaign_source_save($sourceid, $data, $size) {
	# Adapted from adesk_file_upload() with several modifications.

	// save file content in database
	// Place holder
	$currentPos = 0;
	// Loop counter
	$count = 1;
	// Chunk size
	$chunkSize = 700000;
	// insert array
	$insert = array(
		'id' => 0,
		'sourceid' => $sourceid,
		'sequence' => 1,
		'data' => ''
	);

	// Loop
	while ( $currentPos < $size ) {
		// Get a order number
		$insert['sequence'] = $count;
		// Get a chunk of the data
		$insert['data'] = substr($data, $currentPos, $chunkSize);
		// Insert it
		$retval = adesk_sql_insert("#campaign_source_data", $insert);
		if ( !$retval ) {
			// If this is ever false we should remove everything about this file from
			// the database.
			adesk_sql_query("DELETE FROM `#campaign_source_data` WHERE `sourceid` = '$sourceid'");
			return;
		}
		// Update the current position
		$currentPos += $chunkSize;
		$count++;
	}
}

function campaign_source($sourceid) {
	$data     = "";
	$sourceid = (int)$sourceid;

	$rs = adesk_sql_query("SELECT * FROM #campaign_source_data WHERE sourceid = '$sourceid' ORDER BY sequence");

	while ($row = adesk_sql_fetch_assoc($rs)) {
		$data .= $row["data"];
	}

	return $data;
}

function campaign_source_clear($campaignid = null, $messageid = null, $listid = null) {
	$campaignid = (int)$campaignid;
	$messageid = (int)$messageid;
	$listid = (int)$listid;
	if ( !$campaignid and !$messageid and !$listid ) return;
	$cond = "";
	if ( $campaignid ) $cond .= "AND campaignid = '$campaignid' ";
	if ( $messageid  ) $cond .= "AND messageid = '$messageid' ";
	if ( $listid ) {
		$cids = adesk_sql_select_list("SELECT campaignid FROM #campaign_list WHERE listid = '$listid'");
		$mids = adesk_sql_select_list("SELECT messageid FROM #message_list WHERE listid = '$listid'");
		if ( !$cids and !$mids ) return;
		$clist = implode("', '", $cids);
		$mlist = implode("', '", $mids);
		if ( $cids ) $cond .= "AND campaignid IN ('$clist') ";
		if ( $mids ) $cond .= "AND messageid IN ('$mlist') ";
	}
	if ( !$cond ) return;

	$ids = adesk_sql_select_list("SELECT id FROM #campaign_source WHERE 1 $cond");
	if ( !$ids ) return;

	$list = implode("', '", $ids);
	adesk_sql_delete("#campaign_source", "id IN ('$list')");
	adesk_sql_delete("#campaign_source_data", "sourceid IN ('$list')");
}

function campaign_source_get($campaignid, $messageid = 0, $type = null) {
	$campaignid = (int)$campaignid;
	if ( !$messageid ) {
		$messageid = (int)adesk_sql_select_one("messageid", "#campaign_message", "campaignid = '$campaignid'");
	}
	$cond = "campaignid = '$campaignid' AND messageid = '$messageid' ";
	if ( $type ) $cond .= "AND type = '$type' ";
	$source = adesk_sql_select_one("SELECT id FROM #campaign_source WHERE $cond");
	$source = campaign_source($source);

	if ($source == "") {
		$source = campaign_quick_send(_a('_t.e.s.t_@example.com'), $campaignid, $messageid, "mime", 'spamcheck'); // call spamcheck to get message source that we can parse
		if ( !is_array($source) ) {
			$in = array(
				"id" => 0,
				"campaignid" => $campaignid,
				"messageid" => $messageid,
				"type" => 'fullsource',
				"len" => strlen($source),
			);
			adesk_sql_insert("#campaign_source", $in);
			$sourceid = (int)adesk_sql_insert_id();

			campaign_source_save($sourceid, $source, $in["len"]);
		}
	}

	return $source;
}
?>
