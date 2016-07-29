<?php

/*
	in this process, we will convert:
	then lists and all related settings,
	then subscribers,
	then campaigns,
	then unsubscriptions,
*/

require_once adesk_admin("functions/subscriber_action.php");	# For converting subscription rules


function upgrade_process() {



	// fetch old backend
	$oldprefix = ( isset($GLOBALS['adesk_updater_backend']) ? $GLOBALS['adesk_updater_backend'] : 'Aawebdesk_' );
	$sql = adesk_sql_query("SELECT * FROM `{$oldprefix}backend`");
	$backend = mysql_fetch_assoc($sql);



	// fetch old lists
	$lists = array();
	$sql = adesk_sql_query("SELECT * FROM `{$oldprefix}lists` ORDER BY `id`");
	while ( $row = mysql_fetch_assoc($sql) ) {
		$lists[$row['id']] = $row;
	}
	$listids = implode("', '", array_keys($lists));



	// fetch old campaigns
	// fetch old responders



	// fetch link tracking
	spit(_a('Converting link tracking data: '), 'em');
	$sql = adesk_sql_query("SELECT * FROM `{$oldprefix}links` WHERE `nl` > 0 OR `respond_id` > 0 ORDER BY `id`");
	while ( $row = adesk_sql_fetch_assoc($sql) ) {
		$found = false;
		if ( $row['nl'] = (int)$row['nl'] ) {
			$cid = $row['nl'];
			if ( isset($campaigns[$cid]) ) {
				$found = true;
				//$cid = $campaigns[$cid]['id'];
				$mid = $campaigns[$cid]['messages'][0];
			}
		} elseif ( $row['respond_id'] = (int)$row['respond_id'] ) {
			if ( isset($responders[$row['respond_id']]) ) {
				$found = true;
				$cid = $responders[$row['respond_id']]['cid'];
				$mid = $responders[$row['respond_id']]['mid'];
			}
		}
		if ( $found ) {
			$isLink = ( $row['link'] != '' and $row['link'] != 'open' );
			// figure out name based on link?
			if ( !$isLink ) {
				$name = _a('Read Tracking');
			} else {
				$name = '';
				// update subscriber
				if ( adesk_str_instr('/forward2.php', $row['link']) ) {
					$tmpVar1 = strpos($row['link'], '?');
					if ( $tmpVar1 > 0 ) $row['link'] = substr($row['link'], 0, $tmpVar1);
					$name = _a("Update Subscriber Link");
				}
				// web copy
				if ( adesk_str_instr('/forward3.php', $row['link']) ) {
					$tmpVar1 = strpos($row['link'], '?');
					if ( $tmpVar1 > 0 ) $row['link'] = substr($row['link'], 0, $tmpVar1);
					$name = _a("Web Copy Link");
				}
				// forward2friend
				if ( adesk_str_instr('/forward.php', $row['link']) ) {
					$tmpVar1 = strpos($row['link'], '?');
					if ( $tmpVar1 > 0 ) $row['link'] = substr($row['link'], 0, $tmpVar1);
					$name = _a("Forward to a Friend Link");
				}
			}
			$insert = array(
				'id' => 0,
				'campaignid' => $cid,
				'messageid' => $mid,
				'link' => $row['link'],
				'name' => $name,
			);
			$done = adesk_sql_insert('#link', $insert);
			if ( !$done ) break;
			$lid = adesk_sql_insert_id();

			// fetch link data
			$sql2 = adesk_sql_query("SELECT * FROM `{$oldprefix}linksd` WHERE `lid` = '$row[id]'");
			while ( $v = adesk_sql_fetch_assoc($sql2) ) {
				// if subscriber is transfered
				$sid = sub_exists($v['email']);
				//if ( isset($subs[$v['email']]) ) {
				if ( $sid ) {
					//$sid = $subs[$v['email']]['id'];
					$ip = ( adesk_str_is_ip($v['ip']) ? adesk_sql_escape($v['ip']) : '127.0.0.1' );
					$insert = array(
						'id' => 0,
						'linkid' => $lid,
						'tstamp' => $v['sdate'] . ' ' . $v['stime'],
						'subscriberid' => $sid,
						'email' => $v['email'],
						'times' => $v['times'],
						'=ip' => "INET_ATON('$ip')",
						//'ua' => '',
						//'=referer' => 'NULL',
					);
					$done = adesk_sql_insert('#link_data', $insert);
					if ( !$done ) break(2);

					// add to link log
					for ( $i = 1; $i <= $v['times']; $i++ ) {
						$insert = array(
							'id' => 0,
							'linkid' => $lid,
							'tstamp' => $v['sdate'] . ' ' . $v['stime'],
							'subscriberid' => $sid,
							'=ip' => "INET_ATON('$ip')",
							//'ua' => '',
							//'=referer' => 'NULL',
						);
						$done = adesk_sql_insert('#link_log', $insert);
						if ( !$done ) break(2);
					}
				}
			}
		} // if not a convertible link
	}
	if ( !$done ) {
		spit(_a('Error'), 'strong|error', 1);
		error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error());
		return;
	} else {
		spit(_a('Done'), 'strong|done', 1);
	}



	// fetch bounce tracking
	spit(_a('Converting bounce tracking data: '), 'em');
	$sql = adesk_sql_query("SELECT * FROM `{$oldprefix}bounce` WHERE `nl` IN ('$listids') AND ( `mid` > 0 OR `respond_id` > 0 ) ORDER BY `id`");
	while ( $row = adesk_sql_fetch_assoc($sql) ) {
		$found = false;
		$row['nl'] = (int)$row['nl'];
		if ( $row['mid'] = (int)$row['mid'] ) {
			$cid = $row['mid'];
			if ( isset($campaigns[$cid]) ) {
				$found = true;
				//$cid = $campaigns[$cid]['id'];
				$mid = $campaigns[$cid]['messages'][0];
			}
		} elseif ( $row['respond_id'] = (int)$row['respond_id'] ) {
			if ( isset($responders[$row['respond_id']]) ) {
				$found = true;
				$cid = $responders[$row['respond_id']]['cid'];
				$mid = $responders[$row['respond_id']]['mid'];
			}
		}
		// if subscriber is not transfered
		$sid = sub_exists($row['email']);
		//if ( !isset($subs[$row['email']]) ) {
		if ( !$sid ) {
			$found = false;
		}
		if ( $found ) {
			//$sid = $subs[$row['email']]['id'];
			$insert = array(
				'id' => $row['id'], // reuse bounce id
				'email' => $row['email'],
				'subscriberid' => $sid,
				'listid' => $row['nl'],
				'campaignid' => $cid,
				'messageid' => $mid,
				'tstamp' => $row['tdate'] . ' ' . $row['ttime'],
				'type' => $row['type'],
				'code' => $row['code'],
			);
			$done = adesk_sql_insert('#bounce_data', $insert);
			if ( !$done ) break;
		} // if not a convertible bounce
	}
	if ( !$done ) {
		spit(_a('Error'), 'strong|error', 1);
		error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error());
		return;
	} else {
		spit(_a('Done'), 'strong|done', 1);
	}



	// fetch forwards tracking
	spit(_a('Converting forward-to-friend tracking data: '), 'em');
	$sql = adesk_sql_query("SELECT * FROM `{$oldprefix}forward_log` WHERE `nl` IN ('$listids') AND ( `mesg_id` > 0 OR `respond_id` > 0 ) ORDER BY `id`");
	while ( $row = adesk_sql_fetch_assoc($sql) ) {
		$found = false;
		$row['nl'] = (int)$row['nl'];
		if ( $row['mesg_id'] = (int)$row['mesg_id'] ) {
			$cid = $row['mesg_id'];
			if ( isset($campaigns[$cid]) ) {
				$found = true;
				//$cid = $campaigns[$cid]['id'];
				$mid = $campaigns[$cid]['messages'][(int)( $row['b'] and isset($campaigns[$cid]['messages'][1]) )];
			}
		} elseif ( $row['respond_id'] = (int)$row['respond_id'] ) {
			if ( isset($responders[$row['respond_id']]) ) {
				$found = true;
				$cid = $responders[$row['respond_id']]['cid'];
				$mid = $responders[$row['respond_id']]['mid'];
			}
		}
		// if subscriber is not transfered
		$sid = sub_exists($row['from_email']);
		//if ( !isset($subs[$row['from_email']]) ) {
		if ( !$sid ) {
			$found = false;
		}
		if ( $found ) {
			//$sid = $subs[$row['from_email']]['id'];
			$ip = ( adesk_str_is_ip($row['ip']) ? adesk_sql_escape($row['ip']) : '127.0.0.1' );
			$insert = array(
				'id' => $row['id'], // reuse forward id
				'subscriberid' => $sid,
				'campaignid' => $cid,
				'messageid' => $mid,
				'email_from' => $row['from_email'],
				//'name_from' => '',
				'email_to' => $row['to_email'],
				//'name_to' => '',
				'brief_message' => $row['brief_message'],
				'tstamp' => $row['date'] . ' ' . $row['time'],
				'=ip' => "INET_ATON('$ip')",
			);
			$done = adesk_sql_insert('#forward', $insert);
			if ( !$done ) break;
		} // if not a convertible bounce
	}
	if ( !$done ) {
		spit(_a('Error'), 'strong|error', 1);
		error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error());
		return;
	} else {
		spit(_a('Done'), 'strong|done', 1);
	}



	// ATTACHMENTS
	spit(_a('Converting message attachments: '), 'em');
	// instead of using $attachments
	$done = adesk_sql_query("
		INSERT INTO
			`#message_file_data`
		(
			SELECT
				a.id,
				a.fileid,
				a.sequence,
				a.data
			FROM
				`{$oldprefix}files_data` a,
				`#message_file` b
			WHERE
				a.fileid = b.id
			ORDER BY a.id
		)
	");
	if ( !$done ) {
		spit(_a('Error'), 'strong|error', 1);
		error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error());
		return;
	} else {
		spit(_a('Done'), 'strong|done', 1);
	}



}















/*
	FUNCTIONS
*/


/*
	replacement function for $subs cache array
*/


// checks if the subscriber exists in the new system already
// input:  AEM v4 subscriber email
// input:  optional subscriber's list (used for duplicates check)
// output: AEM v5 subscriber id if exists, zero (0) if not
function sub_exists($email, $list = null) {
	$esc = adesk_sql_escape($email);
	$list = (int)$list;
	if ( $list ) {
		$sql = adesk_sql_query("SELECT s.id FROM #subscriber s, #subscriber_list l WHERE s.email = '$esc' AND l.listid = '$list' AND s.id = l.subscriberid");
	} else {
		$sql = adesk_sql_query("SELECT id FROM #subscriber WHERE `email` = '$esc'");
	}
	if ( !$sql or !mysql_num_rows($sql) ) return 0;
	$sub = adesk_sql_fetch_assoc($sql);
	return $sub['id'];
}

?>