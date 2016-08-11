<?php

require_once awebdesk_classes("select.php");
require_once awebdesk_functions("process.php");

function list_insert_post() {
	// user access
	$admin = adesk_admin_get();

	$id = 0;
	$ary = landingpage_post_prepare($id);

	// validation
	if ( $ary['title'] == '' ) {
		return adesk_ajax_api_result(false, _a("Landing Page Title can not be empty."));
	}

	$sql = adesk_sql_insert("#landingpage", $ary);
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Landing page could not be added.") . adesk_sql_error());
	}
	// collect id
	$id = adesk_sql_insert_id();

	// rebuild admin's permissions
	adesk_session_drop_cache();
	$GLOBALS['admin'] = adesk_admin_get();
	$lists = list_get_all(true);
	return adesk_ajax_api_added(_a("Landing Page"), array("id" => $id));
}

function landingpage($id) {

	$admin = adesk_admin_get();
	$r = array();

	// general list settings
	$r['title'] = (string)adesk_http_param('title');
	$r['type'] = (string)adesk_http_param('type');
	$r['description'] = (string)adesk_http_param('description');

	if ( $id == 0 ) $r['user_id'] = (int)$admin['id'];
	if ( adesk_admin_ismaingroup() and (int)adesk_http_param('userid') ) {
		$r['user_id'] = (int)adesk_http_param('userid');
	}

	$r['list_id'] = (int)adesk_http_param('list_id');
	$r['page_link'] = (string)adesk_http_param('page_link');
	$r['date_created'] = date('Y-m-d H:i:s');

	return $r;
}

?>
