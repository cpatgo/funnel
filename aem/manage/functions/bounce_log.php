<?php

require_once awebdesk_classes("select.php");

function bounce_log_select_query(&$so) {
	return $so->query("
		SELECT
			b.*,
			c.code AS bouncecode,
			c.type AS bouncetype,
			c.descript AS bouncedescript
		FROM
			#bounce_log b
		LEFT JOIN
			#bounce_code c
		ON
			b.codeid = c.id
		WHERE
		[...]
	");
}

function bounce_log_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND b.id = '$id'");

	$r = adesk_sql_select_row(bounce_log_select_query($so));
	if ( !$r ) return false;
	$errorStrings = bounce_management_parse_log_errors();
	$r['msg'] = ( isset($errorStrings[$r['error']]) ? $errorStrings[$r['error']] : $r['error']);
	$r['campaign'] = false;
	if ( $r['campaignid'] ) {
		$r['campaign'] = adesk_sql_select_row("SELECT * FROM #campaign WHERE id = '$r[campaignid]'");
	}
	if ( !$r['campaign'] ) $r['campaignid'] = 0;
	// do something with source here?
	return $r;
}

function bounce_log_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		$tmp = array_map("intval", explode(",", $ids));
		$ids = implode("','", $tmp);
		$so->push("AND id IN ('$ids')");
	}
	$rows = adesk_sql_select_array(bounce_log_select_query($so));
	if ( !$rows ) return array();
	$errorStrings = bounce_management_parse_log_errors();
	foreach ( $rows as $k => $v ) {
		$rows[$k]['msg'] = ( isset($errorStrings[$v['error']]) ? $errorStrings[$v['error']] : $v['error']);
		unset($rows[$k]['source']);
	}
	return $rows;
}

?>
