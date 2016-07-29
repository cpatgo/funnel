<?php

require_once dirname(__FILE__) . "/cache.php";

// not used anymore
function adesk_import_src($running = false) {
	// gather input
	$admin = adesk_admin_get();
	$uid = ( $admin['id'] == 0 ? 1 : $admin['id'] );
	$relid = adesk_http_param('relid');
	if ( is_array($relid) ) {
		$relid = array_diff(array_map('intval', $relid), array(0)); // don't allow zeros
		//$relid = array_map('intval', $relid); // allow zeros
	} else {
		$relid = (int)adesk_http_param('relid');
	}
	$destination = (int)adesk_http_param('destination');
	$type = (string)adesk_http_param('type');
	$delimiter = (string)adesk_http_param('delimiter_file');
	$text = (string)adesk_http_param('import_text');
	$file = adesk_http_param('import_file');
	// define result
	$r = array(
		'relid' => $relid,
		'valid' => false,
		'succeeded' => false,
		'message' => '',
		'filename' => '',
		'rows' => 0,
		'fields' => array(),
		'standardfields' => adesk_ihook('adesk_import_fields', $relid, $destination),
		'customfields' => adesk_ihook('adesk_import_custom_fields', $relid, $destination),
		'delimiter_file' => $delimiter,
	);
	/*
	if ( !adesk_admin_isadmin() ) {
		$r['message'] = _a('Only admin users can import files.');
		return $r;
	}
	*/
	if ( !$r['customfields'] ) $r['customfields'] = array();
	// if input type is textarea, save the file for future use
	$path = adesk_cache_dir() . "/";
	if ( $type == 'text' ) {
		if ( trim($text) == '' ) {
			$r['message'] = _a('You did not enter any data into a text box. Please add data first...');
			return $r;
		}
		$r['delimiter_file'] = $delimiter = (string)adesk_http_param("delimiter_text");
		$filename = 'csvimport-' . $uid . '-tmpfile.csv';
		unset($_POST["import_text"]);
		$_POST["import_file"] = array($filename);
		$_POST["type"] = "file";
		$file = array($filename);
		$text = adesk_utf_conv("utf-8", _i18n("utf-8"), $text);
		if ( !@file_put_contents($path . $filename, $text) ) {
			$r['message'] = _a('Could not save the content to import.');
			return $r;
		}
	// if input type is file, check if file(s) uploaded properly
	}
	$uploaded = false;
	if ( !is_array($file) ) $file = array();
	foreach ( $file as $filename ) {
		if ( file_exists($path . $filename) ) {
			$text = @file_get_contents($path . $filename);
			if ( $text ) {
				$text = adesk_utf_conv(_i18n("utf-8"), "UTF-8", $text);
				$uploaded = true;
				break; // only one file at the time in importer
			}
		}
	}
	if ( !$uploaded ) {
		$r['message'] = _a('You did not upload a file to import. Please do that first...');
		return $r;
	}
	$r['filename'] = $filename;
	// do stuff with $text (data) string variable
	// get array from CSV file
	$csv = adesk_import_csv2array($text, $delimiter);
	unset($text);
	// get fields
	$r['fields'] = adesk_import_columns($csv);
	// save CSV data if running
	$r['rows'] = count($csv);
	if ( $running ) $r['data'] = $csv;
	if ( count($r['fields']) == 0 ) {
		$r['message'] = _a('This is either not a CSV file, or no columns could be matched. Please try using different settings.');
		return $r;
	}
	// count required fields
	$required = 0;
	foreach ( $r['standardfields'] as $row ) {
		if ( $row['req'] ) $required++;
	}
	foreach ( $r['customfields'] as $row ) {
		if ( $row['req'] ) $required++;
	}
	if ( count($r['fields']) < $required ) {
		$r['message'] = sprintf(_a('This CSV file does not have enough columns to complete the import. It needs to have at least %d columns.'), $required);
		return $r;
	}
	$r['valid'] = true;
	if ( adesk_ihook_exists('adesk_import_valid_check') ) {
		$r['valid'] = (bool)adesk_ihook('adesk_import_valid_check', $r);
	}
	$r['succeeded'] = true;
	$r['message'] = _a('CSV content successfully parsed.');
	return $r;
}

function adesk_import_delimiter_guess($data) {
	$lines = explode("\n", str_replace("\r", "\n", str_replace("\r\n", "\n", $data)));

	# Nothing here?
	if (count($lines) == 0)
		return "comma";

	$line = $lines[0];

	# Get rid of any escapes -- we don't want to deal with them.
	$line = preg_replace('/\\./', '', $line);

	# We're mainly checking for commas, semicolons, and tabs.  Check
	# in order, starting with any combinations which have quote characters
	# surrounding field values.

	if (preg_match('/"\s*,\s*"/', $line))
		return "comma";
	if (preg_match('/\'\s*,\s*\'/', $line))
		return "comma";
	#--
	if (preg_match('/"\s*;\s*"/', $line))
		return "semicolon";
	if (preg_match('/\'\s*;\s*\'/', $line))
		return "semicolon";
	#--
	if (preg_match('/"[ ]*\t[ ]*"/', $line))
		return "tab";
	if (preg_match('/\'[ ]*\t[ ]*\'/', $line))
		return "tab";

	# If we get here, that means we don't seem to be using quotes surrounding
	# field values.  The matches here are a bit less precise.

	if (preg_match('/.,./', $line))
		return "comma";
	if (preg_match('/.;./', $line))
		return "semicolon";
	if (preg_match('/\S\t\S/', $line))
		return "tab";

	# Weird.  We're not sure.  Better go with comma.
	return "comma";
}

function adesk_import_delimiter($delimiter) {
	switch ( $delimiter ) {
		case 'comma':
			$delimiter = ',';
			break;
		case 'semicolon':
			$delimiter = ';';
			break;
		case 'tab':
			$delimiter = "\t";
			break;
		//case 'pipe':
			//$delimiter = '\|';
			//break;
		default:
			$delimiter = ',';
	}
	return $delimiter;
}

