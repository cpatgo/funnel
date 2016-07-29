var form_form_str_cant_insert = '{"You do not have permission to add Subscription Forms"|alang|js}';
var form_form_str_cant_update = '{"You do not have permission to edit Subscription Forms"|alang|js}';
var form_form_str_cant_find   = '{"Subscription Form not found."|alang|js}';
var form_form_custommessage_sub1 = '{"You have successfully been subscribed to this list."|alang|js}';
var form_form_custommessage_sub2 = '{"Your subscription is awaiting confirmation. Please check your email to confirm."|alang|js}';
var form_form_custommessage_sub3 = '{"Your subscription has been confirmed."|alang|js}';
var form_form_custommessage_sub4 = '{"An error occurred while trying to subscribe:"|alang|js}';
var form_form_custommessage_unsub1 = '{"You have successfully been unsubscribed to this list."|alang|js}';
var form_form_custommessage_unsub2 = '{"Your unsubscription is awaiting confirmation. Please check your email to confirm."|alang|js}';
var form_form_custommessage_unsub3 = '{"Your unsubscription has been confirmed."|alang|js}';
var form_form_custommessage_unsub4 = '{"An error occurred while trying to unsubscribe:"|alang|js}';
var form_form_custommessage_up1 = '{"Your request to update subscription details has been processed."|alang|js}';
var form_form_custommessage_up2 = '{"Your subscription details have been updated."|alang|js}';
var form_form_add = '{"Add & Continue"|alang|js}';
var form_form_edit = '{"Update & Continue"|alang|js}';

{jsvar name=fields var=$fields}

{literal}

var lists = [];
for ( var i in adesk_js_admin.lists ) {
	var l = adesk_js_admin.lists[i];
	if ( typeof l != 'function' ) {
		lists.push(l);
	}
}

var customFieldsObj = new ACCustomFields({
	sourceType: 'SELECT',
	sourceId: 'parentsList',
	api: 'form.form_list_change',
	responseIndex: 'fields',
	includeGlobals: 0,
	additionalHandler: function(ary) {

		var b = browser_ident();

		form_lists_change(ary.lists);

		/*
		// reset editors after adding the new contents
		if (b != "Explorer 7" && b != "Explorer 8") {
			adesk_editor_toggle('optinEditor', adesk_editor_init_word_object);
			adesk_editor_toggle('optoutEditor', adesk_editor_init_word_object);
			adesk_editor_toggle('sub1Editor', adesk_editor_init_normal_object);
			adesk_editor_toggle('sub2Editor', adesk_editor_init_normal_object);
			adesk_editor_toggle('sub3Editor', adesk_editor_init_normal_object);
			adesk_editor_toggle('sub4Editor', adesk_editor_init_normal_object);
			adesk_editor_toggle('unsub1Editor', adesk_editor_init_normal_object);
			adesk_editor_toggle('unsub2Editor', adesk_editor_init_normal_object);
			adesk_editor_toggle('unsub3Editor', adesk_editor_init_normal_object);
			adesk_editor_toggle('unsub4Editor', adesk_editor_init_normal_object);
			adesk_editor_toggle('up1Editor', adesk_editor_init_normal_object);
			adesk_editor_toggle('up2Editor', adesk_editor_init_normal_object);
		}
		*/

		for ( var i in fields ) {
			var f = fields[i];
			if ( typeof f != 'function' ) {
				// get the input
				var rel = $('custom' + f.id + 'Field');
				rel.checked = adesk_array_has(this.selection, f.id);
			}
		}
	}
});

// Load custom fields onto the page
customFieldsObj.addHandler('custom_fields_table', 'list');

var form_form_id = 0;

