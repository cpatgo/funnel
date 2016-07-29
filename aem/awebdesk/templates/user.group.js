var user_group_str_blankname = '{"You must give a name to this group."|alang|js}';
var user_group_newid = 0;
{literal}
function user_group_defaults() {
	$("user_form_group_name").value = '';
	$("user_form_group_descript").value = '';

	if (typeof user_group_defaults_extended == "function")
		user_group_defaults_extended();
}

function user_group_refresh() {
	adesk_ajax_call_cb("awebdeskapi.php", "group!adesk_group_select_array_userpage", user_group_refresh_cb, user_form_id);
}

function user_group_refresh_cb(xml, text) {
	var ary = adesk_dom_read_node(xml, null);

	adesk_dom_remove_children($("form_group"));
	for (var i = 0; i < ary.row.length; i++) {
		var attrs = { value: ary.row[i].id };

		if (ary.row[i].id == 1)
			continue;

		$("form_group").appendChild(Builder.node("option", attrs, ary.row[i].title));
		$("form_group").lastChild.selected = false;

		if (ary.row[i]._selected == 1) {
			$("form_group").lastChild.selected = true;
		}

		/*
		if (typeof ary.row[i].maxid == "undefined" || ary.row[i].maxid != ary.row[i].id)
			$("form_group").lastChild.selected = false;
		 */
	}

	if (user_group_newid > 0)
		$("form_group").value = user_group_newid;
}

function user_group_save() {
	var post = adesk_form_post("group");

	if (post.title == "" || post.title.match(/^ +$/)) {
		alert(user_group_str_blankname);
		return false;
	}

	adesk_ajax_post_cb("awebdeskapi.php", "group!adesk_group_insert_post", user_group_save_cb, post);
}

function user_group_save_cb(xml, text) {
	var ary = adesk_dom_read_node(xml, null);
	adesk_ui_api_callback();

	user_group_newid = ary.group_id;

	if (ary.succeeded) {
		adesk_result_show(ary.message);
	} else {
		adesk_error_show(ary.message);
	}

	user_group_refresh();
	adesk_dom_toggle_display("group", "block");
}
{/literal}
