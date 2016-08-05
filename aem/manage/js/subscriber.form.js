var subscriber_form_str_cant_insert = '{"You do not have permission to add subscribers"|alang|js}';
var subscriber_form_str_cant_update = '{"You do not have permission to edit subscribers"|alang|js}';
var subscriber_form_str_cant_find   = '{"Subscriber not found."|alang|js}';
var subscriber_form_str_bad_email   = '{"Email is either not entered or invalid."|alang|js}';
var subscriber_form_str_lastsend_no =
	'{"Last campaign was not sent to subscriber."|alang|js}\n\n' +
	'{"Probable cause:"|alang|js}\n' +
	'{"Previous campaigns were sent to a list segment that this subscriber does not belong to."|alang|js}'
;

{literal}

var customFieldsObj = new ACCustomFields({
	sourceType: 'CHECKBOX',
	sourceId: 'listField',
	sourceName: 'p[]',
	api: 'list.list_field_update',
	responseIndex: 'fields',
	includeGlobals: 1
});
customFieldsObj.showhidden = true;
customFieldsObj.addHandler('custom_fields_table', 'display');

var subscriber_form_id = 0;

function subscriber_form_defaults() {
	$("form_id").value = 0;
	$("form_view").className = 'adesk_hidden';
	$("statusadvanced").hide();
	$("emailField").value = '';
	$("firstnameField").value = '';
	$("lastnameField").value = '';
	$("norespondersField").checked = false;
	$("sendoptinField").checked = true;
	$("instantrespondersField").checked = false;
	$("lastmessageField").checked = false;
	// set ALL checkboxes to unchecked
	//adesk_dom_boxclear("listField");
}

function subscriber_form_load(id) {
	subscriber_form_defaults();
	subscriber_form_id = id;

	if (!subscriber_canadd) {
		adesk_ui_anchor_set(subscriber_list_anchor());
		alert(subscriber_form_str_cant_insert);
		return;
	}

	$("statusField").value = 1;
	subscriber_form_setstatus(1);

	//$$(".listField").each(function(e) { e.checked = false; });
	customFieldsObj.fetch(0);
	//update_custom_fields_checkbox(0);
	$("form_submit").className = "adesk_button_add glc_button glc_btn_large";
	$("form_submit").value = jsAdd;
	$("form").className = "adesk_block";
	$("emailField").focus();
}

function subscriber_form_save(id) {
	var post = adesk_form_post($("form"));

	if ( !adesk_str_email(post.email) ) {
		alert(subscriber_form_str_bad_email);
		$('emailField').focus();
		return;
	}

	adesk_ui_api_call(jsSaving, 30);
	adesk_ajax_post_cb("awebdeskapi.php", "subscriber.subscriber_insert_post_web", subscriber_form_save_cb, post);
}

function subscriber_form_save_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		if ( ary.sendlast_should == 1 && ary.sendlast_did == 0 ) {
			alert(subscriber_form_str_lastsend_no);
		}
		adesk_ui_anchor_set(subscriber_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}
}

function subscriber_form_setstatus(status) {
	$("liststatus0Stuff").hide();
	$("liststatus1Stuff").hide();
	for ( var i = 0; i <= 5; i++ ) { // maximum 5 statuses
		var rel = $('liststatus' + i + 'Stuff');
		if ( rel ) {
			adesk_dom_showif($(rel), status == i);
		}
	}
}

function subscriber_form_list(selector) {
	//$('parentList' + selector.value).className = ( selector.checked ? 'adesk_list_selector_item' : 'adesk_hidden' );
	customFieldsObj.fetch(subscriber_form_id);
	//update_custom_fields_checkbox(subscriber_form_id);
}

function subscriber_form_list_all(isChecked) {
	if (isChecked) {
		$$(".listField").each(function(e) { e.checked = true; });
	} else {
		adesk_dom_boxclear("listField");
	}

	customFieldsObj.fetch(0);
}

{/literal}
