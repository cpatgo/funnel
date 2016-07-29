#!/usr/local/bin/php
<?php
// require main include file
require_once(dirname(dirname(dirname(__FILE__))) . '/awebdeskend.inc.php');
require_once(awebdesk_functions('cron.php'));
require_once(awebdesk_functions('sync.php'));
require_once(awebdesk_functions('process.php'));


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

// id manipulation is handled within this function; no CLI running, via server only
adesk_sync_run_cron(); // this runs sync

adesk_cron_monitor_stop(); // log cron end

?>
