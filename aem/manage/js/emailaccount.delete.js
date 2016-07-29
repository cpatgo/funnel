var emailaccount_delete_str = '{"Are you sure you want to delete email account %s?"|alang|js}';
var emailaccount_delete_str_multi = '{"Are you sure you want to delete the following email account?"|alang|js}';
var emailaccount_delete_str_cant_delete = '{"You do not have permission to delete email account"|alang|js}';
{literal}
var emailaccount_delete_id = 0;
var emailaccount_delete_id_multi = "";

function emailaccount_delete_check(id) {
	if (adesk_js_admin.pg_list_edit != 1) {
		adesk_ui_anchor_set(emailaccount_list_anchor());
		alert(emailaccount_delete_str_cant_delete);
		return;
	}

	if (id < 1) {
		emailaccount_delete_check_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "emailaccount.emailaccount_select_row", emailaccount_delete_check_cb, id);
}

function emailaccount_delete_check_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	adesk_dom_remove_children($("delete_list"));

	emailaccount_delete_id = ary.id;
	$("delete_message").innerHTML = sprintf(emailaccount_delete_str, ary.email);
	adesk_dom_display_block("delete");
}

function emailaccount_delete_check_multi() {
	if (adesk_js_admin.pg_list_edit != 1) {
		adesk_ui_anchor_set(emailaccount_list_anchor());
		alert(emailaccount_delete_str_cant_delete);
		return;
	}

	if (!adesk_form_check_selection_check($("list_table"), "multi[]", jsNothingSelected, jsNothingFound)) {
		adesk_ui_anchor_set(emailaccount_list_anchor());
		return;
	}

	var sel = adesk_form_check_selection_get($("list_table"), "multi[]");
	adesk_ajax_call_cb("awebdeskapi.php", "emailaccount.emailaccount_select_array", emailaccount_delete_check_multi_cb, 0, sel.join(","));
	emailaccount_delete_id_multi = sel.join(",");
}

function emailaccount_delete_check_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	$("delete_message").innerHTML = emailaccount_delete_str_multi;

	adesk_dom_remove_children($("delete_list"));
	if (!selectAllSwitch) {
		for (var i = 0; i < ary.row.length; i++)
			$("delete_list").appendChild(Builder.node("li", [ ary.row[i].email ]));
	} else {
		t$("delete_list").appendChild(Builder.node("li", [ jsAllItemsWillBeDeleted ]));
	}

	adesk_dom_display_block("delete");
}

function emailaccount_delete(id) {
	if (emailaccount_delete_id_multi != "") {
		emailaccount_delete_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "emailaccount.emailaccount_delete", emailaccount_delete_cb, id);
}

function emailaccount_delete_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(emailaccount_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
}

function emailaccount_delete_multi() {
	if (selectAllSwitch) {
		adesk_ajax_call_cb("awebdeskapi.php", "emailaccount.emailaccount_delete_multi", emailaccount_delete_multi_cb, "_all", emailaccount_list_filter);
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "emailaccount.emailaccount_delete_multi", emailaccount_delete_multi_cb, emailaccount_delete_id_multi);
	emailaccount_delete_id_multi = "";
}

function emailaccount_delete_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(emailaccount_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
	$('selectXPageAllBox').className = 'adesk_hidden'; 
	$('acSelectAllCheckbox').checked = false;
}
{/literal}
