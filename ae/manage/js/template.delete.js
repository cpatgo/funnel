var template_delete_str = '{"Are you sure you want to delete template %s?"|alang|js}';
var template_delete_str_multi = '{"Are you sure you want to delete the following template?"|alang|js}';
var template_delete_str_cant_delete = '{"You do not have permission to delete templates"|alang|js}';
{literal}
var template_delete_id = 0;
var template_delete_id_multi = "";

function template_delete_check(id) {
	if (adesk_js_admin.pg_template_delete != 1) {
		adesk_ui_anchor_set(template_list_anchor());
		alert(template_delete_str_cant_delete);
		return;
	}

	if (id < 1) {
		template_delete_check_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "template.template_select_row", template_delete_check_cb, id);
}

function template_delete_check_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	adesk_dom_remove_children($("delete_list"));

	template_delete_id = ary.id;
	$("delete_message").innerHTML = sprintf(template_delete_str, ary.name);
	adesk_dom_display_block("delete");	// can't use toggle here in IE
}

function template_delete_check_multi() {
	if (adesk_js_admin.pg_template_delete != 1) {
		adesk_ui_anchor_set(template_list_anchor());
		alert(template_delete_str_cant_delete);
		return;
	}

	if (!adesk_form_check_selection_check($("list_table"), "multi[]", jsNothingSelected, jsNothingFound)) {
		adesk_ui_anchor_set(template_list_anchor());
		return;
	}

	var sel = adesk_form_check_selection_get($("list_table"), "multi[]");
	adesk_ajax_call_cb("awebdeskapi.php", "template.template_select_array", template_delete_check_multi_cb, 0, sel.join(","));
	template_delete_id_multi = sel.join(",");
}

function template_delete_check_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	$("delete_message").innerHTML = template_delete_str_multi;

	adesk_dom_remove_children($("delete_list"));
	if (!selectAllSwitch) {
		for (var i = 0; i < ary.row.length; i++)
			$("delete_list").appendChild(Builder.node("li", [ ary.row[i].name ]));
	} else {
		$("delete_list").appendChild(Builder.node("li", [ jsAllItemsWillBeDeleted ]));
	}

	adesk_dom_display_block("delete");
}

function template_delete(id) {
	if (template_delete_id_multi != "") {
		template_delete_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "template.template_delete", template_delete_cb, id);
}

function template_delete_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(template_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
}

function template_delete_multi() {
	if (selectAllSwitch) {
		adesk_ajax_call_cb("awebdeskapi.php", "template.template_delete_multi", template_delete_multi_cb, "_all", template_list_filter);
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "template.template_delete_multi", template_delete_multi_cb, template_delete_id_multi);
	template_delete_id_multi = "";
}

function template_delete_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(template_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
	$("acSelectAllCheckbox").checked = false;
	$('selectXPageAllBox').className = 'adesk_hidden';
}
{/literal}
