#!/usr/local/bin/php
<?php
// require main include file
require_once(dirname(dirname(dirname(__FILE__))) . '/awebdeskend.inc.php');
require_once(awebdesk_functions('cron.php'));
require_once(awebdesk_functions('process.php'));
require_once(awebdesk_functions('ajax.php'));


// turning off some php limits
@ignore_user_abort(1);
@ini_set('max_execution_time', 950 * 60);
@set_time_limit(950 * 60);
$ml = ini_get('memory_limit');
if ( $ml != -1 and (int)$ml < 128 and substr($ml, -1) == 'M') @ini_set('memory_limit', '128M');
set_include_path('.');
@set_magic_quotes_runtime(0);

// admin permission reset (use admin=1!)
$admin = adesk_admin_get_totally_unsafe(1);

// Preload the language file
adesk_lang_get('admin');



$id = (int)adesk_http_param('id');
$debug = (bool)adesk_http_param('debug');
$debug_comm = (bool)adesk_http_param('debug_comm');

if ( !$id ) {
	$id = ( isset($_SERVER['argv'][1]) ? (int)$_SERVER['argv'][1] : 0 );
	if ( isset($_SERVER['argv'][2]) ) $debug = (bool)$_SERVER['argv'][2];
}


if ( $debug ) {
	echo "

	<style type='text/css'>

		body {
			font-family: Arial;
			font-size: 11px;
		}

	</style>

	";
	if ( !defined('adesk_POP3_DEBUG') ) define('adesk_POP3_DEBUG', $debug);
}

if ( $debug_comm ) {
	if ( !defined('adesk_POP3_DEBUG_COMM') ) define('adesk_POP3_DEBUG_COMM', $debug_comm);
}

// include these after setting a debugging constant
require_once(adesk_admin('functions/emailaccount.php'));
require_once(awebdesk_functions('pop3.php'));



if ( !$debug ) {
	$cron_run_id = adesk_cron_monitor_start(basename(__FILE__, '.php')); // log cron start
}


emailaccount_process($id);

if ( !$debug ) {
	adesk_cron_monitor_stop(); // log cron end
}

?>
