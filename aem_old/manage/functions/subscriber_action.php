<?php

require_once awebdesk_classes("select.php");
require_once adesk_admin("functions/subscriber.php");
require_once adesk_admin("functions/campaign.php");

function subscriber_action_select_query(&$so) {
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		if ( $admin['id'] != 1 ) {
			//@$liststr = implode("','", $admin["lists"]);
		//sandeep	
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
			$so->push("AND (r.listid IN ('$liststr') OR r.listid = 0)");
		}
	}

	return $so->query("
		SELECT
			r.*,
			(SELECT subl.name FROM #list subl WHERE subl.id = r.listid) AS `a_listname`
		FROM
			#subscriber_action r
		WHERE
			[...]
	");
}

function subscriber_action_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND r.id = '$id'");

	$row = adesk_sql_select_row(subscriber_action_select_query($so));
	if ( !$row ) return false;

	require_once adesk_admin("functions/campaign.php");
	require_once adesk_admin("functions/link.php");

	$row["campaigns"]["row"] = campaign_selectdropdown_bylist($row["listid"]);
	$row["links"]["row"]     = link_selectdropdown_bycampaign($row["campaignid"]);
	$row["parts"]["row"]     = adesk_sql_select_array("
		SELECT
			*
		FROM
			#subscriber_action_part
		WHERE
			actionid = '$row[id]'
	");

	return $row;
}

function subscriber_action_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND r.id IN ('$ids')");
	}
	return adesk_sql_select_array(subscriber_action_select_query($so));
}

