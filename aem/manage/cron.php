#!/usr/local/bin/php
<?php
// require main include file
require_once(dirname(__FILE__) . '/awebdeskend.inc.php');
require_once(awebdesk_functions('cron.php'));
require_once(awebdesk_functions('process.php'));


if ( !defined('adesk_CRON') ) define('adesk_CRON', 1);


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

if ( $id == 0 ) {
	$id = ( isset($_SERVER['argv'][1]) ? (int)$_SERVER['argv'][1] : 0 );
	if ( isset($_SERVER['argv'][2]) ) $debug = (bool)$_SERVER['argv'][2];
}

if ( $debug ) $_GET['debugspawn'] = 1;

// help
if ( isset($_SERVER['argv'][1]) and in_array($_SERVER['argv'][1], array('--help', '-help', '-h', '-?', '/?') ) ) {

	adesk_flush("

  This is a command line PHP script with two options: cron id and debug switch.

  Usage:
  {$_SERVER['argv'][0]} <cronid> <debugswitch>

  <cronid> can be a specific cron ID you would like to run,
  or 0 (default) to run all jobs that are due.

  <debugswitch> can be 0 (default) to turn it off or 1 to turn it on.

  With the --help, -help, -h, -? or /? options, you can get this help.

");
	exit;
}


adesk_cron_run($id, $debug); // this spawns cron(s)

?>
