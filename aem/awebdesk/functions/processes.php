<?php

require_once awebdesk_classes("select.php");
require_once awebdesk_functions("process.php");

function adesk_processes_select_query(&$so) {
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		$uid = ( isset($admin['id']) && (int)$admin['id'] > 0 ? (int)$admin['id'] : 1 );
		$so->push("AND `userid` = '$uid'");
	}
	return adesk_process_select_query($so);
}

function adesk_processes_select_row($id) {
	$r = adesk_process_get($id);
	if ( !$r ) {
		return adesk_ajax_api_result(false, _a("Process not found."));
	}
	// user check
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		$uid = ( isset($admin['id']) && (int)$admin['id'] > 0 ? (int)$admin['id'] : 1 );
		if ( $r['userid'] != $uid ) {
			return adesk_ajax_api_result(false, _a("Process not found."));
		}
	}
	return adesk_process_info($r);
}

function adesk_processes_select_array($so = null, $ids = null) {
	if ( !$so ) $so = new adesk_Select();
	if ( $ids ) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$ids = array_diff(array_map('intval', $ids), array(0));
		if ( count($ids) > 0 ) {
			$idlist = implode(',', $ids);
			$so->push("AND `id` IN ($idlist)");
		}
	}
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		$uid = ( isset($admin['id']) && (int)$admin['id'] > 0 ? (int)$admin['id'] : 1 );
		$so->push("AND `userid` = '$uid'");
	}
	//return array_map('adesk_process_info', adesk_process_select_array($so));
	// old style, in case we wanna do something else here
	$r = adesk_process_select_array($so);
	foreach ( $r as $k => $v ) {
		$r[$k] = adesk_process_info($v);
	}
	return $r;
}

