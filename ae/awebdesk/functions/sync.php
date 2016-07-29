<?php

require_once(awebdesk_functions('sync_interface.php'));

function adesk_sync_get_one($id, $table = 'sync') {
	$r = adesk_sql_select_row("SELECT * FROM #{$table} WHERE id = '$id'");
	$types = adesk_sync_database_types();
	if ( !isset($row['db_type']) or !isset($types[$row['db_type']]) ) {
		$row['db_type'] = key($types); // mysql
	}
	return $r;
}

function adesk_sync_get_all($sort, $table = 'sync') {
	$rels = adesk_ihook('adesk_sync_relations');
	$r = array();
	$types = adesk_sync_database_types();
	$sql = adesk_sql_query("SELECT * FROM #{$table} ORDER BY $sort");
	while ( $row = adesk_sql_fetch_assoc($sql, array('tstamp')) ) {
		if ( !isset($row['db_type']) or !isset($types[$row['db_type']]) ) {
			$row['db_type'] = key($types); // mysql
		}
		if (array_key_exists($row['relid'],$rels)) $r[$row['id']] = $row;
	}
	return $r;
}

function adesk_sync_list($sort) {
	// check for privileges first!
	if ( adesk_ihook_exists('adesk_sync_permission') ) {
		if ( !adesk_ihook('adesk_sync_permission') ) {
			return array('rows' => array());
		}
	}
	return array('rows' => adesk_sync_get_all(adesk_sync_sort($sort)));
}

function adesk_sync_prepare($data) {
	// break fields
	$from = explode('||', $data['field_from']);
	$to   = explode('||', $data['field_to']);
	$data['fieldslist'] = array();
	// slash fields
	foreach ( $to as $k => $v ) {
		$tofield   = trim($v);
		$fromfield = trim($from[$k]);
		if ( $tofield != '' and $fromfield != '' ) {
			$data['fieldslist'][$tofield] = $fromfield;
		}
	}
	// break rules
	$data['ruleslist'] = array();
	$data['is_custom'] = ( $data['db_table'] == '' );
	if ( !$data['is_custom'] ) {
		// slash rules
		$rules = explode('||B||', $data['rules']);
		foreach ( $rules as $r ) {
			if ( trim($r) != '' ) $data['ruleslist'][] = trim($r);
		}
		// construct a query
		$fl = array();
		foreach ( $data['fieldslist'] as $k => $v ) {
			if ( !adesk_str_instr('(', $v) ) {
				$fl[$k] = adesk_sync_wrap($v, $data);
			} else {
				$fl[$k] = $v;
			}
		}
		$fields = implode(', ', $fl);
		$table = adesk_sync_wrap($data['db_table'], $data);
		$cond = ( count($data['ruleslist']) > 0 ? implode(' AND ', $data['ruleslist']) : '1 = 1' );
		$data['query'] = "SELECT\n\t$fields\nFROM\n\t$table\nWHERE\n\t$cond";
		if ( $data['db_type'] != 'mssql' ) {
			$data['query4count'] = "SELECT\n\tCOUNT(*)\nFROM\n\t$table\nWHERE\n\t$cond";
		}
	} else {
		// custom query
		$data['query'] = $data['rules'];
	}
	// last run var
	if ( !isset($data['tstamp']) or !adesk_str_is_datetime($data['tstamp']) ) {
		$data['tstamp'] = '1970-01-01 00:00:00';
	}
	list($lastrundate, $lastruntime) = explode(' ', $data['tstamp']);

	$data['query'] = str_replace('%SYNC_LASTRUN_DATETIME%', $data['tstamp'], $data['query']);
	$data['query'] = str_replace('%SYNC_LASTRUN_DATE', $lastrundate, $data['query']);
	$data['query'] = str_replace('%SYNC_LASTRUN_TIME%', $lastruntime, $data['query']);
	if(isset($data['query4count'])) {
		$data['query4count'] = str_replace('%SYNC_LASTRUN_DATETIME%', $data['tstamp'], $data['query4count']);
		$data['query4count'] = str_replace('%SYNC_LASTRUN_DATE%', $lastrundate, $data['query4count']);
		$data['query4count'] = str_replace('%SYNC_LASTRUN_TIME%', $lastruntime, $data['query4count']);
	}
	return $data;
}


function adesk_sync_add() {
	// prepare data
	$values = array();
	adesk_sync_prepare_post($values);
	if ( !isset($values['sync_name']) ) return 0;
	$values['id'] = 0;
	$values['userid'] = $GLOBALS['admin']['id'];
	// cleared, do insert
	$r = (int)adesk_sql_insert('#' . $GLOBALS['adesk_sync_table'], $values);
	$syncID = 0;
	if ( $r ) {
		// collect new ID
		$syncID = adesk_sql_insert_id();
		// run any aftersave hooks
		adesk_ihook('adesk_sync_after_add', $syncID);
	}
	return $syncID;
}

function adesk_sync_edit() {
	// get sync for editing
	$syncID = (int)adesk_http_param('id');
	$sync = adesk_sync_get_one($syncID, $GLOBALS['adesk_sync_table']);
	if ( !$sync ) return 0;
	// prepare data
	$values = array();
	adesk_sync_prepare_post($values);
	if ( !isset($values['sync_name']) ) return 0;
	// cleared, do update
	$r = adesk_sql_update('#' . $GLOBALS['adesk_sync_table'], $values, "`id` = '$syncID'");
	if ( $r ) {
		// run any aftersave hooks
		adesk_ihook('adesk_sync_after_edit', $syncID);
	}
	return $r;
}

