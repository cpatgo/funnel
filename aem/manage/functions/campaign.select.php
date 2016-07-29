<?php

require_once awebdesk_classes("select.php");

function campaign_select_totals($campaignid, $messageid) {
	$campaignid = intval($campaignid);
	$messageid  = intval($messageid);
	$table      = "#campaign";
	$cond       = "id = '$campaignid'";
	$type       = "type, ldate, status,";

	if ($messageid > 0) {
		$table = "#campaign_message";
		$cond  = "messageid = '$messageid' AND campaignid = '$campaignid'";
		$type  = "( SELECT ldate FROM #campaign WHERE id = '$campaignid' ) AS ldate,";
		$type .= "( SELECT status FROM #campaign WHERE id = '$campaignid' ) AS status,";
		$campaign_message = $messageid . " AS messageid,";
	}
	else {
	  // get the message ID(s) - if it's split campaign, group together as string: '1,2,etc'
    $campaign_message = "(SELECT GROUP_CONCAT(messageid ORDER BY messageid ASC SEPARATOR ',') FROM #campaign_message WHERE campaignid = $campaignid) AS messageid,";
	}

	return adesk_sql_select_row("
		SELECT
			$type
			total_amt,
			send_amt,
			$campaign_message
			uniqueopens,
			uniquelinkclicks,
			subscriberclicks,
			unsubscribes,
			forwards,
			updates,
			hardbounces + softbounces AS totalbounces,
			socialshares
		FROM
			$table
		WHERE
			$cond
	", array("ldate"));
}

function campaign_select_query(&$so, $deleted = false) {
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		$uid = $admin['id'];
		if ( $admin['id'] > 1 ) {
			if ( !isset($so->permsAdded) ) {
				$so->permsAdded = 1;
				//sandeep
				$lists = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");
				$liststr = implode("','", $lists);

				if ($so->counting)
					$so->push("AND (SELECT COUNT(*) FROM #campaign_list subq WHERE subq.campaignid = c.id AND subq.listid IN ('$liststr'))");
				else
					$so->push("AND (l.listid IN ('$liststr') OR c.userid = '$admin[id]')");
			}
		}
	}
	$tablesuffix = $deleted ? '_deleted' : '';
	return $so->query("
		SELECT
			c.*,
			IF(c.ldate IS NULL AND c.status IN (0, 1, 6, 7), '9999-01-01 00:00:00', c.ldate) AS ldate,
			COUNT(l.id) AS lists
		FROM
			#campaign$tablesuffix c
		LEFT JOIN
			#campaign_list l
		ON
			c.id = l.campaignid
		WHERE
			[...]
		GROUP BY
			c.id
	");
}

function campaign_select_prepare($row, $full = false) {
	if ( $full ) {
		if ( adesk_http_param_exists('nolt') and adesk_http_param('nolt') ) {
			$row['tracklinks'] = 'none';
		}
		$row['lists'] = campaign_lists($row['id']);
		if ( !$row['lists'] ) $row['lists'] = array();
		// calculate list limits
		$row['p_duplicate_send']     = 1;
		$row['p_embed_image']        = 0;
		$row['p_use_scheduling']     = ( $row['status'] == 3 or $row['status'] == 4 );
		$row['p_use_tracking']       = 0;
		$row['p_use_analytics_read'] = 0;
		$row['p_use_analytics_link'] = 0;
		$row['p_use_twitter'] = 0;
		$row['p_use_facebook'] = 0;
		$lists = array();
		foreach ( $row['lists'] as $l ) {
			$lists[] = $l['listid'];
			if ( !$l['p_duplicate_send'] )    $row['p_duplicate_send']     = $l['p_duplicate_send'];
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
		$row['ratios'] = array();
		$row['sources'] = array();
		$messages = array();
		$sql = adesk_sql_query("SELECT * FROM #campaign_message WHERE campaignid = '$row[id]'");
		while ( $msg = adesk_sql_fetch_assoc($sql) ) {
			$messages[] = $msg['messageid'];
			$row['ratios'][$msg['messageid']] = $msg['percentage'];
			$row['sources'][$msg['messageid']] = $msg['sourcesize'];
		}
		// fetch all message info
		$row['messages'] = message_select_array(null, $messages, implode(',', $lists));
		foreach ( $row['messages'] as $k => $v ) {
			$row['messages'][$k]['percentage'] = $row['ratios'][$v['id']];
			$row['messages'][$k]['sourcesize'] = $row['sources'][$v['id']];
		}
		$row['messageslist'] = implode('-', $messages);
		// do not proceed (return false for campaign fetch) if campaign has no messages
		#if ( !$row['messages'] ) return false;
		// fetch all links for parsing
		$row['tlinks'] = campaign_links_get($row);
		$row['readactions'] = campaign_read_actions($row["id"]);
	}
	if ( isset($row['type']) and $row['type'] == 'reminder' ) {
		if ($row['reminder_field'] == 'sdate') {
			$row['reminder_field_name'] = _a("Subscription Date");
		}
		elseif ($row['reminder_field'] == 'cdate') {
			$row['reminder_field_name'] = _a("Creation Date");
		}
		elseif ( (int)$row['reminder_field'] ) {
			// custom field being used for the date
			$row['reminder_field_name'] = adesk_sql_select_one("SELECT title FROM #list_field WHERE id = '$row[reminder_field]'");
		}
	}
	if ( isset($GLOBALS['_hosted_account']) ) {
		$row['bounceid'] = -1;
	}
	return $row;
}

function campaign_select_row($id, $full = true, $offset = true, $trydeleted = false) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND c.id = '$id'");

	if ($offset)
		$r = adesk_sql_select_row(campaign_select_query($so, false), array("sdate", "ldate"));
	else
		$r = adesk_sql_select_row(campaign_select_query($so, false));

	// if we should check deleted campaigns as well
	if ( !$r and $trydeleted ) {
		if ($offset)
			$r = adesk_sql_select_row(campaign_select_query($so, true), array("sdate", "ldate"));
		else
			$r = adesk_sql_select_row(campaign_select_query($so, true));
	}

	if ( $r ) {
		$r = campaign_select_prepare($r, $full);
	}
	return $r;
}

function campaign_select_array($so = null, $ids = null, $full = false) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(",", $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND c.id IN ('$ids')");
	}
	//dbg(adesk_prefix_replace(campaign_select_query($so)));
	$r = adesk_sql_select_array(campaign_select_query($so), array("sdate", "ldate"));
	foreach ( $r as $k => $v ) {
		if ( $v ) {
			$r[$k] = campaign_select_prepare($v, $full);
		}
	}

	return $r;
}

