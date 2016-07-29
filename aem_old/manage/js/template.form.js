var template_form_str_cant_insert = '{"You do not have permission to add templates"|alang|js}';
var template_form_str_cant_update = '{"You do not have permission to edit templates"|alang|js}';
var template_form_str_cant_find   = '{"Template not found."|alang|js}';
var template_form_str_noname      = '{"You must give this template a name."|alang|js}';

{if $__ishosted}
var ishosted = true;
{else}
var ishosted = false;
{/if}

{jsvar name=fields var=$fields}

//adesk_editor_init_word_object.plugins += ",ota_personalize,ota_deskrss,ota_conditional";
//adesk_editor_init_word_object.theme_advanced_buttons1_add += ",ota_personalize,ota_deskrss,ota_conditional";
adesk_editor_init_word_object.language = _twoletterlangid;
adesk_editor_init_word_object.plugins += ",fullpage";
adesk_editor_init_word();

{literal}

var customFieldsObj = new ACCustomFields({
	sourceType: 'INPUT',
	sourceId: 'p',
	api: 'list.list_field_update',
	responseIndex: 'fields',
	includeGlobals: 0,
	additionalHandler: function(ary) {
		// deal with personalization tags
		form_editor_sender_personalization(ary.personalizations, $('personalizelist'));
		/*
		if (adesk_editor_is("templateEditor")) {
			adesk_editor_toggle('templateEditor', adesk_editor_init_word_object);
			adesk_editor_toggle('templateEditor', adesk_editor_init_word_object);
		}
		*/
	}
});
customFieldsObj.addHandler('personalizelist', 'links');
customFieldsObj.addHandler('conditionalfield', 'pers');

var template_form_id = 0;

function template_form_defaults() {
	$("form_id").value = 0;

	$('nameField').value = '';
	$('subjectField').value = '';
	if ( template_listfilter && typeof(template_listfilter) == 'object' ) {
		adesk_form_select_multiple($('parentsList'), template_listfilter);
	} else if ( template_listfilter > 0 ) {
		if ( $('p_' + template_listfilter) ) $('p_' + template_listfilter).checked = true;
	} else {
		var list_inputs = $('parentsList_div').getElementsByTagName('input');
		// check all lists first
		for (var i = 0; i < list_inputs.length; i++) {
			list_inputs[i].checked = true;
		}
	}
	if ( $('template_scope_specific') ) $('template_scope_specific').checked = true;
	$('template_form_lists').show();
	form_editor_defaults('template', 'html', [ 'subscriber', 'sender', 'system' ]);
	form_editor_personalization('conditionalfield', [ 'subscriber' ], 'text', '');
	template_preview_upload_reset(); // reset upload form stuff
}

function template_form_load(id) {
	template_form_defaults();
	template_form_id = id;

	// adjust the file upload iframe src attribute, so it includes the form ID
	//$('template_preview_iframe').src += '&relid=' + template_form_id;

	if (id > 0) {
		if (adesk_js_admin.pg_template_edit != 1) {
			adesk_ui_anchor_set(template_list_anchor());
			alert(template_form_str_cant_update);
			return;
		}

		adesk_ui_api_call(jsLoading);
		$("form_submit").className = "adesk_button_update";
		$("form_submit").value = jsUpdate;
		adesk_ajax_call_cb("awebdeskapi.php", "template.template_select_row", template_form_load_cb, id);
	} else {
		if (adesk_js_admin.pg_template_add != 1) {
			adesk_ui_anchor_set(template_list_anchor());
			alert(template_form_str_cant_insert);
			return;
		}

		// get custom fields for the preselect value for add
		customFieldsObj.fetch(0);

		$("form_submit").className = "adesk_button_add";
		$("form_submit").value = jsAdd;
		$("form").className = "adesk_block";
	}
}

function template_form_load_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if ( !ary.id ) {
		adesk_error_show(template_form_str_cant_find);
		adesk_ui_anchor_set(template_list_anchor());
		return;
	}
	template_form_id = ary.id;
	$("form_id").value = ary.id;
	$('nameField').value = ary.name;
	$('subjectField').value = ary.subject;

	if (ary.preview_image) {
		template_preview_display(ary.id);
	}
	else {
		template_preview_upload_reset();
	}

	var list_inputs = $('parentsList_div').getElementsByTagName('input');

	// if NOT global, uncheck all lists, then check the ones that are saved
	if (ary.listslist != 0) {
		// uncheck all lists first
		for (var i = 0; i < list_inputs.length; i++) {
			list_inputs[i].checked = false;
		}
		var lists = (ary.listslist + '').split('-');
		for (var i = 0; i < lists.length; i++) {
			if ( $('p_' + lists[i]) ) $('p_' + lists[i]).checked = true;
		}

		if ( $('template_scope_specific') ) $('template_scope_specific').checked = true;
	}
	else {
		// check all lists
		for (var i = 0; i < list_inputs.length; i++) {
			list_inputs[i].checked = true;
		}

		// show/hide related sections
		if ( $('template_scope_all') ) $('template_scope_all').checked = true;
		$('template_form_lists').hide();
	}

	ary.html = ary.content;

	$("form").className = "adesk_block";

	form_editor_update('template', ary);
	form_editor_update_fields('conditionalfield', ary, '');
}

function template_form_save(id) {
	var post = adesk_form_post($("form"));
	adesk_ui_api_call(jsSaving);

	if ($("nameField").value == '') {
		alert(template_form_str_noname);
		return;
	}

	if (id > 0)
		adesk_ajax_post_cb("awebdeskapi.php", "template.template_update_post", template_form_save_cb, post);
	else
		adesk_ajax_post_cb("awebdeskapi.php", "template.template_insert_post", template_form_save_cb, post);
}

function template_form_save_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(template_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}
}

function template_form_lists_toggle_scope(value) {
	if (value == 'all') {
		$('template_form_lists').hide();
	}
	else {
		$('template_form_lists').show();
	}
}

function template_preview_display(id, filename, location) {
	$('template_preview_upload_div').hide();
	$('template_preview_image_div').show();
	if (id) {
		// loading the template edit page, and displaying a previously saved image
		$('template_preview_image').src = adesk_js_site['p_link'] + '/manage/preview_message.php?which=tpl&id=' + id;
		$('template_preview_image').show();
	}
	else {
		// uploading a new image, and displaying the image inline, as a preview prior to saving the template form
		if (ishosted)
			$('template_preview_image').src = adesk_js_site['p_link'] + '/manage/preview_template_file.php?name=' + filename;
		else
			$('template_preview_image').src = location + filename;

		$('template_preview_image').show();
	}
	$('template_preview_text1').hide();
	$('template_preview_text2').show();
	$('template_preview_upload_extra').show();
}

function template_preview_upload_reset() {
	$('template_preview_cache_filename').value = '';
	$('template_preview_cache_filename_mimetype').value = '';
	$('template_preview_image_div').hide();
	$('template_preview_iframe').className = ''; // the iframe (ac global file) still has class='adesk_hidden' on it
	$('template_preview_iframe').style.height = '40px';
	$('template_preview_image').src = '';
	$('template_preview_image').hide();
	adesk_dom_remove_children( $('template_preview_list') );
	$('template_preview_upload_extra').hide();
	$('template_preview_upload_div').show();
	$('template_preview_text1').show();
	$('template_preview_text2').hide();
}

{/literal}