function adesk_sync_prepare_post(&$values) {
	// prepare data
	//$values = array();
	// references/destinations
	$values['relid'] = (int)adesk_http_param('relid');
	if ( $values['relid'] == 0 ) return;
	$dest = adesk_http_param('dest');
	if ( !is_array($dest) ) return;
	$values['field_from'] = $values['field_to'] = '';
	foreach ( $dest as $k => $v ) {
		if ( $v != 'DNI' ) {
			$values['field_from'] .= $k . '||';
			$values['field_to']   .= $v . '||';
		}
	}
	if ( $values['field_from'] == '' ) return;
	// db info
	$values['db_host'] = trim((string)adesk_http_param('db_host'));
	$values['db_name'] = trim((string)adesk_http_param('db_name'));
	$values['db_user'] = trim((string)adesk_http_param('db_user'));
	$values['db_pass'] = base64_encode(trim((string)adesk_http_param('db_pass')));
	$values['db_name'] = trim((string)adesk_http_param('db_name'));
	$values['db_table'] = trim((string)adesk_http_param('db_table'));
	$values['db_type'] = trim((string)adesk_http_param('db_type'));
	$values['sourcecharset'] = trim((string)adesk_http_param('sourcecharset'));
	// if table is empty, we are using a custom query
	if ( $values['db_table'] == '' ) {
		// custom query
		$query = trim((string)adesk_http_param('db_query'));
		if ( $query == '' ) return;
		$values['rules'] = $query;
	} else {
		// rules
		$values['rules'] = '';
		$rules = adesk_http_param('rules');
		if ( !is_array($rules) ) $rules = array();
		foreach ( $rules as $rule ) {
			$values['rules'] .= $rule . '||B||';
		}
	}
	// options
	$values['delete_all'] = (int)adesk_http_param_exists('sync_option_delete_all');
	// sync name (we use this to check if array is prepared properly)
	$values['sync_name'] = trim((string)adesk_http_param('sync_name'));
	//$values['=tstamp'] = 'NULL';
	// run hooks
	if ( adesk_ihook_exists('adesk_sync_prepare_post') ) {
		$values = adesk_ihook('adesk_sync_prepare_post', $values);
	}
}

function adesk_sync_delete($syncID) {
	// do actual deleting
	$sync_ids = explode(",", $syncID);
	$sync_ids = implode("','", $sync_ids);
	$r = adesk_sql_query("DELETE FROM #{$GLOBALS['adesk_sync_table']} WHERE `id` IN ('$sync_ids')"); // sync
	if ( $r ) {
		// run any aftersave hooks
		adesk_ihook('adesk_sync_after_delete', $syncID);
		// return succeeded
		return adesk_ajax_api_deleted(_a("Sync Job"));
	}
	// return failed
	return adesk_ajax_api_result(false, _a("Sync Job could not be deleted."));
}

function adesk_sync_new($relid = 0, $table = 'sync') {
	$r = adesk_sql_default_row('#' . $table);
	$r['relid'] = $relid;
	$r['sentresponders'] = '';
	return $r;
}


// convert requested sort to ORDER BY clause
function adesk_sync_sort($sort = null) {
	if ( is_null($sort) )
		$sort = ( isset($_GET['syncsort']) ? $_GET['syncsort'] : ( isset($_SESSION['syncsort']) ? $_SESSION['syncsort'] : '' ) );
	if ( $sort == "01" ) {
		return "sync_name ASC";
	} elseif ( $sort == "01D" ) {
		return "sync_name DESC";
	} elseif ( $sort == "02" ) {
		return "db_name ASC";
	} elseif ( $sort == "02D" ) {
		return "db_name DESC";
	} elseif ( $sort == "03" ) {
		return "tstamp ASC";
	} elseif ( $sort == "03D" ) {
		return "tstamp DESC";
	} else {
		return "sync_name ASC";
	}
}

function adesk_sync_limitize($query, $offset = null, $limit = null, $sync) {
	$limitize = !is_null($offset) or !is_null($limit);
	// search for a limit and remove it
	$q = preg_replace("/\r?\n/", ' ', trim($query));
	if ( $sync['db_type'] == 'mssql' ) {
		$r = preg_replace('/^SELECT\sTOP\s\d+/i', 'SELECT', $q);
	//} elseif ( $sync['db_type'] == 'mysql' ) {
	} else {
		$p = strpos(strtolower($q), 'limit ');
		if ( $p !== false ) {
			$r = substr($q, 0, $p);
		} else {
			$r = $query;
		}
	}
	// add our own limit
	$offset = (int)$offset;
	$limit  = (int)$limit;
	if ( $limitize ) {
		if ( !$limit ) $limit = 999999999;
		if ( $sync['db_type'] == 'mssql' ) {
			if ( 1 or $offset ) {
				$limit = $offset + $limit;
				$indexField = adesk_sync_wrap($sync['fieldslist']['email'], $sync);
				//$inner_q = preg_replace('/^SELECT\s/i', "SELECT Row_Number() OVER (ORDER BY [$indexField]) AS RowIndex, ", $q);
				$inner_q = preg_replace('/^SELECT\s/i', "SELECT Row_Number() OVER (ORDER BY $indexField) AS RowIndex, ", $q);
				$r = "SELECT * FROM ( $inner_q ) AS syncTable WHERE syncTable.RowIndex > $offset AND syncTable.RowIndex <= $limit";
			} else {
				$r = preg_replace('/^SELECT\s/i', "SELECT TOP $limit", $q);
			}
		//} elseif ( $sync['db_type'] == 'mysql' ) {
		} else {
			$r .= " LIMIT $offset, $limit";
		}
	}
	return $r;
}

