<?php

require_once awebdesk_classes("select.php");

function optinoptout_select_query(&$so) {
	$admin = adesk_admin_get();
	 $uid = $admin['id'];
	if ( !adesk_admin_ismain() ) {
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


				
				//$liststr = implode("','", $admin["lists"]);
				$so->push("
					AND
					(
						(SELECT COUNT(*) FROM #optinoptout_list l WHERE l.emailconfid = o.id AND l.listid IN ('$liststr') > 0)
					OR
						o.id = 1
					)
				");
			}
		}
	}
	if ( isset($admin['optinconfirm']) && $admin['optinconfirm'] ) {
		$cond = "AND o.optin_confirm = 1";
		if ( !in_array($cond, $so->conds) ) {
			$so->push($cond);
		}
	}
	return $so->query("
		SELECT
			o.*,
			o.name AS optname,
			(SELECT COUNT(*) FROM #optinoptout_list WHERE emailconfid = o.id) AS lists
		FROM
			#optinoptout o
		WHERE
			[...]
		GROUP BY
			o.id
	");
}

function optinoptout_select_prepare($row, $full = false) {
	$row['optin_files'] = adesk_file_upload_list('#optinoptout_file', 'optinoptoutid', "$row[id] AND `type` = 'in'");
	$row['optout_files'] = adesk_file_upload_list('#optinoptout_file', 'optinoptoutid', "$row[id] AND `type` = 'out'");
	$cond = '';
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		if ( $admin['id'] != 1 ) {
			//$admin['lists'][0] = 0;
			$cond = "AND listid IN ('" . implode("', '", $admin['lists']) . "')";
		}
	}
	$lists = adesk_sql_select_list("SELECT listid FROM #optinoptout_list WHERE `emailconfid` = '$row[id]' $cond");
	$row['listslist'] = implode('-', $lists);
	$so = new adesk_Select();
	$listslist = implode(',', $lists);
	$so->push("AND l.listid IN ('$listslist')");
	$row['fields'] = list_get_fields($lists, false);
	$row['personalizations'] = list_personalizations($so);
	return $row;
}

function optinoptout_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND o.id = '$id'");

	$r = adesk_sql_select_row(optinoptout_select_query($so));
	$admin = adesk_admin_get();

	$lists = implode("', '", $admin['lists']);

	$r["lists"] = implode(",", adesk_sql_select_list("SELECT listid FROM #optinoptout_list WHERE emailconfid = '$id' AND listid IN ('$lists')"));

	return optinoptout_select_prepare($r, true);
}

function optinoptout_select_row_ajax($id) {
	$id = intval($id);
	if ( !adesk_admin_ismain() and $id == 1 ) return false;
	return optinoptout_select_row($id);
}

function optinoptout_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND o.id IN ('$ids')");
	}
	$r = adesk_sql_select_array(optinoptout_select_query($so));
	foreach ( $r as $k => $v ) {
		$r[$k] = optinoptout_select_prepare($v, false);
	}
	return $r;
}

