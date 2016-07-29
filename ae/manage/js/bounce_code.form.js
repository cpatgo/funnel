var bounce_code_form_str_cant_insert = '{"You do not have permission to add Bounce Code"|alang|js}';
var bounce_code_form_str_cant_update = '{"You do not have permission to edit Bounce Code"|alang|js}';
var bounce_code_form_str_cant_find   = '{"Bounce Code not found."|alang|js}';
var bounce_code_form_str_code_missing = '{"Bounce Code not entered."|alang|js}';
var bounce_code_form_str_match_missing = '{"Matching String not entered."|alang|js}';
{literal}
var bounce_code_form_id = 0;

function bounce_code_form_defaults() {
	$("form_id").value = 0;
	$("codeField").value = '';
	$("matchField").value = '';
	$("typeField").value = 'soft';
	$("descriptField").value = '';
}

function bounce_code_form_load(id) {
	bounce_code_form_defaults();
	bounce_code_form_id = id;

	if (id > 0) {
		if (adesk_js_admin.id != 1) {
			adesk_ui_anchor_set(bounce_code_list_anchor());
			alert(bounce_code_form_str_cant_update);
			return;
		}

		adesk_ui_api_call(jsLoading);
		$("form_submit").className = "adesk_button_update";
		$("form_submit").value = jsUpdate;
		adesk_ajax_call_cb("awebdeskapi.php", "bounce_code.bounce_code_select_row", bounce_code_form_load_cb, id);
	} else {
		if (adesk_js_admin.id != 1) {
			adesk_ui_anchor_set(bounce_code_list_anchor());
			alert(bounce_code_form_str_cant_insert);
			return;
		}

		$("form_submit").className = "adesk_button_add";
		$("form_submit").value = jsAdd;
		$("form").className = "adesk_block";
	}
}

function bounce_code_form_load_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if ( !ary.id ) {
		adesk_error_show(bounce_code_form_str_cant_find);
		adesk_ui_anchor_set(bounce_code_list_anchor());
		return;
	}
	bounce_code_form_id = ary.id;

	$("form_id").value = ary.id;
	$("codeField").value = ary.code;
	$("matchField").value = ary.match;
	$("typeField").value = ( ary.type == 'hard' ? 'hard' : 'soft' );
	$("descriptField").value = ary.descript;

	$("form").className = "adesk_block";
}

function bounce_code_form_save(id) {
	var post = adesk_form_post($("form"));

	if ( adesk_str_trim(post.code) == '' ) {
		alert(bounce_code_form_str_code_missing);
		$('codeField').focus();
		return;
	}
	if ( post.match == '' ) {
		alert(bounce_code_form_str_match_missing);
		$('matchField').focus();
		return;
	}

	adesk_ui_api_call(jsSaving);

	if (id > 0)
		adesk_ajax_post_cb("awebdeskapi.php", "bounce_code.bounce_code_update_post", bounce_code_form_save_cb, post);
	else
		adesk_ajax_post_cb("awebdeskapi.php", "bounce_code.bounce_code_insert_post", bounce_code_form_save_cb, post);
}

function bounce_code_form_save_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(bounce_code_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}
}
{/literal}
