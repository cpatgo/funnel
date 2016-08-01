var approval_delete_str = '{"Are you sure you want to delete Campaign Approval submitted on %s?"|alang|js}';
var approval_delete_str_multi = '{"Are you sure you want to delete the following Campaign Approval?"|alang|js}';
var approval_delete_str_cant_delete = '{"You do not have permission to delete Campaign Approval"|alang|js}';
{literal}
var approval_delete_id = 0;
var approval_delete_id_multi = "";

function approval_delete_check(id) {
	/*
	if (adesk_js_admin.__CAN_DELETE__ != 1) {
		adesk_ui_anchor_set(approval_list_anchor());
		alert(approval_delete_str_cant_delete);
		return;
	}
	*/

	if (id < 1) {
		approval_delete_check_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "approval.approval_select_row", approval_delete_check_cb, id);
}

function approval_delete_check_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	adesk_dom_remove_children($("delete_list"));

	// The row probably doesn't exist.
	if (typeof ary.id == "undefined") {
		adesk_ui_anchor_set(approval_list_anchor());
		return;
	}

	approval_delete_id = ary.id;
	$("delete_message").innerHTML = sprintf(approval_delete_str, ary.sdate);
	adesk_dom_display_block("delete");	// can't use toggle here in IE
}

function approval_delete_check_multi() {
	/*
	if (adesk_js_admin.__CAN_DELETE__ != 1) {
		adesk_ui_anchor_set(approval_list_anchor());
		alert(approval_delete_str_cant_delete);
		return;
	}
	*/

	if (!adesk_form_check_selection_check($("list_table"), "multi[]", jsNothingSelected, jsNothingFound)) {
		adesk_ui_anchor_set(approval_list_anchor());
		return;
	}

	var sel = adesk_form_check_selection_get($("list_table"), "multi[]");
	adesk_ajax_call_cb("awebdeskapi.php", "approval.approval_select_array", approval_delete_check_multi_cb, 0, sel.join(","));
	approval_delete_id_multi = sel.join(",");
}

function approval_delete_check_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	$("delete_message").innerHTML = approval_delete_str_multi;

	adesk_dom_remove_children($("delete_list"));
	if (!selectAllSwitch) {
		for (var i = 0; i < ary.row.length; i++)
			$("delete_list").appendChild(Builder.node("li", [ ary.row[i].sdate ]));
	} else {
		$("delete_list").appendChild(Builder.node("li", [ jsAllItemsWillBeDeleted ]));
	}

	adesk_dom_display_block("delete");
}

function approval_delete(id) {
	if (approval_delete_id_multi != "") {
		approval_delete_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "approval.approval_delete", approval_delete_cb, id);
}

function approval_delete_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded && ary.succeeded == "1") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(approval_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
}

function approval_delete_multi() {
	if (selectAllSwitch) {
		adesk_ajax_call_cb("awebdeskapi.php", "approval.approval_delete_multi", approval_delete_multi_cb, "_all");
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "approval.approval_delete_multi", approval_delete_multi_cb, approval_delete_id_multi);
	approval_delete_id_multi = "";
}

function approval_delete_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded && ary.succeeded == "1") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(approval_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
	$('selectXPageAllBox').className = 'adesk_hidden';
	$("acSelectAllCheckbox").checked = false;
}
{/literal}