function campaign_select_array_paginator_public($id, $sort, $offset, $limit, $filter, $public = false, $list_stringid = null, $showdrafts = true) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;
	$so2 = new adesk_Select;

	$so->push( "AND c.public = 1");
	$so2->push("AND c.public = 1");
	$so->push( "AND c.type != 'special'");
	$so2->push("AND c.type != 'special'");

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'campaign'");
		$so->push($conds);

		$conds = preg_replace(
			'/l\.listid = \'?(\d+)\'?/',
			'\1 IN (SELECT subq.listid FROM #campaign_list subq WHERE subq.campaignid = c.id)',
			$conds
		);

		$conds = preg_replace(
			'/l\.listid IN \(([^)]+)\)/',
			'(SELECT COUNT(*) FROM #campaign_list subq WHERE subq.campaignid = c.id AND subq.listid IN (\1)) > 0',
			$conds
		);

		$so2->push($conds);
	}

	if ( $public ) {
		$so->push( "AND ( c.type IN ('responder', 'reminder') OR c.status IN (2,3,4,5) )");
		$so2->push("AND ( c.type IN ('responder', 'reminder') OR c.status IN (2,3,4,5) )");
	}

	if (!$showdrafts) {
		$so->push( "AND c.status != 0");
		$so2->push("AND c.status != 0");
	}

	$so2->count();
	//dbg(campaign_select_query($so2));
	$total = (int)adesk_sql_select_one(campaign_select_query($so2));

	switch ($sort) {
		case "02":
			$so->orderby("c.type"); break;
		case "02D":
			$so->orderby("c.type DESC"); break;
		case "03":
			$so->orderby("c.status"); break;
		case "03D":
			$so->orderby("c.status DESC"); break;
		case "04":
			$so->orderby("c.name"); break;
		case "04D":
			$so->orderby("c.name DESC"); break;
		case "05":
			$so->orderby("c.sdate"); break;
		case "05D":
			$so->orderby("c.sdate DESC"); break;
		case "06":
			$so->orderby("c.cdate"); break;
		case "06D":
			$so->orderby("c.cdate DESC"); break;
		case "07":
			$so->orderby("messagesubject"); break;
		case "07D":
			$so->orderby("messagesubject DESC"); break;
		case "01":
			$so->orderby("ldate, c.sdate"); break;
		case "01D":
		default:
			$so->orderby("ldate DESC, c.sdate DESC"); break;
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");

	// if public, add message subject
	if ($public) {
		// we need to add all "SELECT" elements here again
		$so->slist = array(
			"c.*",
			"COUNT(l.id) AS lists",
			"
				(
					SELECT
						IF(( m.subject IS NULL OR m.subject = '' ), m.html, m.subject) AS `subject`
					FROM
						#campaign_message cm,
						#message m
					WHERE
						cm.campaignid = c.id
					AND
						cm.messageid = m.id
					LIMIT 0, 1
				) AS messagesubject
			"
		);
		$so->remove = false;

		$rows = campaign_select_array($so);

		foreach ( $rows as $k => $v ) {
			$rows[$k]['url'] = campaign_url($v, $list_stringid);
		}
	} else {
		$rows = campaign_select_array($so/*, null, true*/);

		foreach ($rows as $k => $v) {
			$rows[$k]['processid'] = campaign_processid($v['id'], 'any');
			$rows[$k]['canresend'] = filter_allows_campaignuse($v["filterid"]);
		}
	}
/*
	foreach ( $rows as $k => $v ) {
		//$rows[$k]['infuture'] = ( $v['sdate'] > adesk_CURRENTDATETIME );
		$rows[$k]['infuture'] = ( $v['sdate'] > adesk_CURRENTDATETIME  and ( !$v['ldate'] or $v['ldate'] < $v['sdate'] ) );
	}dbg($rows);
*/

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}