function form_form_defaults() {

	$("form_id").value = 0;
	$('nameField').value = '';
	$('type').value = 'both';
	$('allowselFieldNo').checked = true;
	$('ask4fnameField').checked = true;
	$('ask4lnameField').checked = true;
	$('captchaField').checked = true;

	// lists
	var list_inputs = $('parentsList_div').getElementsByTagName('input');
	if ( form_listfilter && typeof(form_listfilter) == 'object' ) {
		for (var i in form_listfilter) {
			if ( $('p_' + form_listfilter[i]) ) $('p_' + form_listfilter[i]).checked = true;
		}
	} else if ( form_listfilter > 0 ) {
		if ( $('p_' + form_listfilter) ) $('p_' + form_listfilter).checked = true;
	} else {
		// check the first input
		list_inputs[0].checked = true;
		// uncheck the rest of them
		for (var i = 1; i < list_inputs.length; i++) {
			list_inputs[i].checked = false;
		}
	}

	// Fields to request
	$("custom_fields_trs_hr").className = "";
	$("custom_fields_trs").className = "";
	$("custom_fields_table").className = "";
	$("ask4fname_tr").className = "";
	$("ask4lname_tr").className = "";

	// Hide the "List Options" and "Opt-in/Out Confirmation" tr's
	$("list_options_tr").className = "adesk_hidden";
	$("opt_confirmation_tr").className = "adesk_hidden";

	// optinout
	//optinoptout_defaults();
	//form_editor_personalization('conditionalfield', [ 'subscriber' ], 'text', '');
	$("emailconfirmationsEach").checked = true;
	// Div below "Send a single email confirmation for all lists"
	$("optinoutchoose").className = "form_confirmation adesk_hidden";

	// Form Completion Options
	var form_completion_options_array = new Array("sub1","sub2","sub3","sub4","unsub1","unsub2","unsub3","unsub4","up1","up2");

	var form_completion_custommessage_default = new Array(
		form_form_custommessage_sub1,
		form_form_custommessage_sub2,
		form_form_custommessage_sub3,
		form_form_custommessage_sub4 + "<br />%MESSAGE%",
		form_form_custommessage_unsub1,
		form_form_custommessage_unsub2,
		form_form_custommessage_unsub3,
		form_form_custommessage_unsub4 + "<br />%MESSAGE%",
		form_form_custommessage_up1,
		form_form_custommessage_up2
	);

	for (var i = 0; i < form_completion_options_array.length; i++) {

		// Select list values
		$(form_completion_options_array[i]).value = "default";

		// textarea editors for "custom"
		$(form_completion_options_array[i] + "EditorDiv").className = "adesk_hidden";
		//adesk_form_value_set($(form_completion_options_array[i] + "Editor"), "");
		adesk_form_value_set($(form_completion_options_array[i] + "Editor"), form_completion_custommessage_default[i]);

		// textbox's for redirect URL's
		$(form_completion_options_array[i] + "_redirect").className = "adesk_hidden";
		$(form_completion_options_array[i] + "_redirect").value = "http://";
	}

	// set the stage
	$('formlistpanel_div').className = '';
	$('formlistpanel').className = 'h2_content';
	$('formredirpanel').className = 'h2_content_invis';
}

function form_form_load(id) {
	form_form_defaults();
	form_form_id = id;

	if (id > 0) {
		if (adesk_js_admin.pg_form_edit != 1) {
			adesk_ui_anchor_set(form_list_anchor());
			alert(form_form_str_cant_update);
			return;
		}

		adesk_ui_api_call(jsLoading);
		$("form_submit").className = "adesk_button_update";
		$("form_submit").value = form_form_edit;
		adesk_ajax_call_cb("awebdeskapi.php", "form.form_select_row", form_form_load_cb, id);
	} else {
		if (adesk_js_admin.pg_form_add != 1) {
			adesk_ui_anchor_set(form_list_anchor());
			alert(form_form_str_cant_insert);
			return;
		}

		//update_custom_fields_list_preselect = null;
		//update_custom_fields(form_form_id, true);
		customFieldsObj.selection = [];
		customFieldsObj.fetch(0);

		$("form_submit").className = "adesk_button_add";
		$("form_submit").value = form_form_add;
		$("form").className = "adesk_block";
		$('nameField').focus();
	}
}

