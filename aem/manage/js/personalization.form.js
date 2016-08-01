var personalization_form_str_cant_insert = '{"You do not have permission to add Personalization Tag"|alang|js}';
var personalization_form_str_cant_update = '{"You do not have permission to edit Personalization Tag"|alang|js}';
var personalization_form_str_cant_find   = '{"Personalization Tag not found."|alang|js}';

{jsvar name=fields var=$fields}

//adesk_editor_init_word_object.plugins += ",ota_personalize,ota_conditional";
//adesk_editor_init_word_object.theme_advanced_buttons1_add += ",ota_personalize,ota_conditional";
adesk_editor_init_word_object.language = _twoletterlangid;
adesk_editor_init_word();

{literal}

var customFieldsObj = new ACCustomFields({
	sourceType: 'SELECT',
	sourceId: 'parentsList',
	api: 'list.list_field_update',
	responseIndex: 'fields',
	includeGlobals: 0,
	additionalHandler: function(ary) {
		// deal with personalization tags
		form_editor_sender_personalization(ary.personalizations, $('personalizelist'));
		/*
		if (adesk_editor_is("personalizationEditor")) {
			adesk_editor_toggle('personalizationEditor', adesk_editor_init_word_object);
			adesk_editor_toggle('personalizationEditor', adesk_editor_init_word_object);
		}
		*/
	}
});
customFieldsObj.addHandler('personalizelist', 'links');
customFieldsObj.addHandler('conditionalfield', 'pers');

var personalization_form_id = 0;

function personalization_form_defaults() {
	$("form_id").value = 0;

	$('nameField').value = '';
	$('tagField').value = '';
	if ( personalization_listfilter && typeof(personalization_listfilter) == 'object' ) {
		adesk_form_select_multiple($('parentsList'), personalization_listfilter);
	} else if ( personalization_listfilter > 0 ) {
		$('parentsList').value = personalization_listfilter;
	} else {
		adesk_form_select_multiple_all($('parentsList'));
	}
	form_editor_defaults('personalization', 'html', [ 'subscriber', 'system' ]);
	form_editor_personalization('conditionalfield', [ 'subscriber', 'sender', 'system' ], 'text', '');
}

function personalization_form_load(id) {
	personalization_form_defaults();
	personalization_form_id = id;

	if (id > 0) {
		if (adesk_js_admin.pg_template_edit != 1) {
			adesk_ui_anchor_set(personalization_list_anchor());
			alert(personalization_form_str_cant_update);
			return;
		}

		adesk_ui_api_call(jsLoading);
		$("form_submit").className = "adesk_button_update";
		$("form_submit").value = jsUpdate;
		adesk_ajax_call_cb("awebdeskapi.php", "personalization.personalization_select_row", personalization_form_load_cb, id);
	} else {
		if (adesk_js_admin.pg_template_add != 1) {
			adesk_ui_anchor_set(personalization_list_anchor());
			alert(personalization_form_str_cant_insert);
			return;
		}

		// get custom fields for the preselect value for add
		customFieldsObj.fetch(0);

		$("form_submit").className = "adesk_button_add";
		$("form_submit").value = jsAdd;
		$("form").className = "adesk_block";
	}
}

function personalization_form_load_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if ( !ary.id ) {
		adesk_error_show(personalization_form_str_cant_find);
		adesk_ui_anchor_set(personalization_list_anchor());
		return;
	}
	personalization_form_id = ary.id;

	$("form_id").value = ary.id;

	$('nameField').value = ary.name;
	$('tagField').value = ary.tag;
	adesk_form_select_multiple($('parentsList'), ( ary.lists + '' ).split('-'));
	ary.html = ( ary.format == 'html' ? ary.content : '' );
	ary.text = ( ary.format == 'text' ? ary.content : '' );
	form_editor_update('personalization', ary);
	form_editor_update_fields('conditionalfield', ary, '');

	$("form").className = "adesk_block";
}

function personalization_form_save(id) {
	var post = adesk_form_post($("form"));
	adesk_ui_api_call(jsSaving);

	if (id > 0)
		adesk_ajax_post_cb("awebdeskapi.php", "personalization.personalization_update_post", personalization_form_save_cb, post);
	else
		adesk_ajax_post_cb("awebdeskapi.php", "personalization.personalization_insert_post", personalization_form_save_cb, post);
}

function personalization_form_save_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(personalization_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}
}
{/literal}
