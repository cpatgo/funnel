<?php

function mpma_macro_queries($arr) {
	foreach ( $arr as $key => $qry ) {
		echo nl2br(strtoupper($key) . ":\n");
		adesk_mpma_query($qry);
		echo adesk_mpma_response();
	}
}

function _macro_campaign($id = 0) {
	$id = (int)$id;
	if ( !$id ) {
		echo nl2br("ERROR: ID NOT PROVIDED.\n");
		return;
	}
	$sid = (int)adesk_sql_select_one("id", "#campaign_count", "campaignid = '$id' ORDER BY id DESC");
	$queries = array(
		"CAMPAIGN INFO" => "SELECT * FROM #campaign         WHERE id = '$id'",
		"LISTS"         => "SELECT * FROM #campaign_list    WHERE campaignid = '$id'",
		"COUNTS"        => "SELECT * FROM #campaign_count   WHERE campaignid = '$id'",
		"MESSAGES"      => "SELECT * FROM #campaign_message WHERE campaignid = '$id'",
		"PROCESS"       => "SELECT * FROM #process          WHERE id = ( SELECT MAX(processid) FROM #campaign_count WHERE campaignid = '$id' )",
		"ERRORS"        => "
			SELECT
				id,
				tstamp,
				errnumber,
				errmessage,
				filename,
				url,
				linenum,
				session,
				userid,
				ip,
				host,
				referer
			FROM
				#trapperrlogs
			WHERE
				`url` LIKE CONCAT('%/process.php?id=', ( SELECT MAX(processid) FROM #campaign_count WHERE campaignid = '$id' ))
		",
		"TOTAL IN X"    => "SELECT COUNT(*) FROM #x$sid",
		"UNSENT IN X"   => "SELECT COUNT(*) FROM #x$sid WHERE sent = 0",
		"4WINNER IN X"  => "SELECT COUNT(*) FROM #x$sid WHERE messageid = 0",
	);
	mpma_macro_queries($queries);
}

function _macro_subscriberid($id = 0) {
	$id = (int)$id;
	if ( !$id ) {
		echo nl2br("ERROR: ID NOT PROVIDED.\n");
		return;
	}
	$queries = array(
		"SUBSCRIBER INFO" => "SELECT * FROM #subscriber           WHERE id           = '$id'",
		"LISTS"           => "SELECT * FROM #subscriber_list      WHERE subscriberid = '$id'",
		"FIELDS"          => "SELECT * FROM #list_field_value     WHERE relid        = '$id'",
		"SENT RESPONDERS" => "SELECT * FROM #subscriber_responder WHERE subscriberid = '$id'",
	);
	mpma_macro_queries($queries);
}

function _macro_subscriberhash($hash = '') {
	if ( !$hash ) {
		echo nl2br("ERROR: ID NOT PROVIDED.\n");
		return;
	}
	$esc = adesk_sql_escape($hash);
	$queries = array(
		"SUBSCRIBER INFO" => "SELECT * FROM #subscriber           WHERE hash         = '$esc'",
		"LISTS"           => "SELECT * FROM #subscriber_list      WHERE subscriberid = ( SELECT id FROM #subscriber WHERE hash = '$esc' LIMIT 0, 1 )",
		"FIELDS"          => "SELECT * FROM #list_field_value     WHERE relid        = ( SELECT id FROM #subscriber WHERE hash = '$esc' LIMIT 0, 1 )",
		"SENT RESPONDERS" => "SELECT * FROM #subscriber_responder WHERE subscriberid = ( SELECT id FROM #subscriber WHERE hash = '$esc' LIMIT 0, 1 )",
	);
	mpma_macro_queries($queries);
}

function _macro_subscriberemail($email = '') {
	if ( !$email ) {
		echo nl2br("ERROR: ID NOT PROVIDED.\n");
		return;
	}
	$esc = adesk_sql_escape($email);
	$queries = array(
		"SUBSCRIBER INFO" => "SELECT * FROM #subscriber           WHERE email        = '$esc'",
		"LISTS"           => "SELECT * FROM #subscriber_list      WHERE subscriberid = ( SELECT id FROM #subscriber WHERE hash = '$esc' LIMIT 0, 1 )",
		"FIELDS"          => "SELECT * FROM #list_field_value     WHERE relid        = ( SELECT id FROM #subscriber WHERE hash = '$esc' LIMIT 0, 1 )",
		"SENT RESPONDERS" => "SELECT * FROM #subscriber_responder WHERE subscriberid = ( SELECT id FROM #subscriber WHERE hash = '$esc' LIMIT 0, 1 )",
	);
	mpma_macro_queries($queries);
}

?>