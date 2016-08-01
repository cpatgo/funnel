var bounce_code_delete_str = '{"Are you sure you want to delete Bounce Code %s?"|alang|js}';
var bounce_code_delete_str_multi = '{"Are you sure you want to delete the following Bounce Code?"|alang|js}';
var bounce_code_delete_str_cant_delete = '{"You do not have permission to delete Bounce Code"|alang|js}';
{literal}
var bounce_code_delete_id = 0;
var bounce_code_delete_id_multi = "";

function bounce_code_delete_check(id) {
	if (adesk_js_admin.id != 1) {
		adesk_ui_anchor_set(bounce_code_list_anchor());
		alert(bounce_code_delete_str_cant_delete);
		return;
	}

	if (id < 1) {
		bounce_code_delete_check_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "bounce_code.bounce_code_select_row", bounce_code_delete_check_cb, id);
}

function bounce_code_delete_check_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	adesk_dom_remove_children($("delete_list"));

	bounce_code_delete_id = ary.id;
	$("delete_message").innerHTML = sprintf(bounce_code_delete_str, ary.code);
	adesk_dom_display_block("delete");	// can't use toggle here in IE
}

function bounce_code_delete_check_multi() {
	if (adesk_js_admin.id != 1) {
		adesk_ui_anchor_set(bounce_code_list_anchor());
		alert(bounce_code_delete_str_cant_delete);
		return;
	}

	if (!adesk_form_check_selection_check($("list_table"), "multi[]", jsNothingSelected, jsNothingFound)) {
		adesk_ui_anchor_set(bounce_code_list_anchor());
		return;
	}

	var sel = adesk_form_check_selection_get($("list_table"), "multi[]");
	adesk_ajax_call_cb("awebdeskapi.php", "bounce_code.bounce_code_select_array", bounce_code_delete_check_multi_cb, 0, sel.join(","));
	bounce_code_delete_id_multi = sel.join(",");
}

function bounce_code_delete_check_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	$("delete_message").innerHTML = bounce_code_delete_str_multi;

	adesk_dom_remove_children($("delete_list"));
	if (!selectAllSwitch) {
		for (var i = 0; i < ary.row.length; i++)
			$("delete_list").appendChild(Builder.node("li", [ ary.row[i].code ]));
	} else {
		$("delete_list").appendChild(Builder.node("li", [ jsAllItemsWillBeDeleted ]));
	}

	adesk_dom_display_block("delete");
}

function bounce_code_delete(id) {
	if (bounce_code_delete_id_multi != "") {
		bounce_code_delete_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "bounce_code.bounce_code_delete", bounce_code_delete_cb, id);
}

function bounce_code_delete_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(bounce_code_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
}

function bounce_code_delete_multi() {
	if (selectAllSwitch) {
		adesk_ajax_call_cb("awebdeskapi.php", "bounce_code.bounce_code_delete_multi", bounce_code_delete_multi_cb, "_all", bounce_code_list_filter);
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "bounce_code.bounce_code_delete_multi", bounce_code_delete_multi_cb, bounce_code_delete_id_multi);
	bounce_code_delete_id_multi = "";
}

function bounce_code_delete_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(bounce_code_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
	$('selectXPageAllBox').className = 'adesk_hidden';
	$("acSelectAllCheckbox").checked = false;
}
{/literal}
