<?php

require_once awebdesk_classes("select.php");
require_once awebdesk_functions("log.php");

function adesk_cron_select_query(&$so) {
	return $so->query("
		SELECT
			c.*
		FROM
			#cron c
		WHERE
		[...]
	");
}

function adesk_cron_select_row($id) {
	$id = intval($id);
	if ( !$id ) return false;
	$so = new adesk_Select;
	$so->push("AND c.id = '$id'");

	$r = adesk_sql_select_row(adesk_cron_select_query($so), array("lastrun"));
	// prepare for edit
	if ( $r ) $r = adesk_cron_prepare($r);
	return $r;
}

function adesk_cron_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND id IN ('$ids')");
	}
	$r = adesk_sql_select_array(adesk_cron_select_query($so), array("lastrun"));
	foreach ( $r as $k => $v ) {
		$r[$k] = adesk_cron_prepare($v);
	}
	return $r;
}

function adesk_cron_select_array_paginator($id, $sort, $offset, $limit, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'cron'");
		$so->push($conds);
	}

	$so->count();
	$total = (int)adesk_sql_select_one(adesk_cron_select_query($so));

	switch ($sort) {
		default:
		case '01':
			$so->orderby("name ASC"); break;
		case '01D':
			$so->orderby("name DESC"); break;
		case '02':
			$so->orderby("descript ASC"); break;
		case '02D':
			$so->orderby("descript DESC"); break;
		case '03':
			$so->orderby("lastrun ASC"); break;
		case '03D':
			$so->orderby("lastrun DESC"); break;
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = adesk_cron_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function adesk_cron_filter_post() {
	$whitelist = array("stringid", "name", "descript", "filename");

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "cron",
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
		$ary["conds"] = "AND ($conds)";
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
			sectionid = 'cron'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

function adesk_cron_insert_post() {
	$ary = adesk_cron_prepare_post(0);

	// perform checks here
	if ( $ary['name'] == '' ) {
		return adesk_ajax_api_result(false, _a("Cron Job Name not provided."));
	}
	if ( $ary['filename'] == '' ) {
		return adesk_ajax_api_result(false, _a("Cron Job Field Name / URL not provided."));
	}

	$sql = adesk_sql_insert("#cron", $ary);
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Cron Job could not be added."));
	}
	$id = adesk_sql_insert_id();

	return adesk_ajax_api_added(_a("Cron Job"));
}

function adesk_cron_update_post() {
	$id = (int)adesk_http_param('id');
	$ary = adesk_cron_prepare_post($id);

	// perform checks here
	if ( $ary['filename'] == '' ) {
		return adesk_ajax_api_result(false, _a("Cron Job Field Name / URL not provided."));
	}

	$sql = adesk_sql_update("#cron", $ary, "id = '$id'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Cron Job could not be updated."));
	}

	return adesk_ajax_api_updated(_a("Cron Job"));
}

function adesk_cron_delete($id) {
	$id = intval($id);
	if ( $id < 10 ) return adesk_ajax_api_result(false, _a('This Cron Job cannot be deleted. You can disable it instead.'));
	adesk_sql_query("DELETE FROM #cron WHERE id = '$id'");
	adesk_sql_query("DELETE FROM #cron_log WHERE `cronid` = '$id'");
	return adesk_ajax_api_deleted(_a("Cron Job"));
}

function adesk_cron_delete_multi($ids, $filter = 0) {
	if ( $ids == '_all' ) {
		$tmp = array();
		$so = new adesk_Select();
		$filter = intval($filter);
		if ($filter > 0) {
			$admin = adesk_admin_get();
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'cron'");
			$so->push($conds);
		}
		$all = adesk_cron_select_array($so);
		foreach ( $all as $v ) {
			$tmp[] = $v['id'];
		}
	} else {
		$tmp = array_map("intval", explode(",", $ids));
	}
	foreach ( $tmp as $id ) {
		$r = adesk_cron_delete($id);
	}
	return $r;
}


