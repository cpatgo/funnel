var approval_form_str_cant_insert = '{"You do not have permission to add Campaign Approval"|alang|js}';
var approval_form_str_cant_update = '{"You do not have permission to edit Campaign Approval"|alang|js}';
var approval_form_str_cant_find   = '{"Campaign Approval not found."|alang|js}';
{literal}
var approval_form_id = 0;

function approval_form_defaults() {
	$("form_id").value = 0;
}

function approval_form_load(id) {
	approval_form_defaults();
	approval_form_id = id;

	if (id > 0) {
		/*
		if (adesk_js_admin.__CAN_EDIT__ != 1) {
			adesk_ui_anchor_set(approval_list_anchor());
			alert(approval_form_str_cant_update);
			return;
		}
		*/

		adesk_ui_api_call(jsLoading);
		$("form_submit").className = "adesk_button_update";
		$("form_submit").value = jsUpdate;
		adesk_ajax_call_cb("awebdeskapi.php", "approval.approval_select_row", approval_form_load_cb, id);
	} else {
		/*
		if (adesk_js_admin.__CAN_ADD__ != 1) {
			adesk_ui_anchor_set(approval_list_anchor());
			alert(approval_form_str_cant_insert);
			return;
		}
		*/

		$("form_submit").className = "adesk_button_add";
		$("form_submit").value = jsAdd;
		$("form").className = "adesk_block";
	}
}

function approval_form_load_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if ( !ary.id ) {
		adesk_error_show(approval_form_str_cant_find);
		adesk_ui_anchor_set(approval_list_anchor());
		return;
	}
	approval_form_id = ary.id;

	$("form_id").value = ary.id;

	$("form").className = "adesk_block";
}

function approval_form_save(id) {
	var post = adesk_form_post($("form"));
	adesk_ui_api_call(jsSaving);

	if (id > 0)
		adesk_ajax_post_cb("awebdeskapi.php", "approval.approval_update_post", approval_form_save_cb, post);
	else
		adesk_ajax_post_cb("awebdeskapi.php", "approval.approval_insert_post", approval_form_save_cb, post);
}

function approval_form_save_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded && ary.succeeded == "1") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(approval_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}
}
{/literal}