function optinoptout_select_array_paginator($id, $sort, $offset, $limit, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'optinoptout'");
		$so->push($conds);
	}

	if (!adesk_admin_ismain())
		$so->push("AND o.id != 1");

	$so->count();
	$total = (int)adesk_sql_select_one(optinoptout_select_query($so));

	switch ($sort) {
		default:
		case "01":
			$so->orderby("name"); break;
		case "01D":
			$so->orderby("name DESC"); break;
		case "02":
			$so->orderby("optin_confirm, optin_subject"); break;
		case "02D":
			$so->orderby("optin_confirm, optin_subject DESC"); break;
		case "03":
			$so->orderby("optout_confirm, optout_subject"); break;
		case "03D":
			$so->orderby("optout_confirm, optout_subject DESC"); break;
		case "04":
			$so->orderby("lists"); break;
		case "04D":
			$so->orderby("lists DESC"); break;
	}

	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = optinoptout_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function optinoptout_filter_post() {
	$whitelist = array(
		"name",
		"optin_from_name",
		"optin_from_email",
		"optin_subject",
		"optin_text",
		"optin_html",
		"optout_from_name",
		"optout_from_email",
		"optout_subject",
		"optout_text",
		"optout_html",
	);

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "optinoptout",
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
				$ary['conds'] .= "AND (SELECT COUNT(*) FROM #optinoptout_list l WHERE l.emailconfid = o.id AND l.listid IN ('$ids')) > 0";
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
				$ary['conds'] .= "AND (SELECT COUNT(*) FROM #optinoptout_list l WHERE l.emailconfid = o.id AND l.listid = '$listid') > 0";
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
			sectionid = 'optinoptout'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

function optinoptout_insert_post() {
	$admin = adesk_admin_get();
	$site = adesk_admin_get();
	$ary = optinoptout_post_prepare();

	if ( $ary['name'] == '' ) {
		return adesk_ajax_api_result(false, _a("Email Confirmation Set can not be left empty. Please name this set."));
	}
	if ( $admin['optinconfirm'] and !$ary['optin_confirm'] ) {
		return adesk_ajax_api_result(false, _a("Email Confirmation Set needs to have Opt-In set up."));
	}
	if ( $ary['optin_confirm'] ) {
		// check for from email
		if ( !adesk_str_is_email($ary['optin_from_email']) ) {
			return adesk_ajax_api_result(false, _a("Opt-in Email Address is not valid."));
		}
		// check for subject
		if ( $ary['optin_subject'] == '' ) {
			return adesk_ajax_api_result(false, _a("Opt-in Email Subject can not be left empty."));
		}
		// check for confirmation links
		if ( $ary['optin_format'] != 'html' and !adesk_str_instr('%CONFIRMLINK%', $ary['optin_text']) ) {
			return adesk_ajax_api_result(false, _a("Opt-in Text version does not contain a confirmation link."));
		}
		if ( $ary['optin_format'] != 'text' and !adesk_str_instr('%CONFIRMLINK%', $ary['optin_html']) ) {
			return adesk_ajax_api_result(false, _a("Opt-in HTML version does not contain a confirmation link."));
		}
	}
	if ( $ary['optout_confirm'] ) {
		// check for from email
		if ( !adesk_str_is_email($ary['optout_from_email']) ) {
			return adesk_ajax_api_result(false, _a("Opt-out Email Address is not valid."));
		}
		// check for subject
		if ( $ary['optout_subject'] == '' ) {
			return adesk_ajax_api_result(false, _a("Opt-out Email Subject can not be left empty."));
		}
		// check for confirmation links
		if ( $ary['optout_format'] != 'html' and !adesk_str_instr('%CONFIRMLINK%', $ary['optout_text']) ) {
			return adesk_ajax_api_result(false, _a("Opt-out Text version does not contain a confirmation link."));
		}
		if ( $ary['optout_format'] != 'text' and !adesk_str_instr('%CONFIRMLINK%', $ary['optout_html']) ) {
			return adesk_ajax_api_result(false, _a("Opt-out HTML version does not contain a confirmation link."));
		}
	}

	$sql = adesk_sql_insert("#optinoptout", $ary);
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Email Confirmation Set could not be added."));
	}
	$id = adesk_sql_insert_id();

	$lists = adesk_http_param("lists");
	// Save the relations here.
	foreach ($lists as $listid) {
		$ins = array(
			"emailconfid" => $id,
			"listid"      => $listid,
		);
		adesk_sql_insert("#optinoptout_list", $ins);
	}
	// remove all other optin sets from these lists
	$listsstr = implode("', '", $lists);
	adesk_sql_delete("#optinoptout_list", "emailconfid = '$id' AND listid NOT IN ('$listsstr')");
	// update list
	adesk_sql_update_one("#list", "optinoptout", $id, "id IN ('$listsstr')");
	adesk_sql_update_one("#list", "optinoptout", 1, "optinoptout = '$id' AND id NOT IN ('$listsstr')");

	// save optin file attachments
	$files = adesk_http_param('optinattach');
	if ( is_array($files) ) {
		$list = implode("', '", $files);
		// save new
		adesk_sql_query("UPDATE #optinoptout_file SET `optinoptoutid` = '$id', `type` = 'in' WHERE `id` IN ('$list')");
	}

	// save optout file attachments
	$files = adesk_http_param('optoutattach');
	if ( is_array($files) ) {
		$list = implode("', '", $files);
		// save new
		adesk_sql_query("UPDATE #optinoptout_file SET `optinoptoutid` = '$id', `type` = 'out' WHERE `id` IN ('$list')");
	}

	// delete all old attachments (if submitted before handler could remove them)
	$sql = adesk_sql_query("SELECT `id` FROM #optinoptout_file WHERE `optinoptoutid` = '0'");
	while ( $row = adesk_sql_fetch_row($sql) ) {
		if ( $site['message_attachments_location'] == 'db' ) {
			adesk_file_upload_remove('#optinoptout_file', '#optinoptout_file_data', $row[0]);
		} else {
			adesk_file_upload_remove('#optinoptout_file', adesk_base('files'), $row[0]);
		}
	}

	return adesk_ajax_api_added(_a("Email Confirmation Set"), array('id' => $id, 'name' => $ary['name']));
}

