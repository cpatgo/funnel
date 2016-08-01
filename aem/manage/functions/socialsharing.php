<?php

// fetch the remote data
function socialsharing_data_fetch_read($campaignid, $messageid, $socialshare_url, $bitly_urls, $source = null) {
	$facebook = $twitter = array();
	switch ($source) {
		default :
		case "facebook" :
			// if it's not in awebdesk_bitly yet, that means no one has clicked on the share link yet
			if ( !isset($bitly_urls["facebook"]) || $bitly_urls["facebook"] == "" ) {
				require_once awebdesk_functions("twit.php");
				$bitly_urls["facebook"] = adesk_bitly($socialshare_url . "&ref=facebook");
				// if adesk_bitly() fails, use the social share URL
				if (!$bitly_urls["facebook"]) $bitly_urls["facebook"] = $socialshare_url . "&ref=facebook";
				// check again if it exists
				$bitly_exists = adesk_sql_select_one("=COUNT(*)", "#bitly", "campaignid = '$campaignid' AND messageid = '$messageid' AND ref = 'facebook'");
				if (!$bitly_exists) {
					// save it so lt.php doesn't have to (when someone clicks on the share link)
					$insert = array(
						"id" => 0,
						"campaignid" => $campaignid,
						"messageid" => $messageid,
						"ref" => "facebook",
						"bitly" => $bitly_urls["facebook"],
					);
					adesk_sql_insert("#bitly", $insert);
				}
			}
			$facebook_data["facebook_bitly"] = $bitly_urls["facebook"];
			$shares = 0;
			$data_user = array();
			if ($GLOBALS["site"]["facebook_app_id"] != "" && $GLOBALS["site"]["facebook_app_secret"] != "") {
				$facebook = socialsharing_facebook_oauth_init();
				$facebook_session = socialsharing_facebook_oauth_getsession($facebook);
				$socialshare_url_seo = $GLOBALS["site"]["p_link"] . "/social/" . md5($campaignid) . "." . $messageid . "/like";
				//$fql = "SELECT share_count, like_count, comment_count, total_count FROM link_stat WHERE url IN ('$bitly_urls[facebook]', '$socialshare_url_seo')";
				$fql = "SELECT share_count, like_count, comment_count, total_count FROM link_stat WHERE url = '$bitly_urls[facebook]'";
				$param = array(
					"method" => "fql.query",
					"query" => $fql,
					"callback" => "",
				);
				$data_totals = $facebook->api($param);
				// I've seen it's an object sometimes; not sure why
				if ( !is_array($data_totals[0]) ) $data_totals[0] = get_object_vars($data_totals[0]);
				//dbg($data_totals);
				// not including "comment_count" right now - just want to add up "share_count" and "like_count"
				$shares = $data_totals[0]["share_count"] + $data_totals[0]["like_count"];
				//$data_user = $facebook->api("/search?q=" . urlencode($bitly_urls["facebook"]));
				//dbg($data_user);
			}
			$facebook_data["facebook_shares"] = $shares;
			$facebook_data["facebook_data"] = array();
		if ($source) break;
		case "twitter" :
			require_once awebdesk_functions("twit.php");
			// if it's not in awebdesk_bitly yet, that means no one has clicked on the share link yet
			if ( !isset($bitly_urls["twitter"]) || $bitly_urls["twitter"] == "" ) {
				$bitly_urls["twitter"] = adesk_bitly($socialshare_url . "&ref=twitter");
				// if adesk_bitly() fails, use the social share URL
				if (!$bitly_urls["twitter"]) $bitly_urls["twitter"] = $socialshare_url . "&ref=twitter";
				// check again if it exists
				$bitly_exists = adesk_sql_select_one("=COUNT(*)", "#bitly", "campaignid = '$campaignid' AND messageid = '$messageid' AND ref = 'twitter'");
				if (!$bitly_exists) {
					// save it so lt.php doesn't have to (when someone clicks on the share link)
					$insert = array(
						"id" => 0,
						"campaignid" => $campaignid,
						"messageid" => $messageid,
						"ref" => "twitter",
						"bitly" => $bitly_urls["twitter"],
					);
					adesk_sql_insert("#bitly", $insert);
				}
			}
			$twitter_data["twitter_bitly"] = $bitly_urls["twitter"];
			$search = array(
				$bitly_urls["twitter"],
				$socialshare_url . "&ref=twitter"
			);
			//dbg($search);
			$mentions = adesk_twit_api_search($search);
			$mentions_total = 0;
			//dbg($mentions);
			$data = array();
			if ( $mentions && is_object($mentions) ) {
				foreach ($mentions->entry as $mention) {
					$mention = get_object_vars($mention);
					$mention_link = get_object_vars($mention["link"][0]);
					$mention_image = get_object_vars($mention["link"][1]);
					$mention_author = get_object_vars($mention["author"]);
					// look for the bitly link within the tweet content (includes <a href=""> around links)
					// this is case-INsensitive
					$bitly_match_loose = preg_match("|" . $bitly_urls["twitter"] . "|i", $mention["content"], $bitly_matches);
					// if we find case-INsensitive matches
					if ( $bitly_match_loose && isset($bitly_matches[0]) && $bitly_matches[0] != "" ) {
						$bitly_match_strict = preg_match("|" . $bitly_urls["twitter"] . "|", $bitly_matches[0]);
						// if we find a bitly match, make sure it is a case-sensitive match (otherwise other bitly URL's will show up)
						if (!$bitly_match_strict) continue;
						$mentions_total++;
					}
					else {
						$mentions_total++;
					}
					$mention = array(
						"itemid" => $mention["id"],
						"published" => $mention["published"],
						"link" => $mention_link["@attributes"]["href"],
						"image" => $mention_image["@attributes"]["href"],
						"title" => $mention["title"],
						"content" => $mention["content"],
						"author" => $mention_author,
					);
					$data[] = $mention;
				}
			}
			$twitter_data["twitter_mentions"] = $mentions_total; // limited by pagination
			$twitter_data["twitter_data"] = $data;
		if ($source) break;
	}
	$r = array_merge($facebook_data, $twitter_data);
	//dbg($r);
	return $r;
}

