var adesk_admin_ismaingroup = {jsvar var=$adesk_admin_ismaingroup}
var user_form_str_cant_update = '{"You do not have permission to edit users"|alang|js}';
var user_form_str_cant_insert = '{"You do not have permission to add users"|alang|js}';
{literal}
var user_form_id = 0;

function user_form_defaults() {
	$("form_username").value = "";
	$("form_username").disabled = false;
	$("form_password").value = "";
	$("form_password_r").value = "";
	$("form_email").value = "";
	$("form_first_name").value = "";
	$("form_last_name").value = "";
	$("form_sourceid").value = "";
	$("form_id").value = 0;

	if ($("group_tr"))
		$("group_tr").className = "";

	if (typeof user_form_defaults_extended == "function")
		user_form_defaults_extended();
}

function user_form_load(id) {
	user_form_defaults();
	user_form_id = id;
	//user_group_refresh();

	if (id > 0) {
		if (typeof user_can_edit == "function" && !user_can_edit()) {
			adesk_ui_anchor_set(user_list_anchor());
			alert(user_form_str_cant_update);
			return;
		}
		adesk_ui_api_call(jsLoading);
		adesk_ajax_call_cb("awebdeskapi.php", "user!adesk_user_select_row", user_form_load_cb, id);
		if ($("user_form_submit")) {
			$("user_form_submit").className = 'adesk_button_add';
			$("user_form_submit").value = jsUpdate;
		}
	} else {
		if (typeof user_can_add == "function" && !user_can_add()) {
			adesk_ui_anchor_set(user_list_anchor());
			alert(user_form_str_cant_insert);
			return;
		}
		if ($("user_form_submit")) {
			$("user_form_submit").className = 'adesk_button_update';
			$("user_form_submit").value = jsAdd;
		}
		if ($("form_group")) {
			$A($("form_group").getElementsByTagName("option")).each(function(opt) {
					if (opt.value == 3)
						opt.selected = true;
					else
						opt.selected = false;
				});
		}
		if (typeof user_form_adduser_extended == "function")
			user_form_adduser_extended();
		$("form").className = "adesk_block";
	}
}

function user_form_load_cb(xml, text) {
	adesk_ui_api_callback();
	var ary = adesk_dom_read_node(xml, null);

	user_form_id = ary.id;

	$("form_username").value = ary.username;
	$("form_email").value = ary.email;
	$("form_first_name").value = ary.first_name;
	$("form_last_name").value = ary.last_name;
	$("form_sourceid").value = ary.sourceid;
	 
	$("form_id").value = ary.id;

	if ($("form_group"))
		$("form_group").disabled = false;

	if (typeof user_form_load_extended == "function")
		user_form_load_extended(ary);

	if (typeof ary.groups == "number")
		ary.groups = [ary.groups.toString()];
	else if (typeof ary.groups == "string") {
		ary.groups = ary.groups.split(",");
	}

	if ($("form_group"))
		$A($("form_group").getElementsByTagName("option")).each(function(opt) {
			opt.selected = ary.groups.indexOf(opt.value) > -1;
		});

	// If it's the Admin user, they can't change their Group, because Admin user has to be in the Admin Group.
	// Also, if it's a non-Admin user, they should also not be able to edit Groups, simply because they are not Admin users.
	if ($("group_tr")) {
		if (ary.id == 1 || !adesk_admin_ismaingroup) {
			$("group_tr").className = "adesk_hidden";
		}
		else {
			$("group_tr").className = "";
		}
	}

	/*
	var group = $("form_group");
	for (var i = 0; i < group.childNodes.length; i++) {
		if (typeof group.childNodes[i].value != "undefined" && adesk_array_has(ary.groups, group.childNodes[i].value)) {
			group.childNodes[i].selected = true;
		}
	}
	 */

	// If this is the "admin" user, make the form element look disabled.

	if (ary.id == 1)
		$("form_username").disabled = true;
	else
		$("form_username").disabled = false;

	$("form").className = "adesk_block";
}

function user_form_save(id) {
	var post = adesk_form_post('form');

	if (typeof user_form_validate_extended == "function") {
		if (!user_form_validate_extended(post)) {
			return false;
		}
	} else {
		if (post.username == "" ||
				(post.password == "" && post.password_r != "") ||
				(post.password_r == "" && post.password != "")) {
			alert(jsUserFormValidationFail);
			return false;
		}
	}

	// trim it
	post.username = adesk_str_trim(post.username, " ");

	if (!post.username.match(/^[a-z0-9_+@.\-]+$/)) {
		alert(jsUserFormValidationUserBadchars);
		return false;
	}

	if (post.password != post.password_r) {
		alert(jsUserFormPasswordMismatch);
		return false;
	}

	adesk_ui_api_call(jsSaving);
	if (id < 1)
		adesk_ajax_post_cb("awebdeskapi.php", "user!adesk_user_insert_post", user_form_save_cb, post);
	else
		adesk_ajax_post_cb('awebdeskapi.php', 'user!adesk_user_update_post', user_form_save_cb, post);
}

function user_form_save_cb(xml, text) {
	var ary = adesk_dom_read_node(xml, null);
	adesk_ui_api_callback();

	if (ary.succeeded) {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(user_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}
}
{/literal}
