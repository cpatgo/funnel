<?php

function list_field_getfielddata(&$subscriber, &$fields) {
	$fieldlist = adesk_array_extract($fields, "id");
	$fieldstr  = implode("','", $fieldlist);
	$ary       = adesk_sql_select_array("
		SELECT
			fieldid,
			val
		FROM
			#list_field_value
		WHERE
			fieldid IN ('$fieldstr')
		AND
			relid    = '$subscriber[id]'
		ORDER BY
			fieldid
	");

	return $ary;
}

function list_field_getfields($campaignid) {
	$campaignid = intval($campaignid);
	return adesk_sql_select_array("
		SELECT DISTINCT
			f.id,
			f.title
		FROM
			#list_field f,
			#list_field_rel r
		WHERE
			f.id = r.fieldid
		AND
			( r.relid IN (
				SELECT
					cl.listid
				FROM
					#campaign_list cl
				WHERE
					cl.campaignid = '$campaignid'
			)
			OR r.relid='0' )
		ORDER BY
			f.id
	");
}

function list_field_getdefaults($listids) {
	# Return an array of the fields which have default values according to the given $listids
	# (and including any global fields).

	$ary     = array();
	$liststr = implode("','", $listids);
	$rs      = adesk_sql_query("
		SELECT
			f.id,
			IF(f.`type` = 2, f.expl, f.onfocus) AS a_default
		FROM
			#list_field f
		WHERE
			IF(f.`type` = 2, f.expl, f.onfocus) != ''
		AND
			f.id IN (
				SELECT
					r.fieldid
				FROM
					#list_field_rel r
				WHERE
					r.relid IN ('0', '$liststr')
			)
	");

	while ($row = adesk_sql_fetch_assoc($rs)) {
		$ary[$row["id"]] = $row["a_default"];
	}

	return $ary;
}

function list_field_insert_post() {
	$ary = array(
		'title' => adesk_http_param('title'),
		'type' => adesk_http_param('type'),
		'expl' => adesk_http_param('expl'),
		'req' => adesk_http_param('req'),
		'onfocus' => adesk_http_param('onfocus'),
		'bubble_content' => adesk_http_param('bubble_content'),
		'label' => adesk_http_param('label'),
		'show_in_list' => adesk_http_param('show_in_list'),
		'perstag' => adesk_http_param('perstag'),
	);
	$fieldid = adesk_custom_fields_insert('#list_field', $ary);
	if ( isset($_POST['p']) and is_array($_POST['p']) and count($_POST['p']) > 0 ) {
		$lists = array_map('intval', $_POST['p']);
	}
	else {
		$lists = array(0); // All lists
	}
	foreach ($lists as $list) {
		adesk_sql_insert("#list_field_rel", array('id' => 0, 'relid' => $list, 'fieldid' => $fieldid));
	}
	$r = array();
	$r['fieldid'] = $fieldid;
	return adesk_ajax_api_added(_a("Custom Field"), $r);
}

function list_field_update_post() {
	$fieldid = (int)adesk_http_param('id');
	$ary = array(
		'title' => adesk_http_param('title'),
		'type' => adesk_http_param('type'),
		'expl' => adesk_http_param('expl'),
		'req' => adesk_http_param('req'),
		'onfocus' => adesk_http_param('onfocus'),
		'bubble_content' => adesk_http_param('bubble_content'),
		'label' => adesk_http_param('label'),
		'show_in_list' => adesk_http_param('show_in_list'),
		'perstag' => adesk_http_param('perstag'),
	);
	$update = adesk_custom_fields_update_field('#list_field', $ary, $fieldid);
	if ( isset($_POST['p']) and is_array($_POST['p']) and count($_POST['p']) > 0 ) {
		$lists = array_map('intval', $_POST['p']);
	}
	else {
		$lists = array(0); // All lists
	}
	adesk_sql_delete("#list_field_rel", "`fieldid` = '$fieldid'");
	foreach ($lists as $list) {
		adesk_sql_insert("#list_field_rel", array('id' => 0, 'relid' => $list, 'fieldid' => $fieldid));
	}
	$r = array();
	return adesk_ajax_api_updated(_a("Custom Field"), $r);
}

function list_field_delete($fieldid) {
	adesk_custom_fields_delete_field('#list_field', '#list_field_rel', 'fieldid', $fieldid);
	$r = array();
	$r['fieldid'] = $fieldid;
	return adesk_ajax_api_deleted(_a("Custom Field"), $r);
}

function list_field_select_nodata_rel($fieldids, $filters = array()) {
	if ($fieldids && $fieldids != "all") {
		$fieldids = explode(",", $fieldids);
		$lists_group = implode("','", $GLOBALS["admin"]["lists"]);
		// get the fields for this group's lists
		$fields_group = adesk_custom_fields_select_nodata_rel('#list_field', '#list_field_rel', "r.relid IN ('$lists_group')");
		$fields_group_ids = array();
		foreach ($fields_group as $field) $fields_group_ids[] = $field["id"];
		$fieldids = array_intersect($fields_group_ids, $fieldids);
		$fieldids = implode("','", $fieldids);
	}
	elseif ($fieldids == "all") {
	  if ( !in_array(0, $GLOBALS["admin"]["lists"]) ) {
	    // include global custom fields
      $GLOBALS["admin"]["lists"][0] = 0;
    }
    $lists_group = implode("','", $GLOBALS["admin"]["lists"]);
    // get the fields for this group's lists
    $fields_group = adesk_custom_fields_select_nodata_rel('#list_field', '#list_field_rel', "r.relid IN ('$lists_group')");
		$fields_group_ids = array();
		foreach ($fields_group as $field) $fields_group_ids[] = $field["id"];
		$fieldids = implode("','", $fields_group_ids);
	}
	else {
	  $fieldids = "";
	}

	$where = "f.id IN ('" . $fieldids . "')";
	$fields = adesk_custom_fields_select_nodata_rel("#list_field", "#list_field_rel", $where);
	return $fields;
}

?>