// fetch the cached data
function socialsharing_data_cache_read($so, $campaignid, $source = null, $export = false) {
	if (!$so) $so = new adesk_Select;
	if ($source) {
		// get the actual cached data to display in paginator
		if ($source != "all") $so->push("AND s.source = '$source'");
		$rows = socialsharing_select_array($so, null, $campaignid);
		require_once(awebdesk_functions('process.php'));
		foreach ($rows as $k => $v) {
			if ($v["source"] == "twitter") {
				$rows[$k]["data"] = adesk_str_unserialize($v["data"]);
			}
			if ($v["subscriberid"]) {
				$rows[$k]["subscriber"] = subscriber_select_row($v["subscriberid"]);
				$rows[$k]["subscriber"]["md5email"] = md5($rows[$k]["subscriber"]["email"]);
			}
		}
		$r["source"] = $source;
		$r["data"] = $rows;
		$r["total"] = count($rows);
		if ($export) {
			$export_rows = array();
			foreach ($rows as $item) {
				$export_row = array();
				$export_row["source"] = $item["source"];
				if ($item["source"] == "facebook") {
					$export_row["name"] = ( isset($item["subscriber"]["name"]) ) ? $item["subscriber"]["name"] : _a("Undefined");
					$export_row["content"] = $item["data"];
					$export_row["published"] = $item["cdate"];
				}
				elseif ($item["source"] == "twitter") {
					$export_row["name"] = $item["data"]["author"]["name"];
					$export_row["content"] = $item["data"]["content"];
					$export_row["published"] = $item["cdate"];
				}
				$export_rows[] = $export_row;
			}
			return $export_rows;
		}
	}
	else {
		// just get totals for the top part, "Facebook Shares" and "Twitter Mentions" toggle links
		$so->slist = array(
			"SUM(s.source = 'facebook') AS facebook_total",
			"SUM(s.source = 'twitter') AS twitter_total",
		);
		$r = adesk_sql_select_row(socialsharing_select_query($so, $campaignid));
		$total_campaign_socialshare = adesk_sql_select_one("SELECT socialshares FROM #campaign WHERE id = '$campaignid'");
		$total_cached_data = adesk_sql_select_one("SELECT COUNT(*) FROM #socialshare WHERE campaignid = '$campaignid'");
		$total_socialshare_facebook_external = 0;
		if ($total_campaign_socialshare > $total_cached_data) {
			// find out how many pertain to facebook (shared both within, and outside of our interface)
			// the remaining must be facebook, since we obtain twitter directly from api, then cache
			$total_socialshare_facebook = $total_campaign_socialshare - $r["twitter_total"];
			// take remaining share count, and subtract the cached facebook total, and we are left with ONLY the count that is external facebook shares (outside of our UI)
			$total_socialshare_facebook_external = $total_socialshare_facebook - $r["facebook_total"];
		}
		// the below is all shares that occurred on Facebook, but not through our UI. so if I just copy the bitly link and paste into facebook to share
		$r["total_socialshare_facebook_external"] = $total_socialshare_facebook_external;
	}
	return $r;
}