function adesk_sync_conn($data, $test) {
	$r = array(
		'id' => $data['id'],
		'succeeded' => false,
		'message' => '',
		'tables' => array(),
		'rules' => array(),
		'is_custom' => false,
		'is_test' => $test
	);
	if ( !isset($data['db_host']) ) {
		$r['message'] = _a('Sync Info is missing. Aborting...');
		return $r;
	}
	$GLOBALS['sync_link'] = @adesk_sync_connect($data);
	if ( !$GLOBALS['sync_link'] ) {
		$r['message'] = sprintf(_a('Error: Could not connect to host %s as user %s.'), $data['db_host'], $data['db_user']);
		return $r;
	}
	$x = @adesk_sync_select_db($data);
	if ( !$x ) {
		$r['message'] = adesk_sync_error($data);
		if ( !$r['message'] ) $r['message'] = sprintf(_a('Error: Could not select the database %s on host %s as user %s.'), $data['db_name'], $data['db_host'], $data['db_user']);
		return $r;
	}
	$r['succeeded'] = true;
	$r['message'] = _a('Connection successful.');
	if ( $test ) {
		// fetch tables list
		if ( $data['db_type'] == 'mssql' ) {
			$sql = adesk_sync_query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES", $data);
		} elseif ($data['db_type'] == 'pg' ) {
			$sql = adesk_sync_query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'", $data);
		} else {
			$sql = adesk_sync_query("SHOW TABLES", $data);
		}
		if ( !$sql ) {
			$r['message'] = adesk_sync_error($data);
			return $r;
		}
		while ( $row = adesk_sync_fetch_row($sql, $data) ) $r['tables'][] = $row[0];
		if ( count($r['tables']) == 0 ) {
			$r['message'] = _a('No tables are found. You can only write a custom query here.');
			return $r;
		}
	}
	return $r;
}

function adesk_sync_db() {
	//require_once(adesk_admin('functions/awebdesk.php'));
	$id = (int)adesk_http_param('id');
	$relid = (int)adesk_http_param('relid');
	$_POST['db_pass'] = base64_encode((string)adesk_http_param('db_pass'));
	$types = adesk_sync_database_types();
	if ( !isset($_POST['db_type']) or !isset($types[$_POST['db_type']]) ) {
		$_POST['db_type'] = key($types); // mysql
	}
	$r = adesk_sync_conn($_POST, true);
	if ( $id > 0 ) {
		$r['sync'] = adesk_sync_get_one($id, $GLOBALS['adesk_sync_table']);
		$r['sync'] = adesk_sync_prepare($r['sync'], true, false);
	}
	$r['duplicate'] = false;
	if ( !$r['succeeded'] ) return $r;
	// check for relid duplicate
	$cond = "relid = '$relid'";
	if ( $id > 0 ) $cond .= "AND id != '$id'";
	$r['succeeded'] = (bool)( $relid /*and adesk_sql_select_one("=COUNT(id)", '#' . $GLOBALS['adesk_sync_table'], $cond) == 0*/ );
	if ( !$r['succeeded'] ) {
		$r['message'] = _a("This destination is already being synced. Please select a different destination.");
		$r['duplicate'] = true;
		return $r;
	}
	$r['customfields'] = adesk_ihook('adesk_sync_custom_fields', $relid);
	if ( !$r['customfields']) $r['customfields'] = array();
	return $r;
}

