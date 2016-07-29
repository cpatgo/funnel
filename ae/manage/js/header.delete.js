var header_delete_str = '{"Are you sure you want to delete email header %s?"|alang|js}';
var header_delete_str_multi = '{"Are you sure you want to delete the following email headers?"|alang|js}';
var header_delete_str_cant_delete = '{"You do not have permission to delete email headers"|alang|js}';
{literal}
var header_delete_id = 0;
var header_delete_id_multi = "";

function header_delete_check(id) {
	if (!adesk_js_admin.pg_list_edit) {
		adesk_ui_anchor_set(header_list_anchor());
		alert(header_delete_str_cant_delete);
		return;
	}

	if (id < 1) {
		header_delete_check_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "header.header_select_row", header_delete_check_cb, id);
}

function header_delete_check_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	adesk_dom_remove_children($("delete_list"));

	header_delete_id = ary.id;
	$("delete_message").innerHTML = sprintf(header_delete_str, ary.title);
	adesk_dom_display_block("delete");
}

function header_delete_check_multi() {
	if (!adesk_js_admin.pg_list_edit) {
		adesk_ui_anchor_set(header_list_anchor());
		alert(header_delete_str_cant_delete);
		return;
	}

	if (!adesk_form_check_selection_check($("list_table"), "multi[]", jsNothingSelected, jsNothingFound)) {
		adesk_ui_anchor_set(header_list_anchor());
		return;
	}

	var sel = adesk_form_check_selection_get($("list_table"), "multi[]");
	adesk_ajax_call_cb("awebdeskapi.php", "header.header_select_array", header_delete_check_multi_cb, 0, sel.join(","));
	header_delete_id_multi = sel.join(",");
}

function header_delete_check_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	$("delete_message").innerHTML = header_delete_str_multi;

	adesk_dom_remove_children($("delete_list"));
	if (!selectAllSwitch) {
		for (var i = 0; i < ary.row.length; i++)
			$("delete_list").appendChild(Builder.node("li", [ ary.row[i].title]));
	} else {
		t$("delete_list").appendChild(Builder.node("li", [ jsAllItemsWillBeDeleted ]));
	}

	adesk_dom_display_block("delete");
}

function header_delete(id) {
	if (header_delete_id_multi != "") {
		header_delete_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "header.header_delete", header_delete_cb, id);
}

function header_delete_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(header_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
}

function header_delete_multi() {
	if (selectAllSwitch) {
		adesk_ajax_call_cb("awebdeskapi.php", "header.header_delete_multi", header_delete_multi_cb, "_all", header_list_filter);
		return;
	}
	header_delete_id_multi = "";
}

function header_delete_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(header_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
	$('selectXPageAllBox').className = 'adesk_hidden'; 
	$('acSelectAllCheckbox').checked = false;
}
{/literal}