function adesk_import_csv2array(&$data, $delimiter) {
	$delimiter = adesk_import_delimiter($delimiter);
	ob_implicit_flush(true);
	$arr = array();
//dbg(memory_get_usage(), 1);
	//$lines = preg_split("/\r\n|\r|\n/", $data);
	$lines = explode("\n", str_replace("\r", "\n", str_replace("\r\n", "\n", $data)));
//dbg(memory_get_usage(), 1);
	$data = null;
	//unset($data);
//dbg(memory_get_usage(), 1);
	foreach ( $lines as $line ) {
		//$line = trim($line);
		$line = trim($line);
		if ( $line != '' ) {
			//$arr[] = adesk_import_csvline2array(trim($line), $delimiter);
			$arr[] =
				preg_replace(
					"/^\"(.*)\"$/",
					"$1",
					preg_replace(
						"/\"\"/",
						"",
						preg_split("/$delimiter(?=(?:[^\"]*\"[^\"]*\")*(?![^\"]*\"))/", $line)
					)
				)
			;
			//$arr[] = adesk_import_csvline2array(trim($line), $delimiter);
		}
		unset($line);
	} // end while
//dbg(memory_get_usage(), 1);
	unset($lines);
//dbg(memory_get_usage(), 1);
	//dbg($arr);
	return $arr;
}

function adesk_import_csvline2array($string, $delimiter = ',') {
	$expr = "/$delimiter(?=(?:[^\"]*\"[^\"]*\")*(?![^\"]*\"))/";
	$results = preg_split($expr, $string);
	unset($string);
	$results = preg_replace("/\"\"/", "\"", $results);
	return preg_replace("/^\"(.*)\"$/", "$1", $results);



	return
		preg_replace(
			"/^\"(.*)\"$/",
			"$1",
			preg_replace(
				"/\"\"/",
				"\"",
				preg_split("/$delimiter(?=(?:[^\"]*\"[^\"]*\")*(?![^\"]*\"))/", $string)
			)
		)
	;
}


function adesk_import_columns($fields) {
	$columns = array();
	//dbg($fields);
	$emailFound = false;
	$dateFound = false;
	$fnFound = false;
	$lnFound = false;
	if ( isset($fields[0]) && is_array($fields[0]) ) {
		foreach ( $fields[0] as $k => $field ) {
			$default = '';
			if ( !$emailFound ) {
				if ( adesk_str_is_email($field) or adesk_str_instr('mail', strtolower($field)) ) {
					$default = 'email';
					$emailFound = true;
				}
			}
			if ( !$dateFound ) {
				if (
					preg_match('/^\d{10}$/', $field) or
					preg_match('/^\d+-\d+-\d+$/', $field) or
					preg_match('/^\d+-\d+-\d+ \d+:\d+:\d+$/', $field) or
					adesk_str_instr('create', strtolower($field)) or
					adesk_str_instr('date', strtolower($field)) or
					adesk_str_instr('tstamp', strtolower($field))
				) {
					$default = 'date';
					$dateFound = true;
				}
			}
			if ( !$fnFound ) {
				if (
					adesk_str_instr('first', strtolower($field)) ||
					"name" == strtolower($field)
				) {
					$default = 'firstname';
					$fnFound = true;
				}
			}
			if ( !$lnFound ) {
				if (
					adesk_str_instr('last', strtolower($field))
				) {
					$default = 'lastname';
					$lnFound = true;
				}
			}
			$columns[] = array(
				'id' => $k,
				'name' => $field,
				'type' => ( is_numeric($field) ? 'numeric' : 'text' ),
				'default' => $default,
			);
		}
	}
	return $columns;
}

function adesk_import_csv_parse($data, $delimiter) {
	// get array from CSV file
	$csv = adesk_import_csv2array($data, $delimiter);
	// get fields
	return /*$fields =*/ adesk_import_columns($csv);
}



function adesk_import_mapping_get($dest = array()) {
	$r = array();
	//$dest = adesk_http_param('dest');
	if ( !is_array($dest) ) return $r;
	foreach ( $dest as $k => $v ) {
		if ( $v != 'DNI' ) {
			$r[$v] = $k;
		}
	}
	return $r;
}

function adesk_import_mapping_check($post, $r) {
	return true; // ?!?!
}




// not used anymore
function adesk_import_file_remove($id) {
	$r = array(
		'succeeded' => false,
		'message' => '',
		'id' => $id
	);
	$r['succeeded'] = adesk_file_upload_remove(adesk_cache_dir(), '', $id);
	if ( $r['succeeded'] ) {
		$r['message'] = sprintf(_a("File '%s' removed."), substr($id, strlen('csvimport-')));
	} else {
		$r['message'] = sprintf(_a("File '%s' could not be removed."), substr($id, strlen('csvimport-')));
	}
	return $r;
}



function adesk_import_log_init($import) {
	if ( !defined('adesk_SYNC_DEBUG') or !adesk_SYNC_DEBUG ) return;
	if ( !isset($import['id']) ) $import['id'] = 0;
	$GLOBALS['_synclog'] = @fopen(adesk_cache_dir('importlog-' . $import['id']), 'a');
	return;
}

function adesk_import_log_store($msg, $deep = true) {
	if ( !defined('adesk_SYNC_DEBUG') or !adesk_SYNC_DEBUG ) return;
	if ( !isset($GLOBALS['_synclog']) or !$GLOBALS['_synclog'] ) return;
	// try to add datetimestamps
	$stamp = date('Y-m-d H:i:s');
	// do microtime processing
	$now = adesk_microtime_get();
	if ( !isset($GLOBALS['_adesk_import_timer']) ) {
		// first instance, set zero
		$time = 'starting';
	} else {
		// subtract from previous stamp, 6 decimal roundup
		$time = round($now - $GLOBALS['_adesk_import_timer'], 6);
	}
	// set this stamp as last
	$GLOBALS['_adesk_import_timer'] = $now;
	// add digits to form 8char string
	if ( strlen("$time") < 8 ) $time .= str_repeat(0, 8 - strlen($time));
	//if ( adesk_str_instr('E-', "$time") ) $time = 'tooshort';//$time = '0.000000';
	// add it to msg
	if ( $deep ) $msg = "[[$stamp $time]] $msg";
	@fwrite($GLOBALS['_synclog'], $msg . "\n");
}

function adesk_import_log_comment($comment = '') {
	if ( !adesk_str_instr('<script>', $comment) and !adesk_str_instr('parent.', $comment) and $comment != '</table>' ) {
		adesk_import_log_store($comment/*, false*/);
	}
	if ( !( defined('adesk_IMPORT_PRINT') and adesk_IMPORT_PRINT ) ) return;
	adesk_flush($comment . "\n<br />\n\n");
}