function adesk_sync_table() {
	$table = (string)adesk_http_param('db_table');
	$query = (string)adesk_http_param('db_query');
	$r = adesk_sync_db();
	if ( !$r['succeeded'] ) return $r;
	$r['succeeded'] = false;
	$r['query'] = $query;
	if ( !$table and !preg_match('/select/i', $query) ) {
		$r['message'] = _a("Query provided is not a type of SELECT.");
		return $r;
	} elseif ( $table and !in_array($table, $r['tables']) ) {
		$r['message'] = sprintf(_a("Table %s not found in database."), $table);
		return $r;
	}
	$date = $time = false;
	$unix = array();
	if ( $table ) {
		// fetch fields list
		$etable = adesk_sync_wrap($table, $_POST);
		if ( $_POST['db_type'] == 'mssql' ) {
			$sql = adesk_sync_query("SP_COLUMNS $etable", $_POST);
		} elseif($_POST['db_type'] == 'pg') {
			$sql = adesk_sync_query("SELECT column_name FROM information_schema.columns WHERE table_name = '$etable'", $_POST);
		} else {
			$sql = adesk_sync_query("SHOW COLUMNS FROM $etable", $_POST);
		}
		if ( !$sql ) {
			$r['message'] = adesk_sync_error($_POST);
			return $r;
		}
		while ( $row = adesk_sync_fetch_row($sql, $_POST) ) {
			if ( $_POST['db_type'] == 'mssql' ) {
				$r['fields'][] = array('name' => $row[3], 'type' => $row[5]);
			} else {
				$r['fields'][] = array('name' => $row[0], 'type' => $row[1]);
			}
			if ( strtolower($row[1]) == 'date' ) $date = $row[0];
			if ( strtolower($row[1]) == 'time' ) $time = $row[0];
		}
	} else {
		$r['is_custom'] = true;
		// fetch fields list from a query
		$q = $query;
		// search for a limit
		$q = preg_replace("/\r?\n/", ' ', trim($q));
		$p = strpos(strtolower($q), 'limit ');
		if ( $p !== false ) {
			$q = substr($q, 0, $p) . ' LIMIT 0, 1';
		}
		// run query
		$sql = adesk_sync_query($q, $_POST);
		if ( !$sql ) {
			$r['message'] = adesk_sync_error($_POST);
			return $r;
		}
		if ( adesk_sync_num_rows($sql, $_POST) == 0 ) {
			$r['message'] = _a("No rows fetched.");
			return $r;
		}
		// fetch first row (for comparison)
		$row = adesk_sync_fetch_assoc($sql, $_POST);
		foreach ( $row as $k => $v ) {
			$r['fields'][] = array('name' => $k, 'type' => 'string');
			if ( preg_match('/^\d{4}-\d{2}-\d{2}$/', $v) ) $date = $k;
			if ( preg_match('/^\d{2}:\d{2}:\d{2}$/', $v) ) $time = $k;
			if ( preg_match('/^\d{10}$/', $v) ) $unix[] = $k;
		}
	}
	// if date only and time only fields are found, offer combo too
	if ( $date and $time ) {
		$desc = "$date + $time";
		$edate = adesk_sync_wrap($date, $_POST);
		$etime = adesk_sync_wrap($time, $_POST);
		$r['fields'][] = array('name' => "CONCAT($edate, ' ', $etime)", 'type' => _a($desc));
	}
	if ( count($unix) > 0 ) {
		foreach ( $unix as $v ) {
			$v = adesk_sync_wrap($v, $_POST);
			$r['fields'][] = array('name' => "FROM_UNIXTIME($v)", 'type' => _a('unix timestamp?'));
		}
	}
	// done
	$r['succeeded'] = true;
	$r['message'] = sprintf(_a('Found %d columns.'), count($r['fields']));
	return $r;
}

function adesk_sync_field() {
	$r = adesk_sync_table();
	$r['rules'] = array();
	if ( isset($r['sync']) and isset($r['sync']['ruleslist']) ) {
		$r['rules'] = $r['sync']['ruleslist'];
	}
	if ( $r['succeeded'] ) {
		$r['message'] = _a('Required fields matched. The rest will get default values.');
	}
	return $r;
}

function adesk_sync_save() {
	$r = array(
		'succeeded' => 0,
		'mode' => $_POST['mode'],
		'message' => ''
	);
	$id = (int)$_POST['id'];
	$relid = (int)$_POST['relid'];
	if ( $r['mode'] != 'edit' ) $r['mode'] = 'add';
	if ( $id == 0 ) $r['mode'] = 'add';
	if ( $r['mode'] == 'edit' ) {
		// check for privileges first!
		if ( adesk_ihook_exists('adesk_sync_permission') ) {
			if ( !adesk_ihook_exists('adesk_sync_permission') ) {
				$r['message'] = _a("You have no permission to manage database syncs.");
				return $r;
			}
		}
		// if sync not provided/found, simply return 'not saved'
		$s = adesk_sync_get_one($id);
		if ( $s ) {
			$r['succeeded'] = adesk_sync_edit();
		}
		if ( $r['succeeded'] ) {
			$r['message'] = sprintf(_a("Sync '%s' saved."), $_POST['sync_name']);
		} else {
			$r['message'] = sprintf(_a("Sync '%s' could not be saved."), $_POST['sync_name']);
		}
	} else {
		// check for privileges first!
		if ( adesk_ihook_exists('adesk_sync_permission') ) {
			if ( !adesk_ihook_exists('adesk_sync_permission') ) {
				$r['message'] = _a("You have no permission to manage database syncs.");
				return $r;
			}
		}
		$id = $r['succeeded'] = adesk_sync_add();
		if ( $r['succeeded'] ) {
			//$id = (int)$r['succeeded'];
			$s = adesk_sync_get_one($id);
		} else {
			$s = array_merge(adesk_sync_new($relid, $GLOBALS['adesk_sync_table']), $_POST);
		}
		if ( $r['succeeded'] ) {
			$r['message'] = sprintf(_a("Sync '%s' added."), $_POST['sync_name']);
		} else {
			$r['message'] = sprintf(_a("Sync '%s' could not be added."), $_POST['sync_name']);
		}
	}
	return array_merge($s, $r);
}