function optinoptout_update_post() {
	$admin = adesk_admin_get();
	$site = adesk_admin_get();
	$ary = optinoptout_post_prepare();

	if ( $ary['name'] == '' ) {
		return adesk_ajax_api_result(false, _a("Email Confirmation Set can not be left empty. Please name this set."));
	}
	if ( $admin['optinconfirm'] and !$ary['optin_confirm'] ) {
		return adesk_ajax_api_result(false, _a("Email Confirmation Set needs to have Opt-In set up."));
	}
	if ( $ary['optin_confirm'] ) {
		// check for from email
		if ( !adesk_str_is_email($ary['optin_from_email']) ) {
			return adesk_ajax_api_result(false, _a("Opt-in Email Address is not valid."));
		}
		// check for subject
		if ( $ary['optin_subject'] == '' ) {
			return adesk_ajax_api_result(false, _a("Opt-in Email Subject can not be left empty."));
		}
		// check for confirmation links
		if ( $ary['optin_format'] != 'html' and !adesk_str_instr('%CONFIRMLINK%', $ary['optin_text']) ) {
			return adesk_ajax_api_result(false, _a("Opt-in Text version does not contain a confirmation link."));
		}
		if ( $ary['optin_format'] != 'text' and !adesk_str_instr('%CONFIRMLINK%', $ary['optin_html']) ) {
			return adesk_ajax_api_result(false, _a("Opt-in HTML version does not contain a confirmation link."));
		}
	}
	if ( $ary['optout_confirm'] ) {
		// check for from email
		if ( !adesk_str_is_email($ary['optout_from_email']) ) {
			return adesk_ajax_api_result(false, _a("Opt-out Email Address is not valid."));
		}
		// check for subject
		if ( $ary['optout_subject'] == '' ) {
			return adesk_ajax_api_result(false, _a("Opt-out Email Subject can not be left empty."));
		}
		// check for confirmation links
		if ( $ary['optout_format'] != 'html' and !adesk_str_instr('%CONFIRMLINK%', $ary['optout_text']) ) {
			return adesk_ajax_api_result(false, _a("Opt-out Text version does not contain a confirmation link."));
		}
		if ( $ary['optout_format'] != 'text' and !adesk_str_instr('%CONFIRMLINK%', $ary['optout_html']) ) {
			return adesk_ajax_api_result(false, _a("Opt-out HTML version does not contain a confirmation link."));
		}
	}

	$id = intval($_POST["id"]);
	$sql = adesk_sql_update("#optinoptout", $ary, "id = '$id'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Email Confirmation Set could not be updated."));
	}

	$lists = adesk_http_param("lists");
	// Save the relations here.
	adesk_sql_query("DELETE FROM #optinoptout_list WHERE emailconfid = '$id'");
	foreach ($lists as $listid) {
		$ins = array(
			"emailconfid" => $id,
			"listid"      => $listid,
		);
		adesk_sql_insert("#optinoptout_list", $ins);
	}
	// remove all other optin sets from these lists
	$listsstr = implode("', '", $lists);
	adesk_sql_delete("#optinoptout_list", "emailconfid = '$id' AND listid NOT IN ('$listsstr')");
	// update list
	adesk_sql_update_one("#list", "optinoptout", $id, "id IN ('$listsstr')");
	adesk_sql_update_one("#list", "optinoptout", 1, "optinoptout = '$id' AND id NOT IN ('$listsstr')");

	// save optin file attachments
	$files = adesk_http_param('optinattach');
	if ( is_array($files) ) {
		$list = implode("', '", $files);
		// save new
		adesk_sql_query("UPDATE #optinoptout_file SET `optinoptoutid` = '$id', `type` = 'in' WHERE `id` IN ('$list')");
		// delete all old attachments (if submitted before handler could remove them)
		$sql = adesk_sql_query("SELECT `id` FROM #optinoptout_file WHERE `optinoptoutid` = '$id' AND `type` = 'in' AND `id` NOT IN ('$list')");
		while ( $row = adesk_sql_fetch_row($sql) ) {
			if ( $site['message_attachments_location'] == 'db' ) {
				adesk_file_upload_remove('#optinoptout_file', '#optinoptout_file_data', $row[0]);
			} else {
				adesk_file_upload_remove('#optinoptout_file', adesk_base('files'), $row[0]);
			}
		}
	} else {
		// delete all old attachments (if submitted before handler could remove them)
		$sql = adesk_sql_query("SELECT `id` FROM #optinoptout_file WHERE `optinoptoutid` = '$id' AND `type` = 'in'");
		while ( $row = adesk_sql_fetch_row($sql) ) {
			if ( $site['message_attachments_location'] == 'db' ) {
				adesk_file_upload_remove('#optinoptout_file', '#optinoptout_file_data', $row[0]);
			} else {
				adesk_file_upload_remove('#optinoptout_file', adesk_base('files'), $row[0]);
			}
		}
	}

	// save optout file attachments
	$files = adesk_http_param('optoutattach');
	if ( is_array($files) ) {
		$list = implode("', '", $files);
		// save new
		adesk_sql_query("UPDATE #optinoptout_file SET `optinoptoutid` = '$id', `type` = 'out' WHERE `id` IN ('$list')");
		// delete all old attachments (if submitted before handler could remove them)
		$sql = adesk_sql_query("SELECT `id` FROM #optinoptout_file WHERE `optinoptoutid` = '$id' AND `type` = 'out' AND `id` NOT IN ('$list')");
		while ( $row = adesk_sql_fetch_row($sql) ) {
			if ( $site['message_attachments_location'] == 'db' ) {
				adesk_file_upload_remove('#optinoptout_file', '#optinoptout_file_data', $row[0]);
			} else {
				adesk_file_upload_remove('#optinoptout_file', adesk_base('files'), $row[0]);
			}
		}
	} else {
		// delete all old attachments (if submitted before handler could remove them)
		$sql = adesk_sql_query("SELECT `id` FROM #optinoptout_file WHERE `optinoptoutid` = '$id' AND `type` = 'out'");
		while ( $row = adesk_sql_fetch_row($sql) ) {
			if ( $site['message_attachments_location'] == 'db' ) {
				adesk_file_upload_remove('#optinoptout_file', '#optinoptout_file_data', $row[0]);
			} else {
				adesk_file_upload_remove('#optinoptout_file', adesk_base('files'), $row[0]);
			}
		}
	}

	return adesk_ajax_api_updated(_a("Email Confirmation Set"));
}