function adesk_import_log_row($post, $row, $result) {
	if ( defined('adesk_IMPORT_LOGTABLE') and adesk_IMPORT_LOGTABLE ) {
		// save him into *_import(_log) table
		$insert = array(
			'id' => 0,
			'processid' => $post['process_id'],
			'email' => ( isset($row[$post['fieldslist']['email']]) ? trim($row[$post['fieldslist']['email']]) : '' ),
			'res' => (int)$result['succeeded'],
			'code' => $result['code'],
			'msg' => $result['message'],
			'=tstamp' => 'NOW()',
		);
		adesk_sql_insert(adesk_IMPORT_LOGTABLE, $insert);
	}
	if ( !( defined('adesk_IMPORT_PRINT') and adesk_IMPORT_PRINT ) ) return;
	if ( !defined('adesk_IMPORT_PRINT_HEADER') ) {
		define('adesk_IMPORT_PRINT_HEADER', 1);
		echo '<table width="100%" class="font_10">';
		echo '<tr>';
		echo '<th>' . _a('Imported') . '</th>';
		foreach ( $row as $k => $v ) {
			// figure out if this one is mapped
			$props = '';
			if ( false !== ( $key = array_search($k, $post['fieldslist']) ) ) {
				$alt = sprintf(_a('Mapped into field: %s'), $key);
				$props = ' alt="' . $alt . '" title="' . $alt . '" class="adesk_mapped_column"';
			}
			echo '<th' . $props . '>' . $k . '</th>';
		}
		echo '</tr>';
	}
	if ( !isset($GLOBALS['importrowid']) ) $GLOBALS['importrowid'] = 0;
	$GLOBALS['importrowid']++;
	$rowid = $GLOBALS['importrowid'];
	//$prefix = ( adesk_site_isAEM5() ? '../../' : '' );
	echo "<tr>\n";
	echo '<td>';
	echo
		'<img src="../awebdesk/media/circle_' .
		( $result['succeeded'] ? 'green' : 'grey' ) .
		'.gif" onmouseout="adesk_dom_toggle_display(\'importresult' . $rowid .
		'\', \'inline\')" onmouseover="adesk_dom_toggle_display(\'importresult' . $rowid . '\', \'inline\')" />'
	;
	echo '<div id="importresult' . $rowid . '" class="adesk_help" style="display: none;">' . $result['message'] . '</div>';
	echo '</td>';
	foreach ( $row as $k => $v ) {
		// figure out if this one is mapped
		$props = '';
		if ( false !== ( $key = array_search($k, $post['fieldslist']) ) ) {
			$alt = sprintf(_a('Mapped into field: %s'), $key);
			$props = ' alt="' . $alt . '" title="' . $alt . '" class="adesk_mapped_column"';
		}
		echo '<td' . $props . '>' . adesk_str_shorten(trim(strip_tags($v)), 30) . '</td>';
	}
	echo "\n</tr>\n\n";
	//echo '</table>';
	flush();
}



function adesk_import_run($post, $test = false, $offset = 0, $prepareOnly = false) {
	# import_src will use $_POST for this, so we can get rid of this now.
	unset($post["import_text"]);

	adesk_import_log_init($post);
	if ( isset($post['process_id']) ) {
		adesk_import_log_store("\nPicking up Import Job (process #$post[process_id]) at $offset\n");
	} else {
		$date = date('Y-m-d H:i:s');
		adesk_import_log_store("\nStarting Import Job at $date\n");
	}
	// set output to true
	if ( !defined('adesk_IMPORT_PRINT') ) define('adesk_IMPORT_PRINT', 1);
	// print javascript
	$charset = _i18n("utf-8");
	$prehtml = "<meta http-equiv='Content-Type' content='text/html; charset=$charset' />\n";
	$prehtml .= '
		<script>
			function adesk_dom_toggle_display(id, val) {
				document.getElementById(id).style.display = ( document.getElementById(id).style.display == val ? "none" : val );
			}
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
		<link href="css/default.css" rel="stylesheet" type="text/css" />

	';
	adesk_import_log_comment($prehtml);
	adesk_flush($prehtml);
	if ( $test ) {
		//adesk_import_log_comment(_a('Testing Import'));
	} else {
		//adesk_import_log_comment(_a('Starting Import'));
	}

	$r = adesk_import_src(true);
	// if didn't even connect, return
	if ( !$r['succeeded'] ) return $r;

	if (isset($_POST["import_file"])) {
		$post["import_file"] = $_POST["import_file"];
		$post["type"]        = $_POST["type"];
	}

	// default values
	$r['succeeded'] = false;
	$r['failed'] = 0;
	$r['found'] = count($r['data']);
	$r['imported'] = 0;
	$r['failedrows'] = array();
	$r['importedrows'] = array();

	$useProcesses = function_exists('adesk_process_create');
	// this process id
	if ( !isset($post['process_id']) ) {
		if ( $useProcesses ) {
			// comming from form submission, has action param here
			if (isset($r['delimiter_file']))
				$post['delimiter_file'] = $r['delimiter_file'];
			$post['process_id'] = adesk_process_create(adesk_http_param('action'), $r['found'], $post, false, '0000-00-00 00:00:00');
			adesk_process_setdata($post['process_id'], $post);
			/*
			if ( !$test and $prepareOnly ) {
				adesk_process_spawn(array('id' => $post['process_id'], 'stall' => 5 * 60));
			}
			*/
		} else {
			// old style - KB3
			$post['process_id'] = rand('100000', '900000');// setting a random process id
		}
	}
	$r['process_id'] = $post['process_id'];

	if ( $useProcesses ) {
		// autoupdate
		//$admin = adesk_admin_get();
		//$secondInterval = ( isset($admin['autoupdate']) ? $admin['autoupdate'] : 60 );
		$secondInterval = 10;

		$callback = ( !$test and $prepareOnly ) ? "parent.import_progressbar_callback" : "null";
		adesk_import_log_comment(
			'
				<script>//alert(\'process: ' . $r['process_id'] . '\');
					if (parent && parent.adesk_progressbar_register && parent.document.getElementById("progressBar")){
						parent.adesk_progressbar_register("progressBar", "' . $r['process_id'] . '", 0, ' . $secondInterval . ', true, ' . $callback . ');
						parent.processID = "' . $r['process_id'] . '";
					}
				</script>
			'
		);
	}

	if ( !$test and $prepareOnly ) {
		if ( $useProcesses ) adesk_process_spawn(array('id' => $post['process_id'], 'stall' => 5 * 60));
		$r['succeeded'] = true;
		return $r;
	}

	// options
	$post['delete_all'] = (int)isset($post['sync_option_delete_all']);

	if ( !isset($post['dest']) ) {
		$r['message'] = _a('Fields not mapped properly. Aborting...');
		return $r;
	}
	$post['fieldslist'] = adesk_import_mapping_get($post['dest']);

	// 2do: check against required fields again

	if ( isset($GLOBALS['_adesk_sync_lists']) ) unset($GLOBALS['_adesk_sync_lists']);

	adesk_import_log_comment(sprintf(_a('Found %d results.  Starting import now...'), $r['found']));
	$i = 0;
	foreach ( $r['data'] as $row ) {
		$i++;
		if ( $i > $offset ) {
			$rs = adesk_import_row($post, $row, $test);
			if ( $useProcesses ) adesk_process_update($post['process_id']);
			if ( $rs['succeeded'] ) {
				$r['importedrows'][] = $row;
			} else {
				$r['failedrows'][] = $row;
			}
			$r['imported'] += $rs['succeeded'];
			adesk_import_log_row($post, adesk_utf_deepconv("UTF-8", _i18n("utf-8"), $row), $rs);
		}
	}

	if ($r['imported'] == 0) {
		# Mark this as if it were completed; something is probably wrong here (e.g., import file
		# is missing, bad data, etc.)
		adesk_process_end($post['process_id']);
	}

	// cleanup if not a test
	if ( !$test ) {
		// delete all check
		if ( $post['delete_all'] ) adesk_ihook('adesk_import_delete_all', $post);
		//adesk_ihook('adesk_import_cleanup', $post, $r);
	}
	$r['failed'] = $r['found'] - $r['imported'] - $offset;
	// done
	$r['succeeded'] = ( $r['found'] == $r['imported'] );
	$r['message'] = sprintf(_a('Import Completed. %d items found, %d items imported.'), $r['found'], $r['imported']);
	$jsfunc = ( $r['succeeded'] ? 'adesk_result_show' : 'adesk_error_show' );
	if ( $r['found'] > 0 ) adesk_import_log_comment('</table>');

	adesk_import_log_comment(
		'
			<script>
				if (parent && parent.adesk_ui_api_callback)
					parent.adesk_ui_api_callback();
				if (parent && parent.' . $jsfunc . ')
					parent.' . $jsfunc . '("' . htmlentities($r['message']) . '");
			</script>
		'
	);

	if ( $useProcesses ) {
		adesk_import_log_comment(
			'
				<script>
					if (parent && parent.adesk_progressbar_register && parent.document.getElementById("progressBar")) {
						parent.adesk_progressbar_set("progressBar", 100);
						parent.adesk_progressbar_unregister("progressBar");
					}
				</script>
			'
		);
	}

	if ( $test ) {
		adesk_import_log_comment(_a('Import Test Completed'));
	} else {
		adesk_import_log_comment(_a('Import Completed'));
		// remove the import file
		if ( $r['filename'] and file_exists(adesk_cache_dir($r['filename'])) ) {
			@unlink(adesk_cache_dir($r['filename']));
		}
	}
	adesk_import_log_comment(_a('Imported: ') . $r['imported']);
	adesk_import_log_comment(_a('Failed: ') . $r['failed']);
	return $r;
}

