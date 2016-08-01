#!/usr/local/bin/php
<?php
// require main include file
require_once(dirname(dirname(dirname(__FILE__))) . '/awebdeskend.inc.php');
require_once(awebdesk_functions('cron.php'));


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


$cron_run_id = adesk_cron_monitor_start(basename(__FILE__, '.php')); // log cron start

//adesk_sync_run_cron(); // this runs sync
// clear out #*log tables
adesk_sql_delete('#bounce_log', "tstamp < SUBDATE(NOW(), INTERVAL 1 MONTH)");
adesk_sql_delete('#cron_log', "sdate < SUBDATE(NOW(), INTERVAL 1 MONTH)");
adesk_sql_delete('#emailaccount_log', "tstamp < SUBDATE(NOW(), INTERVAL 1 MONTH)");
adesk_sql_delete('#error_source', "tstamp < SUBDATE(NOW(), INTERVAL 1 MONTH)");
adesk_sql_delete('#link_log', "tstamp < SUBDATE(NOW(), INTERVAL 1 MONTH)");
if ( !isset($GLOBALS['_hosted_account']) ) adesk_sql_delete('#log', "tstamp < SUBDATE(NOW(), INTERVAL 3 MONTH)");

if (isset($GLOBALS['_hosted_account']))
	adesk_sql_delete("#delay", "tstamp < SUBDATE(NOW(), INTERVAL 3 DAY)");

if ( (int)adesk_sql_select_one('=COUNT(*)', '#subscriber_import') > 100000 ) {
	adesk_sql_query("TRUNCATE TABLE #subscriber_import");
} else {
	adesk_sql_delete('#subscriber_import', "tstamp < SUBDATE(NOW(), INTERVAL 1 DAY)");
}
adesk_sql_delete('#trapperrlogs', "tstamp < SUBDATE(NOW(), INTERVAL 1 MONTH)");

adesk_file_delete_old(adesk_cache_dir(), 14, '\.msg$');

$cron_run_id = adesk_cron_monitor_stop(); // log cron end

?>