function adesk_sync_select($id) {
	// get sync id
	$data = array();
	$ids = explode(',', $id);
	if ( count($ids) > 1 ) {
		$data['rows'] = array();
		foreach ( $ids as $v ) {
			$v = (int)$v;
			if ( $v > 0 ) {
				$data['rows'][$v] = adesk_sync_get_one($v, $GLOBALS['adesk_sync_table']);
				$data['rows'][$v]['db_pass'] = base64_decode($data['rows'][$v]['db_pass']);
				$data['rows'][$v] = adesk_sync_prepare($data['rows'][$v]);
			}
		}
	} else {
		$id = (int)$id;
		if ( $id > 0 ) {
			$r = adesk_sync_get_one($id, $GLOBALS['adesk_sync_table']);
			$r['db_pass'] = base64_decode($r['db_pass']);
			$r = adesk_sync_prepare($r);
			$data[] = $r;
		}
	}
	return $data;
}



function adesk_sync_log_init($sync) {
	if ( !defined('adesk_SYNC_DEBUG') or !adesk_SYNC_DEBUG ) return;
	$GLOBALS['_synclog'] = @fopen(adesk_cache_dir('synclog-' . $sync['id']), 'a');
	return;
}

function adesk_sync_log_store($msg, $deep = true) {
	if ( !defined('adesk_SYNC_DEBUG') or !adesk_SYNC_DEBUG ) return;
	if ( !isset($GLOBALS['_synclog']) or !$GLOBALS['_synclog'] ) return;
	// try to add datetimestamps
	$stamp = date('Y-m-d H:i:s');
	// do microtime processing
	$now = adesk_microtime_get();
	if ( !isset($GLOBALS['_adesk_sync_timer']) ) {
		// first instance, set zero
		$time = 'starting';
	} else {
		// subtract from previous stamp, 6 decimal roundup
		$time = round($now - $GLOBALS['_adesk_sync_timer'], 6);
	}
	// set this stamp as last
	$GLOBALS['_adesk_sync_timer'] = $now;
	// add digits to form 8char string
	if ( strlen("$time") < 8 ) $time .= str_repeat(0, 8 - strlen($time));
	//if ( adesk_str_instr('E-', "$time") ) $time = 'tooshort';//$time = '0.000000';
	// add it to msg
	if ( $deep ) $msg = "[[$stamp $time]] $msg";
	@fwrite($GLOBALS['_synclog'], $msg . "\n");
}

function adesk_sync_log_comment($comment = '') {
	if ( !adesk_str_instr('<script>', $comment) and !adesk_str_instr('parent.', $comment) and $comment != '</table>' ) {
		adesk_sync_log_store($comment/*, false*/);
	}
	if ( !( defined('adesk_SYNC_PRINT') and adesk_SYNC_PRINT ) ) return;
	adesk_flush($comment . "\n<br />\n\n");
}

function adesk_sync_log_row($sync, $row, $result) {
	//dbg(defined('adesk_SYNC_LOGTABLE'), 1);
	if ( defined('adesk_SYNC_LOGTABLE') and adesk_SYNC_LOGTABLE ) {
		// save him into *_import(_log) table
		$insert = array(
			'id' => 0,
			'processid' => $sync['process_id'],
			'email' => trim($row[$sync['fieldslist']['email']]),
			'res' => (int)$result['succeeded'],
			'code' => $result['code'],
			'msg' => $result['message'],
			'=tstamp' => 'NOW()',
		);
		adesk_sql_insert(adesk_SYNC_LOGTABLE, $insert) or die(adesk_sql_error());
	}
	adesk_sync_log_store("\n\nRow completed!\n"/*, false*/);
	if ( !( defined('adesk_SYNC_PRINT') and adesk_SYNC_PRINT ) ) return;
	if ( !isset($GLOBALS['syncrowid']) ) $GLOBALS['syncrowid'] = 0;
	$GLOBALS['syncrowid']++;
	$rowid = $GLOBALS['syncrowid'];
	if ( !( defined('adesk_SYNC_DEBUG') and adesk_SYNC_DEBUG ) ) {
		adesk_flush('. ');
		return;
	}
	if ( !defined('adesk_SYNC_PRINT_HEADER') ) {
		define('adesk_SYNC_PRINT_HEADER', 1);
		echo '<table width="100%">';
		echo '<tr>';
		echo '<th>' . _a('Synced') . '</th>';
		foreach ( $row as $k => $v ) {
			// figure out if this one is mapped
			$props = '';
			if ( false !== ( $key = array_search($k, $sync['fieldslist']) ) ) {
				$alt = sprintf(_a('Mapped into field: %s'), $key);
				$props = ' alt="' . $alt . '" title="' . $alt . '" class="adesk_mapped_column"';
			}
			echo '<th' . $props . '>' . $k . '</th>';
		}
		echo '</tr>';
	}
	$prefix = ( adesk_site_isAEM5() ? '../../' : '' );
	echo "<tr>\n";
	echo '<td>';
	echo
		'<img src="' . $prefix . '../awebdesk/media/circle_' .
		( $result['succeeded'] ? 'green' : 'grey' ) .
		'.gif" onmouseout="adesk_dom_toggle_display(\'syncresult' . $rowid .
		'\', \'inline\')" onmouseover="adesk_dom_toggle_display(\'syncresult' . $rowid . '\', \'inline\')" />'
	;
	echo '<div id="syncresult' . $rowid . '" class="adesk_help" style="display: none;">' . $result['message'] . '</div>';
	echo '</td>';
	foreach ( $row as $k => $v ) {
		// figure out if this one is mapped
		$props = '';
		if ( false !== ( $key = array_search($k, $sync['fieldslist']) ) ) {
			$alt = sprintf(_a('Mapped into field: %s'), $key);
			$props = ' alt="' . $alt . '" title="' . $alt . '" class="adesk_mapped_column"';
		}
		echo '<td' . $props . '>' . adesk_str_shorten(trim(strip_tags($v)), 30) . '</td>';
	}
	echo "\n</tr>\n\n";
	//echo '</table>';
	flush();
}