function adesk_import_row($post, $row, $test) {
	return adesk_ihook('adesk_import_row', $post, $row, $test);
}

function adesk_import_report($processid) {
	$processid = (int)$processid;
	$table = adesk_IMPORT_LOGTABLE;
	$r = array(
		'counts' => array(),
		'lists'  => array(),
		'total'  => 0,
	);
	$query = "SELECT email, code, msg, tstamp FROM $table WHERE processid = '$processid' AND res = 0 ORDER BY tstamp DESC";
	$sql = adesk_sql_query($query);
	while ( $row = adesk_sql_fetch_assoc($sql) ) {
		$destination = adesk_ihook('adesk_import_row_report', $row);
		$r['lists'][$destination][] = $row;
	}
	$r['counts'] = array_map('count', $r['lists']);

	$r['total'] = array_sum($r['counts']);

	return $r;
}

function adesk_import_freshbooks_clients($account, $apikey, $page, $perpage, $useragent, $filter = array()) {
  $admin = adesk_admin_get();
  // check for oauth tokens
  $tokens = adesk_sql_select_one("connection_data", "#subscriber_import_service", "userid = '$admin[id]' AND service = 'freshbooks'");
  if ($tokens) {
    $tokens = unserialize($tokens);
    // $tokens["oauth_token"], $tokens["oauth_token_secret"]
    $header_params = array(
      "oauth_consumer_key" => "awebdesk",
      "oauth_token" => $tokens["oauth_token"],
      "oauth_signature" => "Tb6Bz3mCVSpAnywN5pmixJ4tBECGTVcBg&" . $tokens["oauth_token_secret"],
      "oauth_signature_method" => "PLAINTEXT",
      "oauth_version" => "1.0",
      "oauth_timestamp" => time(),
      "oauth_nonce" => md5(microtime()),
    );
    $httpheader = 'OAuth realm=""';
    foreach ($header_params as $k => $v) {
      $httpheader .= ',' . $k . '="' . urlencode($v) . '"';
    }
    $httpheader = array(
    	'Authorization: ' . $httpheader,
    );
  }
  else {
		$r['message'] = _a('There was a problem connecting to Freshbooks. Please re-connect to Freshbooks.');
		return $r;
  }
	$request = curl_init("https://$tokens[account].freshbooks.com/api/2.1/xml-in");
	$filter_xml = "";
	if ($filter) {
	  foreach ($filter as $field => $value) {
	     if ( trim($value) ) {
	       $filter_xml .= "<" . $field . ">" . htmlentities($value) . "</" . $field . ">";
	     }
	  }
	}
	$post_xml = '<request method="client.list">' . $filter_xml . '<page>' . $page . '</page><per_page>' . $perpage . '</per_page></request>';
	//curl_setopt($request, CURLOPT_HEADER, 0);
	curl_setopt($request, CURLOPT_HTTPHEADER, $httpheader);
	curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($request, CURLOPT_POSTFIELDS, $post_xml);
	curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($request, CURLOPT_USERAGENT, $useragent);
	//curl_setopt($request, CURLOPT_USERPWD, $apikey . ':X');
	$response = curl_exec($request);
	//dbg(curl_error($request));
	curl_close($request);
	$r = array();
	//dbg($response);
	// try to capture errors
	if ( preg_match('/status="fail"/', $response) ) {
	  $error = preg_match("/<error>[^<]+<\/error>/", $response, $text);
	  if ($text[0]) {
	    $error = strip_tags($text[0]);
	  }
	  if ($error) {
  		$r['message'] = $error;
  		return $r;
	  }
	}
	if ( !$response ) {
		$r['message'] = _a('Nothing was returned. Please verify your connection details with FreshBooks.');
		return $r;
	}
	$object = simplexml_load_string($response);
	return $object;
}

