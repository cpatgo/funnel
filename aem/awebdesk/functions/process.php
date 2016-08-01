<?php

function adesk_process_select_query(&$so) {
	return $so->query("
		SELECT
			*,
			`total` - `completed` AS remaining,
			IF(`ldate` IS NULL, 1, UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(`ldate`)) AS stall
		FROM
			`#process`
		WHERE
		[...]
	");
}


function adesk_process_select_count(&$so) {
	$so->count();
	return adesk_sql_select_one(adesk_process_select_query($so));
}

function adesk_process_select_array($var = null) {
	if (!is_object($var))
		$so = new adesk_Select;
	else
		$so = $var;

	if ($var !== null && !is_object($var)) {
		if ( !is_array($var) ) $var = explode(",", $var);
		$ids = implode("', '", array_map("intval", $var));
		$so->push("AND `id` IN ('$ids')");
	}
	//dbg(adesk_prefix_replace(adesk_process_select_query($so)));
	return adesk_sql_select_array(adesk_process_select_query($so), array('cdate', 'ldate'));
}

function adesk_process_create($action, $total, $data = null, $spawn = true, $sdate = null) {
	$admin = adesk_admin_get();
	$uid = ( $admin['id'] > 0 ? $admin['id'] : 1 );
	// check for existence
	if ( !is_null($data) or $spawn ) {
		$so = new adesk_Select;
		$actionEsc = adesk_sql_escape($action);
		$dataEsc = adesk_sql_escape(serialize($data));
		$so->push("AND `userid` = '$uid'");
		$so->push("AND `action` = '$actionEsc'");
		$so->push("AND `data` = '$dataEsc'");
		if ( !is_null($sdate) ) {
			$sdateEsc = adesk_sql_escape($sdate);
			$so->push("AND `ldate` = '$sdateEsc'");
		}
		$found = adesk_process_awaiting($so, true);
		if ( $found > 0 ) {
			list($process) = adesk_process_awaiting($so);
			if ( $spawn ) adesk_process_spawn($process);
			return $process['id'];
		}
	}
	// create it in db
	$arr = array(
		'id' => 0,
		'userid' => $uid,
		'rnd' => mt_rand(1201000, 9871000),
		'action' => $action,
		'total' => $total,
		'completed' => 0,
		'percentage' => 0,
		'data' => serialize($data),
		'=cdate' => 'NOW()',
	);
	$datefields = array(/*'cdate', 'ldate'*/);
	if ( !is_null($sdate) ) {
		if ( $sdate == 'NULL' ) { // paused
			$arr['=ldate'] = 'NULL';
		} else {
			$arr['ldate'] = $sdate;
			$datefields = array('ldate');
		}
	} else {
		if ( $spawn ) {
			$arr['ldate'] = '0000-00-00 00:00:00'; // hack
			//$arr['=ldate'] = 'SUBDATE(NOW(), INTERVAL 5 MINUTE)'; // hack
		} else {
			$arr['=ldate'] = 'NOW()';
		}
	}
	$done = adesk_sql_insert('#process', $arr, $datefields) or die(adesk_sql_error());
	if ( !$done ) return false;
	// grab newly created process id
	$id = adesk_sql_insert_id();
	// spawn separate process
	if ( $spawn /*and isset($arr['=ldate']) and $arr['=ldate'] != 'NULL'*/ ) {
		adesk_process_spawn(array('id' => $id, 'stall' => 5 * 60));
	}
	return $id;
}

function adesk_process_update($id, $increment = true) {
	$id = (int)$id;
	$arr = array('=ldate' => 'NOW()');
	if ( $increment ) {
		$arr['=completed'] = '`completed` + 1';
		$arr['=percentage'] = '`completed` / `total` * 100';
	}
/*
	$filename = adesk_base('cache/process-' . $id . '.txt');
    if ($handle = fopen($filename, 'a')) {
	    $somecontent = str_repeat("\n", 3) . "PROCESS UPDATE:\n" . print_r(debug_backtrace(), 1);
    	fwrite($handle, $somecontent);
	    fclose($handle);
    }
*/
	return adesk_sql_update('#process', $arr, "`id` = '$id'");
}

function adesk_process_setdata($id, $data = null) {
	$id = (int)$id;
	return adesk_sql_update_one('#process', 'data', serialize($data), "`id` = '$id'");
}

function adesk_process_pickup($id) {
	$process = adesk_process_get($id);
	if ( !$process ) return;
	// if stalled for more than 4 minutes
	if ( $process['stall'] && $process['stall'] > 4 * 60 ) {
		$process['data'] = @adesk_str_unserialize($process['data']);
		$r = adesk_ihook('adesk_process_handler', $process); // this one might stall
		// but if it didn't, and returned result
		if ( isset($r['succeeded']) ) {
			// and result is false
			if ( !$r['succeeded'] ) {
				// should we remove this process?
				//adesk_process_remove($id);
			}
		}
	}
}

function adesk_process_info(&$process) {
	$process['name'] = sprintf(_a('Unknown Process (%s)'), $process['action']);
	$process['descript'] = '';
	if ( !isset($process['data']) ) return $process;
	$process['data'] = @unserialize($process['data']);
	if ( !$process['data'] ) return $process;
	$r = adesk_ihook('adesk_process_info', $process);
	if ( !$r ) return $process;
	if ( isset($r['name']) ) $process['name'] = $r['name'];
	if ( isset($r['descript']) ) $process['descript'] = $r['descript'];
	return $process;
}

function adesk_process_end($id) {
	$id = (int)$id;
	adesk_sql_query("UPDATE #process SET completed = total, percentage = 100 WHERE id = '$id'");
}

function adesk_process_spawn($process) {
	$debug = (bool)adesk_http_param('debugspawn');
	$url = adesk_site_alink('process.php?id=' . $process['id']);
	// if stalled for more than 4 minutes
	if ( !$process['stall'] or $process['stall'] < 4 * 60 ) return;
	// respawn old process as a separate process
	//dbg($url);
	//dbg(adesk_http_get($url));

	$ran = adesk_http_spawn($url);
	if ( $debug ) adesk_flush("\n\n$url spawned:\n-\n$ran\n-");

	if ( !$ran and isset($GLOBALS['__tryspawnonly']) ) return;

	if ( !$ran and function_exists('curl_init') ) {
		// Set up and execute the curl process
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $url);
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
		if ( $debug ) adesk_flush("\n\n$url curled:\n-\n$ran\n-");
	}
	if ( !$ran ) {
		$ran = adesk_http_get($url);
		if ( adesk_str_instr('404', $ran) or adesk_str_instr('page not found', strtolower($ran) ) ) {
			$ran = false;
		}
		if ( $debug ) adesk_flush("\n\n$url fetched:\n-\n$ran\n-");

	}
	if ( !$ran and function_exists('proc_open') and strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN' ) {
		$spawnres = !adesk_php_spawn(sprintf('wget %s > /dev/null', $url), $debug);
		$ran = ( $spawnres['res'] and $spawnres['err'] != '' );
		if ( $debug ) adesk_flush("\n\n$url spawned:\n-\n$ran\n-");
	}
	// if local file, finally try include
	if ( !$ran ) {
		adesk_process_pickup($process['id']); // this runs process
		$ran = true;
	}
}

