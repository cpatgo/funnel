<?php

require_once awebdesk_classes("select.php");

function exclusion_select_query(&$so) {
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		$uid = $admin['id'];
		if ( $admin['id'] != 1 ) {
			if ( !isset($so->permsAdded) ) {
				$so->permsAdded = 1;
				//sandeep
				$lists = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");
				$liststr = implode("','", $lists );
				$so->push("AND (SELECT COUNT(*) FROM #exclusion_list l WHERE l.listid IN ('$liststr') AND l.exclusionid = e.id) > 0");
			}
		}
	}

	return $so->query("
		SELECT
			e.email AS pattern,
			e.*,
			(SELECT COUNT(*) FROM #exclusion_list l WHERE e.id = l.exclusionid) AS lists
		FROM
			#exclusion e
		WHERE
			[...]
	");
}

function exclusion_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND e.id = '$id'");

	$rval = adesk_sql_select_row(exclusion_select_query($so));
	if ($rval) {
		$admin      = adesk_admin_get();
		$liststr    = implode("','", $admin["lists"]);
		$rval["lists"] = implode(",", adesk_sql_select_list("SELECT listid FROM #exclusion_list WHERE exclusionid = '$rval[id]' AND listid IN ('$liststr', '0')"));

		if ($rval["lists"] == "0")
			$rval["matchall"] = 1;
		else
			$rval["matchall"] = 0;
	}

	return $rval;
}

function exclusion_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND e.id IN ('$ids')");
	}
	return adesk_sql_select_array(exclusion_select_query($so));
}

