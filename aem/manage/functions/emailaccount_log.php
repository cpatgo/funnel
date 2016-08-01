<?php

require_once awebdesk_classes("select.php");

function emailaccount_log_select_query(&$so) {
	return $so->query("
		SELECT
			b.*
		FROM
			#emailaccount_log b
		WHERE
		[...]
	");
}

function emailaccount_log_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND b.id = '$id'");

	$r = adesk_sql_select_row(emailaccount_log_select_query($so));
	if ( !$r ) return false;
	$errorStrings = emailaccount_parse_log_errors();
	$r['msg'] = ( isset($errorStrings[$r['error']]) ? $errorStrings[$r['error']] : $r['error']);
	// do something with source here?
	return $r;
}

function emailaccount_log_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		$tmp = array_map("intval", explode(",", $ids));
		$ids = implode("','", $tmp);
		$so->push("AND id IN ('$ids')");
	}
	$rows = adesk_sql_select_array(emailaccount_log_select_query($so));
	if ( !$rows ) return false;
	$errorStrings = emailaccount_parse_log_errors();
	foreach ( $rows as $k => $v ) {
		$rows[$k]['msg'] = ( isset($errorStrings[$v['error']]) ? $errorStrings[$v['error']] : $v['error']);
		unset($rows[$k]['source']);
	}
	return $rows;
}

?>
