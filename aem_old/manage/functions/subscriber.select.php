<?php

require_once adesk_admin("functions/filter.php");
require_once awebdesk_functions("log.php");

function subscriber_select_query(&$so, $count = false, $unsub = false) {
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		if ( $admin['id'] > 1 ) {
		//	@$liststr = implode("','", $admin["lists"]);
	$admin   = adesk_admin_get();
	
        $uid = $admin['id'];
	if($uid != 1 ){
	$lists = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");
	//get the lists of users
	
	@$liststr = implode("','",$lists);
	}
	else
	{

	@$liststr = implode("','", $admin["lists"]);
	}
			if ($so->counting)
				$cond = "AND (SELECT COUNT(*) FROM #subscriber_list subq WHERE subq.subscriberid = s.id AND subq.listid IN ('$liststr')) > 0";
			else
				$cond = "AND l.listid IN ('$liststr')";
			if ( !in_array($cond, $so->conds) ) $so->push($cond);
			/*
			if (!$so->counting)
				$so->push("AND l.listid IN ('" . implode("', '", $admin['lists']) . "')");
			else
				$so->push("AND s.id IN (SELECT subl.subscriberid FROM #subscriber_list subl WHERE subl.listid IN ('$liststr'))");
			*/
		}
	}

	$listtab = ", #subscriber s";
	if ( !in_array("AND s.id = l.subscriberid", $so->conds) ) $so->push("AND s.id = l.subscriberid");


	if (!$so->counting) {
		if ( !isset($so->usedInSendingEngine) ) {
			//$so->groupby("l.subscriberid");
		}
	} else {
		$addsubcond = !in_array("AND ( SELECT COUNT(*) FROM #subscriber subqs WHERE subqs.id = s.id ) > 0", $so->conds);
		foreach ( $so->conds as $v ) {
			if ( adesk_str_instr('#subscriber ', $v) ) {
				$addsubcond = false;
				break;
			}
		}
		if ( $addsubcond ) $so->push("AND ( SELECT COUNT(*) FROM #subscriber subqs WHERE subqs.id = s.id ) > 0");
	}


	return $so->query("
		SELECT
			*,
			s.id AS id,
			l.id AS lid,
			INET_NTOA(s.ip) AS ip,
			INET_NTOA(l.ip4) AS ip4,
			TRIM(CONCAT(first_name, ' ', last_name)) AS name,
			DATE(l.udate) AS a_unsub_date,
			TIME(l.udate) AS a_unsub_time
		FROM
			#subscriber_list l
			$listtab
			[___]
		WHERE
		[...]
	");
}