function adesk_sync_run_cron() {
	if ( !defined('adesk_CRON') ) define('adesk_CRON', 1);
	$cron = !isset($_SERVER['REMOTE_ADDR']);
	if ( !defined('adesk_SYNC_PRINT') ) define('adesk_SYNC_PRINT', (int)!$cron); // turn off log prints if real cron, doesn't need output
	// collect params
	if ( $cron ) {
		// from command line
		$id = ( isset($_SERVER['argv'][1]) ? (int)$_SERVER['argv'][1] : 0 );
		$test = false;
	} else {
		// from get/post
		$id = (int)adesk_http_param('id');
		$test = (bool)adesk_http_param('test');
	}
	// prepare for output
	if ( !$cron ) {
		// print javascript
		$prehtml = '
			<script>
				function adesk_dom_toggle_display(id, val) {
					document.getElementById(id).style.display = ( document.getElementById(id).style.display == val ? "none" : val );
				}
				if (parent && parent.document.getElementById("syncRunStart"))
					parent.document.getElementById("syncRunStart").disabled = false;
			</script>
			<style>
			div.adesk_help {
				z-index: 999;
				/*display: none;*/
				position:absolute;
				border: 1px solid #B4CDE6;
				padding: 10px;
				width:200px;
				margin-top:6px;
				font-size:10px;
				background:#F0F6FB;
				color:#333333;
			}
			.adesk_mapped_column {
				background-color: #ccc;
			}
			</style>
		';
		adesk_sync_log_comment($prehtml);
		adesk_flush($prehtml);
	}
	if ( $id > 0 ) {
		// get only one if provided
		$sync = adesk_sync_get_one($id, $GLOBALS['adesk_sync_table']);
		if ( !$sync ) {
			die(_a("Sync $id not found."));
			return;
		}
		$list = array($id => $sync);
	} else {
		if ( isset($_SESSION[adesk_prefix('sync_data')]) ) {
			// get saved data
			$list = array($_SESSION[adesk_prefix('sync_data')]);
		} else {
			// get all syncs
			$list = adesk_sync_get_all(adesk_sync_sort('03'), $GLOBALS['adesk_sync_table']);
		}
	}
	foreach ( $list as $sync ) {
		adesk_sync_log_init($sync);
		$date = date('Y-m-d H:i:s');
		$pid = ( isset($sync['process_id']) ? $sync['process_id'] : 'N/A' );
		adesk_sync_log_store("\nStarting Cron Job (process #$pid) at $date\n");
		adesk_sync_log_comment(( $test ? _a('Testing Sync: ') : _a('Starting Sync: ') ) . $sync['sync_name']);
		$r = adesk_sync_run($sync, $test, true); // this one might stall
		adesk_sync_log_comment('</table>');
		adesk_sync_log_comment(( $test ? _a('Sync Test Completed: ') : _a('Sync Completed: ') ) . $sync['sync_name']);
		adesk_sync_log_comment(_a('Synced: ') . $r['synced']);
		adesk_sync_log_comment(_a('Failed: ') . $r['failed']);
		// do something with $r?
	}
}

function adesk_sync_run_api() {
	if ( !defined('adesk_SYNC_PRINT') ) define('adesk_SYNC_PRINT', 0); // turn off log prints, api needs XML
	// collect params from get/post
	$id = (int)adesk_http_param('id');
	$test = (bool)adesk_http_param('test');
	if ( $id > 0 ) {
		$sync = adesk_sync_get_one($id, $GLOBALS['adesk_sync_table']);
		if ( !$sync ) return 0;
	} else {
		// prepare data
		$sync = array();
		adesk_sync_prepare_post($sync);
		$sync['id'] = $id;
		$_SESSION[adesk_prefix('sync_data')] = $sync;
	}
	//adesk_sync_log_init($sync); api should not store the log
	return adesk_sync_run($sync, $test, $full = false);
}

function adesk_sync_run_process($process) {
	$sync = $process['data']['sync'];
	$test = $process['data']['test'];
	$offset = $process['completed'];
	adesk_sync_log_init($sync);
	adesk_sync_log_store("\nPicking up Cron Job (process #$sync[process_id]) at $process[completed] / $process[total]\n");
	return adesk_sync_run($sync, $test, $full = true, $offset);
}

