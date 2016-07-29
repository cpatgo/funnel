var list_delete_str = '{"Are you sure you want to delete list <strong>%s</strong>? All of the following data associated with this list will be deleted, and cannot be recovered: <ul><li/>Subscribers <li/>Campaigns <li/>Reports</ul> Do you wish to proceed?"|alang|js}';
var list_delete_str_multi = '{"Are you sure you want to delete the following lists? All campaigns associated with these lists will be deleted as well."|alang|js}';
var list_delete_str_cant_delete = '{"You do not have permission to delete lists"|alang|js}';
var list_delete_str_deleting = '{"Deleting..."|alang|js}';
{literal}
var list_delete_id = 0;
var list_delete_id_multi = "";

function list_delete_check(id) {
	if (adesk_js_admin.pg_list_delete != 1) {
		adesk_ui_anchor_set(list_list_anchor());
		alert(list_delete_str_cant_delete);
		return;
	}

	if (id < 1) {
		list_delete_check_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "list.list_select_row", list_delete_check_cb, id);
}

function list_delete_check_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	adesk_dom_remove_children($("delete_list"));

	// The row probably doesn't exist.
	if (typeof ary.id == "undefined") {
		adesk_ui_anchor_set(list_list_anchor());
		return;
	}

	list_delete_id = ary.id;
	$("delete_message").innerHTML = sprintf(list_delete_str, ary.name);
	$("delete").style.display = "block";
}

function list_delete_check_multi() {
	if (adesk_js_admin.pg_list_delete != 1) {
		adesk_ui_anchor_set(list_list_anchor());
		alert(list_delete_str_cant_delete);
		return;
	}

	if (!adesk_form_check_selection_check($("list_table"), "multi[]", jsNothingSelected, jsNothingFound)) {
		adesk_ui_anchor_set(list_list_anchor());
		return;
	}

	var sel = adesk_form_check_selection_get($("list_table"), "multi[]");
	adesk_ajax_call_cb("awebdeskapi.php", "list.list_select_array", list_delete_check_multi_cb, 0, sel.join(","));
	list_delete_id_multi = sel.join(",");
}

function list_delete_check_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	$("delete_message").innerHTML = list_delete_str_multi;

	adesk_dom_remove_children($("delete_list"));
	if (!selectAllSwitch) {
		for (var i = 0; i < ary.row.length; i++)
			$("delete_list").appendChild(Builder.node("li", [ ary.row[i].name ]));
	} else {
		t$("delete_list").appendChild(Builder.node("li", [ jsAllItemsWillBeDeleted ]));
	}

	adesk_dom_display_block("delete");
}

function list_delete(id) {
	if (list_delete_id_multi != "") {
		list_delete_multi();
		return;
	}
	adesk_dom_toggle_display("delete", "block");
	adesk_ui_api_call(list_delete_str_deleting);
	adesk_ajax_call_cb("awebdeskapi.php", "list.list_delete", list_delete_cb, id);
}

function list_delete_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(list_list_anchor());
	} else {
		adesk_error_show(ary.message);

		if (typeof ary.pastlimit != "undefined")
			$("sublimit").show();
	}
}

function list_delete_multi() {
	if (selectAllSwitch) {
		adesk_ajax_call_cb("awebdeskapi.php", "list.list_delete_multi", list_delete_multi_cb, "_all", list_list_filter);
		return;
	}
	adesk_dom_toggle_display("delete", "block");
	adesk_ui_api_call(list_delete_str_deleting);
	adesk_ajax_call_cb("awebdeskapi.php", "list.list_delete_multi", list_delete_multi_cb, list_delete_id_multi);
	list_delete_id_multi = "";
}

function list_delete_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(list_list_anchor());
	} else {
		adesk_error_show(ary.message);

		if (typeof ary.pastlimit != "undefined")
			$("sublimit").show();
	}
	
	$('selectXPageAllBox').className = 'adesk_hidden'; 
	$('acSelectAllCheckbox').checked = false;
}
{/literal}