function subscriber_action_select_array_paginator($id, $sort, $offset, $limit, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'subscriber_action'");
		$so->push($conds);
	}

	if ($admin["id"] != 1) {
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
		
		$so->push("AND r.listid IN ('$liststr')");
	}

	$so->count();
	$so->greedy = true;
	$total = (int)adesk_sql_select_one(subscriber_action_select_query($so));

	switch ($sort) {
		case "01":
			$so->orderby("r.name"); break;
		case "01D":
			$so->orderby("r.name DESC"); break;
		case "02":
			$so->orderby("r.type"); break;
		case "02D":
			$so->orderby("r.type DESC"); break;
		case "03":
			$so->orderby("a_listname"); break;
		case "03D":
			$so->orderby("a_listname DESC"); break;
	}

	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = subscriber_action_select_array($so);

	for ($i = 0, $len = count($rows); $i < $len; $i++) {
		$actid = $rows[$i]["id"];
		$parts = adesk_sql_select_list("SELECT act FROM #subscriber_action_part WHERE actionid = '$actid'");
		$rows[$i]["a_parts"] = implode(", ", $parts);
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

function subscriber_action_filter_post() {
	$whitelist = array("r.name");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "subscriber_action",
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
				if (count($nl) != 1 || $nl[0] != 0) {
					$ids = implode("', '", array_map('intval', $nl));
					$ary['conds'] .= "AND r.listid IN ('$ids') ";
				}
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
				$ary['conds'] .= "AND (r.listid = '$listid' OR r.listid = '0') ";
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
			sectionid = 'subscriber_action'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

function subscriber_action_updateparts($id) {
	# Update the action parts for a given $id, relying on what's passed by POST.
	$actlist = adesk_http_param("linkaction");
	$vals    = array(
		1 => adesk_http_param("linkvalue1"),
		2 => adesk_http_param("linkvalue2"),
		3 => adesk_http_param("linkvalue3"),
		4 => adesk_http_param("linkvalue4"),
	);

	if (!$actlist || !$vals[1] || !$vals[2] || !$vals[3] || !$vals[4])
		return;

	# Check the counts.
	$len = count($actlist);
	if (count($vals[1]) != $len || count($vals[2]) != $len || count($vals[3]) != $len || count($vals[4]) != $len)
		return;

	$id = intval($id);

	# Remove everything that's there; we'll re-insert them.
	adesk_sql_delete("#subscriber_action_part", "`actionid` = '$id'");

	# The rules:
	#   linkvalue1 = listid
	#   linkvalue2 = campaignid
	#   linkvalue3 = fieldid or field name
	#   linkvalue4 = field value

	for ($i = 0, $len = count($actlist); $i < $len; $i++) {
		$act = $actlist[$i];
		$ins = array(
			"actionid" => $id,
			"act"      => $act,
		);

		switch ($act) {
			case "subscribe":
			case "unsubscribe":
				$ins["targetid"] = $vals[1][$i];
				break;

			case "send":
				$ins["targetid"] = $vals[2][$i];
				break;

			case "update":
				# targetid will be 0 if this vals[3] is not a whole number.
				if (preg_match('/^[0-9]+$/', $vals[3][$i]))
					$ins["targetid"] = $vals[3][$i];
				else
					$ins["targetfield"] = $vals[3][$i];

				$ins["param"] = $vals[4][$i];
				break;
			default:
				break;
		}

		adesk_sql_insert("#subscriber_action_part", $ins);
	}

	return count($actlist);
}

function subscriber_action_autoname($type, $listid, $linkid, $campaignid) {
	$name       = "";
	$listname   = "";
	$linkname   = "";
	$campname   = "";
	$listid     = intval($listid);
	$linkid     = intval($linkid);
	$campaignid = intval($campaignid);

	switch ($type) {
		default:
		case "read":
			$campname = adesk_sql_select_one("SELECT name FROM #campaign WHERE id = '$campaignid'");
			$name     = sprintf(_a("Open in campaign %s", $campname));
			break;

		case "link":
			$linkname = adesk_str_shorten(str_replace('http://', '', adesk_sql_select_one("SELECT IF(name != '' OR name IS NOT NULL, name, link) FROM #link WHERE id = '$linkid'")), 25);
			$campname = adesk_sql_select_one("SELECT name FROM #campaign WHERE id = '$campaignid'");
			$name     = sprintf(_a("Link %s in campaign %s", $linkname, $campname));
			break;

		case "subscribe":
			$listname = adesk_sql_select_one("SELECT name FROM #list WHERE id = '$listid'");
			$name     = sprintf(_a("Subscribe to list %s", $listname));
			break;

		case "unsubscribe":
			$listname = adesk_sql_select_one("SELECT name FROM #list WHERE id = '$listid'");
			$name     = sprintf(_a("Unsubscribe from list %s", $listname));
			break;

		case "forward":
			$campname = adesk_sql_select_one("SELECT name FROM #campaign WHERE id = '$campaignid'");
			$name     = sprintf(_a("Forward campaign %s", $campname));
			break;
	}

	return $name;
}

function subscriber_action_insert_post() {
	$ary = array(
		"campaignid" => $_POST["campaignid"],
		"linkid"     => $_POST["linkid"],
		"listid"     => $_POST["listid"],
		"type"       => $_POST["type"],
		"socmedia"   => $_POST["social"],
	);

	if (isset($_POST["name"])) {
		$ary["name"] = $_POST["name"];
	}

	$sql = adesk_sql_insert("#subscriber_action", $ary);
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Subscription rule could not be added: " . mysql_error()), array("id" => 0));
	}
	$id = adesk_sql_insert_id();

	$c = subscriber_action_updateparts($id);
	return adesk_ajax_api_added(_a("Subscriber Action"), array("id" => $id, "actions" => $c));
}

function subscriber_action_update_post() {
	$ary = array(
		"campaignid" => $_POST["campaignid"],
		"linkid"     => $_POST["linkid"],
		"listid"     => $_POST["listid"],
		"type"       => $_POST["type"],
		"socmedia"   => $_POST["social"],
	);

	if (isset($_POST["name"])) {
		$ary["name"] = $_POST["name"];
	}

	$id = intval($_POST["id"]);
	$sql = adesk_sql_update("#subscriber_action", $ary, "id = '$id'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Subscription rule could not be updated."), array("id" => $id));
	}

	$c = subscriber_action_updateparts($id);
	return adesk_ajax_api_updated(_a("Subscriber Action"), array("id" => $id, "actions" => $c));
}

function subscriber_action_delete($id) {
	$id = intval($id);
	$admincond = '';
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		if ( $admin['id'] != 1 ) {
		}
	}
	adesk_sql_delete('#subscriber_action', "id = '$id' $admincond");
	return adesk_ajax_api_deleted(_a("Subscription rule"));
}

function subscriber_action_deleteparts($id) {
	$id = intval($id);
	adesk_sql_delete("#subscriber_action_part", "actionid = '$id'");
}

function subscriber_action_delete_multi($ids, $filter = 0) {
	if ( $ids == '_all' ) {
		$tmp = array();
		$so = new adesk_Select();
		$filter = intval($filter);
		if ($filter > 0) {
			$admin = adesk_admin_get();
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'subscriber_action'");
			$so->push($conds);
		}
		$all = subscriber_action_select_array($so);
		foreach ( $all as $v ) {
			$tmp[] = $v['id'];
		}
	} else {
		$tmp = array_map("intval", explode(",", $ids));
	}
	foreach ( $tmp as $id ) {
		$r = subscriber_action_delete($id);
	}
	return $r;
}

