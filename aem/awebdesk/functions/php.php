<?php


// javascript converter
function adesk_php_js($var) {
	if ( is_object($var) )
		$var = get_object_vars($var);
	if ( is_array($var) ) {
		$a = array();
		foreach ( $var as $k => $v ) {
			$a[] = adesk_php_js($k) . ' : ' . adesk_php_js($v);
		}
		//return "{\n\t" . implode(",\n\t", $a) . "\n}\n"; // use this to debug invalid chars in javascript
		return '{ ' . implode(', ', $a) . ' }';
	} elseif ( is_numeric($var) ) {
		return ( ( preg_match('/[a-zA-Z]/', $var) or ( strlen($var) > 1 and substr($var, 0, 1) == 0 and substr($var, 1, 1) != '.' ) ) ? '"' . $var . '"' : $var );
	} elseif ( is_string($var) ) {
		// taken from smarty_modifier_escape()
		// The first sequence (226 / 128 / 168) is UTF-8 for Unicode hex 2028, which is a line separator.  JS does NOT like this character in any of its strings.
		$escaped = strtr($var, array(chr(226) . chr(128) . chr(168) => '\\n\\n', '\\'=>'\\\\',"'"=>"\\'",'"'=>'\\"',"\r"=>'\\r',"\n"=>'\\n',"\t"=>'\\t','</'=>'<\/'));
		return '"' . $escaped . '"';
	} elseif ( is_bool($var) ) {
		return ( $var ? 'true' : 'false' );
	} else {
		return 'null';
	}
}


function adesk_magic_quotes() {
	// MAGIC QUOTES
	// turn off escaping
	@set_magic_quotes_runtime(0);
	@ini_set('magic_quotes_runtime', 0);

	# Whitelist: don't convert any of the following in $_SERVER only...
	$whitelist = array(
		"ALLUSERSPROFILE"    => 1,
		"USERPROFILE"        => 1,
		"APPL_PHYSICAL_PATH" => 1,
		"PATH_TRANSLATED"    => 1,
		"DOCUMENT_ROOT"      => 1,
		"SCRIPT_FILENAME"    => 1,
	);
	if ( is_null(adesk_php_global_get('_adesk_magic_quotes_reverted')) and get_magic_quotes_gpc() ) {
		$input = array(&$_GET, &$_POST, &$_COOKIE, &$_ENV, &$_SERVER);
		while ( list($k, $v) = each($input) ) {
			foreach ( $v as $key => $val ) {
				if (isset($whitelist[$key]) && isset($_SERVER[$key]))
					continue;
				$key2 = stripslashes($key);
				if ( $key != $key2 ) {
					unset($input[$k][$key]);
					$input[$k][$key2] = $val;
					$key = $key2;
				}
				if ( !is_array($val) ) {
					$input[$k][$key] = stripslashes($val);
					continue;
				}
				$input[] =& $input[$k][$key];
			}
		}
		unset($input);
	}
	adesk_php_global_set('_adesk_magic_quotes_reverted', 1);
}


function adesk_php_global_set($var, $val) {
	$GLOBALS[$var] = $val;
}

function &adesk_php_global_get($var) {
	$notSet = null;
	if ( isset($GLOBALS[$var]) )
		return $GLOBALS[$var];
	else
		return $notSet;
}

function adesk_php_time_limit($timeLimit = 30) {
	// allow nothing below 30 seconds (0 = indefinite)
	if ( $timeLimit == 0 or (int)ini_get('max_execution_time') < $timeLimit )
		@set_time_limit($timeLimit);
}


/**
 * This function sets php environment to operating level
 *
 * @param integer $timeLimit how long the script can run
 * @param integer $errorReporting no reporting is 0, report all is 1, include trapperr too is 2
 * @param boolean $startSession should it start a new session or not
 */