// fetch the remote data, then cache it to database
function socialsharing_data_cache_write($campaignid, $messageid, $socialshare_url) {
	$bitly_urls = adesk_sql_select_box_array("SELECT ref, bitly FROM #bitly WHERE campaignid = '$campaignid'");
	$data = socialsharing_select_totals($campaignid, $messageid, $socialshare_url, $bitly_urls);

	// adjust social report counts - this is after we fetch the remote data
	$total_remote = $total_socialshares = $data["facebook_total"] + $data["twitter_total"];
	$total_cached_data = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #socialshare WHERE campaignid = '$campaignid'");
	// we use $total_remote by default (total of all facebook and twitter shares)
	// unless for some reason total_remote is LESS THAN what we have cached, then use the cached total
	if ($total_remote < $total_cached_data) {
		$total_socialshares = $total_cached_data;
	}
	adesk_sql_query("UPDATE #campaign SET socialshares = $total_socialshares WHERE id = '$campaignid'");
	adesk_sql_query("UPDATE #campaign_message SET socialshares = $total_socialshares WHERE campaignid = '$campaignid' AND messageid = '$messageid'");

	$insert = array(
		"campaignid" => $campaignid,
	);
	// there currently is no facebook data being returned, other than counts - so it should never get in this foreach
	foreach ($data["facebook_data"] as $share) {
		$insert["source"] = "facebook";
		$insert["itemid"] = $share["itemid"];
		$insert["=cdate"] = "NOW()";
		$insert["pdate"] = $share["published"];
		$insert["data"] = serialize($share["data"]);
		$exists = adesk_sql_select_row("SELECT * FROM #socialshare WHERE source = 'facebook' AND itemid = '$share[itemid]'");
		if (!$exists) {
			adesk_sql_insert("#socialshare", $insert);
		}
	}
	//dbg( $data["twitter_data"] );
	foreach ($data["twitter_data"] as $mention) {
		$insert["source"] = "twitter";
		$insert["itemid"] = $mention["itemid"];
		$insert["=cdate"] = "NOW()";
		$insert["pdate"] = $mention["published"];
		$insert["data"] = serialize($mention);
		$exists = adesk_sql_select_row("SELECT * FROM #socialshare WHERE source = 'twitter' AND itemid = '$mention[itemid]'");
		if (!$exists) {
			adesk_sql_insert("#socialshare", $insert);
		}
	}
	return $data;
}

function socialsharing_select_totals($campaignid, $messageid, $socialshare_url = null, $bitly_urls = array()) {
	$campaignid = intval($campaignid);
	$messageid  = intval($messageid);
	$table      = "#campaign";
	$cond       = "id = '$campaignid'";

	if ($messageid > 0) {
		$table = "#campaign_message";
		$cond  = "messageid = '$messageid' AND campaignid = '$campaignid'";
	}

	$total_amt = 0;
	if ($campaignid) {
		$campaign = adesk_sql_select_row("
			SELECT
				total_amt,
				socialshares
			FROM
				$table
			WHERE
				$cond
		");
	}

	// fetch the remote data
	if ($socialshare_url) {
		$data = socialsharing_data_fetch_read($campaignid, $messageid, $socialshare_url, $bitly_urls);
		return array(
			"total_amt_sent" => $campaign["total_amt"],
			"facebook_total" => $data["facebook_shares"],
			"facebook_data" => $data["facebook_data"],
			"facebook_bitly" => $data["facebook_bitly"],
			"twitter_total" => $data["twitter_mentions"],
			"twitter_data" => $data["twitter_data"],
			"twitter_bitly" => $data["twitter_bitly"],
		);
	}
	else {
		// get the cached data - in this case we are just getting totals to display along the top of the "Social Sharing" campaign reports page
		$data = socialsharing_data_cache_read(null, $campaignid);
		$facebook_total = ($data["facebook_total"]) ? $data["facebook_total"] : 0;
		$twitter_total = ($data["twitter_total"]) ? $data["twitter_total"] : 0;
		return array(
			"total_amt_sent" => $campaign["total_amt"],
			"total_shares" => $campaign["socialshares"],
			"facebook_total" => $facebook_total,
			"twitter_total" => $twitter_total,
			"total_socialshare_facebook_external" => $data["total_socialshare_facebook_external"],
		);
	}
}

function socialsharing_select_query(&$so, $campaignid = 0) {
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		if ( $admin['id'] != 1 ) {
			if ( !isset($so->permsAdded) ) {
				$so->permsAdded = 1;
				$so->push("AND s.campaignid IN (SELECT cl.campaignid FROM #campaign_list cl WHERE cl.listid IN ('" . implode("', '", $admin['lists']) . "'))");
			}
		}
	}

	if ($campaignid > 0)
		$so->push("AND s.campaignid = '$campaignid'");

	return $so->query("
		SELECT
			*
		FROM
			#socialshare s
		WHERE
			[...]
	");
}

function socialsharing_select_array($so = null, $ids = null, $campaignid = 0) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND f.id IN ('$ids')");
	}
	return adesk_sql_select_array(socialsharing_select_query($so, $campaignid));
}