function adesk_import_tactile_people($account, $apikey, $page, $perpage, $useragent, $filter = "") {
	if ($filter) {
	  foreach ($filter as $field => $value) {
	    if ( trim($value) ) {
	      $filter .= "&" . $field . "=" . urlencode($value);
	    }
	  }
	}
	$url = 'https://' . $account . '.tactilecrm.com/people?api_token=' . $apikey . "&limit=" . $perpage . "&page=" . $page . $filter;
	//dbg($url);
	$request = curl_init($url);
	curl_setopt($request, CURLOPT_HEADER, 0);
	curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($request, CURLOPT_USERAGENT, $useragent);
	$response = curl_exec($request);
	curl_close($request);
	$object = json_decode($response);
	//dbg($object,1);
	$r = array();
	if ($object -> status == "error") {
		$message = get_object_vars($object -> messages);
		$r['message'] = implode(', ', $message);
		return $r;
	}
	return $object;
}

function adesk_import_zohocrm_records($apikey, $response_login_ticket, $page, $perpage, $filter = "", $fields_map, $cdata = true) {
  $api_method = "getRecords";
  $filter_string = "";
  // second form submission, after passing the filter step
	if ($filter && $fields_map) {
	  foreach ($filter as $fieldid => $value) {
	    if ( trim($value) ) {
	      $filter_string .= "&searchCondition=(" . urlencode($fields_map[$fieldid]) . "|contains|*" . urlencode($value) . "*)";
	    }
	  }
	  if ($filter_string) $api_method = "getSearchRecords";
	}
	$fromindex = ( ($page - 1) * $perpage) + 1;
	$toindex = $page * $perpage;
	$url = "https://crm.zoho.com/crm/private/xml/Contacts/" . $api_method . "?apikey=" . $apikey . "&ticket=" . $response_login_ticket . "&fromIndex=" . $fromindex . "&toIndex=" . $toindex . "&selectColumns=All&newFormat=2" . $filter_string;
	//if ($cdata) dbg($url);
	$request = curl_init($url);
	curl_setopt($request, CURLOPT_HEADER, 0);
	curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE);
	$response_data = curl_exec($request);
	curl_close($request);
	//if ($cdata) dbg($response_data);
	if ($cdata) {
	  $object = simplexml_load_string($response_data, 'SimpleXMLElement', LIBXML_NOCDATA);
	}
	else {
	  $object = simplexml_load_string($response_data, 'SimpleXMLElement');
	}
	return $object;
}

function adesk_import_helpdesk_users($post, $params) {
	$query = "";
	foreach( $params as $key => $value ) $query .= $key . '=' . urlencode($value) . '&';
	$query = rtrim($query, '& ');
	$url = rtrim($post['hd_url'], '/ ');
	$api = $url . '/manage/awebdeskapi.php?' . $query;
	$request = curl_init($api); // initiate curl object
	curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
	curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
	//curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment if you get no gateway response and are using HTTPS
	$response = (string)curl_exec($request); // execute curl fetch and store results in $response
	curl_close($request); // close curl object
	$r = array();
	if ( !$response ) {
		$r['message'] = _a('Nothing was returned. Do you have a connection to Help Desk server?');
		return $r;
	}
	$result = @unserialize($response);
	if ( !is_array($result) or !isset($result['result_code']) ) {
		$r['message'] = _a('Improper response received from Help Desk. Please try again later.');
		return $r;
	}
	if ( !$result['result_code'] ) {
		$r['message'] = $result['result_message'];
		return $r;
	}
	if ( !$result['rows'] ) {
		$r['message'] = _a('No records were found.');
		return $r;
	}
	return $result;
}

