#!/usr/local/bin/php
<?php
// require main include file
require_once(dirname(dirname(dirname(__FILE__))) . '/awebdeskend.inc.php');
require_once(awebdesk_functions('cron.php'));
require_once(awebdesk_functions('process.php'));
require_once adesk_admin("functions/socialsharing.php");
require_once adesk_admin("functions/bitly.php");

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

//$cron_run_id = adesk_cron_monitor_start(basename(__FILE__, '.php')); // log cron start

if ( function_exists('curl_init') && function_exists('hash_hmac') && (int)PHP_VERSION > 4 ) {
	$cids = adesk_sql_select_list("SELECT id FROM #campaign WHERE ldate >= (NOW() - INTERVAL 10 DAY)");
	foreach ($cids as $cid) {
		$mids = adesk_sql_select_list("SELECT messageid FROM #campaign_message WHERE campaignid = '$cid'");
		foreach ($mids as $mid) {
			$url = bitly_lookup($cid, $mid, '');
			socialsharing_data_cache_write($cid, $mid, $url);
		}
	}
}

//adesk_cron_monitor_stop(); // log cron end

?>