function subscriber_action_select_fordispatch($campid, $linkid) {
	$campid = intval($campid);
	$linkid = intval($linkid);

	$act = adesk_sql_select_row("");
}

function subscriber_action_dispatch($type, $sub, $list, $camp, $link, $socmedia = "") {
	# Carry out the different parts of the action.

	$campaignid = is_array($camp) ? $camp["id"] : 0;
	$linkid     = is_array($link) ? $link["id"] : 0;
	$listid     = is_array($list) ? $list["id"] : (int)$list;

	if ($type == "read")
		$linkid = 0;

	if ($listid > 0) {
		$acts = adesk_sql_select_array("
			SELECT
				*
			FROM
				#subscriber_action
			WHERE
				listid = '$listid'
			AND `type` = '$type'
		");
	} else {
		$clists   = adesk_sql_select_list("SELECT listid FROM #campaign_list WHERE campaignid = '$campaignid'");
		$cliststr = implode("','", $clists);
		$cond     = "";

		if ($socmedia != "") {
			$socmedia = adesk_sql_escape($socmedia);
			$cond     = " AND sa.socmedia = '$socmedia'";
		}

		$acts     = adesk_sql_select_array("
			SELECT
				*
			FROM
				#subscriber_action sa
			WHERE
			(
					(sa.campaignid = '$campaignid' AND sa.linkid = '$linkid')
				OR	(sa.campaignid = '$campaignid' AND sa.linkid = 0)
				OR	(sa.campaignid = 0 AND sa.linkid = 0 AND sa.listid IN ('$cliststr'))
			)
			AND	sa.type = '$type'
			$cond
		");
	}

	if (count($acts) == 0 || !is_array($acts))
		return;

	foreach ($acts as $act) {
		$parts = adesk_sql_select_array("
			SELECT
				*
			FROM
				#subscriber_action_part
			WHERE
				actionid = '$act[id]'
		");

		foreach ($parts as $part) {
			switch ( $part['act'] ) {
				case 'subscribe':
					if ($part["targetid"] == -1) { # Exclusion list
						exclusion_add($sub["email"], 0);
					} else {
						subscriber_list_add($sub, (int)$part['targetid']);
					}

					break;

				case 'unsubscribe':
					//subscriber_list_remove($sub, (int)$part['targetid']);
					if ($part["targetid"] == -1) {
						exclusion_remove($sub["email"], 0);
					} else {
						$subscriberid = $sub['id'];
						$listid = (int)$part['targetid'];
						$array = array(
							'status' => '2',
							'=udate' => 'NOW()',
						);
						adesk_sql_update("#subscriber_list",$array,"subscriberid='$subscriberid' AND listid='$listid'");
					}

					break;

				case 'send':
					$campaign2send = campaign_select_row((int)$part['targetid']);
					if ( $campaign2send ) campaign_send(null, $campaign2send, $sub, 'send');
					break;
				case 'update':
					$field = ($part["targetfield"]) ? $part["targetfield"] : $part["targetid"];
					$value = $part["param"];
					subscriber_update_info($sub, $field, $value);
					break;
			}
		}
	}
}

function subscriber_action_importv4($act) {
	$ins = array(
		"listid" => $act["list_id"],
		"type"   => $act["subscr_unsubscr_from"],
	);

	if ($ins["type"] == "subscribe")
		$lhs = _a("Subscribe to");
	else
		$lhs = _a("Unsubscribe from");

	if ($act["subscr_unsubscr_to"] == "subscribe")
		$rhs = _a("subscribes to");
	else
		$rhs = _a("unsubscribes from");

	$llist = adesk_sql_select_one("SELECT name FROM #list WHERE id = '$act[list_id]'");

	if (!$llist)
		return false;

	$rlist = adesk_sql_select_one("SELECT name FROM #list WHERE id = '$act[subscr_unsubscr_list_id]'");

	if (!$rlist)
		return false;

	$ins["name"] = sprintf(_a("%s \"%s\" when subscriber %s \"%s\""), $lhs, $llist, $rhs, $rlist);
	adesk_sql_insert("#subscriber_action", $ins);
	$actid = adesk_sql_insert_id();

	$ins = array(
		"actionid" => $actid,
		"act"      => $act["subscr_unsubscr_to"],
		"targetid" => $act["subscr_unsubscr_list_id"],
	);

	adesk_sql_insert("#subscriber_action_part", $ins);
	return true;
}

?>
