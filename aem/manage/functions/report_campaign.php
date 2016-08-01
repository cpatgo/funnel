<?php

require_once awebdesk_classes("select.php");
/*
function report_campaign_select_query(&$so) {
	return $so->query("
	");
}

function report_campaign_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND id = '$id'");

	return adesk_sql_select_row(report_campaign_select_query($so));
}

function report_campaign_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		$tmp = array_map("intval", explode(",", $ids));
		$ids = implode("','", $tmp);
		$so->push("AND id IN ('$ids')");
	}
	return adesk_sql_select_array(report_campaign_select_query($so));
}

function report_campaign_select_array_paginator($id, $sort, $offset, $limit, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$so->count();
	$total = (int)adesk_sql_select_one(report_campaign_select_query($so));

	switch ($sort) {
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = report_campaign_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}
*/

function report_campaign_spamcheck($campaignid, $messageid) {

	$campaignid = (int)$campaignid;
	$messageid = (int)$messageid;

	$spamcheck = adesk_sql_select_row("
		SELECT spamcheck_score, spamcheck_max
		FROM #campaign_message
		WHERE campaignid = '$campaignid' AND messageid = '$messageid'
	");
	if ( !$spamcheck ) {
		return adesk_ajax_api_result(false, _a("Campaign message not found."));
	}

	$rules = adesk_sql_select_array("
		SELECT
			*,
			rule AS name
		FROM
			#campaign_spamcheck
		WHERE
			campaignid = '$campaignid'
		AND
			messageid = '$messageid'
		ORDER BY
			score DESC
	");
	if ( !$rules ) $rules = array();

	$r = array(
		'rules' => $rules,
		'score' => $spamcheck['spamcheck_score'],
		'max' => $spamcheck['spamcheck_max'],
	);

	return adesk_ajax_api_result(true, sprintf(_a("This campaign's Spam Score is: %s / %s"), $spamcheck['spamcheck_score'], $spamcheck['spamcheck_max']), $r);
}

function report_campaign_share($campaignid, $email = 'web') {
	if ( !$email and $email != 'web' and !adesk_str_is_email($email) ) {
		return adesk_ajax_api_result(false, _a("Please provide valid email or 'web' to the API."));
	}

	$campaignid = (int)$campaignid;
	$campaign = campaign_select_row($campaignid);
	if ( !$campaign ) {
		return adesk_ajax_api_result(false, _a("Campaign not found."));
	}

	$listid = $campaign["lists"][0]["id"];

	$hash = awebdesk_reporthash($campaignid, $listid, $email);
	$url = awebdesk_reporthash_url($campaignid, $hash, $email);

	$r = array(
		'campaignid' => $campaignid,
		'email' => $email,
		'hash' => $hash,
		'url' => $url,
	);
	return adesk_ajax_api_result(true, _a("Campaign successfully shared."), $r);
}
?>