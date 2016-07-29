<?php

require_once awebdesk_classes("select.php");

function settings_select_row() {
	$so = new adesk_Select;
	$so->push("AND id = '1'");

	return adesk_site_get();
}

function settings_update_post() {
	$ary = array(
	);

	$sql = adesk_sql_update("#settings", $ary, "id = '1'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Settings could not be updated."));
	}

	return adesk_ajax_api_updated(_a("Settings"));
}

function settings_help_videos($url) {
	return array("help" => adesk_http_get($url));
}

function settings_gettingstarted_hide($groupids) {
	// pg_startup_gettingstarted = 1. Has to be visible for them to even run this command.
	// 0 = hidden, 1 = show, 2 = hidden no matter what (they clicked the Close link)
	$sql = adesk_sql_update("#group", array("pg_startup_gettingstarted" => 2), "id IN ($groupids) AND pg_startup_gettingstarted = 1");

	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Settings could not be updated."));
	}

	return adesk_ajax_api_updated(_a("Settings"));
}

function settings_cname_check($cname) {
	if ( !$cname ) return array('result' => false);
	$url = 'http://' . $cname . '/index.php?action=rewritetest';
	$rval  = adesk_http_testdata($url, "<!-- ac:hd:rewrite:test -->");
	return $rval;
}

function settings_sendlog_switch() {
	$site = adesk_site_get();
	$newval = $site['mailer_log_file'] ? 0 : 4;
	adesk_sql_update_one('#backend', 'mailer_log_file', $newval);
	return adesk_ajax_api_saved(_a("Sending Logs Setting"), array('newval' => $newval));
}

?>
