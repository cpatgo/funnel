<?php

require_once awebdesk_classes("select.php");
require_once awebdesk_functions("postmarkSpam.php");

function message_select_query(&$so) {
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		   $uid = $admin['id'];
		if ( $admin['id'] > 1 ) {
			if ( !isset($so->permsAdded) ) {
				$so->permsAdded = 1;
			//sandeep
			if($uid != 1 ){
	$lists = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");


	//get the lists of users
	
	@$liststr = implode("','",$lists);
	}
	else
	{
	@$liststr = implode("','", $admin["lists"]);
	}

			
			
			//	$liststr = implode("','", $admin["lists"]);

				if ($so->counting)
					$so->push("AND (SELECT COUNT(*) FROM #message_list subq WHERE subq.messageid = m.id AND subq.listid IN ('$liststr'))");
				else
					$so->push("AND l.listid IN ('$liststr')");
			}
		}
	}

	return $so->query("
		SELECT
			m.*,
			COUNT(l.id) AS lists
		FROM
			#message m
		LEFT JOIN
			#message_list l
		ON
			m.id = l.messageid
		WHERE
			[...]
		GROUP BY
			m.id
	");
}

function message_select_prepare($row, $full = true, $listsstr = '') {
	$row['files'] = adesk_file_upload_list('#message_file', 'messageid', $row['id']);
	$row['filescnt'] = count($row['files']);
	if ( $full or $listsstr != '' ) {
		$cond = '';
		if ( !adesk_admin_ismain() ) {
			$admin = adesk_admin_get();
			if ( $admin['id'] != 1 ) {
				$cond = "AND m.listid IN ('" . implode("', '", $admin['lists']) . "')";
			}
		}
		if ( $listsstr != '' ) {
			$sqllists = str_replace(",", "','", $listsstr);
			$cond .= " AND m.listid IN ('$sqllists')";
		}
		// fetch all lists it belongs to (should be only selected for campaign)
		$row['lists'] = adesk_sql_select_array("SELECT l.* FROM #message_list m, #list l WHERE m.messageid = '$row[id]' AND m.listid = l.id $cond");
		// calculate list limits here? and fetch list ids
		$lists = array();
		foreach ( $row['lists'] as $l ) {
			$lists[] = $l['id'];
		}
		$row['listslist'] = implode('-', $lists);
		if ( $full ) {
			$so = new adesk_Select();
			$listslist = implode(',', $lists);
			$so->push("AND l.listid IN ('$listslist')");
			$row['fields'] = list_get_fields($lists, false);
			$row['personalizations'] = list_personalizations($so);
		}
	}
	if ( isset($row['html']) ) {
		// check to see what type of RSS they want - show ALL messages, or just NEW
		if ( preg_match('/\|SHOW:ALL%/', $row['html']) ) {
			$row['deskrss_show'] = 'all';
		}
		else {
			$row['deskrss_show'] = 'new';
		}
		// fetch content url if used
		$row['htmlfetchurl'] = '';
		if ( substr(trim($row['html']), 0, 6) == 'fetch:' ) {
			$row['htmlfetchurl'] = substr(trim($row['html']), 6);
		}
	}
	if ( isset($row['text']) ) {
		// check to see what type of RSS they want - show ALL messages, or just NEW
		if ( preg_match('/\|SHOW:ALL%/', $row['text']) ) {
			$row['deskrss_show'] = 'all';
		}
		else {
			$row['deskrss_show'] = 'new';
		}
		$row['textfetchurl'] = '';
		if ( substr(trim($row['text']), 0, 6) == 'fetch:' ) {
			$row['textfetchurl'] = substr(trim($row['text']), 6);
		}
	}
	if ( $listsstr != '' ) {
		// do_basic_personalization
		require_once(adesk_admin('functions/personalization.php'));
		// fetch html content if needed
		if (isset($row['html'])) {
			if ( $row['htmlfetchurl'] ) {
				$row['htmlfetchurl'] = personalization_basic($row['htmlfetchurl'], $row['subject']);
				$row['html'] = adesk_http_get($row['htmlfetchurl'], "UTF-8");
				$row['html'] = message_link_resolve($row['html'], $row['htmlfetchurl']);
			}
			$row['html'] = personalization_basic($row['html'], $row['subject']);
		}

		if (isset($row['text'])) {
			// fetch text content if needed
			if ( $row['textfetchurl'] ) {
				$row['textfetchurl'] = personalization_basic($row['textfetchurl'], $row['subject']);
				$row['text'] = adesk_http_get($row['textfetchurl'], "UTF-8");
			}
			$row['text'] = personalization_basic($row['text'], $row['subject']);
		}
	}

	if (isset($row['html']) || isset($row['text'])) {
		// fetch all links found
		$row['links'] = message_extract_links($row);
		// fetch all images found (for embeding)
		$row['images'] = message_extract_images($row);
	}
	//dbg($row);
	return $row;
}

function message_select_row($id, $lists = '') {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND m.id = '$id'");
	if ( $lists != '' ) {
		$lists = implode("','", array_map('intval', explode('-', $lists)));
		$so->push("AND l.listid IN ('$lists')");
	}

	$r = adesk_sql_select_row(message_select_query($so));
	if ( $r ) {
		$r = message_select_prepare($r, $full = true, $lists);
	}
	return $r;
}

function message_select_array($so = null, $ids = null, $lists = '') {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND m.id IN ('$ids')");
	}
	$r = adesk_sql_select_array(message_select_query($so));
	foreach ( $r as $k => $v ) {
		$r[$k] = message_select_prepare($v, false, $lists);
	}
	return $r;
}

function message_select_array_paginator($id, $sort, $offset, $limit, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;
	$so->push("AND m.hidden = 0");

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'message'");
		$so->push($conds);

		// Using message_select_query() for the COUNT strips out the JOIN stuff, but still passes "WHERE l.listid = ...", so total is always 0
		$total = (int)adesk_sql_num_rows(adesk_sql_query("SELECT COUNT(*) as count FROM #message m LEFT JOIN #message_list l ON m.id = l.messageid WHERE m.hidden = 0 " . $conds . " GROUP BY m.id"));
	}
	else {
		$so->count();
		$total = (int)adesk_sql_select_one(message_select_query($so));
	}

	switch ($sort) {
		default:
		case "01":
			$so->orderby("fromname, fromemail"); break;
		case "01D":
			$so->orderby("fromname, fromemail DESC"); break;
		case "02":
			$so->orderby("subject"); break;
		case "02D":
			$so->orderby("subject DESC"); break;
		case "03":
			$so->orderby("format"); break;
		case "03D":
			$so->orderby("format DESC"); break;
		case "04":
			$so->orderby("lists"); break;
		case "04D":
			$so->orderby("lists DESC"); break;
		case "05":
			$so->orderby("m.id"); break;
		case "05D":
			$so->orderby("m.id DESC"); break;
	}

	$so->slist = array(
		"m.id",
		"fromemail",
		"fromname",
		"IF(( m.subject IS NULL OR m.subject = '' ), m.html, m.subject) AS `subject`",
		"format",
		"COUNT(l.id) AS lists",
	);
	$so->remove = false;

	if ( (int)$limit == 0 ) $limit = 999999999;
	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = message_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function message_select_array_available($ids, $lists) {
	$so = new adesk_Select();
	$so->push("AND m.hidden = 0");
	return message_select_array($so, $ids, $lists);
}