function form_form_load_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if ( !ary.id ) {
		adesk_error_show(form_form_str_cant_find);
		adesk_ui_anchor_set(form_list_anchor());
		return;
	}
	form_form_id = ary.id;

	$("form_id").value = ary.id;
	$('nameField').value = ary.name;

	if (form_form_id == 1000) {
		$('formlistpanel_div').className = 'adesk_hidden';
		$('formredirpanel').className = 'h2_content';

		customFieldsObj.selection = [];
		customFieldsObj.fetch(0);
	}
	else {

		$('formlistpanel_div').className = '';
		$('formredirpanel').className = 'h2_content_invis';

		$('type').value = ary.type;
		$('optinoutidField').value = ary.optinoptout;

		// Show or hide custom fields based on "type"
		form_options_type_change(ary.type);

		$('allowselFieldYes').checked = ( ary.allowselection == 1 );
		$('allowselFieldNo').checked = ( ary.allowselection == 0 );
		$('emailconfirmationsEach').checked = ( ary.emailconfirmations == 1 );
		$('emailconfirmationsAll').checked = ( ary.emailconfirmations == 0 );

		if (ary.emailconfirmations == 0) {
			// Div below "Send a single email confirmation for all lists"
			$("optinoutchoose").className = "form_confirmation";
		}
		else {
			// Div below "Send a single email confirmation for all lists"
			$("optinoutchoose").className = "form_confirmation adesk_hidden";
		}

		$('ask4fnameField').checked = ( ary.ask4fname == 1 );
		$('ask4lnameField').checked = ( ary.ask4lname == 1 );
		$('captchaField').checked = ( ary.captcha == 1 );

		var list_inputs = $('parentsList_div').getElementsByTagName('input');
		// uncheck all lists first
		for (var i = 0; i < list_inputs.length; i++) {
			list_inputs[i].checked = false;
		}

		// now check the ones pertaining to this form
		var lists = (ary.listslist + '').split('-');
		for (var i = 0; i < lists.length; i++) {
			if ( $('p_' + lists[i]) ) $('p_' + lists[i]).checked = true;
		}

		// Convert to a string in case there are no commas, it would treat the single number as integer
		var listslist = ary.listslist + '';

		// If a comma is present
		if (listslist.indexOf(',') != -1) {
			if (listslist.split('-').length > 1) {
				// SHOW the "List Options" and "Opt-in/Out Confirmation" tr's
				$("list_options_tr").className = "";
				$("opt_confirmation_tr").className = "";
			}
			else {
				// HIDE the "List Options" and "Opt-in/Out Confirmation" tr's
				$("list_options_tr").className = "adesk_hidden";
				$("opt_confirmation_tr").className = "adesk_hidden";
			}
		}


		//customFieldsObj.selection = ( ary.listslist + '' ).split(',');
		customFieldsObj.selection = ( ary.fieldslist + '' ).split(',');
		customFieldsObj.fetch(0);
		//update_custom_fields_list_preselect = ( ary.fieldslist + '' ).split(',');
		//update_custom_fields(form_form_id, true);
	}
	$("form").className = "adesk_block";

	// Form Completion Options
	var form_completion_options_array = new Array("sub1","sub2","sub3","sub4","unsub1","unsub2","unsub3","unsub4","up1","up2");

	for (var i = 0; i < form_completion_options_array.length; i++) {

		// Select list values
		$(form_completion_options_array[i]).value = ary[form_completion_options_array[i] + "_type"];

		// Swap the appropriate textarea or textbox depending on _type value from database: default, custom, redirect
		form_completion_change(form_completion_options_array[i] + "_" + ary[form_completion_options_array[i] + "_type"]);

		// Decide which form element to populate with the content: the "custom" textarea, or the "redirect" textbox
		if (ary[form_completion_options_array[i] + "_type"] == "redirect") {

			// Populate the textbox for "redirect"
			$(form_completion_options_array[i] + "_redirect").value = ary[form_completion_options_array[i] + "_value"];

			// Clear out the textarea for "custom"
			adesk_form_value_set($(form_completion_options_array[i] + "Editor"), "");
		}
		else if (ary[form_completion_options_array[i] + "_type"] == "custom") {

			// Populate the textarea for "custom"
			adesk_form_value_set($(form_completion_options_array[i] + "Editor"), ary[form_completion_options_array[i] + "_value"]);

			// Clear out the textbox for "redirect"
			$(form_completion_options_array[i] + "_redirect").value = "";
		}
		else {

			// "default" is set, so clear out both the textbox and textarea
			$(form_completion_options_array[i] + "_redirect").value = "";
			//adesk_form_value_set($(form_completion_options_array[i] + "Editor"), "");
		}
	}
}

