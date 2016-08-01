<?php
// public side switch
define('AWEBVIEW', true);
define('AWEBP_USER_NOAUTH', true);

// require main include file
require_once(dirname(__FILE__) . '/manage/awebdeskend.inc.php');
require_once(adesk_admin('functions/campaign.php'));
require_once(adesk_admin('functions/message.php'));
require_once(awebdesk_functions('emailclient.php'));


// Preload the language file
adesk_lang_get('public');


$nl = (int)adesk_http_param('nl'); // list id
$c = (int)adesk_http_param('c'); // campaign id
$m = (int)adesk_http_param('m'); // message id
$lid = (int)adesk_http_param('lid'); // link id
$s = trim((string)adesk_http_param('s')); // subscriber hash
$l = (string)adesk_http_param('l'); // link

$ip      = ( isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1' );
$uasrc   = ( isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '' );
$referer = ( isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' );
$ua      = adesk_emailclient_ident($uasrc, $referer);
$savesrc = ( !$ua or $GLOBALS['adesk_emailclient_isdirty'] );
$social  = false;
$socmedia = "";

if ( !$savesrc ) $referer = '';

// is it a link tracker (redirect to link), or a read tracker (show dummy image)
$isLink = ( $l != 'open' and $l != '' );
$internal = !$isLink;

// if it is a link
if ( $isLink ) {
	// clean up the system replacements
	$l = message_link_cleanup($l);
	// check if it is an internal link
	$murl = adesk_site_plink();
	$internal = substr($l, 0, strlen($murl)) == $murl;
}
// this is where we will redirect the user
$url = ( $isLink ? $l : adesk_site_plink('awebdesk/media/1x1.gif') );

// make sure that we ignore all special tags
$special_tags = array (
		'currentmesg' => 1,
		'subscribername' => 1,
		'subscriberemail' => 1,
		'currentrespond' => 1,
		'currentnl' => 1,
		'subscriberemailecy' => 1,
		'subscriberid'	=> 1,
);

// check inputs
if ( trim($s) == '' or isset($special_tags[$s]) ) $s = '';
if ( !$nl or isset($special_tags[$nl]) ) $nl = 0;
if ( !$c or isset($special_tags[$c]) ) $c = 0;
if ( !$m or isset($special_tags[$m]) ) $m = 0;


// fetch subscriber
$subscriber = ( $s != '' ? subscriber_exists($s, 0, 'hash') : false );
if ( $subscriber ) {
	// GMail patch
	// if client was not (properly) found,
	// and referer is missing,
	// and user's email is google
	if ( $GLOBALS['adesk_emailclient_isdirty'] and !$referer ) {
		if ( adesk_str_instr('@gmail.com', strtolower($subscriber['email'])) ) {
			$ua = 'GMail';
		}
	}
}

// fetch campaign/list
$campaign = $list = false;
if ( $c > 0 and $nl > 0 ) {
	$query = "SELECT * FROM #campaign WHERE `id` = '$c'";
	$campaign = adesk_sql_select_row($query);
	if ( !$campaign ) {
		$query = "SELECT * FROM #campaign_deleted WHERE `id` = '$c'";
		$campaign = adesk_sql_select_row($query);
	}
	if ( $campaign ) {
		//$query = "SELECT * FROM #campaign_list WHERE `listid` = '$nl' AND `campaignid` = '$c'";
		$query = "
			SELECT
				*,
				l.id AS id,
				l.id AS lid,
				c.id AS cid
			FROM
				#campaign_list c,
				#list l
			WHERE
				c.listid = l.id
			AND
				c.campaignid = '$c'
			GROUP BY
				l.id
		";
		$campaign['lists'] = adesk_sql_select_array($query);
		foreach ( $campaign['lists'] as $k => $v ) {
			if ( $v['id'] == $nl ) $list = $v;
		}
	}
	if ( !$list ) $campaign = false;
}


// fetch link
$link = false;
if ( $campaign ) {
	/*
	// strip personalized info from internal links
	if ( $isLink and $internal ) {
		// internal links - old style
		if ( in_string('/forward2.php?mi=', $l) or in_string('/forward3.php?mi=', $l) or in_string('/forward.php?mi=', $l) ) {
			$tmpVar1 = strpos($l, '?');
			if ( $tmpVar1 > 0 ) $l = substr($l, 0, $tmpVar1);
		}
		// internal links - current style
		if ( in_string('/forward2.php?', $l) or in_string('/forward3.php?', $l) or in_string('/forward.php?', $l) ) {
			$tmpVar1 = strpos($l, '?');
			if ( $tmpVar1 > 0 ) $l = substr($l, 0, $tmpVar1);
		}
	}
	*/
	$le = adesk_sql_escape(message_link_internal($l));
	$me = ( $isLink ? $m : 0 );
	$query = "SELECT * FROM #link WHERE `campaignid` = '$c' AND `messageid` = '$me' AND `link` = '$le'";
	$link = adesk_sql_select_row($query);
	// link not found, but id exists
	if ( !$link ) {
		if ( $lid ) {
			$query = "SELECT * FROM #link WHERE `campaignid` = '$c' AND `messageid` = '$me' AND `id` = '$lid'";
			$link = adesk_sql_select_row($query);
		}
		if ( !$link and $subscriber ) {
			$pers = subscriber_personalize_get($subscriber, campaign_select_prepare($campaign, true));
			$pers = adesk_array_sort_strlen_r($pers);
			$lpers = $l;
			foreach ( $pers as $k => $v ) {
				$lpers = str_replace(rawurlencode($v), $k, $lpers);
				$lpers = str_replace($v, $k, $lpers);
			}
			if ( $lpers != $l ) {
				$lpe = adesk_sql_escape($lpers);
				$query = "SELECT * FROM #link WHERE `campaignid` = '$c' AND `messageid` = '$me' AND `link` = '$lpe'";
				$link = adesk_sql_select_row($query);
			}
		}
	}
}

if ( $campaign and !$link and !$internal ) {
	adesk_flush(_p('Unable to redirect you to this link. Please try again or contact your list admin.'));
	exit;
}

if ( $isLink ) {
	if ( $internal ) {
		// if link tracking for social, get bitly instead
		if ( adesk_str_instr('/index.php?action=social&c=', $link['link']) || adesk_str_instr('/we.php?c=', $link['link']) ) {
			$social = true;
			require_once adesk_admin('functions/socialsharing.php');
			//list($url, $link, $socmedia) = socialsharing_process_link($c, $m, $subscriber['id'], $url, $link);
			list($url, $link, $socmedia) = socialsharing_process_link($c, $m, $s, $url, $link);
		}
	}

	// append analytics code
	$url = message_link_analytics($url, $list, $campaign, $subscriber);
}

// do redirection
//dbg($url, 1);dbg($link, 1);dbg($subscriber, 1);dbg($campaign);
if (!$isLink){
	header('Content-type: image/gif');
	echo file_get_contents(dirname(__FILE__) . '/awebdesk/media/1x1.gif');
} else {
	adesk_http_redirect($url, $stop = false);
}

// if both subscriber and campaign and link are valid
if ( $subscriber and $campaign and $link ) {
	// check if this user already have a record
	$dataid  = (int)adesk_sql_select_one('id', '#link_data', "linkid = '$link[id]' AND subscriberid = '$subscriber[id]'");
	$countup = array();
	if ( $dataid > 0 ) {
		// he does, increment it
		$update = array(
			'=times' => 'times + 1',
			'=ip' => "INET_ATON('$ip')",
			//'ua' => $ua,
			//'uasrc' => ( $savesrc && !$isLink ? $uasrc : '' ),
			//'referer' => $referer,
		);
		adesk_sql_update('#link_data', $update, "id = '$dataid'");
	} else {
		# We need to see if we should increment the subscriberclicks count.  That happens if
		# the subscriber has clicked any link in a given message for the first time.  We also
		# need to check this before we insert, of course...

		$subclicks = adesk_sql_select_one("
			SELECT
				COUNT(*)
			FROM
				#link_data ld
			WHERE
				ld.subscriberid = '$subscriber[id]'
			AND ld.linkid IN
			(
				SELECT
					l.id
				FROM
					#link l
				WHERE
					l.campaignid = '$campaign[id]'
				AND l.link != 'open'
			)
		");

		// doesn't, add first record
		$arr = array(
			'id' => 0,
			'linkid' => $link['id'],
			'subscriberid' => $subscriber['id'],
			'email' => $subscriber['email'],
			'times' => 1,
			'=tstamp' => 'NOW()',
			'=ip' => "INET_ATON('$ip')",
			'ua' => $ua,
			'uasrc' => ( $savesrc && !$isLink ? $uasrc : '' ),
			'referer' => $referer,
		);

		# Wait!  It's possible that in the meantime between the query done for $subclicks (tests
		# indicate latency of around 0.2 seconds for one client) and when we first checked for
		# $dataid, lt.php was double-loaded.  (It SEEMS to happen for some email clients.)  So
		# it's possible that we might be wrong--that $dataid will now be greater than zero.
		# Check again.
		$dataid  = (int)adesk_sql_select_one('id', '#link_data', "linkid = '$link[id]' AND subscriberid = '$subscriber[id]'");
		if ( $dataid > 0 ) {
			// he does, increment it
			$update = array(
				'=times' => 'times + 1',
				'=ip' => "INET_ATON('$ip')",
				//'ua' => $ua,
				//'uasrc' => ( $savesrc && !$isLink ? $uasrc : '' ),
				//'referer' => $referer,
			);
			adesk_sql_update('#link_data', $update, "id = '$dataid'");
		} else {
			adesk_sql_insert('#link_data', $arr);
			$dataid = (int)adesk_sql_insert_id();
			#message_link_actions($subscriber, /*$list, $campaign,*/ $link);
			if ( $isLink ) {
				$countup["=uniquelinkclicks"] = "uniquelinkclicks + 1";
				if ($subclicks < 1)
					$countup["=subscriberclicks"] = "subscriberclicks + 1";
				// run any needed actions here
				subscriber_action_dispatch("link", $subscriber, null, $campaign, $link);

				if ($social)
					subscriber_action_dispatch("social", $subscriber, null, $campaign, null, $socmedia);
			} else {
				$countup["=uniqueopens"] = "uniqueopens + 1";
				subscriber_action_dispatch("read", $subscriber, null, $campaign, $link);
			}
		}
	}

	if ( $isLink ) {
		$countup["=linkclicks"] = "linkclicks + 1";
	} else {
		$countup["=opens"] = "opens + 1";
	}

	// if this is a link
	if ( $isLink ) {
		// read tracking is on
		if ( $campaign['trackreads'] ) {
			// "open" link record exists
			$openlink = adesk_sql_select_row("SELECT * FROM #link WHERE campaignid = '$c' AND messageid = '0' AND link IN ('open', '')");
			if ( isset($openlink['id']) ) {
				// wasn't logged so far, log it
				$found = (int)adesk_sql_select_one('=COUNT(*)', '#link_data', "linkid = '$openlink[id]' AND subscriberid = '$subscriber[id]'");
				if ( $found == 0 ) {
					// log it
					$arr = array(
						'id' => 0,
						'linkid' => $openlink['id'],
						'subscriberid' => $subscriber['id'],
						'email' => $subscriber['email'],
						'times' => 1,
						'=tstamp' => 'NOW()',
						'=ip' => "INET_ATON('$ip')",
						//'ua' => $ua,
						//'uasrc' => ( $savesrc ? $uasrc : '' ),
						//'referer' => $referer,
					);
					adesk_sql_insert('#link_data', $arr);
					$dataid = (int)adesk_sql_insert_id();
					// run any needed actions here
					subscriber_action_dispatch("read", $subscriber, null, $campaign, $openlink);
					#message_link_actions($subscriber, /*$list, $campaign,*/ $openlink);
					$countup["=uniqueopens"] = "uniqueopens + 1";
					$countup["=opens"] = "opens + 1";
				}

				# We also need to make sure there is a record for the read with respect to
				# the specific message.
				$ocount = adesk_sql_select_one("
					SELECT
						COUNT(*)
					FROM
						#link
					WHERE
						campaignid = '$c'
					AND
						messageid = '$m'
					AND
						link = 'open'
				");

				if ($ocount < 1) {
					$ins = array(
						"campaignid" => $c,
						"messageid" => $m,
						"link" => "open",
					);

					adesk_sql_insert('#link', $ins);
					$mlinkid = adesk_sql_insert_id();
				} else {
					$mlinkid = (int)adesk_sql_select_one("SELECT id FROM #link WHERE campaignid = '$c' AND messageid = '$m' AND link = 'open'");
				}

				# Finally, we need to insert a read record for this specific message.
				$found = (int)adesk_sql_select_one('=COUNT(*)', '#link_data', "linkid = '$mlinkid' AND subscriberid = '$subscriber[id]'");
				if ( $found == 0 ) {
					// log it
					$arr = array(
						'id' => 0,
						'linkid' => $mlinkid,
						'subscriberid' => $subscriber['id'],
						'email' => $subscriber['email'],
						'times' => 1,
						'=tstamp' => 'NOW()',
						'=ip' => "INET_ATON('$ip')",
						//'ua' => $ua,
						//'uasrc' => ( $savesrc ? $uasrc : '' ),
						//'referer' => $referer,
					);
					adesk_sql_insert('#link_data', $arr);
				}
			}
		}
	} else {
		# We also need to make sure there is a record for the read with respect to
		# the specific message.
		$ocount = adesk_sql_select_one("
			SELECT
				COUNT(*)
			FROM
				#link
			WHERE
				campaignid = '$c'
			AND
				messageid = '$m'
			AND
				link = 'open'
		");

		if ($ocount < 1) {
			$ins = array(
				"campaignid" => $c,
				"messageid" => $m,
				"link" => "open",
			);

			adesk_sql_insert('#link', $ins);
			$mlinkid = adesk_sql_insert_id();
		} else {
			$mlinkid = (int)adesk_sql_select_one("SELECT id FROM #link WHERE campaignid = '$c' AND messageid = '$m' AND link = 'open'");
		}

		# Finally, we need to insert a read record for this specific message.
		$dataid = (int)adesk_sql_select_one('id', '#link_data', "linkid = '$mlinkid' AND subscriberid = '$subscriber[id]'");
		if ( $dataid == 0 ) {
			// log it
			$arr = array(
				'id' => 0,
				'linkid' => $mlinkid,
				'subscriberid' => $subscriber['id'],
				'email' => $subscriber['email'],
				'times' => 1,
				'=tstamp' => 'NOW()',
				'=ip' => "INET_ATON('$ip')",
				'ua' => $ua,
				'uasrc' => ( $savesrc ? $uasrc : '' ),
				'referer' => $referer,
			);
			adesk_sql_insert('#link_data', $arr);
		} else {
			// he does, increment it
			$update = array(
				'=times' => 'times + 1',
				'=ip' => "INET_ATON('$ip')",
				//'ua' => $ua,
				//'uasrc' => ( $savesrc ? $uasrc : '' ),
				//'referer' => $referer,
			);
			adesk_sql_update('#link_data', $update, "id = '$dataid'");
		}

		// reset any soft bounces for this subscriber
		if ( $subscriber['bounced_soft'] ) {
			adesk_sql_update_one("#subscriber", "bounced_soft", 0, "id = '$subscriber[id]'");
			adesk_sql_delete('#bounce_data', "( `subscriberid` = '$subscriber[id]' OR `email` = '$subscriber[email]' ) AND `type` = 'soft'");
			subscriber_bounce_lowercounts($subscriber['id'], $subscriber['email'], "soft");
		}

	}

	// log this action
	$insert = array(
		'id' => 0,
		'linkid' => $link['id'],
		'subscriberid' => $subscriber['id'],
		'=tstamp' => 'NOW()',
		'=ip' => "INET_ATON('$ip')",
		'ua' => $ua,
		'uasrc' => ( $savesrc && !$isLink ? $uasrc : '' ),
		'referer' => $referer,
	);
	adesk_sql_insert('#link_log', $insert);

	if (count($countup) != 0) {
		adesk_sql_update("#campaign", $countup, "id = '$c'");
		adesk_sql_update("#campaign_deleted", $countup, "id = '$c'");
		adesk_sql_update("#campaign_message", $countup, "campaignid = '$c' AND messageid = '$m'");
	}
}

//dbg($url, 1);dbg($link, 1);dbg($subscriber, 1);dbg($campaign);
?>