function adesk_php_environment($timeLimit = 30, $errorReporting = 2, $startSession = true) {
	// instant backwards compatibility
	if ( !defined('PATH_SEPARATOR') ) {
		define('PATH_SEPARATOR', ( OS_WINDOWS ? ';' : ':' ) );
	}
	// fix APACHE's SERVER_NAME != HTTP_HOST disrepancy
	if ( isset($_SERVER['SERVER_NAME']) and isset($_SERVER['HTTP_HOST']) ) {
		if ( $_SERVER['HTTP_HOST'] == 'www.' . $_SERVER['SERVER_NAME'] ) {
			$_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];
		}
		elseif ( 'www.' . $_SERVER['HTTP_HOST'] == $_SERVER['SERVER_NAME'] ) {
			$_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];
		}
	}
	// fix IPV6
	if ( isset($_SERVER['REMOTE_ADDR']) ) {
		$_SERVER['REMOTE_ADDR'] = adesk_str_noipv6($_SERVER['REMOTE_ADDR']);
	}
	// reset include path (no server's pear file inclusion)
	set_include_path('.');
	// limit must be at least 30 seconds (or 0 for indefinite run)
	if ( !is_null($timeLimit) ) {
		$timeLimit = (int)$timeLimit;
		if ( $timeLimit != 0 and $timeLimit < 30 ) $timeLimit = 30;
		adesk_php_time_limit($timeLimit);
	}
	// INITIAL ERROR REPORTING
	if ( $errorReporting == 0 ) {
		// report nothing
		error_reporting(0);
	} else {
		// report everything
		error_reporting(E_ALL);
		if ( $errorReporting == 2 ) {
			// set trapperr
			require_once(awebdesk_functions('trapperr.php'));
		}
	}
	if ( $startSession ) @session_start();
	adesk_magic_quotes();
}


/**
 * This function figures out what is the application encoding
 *
 * @return string ioncube, zend, source, or blank for none of the above
 */
function adesk_php_encoding() {
	$f = file_get_contents(adesk_base('manage/functions/post-ftp.php'));
	if ( !$f ) return '';
	if ( preg_match('/ioncube/i', $f) ) return 'ioncube';
	if ( preg_match('/zend/i', $f) ) return 'zend';
	if ( preg_match('/updater\.php/i', $f) ) return 'source';
	return '';
}


function adesk_php_settings($conn = null) {
    $inis = function_exists('ini_get_all') ? ini_get_all() : array();
    // tried doing this in smarty, but it doesn't work because it would be $inis.mysql.default_host.local_value
    $mysql_vars = array (
        'allow_persistent',
        'connect_timeout',
        'default_host',
        'default_password',
        'default_port',
        'default_socket',
        'default_user',
        'max_links',
        'max_persistent',
    );
    $mysql = array();
    foreach ( $mysql_vars as $key ) {
    	if ( !isset($inis['mysql.'.$key]) ) $inis['mysql.'.$key]['local_value'] = @ini_get('mysql.'.$key);
        $mysql[$key] = $inis['mysql.'.$key]['local_value'];
    }
	$r = array(
		'phpversion' => phpversion(),
        'mysql_ClientInfo' => mysql_get_client_info(),
        'mysql' => $mysql,
        'inis' => $inis
	);
	if ( !is_null($conn) ) {
		$r = array_merge($r, adesk_php_settings_mysql($conn));
	}
	return $r;
}

function adesk_php_settings_mysql($conn) {
	$r = array(
        'mysqlversion' => mysql_get_server_info($conn),
        'mysql_HostInfo' => mysql_get_host_info($conn),
        'mysql_Protocols' => mysql_get_proto_info($conn),
        'mysql_ClientInfo' => mysql_get_client_info(),
	);
	return $r;
}


function adesk_php_inisize($val) {
	$val = trim($val);
	if ( $val == '' ) return 0;
	$last = strtolower($val{strlen($val)-1});
	switch($last) {
		// The 'G' modifier is available since PHP 5.1.0
		case 'g':
			$val *= 1024;
		case 'm':
			$val *= 1024;
		case 'k':
			$val *= 1024;
	}
    return $val;
}