function campaign_select_array_paginator($id, $sort, $offset, $limit, $filter, $public = false, $list_stringid = null, $showdrafts = true) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;
	$so2 = new adesk_Select;

	$so->push("AND c.type != 'special'");
	$so2->push("AND c.type != 'special'");

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'campaign'");
		$so->push($conds);

		$conds = preg_replace(
			'/l\.listid = \'?(\d+)\'?/',
			'\1 IN (SELECT subq.listid FROM #campaign_list subq WHERE subq.campaignid = c.id)',
			$conds
		);

		$conds = preg_replace(
			'/l\.listid IN \(([^)]+)\)/',
			'(SELECT COUNT(*) FROM #campaign_list subq WHERE subq.campaignid = c.id AND subq.listid IN (\1)) > 0',
			$conds
		);

		$so2->push($conds);
	}

	if ( $public ) {
		$so->push("AND ( c.type IN ('responder', 'reminder') OR c.status = 5 )");
		$so2->push("AND ( c.type IN ('responder', 'reminder') OR c.status = 5 )");
	}

	if (!$showdrafts) {
		$so->push("AND c.status != 0");
		$so2->push("AND c.status != 0");
	}

	$so2->count();
	//dbg(campaign_select_query($so2));
	$total = (int)adesk_sql_select_one(campaign_select_query($so2));

	switch ($sort) {
		case "02":
			$so->orderby("c.type"); break;
		case "02D":
			$so->orderby("c.type DESC"); break;
		case "03":
			$so->orderby("c.status"); break;
		case "03D":
			$so->orderby("c.status DESC"); break;
		case "04":
			$so->orderby("c.name"); break;
		case "04D":
			$so->orderby("c.name DESC"); break;
		case "05":
			$so->orderby("c.sdate"); break;
		case "05D":
			$so->orderby("c.sdate DESC"); break;
		case "06":
			$so->orderby("c.cdate"); break;
		case "06D":
			$so->orderby("c.cdate DESC"); break;
		case "07":
			$so->orderby("messagesubject"); break;
		case "07D":
			$so->orderby("messagesubject DESC"); break;
		case "01":
			$so->orderby("ldate, c.sdate"); break;
		case "01D":
		default:
			$so->orderby("ldate DESC, c.sdate DESC"); break;
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");

	// if public, add message subject
	if ($public) {
		// we need to add all "SELECT" elements here again
		$so->slist = array(
			"c.*",
			"COUNT(l.id) AS lists",
			"
				(
					SELECT
						IF(( m.subject IS NULL OR m.subject = '' ), m.html, m.subject) AS `subject`
					FROM
						#campaign_message cm,
						#message m
					WHERE
						cm.campaignid = c.id
					AND
						cm.messageid = m.id
					LIMIT 0, 1
				) AS messagesubject
			"
		);
		$so->remove = false;

		$rows = campaign_select_array($so);

		foreach ( $rows as $k => $v ) {
			$rows[$k]['url'] = campaign_url($v, $list_stringid);
		}
	} else {
		$rows = campaign_select_array($so/*, null, true*/);

		foreach ($rows as $k => $v) {
			$rows[$k]['processid'] = campaign_processid($v['id'], 'any');
			$rows[$k]['canresend'] = filter_allows_campaignuse($v["filterid"]);
		}
	}
/*
	foreach ( $rows as $k => $v ) {
		//$rows[$k]['infuture'] = ( $v['sdate'] > adesk_CURRENTDATETIME );
		$rows[$k]['infuture'] = ( $v['sdate'] > adesk_CURRENTDATETIME  and ( !$v['ldate'] or $v['ldate'] < $v['sdate'] ) );
	}dbg($rows);
*/

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

// api
function campaign_select_list($ids, $filters = array()) {

	if ( !$ids && !$filters ) return $ids;

	$r = array();
	$conds = array("1");

	// filters from API
	if ($filters) {
		$whitelist = array("name");
		foreach ($filters as $k => $v) {
			if (!in_array($k, $whitelist)) {
				continue;
			}
			if ($k == "name") $conds[] = "name LIKE '%" . adesk_sql_escape($v, true) . "%'";
		}
	}

	if ($ids && $ids != "all") {
		$ids = explode(",", $ids);
		$ids = implode("','", $ids);
		$conds[] = "id IN ('" . $ids . "')";
	}

	// first pull just the ID's for Campaigns that match the conds
	$ids = adesk_sql_select_list( "SELECT id FROM #campaign WHERE " . implode(" AND ", $conds) );

	// then loop through each ID and pull the full Campaign row
	foreach ($ids as $id) {
		if ( $v = campaign_select_row($id) ) $r[] = $v;
	}
	return $r;
}

function campaign_filter_post() {
	//$whitelist = array("c.name", "c.analytics_campaign_name", "_message");
	$whitelist = array("c.name", "_message_subject", "_message_from", "_message_body");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "campaign",
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
			if ( $sect == '_message_subject' ) {
				$conds[] = "( SELECT m.subject FROM #campaign_message cm, #message m WHERE cm.campaignid = c.id AND cm.messageid = m.id LIMIT 0, 1 ) LIKE '%$content%' ";
			} elseif ($sect == '_message_from') {
				$conds[] = "( SELECT CONCAT(m.fromname, m.fromemail) FROM #campaign_message cm, #message m WHERE cm.campaignid = c.id AND cm.messageid = m.id LIMIT 0, 1 ) LIKE '%$content%' ";
			} elseif ($sect == '_message_body') {
				$conds[] = "( SELECT CONCAT(m.text, m.html) FROM #campaign_message cm, #message m WHERE cm.campaignid = c.id AND cm.messageid = m.id LIMIT 0, 1 ) LIKE '%$content%' ";
			} else {
				$conds[] = "$sect LIKE '%$content%'";
			}
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
				$ary["conds"] .= "AND (SELECT COUNT(*) FROM #campaign_list subq WHERE subq.campaignid = c.id AND subq.listid IN ('$ids')) > 0 ";
				//$ary['conds'] .= "AND l.listid IN ('$ids') ";
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
				$ary["conds"] .= "AND (SELECT COUNT(*) FROM #campaign_list subq WHERE subq.campaignid = c.id AND subq.listid = '$listid') > 0 ";
				//$ary['conds'] .= "AND l.listid = '$listid' ";
			} else {
				if ( defined('AWEBVIEW') ) {
					unset($_SESSION['nlp']);
				} else {
					unset($_SESSION['nla']);
				}
			}
		}
	}

	if (isset($_POST["status"])) {
		if ( is_array($_POST['status']) ) {
			if ( count($_POST['status']) > 0 ) {
				if ( !( count($_POST['status']) == 1 and $_POST['status'][0] == '' ) ) {
					$ids = implode("', '", array_map('intval', $_POST['status']));
					$ary['conds'] .= "AND c.status IN ('$ids') ";
				}
			}
		} else {
			if ( $_POST['status'] != '' ) {
				$status = (int)$_POST['status'];
				$ary['conds'] .= "AND c.status = '$status' ";
			}
		}
	}
	if (isset($_POST["type"])) {
		if ( is_array($_POST['type']) ) {
			if ( count($_POST['type']) > 0 ) {
				if ( !( count($_POST['type']) == 1 and $_POST['type'][0] == '' ) ) {
					$ids = implode("', '", array_map('adesk_sql_escape', $_POST['type']));
					$ary['conds'] .= "AND c.type IN ('$ids') ";
				}
			}
		} else {
			if ( $_POST['type'] != '' ) {
				$type = adesk_sql_escape($_POST['type']);
				$ary['conds'] .= "AND c.type = '$type' ";
			}
		}
	}

	if ( isset($_POST['public']) ) {
		$ary['conds'] .= "AND c.public = 1 ";
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
			sectionid = 'campaign'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

function campaign_new() {
	// default campaign array (create new)
	$r = adesk_sql_default_row('#campaign');
	// front-end stuff
	$r['step'] = 1;
	$r['lists'] = $r['messages'] = $r['tlinks'] = $r['links'] = $r['linkmessages'] = $r['actions'] = array();
	$r['listslist'] = '';
	$r['htmlunsubdata'] = _a('<div><a href="%UNSUBSCRIBELINK%">Click here</a> to unsubscribe from future mailings.</div>');
	$r['textunsubdata'] = _a('Click here to unsubscribe from future mailings: %UNSUBSCRIBELINK%');

	$r['sdate'] = strftime("%Y-%m-%d %H:%M:%S", strtotime(adesk_CURRENTDATETIME));
	return $r;
}

function campaign_list_messages($type, $ids = '', $findPhrase = '') {
	require_once(adesk_admin('functions/message.php'));
	$so = new adesk_Select();
	if ( $ids ) {
		$sqlids = str_replace(",", "','", (string)$ids);
		$so->push("AND l.listid IN ('$sqlids')");
	}
	// messages stuff
	$so->push("AND m.hidden = 0");
	if ( $type == 'deskrss' ) {
		$so->push("
			AND
			(
				m.text LIKE '%\%RSS-FEED|URL:%'
			OR
				SUBSTR(m.text, 1, 6) = 'fetch:'
			OR
				m.html LIKE '%\%RSS-FEED|URL:%'
			OR
				SUBSTR(m.html, 1, 6) = 'fetch:'
			)
		");
	}
	if ( $findPhrase = trim((string)$findPhrase) ) {
		$phrase = adesk_sql_escape($findPhrase, true);
		$so->push("
			AND
			(
				m.fromname LIKE '%$phrase%'
			OR
				m.fromemail LIKE '%$phrase%'
			OR
				m.reply2 LIKE '%$phrase%'
			OR
				m.subject LIKE '%$phrase%'
			OR
				m.text LIKE '%$phrase%'
			OR
				m.html LIKE '%$phrase%'
			)
		");
	}
	$so->slist = array(
		"m.id",
		"m.cdate",
		"m.mdate",
		"m.fromname",
		"m.fromemail",
		"m.format",
		"IF(( m.subject IS NULL OR m.subject = '' ), m.html, m.subject) AS `subject`",
		"COUNT(l.id) AS lists",
		"
			(
				SELECT
					COUNT(*)
				FROM
					#campaign_message cm,
					#campaign_list cl,
					#campaign c
				WHERE
					c.status != 0
				AND
					c.filterid = 0
				AND
					c.type IN ('single', 'recurring', 'deskrss', 'text')
				AND
					c.id = cm.campaignid
				AND
					c.id = cl.campaignid
				AND
					l.id = cl.listid
				AND
					m.id = cm.messageid
			) AS usedb4
		"
	);
	$so->remove = false;
	$so->orderby("usedb4, m.subject ASC");
	return message_select_array($so, null);
}

function campaign_list_change($campaignid, $ids, $includeGlobals, $type) {
	//require_once(adesk_admin('functions/filter.php'));
	require_once(adesk_admin('functions/bounce_management.php'));
	require_once(adesk_admin('functions/personalization.php'));
	$showAllMessages = (bool)substr($type, -1);
	$type = substr($type, 0, -1);
	$idsarr = array_diff(array_map('intval', explode('-', $ids)), array(0));
	$ids = implode(',', $idsarr);
	$sqlids = implode("','", $idsarr);
	if ( !$ids ) {
		return array(
			//'filters' => array(),
			'messages' => array(),
			'fields' => array(),
			'lists' => array(),
			'bounces' => array(),
			'personalizations' => array(),
		);
	}
	$bso = new adesk_Select();
	//$fso = new adesk_Select();
	$pso = new adesk_Select();
	$bso->push("AND l.listid IN ('$sqlids')");
	//$fso->push("AND f.id IN (SELECT l.filterid FROM #filter_list l WHERE l.listid IN ('$sqlids'))");
	//$fso->push("AND f.hidden = 0");
	$pso->push("AND l.listid IN ('$sqlids')");
	//$fso->orderby("f.name");
	return array(
		//'filters' => filter_select_array($fso, null),
		'messages' => campaign_list_messages($type, ( $showAllMessages ? '' : $ids )),
		'fields' => list_get_fields($idsarr, false),
		'lists' => list_select_array(null, $ids, ''),
		'bounces' => campaign_list_bounces($bso),
		'personalizations' => list_personalizations($pso),
	);
}

function campaign_list_bounces($so) {
	$so->push("AND b.type != 'none'");
	$so->push("AND b.email != ''");
	require_once adesk_admin("functions/bounce_management.php");
	return adesk_array_unique(bounce_management_select_array($so, null), 'email');
}

function campaign_list_headers($so) {
	require_once adesk_admin("functions/header.php");
	return adesk_array_unique(header_select_array($so, null), 'name');
}

function campaign_links_get($row, $messagesList = null) {
	if ( $row['tracklinks'] == 'none' ) return array();

	$cid = ( $row['type'] == 'special' and $row['realcid'] ) ? $row['realcid'] : $row['id'];

	// prepare message condition
	$cond = ( is_null($messagesList) ? str_replace('-', "','", $row['messageslist']) : implode("','", $messagesList) );

	// fetch all links in messages specified in this campaign
	$tlinks = adesk_sql_select_array("
		SELECT
			l.*,
			(SELECT m.format FROM #message m WHERE m.id = l.messageid) AS `format`
		FROM
			#link l
		WHERE
			l.campaignid = '$cid'
		AND
			l.messageid IN ('$cond')
	");

	if ( !$tlinks ) $tlinks = array();
	foreach ( $tlinks as $k => $v ) {
		$tlinks[$k]['actions'] = campaign_links_actions($v['id']);
	}
	return $tlinks;
}

function campaign_read_actions($campaignid) {
	$campaignid = (int)$campaignid;
	$rval = adesk_sql_select_array("SELECT a.* FROM #subscriber_action a WHERE campaignid = '$campaignid' AND linkid = '0'");

	foreach ($rval as $k => $v) {
		$rval[$k]["parts"] = adesk_sql_select_array("SELECT * FROM #subscriber_action_part WHERE actionid = '$v[id]'");
	}

	return $rval;
}

function campaign_links_actions($linkid) {
	$rval = adesk_sql_select_array("SELECT a.* FROM #subscriber_action a WHERE linkid = '$linkid'");
	foreach ($rval as $k => $v) {
		$rval[$k]["parts"] = adesk_sql_select_array("SELECT * FROM #subscriber_action_part WHERE actionid = '$v[id]'");
	}
	return $rval;
}

function campaign_selectdropdown_bylist($listid) {
	$listid = intval($listid);
	return adesk_sql_select_array("
		SELECT
			c.id,
			c.name
		FROM
			#campaign c
		WHERE
			c.id IN
			(
				SELECT
					subcl.campaignid
				FROM
					#campaign_list subcl
				WHERE
					subcl.listid = '$listid'
			)
	");
}

function campaign_url($campaign, $list_stringid) {
	global $site;
	// use absolute URL?
	$base = $site['p_link'];
	// remove trailing slash if exists
	if ( substr($base, -1) == '/' ) $base = substr($base, 0, -1);
	// working array always starts with a base, without trailing slash
	$arr = array($base);

	if ( !$site['general_url_rewrite'] ) {
		$arr[] = 'index.php?action=message&c=' . $campaign['id'];
	}
	else {
		$arr[] = 'archive';
		$arr[] = $list_stringid;
		$arr[] = $campaign['id'];
	}
	// return an url
	return implode('/', $arr);
}

function campaign_lists($id) {
	$cond = '';
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		if ( $admin['id'] > 1 ) {
			$cond = "AND c.listid IN ('" . implode("', '", $admin['lists']) . "')";
		}
	}
	// fetch all lists it belongs to (should be only selected for campaign)
	$query = "
		SELECT
			*,
			c.id AS relid,
			l.id AS id
		FROM
			#campaign_list c,
			#list l
		WHERE
			c.campaignid = '$id'
		AND
			c.listid = l.id
		$cond
		ORDER BY
			l.name
	";
	return adesk_sql_select_array($query);
}

function campaign_messages($id, $compact = false) {
	$what = ( $compact ? "IF(( m.subject IS NULL OR m.subject = '' ), m.html, m.subject) AS `subject`" : '*' );
	// fetch all lists it belongs to (should be only selected for campaign)
	$query = "
		SELECT
			$what,
			c.id AS relid,
			m.id AS id
		FROM
			#campaign_message c,
			#message m
		WHERE
			c.campaignid = '$id'
		AND
			c.messageid = m.id
		ORDER BY
			m.subject
	";
	return adesk_sql_select_array($query);
}

function campaign_share_get($id, $email = 'web') {
	$campaign = campaign_select_row($id);
	if ( !$campaign ) return false;
	$campaign["sharelink"] = awebdesk_reporthash_link($campaign, $email);
	return $campaign;
}

// when updating this function, also update /manage/templates/strings.js, it contains the same names
function campaign_statuses() {
	return array(
		_a("Draft"),
		_a("Scheduled"),
		_a("Sending"),
		_a("Paused"),
		_a("Stopped"),
		_a("Completed"),
		_a("Disabled"),
		_a("Pending Approval"),
	);
}

// when updating this function, also update /manage/templates/strings.js, it contains the same names
function campaign_type() {
	return array(
		'single' => _a("One-Time Campaign"),
		'recurring' => _a("Recurring Campaign"),
		'responder' => _a("AutoResponder"),
		'reminder' => _a("Subscriber Date Based Campaign"),
		'split' => _a("Split Test"),
		'deskrss' => _a("RSS Campaign"),
		//'special' => _a("Special Campaign")
		'text' => _a("Text-based Campaign")
	);
}

// when updating this function, also update /manage/templates/strings.js, it contains the same names
function campaign_types() {
	return array(
		'single' => _a("One-Time Campaigns"),
		'recurring' => _a("Recurring Campaigns"),
		'responder' => _a("AutoResponders"),
		'reminder' => _a("Subscriber Date Based Campaigns"),
		'split' => _a("Split Tests"),
		'deskrss' => _a("RSS Campaigns"),
		//'special' => _a("Special Campaigns")
		'text' => _a("Text-based Campaigns")
	);
}

function campaign_recur_intervals() {
	return array(
		'hour0' => _a("Half an Hour"),
		'hour1' => _a("Hour"),
		'hour2' => _a("Other Hour"),
		'hour6' => _a("6 Hours"),
		'hour12' => _a("12 Hours"),
		'day1' => _a("Day"),
		'day2' => _a("Other Day"),
		'week1' => _a("Week"),
		'week2' => _a("Other Week"),
		'month1' => _a("Month"),
		'month2' => _a("Other Month"),
		'quarter1' => _a("Quarter"),
		'quarter2' => _a("Other Quarter"),
		'year1' => _a("Year"),
		'year2' => _a("Other Year"),
	);
}

function campaign_processid($campaignid, $type = 'any') {
	switch ( $type) {
		case 'stalled':
			$cond = "AND p.completed < p.total AND p.ldate < SUBDATE(NOW(), INTERVAL 4 MINUTE)";
			break;
		case 'running':
			$cond = "AND p.completed < p.total AND p.ldate > SUBDATE(NOW(), INTERVAL 4 MINUTE)";
			break;
		case 'active':
			$cond = "AND p.completed < p.total";
			break;
		case 'completed':
			$cond = "AND p.completed = p.total";
			break;

		case 'any':
		default:
			$cond = "";
			break;
	}
	$processid = (int)adesk_sql_select_one("
		SELECT
			MAX(p.id)
		FROM
			#campaign_count c,
			#process p
		WHERE
			c.campaignid = '$campaignid'
		AND
			c.processid = p.id
		$cond
	");
	return $processid;
}

?>
