var subscriber_delete_str = '{"Are you sure you want to delete subscriber %s?"|alang|js}';
var subscriber_delete_str_multi = '{"Are you sure you want to delete the following subscribers?"|alang|js}';
var subscriber_delete_str_cant_delete = '{"You do not have permission to delete subscribers"|alang|js}';
{literal}
var subscriber_delete_id = 0;
var subscriber_delete_id_multi = "";

function subscriber_delete_check(id) {
	if (adesk_js_admin.pg_subscriber_delete != 1) {
		adesk_ui_anchor_set(subscriber_list_anchor());
		alert(subscriber_delete_str_cant_delete);
		return;
	}

	if (id < 1) {
		subscriber_delete_check_multi();
		return;
	}

	$("delete_lists").style.display = 'none';
	adesk_dom_remove_children($("delete_list"));

	adesk_ajax_call_cb("awebdeskapi.php", "subscriber.subscriber_select_row", subscriber_delete_check_cb, id);
}

function subscriber_delete_check_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	adesk_dom_remove_children($("delete_lists"));
	var listfilterid = $("JSListManager").value;

	for (var i = 0; i < ary.lists.length; i++) {
		var props = { type: "checkbox", name: "listids[]", value: ary.lists[i].listid };
		
		if (listfilterid != "0") {
			if (ary.lists[i].listid == listfilterid)
				props.checked = true;
		} else {
			props.checked = true;
		}

		$("delete_lists").appendChild(
				Builder.node("div", [
					Builder.node("input", props),
					Builder._text(" "),
					Builder._text(ary.lists[i].listname)
				]
			));
	}

	subscriber_delete_id = ary.id;
	$("delete_message").innerHTML = sprintf(subscriber_delete_str, ary.email);
	adesk_dom_display_block("delete");
}

function subscriber_delete_check_multi() {
	if (adesk_js_admin.pg_subscriber_delete != 1) {
		adesk_ui_anchor_set(subscriber_list_anchor());
		alert(subscriber_delete_str_cant_delete);
		return;
	}

	if (!adesk_form_check_selection_check($("list_table"), "multi[]", jsNothingSelected, jsNothingFound)) {
		adesk_ui_anchor_set(subscriber_list_anchor());
		return;
	}

	var sel = adesk_form_check_selection_get($("list_table"), "multi[]");
	$("delete_lists").style.display = 'none';
	adesk_dom_remove_children($("delete_list"));
	adesk_ajax_call_cb("awebdeskapi.php", "subscriber.subscriber_select_array_alt", subscriber_delete_check_multi_cb, 0, sel.join(","));
	subscriber_delete_id_multi = sel.join(",");
}

function subscriber_delete_check_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	$("delete_message").innerHTML = subscriber_delete_str_multi;

	adesk_dom_remove_children($("delete_list"));

	$("delete_lists").innerHTML = $("delete_multilists").innerHTML;

	if (!selectAllSwitch) {
		for (var i = 0; i < ary.row.length; i++)
			$("delete_list").appendChild(Builder.node("li", [ ary.row[i].email ]));
	} else {
		$("delete_list").appendChild(Builder.node("li", [ jsAllItemsWillBeDeleted ]));
	}

	adesk_dom_display_block("delete");
}

function subscriber_delete(id) {
	if (subscriber_delete_id_multi != "") {
		subscriber_delete_multi();
		return;
	}

	var post = adesk_form_post("delete");
	post.id = id;

	adesk_ajax_post_cb("awebdeskapi.php", "subscriber.subscriber_delete_post", subscriber_delete_cb, post);
}

function subscriber_delete_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(subscriber_list_anchor());
	} else {
		adesk_error_show(ary.message);

		if (typeof ary.pastlimit != "undefined")
			$("sublimit").show();
	}

	adesk_dom_toggle_display("delete", "block");
}

function subscriber_delete_multi() {
	var post = adesk_form_post("delete");

	if (selectAllSwitch) {
		post.ids = "_all";
		post.filter = subscriber_list_filter;
		adesk_ajax_post_cb("awebdeskapi.php", "subscriber.subscriber_delete_multi_post", subscriber_delete_multi_cb, post);
		return;
	}

	post.ids = subscriber_delete_id_multi;
	adesk_ajax_post_cb("awebdeskapi.php", "subscriber.subscriber_delete_multi_post", subscriber_delete_multi_cb, post);
	subscriber_delete_id_multi = "";
}

function subscriber_delete_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(subscriber_list_anchor());
	} else {
		adesk_error_show(ary.message);

		if (typeof ary.pastlimit != "undefined")
			$("sublimit").show();
	}

	$('selectXPageAllBox').className = 'adesk_hidden';
	$('acSelectAllCheckbox').checked = false;
	
	adesk_dom_toggle_display("delete", "block");
}
{/literal}
