#!/usr/local/bin/php
<?php

// require main include file
require_once(dirname(__FILE__) . '/awebdeskend.inc.php');
require_once(awebdesk_functions('process.php'));
require_once awebdesk_functions("cron.php");

adesk_flush('');

// turning off some php limits
@ignore_user_abort(1);
@ini_set('max_execution_time', 950 * 60);
@set_time_limit(950 * 60);
$ml = ini_get('memory_limit');
if ( $ml != -1 and (int)$ml < 128 and substr($ml, -1) == 'M') @ini_set('memory_limit', '128M');
set_include_path('.');

// admin permission reset (use admin=1!)
$admin = adesk_admin_get_totally_unsafe(1);

// Preload the language file
adesk_lang_get('admin');

$id = (int)adesk_http_param('id');
$debug = (bool)adesk_http_param('debug');



if ( $id == 0 ) {
	$id = ( isset($_SERVER['argv'][1]) ? (int)$_SERVER['argv'][1] : 0 );
	if ( isset($_SERVER['argv'][2]) ) $debug = (bool)$_SERVER['argv'][2];
}

if ( $debug ) $_GET['debugspawn'] = 1;

if ( $id == 0 ) {
	adesk_flush('');

	adesk_cron_monitor_start(basename(__FILE__, '.php')); // log cron start
	adesk_process_respawn(null, $debug); // this triggers to run all process
	adesk_cron_monitor_stop(); // log cron end
} else {
	adesk_flush('.');
	adesk_process_pickup($id); // this runs process
}

adesk_process_cleanup(); // this removes old&finished processes

?>