function adesk_cron_prepare_post($id) {
	// handle 'minute' fields
	$minuteOperator = adesk_http_param('minuteoperator');
	if ( $minuteOperator != 'every' ) $minuteOperator = 'at';
	if ( $minuteOperator == 'every' ) {
		$minute = array((int)adesk_http_param('minute1'));
	} else {
		$minute = array(
			(int)adesk_http_param('minute1'),
			(int)adesk_http_param('minute2'),
			(int)adesk_http_param('minute3'),
			(int)adesk_http_param('minute4'),
			(int)adesk_http_param('minute5'),
			(int)adesk_http_param('minute6'),
		);
		$minute = array_diff(array_unique($minute), array('-'/*, '-2'*/));
		sort($minute);
		if ( $minute[0] == -1 ) $minute = array(-1);
	}
	// handle 'hour' fields
	$hour = (int)adesk_http_param('hour');
	$hourOperator = adesk_http_param('houroperator');
	if ( $hourOperator != 'every' ) $hourOperator = 'at';
	if ( $hourOperator == 'every' ) $hour += 100;
	// create an insert/update array
	$ary = array(
		'active' => (int)adesk_http_param_exists('active'),
		'filename' => (string)adesk_http_param('filename'),
		'loglevel' => (int)adesk_http_param_exists('loglevel'),
		'weekday' => (int)adesk_http_param('weekday'),
		'day' => (int)adesk_http_param('day'),
		'hour' => $hour,
		'minute' => serialize($minute),
	);
	// handle 'day' field
	if ( $ary['weekday'] != -1 ) $ary['day'] = -2;
	// handle insert-only vars
	if ( $id == 0 ) {
		$ary['name'] = (string)adesk_http_param('name');
		$ary['descript'] = (string)adesk_http_param('descript');
		$ary['stringid'] = trim((string)adesk_http_param('stringid'));
		if ( $ary['stringid'] == '' ) $ary['stringid'] = adesk_str_urlsafe($ary['name']);
		$ary['stringid'] = adesk_sql_find_next_index('#cron', 'stringid', adesk_str_urlsafe($ary['stringid']), '');
	}
	return $ary;
}


// switch cron job's status on/off
function adesk_cron_status($id, $state) {
	$id = (int)$id;
	$state = (int)(bool)$state;
	// update the field
	$sql = adesk_sql_update_one("#cron", 'active', $state, "id = '$id'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Cron Job could not be updated."));
	}
	// return result
	return adesk_ajax_api_updated(_a("Cron Job"));
}