function adesk_import_batchbook($post, $external_options) {
	// include "location" (term used in batchbook to refer to a group of information) fields by default
	$fields_map = array(
	  "location" => "",
		"email" => "",
		"website" => "",
		"phone" => "",
		"cell" => "",
		"fax" => "",
		"postal_address" => "",
		"tags" => "",
  );
  $limit = 500;
	// the first call we use just to obtain the fields
  $result = adesk_import_batchbook_contacts($post, $limit);
  $result = get_object_vars($result);
  //dbg($result);
  $fields_map_exclude = array("locations", "mega_comments", "notes", "tags");
  $location_label_array = array("primary" => "Primary");
  if ( is_object($result["person"]) ) {
    // only one contact returned for this page
    $person = get_object_vars($result["person"]);
    foreach ($person as $field => $value) {
      $value = get_object_vars($value);
      if ($field == "locations") {
        if ( isset($value["location"]) && is_object($value["location"]) ) {
          // just one location being used
          $location_label_array = array($location["label"] => $value["location"] -> label);
        }
        elseif ( isset($value["location"]) && is_array($value["location"]) ) {
          // multiple locations being used
          $location_label_array = array("primary" => "Primary");
          foreach ($value["location"] as $location) {
            $location = get_object_vars($location);
            $location_label_array[ $location["label"] ] = $location["label"];
          }
        }
      }
      if ( !in_array($field, $fields_map_exclude) ) {
        if ( !isset($fields_map[$field]) ) $fields_map[$field] = "";
      }
    }
  }
  else {
    // more than one person returned for this page
    foreach ($result["person"] as $person) {
      $person = get_object_vars($person);
      foreach ($person as $field => $value) {
        $value = get_object_vars($value);
        if ($field == "locations") {
          if ( isset($value["location"]) && is_object($value["location"]) ) {
            // just one location being used
            $location = get_object_vars($value["location"]);
            if ( !isset($location_label_array[ $location["label"] ]) ) $location_label_array[ $location["label"] ] = $location["label"];
          }
          elseif ( isset($value["location"]) && is_array($value["location"]) ) {
            // multiple locations being used
            $location_label_array = array("primary" => "Primary");
            foreach ($value["location"] as $location) {
              $location = get_object_vars($location);
              if ( !isset($location_label_array[ $location["label"] ]) ) $location_label_array[ $location["label"] ] = $location["label"];
            }
          }
        }
        if ( !in_array($field, $fields_map_exclude) ) {
          if ( !isset($fields_map[$field]) ) $fields_map[$field] = "";
        }
      }
    }
  }
  ksort($fields_map);
  //dbg($fields_map);
  // only allow filtering on fields supported by batchbook API search method
  $fields_filter = array(
  	"location" => array("label" => "Location", "type" => "select", "options" => $location_label_array),
    "name" => array("label" => "Name", "type" => "textbox"),
    "email" => array("label" => "Email", "type" => "textbox"),
    "tag" => array("label" => "Tag", "type" => "textbox"),
    "state" => array("label" => "State", "type" => "textbox"),
  );
  if ($external_options) {
		// save connection details to DB
		$connection_data = array( 'batchbook_account' => $post['batchbook_account'], 'batchbook_token' => base64_encode($post['batchbook_token']) );
		$connection_save = subscriber_import_external_save($post['external'], $connection_data);
	  return $fields_filter;
	}
	// second submit (after the filter screen) starts here
	$filter = ( isset($post["external_options_filters"]) ) ? $post["external_options_filters"] : "";
	// now get as many contacts as possible - change the limit if ever needed
  $result = adesk_import_batchbook_contacts($post, $limit, $filter);
  $result = get_object_vars($result);
  //dbg($result);
  $people = array();
  if ( is_object($result["person"]) ) {
    // only one contact returned for this page/result
    $person = get_object_vars($result["person"]);
    $people_person = array();
    foreach ($person as $field => $value) {
      $add = true;
      if ($field == "locations") {
        $value = get_object_vars($value);
        if ( !isset($value["location"]) ) break;
        if ( isset($value["location"]) && is_object($value["location"]) ) {
          // just one location being used for this person
          $location = get_object_vars($value["location"]);
          if ($filter["location"] == "primary" && $location["primary"] != "true") {
            $add = false;
          }
          if ($filter["location"] != "primary" && $location["label"] != $filter["location"]) {
            $add = false;
          }
          $people_person["location"] = $location["label"];
          $people_person["email"] = ( !is_object($location["email"]) ) ? $location["email"] : "";
          $people_person["website"] = ( !is_object($location["website"]) ) ? $location["website"] : "";
          $people_person["phone"] = ( !is_object($location["phone"]) ) ? $location["phone"] : "";
          $people_person["cell"] = ( !is_object($location["cell"]) ) ? $location["cell"] : "";
          $people_person["fax"] = ( !is_object($location["fax"]) ) ? $location["fax"] : "";
          $people_person["postal_address"] = ( !is_object($location["street_1"]) ) ? $location["street_1"] : "";
          $people_person["postal_address"] .= ( !is_object($location["street_2"]) ) ? ", " . $location["street_2"] : "";
          $people_person["postal_address"] .= ( !is_object($location["city"]) ) ? ", " . $location["city"] : "";
          $people_person["postal_address"] .= ( !is_object($location["state"]) ) ? ", " . $location["state"] : "";
          $people_person["postal_address"] .= ( !is_object($location["postal_code"]) ) ? " " . $location["postal_code"] : "";
          $people_person["postal_address"] .= ( !is_object($location["country"]) ) ? " " . $location["country"] : "";
        }
        elseif ( isset($value["location"]) && is_array($value["location"]) ) {
          // multiple locations being used for this person
          $add = false;
          foreach ($value["location"] as $location) {
            $location = get_object_vars($location);
            // we keep re-setting these values until we hit the right location, then we break the loop
            $people_person["location"] = $location["label"];
            $people_person["email"] = ( !is_object($location["email"]) ) ? $location["email"] : "";
            $people_person["website"] = ( !is_object($location["website"]) ) ? $location["website"] : "";
            $people_person["phone"] = ( !is_object($location["phone"]) ) ? $location["phone"] : "";
            $people_person["cell"] = ( !is_object($location["cell"]) ) ? $location["cell"] : "";
            $people_person["fax"] = ( !is_object($location["fax"]) ) ? $location["fax"] : "";
            $people_person["postal_address"] = ( !is_object($location["street_1"]) ) ? $location["street_1"] : "";
            $people_person["postal_address"] .= ( !is_object($location["street_2"]) ) ? ", " . $location["street_2"] : "";
            $people_person["postal_address"] .= ( !is_object($location["city"]) ) ? ", " . $location["city"] : "";
            $people_person["postal_address"] .= ( !is_object($location["state"]) ) ? ", " . $location["state"] : "";
            $people_person["postal_address"] .= ( !is_object($location["postal_code"]) ) ? " " . $location["postal_code"] : "";
            $people_person["postal_address"] .= ( !is_object($location["country"]) ) ? " " . $location["country"] : "";
            if ($filter["location"] == "primary" && $location["primary"] == "true") {
              $add = true;
              break;
            }
            if ($filter["location"] != "primary" && $location["label"] == $filter["location"]) {
              $add = true;
              break;
            }
          }
        }
      }
      elseif ($field == "tags") {
        $value = get_object_vars($value);
        if ( isset($value["tag"]) ) {
          if ( is_object($value["tag"]) ) {
            // only one tag supplied for this person
            $tag = get_object_vars($value["tag"]);
            $tags = $tag["name"];
          }
          elseif ( is_array($value["tag"]) ) {
            // multiple tags supplied for this person
            $tag_string = "";
            foreach ($value["tag"] as $tag) {
              $tag = get_object_vars($tag);
              $tag_string .= $tag["name"] . ", ";
            }
            $tags = trim($tag_string, ", ");
          }
        }
        else {
          $tags = "";
        }
        $people_person["tags"] = $tags;
      }
      if ( !in_array($field, $fields_map_exclude) ) {
        if ( !isset($people_person[$field]) ) $people_person[$field] = ( !is_object($value) ) ? $value : "";
      }
    }
    ksort($people_person);
    if ($add) $people[] = $people_person;
  }
  else {
    // should be an array here - more than one contact returned for this page/result
    $counter = 0;
    //dbg( $result["person"] );
    foreach ($result["person"] as $person) {
      $person = get_object_vars($person);
      $people_person = array();
      //dbg($person,1);
      $add = true;
      foreach ($person as $field => $value) {
        if ($field == "locations") {
          $value = get_object_vars($value);
          if ( !isset($value["location"]) ) continue(2);
          if ( isset($value["location"]) && is_object($value["location"]) ) {
            // just one location being used for this person
            $location = get_object_vars($value["location"]);
            if ($filter["location"] == "primary" && $location["primary"] != "true") {
              $add = false;
            }
            if ($filter["location"] != "primary" && $location["label"] != $filter["location"]) {
              $add = false;
            }
            $people_person["location"] = $location["label"];
            $people_person["email"] = ( !is_object($location["email"]) ) ? $location["email"] : "";
            $people_person["website"] = ( !is_object($location["website"]) ) ? $location["website"] : "";
            $people_person["phone"] = ( !is_object($location["phone"]) ) ? $location["phone"] : "";
            $people_person["cell"] = ( !is_object($location["cell"]) ) ? $location["cell"] : "";
            $people_person["fax"] = ( !is_object($location["fax"]) ) ? $location["fax"] : "";
            $people_person["postal_address"] = ( !is_object($location["street_1"]) ) ? $location["street_1"] : "";
            $people_person["postal_address"] .= ( !is_object($location["street_2"]) ) ? ", " . $location["street_2"] : "";
            $people_person["postal_address"] .= ( !is_object($location["city"]) ) ? ", " . $location["city"] : "";
            $people_person["postal_address"] .= ( !is_object($location["state"]) ) ? ", " . $location["state"] : "";
            $people_person["postal_address"] .= ( !is_object($location["postal_code"]) ) ? " " . $location["postal_code"] : "";
            $people_person["postal_address"] .= ( !is_object($location["country"]) ) ? " " . $location["country"] : "";
          }
          elseif ( isset($value["location"]) && is_array($value["location"]) ) {
            // multiple locations being used for this person
            $add = false;
            foreach ($value["location"] as $location) {
              $location = get_object_vars($location);
              // we keep re-setting these values until we hit the right location, then we break the loop
              $people_person["location"] = $location["label"];
              $people_person["email"] = ( !is_object($location["email"]) ) ? $location["email"] : "";
              $people_person["website"] = ( !is_object($location["website"]) ) ? $location["website"] : "";
              $people_person["phone"] = ( !is_object($location["phone"]) ) ? $location["phone"] : "";
              $people_person["cell"] = ( !is_object($location["cell"]) ) ? $location["cell"] : "";
              $people_person["fax"] = ( !is_object($location["fax"]) ) ? $location["fax"] : "";
              $people_person["postal_address"] = ( !is_object($location["street_1"]) ) ? $location["street_1"] : "";
              $people_person["postal_address"] .= ( !is_object($location["street_2"]) ) ? ", " . $location["street_2"] : "";
              $people_person["postal_address"] .= ( !is_object($location["city"]) ) ? ", " . $location["city"] : "";
              $people_person["postal_address"] .= ( !is_object($location["state"]) ) ? ", " . $location["state"] : "";
              $people_person["postal_address"] .= ( !is_object($location["postal_code"]) ) ? " " . $location["postal_code"] : "";
              $people_person["postal_address"] .= ( !is_object($location["country"]) ) ? " " . $location["country"] : "";
              if ($filter["location"] == "primary" && $location["primary"] == "true") {
                $add = true;
                break;
              }
              if ($filter["location"] != "primary" && $location["label"] == $filter["location"]) {
                $add = true;
                break;
              }
            }
          }
        }
        elseif ($field == "tags") {
          $value = get_object_vars($value);
          if ( isset($value["tag"]) ) {
            if ( is_object($value["tag"]) ) {
              // only one tag supplied for this person
              $tag = get_object_vars($value["tag"]);
              $tags = $tag["name"];
            }
            elseif ( is_array($value["tag"]) ) {
              // multiple tags supplied for this person
              $tag_string = "";
              foreach ($value["tag"] as $tag) {
                $tag = get_object_vars($tag);
                $tag_string .= $tag["name"] . ", ";
              }
              $tags = trim($tag_string, ", ");
            }
          }
          else {
            $tags = "";
          }
          $people_person["tags"] = $tags;
        }
        if ( !in_array($field, $fields_map_exclude) ) {
          if ( !isset($people_person[$field]) ) $people_person[$field] = ( !is_object($value) ) ? $value : "";
        }
      }
      ksort($people_person);
      if ($add) $people[] = $people_person;
      $counter++;
    }
  }
  //dbg($people);
	$firstrow = current($people);
	$header = array_keys($fields_map);
	return adesk_array_csv($people, $header, $output = array());
}