function message_select_list($limit = 0, $ids = null) {
	$so = new adesk_Select();
	$so->push("AND m.hidden = 0");
	$so->slist = array(
		"m.id",
		"IF(( m.subject IS NULL OR m.subject = '' ), m.html, m.subject) AS `subject`",
		//"COUNT(l.id) as lists",
	);

	if ($ids !== null && $ids != 'all') {
		if ( !is_array($ids) ) $ids = explode(",", $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND m.id IN ('$ids')");
	}

	if ( $limit = (int)$limit ) $so->limit($limit);
	$so->orderby('m.id DESC');
	$so->remove = false;
	return message_select_array($so);
}

function message_filter_post() {
	$whitelist = array("subject", "fromemail", "fromname", "reply2", "_content");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "message",
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
			if ( $sect == '_content' ) {
				$conds[] = "( html LIKE '%$content%' OR text LIKE '%$content%' )";
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
	if (isset($_POST["format"])) {
		if ( is_array($_POST['format']) ) {
			if ( count($_POST['format']) > 0 ) {
				if ( !( count($_POST['format']) == 1 and $_POST['format'][0] == '' ) ) {
					$ids = implode("', '", array_map('adesk_sql_escape', $_POST['format']));
					$ary['conds'] .= "AND m.format IN ('$ids') ";
				}
			}
		} else {
			if ( $_POST['format'] != '' ) {
				$format = adesk_sql_escape($_POST['format']);
				$ary['conds'] .= "AND m.format = '$format' ";
			}
		}
	}

	if (isset($_POST["conds"])) {
		$ary['conds'] = $_POST["conds"];
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
			sectionid = 'message'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

function message_insert_post() {
	// find parents
	// find parents
	$lists = array();
	if ( isset($_POST['p']) and is_array($_POST['p']) and count($_POST['p']) > 0 ) {
		$lists = array_diff(array_map('intval', $_POST['p']), array(0));
	}
	if ( !count($lists) ) {
		return adesk_ajax_api_result(false, _a("You did not select any lists."));
	}
	$admin = adesk_admin_get();
	$ary = message_post_prepare();

	if ( isset($ary['error']) && $ary['error'] ) {
		return adesk_ajax_api_result(false, $ary['error_message']);
	}

	$ary['=cdate'] = 'NOW()';
	$ary['userid'] = $admin['id'];

	// check for from email
	if ( !adesk_str_is_email($ary['fromemail']) ) {
		return adesk_ajax_api_result(false, _a("From Email Address is not valid."));
	}
	// check for subject
	if ( $ary['subject'] == '' ) {
		if ( !( $ary['format'] != 'text' and preg_match('/^fetch:.*$/', $ary['html']) ) ) {
			return adesk_ajax_api_result(false, _a("Email Subject can not be left empty."));
		}
	}
	// do TEXT inbody checks
	if ( $ary['format'] != 'html' and !preg_match('/^fetch:.*$/', $ary['text']) ) {
		// check for confirmation links
		if ( adesk_http_param('formsource') != -1 and $admin['unsubscribelink'] and !adesk_str_instr('%UNSUBSCRIBELINK%', $ary['text']) ) {
			return adesk_ajax_api_result(false, _a("Text version does not contain an unsubscription link."));
		}
		// check for conditional content
		if ( adesk_str_instr('%/IF%', strtoupper($ary['text'])) ) {
			if ( $ary['text'] == message_conditional_check($ary, false) ) {
				return adesk_ajax_api_result(false, _a("There is an error with your conditional content. You may have invalid or missing parts of your condtions."));
			}
		}
	}
	// do HTML inbody checks
	if ( $ary['format'] != 'text' and !preg_match('/^fetch:.*$/', $ary['html']) ) {
		// check for confirmation links
		/*
		if ( adesk_http_param('formsource') != -1 and $admin['unsubscribelink'] and !adesk_str_instr('%UNSUBSCRIBELINK%', $ary['html']) ) {
			return adesk_ajax_api_result(false, _a("HTML version does not contain an unsubscription link."));
		}
		*/
		// check for conditional content
		if ( adesk_str_instr('%/IF%', strtoupper($ary['html'])) ) {
			if ( $ary['html'] == message_conditional_check($ary, true) ) {
				return adesk_ajax_api_result(false, _a("There is an error with your conditional content. You may have invalid or missing parts of your condtions."));
			}
		}

	}

	$sql = adesk_sql_insert("#message", $ary);
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Email Message could not be added."));
	}
	$id = adesk_sql_insert_id();

	// save file attachments
	$files = adesk_http_param('attach');
	if ( is_array($files) ) {
		$list = implode("', '", $files);
		// save new
		adesk_sql_query("UPDATE #message_file SET `messageid` = '$id' WHERE `id` IN ('$list')");
	}
	$site = adesk_site_get();
	// delete all old attachments (if submitted before handler could remove them)
	$sql = adesk_sql_query("SELECT `id` FROM #message_file WHERE `messageid` = '0'");
	while ( $row = adesk_sql_fetch_row($sql) ) {
		if ( $site['message_attachments_location'] == 'db' ) {
			adesk_file_upload_remove('#message_file', '#message_file_data', $row[0]);
		} else {
			adesk_file_upload_remove('#message_file', adesk_base('files'), $row[0]);
		}
	}

	// list relations
	foreach ( $lists as $l ) {
		if ( $l > 0 ) adesk_sql_insert('#message_list', array('id' => 0, 'messageid' => $id, 'listid' => $l));
	}

	return adesk_ajax_api_added(_a("Email Message"), array('id' => $id, 'subject' => $ary['subject']));
}

function message_update_post() {
	$admin = adesk_admin_get();
	// find parents
	$lists = array();
	if ( isset($_POST['p']) and is_array($_POST['p']) and count($_POST['p']) > 0 ) {
		$lists = array_diff(array_map('intval', $_POST['p']), array(0));
	}
	if ( !count($lists) ) {
		return adesk_ajax_api_result(false, _a("You did not select any lists."));
	}
	$ary = message_post_prepare();

	if ( isset($ary['error']) && $ary['error'] ) {
		return adesk_ajax_api_result(false, $ary['error_message']);
	}

	// check for from email
	if ( !adesk_str_is_email($ary['fromemail']) ) {
		return adesk_ajax_api_result(false, _a("From Email Address is not valid."));
	}
	// check for subject
	if ( $ary['subject'] == '' ) {
		if ( !( $ary['format'] != 'text' and preg_match('/^fetch:.*$/', $ary['html']) ) ) {
			return adesk_ajax_api_result(false, _a("Email Subject can not be left empty."));
		}
	}
	// do TEXT inbody checks
	if ( $ary['format'] != 'html' and !preg_match('/^fetch:.*$/', $ary['text']) ) {
		// check for confirmation links
		if ( adesk_http_param('formsource') != -1 and $admin['unsubscribelink'] and !adesk_str_instr('%UNSUBSCRIBELINK%', $ary['text']) ) {
			return adesk_ajax_api_result(false, _a("Text version does not contain an unsubscription link."));
		}
		// check for conditional content
		if ( adesk_str_instr('%/IF%', strtoupper($ary['text'])) ) {
			if ( $ary['text'] == message_conditional_check($ary, false) ) {
				return adesk_ajax_api_result(false, _a("There is an error with your conditional content. You may have invalid or missing parts of your condtions."));
			}
		}
	}
	// do HTML inbody checks
	if ( $ary['format'] != 'text' and !preg_match('/^fetch:.*$/', $ary['html']) ) {
		// check for confirmation links
		/*
		if ( adesk_http_param('formsource') != -1 and $admin['unsubscribelink'] and !adesk_str_instr('%UNSUBSCRIBELINK%', $ary['html']) ) {
			return adesk_ajax_api_result(false, _a("HTML version does not contain an unsubscription link."));
		}
		*/
		// check for conditional content
		if ( adesk_str_instr('%/IF%', strtoupper($ary['html'])) ) {
			if ( $ary['html'] == message_conditional_check($ary, true) ) {
				return adesk_ajax_api_result(false, _a("There is an error with your conditional content. You may have invalid or missing parts of your condtions."));
			}
		}
	}

	$id = intval($_POST["id"]);
	$sql = adesk_sql_update("#message", $ary, "id = '$id'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Email Message could not be updated."));
	}

	$site = adesk_site_get();
	// save file attachments
	$files = adesk_http_param('attach');
	if ( is_array($files) ) {
		$list = implode("', '", $files);
		// save new
		adesk_sql_query("UPDATE #message_file SET `messageid` = '$id' WHERE `id` IN ('$list')");
		// delete all old attachments (if submitted before handler could remove them)
		$sql = adesk_sql_query("SELECT `id` FROM #message_file WHERE `messageid` = '$id' AND `id` NOT IN ('$list')");
		while ( $row = adesk_sql_fetch_row($sql) ) {
			if ( $site['message_attachments_location'] == 'db' ) {
				adesk_file_upload_remove('#message_file', '#message_file_data', $row[0]);
			} else {
				adesk_file_upload_remove('#message_file', adesk_base('files'), $row[0]);
			}
		}
	} else {
		// delete all old attachments (if submitted before handler could remove them)
		$sql = adesk_sql_query("SELECT `id` FROM #message_file WHERE `messageid` = '$id'");
		while ( $row = adesk_sql_fetch_row($sql) ) {
			if ( $site['message_attachments_location'] == 'db' ) {
				adesk_file_upload_remove('#message_file', '#message_file_data', $row[0]);
			} else {
				adesk_file_upload_remove('#message_file', adesk_base('files'), $row[0]);
			}
		}
	}

	// list relations
	$cond = implode("', '", $lists);
	$admincond = '';
	if ( !adesk_admin_ismain() ) {
		//$admin = adesk_admin_get();
		$admincond = "AND listid IN ('" . implode("', '", $admin['lists']) . "')";
	}
	adesk_sql_delete('#message_list', "messageid = '$id' AND listid NOT IN ($cond) $admincond");
	foreach ( $lists as $l ) {
		if ( $l > 0 ) {
			if ( !adesk_sql_select_one('=COUNT(*)', '#message_list', "messageid = '$id' AND listid = '$l'") )
				adesk_sql_insert('#message_list', array('id' => 0, 'messageid' => $id, 'listid' => $l));
		}
	}

	// clear all message sources for this message
	campaign_source_clear(null, $id, null);

	return adesk_ajax_api_updated(_a("Email Message"), array('id' => $id, 'subject' => $ary['subject']));
}

function message_delete($id) {
	$id = intval($id);
	$admincond = '';
	$used = (int)adesk_sql_select_one('=COUNT(*)', '#campaign_message', "messageid = '$id'");
	/*
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		$admincond = "AND listid IN ('" . implode("', '", $admin['lists']) . "')";
	}
	*/
	if ( $used ) {
		adesk_sql_update_one('#message', 'hidden', 1, "id = '$id' $admincond");
	} else {
		adesk_sql_delete('#message', "id = '$id' $admincond");
		adesk_sql_delete('#message_file', "messageid = '$id'");
		# If any data segments no longer have any files associated with them, delete them.
		adesk_sql_delete('#message_file_data mfd', "(SELECT _f.id FROM #message_file _f WHERE _f.id = mfd.fileid) IS NULL");
	}
	return adesk_ajax_api_deleted(_a("Email Message"));
}

function message_delete_multi($ids, $filter = 0) {
	$deletelist = $hidelist = array();
	if ( $ids == '_all' ) $ids = null;
	$so = new adesk_Select();
	$so->slist = array(
		'm.id',
		//'COUNT(l.id) AS lists',
		'(SELECT COUNT(cm.id) FROM #campaign_message cm WHERE m.id = cm.messageid) AS used',
	);
	$so->remove = false;
	$filter = intval($filter);
	if ($filter > 0) {
		$admin = adesk_admin_get();
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'message'");
		$so->push($conds);
	}
	$all = message_select_array($so, $ids);
	foreach ( $all as $v ) {
		if ( $v['used'] ) {
			$hidelist[] = $v['id'];
		} else {
			$deletelist[] = $v['id'];
		}
	}
	// do hide
	$ids = implode("','", $hidelist);
	adesk_sql_update_one('#message', 'hidden', 1, "id IN ('$ids')");
	// do delete
	$ids = implode("','", $deletelist);
	adesk_sql_delete('#message'              , "id IN ('$ids')");
	adesk_sql_delete('#message_file'         , "messageid IN ('$ids')");
	# If any data segments no longer have any files associated with them, delete them.
	adesk_sql_delete('#message_file_data mfd', "(SELECT _f.id FROM #message_file _f WHERE _f.id = mfd.fileid) IS NULL");
	return adesk_ajax_api_deleted(_a("Email Message"));
}

function message_post_prepare() {
	// message
	$types = array('text', 'html', 'mime');
	$ary = array();
	$ary['name'] = (string)adesk_http_param('messagename');
	$ary['format'] = (string)adesk_http_param('format');
	if ( !in_array($ary['format'], $types) ) $ary['format'] = 'text';
	$ary['fromname'] = (string)adesk_http_param('fromname');
	$ary['fromemail'] = (string)adesk_http_param('fromemail');
	$ary['subject'] = (string)adesk_http_param('subject');
	$ary['reply2'] = (string)adesk_http_param('reply2');
	$ary['priority'] = (string)adesk_http_param('priority');
	$ary['charset'] = (string)adesk_http_param('charset');
	$ary['encoding'] = (string)adesk_http_param('encoding');
	$ary['text'] = (string)adesk_http_param('text');
	$ary['textfetch'] = 'now';
	if ( adesk_http_param('textconstructor') == 'external' ) {
	  $textfetch_url = (string)adesk_http_param('textfetch');
	  /*
	  if ( !adesk_str_is_url($textfetch_url) ) {
	    adesk_b64_decode($textfetch_url);
	  }
	  else {
	    adesk_b64_encode($textfetch_url);
	  }
	  */
	  $textfetch_url = adesk_b64_decode($textfetch_url);
		$ary['text'] = 'fetch:' . $textfetch_url;
		$ary['textfetch'] = (string)adesk_http_param('textfetchwhen');
	}
	$ary['html'] = (string)adesk_http_param('html');
	$ary["html"] = adesk_str_strip_malicious($ary["html"]);
	$ary['htmlfetch'] = 'now';

	// do some basic validation of conditional content tags
	$if_occurrences_exist = preg_match_all('/%if/i', $ary['html'], $if_occurrences);
	if ($if_occurrences_exist && isset($if_occurrences[0]) && $if_occurrences[0]) {
		$if_occurrences_total = count($if_occurrences[0]);
		$if_closing_occurrences_exist = preg_match_all('/%\/if%/i', $ary['html'], $if_closing_occurrences);
		$if_closing_occurrences_total = 0;
		if ($if_closing_occurrences_exist && isset($if_closing_occurrences[0]) && $if_closing_occurrences[0]) {
			$if_closing_occurrences_total = count($if_closing_occurrences[0]);
		}
		// if there are more opening IF tags (%IF) than closing IF tags (%/IF%), stop them from saving
		if ($if_occurrences_total != $if_closing_occurrences_total) {
			$ary['error'] = true;
			$ary['error_message'] = _a('Please verify you are closing all conditional IF statements properly');
		}
	}

	if ( adesk_http_param('htmlconstructor') == 'external' ) {
	  $htmlfetch_url = (string)adesk_http_param('htmlfetch');
	  /*
	  if ( !adesk_str_is_url($htmlfetch_url) ) {
	    adesk_b64_decode($htmlfetch_url);
	  }
	  else {
	    adesk_b64_encode($htmlfetch_url);
	  }
	  */
	  $htmlfetch_url = adesk_b64_decode($htmlfetch_url);
		$ary['html'] = 'fetch:' . $htmlfetch_url;
		$ary['htmlfetch'] = (string)adesk_http_param('htmlfetchwhen');
	}

	# Fix for tinyMCE, which is converting ampersands to &amp;
	$ary["text"] = str_replace('&amp;', '&', $ary["text"]);
	$ary["html"] = str_replace('&amp;', '&', $ary["html"]);
	$ary['html'] = adesk_str_fixtinymce($ary['html']);

	$ary['=mdate'] = 'NOW()';
	return $ary;
}

function message_conditional_check($row, $isHTML = true) {
	return $row[( $isHTML ? 'html' : 'text' )] . 'blah'; // add something so they're not the same (same = error)
}

function message_fetch_url($url, $type = 'text') {
	$url = adesk_b64_decode($url);
	$r = array(
		'url' => $url,
		'type' => $type,
		'data' => '',
		'pagetitle' => '',
	);
	$r['data'] = (string)@adesk_http_get($url, "utf-8");
	if ( !$r['data'] ) {
		return adesk_ajax_api_result(0, _a("URL could not be fetched."), $r);
	}
	// try to find the title
	if ( $type != 'text' ) {
		preg_match('/<title>(.*)<\/title>/i', $r['data'], $matches);
		if ( isset($matches[1]) ) $r['pagetitle'] = $matches[1];
		$r['data'] = message_link_resolve($r['data'], $url);
	} else {
		$r['stripped'] = adesk_str_strip_tags($r['data']);
	}
	return adesk_ajax_api_result(1, _a("Content Fetched."), $r);
}

function message_html2text($row) {
	if ( isset($row['htmlfetchurl']) && $row['htmlfetchurl'] ) {
		// do_basic_personalization
		$row['htmlfetchurl'] = personalization_basic($row['htmlfetchurl'], $row['subject']);
		$row['html'] = adesk_http_get($row['htmlfetchurl'], "UTF-8");
		$row['html'] = message_link_resolve($row['html'], $row['htmlfetchurl']);
	}
	return adesk_htmltext_convert($row["html"]);
}

function message_fetch_upload($file, $type = 'text') {
	$r = array(
		'file' => $file,
		'type' => $type,
		'data' => '',
		'pagetitle' => '',
	);
	// check if file(s) uploaded properly
	$path = adesk_cache_dir($file);
	if ( file_exists($path) ) {
		$r['data'] = (string)@adesk_file_get($path);
		if ( !$r['data'] ) {
			return adesk_ajax_api_result(0, _a("File Upload not specified."), $r);
		}
		// try to find the title
		if ( $type != 'text' ) {
			preg_match('/<title>(.*)<\/title>/i', $r['data'], $matches);
			if ( isset($matches[1]) ) $r['pagetitle'] = $matches[1];
		}
	} else {
		return adesk_ajax_api_result(0, _a("File Upload not specified."), $r);
	}
	return adesk_ajax_api_result(1, _a("File Uploaded."), $r);
}

function message_attachments($files) {
	$r = array();
	foreach ( $files as $f ) {
		if ( !isset($r[$f['name']]) ) {
			$f['data']     = message_attachment($f['id']);
			$r[$f['name']] = $f;
		}
	}
	return $r;
}

function message_attachment($id) {
	$site = adesk_site_get();
	if ( $site['message_attachments_location'] == 'fs' ) {
		$tabled = adesk_base('files/message-');
	} else {
		$tabled = '#message_file_data';
	}
	return adesk_file_upload_get_data($tabled, $id);
}

function message_extract_links($row) {
	$r = array();
	if ( $row['format'] != 'text' ) {
		$links = message_parse_links($row['html'], array(), 'html');
		foreach ( $links as $k => $v ) {
			$kesc = adesk_sql_escape(message_link_internal($k));
			$r[] = array(
				'id' => intval(adesk_sql_select_one("SELECT id FROM #link WHERE link = '$kesc' AND messageid = '$row[id]'")),
				'link' => $k,
				'count' => $v['count'],
				'format' => 'html',
				'messageid' => $row['id'],
				'actions' => array(),
				'title' => $v['title'],
			);
		}
	}
	if ( $row['format'] != 'html' ) {
		$links = message_parse_links($row['text'], array(), 'text');
		foreach ( $links as $k => $v ) {
			$kesc = adesk_sql_escape(message_link_internal($k));
			$r[] = array(
				'id' => intval(adesk_sql_select_one("SELECT id FROM #link WHERE link = '$kesc' AND messageid = '$row[id]'")),
				'link' => $k,
				'count' => $v['count'],
				'format' => 'text',
				'messageid' => $row['id'],
				'actions' => array(),
				'title' => $v['title'],
			);
		}
	}
	return $r;
}

function message_parse_links(&$str, $parse = array(), $format = 'html') {
	$r = array();
	if ( $format != 'text' ) $format = 'html';

	// HTML
	if ( $format == 'html' ) {
		$tagsArr = array('a' => true, 'area' => false);
		foreach ( $tagsArr as $tag => $long ) {
			if ( $long ) {
				$tagPattern = '/<' . $tag . '\s(.*?)<\/' . $tag . '>/si';
			} else {
				$tagPattern = '/<' . $tag . '\s(.*?)>/si';
			}
			preg_match_all($tagPattern, $str, $anchors);
			foreach ( $anchors[0] as $a ) {
				message_parse_links_recurring($str, $r, adesk_str_preg_link('"'), '"', $parse, $a);
				message_parse_links_recurring($str, $r, adesk_str_preg_link("'"), "'", $parse, $a);
			}
		}
	} elseif ( $format == 'text' ) {
		message_parse_links_recurring($str, $r, adesk_str_preg_link(''), '', $parse);
	}
	return $r;
}

function message_parse_links_recurring(&$str, &$r, $pattern = '', $wrap = '', $parse = array(), $lookIn = null) {
	$url = adesk_site_plink("lt.php?c=cmpgnid&m=currentmesg&nl=currentnl&s=subscriberid&lid=%s&l=");
	if ( !$lookIn ) $lookIn = $str;
	preg_match_all($pattern, $lookIn, $matches);
	// grab TITLE attribute and value
	preg_match_all("/title=['\"]+[^'\"]+['\"]+/i", $lookIn, $matches2);
	preg_match_all("/aclinkname=['\"]+[^'\"]+['\"]+/i", $lookIn, $matches3);
	/* DOUBLE QUOTES */
	foreach ( $matches[0] as $v ) {
		if ( $v == '' ) continue;
		if ( $v == 'href=' . $wrap ) continue;
		if ( adesk_str_instr('href=', $pattern) and !adesk_str_instr('href=', $v) ) continue;
		if ( !preg_match('/http/i', $v) and !preg_match('/ftp/i', $v) ) continue;
		if ( substr($v, 0, 1) == '#' ) continue;
		if ( adesk_str_instr('mailto:', $v) ) continue;
		if ( adesk_str_instr('%UNSUBSCRIBELINK%', $v) ) continue;
		if ( adesk_str_instr('%WEBCOPYLINK%', $v) ) continue;
		if ( adesk_str_instr('%UPDATEPROFILE%', $v) ) continue;
		if ( adesk_str_instr('t_go.php', $v) ) continue;
		if ( adesk_str_instr('/lt.php', $v) ) continue;
		if ( adesk_str_instr('/surround.php', $v) ) continue;
		if ( adesk_str_instr('|SHOW', $v) ) continue;		# This is going to be an RSS feed...
		// prepare the link
		$v = str_replace($wrap . ' target=' . $wrap . '_', '', $v);
		//$something = str_replace("?", "\?", $something);
		$v = str_replace('href=', '', $v);
		$old_link = $new_link = str_replace($wrap, '', $v);
		// if anything is left as a link
		if ( $new_link != '' and $old_link != '' ) {
			// build new version of a link
			$parts = explode('#', $new_link);
			$new_link = $parts[0];
			$new_link = str_replace('?', '--Q-', $new_link);
			$new_link = str_replace('&amp;', '--A-', $new_link);
			$new_link = str_replace('&', '--A-', $new_link);
			$new_link = str_replace('=', '--E-', $new_link);
			$new_link = str_replace('+', '--PL-', $new_link);
			$new_link = str_replace('http://', '-http--', $new_link);
			$new_link = str_replace('https://', '-https--', $new_link);
			$new_link = str_replace('ftp://', '-ftp--', $new_link);
			$new_link = str_replace('ftp://', '-ftp--', $new_link);
			if ( isset($parts[1]) ) $new_link .= '--PND-' . $parts[1];
			// check if we should parse this one
			if ( count($parse) > 0 ) {
				foreach ( $parse as $p ) {
					if (!$p['tracked'])
						continue;

					if ( $p['link'] == $old_link or $p['link'] == message_link_internal($old_link)) {
						$str = str_replace($wrap . $old_link . $wrap, $wrap . sprintf($url, $p['id']) . $new_link . $wrap, $str);
					}
				}
			}

			// if title="something" is found in the <a> element
			if ( isset($matches3[0][0]) ) {
				// clean up the string so we only grab the value portion
				$title = $matches3[0][0];
				$title = explode("=", $title);
				$title = preg_replace("/['\"]+/", "", $title[1]);
			}
			else {
				if ( isset($matches2[0][0]) ) {
					// clean up the string so we only grab the value portion
					$title = $matches2[0][0];
					$title = explode("=", $title);
					$title = preg_replace("/['\"]+/", "", $title[1]);
				}
				else {
					$title = "";
				}
			}

			if ( isset($r[message_link_internal($old_link)]) ) {
				$r[message_link_internal($old_link)]["count"]++;
				// only update it if it's empty
				if ($r[message_link_internal($old_link)]["title"] == "") {
					$r[message_link_internal($old_link)]["title"] = $title;
				}
			} else {
				$r[message_link_internal($old_link)] = array( "count" => 1, "title" => $title );
			}
			//print "found 1<br>old - $old_link<br>new - $new_link";
			//print $new_link;
		}
	}
}

function message_link_cleanup($url) {

	$parts = explode('--PND-', $url);
	$url = $parts[0];
//dbg($url);
	$arr = explode('--Q-', $url, 2);
	if ( count($arr) == 2 ) {
		$url = $arr[0] . '--Q-' . str_replace("%2F", "/", rawurlencode($arr[1])); // some servers like slashes as variable names, but wont decode them back
	}

	$arr = explode('|Q|', $url, 2);
	if ( count($arr) == 2 ) {
		$url = $arr[0] . '|Q|' . str_replace("%2F", "/", rawurlencode($arr[1])); // some servers like slashes as variable names, but wont decode them back
	}

	$url = str_replace('|Q|', '?', $url);
	$url = str_replace('|E|', '=', $url);
	$url = str_replace('|A|', '&', $url);
	$url = str_replace('--Q-', '?', $url);
	$url = str_replace('--E-', '=', $url);
	$url = str_replace('--A-', '&', $url);
	$url = str_replace('--PL-', '+', $url);
	$url = str_replace('-http--', 'http://', $url);
	$url = str_replace('-https--', 'https://', $url);
	$url = str_replace('-ftp--', 'ftp://', $url);
	if ( preg_match('/\s/', $url) ) {
		$url = preg_replace('/\s/', '%20', $url);
	}
	if ( isset($parts[1]) ) $url .= '#' . $parts[1];
	return $url;
}

// reference: http://www.google.com/support/googleanalytics/bin/answer.py?answer=55578&cbid=-xhfk4b7ynhst
function message_link_analytics($url, $list, $campaign, $subscriber) {
	// list checks
	if ( !$list ) return $url;
	if ( !$list['p_use_analytics_link'] ) return $url;
	if ( !$list['analytics_domains'] ) return $url;
	$domains = array_map('strtolower', explode("\n", $list['analytics_domains']));
	$arr = parse_url($url);

	if ( !isset($arr['host']) ) return $url;

	$found = ( in_array(strtolower($arr['host']), $domains) );
	if ( !$found ) {
		$sqlhost = adesk_sql_escape($arr['host']);
		foreach ( $domains as $domain ) {
			if ( !adesk_str_instr('%', $domain) ) continue;
			$sqldomain = adesk_sql_escape($domain, false); // don't escape wildcards
			$res = (int)adesk_sql_select_one("SELECT IF( '$sqlhost' LIKE '$sqldomain', 1, 0 )");
			if ( $res ) {
				$found = true;
			}
		}
	}

	if ( !$found ) return $url;

	// campaign checks
	if ( !$campaign ) return $url;
	if ( !$campaign['tracklinksanalytics'] ) return $url;
	// source
	$source = $list['analytics_source'];
	if ( !$source ) $source = $list['name'];
	// campaign
	$campaignname = $campaign['analytics_campaign_name'];
	if ( !$campaignname ) $campaignname = $campaign['name'];
	// addon
	$addon =
		'utm_source=' . urlencode(trim($source)) . '&' .
		'utm_medium=email&' .
		'utm_campaign=' . urlencode(trim($campaignname))
	;
	if ( isset($subscriber['email']) ) $addon .= '&utm_content=' . urlencode(trim($subscriber['email']));
	return $url . ( adesk_str_instr('?', $url) ? '&' : '?' ) . $addon;
}

// reference: http://code.google.com/apis/analytics/docs/gaTrackingTroubleshooting.html
function message_read_analytics($campaign, $message) {
	// campaign checks
	if ( !$campaign ) return '';
	if ( !$campaign['trackreadsanalytics'] ) return '';
	//$ua = '';
	$arr = parse_url(adesk_site_plink());
	$host = $arr['host'];
	if ( !isset($arr['path']) or !$arr['path'] ) $arr['path'] = '/';
	//$path = $arr['path'];
	// construct image url
	$url  = 'http://www.google-analytics.com/__utm.gif?';
	$url .= 'utmwv=3&'; // tracking code version
	$url .= 'utmn=rndmnmbr&'; // random number
	$url .= 'utme=&'; // X-10 data parameter
	$url .= 'utmcs=' . $message['charset'] . '&'; // character set used
	$url .= 'utmsr=600x800&'; // screen resolution
	$url .= 'utmsc=24-bit&'; // screen color depth
	$url .= 'utmul=' . _i18n("en-us") . '&'; // browser language
	$url .= 'utmje=0&'; // not java enabled
	$url .= 'utmfl=-&'; // flash version
	$url .= 'utmhn=' . rawurlencode($host) . '&'; // host name
	$url .= 'utmhid=2112093191&'; // random number / adsense id
	$url .= 'utmr=-&'; // referer
	//$url .= 'utmr=' . rawurlencode(ac-site_plink('index.php?action=subscribe&nl=currentnl')) . '&'; // referer
	$url .= 'utmp=' . rawurlencode('/forward3.php?nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid') . '&'; // this page
	$url .= 'utmac=%ANALYTICSUA%&'; // analytics UA
	$url .= 'utmcc=__utma%3D117243.1695285.22%3B%2B __utmz%3D117945243.1202416366.21.10. utmcsr%3Db%7C utmccn%3D(referral)%7C utmcmd%3Dreferral%7C utmcct%3D%252Fissue%3B%2B&'; // cookie value
	//$url .= 'utmcc=&'; // cookie value
	$url .= 'utmdt=' . rawurlencode($message['subject']); // page title
	return $url;
}

function message_link_actions($subscriber, /*$list, $campaign,*/ $link) {
	$actions = campaign_links_actions($link['id']);
	foreach ( $actions as $action ) {
		switch ( $action['action'] ) {
			case 'subscribe':
				subscriber_list_add($subscriber, (int)$action['value']);
				break;
			case 'unsubscribe':
				subscriber_list_remove($subscriber, (int)$action['value']);
				break;
			case 'send':
				$campaign2send = campaign_select_row((int)$action['value']);
				campaign_send(null, $campaign2send, $subscriber, 'send');
				break;
			case 'update':
				list($field, $value) = explode('||', $action['value']);
				subscriber_update_info($subscriber, $field, $value);
				break;
		}
	}
}

function message_extract_images($row) {
	$r = array();
	$images = message_parse_images($row['html'], false);
	foreach ( $images as $k => $v ) {
		$r = array(
			'link' => $k,
			'hash' => $v
		);
	}
	return $r;
}

function message_parse_images(&$html, $embed = false) {
	require_once(awebdesk_functions('mime.php'));
	return adesk_mail_embed_images($html, $embed);
}
/*
function message_striptags() {
	$html = urldecode(strval(adesk_http_param("html")));
	$html = preg_replace('/<title>([^<]+)<\/title>/', '', $html);
	$html = strip_tags($html);

	# Do another pass for some common entities.
	$html = str_replace("&nbsp;", " ", $html);
	$html = str_replace("&ndash;", "-", $html);
	$html = str_replace("&mdash;", "--", $html);
	$html = str_replace("&quot;", '"', $html);

	return array(
		"text" => trim($html),
	);
}
*/
function message_overlay_popup($link, $idx, $totalopens) {
	if (!$link) {
		return "<img src='images/overlay_button_grey.gif' border='0' class='overlayimg'/>";
	}

	if ($totalopens == 0)
		$rate = 0;
	else
		$rate = number_format($link["clicks"] / $totalopens * 100, 2);

	$minibar = "<span id='overlayBar$idx' class='overlayONbar'>";
	$block   = "<span id='overlayBlock$idx' class='overlayOFF' style='color: black; text-align: left;'>";
	$block  .= sprintf(_a("Link clicked on %s times. (%.2lf%%)"), $link['clicks'], $link['percent']) . "<br>";
	$block  .= sprintf(_a("Link clicked on by %s subscriber(s). (%.2lf%%)"), $link['people'], $link['peoplepercent']) . "<br>";
	$block  .= sprintf(_a("Click to Open Rate: %.2lf%%"), $rate) . '<div style="margin-top: 5px;">';

	foreach ($link['bars'] as $bar) {
		$minibar .= "<img src='images/gradient_$bar.gif' width='3' height='6'>";
		$block   .= "<img src='images/gradient_$bar.gif' width='10' height='10'>";
	}

	$minibar .= "</span>";
	$block   .= '</div></span>';
	$link     = "<a href='javascript: flipOverlayBlock(\"$idx\");'><img src='images/overlay_button_blue.gif' border='0'  class='overlayimg'/></a>";

	return $minibar . $block . $link;
}

function message_overlay_percent($top, $bottom) {
	if ($bottom == 0)
		return 0;
	return round($top / $bottom * 100, 2);
}

function message_overlay_bars($percent) {
   // make an array that is full of 1's or 2's, to show the black or gray graphic
   // make one new element for every 5%.
   $bars = array();
   $c    = 0;
   for ($i = 1; $i <= 20; $i++) {
       if ($percent <= $c)
           $bars[] = 2;
       else
           $bars[] = 1;

       $c += 5;
   }
   return $bars;
}

function message_overlay($mesg, $source, $campaignid) {
	$totalopens = adesk_sql_select_one("
		SELECT
			COUNT(*)
		FROM
			#link l,
			#link_data d
		WHERE
			l.id = d.linkid
		AND
			l.link = 'open'
		AND
			l.tracked = 1
		AND
			l.messageid = '$mesg[id]'
	");

	$rs = adesk_sql_query("
		SELECT
			*
		FROM
			#link
		WHERE
			messageid = '$mesg[id]'
		AND
			campaignid = '$campaignid'
		AND
			link != 'open'
		AND
			tracked = 1
	");

	$links = array();

	while ($link = adesk_sql_fetch_assoc($rs)) {
		$clicks = adesk_sql_select_row("
			SELECT
				COUNT(*) as people,
				SUM(times) as total
			FROM
				awebdesk_link l,
				awebdesk_link_data d
			WHERE
				l.id = '$link[id]'
			AND
				d.linkid = '$link[id]'
		");

		$link['clicks']        = $clicks['total'];
		$link['people']        = $clicks['people'];
		$link['peoplepercent'] = message_overlay_percent($link['people'], $totalopens);
		$link['percent']       = message_overlay_percent($link['clicks'], $totalopens);
		$link['bars']          = message_overlay_bars($link['percent']);
		$links[$link['link']]  = $link;
	}

	$html    = $source;
	$hlen    = strlen($html);
	$out     = "";
	$linkidx = 0;

	for ($i = 0; $i < $hlen; $i++) {
		if ($html[$i] == '<' && (strtoupper($html[$i+1]) == 'A')) {
			$off = $i + 3;	# Begin after, presumably, the whitespace following "<A".
			$url = "";

			while ($off < $hlen && strtoupper(substr($html, $off, 5)) != "HREF=")
				$off++;

			$off += 5;	# Skip HREF=.  If we went past the end of the string, we'll still catch that.

			if ($off >= $hlen)
				break;

			if ($html[$off] != "'" && $html[$off] != '"')
				break;

			$end = $html[$off];
			$off++;

			if ($off >= $hlen)
				break;

			while ($off < $hlen && $html[$off] != $end) {
				$url .= $html[$off];
				$off++;
			}

			$url = message_link_real($url);

			// message_link_internal returns the same link, or a shortened version if it's internal (to omit personalized values)
			if (isset($links[message_link_internal($url)])) {
				$out .= message_overlay_popup($links[message_link_internal($url)], $linkidx++, $totalopens);
			}
		}

		$out .= $html[$i];
	}

	$post = '
<script>
function flipOverlayBlock(divID) {
	var oldClass = document.getElementById("overlayBlock" + divID).className;
	document.getElementById("overlayBlock" + divID).className = ( oldClass == "overlayOFF" ? "overlayON" : "overlayOFF" );
	document.getElementById("overlayBar" + divID).className = ( oldClass == "overlayOFF" ? "overlayOFF" : "overlayONbar" );
}
function showOverlays() {
	var x = document.getElementsByTagName("div");
	for ( var i = 0; i < x.length; i++ ) {
		if ( x[i].id == "overlayBlock" ) x[i].className = "overlayON";
	}
}
</script>
<style>
.overlayON {z-index: 99; display: inline; position: absolute; background:#FFFCE8; font-size: 10px; font-family:arial; border:1px dashed #FFF066; padding:10px; filter:alpha(opacity=90); opacity:.9; -moz-opacity:.9; margin-top:15px; margin-left:0px;}
.overlayONbar {z-index: 98; display: inline; padding: 1px; position: absolute; background:#FFFCE8; border:1px dashed #FFF066; padding:1px; filter:alpha(opacity=90); opacity:.9; -moz-opacity:.9; margin-top:20px; margin-left:0px;}
.overlayOFF {display: none;}
.overlayimg {z-index: 97; display: inline; position: absolute;   }

</style>
	';
	return $out . $post;
}

function message_spam_emailcheck() {
	$r = campaign_quick_send(
		trim((string)adesk_http_param('spamcheckemail')),
		-1,
		0, // (int)adesk_http_param('spamcheckemailsplit'),
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
		echo check_spam_postmark($r);
	exit;
}

function message_send_emailtest() {
	$r = campaign_quick_send(
		trim((string)adesk_http_param('testemailmessage')),
		-1,
		0, // -1
		trim((string)adesk_http_param('testemailmessagetype')),
		'test'
	);
	if ( is_array($r) ) return $r;
	return adesk_ajax_api_result($r > 0, _a("Test Email Sent"), array('sent' => $r));
}

function message_post2preparedcampaign() {
	// find basic campaign info
	$row = campaign_new();
	if ( isset($GLOBALS['_hosted_account']) ) {
		$row['htmlunsub'] =
		$row['textunsub'] = 0;
		$row['htmlunsubdata'] =
		$row['textunsubdata'] = '';
		$row['bounceid'] = -1;
	}

	//turn analytics off for test message
	$row['tracklinksanalytics'] = 0;
	$row['trackreadsanalytics'] = 0;

	// find parents
	$lists = array();
	$p = adesk_http_param('p');
	if ( is_array($p) and count($p) > 0 ) {
		$lists = array_diff(array_map('intval', $p), array(0));
	}
	$row['listslist'] = implode('-', $lists);
	$row['lists'] = list_select_array(null, implode(',', $lists));
	foreach ( $row['lists'] as $k => $v ) {
		$row['lists'][$k]['relid'] = 0;
	}

	// calculate list limits
	$row['p_duplicate_send']     = 1;
	$row['p_embed_image']        = 0;
	$row['p_use_scheduling']     = ( $row['status'] == 3 or $row['status'] == 4 );
	$row['p_use_tracking']       = 0;
	$row['p_use_analytics_read'] = 0;
	$row['p_use_analytics_link'] = 0;
	$row['p_use_twitter']        = 0;
	$row['p_use_facebook']       = 0;
	foreach ( $row['lists'] as $l ) {
		if ( !$l['p_duplicate_send'] )    $row['p_duplicate_send']     = $l['p_duplicate_send'];
		if ( $l['p_embed_image'] )        $row['p_embed_image']        = $l['p_embed_image'];
		if ( $l['p_use_tracking'] )       $row['p_use_tracking']       = $l['p_use_tracking'];
		if ( $l['p_use_analytics_read'] ) $row['p_use_analytics_read'] = $l['p_use_analytics_read'];
		if ( $l['p_use_analytics_link'] ) $row['p_use_analytics_link'] = $l['p_use_analytics_link'];
		if ( $l['p_use_twitter'] )        $row['p_use_twitter']        = $l['p_use_twitter'];
		if ( $l['p_use_facebook'] )       $row['p_use_facebook']       = $l['p_use_facebook'];
	}

	// fetch all fields (for those lists only, globals should be prefetched elsewhere)
	$row['fields'] = list_get_fields($lists, false);

	// set message
	$message = message_post_prepare();
	$message['id'] = $messageid = -1;
	$message['userid'] = $GLOBALS['admin']['id'];
	$message['cdate'] = adesk_CURRENTDATETIME;
	$message = message_select_prepare($message, true, implode(',', $lists));
	$message['percentage'] = 100;
	$message['sourcesize'] = 0;
	$row['messages'] = array($message);
	$row['ratios'] = array(100);

	if ( $message['format'] != 'html' ) {
		if ( adesk_str_instr('%UNSUBSCRIBELINK%', $message['text']) or adesk_str_instr('/surround.php?nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid&funcml=unsub2', $message['text']) ) {
			$row['textunsub'] = 0;
		}
	}
	if ( $message['format'] != 'text' ) {
		if ( adesk_str_instr('%UNSUBSCRIBELINK%', $message['html']) or adesk_str_instr('/surround.php?nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid&funcml=unsub2', $message['html']) ) {
			$row['htmlunsub'] = 0;
		}
	}

	$row['messageslist'] = -1;

	// fetch all links for parsing
	$row['tlinks'] = array();
	foreach ( $row['links'] as $k => $v ) {
		$row['tlinks'][] = array(
			'id' => 0,
			'campaignid' => 0,
			'messageid' => -1,
			'link' => $v['link'],
			'name' => '',
		);
	}

	return $row;
}

function message_link_internal($link) {
	// check if it is an internal link
	$murl = adesk_site_plink();
	$internal = substr($link, 0, strlen($murl)) == $murl;
	if ( $internal ) {
		// internal links - old style
		if ( adesk_str_instr('/forward2.php?mi=', $link) or adesk_str_instr('/forward3.php?mi=', $link) or adesk_str_instr('/forward.php?mi=', $link) ) {
			$tmpVar1 = strpos($link, '?');
			if ( $tmpVar1 > 0 ) $link = substr($link, 0, $tmpVar1);
		}
		// internal links - current style
		if ( adesk_str_instr('/forward2.php?', $link) or adesk_str_instr('/forward3.php?', $link) or adesk_str_instr('/forward.php?', $link)/* or adesk_str_instr('/index.php?action=social&c=', $link)*/ ) {
			$tmpVar1 = strpos($link, '?');
			if ( $tmpVar1 > 0 ) $link = substr($link, 0, $tmpVar1);
		}
	}
	return $link;
}

function message_link_real($link) {
	$pos = strpos($link, "&l=");

	if ($pos !== false) {
		$tmp = explode("&l=", $link);
		if (count($tmp) == 2)
			return message_link_cleanup($tmp[1]);
	}

	return $link;
}
/*
function message_content_cleanup($row) {
	if ( $row['format'] == 'text' ) return $row;
	$row['html'] = message_link_resolve($row['html'], $row['htmlfetchurl']);
	return $row;
}
*/
function message_link_resolve($html, $baseurl = null) {
	$html = adesk_str_strip_malicious($html);
	if ( $baseurl ) {
		$lnkbase = $imgbase = $baseurl;
	} else {
		$lnkbase = adesk_site_plink() . '/';
		$imgbase = $lnkbase . 'images/' . $GLOBALS['admin']['username'] . '/';
	}
	// try to find an embedded base url
	preg_match('/<base(\starget="[^"]*")?\shref="([^"]*)"/i', $html, $matches);
	if ( !isset($matches[2]) ) {
		preg_match("/<base(\starget='[^']*')?\shref='([^']*)'/i", $html, $matches);
	}
	if ( isset($matches[2]) ) {
		$lnkbase = $imgbase = $matches[2];
	}
	// extract all single+double quoted hrefs/srcs
	preg_match_all('/\s(href|src)="([^"]*)"/i', $html, $matches1);
	preg_match_all("/\s(href|src)='([^']*)'/i", $html, $matches2);
	$matches = array();
	for ( $i = 0; $i < 3; $i++ ) $matches[$i] = array_merge($matches1[$i], $matches2[$i]);
	// loop through results
	foreach ( $matches[0] as $k => $v ) {
		// bypass personalization tags
		if ( preg_match('/^%.*%$/', $matches[2][$k]) ) continue;
		// bypass anchors
		if ( substr($matches[2][$k], 0, 1) == '#' ) continue;
		// choose a base to use (images use admin's folder)
		$base = ( $matches[1][$k] == 'src' ? $imgbase : $lnkbase );
		// try to replace spaces into %20
		$url = str_replace(' ', '%20', $matches[2][$k]);
		// replace the original url with resolved one
		$url = adesk_http_resolve_url($base, $url);
		if ( $url == $matches[2][$k] ) continue;
		$v = str_replace($matches[2][$k], $url, $v);
		// replace the old link with a resolved one
		$html = str_replace($matches[0][$k], $v, $html);
	}
	return $html;
}

function message_wrap_html($html, $lim = 0) {
	if ( !$lim ) $lim = 980;
	if ( /*preg_match('/\r?\n/', $html) or */strlen($html) < $lim ) return $html;
	//return wordwrap($html, $lim);
	// old code: break by block tags
	$tags = array('p', 'table', 'tr', 'td', 'div', 'fieldset');
	foreach ( $tags as $tag ) $html = preg_replace("/(<\/$tag>)/i", "\\1\n", $html);
	//return $html;
	return wordwrap($html, $lim);
}

function message_wrap_text($text, $lim = 0) {
	if ( !$lim ) $lim = 76;

	# No more wrapping due to change not to use any encoding on text content parts.
	return $text;
}

function message_preview($which, $filter, $offset, $limit) {
	$offset = (int)$offset;
	$limit  = (int)$limit;
	$rval   = array();
	$filter = adesk_sql_escape($filter);

	if (strlen($filter) < 2)
		$filter = "";

	$so = new adesk_Select;

	switch ($which) {
		case "message":
			$so->limit("$offset, $limit");
			$so->orderby("m.id DESC");

			if ($filter != "") {
				$so->limit("0, $limit");
				$so->push("AND (m.subject LIKE '%$filter%' OR m.html LIKE '%$filter%' OR m.text LIKE '%$filter%')");
			}

			// message permissions
			if ( !adesk_admin_ismain() ) {
				$admin = adesk_admin_get();
				if ( $admin['id'] > 1 ) {
					if ( !isset($so->permsAdded) ) {
						$so->permsAdded = 1;
						$liststr = implode("','", $admin["lists"]);
						$so->push("AND (SELECT COUNT(*) FROM #message_list subq WHERE subq.messageid = m.id AND subq.listid IN ('$liststr'))");
					}
				}
			}

			$rval = adesk_sql_select_array($so->query("
				SELECT
					m.id,
					m.subject,
					'msg' AS `add`,
					IF (m.preview_data IS NOT NULL AND m.preview_data != '', 1, 0) AS has_image
				FROM
					#message m
				WHERE
					[...]
			"));
			break;
		case "template":
			$so->limit("$offset, $limit");
			$so->orderby("t.subject");

			if ($filter != "") {
				$so->limit("0, $limit");
				$so->push("AND (t.name LIKE '%$filter%' OR t.content LIKE '%$filter%')");
			}

			// template permissions
			if ( !adesk_admin_ismain() ) {
				$admin = adesk_admin_get();
				if ( $admin['id'] > 1 ) {
					if ( !isset($so->permsAdded) ) {
						$so->permsAdded = 1;
						$admin['lists'][0] = 0;
						$liststr = implode("','", $admin["lists"]);
						$so->push("AND (SELECT COUNT(*) FROM #template_list l WHERE l.templateid = t.id AND l.listid IN ('$liststr')) > 0");
					}
				}
			}

			$rval = adesk_sql_select_array($so->query("
				SELECT
					t.id,
					t.name AS subject,
					'tpl' AS `add`,
					IF (t.preview_data IS NOT NULL AND t.preview_data != '', 1, 0) AS has_image
				FROM
					#template t
				WHERE
					[...]
			"));
			break;
		case "basic":
			$so->limit("$offset, $limit");
			$so->orderby("t.subject");
			$so->push("AND t.categoryid = 2");

			// template permissions
			if ( !adesk_admin_ismain() ) {
				$admin = adesk_admin_get();
				if ( $admin['id'] > 1 ) {
					if ( !isset($so->permsAdded) ) {
						$so->permsAdded = 1;
						$admin['lists'][0] = 0;
						$liststr = implode("','", $admin["lists"]);
						$so->push("AND (SELECT COUNT(*) FROM #template_list l WHERE l.templateid = t.id AND l.listid IN ('$liststr')) > 0");
					}
				}
			}

			if ($filter != "") {
				$so->limit("0, $limit");
				$so->push("AND (t.name LIKE '%$filter%' OR t.content LIKE '%$filter%')");
			}

			$rval = adesk_sql_select_array($so->query("
				SELECT
					t.id,
					t.name,
					'tpl' AS `add`,
					IF (t.preview_data IS NOT NULL AND t.preview_data != '', 1, 0) AS has_image
				FROM
					#template t
				WHERE
					[...]
			"));
			break;
		case "all":
		default:
			$which = 'all';
			adesk_sql_query("CREATE TEMPORARY TABLE IF NOT EXISTS awebdesk_temp_preview (id INT(10) NOT NULL DEFAULT '0', `add` VARCHAR(20) NOT NULL DEFAULT '', subject TEXT NULL DEFAULT NULL, has_image TINYINT NOT NULL DEFAULT '0')");
			adesk_sql_query("TRUNCATE TABLE awebdesk_temp_preview");

			$cond = "";
			if ($filter != "")
				$cond = "AND (m.subject LIKE '%$filter%' OR m.html LIKE '%$filter%' OR m.text LIKE '%$filter%')";

			// message permissions
			if ( !adesk_admin_ismain() ) {
				$admin = adesk_admin_get();
				if ( $admin['id'] > 1 ) {
					if ( !isset($so->permsAdded) ) {
						//$so->permsAdded = 1;
						$liststr = implode("','", $admin["lists"]);
						$cond .= " AND (SELECT COUNT(*) FROM #message_list subq WHERE subq.messageid = m.id AND subq.listid IN ('$liststr'))";
					}
				}
			}

			adesk_sql_query("INSERT INTO awebdesk_temp_preview (id, `add`, subject, has_image) SELECT m.id, 'msg' AS `add`, m.subject, IF (m.preview_data IS NOT NULL AND m.preview_data != '', 1, 0) AS has_image FROM #message m WHERE 1 $cond");

			$cond = "";
			if ($filter != "")
				$cond = "AND (name LIKE '%$filter%' OR content LIKE '%$filter%')";

			// template permissions
			if ( !adesk_admin_ismain() ) {
				$admin = adesk_admin_get();
				if ( $admin['id'] > 1 ) {
					if ( !isset($so->permsAdded) ) {
						//$so->permsAdded = 1;
						$admin['lists'][0] = 0;
						$liststr = implode("','", $admin["lists"]);
						$cond .= " AND (SELECT COUNT(*) FROM #template_list l WHERE l.templateid = t.id AND l.listid IN ('$liststr')) > 0";
					}
				}
			}

			adesk_sql_query("INSERT INTO awebdesk_temp_preview (id, `add`, subject, has_image) SELECT t.id, 'tpl' AS `add`, t.name AS subject, IF (t.preview_data IS NOT NULL AND t.preview_data != '', 1, 0) AS has_image FROM #template t WHERE 1 $cond");

			if ($filter == "")
				$so->limit("$offset, $limit");
			else
				$so->limit("0, $limit");

			$so->orderby("id DESC");

			$rval = adesk_sql_select_array($so->query("
				SELECT
					id,
					`add`,
					subject,
					has_image
				FROM
					awebdesk_temp_preview
				WHERE
					[...]
			"));
			break;
	}

	$rval['which'] = $which;
	$rval['filter'] = $filter;
	return $rval;
}

function message_copy_attach($oldmsgid, $newmsgid) {
	// copy message attachments
	$attachs = adesk_sql_select_list("SELECT id FROM #message_file WHERE messageid = '$oldmsgid'");
	if ( count($attachs) > 0 ) {
		foreach ( $attachs as $a ) {
			// copy attachment files
			adesk_sql_query("
				INSERT INTO
					#message_file
				(
					id, name, size, mime_type, messageid, tstamp
				)
					SELECT
						0 AS id,
						name,
						size,
						mime_type,
						$newmsgid AS messageid,
						tstamp
					FROM
						#message_file
					WHERE
						id = '$a'
			");
			$aid = (int)adesk_sql_insert_id();

			// copy attachment data
			adesk_sql_query("
				INSERT INTO
					#message_file_data
				(
					id, fileid, sequence, data
				)
					SELECT
						0 AS id,
						$aid AS fileid,
						sequence,
						data
					FROM
						#message_file_data
					WHERE
						fileid = '$a'
			");
		}
	}
}

?>