function adesk_sync_run($sync, $test = false, $full = true, $offset = 0) {

	$admin = adesk_admin_get();
	$oldadmin = null;

	if ( isset($sync['userid']) && $sync['userid'] ) {
		if ( $admin['id'] != $sync['userid'] ) {
			$oldadmin = $admin;
			$GLOBALS['admin'] = $admin = adesk_admin_get_totally_unsafe($sync['userid']);
		}
	}


	// first try to connect
	$r = adesk_sync_conn($sync, $test);
	$r['failed'] = 0;
	$r['found'] = 0;
	$r['synced'] = 0;
	$r['failedrows'] = array();
	$r['syncedrows'] = array();
	// if didn't even connect, return
	if ( !$r['succeeded'] ) {
		if ( $oldadmin ) $GLOBALS['admin'] = $oldadmin;
		return $r;
	}
	$r['succeeded'] = false;
	adesk_sync_log_comment(sprintf(_a('Connected successfully to host %s as user %s.'), $sync['db_host'], $sync['db_user']));
	if ( !isset($sync['query']) ) $sync = adesk_sync_prepare($sync);
	$r['sync'] = $sync;
	// check for basic validity
	if ( $sync['is_custom'] and !preg_match('/select/i', $sync['rules']) ) {
		$r['message'] = _a("Query provided is not a type of SELECT.");
		if ( $oldadmin ) $GLOBALS['admin'] = $oldadmin;
		return $r;
	} elseif ( !$sync['is_custom'] and $test and !in_array($sync['db_table'], $r['tables']) ) {
		$r['message'] = sprintf(_a("Table %s not found in database."), $sync['db_table']);
		if ( $oldadmin ) $GLOBALS['admin'] = $oldadmin;
		return $r;
	}
	$spawnProcess = false;

	$syncID = (int)$sync['id'];

	// this process id
	$useProcesses = function_exists('adesk_process_create'); // this makes api not use it
	$processid = 0;
	if ( !isset($sync['process_id']) ) {
		if ( $useProcesses and $full ) {
			// create a process
			$arr = array('sync' => $sync, 'test' => $r['is_test']);
			$total = 0;
			adesk_sync_log_store("\nFetching the count of rows that will be imported...\n");
			if ( isset($sync['query4count']) ) {
				$sql = adesk_sync_query($sync['query4count'], $sync);
				if ( !$sql ) {
					$r['message'] = adesk_sync_error($sync);
					if ( $oldadmin ) $GLOBALS['admin'] = $oldadmin;
					return $r;
				}
				if ( adesk_sync_num_rows($sql, $sync) ) {
					$tmparr = adesk_sync_fetch_row($sql, $sync);
					$total = (int)$tmparr[0];
				}
			} else {
				// run the query with no limits
				$sql = adesk_sync_query(adesk_sync_limitize($sync['query'], null, null, $sync), $sync);
				if ( !$sql ) {
					$r['message'] = adesk_sync_error($sync);
					if ( $oldadmin ) $GLOBALS['admin'] = $oldadmin;
					return $r;
				}
				$total = (int)adesk_sync_num_rows($sql, $sync);
			}
			adesk_sync_log_store("\nFound $total rows for import.\n");
			$r['found'] = $r['synced'] = $total;
			if ( $total > 0 ) {
				// check if there are any ongoing processes for this sync
				if ( $syncID ) {
					$found = (int)adesk_sql_select_one("=COUNT(*)", "#process", adesk_sync_process_cond($syncID) . " AND percentage < 100");
					if ( $found ) {
						$r['message'] = _a("There is already a process for this Sync Job. Please try again later or remove the ongoing process first.");
						if ( $oldadmin ) $GLOBALS['admin'] = $oldadmin;
						return $r;
					}
				}
				$processid = adesk_process_create('sync', $total, $arr, false, '0000-00-00 00:00:00'); // current time makes it pickup in 4mins
			} else {
				$processid = 0;
			}
			$arr['sync']['process_id'] = $sync['process_id'] = $processid;
			// then resave the data with process id
			if ( $processid ) {
				adesk_process_setdata($processid, $arr);
				$spawnProcess = true;
				//adesk_process_spawn(array('id' => $processid, 'stall' => 5 * 60));
			}
			adesk_sync_log_comment(sprintf(_a('Process #%s created.'), $processid));
		} else {
			// old style - KB3
			$sync['process_id'] = rand('100000', '900000');// setting a random process id
		}
		$r['sync']['process_id'] = $sync['process_id'];
	} elseif ( $useProcesses ) {
		// process already created, which should mean we are in a process pickup tool
		// update this process so some other process doesn't start
		adesk_sql_update_one('#process', '=ldate', 'NOW()', "`id` = '$sync[process_id]");
	}

	// if not full test, return here success
	if ( !$full ) {
		$r['succeeded'] = true;
		$r['message'] = _a("Ready to run.");
		if ( $oldadmin ) $GLOBALS['admin'] = $oldadmin;
		return $r;
	}

	if ( defined('adesk_CRON') and $useProcesses ) {
		// autoupdate
		if ( $processid ) {
			//$admin = adesk_admin_get();
			//$secondInterval = ( isset($admin['autoupdate']) ? $admin['autoupdate'] : 60 );
			$secondInterval = 10;
			adesk_sync_log_comment(
				'
					<script>//alert(\'process: ' . $processid . '\');
						if (parent && parent.adesk_progressbar_register && parent.document.getElementById("progressBar")) {
							parent.adesk_progressbar_unregister("progressBar");
							parent.adesk_progressbar_register("progressBar", "' . $processid . '", 0, ' . $secondInterval . ', true, parent.adesk_sync_progressbar_callback);
							parent.processID = "' . $processid . '";
							if ( parent.document.getElementById("report_count") ) {
								parent.document.getElementById("report_count").innerHTML = "' . $total . '";
							}
						}
					</script>
				'
			);
		} else {
			adesk_sync_log_comment(
				'
					<script>//alert(\'process: ' . $processid . '\');
						if (parent && parent.adesk_progressbar_register && parent.document.getElementById("progressBar")) {
							parent.adesk_progressbar_set("progressBar", 100);
							parent.adesk_sync_progressbar_callback({ percentage: 100 });
						}
					</script>
				'
			);
		}
		if ( $spawnProcess ) adesk_process_spawn(array('id' => $processid, 'stall' => 5 * 60));
		$r['succeeded'] = true;
		$r['message'] = _a("Synchronization Process initiated.");
		if ( $oldadmin ) $GLOBALS['admin'] = $oldadmin;
		return $r;
	}

	// now fetch the rows with offset applied (omit processed ones)
	//$limit = ( $offset > 0 ? "LIMIT $offset, 99999999" : '' );
	$limit = ( $offset > 0 ? 99999999 : null );
	$offset = ( $offset > 0 ? $offset : null );
	$q = adesk_sync_limitize($sync['query'], $offset, $limit, $sync);

	// RUN QUERY
	adesk_sync_log_comment(sprintf(_a('Querying the database: %s'), $q));
	$sql = adesk_sync_query($q, $sync);
	if ( !$sql ) {
		$r['message'] = adesk_sync_error($sync);
		if ( $oldadmin ) $GLOBALS['admin'] = $oldadmin;
		return $r;
	}
	$r['found'] = adesk_sync_num_rows($sql, $sync);
	if ( $r['found'] == 0 ) {
		$r['message'] = _a("No rows fetched.");
		if ( $oldadmin ) $GLOBALS['admin'] = $oldadmin;
		return $r;
	}
	$i = 0;
	if ( isset($GLOBALS['_adesk_sync_lists']) ) unset($GLOBALS['_adesk_sync_lists']);
	adesk_sync_log_comment(sprintf(_a('Database queried, fetched %d results.'), $r['found']));
	while ( $row = adesk_sync_fetch_assoc($sql, $sync) ) {
		$i++;
		adesk_sync_log_comment(sprintf(_a('Processing row number %d (out of %d).'), $i, $r['found']));
		$rs = adesk_sync_row($sync, $row, $test);
		$r['synced'] += (int)$rs['succeeded'];
		adesk_sync_log_row($sync, $row, $rs);
		if ( $useProcesses ) adesk_process_update($sync['process_id']);
		//if ( $r['synced'] == 10 ) die('stop here');
	}
	// save if synced
	if ( !$test ) {
		// delete all check
		if ( $sync['delete_all'] ) adesk_ihook('adesk_sync_delete_all', $sync);
		// save if synced
		if ( $syncID > 0 ) {
			adesk_sql_update('#' . $GLOBALS['adesk_sync_table'], array('=tstamp' => 'NOW()'), "id = '$syncID'");
		}
	}
	$r['failed'] = $r['found'] - $r['synced'];
	// done
	$r['succeeded'] = ( $r['found'] == $r['synced'] );
	$r['message'] = sprintf(_a('Sync Completed. %d items found, %d items synced.'), $r['found'], $r['synced']);
	if ( defined('adesk_CRON') and !$useProcesses ) {
		adesk_sync_log_comment(
			'
				<script>//alert(\'process: ' . $processid . '\');
					if (parent && parent.adesk_progressbar_register && parent.document.getElementById("progressBar")) {
						parent.adesk_progressbar_set("progressBar", 100);
						parent.adesk_sync_progressbar_callback({ percentage: 100 });
					}
				</script>
			'
		);
	}

	if ( $oldadmin ) $GLOBALS['admin'] = $oldadmin;
	return $r;
}