function optinoptout_delete($id) {
	$id = intval($id);
	if ( $id < 2 ) {
		return adesk_ajax_api_result(false, _a("Email Confirmation Set could not be deleted."));
	}
	adesk_sql_query("DELETE FROM #optinoptout WHERE id = '$id'");
	optinoptout_delete_relations($id);
	return adesk_ajax_api_deleted(_a("Email Confirmation Set"));
}

function optinoptout_delete_multi($ids, $filter = 0) {
	if ( $ids == '_all' ) $ids = null;
	$so = new adesk_Select();
	$so->slist = array('o.id');
	$so->remove = false;
	$filter = intval($filter);
	if ($filter > 0) {
		$admin = adesk_admin_get();
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'optinoptout'");
		$so->push($conds);
	}
	$tmp = optinoptout_select_array($so, $ids);
	$idarr = array();
	foreach ( $tmp as $v ) {
		if ( $v != 1 ) $idarr[] = $v['id'];
	}
	$ids = implode("','", $idarr);
	adesk_sql_query("DELETE FROM #optinoptout WHERE id IN ('$ids')");
	optinoptout_delete_relations($ids);
	return adesk_ajax_api_deleted(_a("Email Confirmation Set"));
}

function optinoptout_delete_relations($ids) {
	$admincond = 1;
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		if ( isset($admin['lists'][1]) ) unset($admin['lists'][1]);
		$admincond = "id IN ('" . implode("', '", $admin['lists']) . "')";
	}
	if ($ids === null) {		# delete all
		adesk_sql_update_one('#list', 'optinoptout', 1, $admincond);
		adesk_sql_update_one('#form', 'optinoptout', 1);
	} else {
		adesk_sql_update_one('#list', 'optinoptout', 1, "optinoptout IN ('$ids') AND $admincond");
		adesk_sql_update_one('#form', 'optinoptout', 1, "optinoptout IN ('$ids')");
	}
}

