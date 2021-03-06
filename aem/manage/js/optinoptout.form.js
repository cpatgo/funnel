var optinoptout_form_str_cant_insert = '{"You do not have permission to add Email Confirmation Set"|alang|js}';
var optinoptout_form_str_cant_update = '{"You do not have permission to edit Email Confirmation Set"|alang|js}';
var optinoptout_form_str_cant_find   = '{"Email Confirmation Set not found."|alang|js}';
var optinoptout_form_str_nolists     = '{"You must select at least one list which may access this email confirmation set"|alang|js}';

{jsvar name=fields var=$fields}
{* jsvar name=editorTemplates var=$templates *}

{literal}
var optinoptout_form_id = 0;

var lists = [];
for ( var i in adesk_js_admin.lists ) {
	var l = adesk_js_admin.lists[i];
	if ( typeof l != 'function' ) {
		lists.push(l);
	}
}

var ranpers = false;

adesk_editor_init_word_object.plugins += ",fullpage";

var customFieldsObj = new ACCustomFields({
	//sourceType: 'SELECT',
	//sourceId: 'parentsList',
	sourceType: 'STATIC',
	rels: lists,
	api: 'list.list_field_update',
	responseIndex: 'fields',
	includeGlobals: 0,
	additionalHandler: function(ary) {
		// deal with personalization tags
		if (!ranpers) {
			form_editor_sender_personalization(ary.personalizations, $('personalizelist'));
			ranpers = true;
		}
		/*
		adesk_editor_toggle('optinEditor', adesk_editor_init_word_object);
		adesk_editor_toggle('optinEditor', adesk_editor_init_word_object);
		adesk_editor_toggle('optoutEditor', adesk_editor_init_word_object);
		adesk_editor_toggle('optoutEditor', adesk_editor_init_word_object);
		*/
	}
});
customFieldsObj.addHandler('conditionalfield', 'pers');
customFieldsObj.addHandler('personalizelist', 'links');

function optinoptout_form_defaults() {
	$("form_id").value = 0;
	//form_editor_personalization('optin', [ 'subscriber', 'sender', 'system' ], 'mime');
	//form_editor_personalization('optout', [ 'subscriber', 'sender', 'system' ], 'mime');
	optinoptout_defaults();
	customFieldsObj.fetch(0);
}

function optinoptout_form_load(id) {
	optinoptout_form_id = id;

	if (id > 0) {
		if (adesk_js_admin.pg_list_edit != 1) {
			adesk_ui_anchor_set(optinoptout_list_anchor());
			alert(optinoptout_form_str_cant_update);
			return;
		}

		optinoptout_form_defaults();

		adesk_ui_api_call(jsLoading);
		$("form_submit").className = "adesk_button_update";
		$("form_submit").value = jsUpdate;
		adesk_ajax_call_cb("awebdeskapi.php", "optinoptout.optinoptout_select_row_ajax", optinoptout_form_load_cb, id);
	} else {
		optinoptout_form_defaults();

		if (adesk_js_admin.pg_list_edit != 1) {
			adesk_ui_anchor_set(optinoptout_list_anchor());
			alert(optinoptout_form_str_cant_insert);
			return;
		}

		$("form_submit").className = "adesk_button_add";
		$("form_submit").value = jsAdd;
		$("form").className = "adesk_block";
	}
}

function optinoptout_form_load_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if ( !ary.id ) {
		adesk_error_show(optinoptout_form_str_cant_find);
		adesk_ui_anchor_set(optinoptout_list_anchor());
		return;
	}

	optinoptout_form_id = ary.id;

	$("form").className = "adesk_block";

	// Doing this here b/c odd issues when trying to populate this editor via optinoptout_update()
	//adesk_form_value_set($("optoutEditor"), ary.optout_html);

	$("form_id").value = ary.id;

	var lists = ary.lists.toString().split(",");
	$A(lists).each(function(e) { $("form_list" + e.toString()).selected = true; });

	optinoptout_update(ary);

	optinoptout_ie_fix();
}

function optinoptout_form_save(id) {
	var post = adesk_form_post($("form"));

	if ($("form_lists").value == "") {
		alert(optinoptout_form_str_nolists);
		return;
	}

	optinout_save(post, optinoptout_form_save_cb);
}

function optinoptout_form_save_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(optinoptout_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}
}
{/literal}