function adesk_sync_row($sync, $row, $test) {
	return adesk_ihook('adesk_sync_row', $sync, $row, $test);
}

function adesk_sync_report($processid) {
	$processid = (int)$processid;
	$table = adesk_SYNC_LOGTABLE;
	$r = array(
		'counts' => array(),
		'lists'  => array(),
		'total'  => 0,
	);
	$query = "SELECT email, code, msg, tstamp FROM $table WHERE processid = '$processid' AND res = 0";
	$sql = adesk_sql_query($query);
	while ( $row = adesk_sql_fetch_assoc($sql) ) {
		$destination = adesk_ihook('adesk_sync_row_report', $row);
		$r['lists'][$destination][] = $row;
	}
	$r['counts'] = array_map('count', $r['lists']);

	$r['total'] = array_sum($r['counts']);

	return $r;
}

function adesk_sync_process_cond($syncid) {
	$syncid_str = (string)(int)$syncid;
	$cond_str = '{s:2:"id";s:' . strlen($syncid_str) . ':"' . $syncid_str . '";';
	$cond_int = '{s:2:"id";i:' . $syncid_str . ';';
	$cond_int_esc = adesk_sql_escape($cond_int);
	$cond_str_esc = adesk_sql_escape($cond_str);
	return "action = 'sync' AND ( data LIKE '%$cond_int_esc%' OR data LIKE '%$cond_str_esc%' )";
}

?>