function adesk_processes_select_array_paginator($id, $sort, $offset, $limit, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'processes'");
		$so->push($conds);
	} else {
		$so->push("AND `completed` < `total`"); // active = DEFAULT
		$so->push("AND `ldate` IS NOT NULL"); // active, STALLED INCLUDED
		//$so->push("AND UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(`ldate`) < 4 * 60"); // active BUT NOT STALLED
	}

	$so->count();
	$total = (int)adesk_sql_select_one(adesk_processes_select_query($so));

	switch ($sort) {
		default:
		case '01':
			$so->orderby("`ldate` ASC"); break;
		case '01D':
			$so->orderby("`ldate` DESC"); break;
		case '02':
			$so->orderby("`ldate` ASC"); break;
		case '02D':
			$so->orderby("`ldate` DESC"); break;
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = adesk_processes_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function adesk_processes_filter_post() {
	$whitelist = array("action", "data");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "processes",
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

	// action filter
	if ( isset($_POST['action']) ) {
		if ( is_array($_POST['action']) ) {
			$actions = implode("', '", array_map('adesk_sql_escape', $_POST['action']));
			$ary['conds'] .= "AND `action` IN ('$actions') ";
		} else {
			if ( $_POST['action'] != '' ) {
				$action = adesk_sql_escape($_POST['action']);
				$ary['conds'] .= "AND `action` = '$action' ";
			}
		}
	}
	// status filter
    if( isset($_POST["status"]) ) 
    {
        if( is_array($_POST["status"]) ) 
        {
            $arr = array(  );
            foreach( $_POST["status"] as $s ) 
            {
                $se = adesk_sql_escape($s);
                if( $s == "done" ) 
                {
                    $str = "AND `completed` = `total` ";
                }
                else
                {
                    $str = "AND `completed` < `total` ";
                    if( $s == "active" ) 
                    {
                        $str .= "AND `ldate` IS NOT NULL ";
                    }
                    else
                    {
                        if( $s == "paused" ) 
                        {
                            $str .= "AND `ldate` IS NULL ";
                        }
                        else
                        {
                            if( $s == "stall" ) 
                            {
                                $str .= "AND UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(`ldate`) > 4 * 60 ";
                            }

                        }

                    }

                }

                $arr[] = $str;
            }
            if( count($arr) == 0 ) 
            {
                $arr[] = 1;
            }

            $ary["conds"] .= "AND ( " . implode(" ) OR ( ", $arr) . " ) ";
        }
        else
        {
            if( $_POST["status"] != "" ) 
            {
                if( $_POST["status"] == "done" ) 
                {
                    $ary["conds"] .= "AND `completed` = `total` ";
                }
                else
                {
                    $ary["conds"] .= "AND `completed` < `total` ";
                    if( $_POST["status"] == "active" ) 
                    {
                        $ary["conds"] .= "AND `ldate` IS NOT NULL ";
                    }
                    else
                    {
                        if( $_POST["status"] == "paused" ) 
                        {
                            $ary["conds"] .= "AND `ldate` IS NULL ";
                        }
                        else
                        {
                            if( $_POST["status"] == "stall" ) 
                            {
                                $ary["conds"] .= "AND UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(`ldate`) > 4 * 60 ";
                            }

                        }

                    }

                }

            }
            else
            {
                $ary["conds"] .= "AND 1 = 1";
            }

        }

    }

    if( $ary["conds"] == "" ) 
    {
        return array( "filterid" => 0 );
    }

	$conds_esc = adesk_sql_escape($ary["conds"]);
	$filterid = adesk_sql_select_one("
		SELECT
			id
		FROM
			#section_filter
		WHERE
			userid = '$ary[userid]'
		AND
			sectionid = 'processes'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}
function adesk_processes_update_post() {
	$id = (int)adesk_http_param('id');
	$ary = array();

	$restart = (int)adesk_http_param_exists('restart');
	$active = (int)adesk_http_param_exists('active');
	$schedule = (int)adesk_http_param_exists('schedule');
	$ldate = (string)adesk_http_param('ldate');
	$spawn = (int)adesk_http_param_exists('spawn');

	$process = adesk_process_get($id);
	if ( !$process ) {
		return adesk_ajax_api_result(false, _a("Process not found."));
	}

	if ( $process['remaining'] == 0 ) {
		// restart option
		if ( $restart ) {
			$ary['completed'] = 0;
			$ary['percentage'] = 0;
			if ( $schedule ) {
				$ary['ldate'] = $ldate;
			} else {
				// if they wanna spawn it, we gotta mark it stalled here first
				$ary['=ldate'] = ( $spawn ? 'FROM_UNIXTIME(UNIX_TIMESTAMP(NOW()) - 4 * 60 - 1)' : 'NOW()' );
			}
		}
	} else {
		// (de)activate option
		if ( $active ) {
			if ( $schedule ) {
				$ary['ldate'] = $ldate;
			} else {
				// if they wanna spawn it, we gotta mark it stalled here first
				$ary['=ldate'] = ( $spawn ? 'FROM_UNIXTIME(UNIX_TIMESTAMP(NOW()) - 4 * 60 - 1)' : 'NOW()' );
			}
		} else {
			$ary['=ldate'] = 'NULL';
		}
	}

	$sql = ( count($ary) > 0 ? adesk_sql_update("#process", $ary, "id = '$id'") : true );
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Process could not be updated."));
	}

	// run ihook if actions need something to continue/stop
	adesk_ihook('adesk_process_update', $process, $_POST);

	// spawn this process if requested
	if ( $active and !$schedule and $spawn ) {
		adesk_process_spawn(array('id' => $id, 'stall' => 4 * 60 + 1));
	}

	return adesk_ajax_api_updated(_a("Process"));
}

function adesk_processes_delete($id) {
	adesk_process_remove((int)$id);
	return adesk_ajax_api_deleted(_a("Process"));
}

function adesk_processes_delete_multi($ids, $filter = 0) {
	if ( $ids == '_all' ) {
		$tmp = array();
		$so = new adesk_Select();
		$filter = intval($filter);
		if ($filter > 0) {
			$admin = adesk_admin_get();
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'processes'");
			$so->push($conds);
		} else {
			$so->push("AND `completed` < `total`"); // active = DEFAULT
			$so->push("AND `ldate` IS NOT NULL"); // active, STALLED INCLUDED
			//$so->push("AND UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(`ldate`) < 4 * 60"); // active BUT NOT STALLED
		}
		$all = adesk_processes_select_array($so);
		foreach ( $all as $v ) {
			$tmp[] = $v['id'];
		}
	} else {
		$tmp = array_map("intval", explode(",", $ids));
	}
	foreach ( $tmp as $id ) {
		$r = adesk_processes_delete($id);
	}
	return $r;
}


function adesk_processes_trigger($id, $action) {
	$whitelist = array('run', 'pause', 'resume', 'restart');
	if ( !in_array($action, $whitelist) ) {
		return adesk_ajax_api_result(false, _a("Action could not be recognized."));
	}
	// simulate post array for update
    $_POST = array( "id" => $id );
	// add vars based on action
    if( $action == "run" ) 
    {
        $_POST["active"] = 1;
        $_POST["spawn"] = 1;
    }
    else
    {
        if( $action == "pause" ) 
        {
			//$_POST["active"] = 0;
              //  $_POST["spawn"] = 0;
        }
        else
        {
            if( $action == "resume" ) 
            {
                $_POST["active"] = 1;
                $_POST["spawn"] = 1;
            }
            else
            {
                if( $action == "restart" ) 
                {
                    $_POST["restart"] = 1;
                    $_POST["spawn"] = 1;
                }

            }

        }

    }
	return adesk_processes_update_post();
}

?>