function socialsharing_select_array_paginator($id, $sort, $offset, $limit, $filter, $campaignid = 0, $source = null) {
	$admin     = adesk_admin_get();
	$so        = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'socialsharing'");
		$so->push($conds);
	}

	switch ($sort) {
		default:
		case "01":
			$so->orderby("cdate"); break;
		case "01D":
			$so->orderby("cdate DESC"); break;
	}

	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$data = socialsharing_data_cache_read($so, $campaignid, $source);
	$total = adesk_sql_select_one("SELECT COUNT(*) FROM #socialshare WHERE campaignid = '$campaignid'");
	$rows = $data["data"];

	return array(
		"source"      => $source,
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

// api
function socialsharing_select_list($campaignid, $messageid = 0) {
	$so = new adesk_Select;
	if ($messageid > 0) {
		$so->push( "AND s.campaignid IN (SELECT campaignid FROM #campaign_message WHERE messageid IN ('" . implode("', '", "'" . $messageid . "'") . "')" );
	}
	return socialsharing_select_array($so, null, $campaignid);
}

function socialsharing_filter_post() {
	$whitelist = array("s.cdate", "s.data");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "socialsharing",
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
			sectionid = 'socialsharing'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

function socialsharing_process_link($c, $m, $s, $url, $link = null) {
  $site = adesk_site_get();
	if (!$link) {
		// $link will be empty when calling from spots that display social share icons that are NOT within the campaign message source
		// in this situation, they haven't gone through lt.php
		// $url at this point is the $webcopy URL, WITH "ref=whatever" on the end
		$link = array('link' => $url);
	}
	else {
		// coming from lt.php
		// first replace the hashes
		$link['link'] = str_replace('cmpgnhash', md5($c), $link['link']);
		$link['link'] = str_replace('cmpgnid', $c, $link['link']);
		$link['link'] = str_replace('currentmesg', $m, $link['link']);
		$link['link'] = str_replace('subscriberid', $s, $link['link']);
	}
  if ($s) $s = subscriber_exists($s, 0, 'hash');
	$facebook_like_link = false;
	// if it's the facebook "like" button that was clicked, we still need to obtain the facebook share link
	if ( adesk_str_instr('&facebook=like', $link['link']) ) {
		$facebook_like_link = true;
		// convert it to the standard facebook share link, so we can get the bitly for it (for use in facebook "like" iframe)
		$link['link'] = str_replace('&facebook=like', '&ref=facebook', $link['link']);
	}
	// find ref
	// match any occurrence of "&ref=whatever" or "&referral=whatever"
	$param_str = preg_match("/&ref[a-z]*=[a-z]+/", $link['link'], $matches);
	if ( isset($matches[0]) ) list(,$ref) = explode('=', $matches[0]);
	// capture we.php links - convert to webcopy URL, so bitly goes to right place
	if ( adesk_str_instr('/we.php?c=', $link['link']) ) {
	  $link['link'] = $site['p_link'] . "/index.php?action=social&c=" . md5($c) . "." . $m . "&ref=" . $ref;
	}
	// find bitly
	$bitly = (int)adesk_sql_select_one("=COUNT(*)", "#bitly", "campaignid = '$c' AND messageid = '$m' AND ref = '$ref'");
	if (!$bitly) {
		require_once(awebdesk_functions('twit.php'));
		// get new bitly
		$bitly = adesk_bitly($link['link']);
		// adesk_bitly() can return false (might not connect to bitly server), so make sure there is SOME link here
		if (!$bitly) $bitly = $link["link"];
		// save it
		$insert = array(
			'id' => 0,
			'campaignid' => $c,
			'messageid' => $m,
			'ref' => $ref,
			'bitly' => $bitly,
		);
		adesk_sql_insert("#bitly", $insert);
	}
	else {
		// grab the actual bitly URL from the table
		$bitly = adesk_sql_select_one("bitly", "#bitly", "campaignid = '$c' AND messageid = '$m' AND ref = '$ref'");
	}

	// facebook "like" link does not get redirected to the external site - we remain on the web copy page (a modal pops up instead).
	// so further below, we don't assign a new $url - we leave that as is, and we change the $ref to 'facebook' (which we return),
	// so we know that a facebook "like" is the same thing as a facebook share, and is captured with subscriber actions, etc
	if ($facebook_like_link) $ref = "facebook_like";

	// get subject
	$subject = adesk_sql_select_one("subject", "#message", "id = '$m'");
	//if ( !$subject ) $subject = $campaign['name'];
	$subjectesc = rawurlencode($subject);
	$shared_verbiage = "";

	// assign bitly URL instead
	$bitlyesc = rawurlencode($bitly);
	switch ( $ref ) {
		case 'facebook':
			$url = "http://www.facebook.com/share.php?u=$bitlyesc";
			if ( $subject ) $url .= "&t=$subjectesc";
			$shared_verbiage = "shared";
		case 'facebook_like':
			// don't re-assign $url in this case.
			// just change the $ref BACK to facebook ($ref is what we return at the end of this function),
			// so later on during subscriber_action_dispatch, the "like" is also treated as a facebook share
			$ref = 'facebook';
			if (!$shared_verbiage) $shared_verbiage = "liked";
			// $s can be 0 if coming from report_campaign.php assets (and possibly other places) - in this case we just need the actual external URL's, and we're
			// not processing a subscriber's click. so if we just need the URL's, don't bother inserting into database
			if ($s) {
				require_once adesk_admin("functions/subscriber.select.php");
				$subscriber = subscriber_select_row($s["id"]);
				// store this occurrence into #socialshare so we can display on reports
				$exists = adesk_sql_select_row("SELECT * FROM #socialshare WHERE campaignid = '$c' AND subscriberid = '$subscriber[id]' AND source = 'facebook'");
				if (!$exists) {
					$insert["campaignid"] = $c;
					$insert["subscriberid"] = $subscriber["id"];
					$insert["itemid"] = "";
					$insert["source"] = "facebook";
					$insert["=cdate"] = "NOW()";
					$insert["=pdate"] = "NULL";
					$insert["data"] = $subscriber["first_name"] . " " . $subscriber["last_name"] . " " . $shared_verbiage . " " . _a("this campaign on Facebook");
					$sql = adesk_sql_insert("#socialshare", $insert);
				}
			}
			break;
		case 'twitter':
			$url = "http://twitter.com/share?text=" . _p('Currently reading') . "&url=$bitlyesc";
			/*
			if ($s) {
				// store this occurrence into #socialshare so we can display on reports
				$exists = adesk_sql_select_row("SELECT * FROM #socialshare WHERE campaignid = '$c' AND subscriberid = '$s' AND source = 'twitter'");
				if (!$exists) {
					$insert["campaignid"] = $c;
					$insert["subscriberid"] = $s;
					$insert["itemid"] = "";
					$insert["source"] = "twitter";
					$insert["=cdate"] = "NOW()";
					$insert["=pdate"] = "NULL";
					$insert["data"] = "";
					$sql = adesk_sql_insert("#socialshare", $insert);
				}
			}
			*/
			break;
		case 'digg':
			$url = "http://digg.com/submit?phase=2&url=$bitlyesc";
			if ( $subject ) $url .= "&title=$subjectesc";
			break;
		case 'delicious':
			$url = "http://del.icio.us/post?v=2&url=$bitlyesc";
			if ( $subject ) $url .= "&title=$subjectesc";
			break;
		case 'greader':
			$url = "http://www.google.com/reader/link?url=$bitlyesc";
			if ( $subject ) $url .= "&title=$subjectesc";
			break;
		case 'reddit':
			$url = "http://reddit.com/submit?url=$bitlyesc";
			if ( $subject ) $url .= "&title=$subjectesc";
			break;
		case 'stumbleupon':
			$url = "http://www.stumbleupon.com/submit?url=";
			$url .= rawurlencode($link["link"]);
			if ( $subject ) $url .= "&title=$subjectesc";
			break;
		default:
			// do nothing
	}
	return array($url, $link, $ref);
}

function socialsharing_facebook_oauth_init() {
	$site = adesk_site_get();
	require_once awebdesk_classes("facebook.php");
	$facebook = new Facebook( array("appId" => $site["facebook_app_id"], "secret" => $site["facebook_app_secret"], "cookie" => true) );
	return $facebook;
}

function socialsharing_facebook_oauth_getsession($init) {
	$session = null;
	// see if the cookie is set
	$session = $init->getSession();
	if ($session) {

	}
	return $session;
}

?>