// execute a cron job
function adesk_cron_run($id = 0, $debug = false) {
	$crons = array();
	if ( $id = (int)$id ) {
		$r = adesk_cron_select_row($id);
		if ( $r and $r['active'] ) $crons[] = $r;
	} else {
		$so = new adesk_Select();
		$so->push("AND `active` = 1");
		$r = adesk_sql_select_array(adesk_cron_select_query($so));
		foreach ( $r as $k => $cron ) {
			$r[$k] = $cron = adesk_cron_prepare($cron);
			// check last run time here
			if ( $cron['shouldrun'] > $cron['lastrun'] ) {
				$crons[] = $cron;
			} else {
				// already ran since the last time it should have run
			}
		}
	}
	if ( $debug and count($crons) == 0 ) {
		adesk_flush('No crons are scheduled to run at this moment (they all ran recently and on time).');
	}
	// run requested crons
	foreach ( $crons as $cron ) {
		// save this cron run time
		adesk_sql_update_one('#cron', '=lastrun', "NOW()", "id = '$cron[id]'");
		// print out cron info
		if ( $debug ) {
			adesk_flush(print_r($cron, 1));
		}
		$ran = false;
		// spawn cron job as a separate process
		if ( $cron['url'] ) {
			// url
			//dbg($url, 1);dbg(adesk_http_get($cron['script']));
			$ran = adesk_http_spawn($cron['script']);
			if ( !$ran and function_exists('curl_init') ) {
				// Set up and execute the curl process
				$curl_handle = curl_init();
				curl_setopt($curl_handle, CURLOPT_URL, $cron['script']);
				curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
				curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($curl_handle, CURLOPT_HEADER, 0);
				//curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, 1); // can't work if safe_mode is on or open_basedir is set
				$ran = curl_exec($curl_handle);
				curl_close($curl_handle);
				if ( adesk_str_instr('404', $ran) or adesk_str_instr('page not found', strtolower($ran) ) ) {
					$ran = false;
				}
				if ( $debug ) adesk_flush("\n\n$cron[script] curled:\n-\n$ran\n-");//return $str;
			}
			if ( !$ran ) {
				$ran = adesk_http_get($cron['script']);
				if ( adesk_str_instr('404', $ran) or adesk_str_instr('page not found', strtolower($ran) ) ) {
					$ran = false;
				}
				if ( $debug ) adesk_flush("\n\n$cron[script] fetched:\n-\n$ran\n-");//return $str;
			}
			if ( !$ran and function_exists('proc_open') and strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN' ) {
				$spawnres = !adesk_php_spawn($cron['exec'], $debug);
				$ran = ( $spawnres['res'] and $spawnres['err'] != '' );
				if ( $debug ) adesk_flush("\n\n$cron[script] spawned:\n-\n$ran\n-");//return $str;
			}
			// if local file, finally try include
			if ( !$ran and substr($cron['script'], 0, strlen(adesk_site_plink())) == adesk_site_plink() ) {
				// make local path out of url
				$inc = adesk_base(trim(substr($cron['script'], strlen(adesk_site_plink())), '/'));
				if ( file_exists($inc) ) {
					@ob_start();
					include($inc);
					@ob_end_clean();
					$ran = true;
				}
			}
		} else {
			// local file
			$ran = false;
			if ( function_exists('proc_open') ) {
				$spawnres = adesk_php_spawn($cron['script'], $debug);
				$ran = ( $spawnres['res'] and $spawnres['err'] != '' );
			}
			// if local file, try include as well
			if ( !$ran and file_exists($cron['script']) ) {
				include($cron['script']);
				$ran = true;
			}
			if ( !$ran ) {
				adesk_flush('CLI cron jobs cannot run on this server.');
			}
		}
	}
}

function adesk_cron_lastrun($line) {
	require_once(awebdesk_classes('cronparser.php'));
	// start new cron parser instance
	$cron = new CronParser();
	if ( !$cron->calcLastRan($line) ) return null;
	$lastRan = $cron->getLastRanUnix();
	return date('Y-m-d H:i:s', $lastRan);
}


function adesk_cron_prepare($cron) {
	if ( isset($cron['prepared']) ) return $cron;
	$cron['prepared'] = 1;
	$cron['script'] = $cron['filename'];
	if ( substr($cron['script'], 0, 2) == './' ) {
		if ( 0 and !defined('adesk_CRON') and function_exists('proc_open') ) { // don't do proc_open for the time being
			$cron['script'] = adesk_base(substr($cron['script'], 2));
		} else {
			$cron['script'] = adesk_site_plink(substr($cron['script'], 2));
		}
	}
	$filename = strtolower($cron['script']);
	$cron['url'] = ( adesk_str_instr('http://', $filename) or adesk_str_instr('https://', $filename) );
	if ( strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ) {
		$cron['cmd'] = ( $cron['url'] ? 'wget %s > /dev/null' : 'C:\PHP\php.exe %s' );
	} else {
		$cron['cmd'] = ( $cron['url'] ? 'wget %s > /dev/null' : '/usr/local/bin/php %s' );
	}
	$cron['minute'] = unserialize($cron['minute']);
	$cron['minutelist'] = implode(',', $cron['minute']);
	$cron['line'] = adesk_cron_line($cron);
	$cron['exec'] = sprintf($cron['cmd'], $cron['script']);
	$cron['command'] = $cron['line'] . ' ' . $cron['exec'];
	$cron['shouldrun'] = adesk_cron_lastrun($cron['line']);
	return $cron;
}