function adesk_php_rewrite_check() {
	$conffile  = adesk_base();
	if ( adesk_site_isknowledgebuilder() and !adesk_site_isstandalone() ) $conffile = dirname(__FILE__);
	$conffile .= '/.htaccess';
	$rwCheck = array();
	$rwCheck['apache'] = ( isset($_SERVER['SERVER_SOFTWARE'])) and adesk_str_instr('apache', strtolower($_SERVER['SERVER_SOFTWARE']) );
	$rwCheck['iis'] = ( isset($_SERVER['SERVER_SOFTWARE'])) and adesk_str_instr('iis', strtolower($_SERVER['SERVER_SOFTWARE']) );
	$rwCheck['modules'] = ( $rwCheck['apache'] and function_exists('apache_get_modules') );
	$rwCheck['rewrite'] = false;
	if ( $rwCheck['modules'] ) {
		$apacheMods = apache_get_modules();
		$rwCheck['rewrite'] = in_array('mod_rewrite', $apacheMods);
	} elseif ( $rwCheck['iis'] ) {
		$rwCheck['rewrite'] = true;
	}
	$rwCheck['htaccess'] = file_exists($conffile);
	if (isset($GLOBALS["_hosted_account"]))
		$rwCheck['htaccess'] = true;
	$rwCheck['possible'] = ( $rwCheck['apache'] and $rwCheck['htaccess'] );
	$rwCheck['available'] = ( $rwCheck['possible'] /*and $rwCheck['modules']*/ and $rwCheck['rewrite'] );
	$rwCheck['configured'] = false;
	if ( $rwCheck['possible'] ) {
		if (isset($GLOBALS["_hosted_account"])) {
			$rwCheck['configured'] = true;
		} else {
			$htaccess = adesk_file_get($conffile);
			$rwCheck['configured'] = ( adesk_str_instr("URL Rewrite Support", $htaccess) );
		}
	}
	return $rwCheck;
}

function adesk_php_stdin() {
	$r = '';
	if ( $fp = fopen('php://stdin', 'rb') ) {
		while ( $line = fread($fp, 1024) ) {
			$r .= $line;
		}
		fclose($fp);
	}
	return $r;
}



/*
	REPLACEMENT FUNCTIONS
*/
if ( !function_exists('file_get_contents') ) {
	function file_get_contents($f) {
		$file = '';
		$aLines = file($f);
		foreach ( $aLines as $sLine ) {
			$file .= $sLine;
		}
		return $file;
	}
}
if ( !function_exists('file_put_contents') ) {
	if ( !defined('FILE_APPEND') )
		define('FILE_APPEND', 1);
	if ( !defined('FILE_TEXT') )
		define('FILE_TEXT', 1);
	if ( !defined('FILE_BINARY') )
		define('FILE_BINARY', 0);
	function file_put_contents($n, $d, $flag = null) {
		$mode = ( $flag == FILE_APPEND || strtoupper($flag) == 'FILE_APPEND' ) ? 'a' : 'w';
		// 2DO: add binary mode here!
		$f = @fopen($n, $mode);
		if ($f === false) {
			return 0;
		} else {
			if ( is_array($d) ) $d = implode('', $d);
			$bytes_written = fwrite($f, $d);
			fclose($f);
			return $bytes_written;
		}
	}
}


function adesk_flush($str = '') {
	//echo $str;
	//flush();
	@ob_start();
	echo $str;
	//@ob_end_clean();
	@flush();
	@ob_end_flush();
}


# Try to keep the script going when it's been running for a while...

function adesk_php_keepalive() {
	adesk_flush(' ');
}


