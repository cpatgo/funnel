<?php

require_once awebdesk_functions("rss.php");

$GLOBALS['deskrss_items_found'] = 0;
$GLOBALS['deskrss_cache'] = array();

function deskrss_parse($campaign, $message, $html = false, $log = false, $action = 'send') {
	$str = $message[ ( $html ? 'html' : 'text' ) ];
	$cid = ( isset($campaign['id']) ? $campaign['id'] : 0 );
	$mid = ( isset($message['id'])  ? $message['id']  : 0 );
	//if ( !plugin_deskrss() ) return $str;
	$GLOBALS['deskrss_items_found'] = 0;
	preg_match_all('/(%RSS-FEED\|URL:([^|]*)\|SHOW:(NEW|ALL)%)(.*?)(%RSS-FEED%)/s', $str, $rssmatches);
	$cnt = count($rssmatches[0]);
	$format = ( $html ? 'HTML' : 'text' );
	if ( $log ) campaign_sender_log("Found $cnt RSS blocks in $format message.");
	if ( $cnt == 0 ) return $str;
	// for every block
	foreach ( $rssmatches[0] as $k => $rssblock ) {
		$url = $rssmatches[2][$k];
		//$limit = $rssmatches[3][$k];
		$type = strtolower($rssmatches[3][$k]);
		if($campaign['type']=="deskrss") $type = "new";
		$inner = $rssmatches[4][$k];
		$loop = $rssmatches[4][$k];
		// get it's RSS feed
		$url = str_replace('&amp;', '&', $url);
		list($url) = explode('   ', $url); // 2do!!! support for multiple feeds
		$limit = 1;
		// now loop through all loops in this block
		preg_match_all('/(%RSS-LOOP\|LIMIT:(\d+)%)(.*?)(%RSS-LOOP%)/s', $rssblock, $loopmatches);
		foreach ( $loopmatches[0] as $k => $loopblock ) {
			$lim = (int)$loopmatches[2][$k];
			if ( !$lim or $lim > $limit ) {
				$limit = $lim;
			}
			if ( !$lim ) break;
		}

		$rsshash = md5("$url, $limit, $type, $cid, $mid");
		if ( $log ) {
			campaign_sender_log("This RSS fetch got cache hash: $rsshash (url=$url;limit=$limit;type=$type;cid=$cid;mid=$mid)");
		}
		if ( isset($GLOBALS['deskrss_cache'][$rsshash]) ) {
			$feed = $GLOBALS['deskrss_cache'][$rsshash];
			$GLOBALS['deskrss_items_found'] += $feed['found_count'];
		} else {
			adesk_rss_useragent_set(deskrss_useragent(/*$campaign['total_amt']*/));
			$GLOBALS['deskrss_cache'][$rsshash] =
			$feed = deskrss_fetch($url, $limit, $type, $cid, $mid, $action);
			if ( $feed['rss'] ) $GLOBALS['deskrss_cache'][$rsshash] = $feed;
			adesk_rss_useragent_unset();
		}

		$cnt = 0;
		if ( $feed['rss'] and isset($feed['rss']->item) ) $cnt = count($feed['rss']->item);
		if ( $log ) {
			campaign_sender_log("Found $cnt RSS items in feed $url");
			//campaign_sender_log(print_r(( $feed['rss'] and isset($feed['rss']->item) ) ? $feed['rss']->item : $feed, 1));
			//campaign_sender_log(print_r($feed['feed'],1));
			//campaign_sender_log($type);
		}
		if ( !$feed['rss'] ) {
			$str = str_replace($rssblock, '', $str);
			continue;
		}
		// now loop through all loops in this block
		foreach ( $loopmatches[0] as $k => $loopblock ) {
			$limit = $loopmatches[2][$k];
			$loop = $loopmatches[3][$k];
			// build a content for each loop
			$content = deskrss_replace_items($feed, $loop, $limit, $html);
			// replace the original block with the generated contents
			$inner = str_replace($loopblock, $content, $inner);
		}
		// build a content outside of loops
		$content = deskrss_replace($feed, $inner, '', $html);
		// replace the original block with the generated contents
		$str = str_replace($rssblock, $content, $str);
		// try to save links if doing this within a (tracked) campaign (that we're formally sending!)
		if ( $cid > 0 and $mid > 0 and $campaign['tracklinks'] != 'none' and $campaign['status'] == 2 ) {
			if ( $log ) {
				campaign_sender_log("Fetching links to track");
			}
			// fetch all links found
			$tmp = array(
				'id' => $mid,
				'format' => $campaign['tracklinks'],
				'text' => $content,
				'html' => $content,
			);
			$links = message_extract_links($tmp);
			// go thru all found links in this rss block
			if ( $log ) {
				campaign_sender_log("Saving tracked links");
			}
			foreach ( $links as $link ) {
				// check if already added
				$linkesc = adesk_sql_escape($link['link']);
				$id = intval(adesk_sql_select_one("
					SELECT
						id
					FROM
						#link
					WHERE
						link = '$linkesc'
					AND
						campaignid = '$cid'
					AND
						messageid = '$mid'
				"));
				if ( $id ) continue;
				// add link
				$insert = array(
					'id' => 0,
					'campaignid' => $cid,
					'messageid' => $mid,
					'link' => $link['link'],
					//'=name' => 'NULL',
					//'ref' => '',
					'tracked' => 1,
				);
				adesk_sql_insert('#link', $insert);
			}
			if ( $log ) {
				campaign_sender_log("Tracked links saved.");
			}
		}
	}
	return $str;
}

function deskrss_fetch($url, $limit = 0, $type = 'new', $cid = 0, $mid = 0, $action = 'send') {
	global $site;
	$tstamp = 0;
	// initialize counter for loop
	$counter = 0;
	// always fetch the feed without caching here
	$feed = adesk_rss_fetch($url, 0);
	// if feed is fetched
	if ( $feed['rss'] ) {
		$urlEsc = adesk_sql_escape($url);
		$conds = array();
		if ( $cid > 0 ) $conds[] = "`campaignid` = '$cid'";
		if ( $mid > 0 ) $conds[] = "`messageid` = '$mid'";
		$conds[] = "`url` = '$urlEsc'";
		$feedArr = adesk_sql_select_row("SELECT * FROM #rssfeed WHERE" . implode(" AND ", $conds));
		if ( !$feedArr ) {
			// add it if new
			$feedArr = array(
				'id' => 0,
				'campaignid' => $cid,
				'messageid' => $mid,
				'url' => $url,
				'type' => $type,
				'=lastcheck' => 'NULL',
				'howmany' => $limit,
			);
			if ( $cid > 0 ) {
				adesk_sql_insert('#rssfeed', $feedArr);
				$feedArr['id'] = adesk_sql_insert_id();
			}
			unset($feedArr['=lastcheck']);
			$feedArr['lastcheck'] = null;
		} else {
			# Update it!

			$up = array(
				"type" => $type,
				"howmany" => $limit,
			);

			if ($cid > 0) {
				adesk_sql_update("#rssfeed", $up, "id = '$feedArr[id]'");
			}

			$feedArr["type"] = $type;
			$feedArr["howmany"] = $limit;
			if ( $action != 'send' ) $feedArr['lastcheck'] = null;
		}

		// figure out last check timestamp
		if ( $feedArr['lastcheck'] ) $tstamp = (int)@strtotime($feedArr['lastcheck']);
		if ( !$tstamp or $tstamp == -1 ) $tstamp = 0;
		$feed['rss']->item = array();
		// if any items are fetched
		if ( isset($feed['rss']->items) ) {
			foreach ( $feed['rss']->items as $k => $v ) {
				// RSS1.0 has no times used (weird, huh?)
				if ( !isset($v['date_timestamp']) ) $v['date_timestamp'] = 0;
				// check if this item should be added to the array of items
				$nike = false;
				if ( $type == 'new' ) {
					// if lastcheck is respected
					// first check if published after last check
					if ( $v['date_timestamp'] >= $tstamp ) {
						// then check for how many should be included
						$nike = ( $feedArr['howmany'] == 0 or $counter < $feedArr['howmany'] );
					}
				} else {
					// if lastcheck is not respected
					// just check for how many should be included
					$nike = ( $feedArr['howmany'] == 0 or $counter < $feedArr['howmany'] );
				}
				// include item
				if ( $nike ) {
					// save date in sql format
					$unixtime = ( $v['date_timestamp'] > 0 ? $v['date_timestamp'] : time() );
					$v['sqldate'] =
					$v['date'] = date('Y-m-d H:i:s', $unixtime);
					$v['dateonly'] = strftime($site['dateformat'], $unixtime);
					$v['timeonly'] = strftime($site['timeformat'], $unixtime);
					$v['date'] =
					$v['datetime'] = strftime($site['datetimeformat'], $unixtime);
					// save this feed item
					$feed['rss']->item[] = $v;
					$counter++;
				}
			}
		}
	} else {
		if ( $cid and $mid ) {
			$cname = adesk_sql_select_one("name", "#campaign", "id = '$cid'");
			$from_name = $site['site_name'];
			$from_mail = $site['emfrom'];
			$body = sprintf(_a("I was unable to send the campaign %s as the RSS feed that was included could not be reached or had an error. Please check your RSS feed and try sending again."), $cname);
			global $MAGPIE_ERROR;
			//if ( $MAGPIE_ERROR ) $body .= "\n\n" . sprintf(_a("Reason for failing: %s"), $MAGPIE_ERROR);
			$subject = sprintf(_a("%s Sending Failure"), $cname);
			$email = adesk_sql_select_one("fromemail", "#message", "id = '$mid'");
			$to_name = _a("Campaign Sender");
			$options = array();
			adesk_mail_send("text", $from_name, $from_mail, $body, $subject, $email, $to_name, $options);
			if ( $MAGPIE_ERROR ) $body .= "\n\n" . sprintf(_a("Reason for failing: %s"), $MAGPIE_ERROR);
			//if ( isset($GLOBALS['_hosted_account']) ) adesk_mail_send("text", $from_name, $from_mail, $body . print_r($feed,1) . (isset($GLOBALS['dbgarr']) ? print_r($GLOBALS['dbgarr'],1) : 'dbgarr undefined!'), $subject, 'support@awebdesk.com', $to_name, $options);
		}
	}
	// save the found counter
	$GLOBALS['deskrss_items_found'] += $counter;
	$feed['found_count'] = $counter;
	// save this feed for personalization
	$feedArr['lctstamp'] = $tstamp;
	$feed['feed'] = $feedArr;
	// return an array of feed items here
	return $feed;
}

function deskrss_replace($feed, $tpl, $filter = '', $html = true) {
	if ( $filter ) $filter = trim($filter, ':') . ':';
	preg_match_all('/%RSS:' . $filter . '([^%]*)%/', $tpl, $matches);
	if ( count($matches[0]) == 0 ) return $tpl;
	foreach ( $matches[1] as $k => $v ) {
		$val = deskrss_tag($feed, 'RSS:' . $v, $html);
		$tpl = str_replace($matches[0][$k], $val, $tpl);
	}
	return $tpl;
}

function deskrss_replace_items($feed, $tpl, $limit, $html = true) {
	$r = '';
	if ( count($feed['rss']->item) == 0 ) return $r;
	$filter = 'ITEM:';
	preg_match_all('/%RSS:ITEM:([^%]*)%/', $tpl, $matches);
	if ( count($matches[0]) == 0 ) return str_repeat($tpl, count($feed['rss']->item));
	$i = 0;
	foreach ( $feed['rss']->item as $key => $item ) {
		if ( $i < $limit or $limit == 0 ) {
			$str = $tpl;
			foreach ( $matches[1] as $k => $v ) {
				$val = deskrss_tag($feed, 'RSS:ITEM:' . $key . ':' . $v, $html);
				$str = str_replace($matches[0][$k], $val, $str);
			}
			$r .= $str;
			//$r .= deskrss_replace($feed, $tpl, $filter . $key . ':', $html);
		}
		$i++;
	}
	return $r;
}

function deskrss_tag($feed, $tag, $html) {
	$arr = explode('|', $tag);
	if ( !isset($arr[1]) ) $arr[1] = 0;
	list($tag, $shorten) = $arr;
	$val = deskrss_tag_value($feed, $tag);
	if ( !$html ) $val = trim(strip_tags($val));
	$stripTags = ( substr($shorten, 0, 1) != '*' );
	if ( !$stripTags ) {
		$shorten = substr($shorten, 1);
	}
	if ( $shorten = (int)$shorten ) {
		if ( $html and $stripTags ) $val = trim(strip_tags($val));
		$val = adesk_str_shorten($val, (int)$shorten);
	}
	return $val;
}

function deskrss_tag_value($item, $ourtag) {
	$tags = explode(':', $ourtag);
	$r = deskrss_tag_value_recursive($item, $tags);
	// if returned other than a string
	if ( is_object($r) ) $r = get_object_vars($r);
	// get first string in array
	while ( is_array($r) ) {
		reset($r);
		$r = current($r);
		if ( is_object($r) ) $r = get_object_vars($r);
	}
	return (string)$r;
}

function deskrss_tag_value_recursive($item, $tags) {
	// if object, convert to array
	if ( is_object($item) ) $item = get_object_vars($item);
	// if not an array, we reached the value
	if ( !is_array($item) ) return (string)$item;
	// if no more tags, we reached the value
	if ( count($tags) == 0 ) return $item; // return array here!
	// get current tag to find
	$tag = array_shift($tags);
	// if tag doesn't exist
	if ( !isset($item[$tag]) ) {
		// try uppercased versions
		$item = array_change_key_case($item, CASE_UPPER);
		$tag  = strtoupper($tag);
	}
	// if tag STILL doesn't exist
	if ( !isset($item[$tag]) ) {
		return '';
	}
	// found a tag, go deeper
	return deskrss_tag_value_recursive($item[$tag], $tags);
}

function deskrss_useragent($subscribers = 0) {
	//require(adesk_admin('functions/versioning.php'));
	$name = _i18n('AwebDesk Email Marketing');
	$url  = _i18n('http://www.awebdesk.com/');
	//$ua = 'MagpieRSS/'. MAGPIE_VERSION . ' (+http://magpierss.sf.net';
	$ua = "$name (+$url";
	//$ua = "$name/$thisVersion (+$url";
	if ( $subscribers ) $ua .= "; $subscribers subscribers";
	if ( defined("MAGPIE_CACHE_ON") && !MAGPIE_CACHE_ON ) $ua .= '; No cache';
	$ua .= ')';
	return $ua;
}

function deskrss_checkfeed($url) {
	$url = trim(adesk_b64_decode($url));
	if ( !adesk_str_is_url($url) ) {
		return adesk_ajax_api_result(false, _a("RSS feed URL is not valid."));
	}

	$feed = @adesk_rss_fetch($url);
	// if feed is fetched
	if ( !$feed['rss'] ) {
		return adesk_ajax_api_result(false, _a("URL provided is not a valid RSS feed."));
	}

	$r = array(
		'channel' => $feed['rss']->channel,
		'items' => isset($feed['rss']->items) ? count($feed['rss']->items) : 0,
		'keys_channel' => deskrss_gettags($feed['rss']->channel),
		'keys_item' => isset($feed['rss']->items) ? deskrss_gettags(current($feed['rss']->items)) : array(),
	);

	return adesk_ajax_api_result(true, _a("RSS feed successfully fetched."), $r);
}

function deskrss_gettags($row) {
	$r = array();
	foreach ( $row as $k => $v ) {
		$r[] = $k;
		if ( is_array($v) ) {
			foreach ( $v as $v1 => $v2 ) {
				$r[] = $k.':'.$v1;
			}
		}
	}
	return $r;
}

?>