function adesk_import_batchbook_contacts($post, $limit, $filter = "") {
	if ($filter) {
	  // if $filter is passed, it should be an array
	  foreach ($filter as $field => $value) {
	    if ($field != "location") {
  	    if ( trim($value) ) {
  	      $filter .= "&" . $field . "=" . urlencode($value);
  	    }
	    }
	  }
	}
  $url = 'https://' . $post['batchbook_account'] . '.batchbook.com/service/people.xml?limit=' . $limit . $filter;
  $request = curl_init($url);
  curl_setopt($request, CURLOPT_HEADER, 0);
  curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($request, CURLOPT_USERAGENT, 'AwebDesk Email Marketing software');
  curl_setopt($request, CURLOPT_USERPWD, $post['batchbook_token'] . ':x');
  $response = curl_exec($request);
  curl_close($request);
  $object = $object = simplexml_load_string($response, 'SimpleXMLElement');
  return $object;
}
function adesk_import_zendesk_users($post, $external_options) {
	$r = array("error" => 0, "message" => "");
	$user_agent = "Email Marketing";

	// this is only set after the second submit (after the filters screen)
	$filter = ( isset($post["external_options_filters"]) ) ? $post["external_options_filters"] : "";

	if (!$filter) {
		// get organizations
		$curl_url = "https://" . $post["zendesk_account"] . ".zendesk.com/organizations.json";
//dbg($curl_url);
		$request = curl_init($curl_url);
		curl_setopt($request, CURLOPT_HEADER, 0);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($request, CURLOPT_USERPWD, $post["zendesk_username"] . ":" . $post["zendesk_password"]);
		curl_setopt($request, CURLOPT_USERAGENT, $user_agent);
		$response = curl_exec($request);
//dbg($response);
		$response = strip_tags($response); // had an issue with json_decode returning nothing
		curl_close($request);

		$organizations = json_decode($response);
//dbg($organizations,1);

		if ( is_object($organizations) && isset($organizations->error) ) {
			$r["error"] = 1;
			// is it below, or: $organizations->error->message ?
			$r["message"] = $organizations->error;
			return $r;
		}

		$organization_array = array(0 => _a("Choose..."));
		foreach ($organizations as $organization) {
			if ( isset($organization->id) && isset($organization->name) ) {
				$organization_array[$organization->id] = $organization->name;
			}
		}

		// get groups
		$curl_url = "https://" . $post["zendesk_account"] . ".zendesk.com/groups.json";
//dbg($curl_url);
		$request = curl_init($curl_url);
		curl_setopt($request, CURLOPT_HEADER, 0);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($request, CURLOPT_USERPWD, $post["zendesk_username"] . ":" . $post["zendesk_password"]);
		curl_setopt($request, CURLOPT_USERAGENT, $user_agent);
		$response = curl_exec($request);
//dbg($response);
		curl_close($request);

		$groups = json_decode($response);
//dbg($groups);

		if ( is_object($groups) && isset($groups->error) ) {
			$r["error"] = 1;
			// is it below, or: $organizations->error->message ?
			$r["message"] = $groups->error;
			return $r;
		}

		$group_array = array(0 => _a("Choose..."));
		foreach ($groups as $group) {
			if ( isset($group->id) && isset($group->name) ) {
				$group_array[$group->id] = $group->name;
			}
		}

		$fields_filter = array(
			"organization" => array("label" => "Organization", "type" => "select", "options" => $organization_array),
			"group" => array("label" => "Group", "type" => "select", "options" => $group_array),
	    "search" => array(
	    	"label" => _a("Search phrase"),
	    	"type" => "textbox",
	    	"extra" => _a("To search user names"),
			),
	  );
	  if ($external_options) {
			// save connection details to DB
			$connection_data = array("zendesk_account" => $post["zendesk_account"], "zendesk_username" => $post["zendesk_username"], "zendesk_password" => base64_encode($post["zendesk_password"]));
			$connection_save = subscriber_import_external_save($post["external"], $connection_data);
		  return $fields_filter;
		}
	}

	// second submit (after the filter screen) starts here
//dbg($filter);

	$filter_array = array();

	if ($filter["organization"]) {
		// get users for this organization
		$filter_array["organization"] = array();
		$curl_url = "https://" . $post["zendesk_account"] . ".zendesk.com/organizations/" . $filter["organization"] . "/users.json";
//dbg($curl_url);
		$request = curl_init($curl_url);
		curl_setopt($request, CURLOPT_HEADER, 0);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($request, CURLOPT_USERPWD, $post["zendesk_username"] . ":" . $post["zendesk_password"]);
		curl_setopt($request, CURLOPT_USERAGENT, $user_agent);
		$response = curl_exec($request);
//dbg($response);
		curl_close($request);

		if (is_string($response)) {
			$users = json_decode($response);
//dbg($users);
			foreach ($users as $user) {
				$filter_array["organization"][$user->id] = $user;
			}
		}
	}

	if ($filter["group"]) {
		// get users for this group
		$filter_array["group"] = array();
		$curl_url = "https://" . $post["zendesk_account"] . ".zendesk.com/groups/" . $filter["group"] . "/users.json";
//dbg($curl_url);
		$request = curl_init($curl_url);
		curl_setopt($request, CURLOPT_HEADER, 0);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($request, CURLOPT_USERPWD, $post["zendesk_username"] . ":" . $post["zendesk_password"]);
		curl_setopt($request, CURLOPT_USERAGENT, $user_agent);
		$response = curl_exec($request);
//dbg($response);
		curl_close($request);

		if (is_string($response)) {
			$users = json_decode($response);
//dbg($users);
			foreach ($users as $user) {
				$filter_array["group"][$user->id] = $user;
			}
		}
	}

	if ($filter["search"]) {
		// get users for this search query
		$filter_array["search"] = array();
		$curl_url = "https://" . $post["zendesk_account"] . ".zendesk.com/users.json?query=" . urlencode($filter["search"]);
//dbg($curl_url);
		$request = curl_init($curl_url);
		curl_setopt($request, CURLOPT_HEADER, 0);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($request, CURLOPT_USERPWD, $post["zendesk_username"] . ":" . $post["zendesk_password"]);
		curl_setopt($request, CURLOPT_USERAGENT, $user_agent);
		$response = curl_exec($request);
//dbg($response);
		curl_close($request);

		if (is_string($response)) {
			$users = json_decode($response);
//dbg($users);
			foreach ($users as $user) {
				$filter_array["search"][$user->id] = $user;
			}
		}
	}

	// if there is at least one filter
	if ($filter_array) {
		// set up array that contains user ID as the key, and total count as value (however many times the user appears amongst all search filters)
		$user_counts = array();
		foreach ($filter_array as $filter_ => $filter_users) {
			foreach ($filter_users as $id => $user) {
				if ( !isset($user_counts[$id]) ) {
					$user_counts[$id] = 1;
				}
				else {
					$user_counts[$id]++;
				}
			}
		}

		// search for those users that appear as many times as there are filters, IE:
		/*
		Array
		(
		    [0] => 185987567
		    [1] => 185392786
		)
		*/
		// (users must be present in all filter results that are being used)
		$user_ids = array_keys($user_counts, count($filter_array));
		$users_ = array();
		foreach ($user_ids as $user_id) {
			foreach ($filter_array as $filter_ => $filter_users) {
				// find them in one of the filter arrays (that contain users from the API result)
				if ( isset($filter_users[$user_id]) ) {
					$users_[] = $filter_users[$user_id];
					break;
				}
			}
		}
	}
	else {
		// no filters set - pull all users
		$curl_url = "https://" . $post["zendesk_account"] . ".zendesk.com/users.json";
//dbg($curl_url);
		$request = curl_init($curl_url);
		curl_setopt($request, CURLOPT_HEADER, 0);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($request, CURLOPT_USERPWD, $post["zendesk_username"] . ":" . $post["zendesk_password"]);
		curl_setopt($request, CURLOPT_USERAGENT, $user_agent);
		$response = curl_exec($request);
//dbg($response);
		curl_close($request);
		$users_ = json_decode($response);
	}

//dbg($users_);

	$fields_map = array();
	$people = array();

	// looping through fetched users and prepare for output array
	foreach ($users_ as $user) {
		// only end-users
		if ($user->roles == 0) {
			$user = get_object_vars($user);
			$person_keys = array_keys($user);
			$person_keys[] = "first_name";
			$person_keys[] = "last_name";
			$user_ = array();
			foreach ($person_keys as $key) {
				if ( !isset($fields_map[$key]) && $key != "groups" &&$key != "name" ) {
					if ($key == "last_login") {
						// so it doesn't think it's "last name" on the mapping screen
						$fields_map["login_mostrecent"] = "login_mostrecent";
					}
					else {
						$fields_map[$key] = $key;
					}
				}
				// exclusions
				if ($key != "groups" && $key != "name" && $key != "first_name" && $key != "last_name") {
					if ($key == "last_login") {
						// so it doesn't think it's "last name" on the mapping screen
						$user_["login_mostrecent"] = $user["last_login"];
					}
					else {
						$user_[$key] = $user[$key];
					}
				}
				$first_name = $user["name"];
				$last_name = "";
				if ( preg_match("/\s/", $user["name"]) ) {
					list($first_name, $last_name) = explode(" ", $user["name"]);
				}
//dbg($first_name,1);
//dbg($last_name);
			}
			// additions
			$user_["first_name"] = $first_name;
			$user_["last_name"] = $last_name;
			$people[] = $user_;
		}
	}

//dbg($fields_map,1);
//dbg($people);

	$firstrow = current($people);
	$header = array_keys($fields_map);
	return adesk_array_csv($people, $header, $output = array());
}

?>