/*
	TESTING ONLY!
*/
if ( !function_exists('dbg') ) {
	function dbg/* i hate doing this, but i gotta */($var, $dontDie = false) {
		if ( !headers_sent() ) {
			// Send 'found a page' status
			header("HTTP/1.0 200 OK");
			header("Status: 200 OK");
		}
		echo "\n<pre>\nVartype: " . gettype($var) . "\n";
		if ( is_array($var) ) {
			echo 'Elements: ' . count($var) . "\n";
		} elseif ( is_string($var) ) {
			echo 'Length: ' . strlen($var) . "\n";
		}
		if ( !function_exists('adesk_http_geturl' ) ) require_once(dirname(__FILE__) . '/http.php');
		if ( strpos(adesk_http_geturl(), '/awebdeskapi.php') !== false ) {
			print_r($var);
		} else {
			echo htmlentities( print_r($var, true) );
		}
		if ( is_object($var) ) {
			echo "Methods:\n";
			echo htmlentities( print_r(get_class_methods($var), true) ) . "\n";
			if ( get_parent_class($var) )
				echo "Parent: " . get_parent_class($var) . "\n";
		}
		echo "\n</pre>\n";
		flush();
		if ( !$dontDie ) exit;
	}
}

function adesk_microtime_get() {
	list($usec, $sec) = explode(' ', microtime());
	return ((float)$usec + (float)$sec);
}

// first call to
function adesk_microtime_set($msg) {
	if ( !isset($GLOBALS['_adesk_microtimers']) ) $GLOBALS['_adesk_microtimers'] = array();
	if ( !isset($GLOBALS['_adesk_microtimers']['start']) and $msg != 'start' ) $GLOBALS['_adesk_microtimers']['start'] = adesk_microtime_get();
	$GLOBALS['_adesk_microtimers'][$msg] = adesk_microtime_get();
}
adesk_microtime_set('start');

if ( !function_exists('dt') ) {
	function dt($sort = true) {
		if ( !isset($GLOBALS['_adesk_microtimers']) or !is_array($GLOBALS['_adesk_microtimers']) or !count($GLOBALS['_adesk_microtimers']) ) {
			dbg('Measuring not active...');
		}
		$stats = $GLOBALS['_adesk_microtimers'];
		$usefull = array();
		reset($stats);
		//list($before) = $stats;
		$before = 'start';
		$first = $before;
		foreach ( $stats as $msg => $tstamp ) {
			if ( $msg != $first ) {
				$time = $tstamp - $stats[$before];
				$before = $msg;
				$time = round($time, 6);
				if ( strlen($time) < 8 ) $time .= str_repeat(0, 8 - strlen($time));
				$usefull[$msg] = $time;
			}
		}
		if ( $sort ) arsort($usefull);
		$output = '<pre>';
		foreach ( $usefull as $msg => $tstamp ) {
			$output .= "\n{$tstamp} sec. = $msg";
		}
		$total = $stats[$before] - $stats['start'];
		$total = round($total, 6);
		$output .= "\n\nPAGE TOTAL    = $total seconds.";
		$output .= '</pre>';
		return $output;
	}
}

function _debug($var, $dontDie = false) {
	if ( !headers_sent() ) {
		// Send 'found a page' status
		header("HTTP/1.0 200 OK");
		header("Status: 200 OK");
	}
	echo '<pre>Vartype: ' . gettype($var) . "\n";
	if ( is_array($var) )
		echo 'Elements: ' . count($var) . "\n";
	elseif ( is_string($var) )
		echo 'Length: ' . strlen($var) . "\n";
	echo htmlentities( print_r($var, true) );
	if ( is_object($var) ) {
		echo "Methods:\n";
		echo htmlentities( print_r(get_class_methods($var), true) );
		if ( get_parent_class($var) )
			echo 'Parent:' . get_parent_class($var) . "\n";
	}
	echo '</pre>';
	flush();
	if ( !$dontDie ) exit;
}