function subscriber_select_query_alt(&$so, $count = false, $unsub = false, $alwayshaslist = false) {
	@$liststr = "";
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		if ( $admin['id'] != 1 ) {
		//	@$liststr = implode("','", $admin["lists"]);
			$admin   = adesk_admin_get();
	
        $uid = $admin['id'];
	if($uid != 1 ){
	$lists = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");
	//get the lists of users
	
	@$liststr = implode("','",$lists);
	}
	else
	{
	@$liststr = implode("','", $admin["lists"]);
	}
			$cond = "AND (SELECT COUNT(*) FROM #subscriber_list subx WHERE subx.listid IN ('$liststr') AND subx.subscriberid = s.id) > 0";
			//$cond = "AND l.listid IN ('" . implode("', '", $admin['lists']) . "')";
			if ( !in_array($cond, $so->conds) ) $so->push($cond);
			/*
			if (!$so->counting)
				$so->push("AND l.listid IN ('" . implode("', '", $admin['lists']) . "')");
			else
				$so->push("AND s.id IN (SELECT subl.subscriberid FROM #subscriber_list subl WHERE subl.listid IN ('$liststr'))");
			*/
		}
	}

	$subcond = ( $liststr ? array("listid IN ('$liststr')") : array("1") );
	$conds   = implode(", ", $so->conds);
	$matches = array();

	# These should be separate from $haslist, $hasstat, etc., since we only
	# care about about lx being inside a subquery.
	if (preg_match('/lx\.listid = \'(\d+)\'/', $conds, $matches))
		$subcond[] = "listid = '$matches[1]'";

	if (preg_match('/lx\.status = \'(\d+)\'/', $conds, $matches))
		$subcond[] = "status = '$matches[1]'";

	$haslist = strpos($conds, "l.listid") !== false;
	$hasstat = strpos($conds, "l.status") !== false;
	$hasname = strpos($conds, "l.first_name") !== false || strpos($conds, "l.last_name") !== false;

	$subcond = "AND " . implode(" AND ", $subcond);

	if ($haslist || $alwayshaslist) {
		$so->join("#subscriber_list l", array("AND l.subscriberid = s.id $subcond"));
	} elseif ($hasstat) {
		if ($liststr != "")
			$so->conds = explode(", ", preg_replace('/l\.status = \'?(\d+)\'?/', '\1 IN (SELECT subx.status FROM #subscriber_list subx WHERE subx.subscriberid = s.id AND subx.listid IN (\'' . $liststr . '\'))', $conds));
		else
			$so->conds = explode(", ", preg_replace('/l\.status = \'?(\d+)\'?/', '\1 IN (SELECT subx.status FROM #subscriber_list subx WHERE subx.subscriberid = s.id)', $conds));
	} elseif ($hasname) {
		$so->join("#subscriber_list l", array("AND l.subscriberid = s.id $subcond"));
	}

	//$subcond = "AND " . implode(" AND ", $subcond);

	// this one might need a conversion into subscriber_list for first|last_name fields
	return $so->query("
		SELECT
			*,
			s.id AS id,
			INET_NTOA(s.ip) AS ip,
			( SELECT INET_NTOA(ip4) FROM #subscriber_list WHERE subscriberid = s.id $subcond LIMIT 1) AS ip4,
			( SELECT first_name FROM #subscriber_list WHERE subscriberid = s.id $subcond LIMIT 1 ) AS first_name,
			( SELECT last_name FROM #subscriber_list WHERE subscriberid = s.id $subcond LIMIT 1 ) AS last_name,
			( SELECT TRIM(CONCAT(first_name, ' ', last_name)) FROM #subscriber_list WHERE subscriberid = s.id $subcond LIMIT 1 ) AS name
		FROM
			#subscriber s
			[___]
		WHERE
			[...]
	");
}

function subscriber_select_row($id) {
	$so = new adesk_Select;
	if ( adesk_str_is_email((string)$id) ) {
		$email = adesk_sql_escape($id);
		$so->push("AND s.email = '$email'");
	} elseif ( $id = (int)$id ) {
		$so->push("AND s.id = '$id'");
	} else {
		return array();
	}

	$r = adesk_sql_select_row(subscriber_select_query($so), array('cdate', 'sdate', 'udate'));

	if ( $r ) {
		$id = $r['id'];
		$r['lists'] = subscriber_get_lists($id, null);
		$r['listslist'] = implode('-', array_keys($r['lists']));
		$r['fields'] = subscriber_get_fields($id, array_keys($r['lists']));
	}

	return $r;
}

function subscriber_view($id) {
	$r = subscriber_select_row($id);
	if ( !$r ) return $r;
	$id = (int)$r['id'];
	$email = adesk_sql_escape($r['email']);
	// collect bounce data
	$r['bounces'] = array(
		'mailing' => array(),
		'mailings' => 0,
		'responder' => array(),
		'responders' => 0
	);
	// for messages
	$query = "
		SELECT
			b.*,
			l.name AS listname,
			m.name AS campaignname,
			c.descript AS description
		FROM
			#bounce_data b,
			#bounce_code c,
			#campaign m,
			#campaign_list s,
			#list l
		WHERE
		(
			b.subscriberid = '$id'
		OR
			b.email = '$email'
		)
		AND
			c.type IN ('single', 'recurring', 'split', 'deskrss', 'text')
		AND
			b.campaignid = m.id
		AND
			s.campaignid = m.id
		AND
			s.listid = l.id
		AND
			c.code = b.code
		GROUP BY
			b.id
	";
	$r['bounces']['mailing'] = adesk_sql_select_array($query, array('tstamp'));
	$r['bounces']['mailings'] = count($r['bounces']['mailing']);
	// for responders
	$query = "
		SELECT
			b.*,
			l.name AS listname,
			m.name AS campaignname,
			c.descript AS description
		FROM
			#bounce_data b,
			#bounce_code c,
			#campaign m,
			#campaign_list s,
			#list l
		WHERE
		(
			b.subscriberid = '$id'
		OR
			b.email = '$email'
		)
		AND
			m.type = 'responder'
		AND
			b.campaignid = m.id
		AND
			s.campaignid = m.id
		AND
			s.listid = l.id
		AND
			c.code = b.code
		GROUP BY
			l.id
	";
	$r['bounces']['responder'] = adesk_sql_select_array($query, array('tstamp'));
	$r['bounces']['responders'] = count($r['bounces']['responder']);
	$r['bouncescnt'] = $r['bounces']['mailings'] + $r['bounces']['responders'];
	// message/responder history might be bigger than 'a few'
	return $r;
}

function subscriber_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND s.id IN ('$ids')");
	}
	return adesk_sql_select_array(subscriber_select_query($so), array('cdate', 'sdate', 'udate'));
}

function subscriber_select_array_alt($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND s.id IN ('$ids')");
	}
	return adesk_sql_select_array(subscriber_select_query_alt($so), array('cdate', 'sdate', 'udate'));
}

function subscriber_select_array_paginator($id = 1, $sort = '', $offset = 0, $limit = 20, $filter = 0, $unsub = false, $fieldsneedtitles = false, $bounced = false) {
	$so_count  = new adesk_Select;
	$so_select = new adesk_Select;

	if (!$offset) $offset = 0; // if NULL (not passed via API)

	$filter = intval($filter);
	$date = 's.cdate';
	if ($filter > 0) {
		$admin = adesk_admin_get();
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'subscriber'");
		$so_count->push($conds);
		$so_select->push($conds);
		if ( adesk_str_instr('l.listid', $conds) ) {
			$date = 'l.sdate';
		}

		$so_select->groupby("s.id");
	} elseif ( $unsub ) {
		$so_count->push("AND l.status = '2'");
		$so_select->push("AND l.status = '2'");
	} elseif ( !$unsub and $bounced ) {
		$so_count->push("AND l.status = '3'");
		$so_select->push("AND l.status = '3'");
	} elseif ( !$unsub and !$bounced ) {
		$so_count->push("AND l.status = 1"); // subscribed = DEFAULT
		$so_select->push("AND l.status = 1"); // subscribed = DEFAULT
	}

	$so_select->push("AND s.email != 'twitter'");
	$so_count->push("AND s.email != 'twitter'");

	$so_count->dcount("s.id");
	//dbg(subscriber_select_query_alt($so_count),1);
	$total = (int)adesk_sql_select_one(subscriber_select_query_alt($so_count));

	$orderby = subscriber_select_sorter($sort, $so_select, $date);
	$so_select->orderby($orderby);

	if ( (int)$limit == 0 ) $limit = 999999999;
	$so_select->limit("$offset, $limit");

	//dbg(adesk_prefix_replace(subscriber_select_query_alt($so_select)));
	$rows = subscriber_select_array_alt($so_select);

	$nl = null;
	if ( isset($_SESSION['nlp']) and defined('AWEBVIEW') ) {
		$nl = $_SESSION['nlp'];
	} elseif ( isset($_SESSION['nla']) ) {
		$nl = $_SESSION['nla'];
	}
	if ( $nl ) {
		foreach ( $rows as $k => $v ) {
			$sel = "";

			if ($fieldsneedtitles)
				$sel = ", f.title AS a_title";

			$rs  = adesk_sql_query("
				SELECT
					v.fieldid,
					v.val
					$sel
				FROM
					#list_field_value v,
					#list_field f
				WHERE
					v.relid = '$v[id]'
					AND
						f.id = v.fieldid
					AND
						f.show_in_list = '1'

			");

			while ($fieldrow = adesk_sql_fetch_assoc($rs)) {
				if ($fieldsneedtitles)
					$rows[$k]["field" . $fieldrow["fieldid"]] = array($fieldrow["a_title"], adesk_custom_fields_check_blank($fieldrow["val"]));
				else
					$rows[$k]["field" . $fieldrow["fieldid"]] = adesk_custom_fields_check_blank($fieldrow["val"]);
			}
		}
	}

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function subscriber_export($fields, $sort, $offset, $limit, $filter, $segmentid = 0) {
	$so = new adesk_Select();
	$so->remove = false;
	$so->slist = array();

	$segmentid = (int)$segmentid;

	$r = array(
		'fields' => array(),
		'customfields' => array(),
		'rs' => false,
	);


	$nl = null;
	if ( isset($_SESSION['nlp']) and defined('AWEBVIEW') ) {
		$nl = $_SESSION['nlp'];
	} elseif ( isset($_SESSION['nla']) ) {
		$nl = $_SESSION['nla'];
	}

	@$liststr = "";
	if ($nl) {
		// filter by just list
		if ( is_array($nl) ) {
			if ( count($nl) > 0 ) {
				@$liststr = implode("','", array_map('intval', $nl));
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
				@$liststr = $listid;
			} else {
				if ( defined('AWEBVIEW') ) {
					unset($_SESSION['nlp']);
				} else {
					unset($_SESSION['nla']);
				}
			}
		}
	}

	if ($segmentid > 0) {
		# Here, we just want to see what lists are used by the segment.  If $liststr is not
		# empty, we'll assign it based on those lists.

		$usedinlists = adesk_sql_select_list("SELECT listid FROM #filter_list WHERE filterid = '$segmentid'");
		$inlistconds = adesk_sql_select_list("SELECT rhs FROM #filter_group_cond WHERE lhs = 'inlist' AND filterid = '$segmentid'");

		$overall = array_unique(array_merge($usedinlists, $inlistconds));
		if (count($overall) > 0)
			@$liststr = implode("','", array_map("intval", $overall));
	}

	$subcond1 = ( $liststr ? "AND lx.listid IN ('$liststr')" : "" );
	//$subcond2 = ( $liststr ? "AND ll.id IN ('$liststr')" : "" );
	$subcond2 = ( $liststr ? "WHERE ll.id IN ('$liststr')" : "WHERE ll.id = l.listid" );

	$r['customfields'] = array();
	$so->slist[] = "s.id AS id";
	foreach ( $fields as $k => $v ) {
		if ( !preg_match('/^\d+$/', $v) ) {
			// if a standard field
			$r['fields'][$k] = $v;
			if ($v == "id")
				continue;
			if ( $v == 'ip' ) {
				$so->slist[] = "(SELECT INET_NTOA(lx.ip4) FROM awebdesk_subscriber_list lx WHERE lx.subscriberid = s.id $subcond1 LIMIT 1) AS ip4";
			} elseif ($v == 'listname') {
				//$so->slist[] = "(SELECT ll.name FROM #list ll WHERE ll.id = l.listid $subcond2) AS listname";
				$so->slist[] = "(SELECT ll.name FROM #list ll $subcond2 LIMIT 1) AS listname";
			} elseif ($v == 'first_name') {
				//$so->slist[] = "l.first_name";
				$so->slist[] = "( SELECT first_name FROM awebdesk_subscriber_list lx WHERE lx.subscriberid = s.id $subcond1 LIMIT 1 ) AS first_name";
			} elseif ($v == 'last_name') {
				$so->slist[] = "( SELECT last_name FROM awebdesk_subscriber_list lx WHERE lx.subscriberid = s.id $subcond1 LIMIT 1 ) AS last_name";
				//$so->slist[] = "l.last_name";
			} else {
				$so->slist[] = "$v";
			}
		} else {
			// custom fields
			$r['customfields'][$v] = $k;
			$so->slist[] = "'' AS field$v";
			// subqueries would be too much here
			//$so->slist[] = "( SELECT val FROM #list_field_value WHERE relid = l.subscriberid AND fieldid = '$v' LIMIT 1 ) AS field$v";
		}
	}
	if ( !count($so->slist) ) {
		$so->slist[] = 's.email';
	}

	// if there is some custom fields
	if ( count($r['customfields']) ) {
		// fetch them
		$ids = implode("','", array_keys($r['customfields']));
		$rs = adesk_sql_select_box_array("SELECT id, title FROM #list_field WHERE id IN ('$ids')");
		// and assign their names
		foreach ( $rs as $k => $v ) {
			$r['fields'][$r['customfields'][$k]] = $v;
		}
	}

	if ( count($r['fields']) == 0 ) return false;

	ksort($r['fields']);

	// now fetch the data

	// add filter conditions
	$filter = intval($filter);
	$date = 's.cdate';
	if ($filter > 0) {
		$admin = adesk_admin_get();
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'subscriber'");
		//dbg($conds);
		$so->push($conds);
		if ( adesk_str_instr('l.listid', $conds) ) {
			$date = 'l.sdate';
		}
	} else {
		$so->push("AND l.status = 1"); // subscribed = DEFAULT
	}

	if($liststr) {
		if(adesk_str_instr(',', $liststr))
			$so->push("AND l.listid IN('$liststr')");
		else
			$so->push("AND l.listid = '$liststr'");
	}

	// sort
	$orderby = subscriber_select_sorter($sort, $so, $date);
	$so->orderby($orderby);

	// limit
	if ( (int)$limit == 0 ) $limit = 999999999;
	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$so->groupby("s.id");

	$query = subscriber_select_query_alt($so, false, false, true);
	$r['rs'] = adesk_sql_query($query);

	if ( !$r['rs'] or !adesk_sql_num_rows($r['rs']) ) return false;

	adesk_ihook_define('adesk_export_row', 'subscriber_export_row');

	return $r;
}

function subscriber_export_row($row, $export) {
	# Convert all values to the proper character set.
	$tocharset = _i18n("utf-8");

	if (isset($row["ip4"])) {
		if ($row["ip4"] == "" || $row["ip4"] == "0.0.0.0")
			$row["ip4"] = (string)adesk_sql_select_one("SELECT INET_NTOA(ip) AS `ip` FROM #subscriber WHERE id = '$row[id]'");
	}

	foreach ($row as $k => $v)
		$row[$k] = adesk_utf_conv("UTF-8", $tocharset, $v);

	if ( !count($export['customfields']) ) {

		if (isset($row["id"]) && !in_array("id", $export["fields"]))
			unset($row["id"]);

		return $row;
	}

	$ids = implode("','", array_keys($export['customfields']));
	$query = "
		SELECT
		fieldid,
		val
		FROM
		#list_field_value
		WHERE
		relid = '$row[id]'
		AND
		fieldid IN ('$ids')
		";
	$rs  = adesk_sql_query($query);

	while ( $field = adesk_sql_fetch_assoc($rs) ) {
		if ( isset($row['field' . $field['fieldid']]) ) {
			$row['field' . $field['fieldid']] = adesk_utf_conv("UTF-8", $tocharset, adesk_custom_fields_check_blank($field['val']));
		}
	}

	if (isset($row["id"]) && !in_array("id", $export["fields"]))
		unset($row["id"]);

	return $row;
}

function subscriber_select_filter($sort, $datefield = 'cdate') {
	switch ($sort) {
		default:
		case "01":
			return "s.email";
		case "01D":
			return "s.email DESC";
		case "02":
			return "l.first_name, l.last_name";
		case "02D":
			return "l.first_name DESC, l.last_name DESC";
		case "03":
			return $datefield;
		case "03D":
			return "$datefield DESC";
		case "99":
			return "l.udate";
		case "99D":
			return "l.udate DESC";
	}
}

function subscriber_select_sorter($sort, &$so, $datefield = 'cdate') {
	switch ($sort) {
		default:
		case "01":
			$so->modify("#subscriber s", "#subscriber s FORCE INDEX (email)");
			return "s.email";
		case "01D":
			$so->modify("#subscriber s", "#subscriber s FORCE INDEX (email)");
			return "s.email DESC";
		case "02":
			//$so->modify("#subscriber s", "#subscriber s");
			return "first_name, last_name";
		case "02D":
			$so->modify("#subscriber s", "#subscriber s");
			return "first_name DESC, last_name DESC";
		case "03":
			if ($datefield == "cdate") {
				$so->modify("#subscriber s", "#subscriber s FORCE INDEX (cdate)");
			} else {
				$so->modify("#subscriber_list l", "#subscriber_list l FORCE INDEX (sdate)");
			}
			return $datefield;
		case "03D":
			if ($datefield == "cdate") {
				$so->modify("#subscriber s", "#subscriber s FORCE INDEX (cdate)");
			} else {
				$so->modify("#subscriber_list l", "#subscriber_list l FORCE INDEX (sdate)");
			}
			return "$datefield DESC";
		case "99":
			$so->modify("#subscriber_list l", "#subscriber_list l FORCE INDEX (udate)");
			return "l.udate";
		case "99D":
			$so->modify("#subscriber_list l", "#subscriber_list l FORCE INDEX (udate)");
			return "l.udate DESC";
	}
}

function subscriber_filter_segment($segmentid) {
	$ary = array(
		"userid"    => $GLOBALS['admin']['id'],
		"sectionid" => "subscriber",
		"=tstamp"   => "NOW()",
	);

	$ary["conds"] = "AND " . filter_compile($segmentid);

	$filter  = filter_select_row($segmentid);
	@$liststr = $filter["lists"];
	$cond = " AND (SELECT COUNT(*) FROM #subscriber_list subx WHERE subx.listid IN ($liststr) AND subx.status = 1 AND subx.subscriberid = s.id) > 0";
	if (!adesk_str_instr(".status", $ary["conds"])) {
		$cond = " AND (SELECT COUNT(*) FROM #subscriber_list subx WHERE subx.listid IN ($liststr) AND subx.status = 1 AND subx.subscriberid = s.id) > 0";
		$ary["conds"] .= $cond;
	}

	$conds_esc = adesk_sql_escape($ary['conds']);
	$filterid = adesk_sql_select_one("
		SELECT
			id
		FROM
			#section_filter
		WHERE
			userid = '$ary[userid]'
		AND
			sectionid = 'subscriber'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

function subscriber_filter_post() {
	$whitelist = array("s.email", "l.first_name", "l.last_name");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "subscriber",
		"conds" => "",
		"=tstamp" => "NOW()",
	);

	if (isset($_POST["qsearch"]) && !isset($_POST["content"])) {
		$_POST["content"] = $_POST["qsearch"];
	}

	if (isset($_POST["content"]) and $_POST['content'] != '') {
		$content = adesk_sql_escape($_POST["content"], true);

		// if there is at least one space present, and not at beginning or end of string
		$space = preg_match("/[A-Za-z0-9]+\s[A-Za-z0-9]+/", $content);
		// allow search for full name - only do this if a space is present in the search query
		if ($space) $whitelist[] = "CONCAT(l.first_name,' ',l.last_name)";

		$conds = array();

		if (!isset($_POST["section"]) || !is_array($_POST["section"]))
			$_POST["section"] = $whitelist;

		foreach ($_POST["section"] as $sect) {
			if (!in_array($sect, $whitelist)) {
				continue;
			}
			$conds[] = "$sect LIKE '%$content%'";
		}

		if (isset($_POST["custom"])) {
			$conds[] = "(
				SELECT
					COUNT(*)
				FROM
					#list_field_value fv
				WHERE
					fv.relid = s.id
				AND
					val LIKE '%$content%'
			) > 0";
		}

		$conds = implode(" OR ", $conds);
		$ary["conds"] = "AND ($conds) ";
	}

	if (isset($_POST["listid"])) {
		if (is_array($_POST["listid"]))
			$_SESSION["nla"] = $_POST["listid"];
		elseif ((int)$_POST["listid"] > 0)
			$_SESSION["nla"] = (int)$_POST["listid"];
		else
			unset($_SESSION["nla"]);
	}

	$nl = null;
	if ( isset($_SESSION['nlp']) and defined('AWEBVIEW') ) {
		$nl = $_SESSION['nlp'];
	} elseif ( isset($_SESSION['nla']) ) {
		$nl = $_SESSION['nla'];
	}

// New code for filter status
	if (isset($_POST["status"])) {
		$filter_status = $_SESSION["Aawebdesk_subscriber_status"] = $_POST["status"];
	}
	else {
		if (isset($_SESSION["Aawebdesk_subscriber_status"])) {
			$filter_status = $_SESSION["Aawebdesk_subscriber_status"];
		}
	}

	if (isset($filter_status) && $nl && ($filter_status != '')) {

		//filter by both list and status

		$x = '';


		if ( $nl ) {
			if ( is_array($nl) ) {
				if ( count($nl) > 0 ) {
					$ids = implode("', '", array_map('intval', $nl));
					# This needs to use "lx" (or something similar) to avoid the pattern checks for "l.listid".
					$x = "AND lx.listid IN ('$ids')";
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
					$x = "AND lx.listid = '$listid'";
					#$ary['conds'] .= "AND l.listid = '$listid' ";
				} else {
					if ( defined('AWEBVIEW') ) {
						unset($_SESSION['nlp']);
					} else {
						unset($_SESSION['nla']);
					}
				}
			}
		}



		$y = '';


		if ( is_array($filter_status) ) {
			if ( count($filter_status) > 0 ) {
				$pos = array_search("", $filter_status);

				if ($pos !== false) {
					array_splice($filter_status, $pos, 1);
				}

				$ids = implode("', '", array_map('intval', $filter_status));

				if ($ids != "")
					$y = "AND lx.status IN ('$ids')";
			}
		} else {
			if ( $filter_status != '' ) {
				$filter_status = (int)$filter_status;
				$y = "AND lx.status = '$filter_status'";
				#$ary['conds'] .= "AND l.status = '$filter_status' ";
			} else {
				$ary['conds'] .= "AND 1 = 1 "; // dummy condition to save no-condition-for-status as filter
			}
		}


		$ary['conds'] .= "AND (SELECT COUNT(*) FROM #subscriber_list lx WHERE lx.subscriberid = s.id $x $y) > 0 ";


	}

	else{

		if ( $nl ) {
			if ( is_array($nl) ) {
				if ( count($nl) > 0 ) {
					$ids = implode("', '", array_map('intval', $nl));
					# This needs to use "lx" (or something similar) to avoid the pattern checks for "l.listid".
					$ary['conds'] .= "AND (SELECT COUNT(*) FROM #subscriber_list lx WHERE lx.subscriberid = s.id AND lx.listid IN ('$ids')) > 0 ";
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
					$ary['conds'] .= "AND (SELECT COUNT(*) FROM #subscriber_list lx WHERE lx.subscriberid = s.id AND lx.listid = '$listid') > 0 ";
					#$ary['conds'] .= "AND l.listid = '$listid' ";
				} else {
					if ( defined('AWEBVIEW') ) {
						unset($_SESSION['nlp']);
					} else {
						unset($_SESSION['nla']);
					}
				}
			}
		}


		if (isset($filter_status)) {

			//filter by just status

			if ( is_array($filter_status) ) {
				if ( count($filter_status) > 0 ) {
					$pos = array_search("", $filter_status);

					if ($pos !== false) {
						array_splice($filter_status, $pos, 1);
					}

					$ids = implode("', '", array_map('intval', $filter_status));

					if ($ids != "")
						$ary['conds'] .= "AND (SELECT COUNT(*) FROM #subscriber_list lx WHERE lx.status IN ('$ids') AND lx.subscriberid = s.id) > 0 ";
				}
			} else {
				if ( $filter_status != '' ) {
					$filter_status = (int)$filter_status;
					$ary['conds'] .= "AND (SELECT COUNT(*) FROM #subscriber_list lx WHERE lx.status = '$filter_status' AND lx.subscriberid = s.id) > 0 ";
					#$ary['conds'] .= "AND l.status = '$filter_status' ";
				} else {
					$ary['conds'] .= "AND 1 = 1 "; // dummy condition to save no-condition-for-status as filter
				}
			}
		}
	}

	/* Old code for filter status
	if (isset($_POST["status"]))
		$_SESSION["Aawebdesk_subscriber_status"] = $_POST["status"];

	if (isset($_POST["status"])) {
		if ( is_array($_POST['status']) ) {
			if ( count($_POST['status']) > 0 ) {
				$ids = implode("', '", array_map('intval', $_POST['status']));
				$ary['conds'] .= "AND l.status IN ('$ids') ";
			}
		} else {
			if ( $_POST['status'] != '' ) {
				$status = (int)$_POST['status'];
				$ary['conds'] .= "AND l.status = '$status' ";
			} else {
				$ary['conds'] .= "AND 1 = 1 "; // dummy condition to save no-condition-for-status as filter
			}
		}
	}
	*/

	if (isset($_POST["segmentid"]) && $_POST["segmentid"] > 0) {
		$ary['conds'] = "AND " . filter_compile($_POST["segmentid"]) . ' ' . $ary['conds'];
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
			sectionid = 'subscriber'
		AND
			conds = '$conds_esc'
	");

	$r = array();

	if ( isset($content) ) $r["content"] = $content;

	if (intval($filterid) > 0) {
		$r["filterid"] = $filterid;
		return $r;
	}

	adesk_sql_insert("#section_filter", $ary);
	$r["filterid"] = adesk_sql_insert_id();
	return $r;
}

// api
function subscriber_view_hash($hash) {
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		if ( $admin['id'] > 1 ) {
			//@$liststr = implode("','", $admin["lists"]);
			
		//needs to be done sandeep	
			$listids = $admin["lists"];
		}
	}
	else {
	  $listids = 0;
	}
	$subscriber = subscriber_exists($hash, $listids, "hash");
	return $subscriber;
}

// $match: email address or hash value
// actions: exact, like, hash
function subscriber_exists($match, $listID = 0, $action = 'exact', $status = null) {
	$whitelist = array('exact', 'like', 'hash');
	if ( !in_array($action, $whitelist) ) $action = 'exact';
	if ( $match == 'twitter' and $action == 'hash' ) {
		$listID = 0;
		$action = 'exact';
	}
	$s = adesk_sql_escape($match);
	switch ( $action ) {
		case 'exact':
			$where = "s.email = '$s'";
			break;
		case 'like':
			$s = adesk_sql_escape($match, true);
			$where = "s.email LIKE '%$s%'";
			break;
		case 'hash':
			//$where = "MD5(CONCAT(s.id, s.email)) = '$s'";
			$where = "s.hash = '$s'";
			break;
		default:
			$where = 0;
			break;
	}
	// if list is 0, we're looking if subscriber exists at all
	if ( is_array($listID) ) {
		$listID_original_array = $listID;
		$listID = implode("','", array_map('intval', $listID));
		if ( !$listID ) $listID = 0;
	} else $listID = (int)$listID;
	$q = "
		SELECT
			*,
			INET_NTOA(s.ip) AS ip,
			INET_NTOA(l.ip4) AS ip4,
			TRIM(CONCAT(first_name, ' ', last_name)) AS name,
			s.id AS id,
			l.id AS lid
		FROM
			#subscriber s
		LEFT JOIN
			#subscriber_list l
		ON
			s.id = l.subscriberid
		AND
			l.listid IN ('$listID')
		WHERE
			$where
		GROUP BY
			s.id
		ORDER BY
			l.id DESC
	";
	$r = adesk_sql_select_row($q, array('cdate', 'sdate'));

	if ( !$r ) return false;
	if ( $listID > 0 ) {

		if(isset($listID_original_array))
			$listID_array = $listID_original_array;
		else
			$listID_array = explode(",", $listID);

		if ( !in_array($r['listid'], $listID_array) ) {
		//if ( !adesk_str_instr($r['listid'], $listID) ) {
		//if ( $r['listid'] != $listID ) {
			return false;
		}
	}
	if ( !is_null($status) ) {
		if ( $r['status'] != $status ) {
			return false;
		}
	}
	if ( !$r['subscriberid'] ) $r['subscriberid'] = $r['id'];
	if ( $r['subscriberid'] != $r['id'] ) {
		$r['lid'] = $r['id'];
		$r['id']  = $r['subscriberid'];
	}
	return $r;
}

function subscriber_list($ids, $filters = array()) {

	$r = array();

	if ($filters) {
		$whitelist = array("email", "first_name", "last_name", "listid", "status", "datetime", "since_datetime", "fields");
		$conds = array();

		foreach ($filters as $k => $v) {

			if (!in_array($k, $whitelist)) {
				continue;
			}

			// #subscriber DB fields
			if ($k == "email") {
				$conds[] = "AND s." . $k . " LIKE '%" . adesk_sql_escape($v, true) . "%'";
			}
			elseif ($k == "fields") {
				// passing custom field filters (filtering on the value)
				$fields = unserialize($v);
				$ids = array();
				foreach ($fields as $fieldid => $search) {
					$relids = adesk_sql_select_list("SELECT relid FROM #list_field_value WHERE val LIKE '%" . adesk_sql_escape($search, true) . "%'");
					foreach ($relids as $relid) {
						// add to main ids array, which contains subscriber ID's - should be unique
						if ( !in_array($relid, $ids) ) $ids[] = $relid;
					}
				}
				// if subscribers are found matching the custom field value search
				if ($ids) {
					$ids = implode(",", $ids);
				}
				else {
					// otherwise provide blank key so no results are returned
					$ids = array(0);
				}
			}
			else {
				// #subscriber_list DB fields
				if ($k == "listid" || $k == "status" || $k == "first_name" || $k == "last_name") {
					$conds[] = "AND l." . $k . " IN ('" . $v . "')";
				}

				if ($k == "datetime") {
					// Decide which database field to use in the expression, based on what was passed for "status"
					$field = ($filters["status"] == 2) ? "udate" : "sdate";
					$conds[] = "AND l." . $field . " LIKE '%" . adesk_sql_escape($v, true) . "%'";
				}

				if ($k == "since_datetime") {
					// Decide which database field to use in the expression, based on what was passed for "status"
					$field = ($filters["status"] == 2) ? "udate" : "sdate";
					$conds[] = "AND l." . $field . " >= '" . $v . "'";
				}
			}
		}

		// If they do happen to pass $ids as well, OR if $ids is already set, take that into account and filter the results further
		if ($ids) {
			// if it's a string of ID's, IE: "1,2,3"
			if ( !is_array($ids) ) {
				$ids = explode(",", $ids);
			}
			$ids = implode("','", $ids);
			$conds[] = "AND l.subscriberid IN ('" . $ids . "')";
		}

		$ids = adesk_sql_select_list("SELECT l.subscriberid FROM #subscriber_list l INNER JOIN #subscriber s ON l.subscriberid = s.id WHERE 1 " . implode(" ", $conds) . " GROUP BY l.subscriberid" );

		foreach ($ids as $id) {
			if ( $v = subscriber_view($id) ) $r[] = $v;
		}

		return $r;
	}

	// Default: if only $ids are passed
	if (!$r) {

		$ids = array_diff(array_map('intval', explode(',', $ids)), array(0));

		if ( !$ids ) return $ids;
		$r = array();
		foreach ( $ids as $id ) {
			if ( $v = subscriber_view($id) ) $r[] = $v;
		}
		return $r;
	}
}

function subscriber_personalize_get($subscriber, $campaign = null) {
	if ( !isset($subscriber['hash']) ) {
		$subscriber['hash'] = md5($subscriber['subscriberid'] . $subscriber['email']);
	}
	if ( !adesk_str_instr(' ', $subscriber['sdate']) ) $subscriber['sdate'] = adesk_CURRENTDATETIME;
	list($sdate, $stime) = explode(' ', $subscriber['sdate']);

	$listname = "";

	// find his list
	$list = null;
	if ( $campaign ) {
		foreach ( $campaign['lists'] as $l ) {
			if ( $l['id'] == $subscriber['listid'] ) $list = $l;
		}
		if ( !$list ) $list = $campaign['lists'][0];
		// check subscriber's name
		if ( $subscriber['first_name'] == '' and $subscriber['last_name'] == '' ) {
			$subscriber['first_name'] = $subscriber['name'] = $list['to_name'];
		}

		$listname = $list["name"];
	}
	// do general personalization
	$pers = array();
	$pers['%SUBSCRIBERID%'] =
	$pers['%PERS_ID%'] = $subscriber['subscriberid'];
	$pers['%PERS_LISTNAME%'] =
	$pers['%LISTNAME%'] = $listname;
	$pers['%PERS_FIRSTNAME%'] =
	$pers['%FIRSTNAME%'] = $subscriber['first_name'];
	$pers['%PERS_LASTNAME%'] =
	$pers['%LASTNAME%'] = $subscriber['last_name'];
	$pers['%FULLNAME%'] =
	$pers['%PERS_NAME%'] = $subscriber['name'];
	$pers['%PERS_EMAIL%'] =
	$pers['%EMAIL%'] = $subscriber['email'];
	$pers['%SUBSCRIBERIP%'] =
	$pers['%PERS_IP%'] = isset($subscriber['ip4']) ? $subscriber['ip4'] : $subscriber['ip'];
	$pers['%SUBDATETIME%'] =
	$pers['%PERS_DATETIME%'] = adesk_date_format($subscriber['sdate'], $GLOBALS['site']['datetimeformat']);
	$pers['%SUBDATE%'] =
	$pers['%PERS_DATE%'] = adesk_date_format($sdate, $GLOBALS['site']['dateformat']);
	$pers['%SUBTIME%'] =
	$pers['%PERS_TIME%'] = adesk_date_format($stime, $GLOBALS['site']['datetimeformat']);
	$pers['%TODAY%'] =
	$pers['%PERS_TODAY%'] =
	$pers['%SENDDATE%'] = adesk_date_format(adesk_getCurrentDate(), $GLOBALS['site']['dateformat']);
	$pers['%SENDTIME%'] = adesk_date_format(adesk_getCurrentTime(), $GLOBALS['site']['timeformat']);
	$pers['%SENDDATETIME%'] = adesk_date_format(adesk_getCurrentDateTime(), $GLOBALS['site']['datetimeformat']);
	//$pers['subscriberemailec'] = base64_encode($subscriber['email']);
	//$pers['subscriberemail'] = urlencode($subscriber['email']);
	//$pers['subscriberid'] = base64_encode($subscriber['subscriber']);
	$pers['subscriberemailec'] =
	$pers['subscriberemail'] =
	$pers['subscriberid'] =
	$pers['%SUBSCRIBER_HASH%'] = $subscriber['hash'];

	// do list id personalization
	if ( !$subscriber['listid'] ) {
		if ( !isset($subscriber['lists']) ) $subscriber = subscriber_select_row($subscriber['subscriberid']);
		$subscriber['listid'] = $campaign['lists'][0]['id']; // he might not be confirmed for this one
	}
	$pers['%LISTID%'] =
	$pers['currentnl'] = (int)$subscriber['listid'];

	$pers['%SUBSCRIBER_RATING%'] = subscriber_rating($subscriber['subscriberid'], $subscriber['email'], $subscriber['listid']);

	// do campaign personalization
	if ( $campaign ) {
		$pers['%SENDDATE%'] = adesk_date_format($campaign['sdate'], $GLOBALS['site']['dateformat']);
		$pers['%SENDTIME%'] = adesk_date_format($campaign['sdate'], $GLOBALS['site']['timeformat']);
		$pers['%SENDDATETIME%'] = adesk_date_format($campaign['sdate'], $GLOBALS['site']['datetimeformat']);
		$pers['rndmnmbr'] = rand('100000', '900000');
		if ( !isset($subscriber['messageid']) or !in_array($subscriber['messageid'], explode('-', $campaign['messageslist'])) ) {
			// set the first one
			$subscriber['messageid'] = $campaign['messages'][0]['id'];
		}
		$pers['%MESSAGEID%'] =
		$pers['currentmesg'] = $subscriber['messageid'];
		$pers['%CAMPAIGNID%'] =
		$pers['cmpgnid'] = ( $campaign['realcid'] ? $campaign['realcid'] : $campaign['id'] );
		$pers['cmpgnhash'] = md5($pers['cmpgnid']);
		$pers['%TOTAL%'] = $campaign['total_amt'];
		$pers['%%PERS_TBLID%%'] = $subscriber['id']; // id in TEMP table
		$awebdesk_xmid = base64_encode($subscriber['email'] . ' , c' . $campaign['id'] . ' , m' . $subscriber['messageid']);
		$pers['%X-MID%'] = $awebdesk_xmid;
		$pers['%ANALYTICSUA%'] = '';
		// find list
		foreach ( $campaign['lists'] as $l ) {
			if ( $l['id'] == $pers['currentnl'] ) {
				$pers['%ANALYTICSUA%'] = $l['analytics_ua'];
			}
		}

//dbg($campaign['fields']);
		foreach ( $campaign['fields'] as $f ) {
			$val = '';
			if ( isset($subscriber['f' . $f['id']]) ) {
				$val = $subscriber['f' . $f['id']];
			} elseif ( isset($subscriber['fields']) ) {
				foreach ( $subscriber['fields'] as $v ) {
					if ( $v['id'] == $f['id'] ) {
						$val = $v['val'];
						break(1);
					}
				}
			}
			// check if exists first (so it doesn't replace any of our internal personalization tags
			if ( !isset($pers[$f['tag']]) ) {
				$pers[$f['tag']] = $val;
			}
		}

		// list sender info
		$pers['%SENDER-INFO%'] = personalization_senderinfo($list);
	} else {
		// if no campaign is provided, do opt-in/opt-out personalization
		//
	}
	return $pers;
}

function subscriber_personalize($subscriber, $listids, $subscription_form_id, $body, $type = 'sub') {
	global $site;


	//$hash = md5($subscriber["id"] . $subscriber["email"]);
	$hash = $subscriber['hash'];

	if ( !isset($subscriber['sdate']) or !$subscriber['sdate'] ) {
		$subscriber['sdate'] = $subscriber['cdate'];
	}
/*
	// Subscriber Personalization Tags
	$body = str_replace("%PERS_EMAIL%", $subscriber["email"], $body);
	$body = str_replace("%EMAIL%", $subscriber["email"], $body);
	$body = str_replace("%PERS_FIRSTNAME%", $subscriber["first_name"], $body);
	$body = str_replace("%FIRSTNAME%", $subscriber["first_name"], $body);
	$body = str_replace("%PERS_LASTNAME%", $subscriber["last_name"], $body);
	$body = str_replace("%LASTNAME%", $subscriber["last_name"], $body);
	$body = str_replace("%FULLNAME%", $subscriber["first_name"].' '.$subscriber["last_name"], $body);
	$body = str_replace("%PERS_IP%", $subscriber["ip"], $body);
	$body = str_replace("%SUBSCRIBERIP%", $subscriber["ip"], $body);
	$body = str_replace("%PERS_DATE%", adesk_date_format($subscriber["sdate"], $site['dateformat']), $body);
	$body = str_replace("%SUBDATETIME%", adesk_date_format($subscriber["sdate"], $site['datetimeformat']), $body);
	$body = str_replace("%SUBDATE%", adesk_date_format($subscriber["sdate"], $site['dateformat']), $body);
	$body = str_replace("%SENDDATE%", adesk_date_format(adesk_CURRENTDATETIME, $site['dateformat']), $body);
	$body = str_replace("%PERS_TIME%", adesk_date_format($subscriber["sdate"], $site['timeformat']), $body);
	$body = str_replace("%SUBTIME%", adesk_date_format($subscriber["sdate"], $site['timeformat']), $body);
	$body = str_replace("%SENDTIME%", adesk_date_format(adesk_CURRENTDATETIME, $site['timeformat']), $body);
	$body = str_replace("%SENDDATETIME%", adesk_date_format(adesk_CURRENTDATETIME, $site['datetimeformat']), $body);
	$body = str_replace("%PERS_ID%", $subscriber["id"], $body);
	$body = str_replace("%SUBSCRIBERID%", $subscriber["id"], $body);
	$body = str_replace("%SUBSCRIBER_RATING%", subscriber_rating($subscriber['id'], $subscriber['email'], $listid ? $listid : 0), $body);

	// System Personalization Tags
	$body = str_replace("%CONFIRMLINK%", $site["p_link"] . "/surround.php?nl=" . $listids . "&p=" . $subscription_form_id . "&s=" . $hash . "&funcml=c" . $type, $body);
	$body = str_replace("%SUBSCRIBELINK%", $site["p_link"] . "/surround.php?nl=" . $listids . "&p=" . $subscription_form_id . "&s=" . $hash . "&funcml=csub", $body);
	$body = str_replace("%UNSUBSCRIBELINK%", $site["p_link"] . "/surround.php?nl=" . $listids . "&p=" . $subscription_form_id . "&s=" . $hash . "&funcml=cunsub", $body);
	$body = str_replace("%FORWARD2FRIEND%", $site["p_link"] . "/forward.php?nl=" . $listids . "&c=0&m=0&s=" . $hash, $body);
	$body = str_replace("%WEBCOPY%", $site["p_link"] . "/forward3.php?l=" . $listids . "&c=0&m=0&s=" . $hash, $body);
	$body = str_replace("%WEBCOPY%", $site["p_link"] . "/index.php?action=social&c=" . md5(0) . ".0", $body);
	$body = str_replace("%PERS_UP%", $site["p_link"] . "/index.php?action=account_update&s=" . $hash, $body);
	$body = str_replace("%UPDATELINK%", $site["p_link"] . "/index.php?action=account_update&s=" . $hash, $body);*/


	//these things don't work in opt-in/out messages
	$body = str_replace("%SOCIALSHARE%", '', $body);
	$socnets = array_map('strtoupper', personalization_social_networks());
	foreach ( $socnets as $sn ) $body = str_replace("%SOCIALSHARE-$sn%", '', $body);
	$body = str_replace("%SOCIAL-FACEBOOK-LIKE%", '', $body);
	$body = str_replace("%FORWARD2FRIEND%", '', $body);
	$body = str_replace("%WEBCOPY%", '', $body);

	$list = null;
	$listname = "";

	if ($listids != "") {
		$tmp = explode(",", $listids);
		if (count($tmp) > 0) {
			$listid   = (int)$tmp[0];
			$list     = adesk_sql_select_row("SELECT * FROM #list WHERE id = '$listid'");
			$listname = $list['name'];
		}
	}

	$perstags = array(
		"%PERS_EMAIL%" => $subscriber["email"],
		"%EMAIL%" => $subscriber["email"],
		"%PERS_LISTNAME%" => $listname,
		"%LISTNAME%" => $listname,
		"%PERS_FIRSTNAME%" => $subscriber["first_name"],
		"%FIRSTNAME%" => $subscriber["first_name"],
		"%PERS_LASTNAME%" => $subscriber["last_name"],
		"%LASTNAME%" => $subscriber["last_name"],
		"%FULLNAME%" => $subscriber["first_name"].' '.$subscriber["last_name"],
		"%PERS_IP%" => $subscriber["ip4"],
		"%SUBSCRIBERIP%" => $subscriber["ip4"],
		"%PERS_DATE%" => adesk_date_format($subscriber["sdate"], $site['dateformat']),
		"%SUBDATETIME%" => adesk_date_format($subscriber["sdate"], $site['datetimeformat']),
		"%SUBDATE%" => adesk_date_format($subscriber["sdate"], $site['dateformat']),
		"%SENDDATE%" => adesk_date_format(adesk_CURRENTDATETIME, $site['dateformat']),
		"%PERS_TIME%" => adesk_date_format($subscriber["sdate"], $site['timeformat']),
		"%SUBTIME%" => adesk_date_format($subscriber["sdate"], $site['timeformat']),
		"%SENDTIME%" => adesk_date_format(adesk_CURRENTDATETIME, $site['timeformat']),
		"%SENDDATETIME%" => adesk_date_format(adesk_CURRENTDATETIME, $site['datetimeformat']),
		"%PERS_ID%" => $subscriber["id"],
		"%SUBSCRIBERID%" => $subscriber["id"],
		"%SUBSCRIBER_RATING%" => subscriber_rating($subscriber['id'], $subscriber['email'], $listid ? $listid : 0),

		"%CONFIRMLINK%" => $site["p_link"] . "/surround.php?nl=" . $listids . "&p=" . $subscription_form_id . "&s=" . $hash . "&funcml=c" . $type,
		"%SUBSCRIBELINK%" => $site["p_link"] . "/surround.php?nl=" . $listids . "&p=" . $subscription_form_id . "&s=" . $hash . "&funcml=csub",
		"%UNSUBSCRIBELINK%" => $site["p_link"] . "/surround.php?nl=" . $listids . "&p=" . $subscription_form_id . "&s=" . $hash . "&funcml=cunsub",
		"currentnl" => $listids,
		"cmpgnid" => 0,
		"cmpgnhash" => md5(0),
		"currentmesg" => 0,
		"subscriberid" => $hash,
		"%SENDER-INFO%" => ( $list ? personalization_senderinfo($list) : '' ),
	);

	// Global Custom Fields
	$fields = subscriber_get_fields($subscriber["id"], $listids, false);
	foreach ( $fields as $field ) {
		$perstags[$field["tag"]] = $field["val"];
		//$body = str_replace($field["tag"], $field["val"], $body);
	}

	require_once adesk_admin("functions/personalization.php");
	// Get the basic tags
	$body = personalization_basic($body, '');



	if ( !adesk_str_instr('%/IF%', strtoupper($body)) ) {
		// personalize
		return str_replace(array_keys($perstags), array_values($perstags), $body);
	}
	// run conditional personalization
	return personalization_conditional($perstags, $body, false);

	//return $body;
}

function subscriber_get_fields($id, $list = 0, $userel = true) {
	$id = (int)$id;
	if ( $list === false || $list === null || $list === '' ) $list = "'0'";
	if ( is_array($list) ) {
		if ( !in_array(0, $list) ) $list[] = 0;
		$list = implode(', ', $list);
	} elseif ( $list !== 0 ) {
		$list .= ', 0';
	}
	$fields = adesk_sql_select_list("SELECT fieldid FROM #list_field_rel WHERE relid IN ($list)");
	$fieldstr = implode("','", $fields);
	// using in-loop fetch
	//return adesk_custom_fields_select_nodata_rel('#list_field', '#list_field_rel', "r.relid IN ($list)", "SELECT val FROM #list_field_value WHERE relid = '$id' AND fieldid = '%s' LIMIT 1");
	// using subquery
	//return adesk_custom_fields_select_data_rel_subquery('#list_field', '#list_field_rel', "SELECT val FROM #list_field_value WHERE relid = '$id' AND fieldid = f.id LIMIT 1", "f.id = r.fieldid AND r.relid IN ($list) GROUP BY f.id");
	// using left join
	if ($userel) {
		$rows = adesk_custom_fields_select_data_rel('#list_field', '#list_field_rel', '#list_field_value', "d.relid = '$id' AND f.id = d.fieldid", "f.id = r.fieldid AND r.relid IN ($list) GROUP BY f.id");
		$rval = array();

		foreach ($rows as $row)
			$rval[$row["id"]] = $row;

		return $rval;
	} else {
		$rows = adesk_custom_fields_select_data_norel(
			"#list_field",
			"#list_field_value",
			"d.relid = '$id' AND f.id IN ('$fieldstr')"
		);
		$rval = array();

		foreach ($rows as $row)
			$rval[$row["id"]] = $row;

		return $rval;
	}
}

function subscriber_get_lists($subscriberid, $status = null, $ids = null) {
	$so = new adesk_Select();
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		if ( $admin['id'] > 1 ) {
		        $uid = $admin['id'];
	if($uid != 1 ){
	$lists = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");
	//get the lists of users
	
	@$liststr = implode("','",$lists);
	}
	else
	{
	@$liststr = implode("','", $admin["lists"]);
	}
		//	@$liststr = implode("','", $admin["lists"]);
			$cond = "AND l.id IN ('$liststr')";
			if ( !in_array($cond, $so->conds) ) $so->push($cond);
		} elseif ( $admin['id'] == 0 ) {
			// or maybe we should process list ids only here?
			//$so->push("AND l.private = 0");
		}
	}
	if ( !is_null($status) ) {
		$statuses = ( is_array($status) ? implode("','", array_map('intval', $status)) : (int)$status );
		$so->push("AND s.status IN ('$statuses')");
	}
	if ( !is_null($ids) ) {
		if ( !is_array($ids) ) $ids = array_diff(array_map('intval', explode(',', $ids)), array(0));
		if ( count($ids) ) {
			$ids = implode("', '", $ids);
			$so->push("AND l.id IN ('$ids')");
		}
	}
	$query = $so->query("
		SELECT
			s.*,
			l.name AS listname
		FROM
			#list l,
			#subscriber_list s
		WHERE
		[...]
		AND
			s.subscriberid = '$subscriberid'
		AND
			s.listid = l.id
	");
	$sql = adesk_sql_query($query);
	$r = array();
	while ( $row = adesk_sql_fetch_assoc($sql, array('sdate', 'udate')) ) {
		$r[$row['listid']] = $row;
	}
	return $r;
}

function subscriber_bounce_lowercounts($id, $email, $type) {
	$rows = adesk_sql_query("
		SELECT
			campaignid,
			count(id) AS count
		FROM
			#bounce_data
		WHERE
			(email = '$email' OR id = '$id')
		AND `type` = '$type'
		GROUP BY
			campaignid
	");

	$bouncefield = "hardbounces";
	if ($type == "soft")
		$bouncefield = "softbounces";

	while ($row = adesk_sql_fetch_assoc($rows)) {
		$campaignid = intval($row["campaignid"]);
		$count      = intval($row["count"]);
		adesk_sql_query("
			UPDATE
				#campaign
			SET
				`$bouncefield` = `$bouncefield` - $count
			WHERE
				id = '$campaignid'
		");
		// try this as well, doesn't hurt
		adesk_sql_query("
			UPDATE
				#campaign_deleted
			SET
				`$bouncefield` = `$bouncefield` - $count
			WHERE
				id = '$campaignid'
		");

		adesk_sql_query("
			UPDATE
				#campaign_message
			SET
				`$bouncefield` = `$bouncefield` - $count
			WHERE
				campaignid = '$campaignid'
		");
	}
}

function subscriber_stats_query(&$so, $panel = 'mailing') {
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		if ( $admin['id'] != 1 ) {
			$cond = "AND l.id IN ('" . implode("', '", $admin['lists']) . "')";
			if ( !in_array($cond, $so->conds) ) $so->push($cond);
		}
	}
	if ( $panel == 'mailing' ) {
		$cond = "AND c.type IN ('single', 'recurring', 'split', 'deskrss', 'text')";
		if ( !in_array($cond, $so->conds) ) $so->push($cond);
		return $so->query("
			SELECT
				c.id,
				l.id AS listid,
				l.name AS listname,
				c.name AS campaignname,
				c.sdate,
				d.email,
				d.subscriberid
			FROM
				#list l,
				#link t,
				#link_data d,
				#campaign_list e,
				#campaign c
			WHERE
			[...]
			AND
				t.id = d.linkid
			AND
				c.id = t.campaignid
			AND
				c.id = e.campaignid
			AND
				l.id = e.listid
			AND
				t.tracked = 1
			GROUP BY c.id
		");
	} elseif ( $panel == 'responder' ) {
		$cond = "AND c.type IN ('responder', 'reminder')";
		if ( !in_array($cond, $so->conds) ) $so->push($cond);
		return $so->query("
			SELECT
				c.id,
				l.id AS listid,
				l.name AS listname,
				c.name AS campaignname,
				r.sdate,
				s.email,
				r.subscriberid
			FROM
				#list l,
				#subscriber s,
				#subscriber_list e,
				#subscriber_responder r,
				#campaign c
			WHERE
			[...]
			AND
				s.id = e.subscriberid
			AND
				l.id = e.listid
			AND
				s.id = r.subscriberid
			AND
				l.id = r.listid
			AND
				c.id = r.campaignid
			GROUP BY c.id
		");
	} elseif ( $panel == 'log' ) {
		return $so->query("
			SELECT
				c.id,
				l.id AS listid,
				l.name AS listname,
				c.name AS campaignname,
				c.sdate,
				s.email,
				g.subscriberid,
				g.comment,
				g.successful,
				g.tstamp
			FROM
				#list l,
				#subscriber s,
				#subscriber_list e,
				#log g,
				#campaign c,
				#campaign_list cl
			WHERE
			[...]
			AND
				s.id = e.subscriberid
			AND
				l.id = e.listid
			AND
				s.id = g.subscriberid
			AND
				c.id = g.campaignid
			AND
				cl.campaignid = c.id
			AND
				cl.listid = l.id
			GROUP BY c.id
		");
	}
}

function subscriber_stats_array($so = null, $ids = null, $panel = 'mailing') {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		$tmp = array_map("intval", explode(",", $ids));
		$ids = implode("','", $tmp);
		$so->push("AND m.id IN ('$ids')");
	}
	//dbg(adesk_prefix_replace(subscriber_stats_query($so, $panel), array('sdate')));
	if($panel=='log')
		$r = adesk_sql_select_array(subscriber_stats_query($so, $panel), array('sdate, tstamp'));
	else
		$r = adesk_sql_select_array(subscriber_stats_query($so, $panel), array('sdate'));

	foreach ( $r as $k => $v ) {
		// fetch reads,links,forwards for this message
		$email = adesk_sql_escape($v['email']);
/**/
			$reads_query = "
				SELECT
					tstamp,
					email,
					times
				FROM
					#link l,
					#link_data d
				WHERE
					l.campaignid = '$v[id]'
				AND
				(
					d.email = '$email'
				OR
					d.subscriberid = '$v[subscriberid]'
				)
				AND
					l.link IN ('open', '')
				AND
					l.tracked = 1
				AND
					d.linkid = l.id
				ORDER BY
					d.tstamp DESC
			";
			$r[$k]['reads'] = adesk_sql_select_array($reads_query, array('tstamp'));
			$links_query = "
				SELECT
					l.link,
					l.name,
					d.times,
					d.tstamp
				FROM
					#link l,
					#link_data d
				WHERE
					l.campaignid = '$v[id]'
				AND
					(
						d.email = '$email'
					OR
						d.subscriberid = '$v[subscriberid]'
					)
				AND
					l.link NOT IN ('open', '')
				AND
					l.tracked = 1
				AND
					d.linkid = l.id
				ORDER BY
					d.tstamp DESC
			";
			$r[$k]['links'] = adesk_sql_select_array($links_query, array('tstamp'));
			// forwards
			$forwards_query = "
				SELECT
					*
				FROM
					#forward f
				WHERE
					f.campaignid = '$v[id]'
				AND
					(
						f.email_from = '$email'
					OR
						f.subscriberid = '$v[subscriberid]'
					)
				ORDER BY
					f.tstamp DESC
			";
			$r[$k]['forwards'] = adesk_sql_select_array($forwards_query, array('tstamp'));
/**/
	}
	return $r;
}

function subscriber_stats_array_paginator($paginator, $panel, $id, $sort, $offset, $limit, $list) {
	$so = new adesk_Select;

	$list = (int)$list;
	if ( $list > 0 ) {
		$so->push("AND l.id = '$list'");
	}

	$subscriber = subscriber_select_row($id);
	if ( $subscriber ) {
		$email = $subscriber['email'];
		$emailEsc = adesk_sql_escape($email);
		//$nl = $subscriber['nl'];
		if ( $panel == 'mailing' ) {
			$so->push("AND t.link IN ('open', '') AND ( d.subscriberid = '$id' OR d.email = '$emailEsc' )");
		} else if ( $panel == 'responder' ) {
			$so->push("AND ( s.id = '$id' OR s.email = '$emailEsc' )");
		} elseif ( $panel == 'log' ) {
			//dbg('2do: logs');
			$so->push("AND s.id = '$id'");
		}
	}


	$so->count("DISTINCT(c.id)");
	$total = (int)adesk_sql_select_one(subscriber_stats_query($so, $panel));

	switch ($sort) {
		case "01":
			$so->orderby("l.name"); break;
		case "01D":
			$so->orderby("l.name DESC"); break;
		case "02":
			$so->orderby("c.name"); break;
		case "02D":
			$so->orderby("c.name DESC"); break;
		case "03":
			$so->orderby("c.sdate"); break;
		case "03D":
			$so->orderby("c.sdate DESC"); break;
		case "04":
			$so->orderby("g.sent"); break;
		case "04D":
			$so->orderby("g.sent DESC"); break;
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = subscriber_stats_array($so, null, $panel);

	return array(
		"paginator"   => $paginator,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function subscriber_search($string, $format = '%%%s%%') {
	$escaped = sprintf($format, adesk_sql_escape($string, true));
	$so = new adesk_Select();
	$so->push("AND ( s.email LIKE '$escaped' OR l.first_name LIKE '$escaped' OR l.last_name LIKE '$escaped' )");
	return subscriber_select_array_alt($so);
}

function subscriber_exportlist_export() {
	$admin = $GLOBALS["admin"];

	if (!$admin["pg_list_add"])
		return adesk_ajax_api_result(false, _a("You do not have permission to export subscribers into a new list."));

	$name = adesk_sql_escape($_POST["name"]);
	$c    = adesk_sql_select_one("SELECT COUNT(*) FROM #list WHERE name = '$name'");

	if ($c > 0)
		return adesk_ajax_api_result(false, _a("There is already a list with that name.  Please choose another."));

	$filterid = intval($_POST["filterid"]);
	if ($filterid < 1)
		return adesk_ajax_api_result(false, _a("You did not provide a filter for us to use."));

	# Now create the list.
	# First unset filterid, in case list_insert_post() should one day want that.  That means that
	# we're leaving name and from_email in $_POST for list_insert_post() to use.
	unset($_POST["filterid"]);

	require_once adesk_admin("functions/list.php");
	$rval = list_insert_post();

	if (!$rval["succeeded"])
		return adesk_ajax_api_result(false, _a("Couldn't add the list."));

	$listid = intval($rval["id"]);
	$conds  = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filterid' AND userid = '$admin[id]' AND sectionid = 'subscriber'");
	$so     = new adesk_Select;

	$so->push($conds);

	$limit = intval($_POST["limit"]);
	if ($limit > 0)
		$so->limit($limit);

	# Create the subscriber_list records, indicating that these subscribers now belong to the
	# new list.
	$newids = adesk_sql_select_list($so->query("
		SELECT DISTINCT
			s.id
		FROM
			#subscriber s,
			#subscriber_list l
		WHERE
			[...]
		AND
			l.subscriberid = s.id
	"));

	$ins = array();
	foreach ($newids as $newid) {
		$first_name = adesk_sql_escape(adesk_sql_select_one('first_name', '#subscriber_list', "subscriberid = '$newid'"));
		$last_name = adesk_sql_escape(adesk_sql_select_one('last_name', '#subscriber_list', "subscriberid = '$newid'"));
		$ins[] = "('$newid', '$listid', NOW(), '1', '$first_name', '$last_name', 3)";
	}

	$insstr = implode(",", $ins);
	adesk_sql_query("
		INSERT INTO #subscriber_list
			(subscriberid, listid, sdate, status, first_name, last_name, sourceid)
		VALUES
			$insstr
	");
}

function subscriber_select_field_dataids($id) {
	$id   = intval($id);
	$rval = array();
	$rs   = adesk_sql_query("
		SELECT
			fieldid, id
		FROM
			#list_field_value
		WHERE
			relid = '$id'
	");

	while ($row = adesk_sql_fetch_assoc($rs))
		$rval[$row["fieldid"]] = $row["id"];

	return $rval;
}

function subscriber_select_fields($id, $listid, $editable) {
	$id     = (int)$id;
	$listid = (int)$listid;

	$admin   = adesk_admin_get();

	if (!isset($admin["lists"][$listid]))
		return;

	$sub    = adesk_sql_select_row("SELECT *, INET_NTOA(ip4) AS ip4str FROM #subscriber_list WHERE subscriberid = '$id' AND listid = '$listid'", array("sdate"));
	$ip     = adesk_sql_select_one("SELECT INET_NTOA(ip) FROM #subscriber WHERE id = '$id'");

	return array(
		"editable"   => $editable,
		"first_name" => $sub["first_name"],
		"last_name"  => $sub["last_name"],
		"sdate"      => strftime($GLOBALS["site"]["datetimeformat"], strtotime($sub["sdate"])),
		"udate"      => strftime($GLOBALS["site"]["datetimeformat"], strtotime($sub["udate"])),
		"ip4"        => $sub["ip4str"],
		"ip"         => $ip,
		"status"     => $sub["status"],
		"listname"   => adesk_sql_select_one("SELECT name FROM #list WHERE id = '$listid'"),
		"row"        => adesk_custom_fields_select_data_rel("#list_field", "#list_field_rel", "#list_field_value", "d.relid = '$id' AND d.fieldid = f.id", "r.relid IN ('$listid', '0') AND r.fieldid = f.id"),
	);
}

function subscriber_update_fields() {
	if (!permission("pg_subscriber_edit"))
		return;

	$admin   = adesk_admin_get();
	$listid  = (int)adesk_http_param("listid");

	if (!isset($admin["lists"][$listid]))
		return;

	$id         = (int)adesk_http_param("id");
	$first_name = (string)adesk_http_param("first_name");
	$last_name  = (string)adesk_http_param("last_name");
	$status     = (int)adesk_http_param("status");
	$fields     = adesk_http_param_forcearray("field");
	$fields     = array_map("adesk_str_strip_tags", $fields);

	adesk_custom_fields_update_data($fields, "#list_field_value", "fieldid", array("relid" => $id));

	$up = array(
		"first_name" => $first_name,
		"last_name"  => $last_name,
		"status"     => $status,
	);

	$oldstatus = (int)adesk_sql_select_one('status', "#subscriber_list", "subscriberid = '$id' AND listid = '$listid'");

	// only update the sdate if they are MOVING TO "subscribed" status (from another status)
	if ($oldstatus != 1 && $status == 1)
		$up["=sdate"] = "NOW()";

	// if switching from bounced to any other
	if ( $oldstatus == 3 and $status != 3 ) {
		$email = adesk_sql_escape(adesk_sql_select_one('email', "#subscriber", "id = '$id'"));
		// update subscriber's bounce totals
		$update = array(
			'bounce_hard' => 0,
			'bounce_soft' => 0,
		);
		adesk_sql_update("#subscriber", $update, "id = '$id'");
		// remove bounce log
		adesk_sql_delete('#bounce_data', "( `id` = '$id' OR `email` = '$email' )");
		// update campaigns that marked him as bounced
		subscriber_bounce_lowercounts($id, $email, "soft");
		subscriber_bounce_lowercounts($id, $email, "hard");
	}

	adesk_sql_update("#subscriber_list", $up, "subscriberid = '$id' AND listid = '$listid'");
}

function subscriber_view_lists($id, $listid) {
	$id      = (int)$id;
	$listid  = (int)$listid;
	$admin   = adesk_admin_get();
	//@$liststr = implode("','", $admin["lists"]);
        $uid = $admin['id'];
	if($uid != 1 ){
	$lists = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");
	//get the lists of users
	
	@$liststr = implode("','",$lists);
	}
	else
	{
	@$liststr = implode("','", $admin["lists"]);
	}
	$available = adesk_sql_select_one("
		SELECT
			COUNT(*)
		FROM
			#list l
		WHERE
			l.id IN ('$liststr')
		AND
			(SELECT COUNT(*) FROM #subscriber_list s WHERE s.subscriberid = '$id' AND s.listid = l.id AND s.status = 1) = 0
	");

	$rval = array(
		"listid"    => $listid,
		"available" => $available,
		"row"       => adesk_sql_select_array("SELECT s.listid, s.status, (SELECT l.name FROM #list l WHERE l.id = s.listid) AS name FROM #subscriber_list s WHERE s.subscriberid = '$id' AND s.listid IN ('$liststr') ORDER BY name"),
	);

	return $rval;
}

function subscriber_view_unlists($id) {
	$id      = (int)$id;

$admin   = adesk_admin_get();
	        $uid = $admin['id'];
	if($uid != 1 ){
	$lists = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");


	//get the lists of users
	
	@$liststr = implode("','",$lists);
	}
	else
	{
	@$liststr = implode("','", $admin["lists"]);
	}




	return adesk_sql_select_array("
		SELECT
			l.id,
			l.name
		FROM
			#list l
		WHERE
			l.id IN ('$liststr')
		AND
			(SELECT COUNT(*) FROM #subscriber_list s WHERE s.subscriberid = '$id' AND s.listid = l.id AND s.status = 1) = 0
		ORDER BY
			l.name
	");
}

function subscriber_view_subscribe($id, $listid, $copyfrom) {
	if (!permission("pg_subscriber_edit"))
		return adesk_ajax_api_result(false, _a("No permission to subscribe"));

	$id     = (int)$id;
	$listid = (int)$listid;
	$admin   = adesk_admin_get();

	if (!isset($admin["lists"][$listid]))
		return;

	# Check if exists first.
	$row = adesk_sql_select_row("SELECT id FROM #subscriber_list WHERE subscriberid = '$id' AND listid = '$listid'");

	if ($row) {
		adesk_sql_query("UPDATE #subscriber_list SET status = 1 WHERE id = '$row[id]'");
		return adesk_ajax_api_result(true, _a("Subscribed"));
	}

	$ins = array(
		"subscriberid" => $id,
		"listid"       => $listid,
		"status"       => 1,
		"=sdate"       => "NOW()",
		"sourceid"     => 3,
	);

	$copyfrom = (int)$copyfrom;

	if ($copyfrom > 0) {
		$copy = adesk_sql_select_row("SELECT * FROM #subscriber_list WHERE subscriberid = '$id' AND listid = '$copyfrom'");
		$ins["first_name"] = $copy["first_name"];
		$ins["last_name"]  = $copy["last_name"];
	}

	adesk_sql_insert("#subscriber_list", $ins);

	return adesk_ajax_api_result(true, _a("Subscribed"));
}

function subscriber_view_unsubscribe($id, $listid) {
	$id     = (int)$id;
	$listid = (int)$listid;

	if (!permission("pg_subscriber_edit"))
		return adesk_ajax_api_result(false, _a("No permission to unsubscribe"));

	$admin   = adesk_admin_get();

	if (!isset($admin["lists"][$listid]))
		return;

	$rval  = array("result" => subscriber_unsubscribe($id, null, array($listid)), "deleted" => 0);
	$count = (int)adesk_sql_select_one("SELECT * FROM #subscriber_list WHERE subscriberid = '$id' AND listid = '$listid' AND status IN (1, 0)");

	return $rval;
}

// creates a dummy subscriber
function subscriber_dummy($email, $listid = 0) {
	return array(
		'id'           => 0,
		'subscriberid' => 0,
		'cdate'        => adesk_CURRENTDATETIME,
		'sdate'        => adesk_CURRENTDATETIME,
		'hash'         => md5(0 . $email),
		'email'        => $email,
		'first_name'   => '',
		'last_name'    => '',
		'name'         => '',
		'ip'           => ( isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1' ),
		'ip4'          => ( isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1' ),
		'ua'           => ( isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '' ),
		'listid' => $listid,
		'lid' => $listid,
	);
}

function subscriber_rating($subscriberid, $email, $listid = 0) {
	// define result
	$r = 0;

	//if ( isset($GLOBALS['_hosted_account']) ) {
		//require(dirname(dirname(__FILE__)) . '/manage/subscriber.rate.inc.php');
	//}

	// return result
	return $r;
}

function subscriber_geoip_save($subscriberid, $ip = '127.0.0.1') {
	$subscriberid = (int)$subscriberid;

	if ( !$subscriberid ) return false;
	if ( !$ip or $ip == '127.0.0.1' ) return false;

	$geoip = adesk_maxmind_lookup($ip, '#geoip');
	if ( !$geoip ) return false;

	$update = array();
	foreach ( $geoip as $k => $v ) { if ( $k != 'id' ) $update['geo_'.$k] = $v; }
	adesk_sql_update('#subscriber_data', $update, "subscriberid = '$subscriberid'");
}

?>
