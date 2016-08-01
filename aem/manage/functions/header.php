<?php

require_once awebdesk_classes("select.php");

function header_select_query(&$so) {
	if ( !adesk_admin_ismain() ) {
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
			h.*,
			COUNT(l.id) AS lists
		FROM
			#header h,
			#header_list l
		WHERE
			[...]
		AND
			h.id = l.headerid
		GROUP BY
			h.id
	");
}

function header_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND h.id = '$id'");

	$r = adesk_sql_select_row(header_select_query($so), array('tstamp'));
	if ( $r ) {
		$cond = '';
		if ( !adesk_admin_ismain() ) {
			$admin = adesk_admin_get();
			if ( $admin['id'] != 1 ) {
				//$admin['lists'][0] = 0;
				$cond = "AND listid IN ('" . implode("', '", $admin['lists']) . "')";
			}
		}
		$r['lists'] = implode('-', adesk_sql_select_list("SELECT listid FROM #header_list WHERE headerid = '$id' $cond"));
	}
	return $r;
}

function header_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND h.id IN ('$ids')");
	}
	return adesk_sql_select_array(header_select_query($so), array('tstamp'));
}

function header_select_array_paginator($id, $sort, $offset, $limit, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'header'");
		$so->push($conds);
	}

	$so->count();
	$total = adesk_sql_select_one(header_select_query($so));

	switch ($sort) {
		default:
		case "01":
			$so->orderby("h.title"); break;
		case "01D":
			$so->orderby("h.title DESC"); break;
		case "02":
			$so->orderby("h.name, h.value"); break;
		case "02D":
			$so->orderby("h.name DESC, h.value DESC"); break;
		case '03':
			$so->orderby("lists"); break;
		case '03D':
			$so->orderby("lists DESC"); break;
/*
		case "03":
			$so->orderby("h.name"); break;
		case "03D":
			$so->orderby("h.name DESC"); break;
		case "04":
			$so->orderby("h.value"); break;
		case "04D":
			$so->orderby("h.value DESC"); break;
*/
	}

	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = header_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function header_filter_post() {
	$whitelist = array("h.title", "h.name", "h.value");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "header",
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
			sectionid = 'header'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

function header_insert_post() {
	// find parents
	if ( isset($_POST['p']) and is_array($_POST['p']) and count($_POST['p']) > 0 ) {
		$lists = array_map('intval', $_POST['p']);
	} else {
		return adesk_ajax_api_result(false, _a("You did not select any lists."));
	}

	$admin = adesk_admin_get();
	$ary = array(
		'id' => 0,
		'userid' => (int)$admin['id'],
		'title' => (string)adesk_http_param('title'),
		'name' => (string)adesk_http_param('name'),
		'value' => (string)adesk_http_param('value'),
		'=tstamp' => 'NOW()'
	);

	if ( $ary['title'] == '' ) {
		return adesk_ajax_api_result(false, _a("Please name this Email Header."));
	}
	if ( $ary['name'] == '' ) {
		return adesk_ajax_api_result(false, _a("Email Header Name can not be left blank."));
	}
	if ( preg_match('/^(bcc|cc|date|from|return-path|sender|subject|to|x-mailer|x-mid|x-priority)$/i', $ary['name']) ) {
		return adesk_ajax_api_result(false, _a("You can not use a restricted Email Header Name."));
	}
	if ( $ary['value'] == '' ) {
		return adesk_ajax_api_result(false, _a("Email Header Value can not be left blank."));
	}

	$sql = adesk_sql_insert("#header", $ary);
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Email Header could not be added."));
	}

	$id = adesk_sql_insert_id();

	// list relations
	foreach ( $lists as $l ) {
		if ( $l > 0 ) adesk_sql_insert('#header_list', array('id' => 0, 'headerid' => $id, 'listid' => $l));
	}
	return adesk_ajax_api_added(_a("Email Header"));
}

function header_update_post() {
	if ( isset($_POST['p']) and is_array($_POST['p']) and count($_POST['p']) > 0 ) {
		$lists = array_map('intval', $_POST['p']);
	} else {
		return adesk_ajax_api_result(false, _a("You did not select any lists."));
	}

	$ary = array(
		'title' => (string)adesk_http_param('title'),
		'name' => (string)adesk_http_param('name'),
		'value' => (string)adesk_http_param('value'),
	);

	if ( $ary['title'] == '' ) {
		return adesk_ajax_api_result(false, _a("Please name this Email Header."));
	}
	if ( $ary['name'] == '' ) {
		return adesk_ajax_api_result(false, _a("Email Header Name can not be left blank."));
	}
	if ( preg_match('/^(bcc|cc|date|from|return-path|sender|subject|to|x-mailer|x-mid|x-priority)$/i', $ary['name']) ) {
		return adesk_ajax_api_result(false, _a("You can not use a restricted Email Header Name."));
	}
	if ( $ary['value'] == '' ) {
		return adesk_ajax_api_result(false, _a("Email Header Value can not be left blank."));
	}

	$id = intval($_POST["id"]);
	adesk_sql_update("#header", $ary, "id = '$id'");

	// list relations
	$cond = implode("', '", $lists);
	$admincond = '';
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		$admincond = "AND listid IN ('" . implode("', '", $admin['lists']) . "')";
	}
	adesk_sql_delete('#header_list', "headerid = '$id' AND listid NOT IN ('$cond') $admincond");
	foreach ( $lists as $l ) {
		if ( $l > 0 ) {
			if ( !adesk_sql_select_one('=COUNT(*)', '#header_list', "headerid = '$id' AND listid = '$l'") )
				adesk_sql_insert('#header_list', array('id' => 0, 'headerid' => $id, 'listid' => $l));
		}
	}

	return adesk_ajax_api_updated(_a("Email Header"));
}

function header_delete($id) {
	$id = intval($id);
	$admincond = '';
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		$admincond = "AND listid IN ('" . implode("', '", $admin['lists']) . "')";
	}
	adesk_sql_delete('#header_list', "headerid = '$id' $admincond");
	if ( adesk_sql_select_one('=COUNT(*)', '#header_list', "headerid = '$id'") == 0 ) {
		adesk_sql_delete('#header', "id = '$id'");
	}
	return adesk_ajax_api_deleted(_a("Email Header"));
}

function header_delete_multi($ids, $filter = 0) {
	if ( $ids == '_all' ) {
		$tmp = array();
		$so = new adesk_Select();
		$filter = intval($filter);
		if ($filter > 0) {
			$admin = adesk_admin_get();
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'header'");
			$so->push($conds);
		}
		$all = header_select_array($so);
		foreach ( $all as $v ) {
			$tmp[] = $v['id'];
		}
	} else {
		$tmp = array_map("intval", explode(",", $ids));
	}
	foreach ( $tmp as $id ) {
		$r = header_delete($id);
	}
	return $r;
}

?>
