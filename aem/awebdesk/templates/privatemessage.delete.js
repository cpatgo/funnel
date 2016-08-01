var privatemessage_delete_str = '{"Are you sure you want to delete Private Message %s?"|alang|js}';
var privatemessage_delete_str_multi = '{"Are you sure you want to delete the following Private Messages?"|alang|js}';
var privatemessage_delete_str_cant_delete = '{"You do not have permission to delete Private Messages"|alang|js}';
{literal}
var privatemessage_delete_id = 0;
var privatemessage_delete_id_multi = "";

function privatemessage_delete_check(id) {

	// Don't allow delete via permalink (IE: "delete-32") since we have removed the "Delete" link.
	// They have to use the checkboxes to delete.
	return;

	/*
	if (adesk_js_admin.pg_privmsg_delete != 1) {
		adesk_ui_anchor_set(privatemessage_list_anchor());
		alert(privatemessage_delete_str_cant_delete);
		return;
	}
	*/

	if (id < 1) {
		privatemessage_delete_check_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "privatemessage!adesk_privatemessage_select_row", privatemessage_delete_check_cb, id);
}

function privatemessage_delete_check_cb(xml) {
	var ary = adesk_dom_read_node(xml, null);

	adesk_dom_remove_children($("delete_list"));

	privatemessage_delete_id = ary.id;
	$("delete_message").innerHTML = sprintf(privatemessage_delete_str, ary.title);
	adesk_dom_display_block("delete");	// can't use toggle here in IE
}

function privatemessage_delete_check_multi() {
	/*
	if (adesk_js_admin.pg_privmsg_delete != 1) {
		adesk_ui_anchor_set(privatemessage_list_anchor());
		alert(privatemessage_delete_str_cant_delete);
		return;
	}
	*/

	if (!adesk_form_check_selection_check($("list_table"), "multi[]", jsNothingSelected, jsNothingFound)) {
		adesk_ui_anchor_set(privatemessage_list_anchor());
		return;
	}

	adesk_dom_remove_children($("delete_list"));

	var sel = adesk_form_check_selection_get($("list_table"), "multi[]");
	adesk_ajax_call_cb("awebdeskapi.php", "privatemessage!adesk_privatemessage_select_array", privatemessage_delete_check_multi_cb, 0, sel.join(","));
	privatemessage_delete_id_multi = sel.join(",");
}

function privatemessage_delete_check_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml, null);

	$("delete_message").innerHTML = privatemessage_delete_str_multi;

	adesk_dom_remove_children($("delete_list"));

	if (!selectAllSwitch) {
		for (var i = 0; i < ary.row.length; i++)
			$("delete_list").appendChild(Builder.node("li", [ ary.row[i].title ]));
	} else {
		$("delete_list").appendChild(Builder.node("li", [ jsAllItemsWillBeDeleted ]));
	}

	adesk_dom_display_block("delete");
}

function privatemessage_delete(id) {
	if (privatemessage_delete_id_multi != "") {
		privatemessage_delete_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "privatemessage!adesk_privatemessage_delete", privatemessage_delete_cb, id);
}

function privatemessage_delete_cb(xml) {
	var ary = adesk_dom_read_node(xml, null);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(privatemessage_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
}

function privatemessage_delete_multi() {
	if (selectAllSwitch) {
		adesk_ajax_call_cb("awebdeskapi.php", "privatemessage!adesk_privatemessage_delete_multi", privatemessage_delete_multi_cb, "_all", 0, $("privatemessage_filter").value);
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "privatemessage!adesk_privatemessage_delete_multi", privatemessage_delete_multi_cb, privatemessage_delete_id_multi, 0, $("privatemessage_filter").value);
	privatemessage_delete_id_multi = "";
}

function privatemessage_delete_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml, null);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(privatemessage_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
	$("acSelectAllCheckbox").checked = false;
}
{/literal}