function exclusion_select_array_paginator($id, $sort, $offset, $limit, $filter, $fields = null) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'exclusion'");
		$so->push($conds);
	}

	if (!adesk_admin_ismain())
		$so->push("AND (SELECT COUNT(*) FROM #exclusion_list l WHERE l.listid = 0 AND l.exclusionid = e.id) = 0");

	$so->count();
	$total = (int)adesk_sql_select_one(exclusion_select_query($so));

	switch ($sort) {
		default:
		case '01':
			$so->orderby("pattern"); break;
		case '01D':
			$so->orderby("pattern DESC"); break;
		case '02':
			$so->orderby("lists"); break;
		case '02D':
			$so->orderby("lists DESC"); break;
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");

	if ( $fields ) {
		$so->remove = false;
		$so->slist = array();
		if ( !is_array($fields) or ( !in_array('id', $fields) and !in_array('pattern', $fields) ) ) {
			$fields = array('id', 'pattern');
		}
		$fixPattern = true;
		foreach ( $fields as $k => $v ) {
			if ( $v == 'id' ) {
				$so->slist[] = "e.id AS id";
			} elseif ( $v == 'pattern' ) {
				$fixPattern = false;
				$so->slist[] = "e.email AS pattern";
			} else {
				$so->slist[] = "$v";
			}
		}
		if ( $fixPattern ) {
			$found = false;
			foreach ( $so->orders as $oi => $order ) {
				if ( adesk_str_instr('pattern', $order) ) unset($so->orders[$oi]);
			}
			if ( !count($so->orders) ) $so->orderby('e.email');
		}
		$query = exclusion_select_query($so);
		return adesk_sql_query($query);
	}

	$rows = exclusion_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function exclusion_filter_post() {
	$whitelist = array("email");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "exclusion",
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
				$ary['conds'] .= "AND (SELECT COUNT(*) FROM #exclusion_list l WHERE l.listid IN ('$ids') AND l.exclusionid = e.id) > 0 ";
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
				$ary['conds'] .= "AND (SELECT COUNT(*) FROM #exclusion_list l WHERE l.listid = '$listid' AND l.exclusionid = e.id) > 0 ";
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
			sectionid = 'exclusion'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

function exclusion_lists($lists) {
	# If one of these list ids, in $lists, is zero, then this exclusion should be global and all
	# we want is the number zero.

	if (adesk_admin_ismain()) {
		if (in_array(0, $lists))
			$lists = array(0);
	} else {
		$lists = array_diff($lists, array(0));
	}

	return $lists;
}

function exclusion_insert_post() {
	$target = adesk_http_param("target");

	if ($target == "several" && isset($_POST['p'])) {
		if (!is_array($_POST['p']))
			$_POST['p'] = array($_POST['p']);
		$lists = array_map('intval', $_POST['p']);
	} elseif ($target == "all") {
		# The zero list is an indicator that we want to match all lists this user's group has
		# access to.

		$lists = array(0);
	} else {
		return adesk_ajax_api_result(false, _a("You did not select any lists."));
	}

	$ins = array(
		"email"     => adesk_http_param("address"),
		"matchtype" => adesk_http_param("matchtype"),
	);

	if ( $ins['email'] == '' ) {
		return adesk_ajax_api_result(false, _a("Pattern can not be left empty, it would match everything."));
	}

	// check for duplicate rows
	$email_esc = adesk_sql_escape($ins['email']);
	$exists = adesk_sql_select_one("SELECT COUNT(*) FROM #exclusion e WHERE e.email = '$email_esc'");

	if ($exists == 0) {
		// if row doesn't exist in #exclusion, add a new one
		$sql = adesk_sql_insert("#exclusion", $ins);

		if ( !$sql ) {
			return adesk_ajax_api_result(false, _a("Exclusion Pattern could not be added."));
		}

		$id = adesk_sql_insert_id();
	} else {
		// If row already exists in #exclusion, grab the id
		$id = adesk_sql_select_one("SELECT e.id FROM #exclusion e WHERE e.email = '$email_esc'");
	}

	foreach ( $lists as $l ) {
		// check for duplicates
		$exists = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #exclusion e, #exclusion_list el WHERE e.email = '$email_esc' AND e.id = el.exclusionid AND el.listid IN ('$l', 0)");

		if ($exists > 0)
			continue; // if they already exist, skip them

		$ins = array(
			"exclusionid" => $id,
			"listid"      => $l,
		);

		adesk_sql_insert('#exclusion_list', $ins);
	}

	return adesk_ajax_api_added(_a("Exclusion Pattern"));
}

function exclusion_update_post() {
	$target = adesk_http_param("target");

	if ($target == "several" && isset($_POST['p'])) {
		if (!is_array($_POST['p']))
			$_POST['p'] = array($_POST['p']);
		$lists = array_map('intval', $_POST['p']);
	} elseif ($target == "all") {
		# The zero list is an indicator that we want to match all lists this user's group has
		# access to.

		$lists = array(0);
	} else {
		return adesk_ajax_api_result(false, _a("You did not select any lists."));
	}

	$id     = intval($_POST["id"]);

	// list relations
	$cond = implode("', '", $lists);
	$admincond = '';
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		$admincond = "AND listid IN ('" . implode("', '", $admin['lists']) . "', '0')";
	}
	adesk_sql_delete('#exclusion_list', "exclusionid = '$id' AND listid NOT IN ('$cond') $admincond");
	foreach ( $lists as $l ) {
		if ( !adesk_sql_select_one('=COUNT(*)', '#exclusion_list', "exclusionid = '$id' AND listid = '$l'") )
			adesk_sql_insert('#exclusion_list', array('id' => 0, 'exclusionid' => $id, 'listid' => $l));
	}

	return adesk_ajax_api_updated(_a("Exclusion Pattern"));
}

function exclusion_delete($id) {
	$id = intval($id);
	exclusion_delete_relations(array($id));

	if (!exclusion_inuse($id))
		adesk_sql_query("DELETE FROM #exclusion WHERE id = '$id'");

	return adesk_ajax_api_deleted(_a("Exclusion Pattern"));
}

function exclusion_inuse($id) {
	$id = (int)$id;
	$c  = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #exclusion_list WHERE exclusionid = '$id'");

	return $c > 0;
}

function exclusion_delete_multi($ids, $filter = 0) {
	if ( $ids == '_all' ) $ids = null;
	$so = new adesk_Select();
	$so->slist = array('e.id');
	$so->remove = false;
	$filter = intval($filter);
	if ($filter > 0) {
		$admin = adesk_admin_get();
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'exclusion'");
		$so->push($conds);
	}
	$tmp = exclusion_select_array($so, $ids);
	$idarr = array();
	foreach ( $tmp as $v ) {
		$idarr[] = $v['id'];
	}
	$ids = implode("','", $idarr);
	exclusion_delete_relations($ids);

	foreach ($idarr as $eid) {
		if (!exclusion_inuse($eid))
			adesk_sql_query("DELETE FROM #exclusion WHERE id = '$eid'");
	}

	return adesk_ajax_api_deleted(_a("Exclusion Pattern"));
}

function exclusion_delete_relations($ids) {
	$admincond = 1;
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		$admincond = "listid IN ('" . implode("', '", $admin['lists']) . "', '0')";
	}
	if ($ids === null) {		# delete all
		adesk_sql_delete('#exclusion_list', $admincond);
	} else {
		if (is_array($ids))
			$ids = implode("','", array_map("intval", $ids));
		adesk_sql_delete('#exclusion_list', $q = "`exclusionid` IN ('$ids') AND $admincond");
	}
}

function exclusion_iswildcard($str) {
	# If this is a wildcard, in the fashion we stored it in our database (e.g. *=%, ?=_), then
	# return true.

	return strpos($str, "*") !== false || strpos($str, "?") !== false;
}