function adesk_charset_convert_gp($in, $out) {
	if ( !function_exists("iconv") ) return;
	if ( $in  == '' ) return;
	if ( $out == '' ) return;
	if ( $in  == $out ) return;

	$in  = strtoupper($in);
	$out = strtoupper($out);

	$input = array(&$_GET, &$_POST);
	while ( list($k, $v) = each($input) ) {
		foreach ( $v as $key => $val ) {
			if ( !is_array($val) ) {
				$input[$k][$key] = @iconv($in, "$out//IGNORE", $val);
				continue;
			}
			$input[] =& $input[$k][$key];
		}
	}
	unset($input);
}


function adesk_php_spawn($cmd, $debug = false) {
	$r = array(
		'in' => '',
		'out' => '',
		'err' => '',
		'res' => false,
	);

	$descriptorspec = array(
		0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
		1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
		2 => array("pipe", "w") // stderr is a pipe to write to
	);

	$cwd = null; // dirname(__FILE__);
	$env = array(/*'suppress_errors' => true, 'bypass_shell' => true*/);

	$process = proc_open($cmd, $descriptorspec, $pipes, $cwd, $env);

	if ( is_resource($process) ) {

		/*
		if ( isset($GLOBALS['site']['stream_set_blocking']) and $GLOBALS['site']['stream_set_blocking'] ) {
			stream_set_blocking($pipes[0], 0);
			stream_set_blocking($pipes[1], 0);
			stream_set_blocking($pipes[2], 0);
		}
		*/

		//print_r ( proc_get_status ( $process ) );exit;

		// $pipes now looks like this:
		// 0 => writeable handle connected to child stdin
		// 1 => readable handle connected to child stdout
		// 2 => writeable handle connected to child stdin (echo)

		// IN
		//fwrite($pipes[0], "");
		//fflush($pipes[0]);
		fclose($pipes[0]);

		// OUT
		while ( !feof($pipes[1]) )
		{
			$res = fgets($pipes[1]);
			//$res = str_replace("\033[1m", "<span style='color: #000080;'>", str_replace("\033[0m", "</span>", $res));
			if ( !isset($_SERVER['REMOTE_ADDR']) ) $res = htmlentities($res);
			$r['out'] .= $res;
		}
		fclose($pipes[1]);

		// ERRORS
		while ( !feof($pipes[2]) )
		{
			$res = fgets($pipes[2]);
			//$res = str_replace("\033[1m", "<span style='color: #000080;'>", str_replace("\033[0m", "</span>", $res));
			if ( !isset($_SERVER['REMOTE_ADDR']) ) $res = htmlentities($res);
			$r['err'] .= $res;
		}
		fclose($pipes[2]);

		// old code
		//echo !isset($_SERVER['REMOTE_ADDR']) ? stream_get_contents($pipes[1]) : htmlentities(stream_get_contents($pipes[1]));


		// It is important that you close any pipes before calling
		// proc_close in order to avoid a deadlock
		$res = proc_close($process);
		$r['res'] = $res == 0;

		if ( $debug ) {
			if ( $r['in' ] ) adesk_flush(nl2br("Input:\n"  . $r['in' ]));
			if ( $r['out'] ) adesk_flush(nl2br("Output:\n" . $r['out']));
			if ( $r['err'] ) adesk_flush(nl2br("Error:\n"  . $r['err']));
		}

		return $r; // 0 means 'completed'!
	}
}

function adesk_php_print($str, $type = 'text', $html = false, $linenumbers = false, $element = 'div' , $props = '') {
	if ( $type != "file" or !file_exists($str) ) $type = "string";
	switch ( $type ) {
		case "file":
			$str = ( !$html ? htmlspecialchars(adesk_file_get($str)) : highlight_file($str, true) );
			break;
		case "string":
		default:
			$str = ( !$html ? htmlspecialchars($str) : highlight_string($str, true) );
	}
	if ( $linenumbers ) {
		$arr = explode("<br />", $str);
		$str = "<ol>\n";
		foreach ( $arr as $line => $syntax ) {
			if ( !$syntax ) $syntax = "&nbsp;";
			$str .= "<li><code>" . $syntax . "</code></li>\n";
		}
		$str .= "</ol>\n";
	}
	if ( $html ) {
		$str = "<$element $props>\n" . $str . "</$element>";
	}
	return $str;
}

