<?php

require_once awebdesk_classes("select.php");

function bounce_code_select_query(&$so) {
	return $so->query("
		SELECT
			b.*
		FROM
			#bounce_code b
		WHERE
		[...]
	");
}

function bounce_code_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND b.id = '$id'");

	return adesk_sql_select_row(bounce_code_select_query($so));
}

function bounce_code_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND b.id IN ('$ids')");
	}
	return adesk_sql_select_array(bounce_code_select_query($so));
}

function bounce_code_select_array_paginator($id, $sort, $offset, $limit, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'bounce_code'");
		$so->push($conds);
	}

	$so->count();
	$total = (int)adesk_sql_select_one(bounce_code_select_query($so));

	switch ($sort) {
		default:
		case '01':
			$so->orderby("code"); break;
		case '01D':
			$so->orderby("code DESC"); break;
		case '02':
			$so->orderby("type"); break;
		case '02D':
			$so->orderby("type DESC"); break;
		case '03':
			$so->orderby("descript"); break;
		case '03D':
			$so->orderby("descript DESC"); break;
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = bounce_code_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function bounce_code_filter_post() {
	$whitelist = array("b.code", "b.match", "b.type", "b.descript");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "bounce_code",
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
			sectionid = 'bounce_code'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

function bounce_code_insert_post() {
	if ( !adesk_admin_ismain() ) {
		return adesk_ajax_api_result(false, _a("You do not have a permission to manage Bounce Codes."));
	}
	if ( isset($GLOBALS['_hosted_account']) ) {
		return adesk_ajax_api_result(false, _a("You do not have a permission to manage Bounce Codes."));
	}
	$ary = bounce_code_prepare_post();

	if ( $ary['code'] == '' ) {
		return adesk_ajax_api_result(false, _a("Bounce Code not provided."));
	}
	if ( $ary['match'] == '' ) {
		return adesk_ajax_api_result(false, _a("Matching String not provided."));
	}

	$sql = adesk_sql_insert("#bounce_code", $ary);
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Bounce Code could not be added."));
	}
	$id = adesk_sql_insert_id();

	return adesk_ajax_api_added(_a("Bounce Code"));
}

function bounce_code_update_post() {
	if ( !adesk_admin_ismain() ) {
		return adesk_ajax_api_result(false, _a("You do not have a permission to manage Bounce Codes."));
	}
	if ( isset($GLOBALS['_hosted_account']) ) {
		return adesk_ajax_api_result(false, _a("You do not have a permission to manage Bounce Codes."));
	}
	$ary = bounce_code_prepare_post();

	if ( $ary['code'] == '' ) {
		return adesk_ajax_api_result(false, _a("Bounce Code not provided."));
	}
	if ( $ary['match'] == '' ) {
		return adesk_ajax_api_result(false, _a("Matching String not provided."));
	}

	$id = intval($_POST["id"]);
	$sql = adesk_sql_update("#bounce_code", $ary, "id = '$id'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Bounce Code could not be updated."));
	}

	return adesk_ajax_api_updated(_a("Bounce Code"));
}

function bounce_code_delete($id) {
	if ( !adesk_admin_ismain() ) {
		return adesk_ajax_api_result(false, _a("You do not have a permission to manage Bounce Codes."));
	}
	if ( isset($GLOBALS['_hosted_account']) ) {
		return adesk_ajax_api_result(false, _a("You do not have a permission to manage Bounce Codes."));
	}
	$id = intval($id);
	adesk_sql_query("DELETE FROM #bounce_code WHERE id = '$id'");
	return adesk_ajax_api_deleted(_a("Bounce Code"));
}

function bounce_code_delete_multi($ids, $filter = 0) {
	if ( !adesk_admin_ismain() ) {
		return adesk_ajax_api_result(false, _a("You do not have a permission to manage Bounce Codes."));
	}
	if ( isset($GLOBALS['_hosted_account']) ) {
		return adesk_ajax_api_result(false, _a("You do not have a permission to manage Bounce Codes."));
	}
	if ( $ids == '_all' ) {
		$tmp = array();
		$so = new adesk_Select();
		$filter = intval($filter);
		if ($filter > 0) {
			$admin = adesk_admin_get();
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'bounce_code'");
			$so->push($conds);
		}
		$all = bounce_code_select_array($so);
		foreach ( $all as $v ) {
			$tmp[] = $v['id'];
		}
	} else {
		$tmp = array_map("intval", explode(",", $ids));
	}
	foreach ( $tmp as $id ) {
		$r = bounce_code_delete($id);
	}
	return $r;
}

function bounce_code_prepare_post() {
	$r = array(
		'code' => trim((string)adesk_http_param('code')),
		'match' => (string)adesk_http_param('match'),
		'type' => (string)adesk_http_param('type'),
		'descript' => trim((string)adesk_http_param('descript'))
	);
	if ( $r['type'] != 'hard' ) $r['type'] = 'soft';
	return $r;
}

?>