function adesk_process_respawn($id = null, $debug = false) {
	$so = new adesk_Select;
	if ( !is_null($id) && $id = (int)$id ) {
		$so->push("AND `id` = '$id'");
	} else {
		$so->limit("0, 5");
	}

	if (isset($GLOBALS["_hosted_account"]) && $_SESSION[$GLOBALS["domain"]]["down4"] == "reverify") {
		$so->push("AND action = 'reverify'");
	}

	$processes = adesk_process_awaiting($so);
	if ( $debug and count($processes) == 0 ) {
		adesk_flush('There are no stalled processes at this time.');
	}
	foreach ( $processes as $process ) {
		// print out process info
		if ( $debug ) {
			adesk_flush(print_r($process, 1));
		}
		adesk_process_spawn($process);
	}
}

function adesk_process_awaiting($so = null, $count = false) {
	if ( is_null($so) ) $so = new adesk_Select;

	$so->push("AND `completed` < `total`");
	$so->push("AND `ldate` IS NOT NULL");
	if ( $count ) {
		return (int)adesk_process_select_count($so);
	}
	$so->orderby("`ldate` ASC");
	return adesk_process_select_array($so);
}

function adesk_process_get($id) {
	$id = (int)$id;
	$so = new adesk_Select;
	$so->push("AND `id` = '$id'");
	return adesk_sql_select_row(adesk_process_select_query($so), array('cdate', 'ldate'));
}

function adesk_process_cleanup() {
	return adesk_sql_delete('#process', "`completed` = `total` AND `ldate` < SUBDATE(NOW(), INTERVAL 10 DAY)");
}

function adesk_process_remove($id) {
	$id = (int)$id;
	return adesk_sql_delete('#process', "`id` = '$id'");
}

function adesk_progressbar_update($id, $spawn = false) {
	$process = adesk_process_get($id);
	if ( !$process ) {
		return adesk_ajax_api_result(false, _a('Process not found.'));
	}
	if ( $process['remaining'] == 0 ) {
		$process['percentage'] = 100; // round it
	} elseif ( $spawn ) {
		adesk_process_spawn($process);
	}
	unset($process['data']);
	return $process;
}

?>
