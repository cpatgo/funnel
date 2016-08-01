var settings_form_str_cant_insert = '{"You do not have permission to add system settings"|alang|js}';
var settings_form_str_cant_update = '{"You do not have permission to edit system settings"|alang|js}';
var settings_form_str_cant_find   = '{"Settings not found."|alang|js}';
{literal}
var settings_form_id = 0;

function settings_form_defaults() {
}

function settings_form_load() {
	settings_form_defaults();

	if (adesk_js_admin.id != 1) {
		alert(settings_form_str_cant_update);
		window.history.go(-1);
		return;
	}

	adesk_ui_api_call(jsLoading);
	$("form_submit").className = "adesk_button_update";
	$("form_submit").value = jsUpdate;
	adesk_ajax_call_cb("awebdeskapi.php", "settings.settings_select_row", settings_form_load_cb);
}

function settings_form_load_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if ( !ary.id ) {
		adesk_error_show(settings_form_str_cant_find);
		window.history.go(-1);
		return;
	}

	$("form").className = "adesk_block";
}

function settings_form_save() {
	var post = adesk_form_post($("form"));
	adesk_ui_api_call(jsSaving);

	adesk_ajax_post_cb("awebdeskapi.php", "settings.settings_update_post", settings_form_save_cb, post);
}

function settings_form_save_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
	} else {
		adesk_error_show(ary.message);
	}
}
{/literal}
