<?php

require_once awebdesk_classes("select.php");

function service_select_query(&$so) {
	return $so->query("
		SELECT
			*
		FROM
			#service s
		WHERE
			[...]
	");
}

function service_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	if ($id == 3) {
	  // unbounce (not in database, so add manually)
	  return array("id" => 3, "name" => "Unbounce", "description" => _a("Configure Unbounce integration settings."));
	}
	else {
	  $so->push("AND id = '$id'");
	  return adesk_sql_select_row(service_select_query($so));
	}
}

function service_get($id) {
	$r = service_select_row($id);
	$site = adesk_site_get();
	$r["twitter_consumer_key"] = $site["twitter_consumer_key"];
	$r["twitter_consumer_secret"] = $site["twitter_consumer_secret"];
	$r["facebook_app_id"] = $site["facebook_app_id"];
	$r["facebook_app_secret"] = $site["facebook_app_secret"];
	return $r;
}

function service_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map('intval', $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND id IN ('$ids')");
	}

	return adesk_sql_select_array(service_select_query($so));
}

function service_select_array_paginator($id, $sort, $offset, $limit, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$so->count();
	$total = (int)adesk_sql_select_one(service_select_query($so));

	switch ($sort) {
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$so->limit("$offset, $limit");
	$so->push("AND id IN (1,2)"); // limit to chosen rows (at one point there was duplicate rows in this table)
	$rows = service_select_array($so);

	$rows[] = array(
	  "id" => 3,
	  "name" => "Unbounce",
	  "description" => _a("Configure Unbounce integration settings."),
	);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function service_insert_post() {
	$ary = array(
	);

	$sql = adesk_sql_insert("#service", $ary);
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("External Service could not be added."));
	}
	$id = adesk_sql_insert_id();

	return adesk_ajax_api_added(_a("External Service"));
}

function service_update_post() {
	$admin = adesk_admin_get();
	$site = adesk_site_get();
	require_once adesk_admin("functions/list.php");
	require_once awebdesk_classes("oauth.php");
	require_once awebdesk_classes("oauth_twitter.php");
	// test getting a request token from Twitter, to make sure the keys are valid
	$oauth = new TwitterOAuth($_POST["service_twitter_key"], $_POST["service_twitter_secret"], null, null);
	$test_request = list_twitter_oauth_getrequesttoken(1, $oauth);
	if ( isset($test_request["error"]) ) {
		return adesk_ajax_api_result(false, _a("External Service not updated. Please verify your Twitter application keys."));
	}
	$ary = array(
		"twitter_consumer_key" => $_POST["service_twitter_key"],
		"twitter_consumer_secret" => $_POST["service_twitter_secret"],
		"facebook_app_id" => $_POST["service_facebook_id"],
		"facebook_app_secret" => $_POST["service_facebook_secret"],
	);
	$sql = adesk_sql_update("#backend", $ary, "id = 1");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("External Service could not be updated."));
	}
	return adesk_ajax_api_updated(_a("External Service"));
}

function service_delete($id) {
	$id = intval($id);
	adesk_sql_query("DELETE FROM #service WHERE id = '$id'");
	service_delete_relations(array($id));
	return adesk_ajax_api_deleted(_a("External Service"));
}

function service_delete_multi($ids) {
	if ($ids == "_all") {
		adesk_sql_query("TRUNCATE TABLE #service");
		service_delete_relations(null);
		return;
	}
	$tmp = array_map("intval", explode(",", $ids));
	$ids = implode("','", $tmp);
	adesk_sql_query("DELETE FROM #service WHERE id IN ('$ids')");
	service_delete_relations($tmp);
	return adesk_ajax_api_deleted(_a("External Service"));
}

function service_delete_relations($ids) {
	if ($ids === null) {		# delete all
	} else {
	}
}

?>
