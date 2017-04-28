<?php

if ( !$SWITCH ) die('This feature is turned off.');

$adminPath = dirname(dirname(dirname(__FILE__))) . '/manage';

error_reporting(E_ALL);
require_once($adminPath . '/awebdeskend.inc.php');
require_once(awebdesk_functions('cron.php'));



// turning off some php limits
@ignore_user_abort(1);
@ini_set('max_execution_time', 950 * 60);
@set_time_limit(950 * 60);
$ml = ini_get('memory_limit');
if ( (int)$ml != -1 ) @ini_set('memory_limit', '-1');
set_include_path('.');
@set_magic_quotes_runtime(0);

// admin permission reset (use admin=1!)
$admin = adesk_admin_get_totally_unsafe(1);

// Preload the language file
adesk_lang_get('admin');



$backupFile = $backupFolder . $fileName;

// check if file exists
if ( ( file_exists($backupFile) and !is_writable($backupFile) ) or !is_writable($backupFolder) ) {
	die('Backup file ' . $backupFile . ' is not writeable!');
}

$cron_run_id = adesk_cron_monitor_start(basename(__FILE__, '.php')); // log cron start

$GLOBALS['sqlstreamfile'] = @fopen($backupFile, 'w+');

adesk_sql_stdout("# MySQL Database Structure & Contents\r\n");
adesk_sql_stdout("# \r\n");
adesk_sql_stdout("# Host: " . ( isset($_SERVER['SERVER_NAME']) ? (string)$_SERVER['SERVER_NAME'] : 'localhost' ) . "\r\n");
adesk_sql_stdout("# Generation Time: " . date('M d, Y \a\t H:i A') . "\r\n");
adesk_sql_stdout("# PHP Version: " . phpversion() . "\r\n");
adesk_sql_stdout("#\r\n");
adesk_sql_stdout("# Database : `" . select_column("SELECT DATABASE()") . "`\r\n");
adesk_sql_stdout("#\r\n\r\n");

// write2file version
adesk_sql_backup_all(true);
fclose($GLOBALS['sqlstreamfile']);


adesk_cron_monitor_stop(); // log cron end




/*
uncomment this if you want your dates/times converted to current while importing
what it does is it replaces every real date in the database with NOW()
that way, when a SQL is restored, everything looks like it was done now
function adesk_sql_stdout_filter(&$string) {
	// if 0000-00-00 00:00:00, don't replace!
	// otherwise, set to be time of execution!
	$preg_datetime = "/'[1-9]\d{3}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}'/";
	$string = preg_replace($preg_datetime, 'NOW()', $string);
	// if 0000-00-00, don't replace!
	// otherwise, set to be date of execution!
	$preg_datetime = "/'[1-9]\d{3}-\d{2}-\d{2}'/";
	$string = preg_replace($preg_datetime, 'CURDATE()', $string);
	// if 00:00:00 will be replaced!
	// otherwise, set to be date of execution!
	$preg_datetime = "/'[1-9]\d{1}:\d{2}:\d{2}'/";
	$string = preg_replace($preg_datetime, 'CURTIME()', $string);
}
*/
?>
