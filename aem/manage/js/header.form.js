var header_form_str_cant_insert = '{"You do not have permission to add Email Headers."|alang|js}';
var header_form_str_cant_update = '{"You do not have permission to edit Email Headers."|alang|js}';
var header_form_str_cant_find   = '{"Email Header not found."|alang|js}';

{jsvar name=fields var=$fields}

{literal}

var customFieldsObj = new ACCustomFields({
	sourceType: 'SELECT',
	sourceId: 'parentsList',
	api: 'list.list_field_update',
	responseIndex: 'fields',
	includeGlobals: 0,
	additionalHandler: function(ary) {
	}
});
customFieldsObj.addHandler('headerPersTags', 'pers');

var header_form_id = 0;

function header_form_defaults() {
	$("form_id").value = 0;
	$("titleField").value = '';
	$("nameField").value = '';
	$("valueField").value = '';
	if ( header_listfilter && typeof(header_listfilter) == 'object' ) {
		adesk_form_select_multiple($('parentsList'), header_listfilter);
	} else if ( header_listfilter > 0 ) {
		$('parentsList').value = header_listfilter;
	} else {
		adesk_form_select_multiple_all($('parentsList'));
	}
	$("tstampSpan").innerHTML = '';
	$("tstampRow").className = 'adesk_hidden';
	form_editor_personalization('header', [ 'subscriber', 'sender', 'system' ], 'text');
}

function header_form_load(id) {
	header_form_defaults();
	header_form_id = id;

	if (id > 0) {
		if (!adesk_js_admin.pg_list_edit) {
			adesk_ui_anchor_set(header_list_anchor());
			alert(header_form_str_cant_update);
			return;
		}

		adesk_ui_api_call(jsLoading);
		$("form_submit").className = "adesk_button_update";
		$("form_submit").value = jsUpdate;
		adesk_ajax_call_cb("awebdeskapi.php", "header.header_select_row", header_form_load_cb, id);
	} else {
		if (!adesk_js_admin.pg_list_edit) {
			adesk_ui_anchor_set(header_list_anchor());
			alert(header_form_str_cant_insert);
			return;
		}

		customFieldsObj.fetch(0);

		$("form_submit").className = "adesk_button_add";
		$("form_submit").value = jsAdd;
		$("form").className = "adesk_block";
	}
}

function header_form_load_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if ( !ary.id ) {
		adesk_error_show(header_form_str_cant_find);
		adesk_ui_anchor_set(header_list_anchor());
		return;
	}

	header_form_id = ary.id;

	$("form_id").value = ary.id;
	$("titleField").value = ary.title;
	$("nameField").value = ary.name;
	$("valueField").value = ary.value;
	$("tstampSpan").innerHTML = sql2date(ary.tstamp).format(dateformat);
	$("tstampRow").className = 'adesk_table_row';
	adesk_form_select_multiple($('parentsList'), ( ary.lists + '' ).split('-'));

	$("form").className = "adesk_block";
}

function header_form_save(id) {
	var post = adesk_form_post($("form"));

	if ( post.title == '' ) {
		alert(strHeaderTitleEmpty);
		$('titleField').focus();
		return;
	}
	if ( post.name == '' ) {
		alert(strHeaderNameEmpty);
		$('nameField').focus();
		return;
	}
	if ( post.name.match(/^(bcc|cc|date|from|return-path|sender|subject|to|x-mailer|x-mid|x-priority)$/i) ) {
		alert(strHeaderNameInvalid);
		$('nameField').focus();
		return;
	}
	if ( post.value == '' ) {
		alert(strHeaderValueEmpty);
		$('valueField').focus();
		return;
	}

	adesk_ui_api_call(jsSaving);

	if (id > 0)
		adesk_ajax_post_cb("awebdeskapi.php", "header.header_update_post", header_form_save_cb, post);
	else
		adesk_ajax_post_cb("awebdeskapi.php", "header.header_insert_post", header_form_save_cb, post);
}

function header_form_save_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(header_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}
}
{/literal}