function form_form_save(id) {
	var post = adesk_form_post($("form"));
	adesk_ui_api_call(jsSaving);

	if (id > 0)
	adesk_ajax_post_cb("awebdeskapi.php", "form.form_update_post", form_form_save_cb, post);
	else
	adesk_ajax_post_cb("awebdeskapi.php", "form.form_insert_post", form_form_save_cb, post);
}

function form_form_save_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set('view-' + ary.id);
		//adesk_ui_anchor_set(form_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}
}

function form_options_type_change(value) {
	// Show or hide custom fields based on "type"
	if (value == "unsubscribe") {
		$("custom_fields_trs_hr").className = "adesk_hidden";
		$("custom_fields_trs").className = "adesk_hidden";
		$("custom_fields_table").className = "adesk_hidden";
		$("ask4fname_tr").className = "adesk_hidden";
		$("ask4lname_tr").className = "adesk_hidden";
		$("redirection_subscription").className = "adesk_hidden";
		$("redirection_unsubscription").className = "";
		$("redirection_other").className = "adesk_hidden";
	}
	else if (value == "subscribe") {
		$("redirection_subscription").className = "";
		$("redirection_unsubscription").className = "adesk_hidden";
		$("redirection_other").className = "adesk_hidden";
	}
	else {
		$("custom_fields_trs_hr").className = "";
		$("custom_fields_trs").className = "";
		$("custom_fields_table").className = "";
		$("ask4fname_tr").className = "";
		$("ask4lname_tr").className = "";
		$("redirection_subscription").className = "";
		$("redirection_unsubscription").className = "";
		$("redirection_other").className = "";
	}
}

function form_lists_change(lists) {

	// 'lists' param is the selected lists (form element) list array database result

	var list_inputs = $("parentsList_div").getElementsByTagName('input');

	// count how many lists are checked
	var selected_lists = 0;
	for (var i = 0; i < list_inputs.length; i++) {
		if ( list_inputs[i].checked ) {
			selected_lists++;
		}
	}

	//alert(selected_lists);

	// If there is only one (or zero) options selected
	if (selected_lists < 2) {
		$("list_options_tr").className = "adesk_hidden";
		$("opt_confirmation_tr").className = "adesk_hidden";

		// Select "Force user to subscribe to or unsubscribe from all lists selected above"
		$('allowselFieldNo').checked = true;
	}
	else {

		$("list_options_tr").className = "";

		var show_opt_confirmation_tr = false;

		for (var i = 0; i < lists.length; i++) {
			// If the "optin_confirm" field is equal to 1
			if (lists[i].optin_confirm == 1) {
				show_opt_confirmation_tr = true;
			}
		}

		// If at least one of the selected lists has optin_confirm = 1, show the Opt-In/Out form section
		if (show_opt_confirmation_tr == true) {
			$("opt_confirmation_tr").className = "";
		}
		else {
			$("opt_confirmation_tr").className = "adesk_hidden";
		}
	}
}

function form_opt_confirmation_change() {
	// If radio option "Send a single email confirmation for all lists" is checked, show the div below it
	if ($("emailconfirmationsAll").checked) {
		$("optinoutchoose").className = "form_confirmation";
	}
	else {
		$("optinoutchoose").className = "form_confirmation adesk_hidden";
	}
}

function form_completion_change(value) {
	var which_option = value.split("_");

	if (which_option[1] == "redirect") {
		// If they choose "Redirect to URL"
		$(which_option[0] + "EditorDiv").className = "adesk_hidden";
		$(which_option[0] + "_redirect").className = "";

		if ( $(which_option[0] + "_redirect").value == "" ) {
			$(which_option[0] + "_redirect").value = "http://";
		}
	}
	else if (which_option[1] == "custom") {
		// If they choose "Custom Message"
		$(which_option[0] + "EditorDiv").className = "";
		$(which_option[0] + "_redirect").className = "adesk_hidden";
	}
	else {
		// If they choose "Default Message"
		$(which_option[0] + "EditorDiv").className = "adesk_hidden";
		$(which_option[0] + "_redirect").className = "adesk_hidden";
	}
}

{/literal}
