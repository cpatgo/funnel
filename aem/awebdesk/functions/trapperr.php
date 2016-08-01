<?php
/**
 * Trapperr Init.
 *
 * INCLUDE: This script loads Trapperr Function for custom error-handling.
 *
 * @package ACGlobal
 * @subpackage Trapperr
 * @author Milos Srdjevic
 *
 */



// user defined error handling function
function adesk_php_error_handler($errno, $errmsg, $filename, $linenum, $vars) {
	global $_CONFIG;


// define an assoc array of error string
// in reality the only entries we should
// consider are 2,8,256,512 and 1024
	$errortype = array
	(
		1   =>  "Error",
		2   =>  "Warning",
		4   =>  "Parsing Error",
		8   =>  "Notice",
		16  =>  "Core Error",
		32  =>  "Core Warning",
		64  =>  "Compile Error",
		128 =>  "Compile Warning",
		256 =>  "User Error",
		512 =>  "User Warning",
		1024=>  "User Notice",
		2047=>  "E_ALL",
		2048=>  "E_STRICT",
		8192=>  "E_DEPRECATED",
		16384=> "E_USER_DEPRECATED",
	);
// set of errors for which a var trace will be saved
	$user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);
	if ( $errno == 2048 ) return;
	if ( $errno == 8192 || $errno == 16384 ) return;
	if ( error_reporting() == 0 ) return;
	if ( $errno == 8 and !file_exists(dirname(dirname(__FILE__)) . '/tools/svnlog.class.php') ) return;
	if ( strpos(strtolower($errmsg), 'headers already sent') !== false ) return;

	# Skip errors about date.timezone
	if (strpos(strtolower($errmsg), 'date.timezone') !== false) return;
	
	$printError = (bool)$_CONFIG['trapperr']['screen'];
	if ( in_array($errno, $user_errors) && strpos(strtolower($errmsg), 'magpierss:') !== false ) {
		$printError = false;
	}

	$headers = '';
	$user = ( isset($GLOBALS['admin']['id']) ? $GLOBALS['admin']['id'] : 0 );
	$ip = ( isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1' );
	@$host = gethostbyaddr($ip);
	$referer = ( !isset($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'] );
	$session = ( isset($_SESSION) ? session_id() : '' );
	$url = adesk_http_geturl();
// serialize additional vars
/*
	if ( isset($vars['GLOBALS']) ) {
		$v1 = array();
		foreach ( $vars as $k => $v ) {
			if ( $k != 'GLOBALS' ) $v1[$k] = $v;
		}
		$txt_vars = var_export($v1, true);
	} else {
		$txt_vars = var_export($vars, true);
	}
*/
	$vars["_POST"] = $_POST;
	$vars["_GET"]  = $_GET;
	$txt_vars = isset($GLOBALS["_hosted_account"]) || (in_array($errno, $user_errors) && !defined('adesk_TRAPPERR_NOVARS')) ? print_r($vars, true) : '';

/*
	SQL 4 database log record
*/
	if ( $_CONFIG['trapperr']['db'] == 1 ) {
		$sql_filename = adesk_sql_escape($filename);
		$sql_url = adesk_sql_escape($url);
		// see if we can insert it (we do not want to flood a database)
		if ( isset($GLOBALS['db_link']) ) {
			$sql = adesk_sql_query("SELECT COUNT(*) FROM `{$_CONFIG['trapperr']['db_table']}` WHERE tstamp > CURDATE()");
			if ( $sql )
				list($found) = mysql_fetch_row($sql);
			else $found = 11;
			if ( $found < 11 ) {
				$sql = adesk_sql_query("
					SELECT
						COUNT(*)
					FROM
						{$_CONFIG['trapperr']['db_table']}
					WHERE
						errnumber = '$errno'
					AND
						filename = '$sql_filename'
					AND
						url = '$sql_url'
					AND
						linenum = '$linenum'
				");
				if ( $sql )
					list($found) = mysql_fetch_row($sql);
				else $found = 1;
				if ( $found > 0 ) $found = 11;
			}
		} else {
			$found = 11;
		}
		if ( (int)$found < 11 ) {
// timestamp for the error entry
			$dt = date( $_CONFIG['trapperr']['sql_date_format'] );
// prepare SQL string
			if ( preg_match('/mysql_.*\(\) expects parameter \d+ to be resource, boolean given/', $errmsg) ) {
				$mysqlerr = adesk_sql_error();
				if ( $mysqlerr ) $errmsg .= ' (mysql error: ' . adesk_sql_error_number() . ': ' . $mysqlerr . ')';
			}
			$sql_errmsg = adesk_sql_escape($errmsg);
			$sql = "
				INSERT INTO
					`{$_CONFIG['trapperr']['db_table']}`
				(
					id,
					tstamp,
					errnumber,
					errmessage,
					filename,
					url,
					linenum,
					session,
					userid,
					ip,
					host,
					referer
				) VALUES (
					'0',
					'$dt',
					'$errno',
					'$sql_errmsg',
					'$sql_filename',
					'$sql_url',
					'$linenum',
					'$session',
					'$user',
					'$ip',
					'$host',
					'$referer'
				)
			";
			if ( isset($GLOBALS['db_link']) ) {
				// execute the query
				$sql_return = @adesk_sql_query($sql);
				if ( $sql_return ) {
					// fetch insert id
					$insertID = adesk_sql_insert_id();
					// add vars dump
					$sql_vars = adesk_sql_escape($txt_vars);
					@adesk_sql_query("UPDATE `{$_CONFIG['trapperr']['db_table']}` SET vars = '$sql_vars' WHERE id = '$insertID'");
					// add backtrace
					$sql_trace = adesk_sql_escape(print_r(debug_backtrace(), true));
					@adesk_sql_query("UPDATE `{$_CONFIG['trapperr']['db_table']}` SET backtrace = '$sql_trace' WHERE id = '$insertID'");
				}
			} else $sql_return = false;
/*
			if ( !$sql_return ) {
				$sql = "Error not inserted into database: \n\n$sql";
				mail_me('text', 'Trapperr', 'trapperr@awebdesk.com', $sql, $_CONFIG['trapperr']['mail_subject'], $_CONFIG['trapperr']['mail_to']);
			}
*/
		}
	}

/*
	TXT 4 mailling log entry
*/

	if ( $_CONFIG['trapperr']['mail'] == 1 and 1 == 0 ) {
// timestamp for the error entry
		$dt = date( $_CONFIG['trapperr']['date_format'] );
// prepare TXT string
		$txt = "\n";
		$txt .= "Date: $dt\n";
		$txt .= "\tError no: $errno\n";
		$txt .= "\tError type: $errortype[$errno]\n";
		$txt .= "\tMessage: $errmsg\n";
		$txt .= "\tFile Location:  $filename\n";
		$txt .= "\tAddress:  $url\n";
		$txt .= "\tLine: $linenum\n";
		$txt .= "\tSession: $session\n";
		$txt .= "\tUser: $user\n";
		$txt .= "\tIP: $ip\n";
		$txt .= "\tHost: $host\n";
		$txt .= "\tReferer: $referer\n";
		$txt .= "\tVars:\n$txt_vars\n";
		//$txt .= "\tBacktrace:\n$txt_trace\n"; // empty
// send an e-mail if there is a critical user error
		adesk_mail_send('text', 'Trapperr', 'trapperr@awebdesk.com', $txt, $_CONFIG['trapperr']['mail_subject'], $_CONFIG['trapperr']['mail_to'], 'Bug Reports');
	}

/*
	HTML 4 on-screen error display
*/

	if ( $printError ) {
// timestamp for the error entry
		$dt = date( $_CONFIG['trapperr']['date_format'] );
		$divID = substr(md5(microtime()), 0, 6);
		if ( defined('AWEBVIEW') ) $txt_vars = '';
// output TXT string
?>
<a href="javascript:void(0)" onclick="var x = document.getElementById('<?php echo $divID; ?>main'); x.style.display = (x.style.display=='none' ? 'block' : 'none')" style="font-size:10px; color:#FF9900; text-decoration:none;">[&nbsp;+&nbsp;]</a>
<div class="trapperr" style="border: 2px dashed #800000; background-color: #C0C0C0; padding: 4px; margin: 10px; display: none;" id="<?php echo $divID; ?>main">
	<i>File Location:</i> <b><?php echo $filename; ?></b><br />
	<i>Line:</i> <b><?php echo $linenum; ?></b><br />
	<i>Message:</i> <b><?php echo $errmsg; ?></b><br />
	<i>Error Type:</i> <b><?php echo $errortype[$errno]; ?></b>, <i>Error Number:</i> <b><?php echo $errno; ?></b><br />
	<i>Address:</i> <b><?php echo $url; ?></b><br />
	<i>Session:</i> <b><?php echo $session; ?></b><br />
	<i>User:</i> <b><?php echo $user; ?></b><br />
	<i>Host:</i> <b><?php echo $host; ?></b>, <i>IP:</i> <b><?php echo $ip; ?></b><br />
<?php if ( $referer ) { ?>
	<i>Referer:</i> <b><?php echo $referer; ?></b><br />
<?php } ?>
	<i>Date:</i> <b><?php echo $dt; ?></b><br />
	<a href="javascript:void(0)" onclick="var x = document.getElementById('<?php echo $divID; ?>vars'); x.style.display = (x.style.display=='none' ? 'block' : 'none')">Vars</a> &middot;
	<a href="javascript:void(0)" onclick="var x = document.getElementById('<?php echo $divID; ?>trace'); x.style.display = (x.style.display=='none' ? 'block' : 'none')">Trace</a><br />
	<div class="trapperr_vars" id="<?php echo $divID; ?>vars" style="display: none; border: 1px solid Black;">
		<i>Vars:</i><br />
		<pre><?php echo $txt_vars; ?></pre>
	</div>
	<div class="trapperr_trace" id="<?php echo $divID; ?>trace" style="display: none; border: 1px solid Black;">
		<i>Backtrace:</i><br />
		<pre><?php if ( !defined('AWEBVIEW') and in_array($errno, $user_errors) and !defined('adesk_TRAPPERR_NOBACKTRACE') ) echo print_r(debug_backtrace(), true); ?></pre>
	</div>
</div>
<?php
		flush();
	}

/*
	XML 4 filesystem log file
*/

	if ( $_CONFIG['trapperr']['logfile'] == 1 ) {
// serialize additional vars
		$xml_vars = ( function_exists('wddx_serialize_value') ? wddx_serialize_value($vars, 'Variables') : '' );
		$xml_trace = ( function_exists('wddx_serialize_value') ? wddx_serialize_value(debug_backtrace(), 'Variables') : '' );
// timestamp for the error entry
		$dt = date( $_CONFIG['trapperr']['xml_date_format'] );
// prepare XML string
		$xml = "<errorentry>\n";
		$xml .= "\t<datetime>$dt</datetime>\n";
		$xml .= "\t<errornum>$errno</errornum>\n";
		$xml .= "\t<errortype>$errortype[$errno]</errortype>\n";
		$xml .= "\t<errormsg>$errmsg</errormsg>\n";
		$xml .= "\t<scriptname>$filename</scriptname>\n";
		$xml .= "\t<scriptlinenum>$linenum</scriptlinenum>\n";
		$xml .= "\t<session>$session</session>\n";
		$xml .= "\t<user>$user</user>\n";
		$xml .= "\t<ip>$ip</ip>\n";
		$xml .= "\t<host>$host</host>\n";
		$xml .= "\t<referer>$referer</referer>\n";
		$xml .= "\t<vars>$xml_vars</vars>\n";
		$xml .= "\t<trace>$xml_trace</trace>\n";
		$xml .= "</errorentry>\n\n";
// save to the error log
		error_log($xml, 3, $_CONFIG['trapperr']['logfile_path']);
	}
	// check if we should violently stop
	if ( $user_errors ) {
		// backward compatibility
		if ( isset($_CONFIG['trapperr']['user_error_is_deadly']) ) {
			// if we should stop on user errors (swift needs this)
			if ( $_CONFIG['trapperr']['user_error_is_deadly'] ) {
				exit;
			}
		}
	}
}

// TRAPPERR constant has to be defined if TRAPPERR is not manually turned off
if ( !defined('TRAPPERR') ) {
	$GLOBALS['_CONFIG']['trapperr'] = adesk_sql_select_box_array("SELECT id, value FROM #trapperr");
	define('TRAPPERR', (bool)count($GLOBALS['_CONFIG']['trapperr']));
}


// turn on custom error handling
if ( TRAPPERR ) {
	$GLOBALS['_CONFIG']['trapperr']['logfile_path'] = adesk_cache_dir('errors.log');
	$old_error_handler = set_error_handler('adesk_php_error_handler');
}

//$jat = I_AM_NOT_HERE; // for testing purposes only

?>