function optinoptout_post_prepare() {
	// optin optout
	$ary = array(
		'name' => (string)adesk_http_param('optname'),
		'optin_confirm' => (int)adesk_http_param('optin_confirm'),
		'optout_confirm' => (int)adesk_http_param('optout_confirm'),
	);
	$types = array('text', 'html', 'mime');
	if ( $ary['optin_confirm'] == 1 ) {
		$ary['optin_format'] = (string)adesk_http_param('optin_format');
		if ( !in_array($ary['optin_format'], $types) ) $ary['optin_format'] = 'text';
		$ary['optin_from_name'] = (string)adesk_http_param('optin_from_name');
		$ary['optin_from_email'] = (string)adesk_http_param('optin_from_email');
		$ary['optin_subject'] = (string)adesk_http_param('optin_subject');
		$ary['optin_text'] = (string)adesk_http_param('optin_text');
		$ary['optin_html'] = adesk_str_fixtinymce((string)adesk_http_param('optin_html'));
	}
	if ( $ary['optout_confirm'] == 1 ) {
		$ary['optout_format'] = (string)adesk_http_param('optout_format');
		if ( !in_array($ary['optout_format'], $types) ) $ary['optout_format'] = 'text';
		$ary['optout_from_name'] = (string)adesk_http_param('optout_from_name');
		$ary['optout_from_email'] = (string)adesk_http_param('optout_from_email');
		$ary['optout_subject'] = (string)adesk_http_param('optout_subject');
		$ary['optout_text'] = (string)adesk_http_param('optout_text');
		$ary['optout_html'] = adesk_str_fixtinymce((string)adesk_http_param('optout_html'));
	}
	return $ary;
}

function optinoptout_attachments($files) {
	$r = array();
	foreach ( $files as $f ) {
		if ( !isset($r[$f['name']]) ) {
			$f['data'] = optinoptout_attachment($f['id']);
			$r[$f['name']] = $f;
		}
	}
	return $r;
}

function optinoptout_attachment($id) {
	$site = adesk_site_get();
	if ( $site['message_attachments_location'] == 'fs' ) {
		$tabled = adesk_base('files/optinoptout-');
	} else {
		$tabled = '#optinoptout_file_data';
	}
	return adesk_file_upload_get_data($tabled, $id);
}

?>