function exclusion_match($email, $lists = array()) {
	if (exclusion_match_hosted($email))
		return true;

	$so       = new adesk_Select();
	$emailEsc = adesk_sql_escape($email);

	if (!is_array($lists))
		$lists = array_map('intval', explode(',', str_replace('-', ',', (string)$lists)));

	$ids = adesk_sql_select_list($q = "
		SELECT
			e.id
		FROM
			#exclusion e
		WHERE
		(
			(matchtype = 'exact' AND email = '$emailEsc')
		OR
			(matchtype = 'begin' AND '$emailEsc' LIKE CONCAT(email, '%'))
		OR
			(matchtype = 'end' AND '$emailEsc' LIKE CONCAT('%', email))
		)
	");

	foreach ($ids as $eid) {
		if (exclusion_match_lists($eid, $lists))
			return true;
	}

	return false;
}

function exclusion_match_hosted($email) {
	# Always return false if we're not in a hosted environment.
	if (!isset($GLOBALS["_hosted_account"]))
		return false;

	$emailEsc = awebdesk_hosted_escape($email);
	$rs       = awebdesk_hosted_query("SELECT COUNT(*) FROM `_account`.`awebdesk_exclusion` WHERE email = '$emailEsc'");

	if ($rs && ($row = mysql_fetch_row($rs))) {
		$c = $row[0];

		return $c > 0;
	}

	return false;
}

function exclusion_match_lists($id, $lists) {
	# See if there's an exact list match for this exclusion; if so, return true.
	$liststr = implode("','", $lists);
	$c       = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #exclusion_list WHERE exclusionid = '$id' AND listid IN ('$liststr', '0')");

	if ($c > 0)
		return true;

	return false;
}

function exclusion_haswildcards($listids) {
	# If any of the lists in the array $listids has a wildcard pattern, return how many are
	# present.  Otherwise, return 0.
	$listids[] = 0;
	$liststr   = implode("','", $listids);

	return (int)adesk_sql_select_one("
		SELECT
			COUNT(*)
		FROM
			#exclusion e,
			#exclusion_list l
		WHERE
			l.exclusionid = e.id
		AND
			l.listid IN ('$liststr')
		AND
			e.matchtype != 'exact'
	");
}

function exclusion_export($fields, $sort, $offset, $limit, $filter) {
	if ( !is_array($fields) or ( !in_array('id', $fields) and !in_array('pattern', $fields) ) ) {
		$fields = array('id', 'pattern');
	}
	return array(
		'fields' => $fields,
		//'customfields' => array(),
		'rs' => exclusion_select_array_paginator(0, $sort, $offset, $limit, $filter, $fields),
	);
}

function exclusion_add($email, $listid) {
	$emailEsc = adesk_sql_escape($email);
	$listid   = (int)$listid;
	$id       = (int)adesk_sql_select_one("SELECT id FROM #exclusion WHERE email = '$emailEsc' AND matchtype = 'exact'");

	if ($id == 0) {
		$ins = array(
			"email" => $email,
			"wildcard" => 0,
			"hidden" => 0,
			"matchtype" => "exact",
		);

		adesk_sql_insert("#exclusion", $ins);
		$id = (int)adesk_sql_insert_id();

		$ins = array(
			"exclusionid" => $id,
			"listid" => $listid,
			"sync" => 0,
		);

		adesk_sql_insert("#exclusion_list", $ins);
	}

	$c = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #exclusion_list WHERE exclusionid = '$id' AND listid = '$listid'");

	if ($c == 0) {
		$ins = array(
			"exclusionid" => $id,
			"listid" => $listid,
			"sync" => 0,
		);

		adesk_sql_insert("#exclusion_list", $ins);
	}
}

function exclusion_remove($email, $listid) {
	$emailEsc = adesk_sql_escape($email);
	$listid   = (int)$listid;
	$id       = (int)adesk_sql_select_one("SELECT id FROM #exclusion WHERE email = '$emailEsc' AND matchtype = 'exact'");

	if ($id == 0) {
		# Nobody here by that email.
		return;
	}

	$c = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #exclusion_list WHERE exclusionid = '$id' AND listid = '$listid'");

	if ($c > 0) {
		adesk_sql_query("DELETE FROM #exclusion_list WHERE exclusionid = '$id' AND listid = '$listid'");
	}

	$c = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #exclusion_list WHERE exclusionid = '$id'");

	# Delete the entire exclusion if nothing is left.
	if ($c == 0)
		adesk_sql_query("DELETE FROM #exclusion WHERE id = '$id'");
}

?>
