var campaign_delete_str = '{"Are you sure you want to delete campaign %s?"|alang|js}';
var campaign_delete_str_multi = '{"Are you sure you want to delete the following campaign?"|alang|js}';
var campaign_delete_str_cant_delete = '{"You do not have permission to delete campaigns"|alang|js}';
{literal}
var campaign_delete_id = 0;
var campaign_delete_id_multi = "";

function campaign_delete_check(id) {
	if (adesk_js_admin.pg_message_delete != 1) {
		adesk_ui_anchor_set(campaign_list_anchor());
		alert(campaign_delete_str_cant_delete);
		return;
	}

	if (id < 1) {
		campaign_delete_check_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "campaign.campaign_select_row", campaign_delete_check_cb, id);
}

function campaign_delete_check_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	adesk_dom_remove_children($("delete_list"));

	campaign_delete_id = ary.id;
	$("delete_message").innerHTML = sprintf(campaign_delete_str, ary.name);
	adesk_dom_display_block("delete");	// can't use toggle here in IE
}

function campaign_delete_check_multi() {
	if (adesk_js_admin.pg_message_delete != 1) {
		adesk_ui_anchor_set(campaign_list_anchor());
		alert(campaign_delete_str_cant_delete);
		return;
	}

	if (!adesk_form_check_selection_check($("list_table"), "multi[]", jsNothingSelected, jsNothingFound)) {
		adesk_ui_anchor_set(campaign_list_anchor());
		return;
	}

	var sel = adesk_form_check_selection_get($("list_table"), "multi[]");
	adesk_ajax_call_cb("awebdeskapi.php", "campaign.campaign_select_array", campaign_delete_check_multi_cb, 0, sel.join(","));
	campaign_delete_id_multi = sel.join(",");
}

function campaign_delete_check_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	$("delete_message").innerHTML = campaign_delete_str_multi;

	adesk_dom_remove_children($("delete_list"));
	if (!selectAllSwitch) {
		for (var i = 0; i < ary.row.length; i++)
			$("delete_list").appendChild(Builder.node("li", [ ary.row[i].name ]));
	} else {
		$("delete_list").appendChild(Builder.node("li", [ jsAllItemsWillBeDeleted ]));
	}

	adesk_dom_display_block("delete");
}

function campaign_delete(id) {
	if (campaign_delete_id_multi != "") {
		campaign_delete_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "campaign.campaign_delete", campaign_delete_cb, id);
}

function campaign_delete_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(campaign_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
}

function campaign_delete_multi() {
	if (selectAllSwitch) {
		adesk_ajax_call_cb("awebdeskapi.php", "campaign.campaign_delete_multi", campaign_delete_multi_cb, "_all", campaign_list_filter);
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "campaign.campaign_delete_multi", campaign_delete_multi_cb, campaign_delete_id_multi);
	campaign_delete_id_multi = "";
}

function campaign_delete_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(campaign_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	$('selectXPageAllBox').className = 'adesk_hidden';
	$('acSelectAllCheckbox').checked = false;
	
	adesk_dom_toggle_display("delete", "block");
}
{/literal}
