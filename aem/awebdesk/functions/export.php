<?php
// export.php

// Functions for exporting csv/xml/html/xls lists

function adesk_export_headers($type, $fileName) {
	if ( $type == 'csv' ) {
		header("Content-type: text/plain; charset=" . _i18n("utf-8"));
		header("Content-Disposition: attachment; filename=$fileName.csv");
	} elseif ( $type == 'xls' ) {
		header("Content-type: application/vnd.ms-excel; charset=" . _i18n("utf-8"));
		header("Content-Disposition: attachment; filename=$fileName.xls");
	} elseif ( $type == 'xml' ) {
		header("Content-type: application/xml; charset=" . _i18n("utf-8"));
		header("Content-Disposition: attachment; filename=$fileName.xml");
	} elseif ( $type == 'html' ) {
		header("Content-type: text/html; charset=" . _i18n("utf-8"));
		header("Content-Disposition: attachment; filename=$fileName.html");
	} else {
		echo _a("Export mode not supported.");
		exit;
	}
	header("Pragma: no-cache");
	header("Expires: 0");
}

function adesk_export_print($arr, $type, $wrapper = '', $delimiter = '') {
	if ( $type == 'xml' ) {
		adesk_export_print_xml($arr, $wrapper, $delimiter);
	} elseif ( $type == 'csv' ) {
		adesk_export_print_csv($arr, $wrapper, $delimiter);
	} else { // html,xls
		adesk_export_print_html($arr, $wrapper, $delimiter);
	}
}

function adesk_export_print_xml($arr, $wrapper = '', $delimiter = '') {
	require_once(awebdesk_functions('ajax.php'));
	// print out headers
    adesk_flush("<?xml version='1.0' encoding='" . _i18n("utf-8") . "'?>\n");
	// print out root node
	adesk_flush("<export>\n");
	// now start printing data
	while ( $row = adesk_sql_fetch_assoc($arr['rs']) ) {
		if ( adesk_ihook_exists('adesk_export_row') ) $row = adesk_ihook('adesk_export_row', $row, $arr);
		adesk_flush(adesk_xml_write_new($row, 'row') . "\n");
	}
	// close root node
	adesk_flush("</export>\n");
}

function adesk_export_print_html($arr, $wrapper = '', $delimiter = '') {
	// first print out fields (headers)
	adesk_flush("<table>\n\t<tr>\n");
	foreach ( $arr['fields'] as $v ) {
		adesk_flush("\t\t<th>" . adesk_str_htmlspecialchars($v) . "</th>\n");
	}
	// finish fields row
	adesk_flush("\t</tr>\n\n");
	// now start printing data
	while ( $row = adesk_sql_fetch_assoc($arr['rs']) ) {
		if ( adesk_ihook_exists('adesk_export_row') ) $row = adesk_ihook('adesk_export_row', $row, $arr);
		// print every field
		adesk_flush("\t</tr>\n\n");
		foreach ( $row as $v ) {
			adesk_flush("\t\t<td>" . adesk_str_htmlspecialchars($v) . "</td>\n");
		}
		// finish this row
		adesk_flush("\t</tr>\n");
	}
	// finish the table
	adesk_flush("</table>\n");
}

function adesk_export_print_csv($arr, $wrapper = '', $delimiter = '') {
	// first print out fields (headers)
	$first = true;
	foreach ( $arr['fields'] as $v ) {
		if ( !$first ) adesk_flush($delimiter);
		$first = false;
		adesk_flush($wrapper . adesk_str_escape_csv($v, $wrapper) . $wrapper);
	}
	// finish fields row
	adesk_flush("\n");
	// now start printing data
	while ( $row = adesk_sql_fetch_assoc($arr['rs']) ) {
		if ( adesk_ihook_exists('adesk_export_row') ) $row = adesk_ihook('adesk_export_row', $row, $arr);
		// print every field
		$first = true;
		foreach ( $row as $v ) {
			if ( !$first ) adesk_flush($delimiter);
			$first = false;
			adesk_flush($wrapper . adesk_str_escape_csv($v, $wrapper) . $wrapper);
		}
		// finish this row
		adesk_flush("\n");
	}
}

?>
