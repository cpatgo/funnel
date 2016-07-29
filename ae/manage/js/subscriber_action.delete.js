var subscriber_action_delete_str = '{"Are you sure you want to delete subscription rule %s?"|alang|js}';
var subscriber_action_delete_str_multi = '{"Are you sure you want to delete the following subscription rule?"|alang|js}';
var subscriber_action_delete_str_cant_delete = '{"You do not have permission to delete subscription rule"|alang|js}';
{literal}
var subscriber_action_delete_id = 0;
var subscriber_action_delete_id_multi = "";

function subscriber_action_delete_check(id) {
	if (adesk_js_admin.pg_subscriber_add != 1 ||adesk_js_admin.pg_subscriber_delete != 1) {
		adesk_ui_anchor_set(subscriber_action_list_anchor());
		alert(subscriber_action_delete_str_cant_delete);
		return;
	}

	if (id < 1) {
		subscriber_action_delete_check_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "subscriber_action.subscriber_action_select_row", subscriber_action_delete_check_cb, id);
}

function subscriber_action_delete_check_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	adesk_dom_remove_children($("delete_list"));

	subscriber_action_delete_id = ary.id;
	$("delete_message").innerHTML = sprintf(subscriber_action_delete_str, ary.name);
	adesk_dom_display_block("delete");
}

function subscriber_action_delete_check_multi() {
	if (adesk_js_admin.pg_subscriber_delete != 1) {
		adesk_ui_anchor_set(subscriber_action_list_anchor());
		alert(subscriber_action_delete_str_cant_delete);
		return;
	}

	if (!adesk_form_check_selection_check($("list_table"), "multi[]", jsNothingSelected, jsNothingFound)) {
		adesk_ui_anchor_set(subscriber_action_list_anchor());
		return;
	}

	var sel = adesk_form_check_selection_get($("list_table"), "multi[]");
	adesk_ajax_call_cb("awebdeskapi.php", "subscriber_action.subscriber_action_select_array", subscriber_action_delete_check_multi_cb, 0, sel.join(","));
	subscriber_action_delete_id_multi = sel.join(",");
}

function subscriber_action_delete_check_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	$("delete_message").innerHTML = subscriber_action_delete_str_multi;

	adesk_dom_remove_children($("delete_list"));
	if (!selectAllSwitch) {
		for (var i = 0; i < ary.row.length; i++)
			$("delete_list").appendChild(Builder.node("li", [ ary.row[i].name ]));
	} else {
		t$("delete_list").appendChild(Builder.node("li", [ jsAllItemsWillBeDeleted ]));
	}

	adesk_dom_display_block("delete");
}

function subscriber_action_delete(id) {
	if (subscriber_action_delete_id_multi != "") {
		subscriber_action_delete_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "subscriber_action.subscriber_action_delete", subscriber_action_delete_cb, id);
}

function subscriber_action_delete_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(subscriber_action_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
}

function subscriber_action_delete_multi() {
	if (selectAllSwitch) {
		adesk_ajax_call_cb("awebdeskapi.php", "subscriber_action.subscriber_action_delete_multi", subscriber_action_delete_multi_cb, "_all", subscriber_action_list_filter);
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "subscriber_action.subscriber_action_delete_multi", subscriber_action_delete_multi_cb, subscriber_action_delete_id_multi);
	subscriber_action_delete_id_multi = "";
}

function subscriber_action_delete_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(subscriber_action_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
	$('selectXPageAllBox').className = 'adesk_hidden'; 
	$('acSelectAllCheckbox').checked = false;
}
{/literal}
