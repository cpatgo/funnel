var list_copy_str = '{"Are you sure you want to copy list %s?"|alang|js}';
var list_copy_str_cant_copy = '{"You do not have permission to copy lists"|alang|js}';
{literal}
var list_copy_id = 0;

function list_copy_check(id) {
	if (!canAddList || adesk_js_admin.pg_list_edit != 1) {
		adesk_ui_anchor_set(list_list_anchor());
		alert(list_copy_str_cant_copy);
		return;
	}

	// List Limit check
	if (!canAddList) {
		adesk_ui_anchor_set(list_list_anchor());
		alert(list_str_list_limit);
		return;
	}

	adesk_ajax_call_cb("awebdeskapi.php", "list.list_select_row", list_copy_check_cb, id);
}

function list_copy_check_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	list_copy_id = ary.id;
	$("copy_message").innerHTML = sprintf(list_copy_str, ary.name);

	$("copy_bounce").checked          = true;
	$("copy_exclusion").checked       = true;
	$("copy_filter").checked          = true;
	$("copy_header").checked          = true;
	$("copy_personalization").checked = true;
	$("copy_template").checked        = true;
	$("copy_field").checked           = true;
	$("copy_form").checked            = true;
	$("copy_subscriber").checked      = false;

	$("copy").style.display = "";
}

function list_copy(id) {
	var post = adesk_form_post("copy_pref");
	post.id = id;

	adesk_ajax_post_cb("awebdeskapi.php", "list.list_copy", list_copy_cb, post);
}

function list_copy_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(list_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	$("copy").style.display = "none";
}

{/literal}