function adesk_php_autoincrement_fix($reftable) {
	// check if auto_increment is here
	$t = adesk_sql_select_row("SHOW FIELDS FROM `#$reftable` LIKE 'id'");
	if ( $t and isset($t['Extra']) ) {
		$bad = trim(strtolower($t['Extra'])) != 'auto_increment';
		if ( $bad ) {
			if ( isset($_GET['fixautoinc']) ) {
				if ( isset($GLOBALS["adesk_prefix_whitelist"]) and $GLOBALS["adesk_prefix_whitelist"] ) {
					$tables = $GLOBALS["adesk_prefix_whitelist"];
				} else {
					$tables = adesk_prefix_tables();
				}
				$cnt = count($tables);
				echo "Fixing $cnt tables...<br />\n";
				foreach ( $tables as $v ) {
					$bad = false;
					$done = false;
					$t = adesk_sql_select_row("SHOW FIELDS FROM `$v` LIKE 'id'");
					if ( $t and isset($t['Extra']) ) {
						if ( strtolower(substr($t['Type'], 0, 4)) == 'int(' ) {
							$bad = trim(strtolower($t['Extra'])) != 'auto_increment';
							if ( $bad ) {
								$done = (
									adesk_sql_query("ALTER TABLE `$v` CHANGE `id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT ")
								and
									adesk_sql_query("DELETE FROM `$v` WHERE `id` = 0 ")
								);
							}
						}
					}
					echo "$v : ";
					if ( $bad ) {
						echo 'BAD';
						if ( $done ) echo ' - <strong>FIXED</strong>';
					} else {
						echo 'good - does not need fixing';
					}
					echo "<br />\n";
				}
				die('Go back to <a href="desk.php">Admin Panel</a>.');
			} else {
				die('<strong>Database Error Detected!</strong><br /><a href="?fixautoinc">Click here to run repair.</a>');
			}
		}
	}
}

function adesk_php_real_ip() {
	// get real IP even if proxy is used
	if (!empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], 'unknown'))
	{
		return trim($_SERVER['HTTP_CLIENT_IP']);
	}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], 'unknown'))
	{
		$ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
		return trim($ips[0]);
	}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED']) && strcasecmp($_SERVER['HTTP_X_FORWARDED'], 'unknown'))
	{
		return trim($_SERVER['HTTP_X_FORWARDED']);
	}
	elseif (!empty($_SERVER['HTTP_FORWARDED']) && strcasecmp($_SERVER['HTTP_FORWARDED'], 'unknown'))
	{
		return trim($_SERVER['HTTP_FORWARDED']);
	}
	return trim($_SERVER['REMOTE_ADDR']);
}

function adesk_php_debug_backtrace($remove = array('args', 'object')) {
	$d = debug_backtrace();
	if ( !count($remove) ) return $d;
	foreach ( $d as $k => $v ) {
		foreach ( $remove as $r ) {
			if ( isset($v[$r]) ) unset($d[$k][$r]);
		}
	}
	return $d;
}

function adesk_php_info($what = -1) {
	ob_start();
	phpinfo($what);
	$phpinfo = ob_get_contents();
	ob_end_clean();
	$phpinfo = preg_replace('/.*<body>(.*)<\/body>.*/si', '$1', $phpinfo);
	/*
	$posOpen = strpos(strtolower($phpinfo), '<body>');
	$posClose = strpos(strtolower($phpinfo), '</body>');
	if ( $posClose ) $phpinfo = substr($phpinfo, 0, $posClose);
	if ( $posOpen ) $phpinfo = substr($phpinfo, $posOpen + 6);
	*/
	return $phpinfo;
}

?>
