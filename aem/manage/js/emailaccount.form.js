var emailaccount_form_str_cant_insert = '{"You do not have permission to add email accounts"|alang|js}';
var emailaccount_form_str_cant_update = '{"You do not have permission to edit email accounts"|alang|js}';
var emailaccount_form_str_cant_find = '{"Email Address not found."|alang|js}';
var emailaccount_form_str_email_invalid = '{"Email Address is not valid."|alang|js}';
var emailaccount_form_str_host_missing = '{"Host name not entered."|alang|js}';
var emailaccount_form_str_user_missing = '{"Account username not entered."|alang|js}';
{literal}
var emailaccount_form_id = 0;

function emailaccount_form_defaults() {
	$("form_id").value = 0;
	if ( emailaccount_listfilter && typeof(emailaccount_listfilter) == 'object' ) {
		adesk_form_select_multiple($('parentsList'), emailaccount_listfilter);
	} else if ( emailaccount_listfilter > 0 ) {
		$('parentsList').value = emailaccount_listfilter;
	} else {
		adesk_form_select_multiple_all($('parentsList'));
	}
	if ( $('emailField').nodeName == 'SELECT' ) {
		$('emailField').selectedIndex = 0;
	} else {
		$('emailField').value = '';
	}
	$('actionField').value = 'sub';
	$('typeField').value = 'pop3';
	$('typeOptions').className = 'adesk_table_rowgroup';
	$('hostField').value = '';
	$('portField').value = 110;
	$('userField').value = '';
	$('passField').value = '';
	$('batchField').value = 120;
	$('accountFilterBox').className = 'adesk_hidden';
	$('filteruseField').checked = false;
	$('filterfieldField').selectedIndex = 0;
	$('filtercondField').selectedIndex = 0;
	$('filtervalField').value = '';

	if ( $('emailField').nodeName == 'SELECT' ) {
		$('typeField').value = 'pipe';
		emailaccount_form_toggle_type('pipe');
	}
}

function emailaccount_form_load(id) {
	emailaccount_form_defaults();
	emailaccount_form_id = id;

	if (id > 0) {
		if (adesk_js_admin.pg_list_edit != 1) {
			adesk_ui_anchor_set(emailaccount_list_anchor());
			alert(emailaccount_form_str_cant_update);
			return;
		}

		adesk_ui_api_call(jsLoading);
		$("form_submit").className = "adesk_button_update";
		$("form_submit").value = jsUpdate;
		adesk_ajax_call_cb("awebdeskapi.php", "emailaccount.emailaccount_select_row", emailaccount_form_load_cb, id);
	} else {
		if (adesk_js_admin.pg_list_edit != 1) {
			adesk_ui_anchor_set(emailaccount_list_anchor());
			alert(emailaccount_form_str_cant_insert);
			return;
		}

		$("form_submit").className = "adesk_button_add";
		$("form_submit").value = jsAdd;
		$("form").className = "adesk_block";
	}
}

function emailaccount_form_load_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if ( !ary.id ) {
		adesk_error_show(emailaccount_form_str_cant_find);
		adesk_ui_anchor_set(emailaccount_list_anchor());
		return;
	}

	emailaccount_form_id = ary.id;

	$("form_id").value = ary.id;
	$('emailField').value = ary.email;
	$('actionField').value = ary.action;
	$('typeField').value = ary.type;
	$('hostField').value = ary.host;
	$('portField').value = ary.port;
	$('userField').value = ary.user;
	$('passField').value = ary.pass;
	$('batchField').value = ary.emails_per_batch;
	$('filteruseField').checked = ( ary.filteruse == 1 );
	$('accountFilterBox').className = ( ary.filteruse == 1 ? 'adesk_inline' : 'adesk_hidden' );
	if ( ary.filteruse == 1 ) {
		$('filterfieldField').value = ary.filterfield;
		$('filtercondField').value = ary.filtercond;
		$('filtervalField').value = ary.filterval;
	}
	emailaccount_form_toggle_type(ary.type);
	// lists
	adesk_form_select_multiple($('parentsList'), ( ary.lists + '' ).split('-'));

	$("form").className = "adesk_block";
}

function emailaccount_form_save(id) {
	var post = adesk_form_post($("form"));

	if ( !adesk_str_email(post.email) ) {
		alert(emailaccount_form_str_email_invalid);
		$('emailField').focus();
		return;
	}
	if ( post.type == 'pop3' ) {
		if ( post.host == '' ) {
			alert(emailaccount_form_str_host_missing);
			$('hostField').focus();
			return;
		}
		if ( post.user == '' ) {
			alert(emailaccount_form_str_user_missing);
			$('userField').focus();
			return;
		}
	}

	adesk_ui_api_call(jsSaving);
	if (id > 0)
		adesk_ajax_post_cb("awebdeskapi.php", "emailaccount.emailaccount_update_post", emailaccount_form_save_cb, post);
	else
		adesk_ajax_post_cb("awebdeskapi.php", "emailaccount.emailaccount_insert_post", emailaccount_form_save_cb, post);
}

function emailaccount_form_save_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(emailaccount_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}
}



function emailaccount_form_toggle_type(val) {
	$('typeOptions').className = ( val == 'pop3' ? 'adesk_table_rowgroup' : 'adesk_hidden');
}


{/literal}