# .---------------- minute (0 - 59)
# |  .------------- hour (0 - 23)
# |  |  .---------- day of month (1 - 31)
# |  |  |  .------- month (1 - 12) OR jan,feb,mar,apr ...
# |  |  |  |  .---- day of week (0 - 6) (Sunday=0 or 7)  OR sun,mon,tue,wed,thu,fri,sat
# |  |  |  |  |
# *  *  *  *  *  command to be executed
function adesk_cron_line($cron) {
	// minute
	if ( !is_array($cron['minute']) ) $cron['minute'] = unserialize($cron['minute']);
	if ( count($cron['minute']) == 1 ) {
		$minute = '*';
		if ( $cron['minute'][0] > 1 ) {
			$minute .= '/' . $cron['minute'][0];
		}
	} else {
		$minute = implode(',', array_diff($cron['minute'], array(-2)));
	}
	// hour
	$hour = ( $cron['hour'] == -1 ? '*' : ( $cron['hour'] > 100 ? '*/' . ($cron['hour'] - 100) : $cron['hour'] ) );
	// day
	$day = ( ( $cron['day'] == -1 or $cron['weekday'] != -1 ) ? '*' : $cron['day'] );
	// month
	$month = '*';
	// week
	$week = ( $cron['weekday'] == -1 ? '*' : $cron['weekday'] );
	return "$minute $hour $day $month $week";
}








// initiating cron
function adesk_cron_monitor_start($stringid) {
	adesk_php_global_set('adesk_cron_monitor_name', $stringid);
	adesk_php_global_set('adesk_cron_monitor_error', '');
	$s = adesk_sql_escape($stringid);
	$so = new adesk_Select;
	$so->push("AND c.stringid = '$s'");
	$cron = adesk_sql_select_row(adesk_cron_select_query($so));
	$r = 0;
	if ( !$cron ) {
		adesk_flush(_a('This cron job is not approved by the administrator.'));
		exit;
	}
	// prepare for edit
	$cron = adesk_cron_prepare($cron);
	adesk_php_global_set('adesk_cron_monitor_row', $cron);
	if ( !$cron['active'] ) {
		if ( !( isset($_GET['force']) and $_GET['force'] ) ) {
			adesk_flush(_a('This cron job is disabled by the administrator.'));
			exit;
		}
	}
	if ( $cron['loglevel'] ) {
		// check if it's already running
		$found = (int)adesk_sql_select_one(
			'=COUNT(*)',
			'#cron_log',
			//"relid = '$cron[id]' AND sdate > SUBDATE(NOW(), INTERVAL 2 MINUTE) AND edate IS NULL"
			"relid = '$cron[id]' AND sdate > SUBDATE(NOW(), INTERVAL 2 MINUTE)"
		);
		if ( $found ) {
			adesk_flush(_a('This cron job is already running.'));
			exit;
		}
		// start
		$insert = array(
			'id'      => 0,
			'relid'  => $cron['id'],
			'file'    => $stringid,
			'=sdate' => 'NOW()',
			'=edate' => 'NULL',
			'=errors'  => 'NULL',
		);
		$r = ( adesk_sql_insert('#cron_log', $insert) ? adesk_sql_insert_id() : 0 );
	}
	adesk_php_global_set('adesk_cron_monitor_id', $r);
	return $r;
}

// stopping cron
function adesk_cron_monitor_stop() {
	$id = (int)adesk_php_global_get('adesk_cron_monitor_id');
	$file = adesk_php_global_get('adesk_cron_monitor_name');
	$error = adesk_php_global_get('adesk_cron_monitor_error');
	if ( $id > 0 ) {
		// stop
		$update = array(
			'=edate' => 'NOW()',
			'errors' => $error,
		);
		adesk_sql_update('#cron_log', $update, "id = '$id'");
	}
	adesk_cron_monitor_cleanup($file);
	return $id;
}

function adesk_cron_monitor_cleanup($file) {
	$cron = adesk_sql_escape($file);
	adesk_sql_delete('#cron_log', "`file` = '$file' AND `sdate` < SUBDATE(NOW(), INTERVAL 7 DAY)");
}

function adesk_cron_log($id) {
	$r = array(
		'cnt' => 0,
		'log' => array()
	);
	//$admin = adesk_admin_get();
	if ( $id = (int)$id ) {
		$r['log'] = adesk_sql_select_array("SELECT * FROM #cron_log WHERE relid = '$id' ORDER BY sdate DESC", array("sdate","edate"));
		$r['cnt'] = count($r['log']);
	}
	return $r;
}

?>
