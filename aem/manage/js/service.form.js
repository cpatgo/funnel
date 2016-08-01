var service_form_str_cant_insert = '{"You do not have permission to add External Services."|alang|js}';
var service_form_str_cant_update = '{"You do not have permission to edit External Services."|alang|js}';
var service_form_str_cant_find   = '{"External Service not found."|alang|js}';

{jsvar name=user_password var=$user_password}

{literal}
var service_form_id = 0;

function service_form_defaults() {
	$("form_id").value = 0;
	$("form_submit").show();
	$("service_facebook").hide();
	$("service_twitter").hide();
	$("service_unbounce").hide();
	$("service_facebook_id").value = "";
	$("service_facebook_secret").value = "";
	$("service_twitter_key").value = "";
	$("service_twitter_secret").value = "";
}

function service_form_load(id) {
	service_form_defaults();
	service_form_id = id;

	if (id > 0) {
		/*
		if (adesk_js_admin.__CAN_EDIT__ != 1) {
			adesk_ui_anchor_set(service_list_anchor());
			alert(service_form_str_cant_update);
			return;
		}
		*/

		// if they are trying to edit Facebook settings, and they are a hosted user, do not let them
		if (id == 1 && service_ishosted) {
			alert(service_edit_no);
			return;
		}

		adesk_ui_api_call(jsLoading);
		$("form_submit").className = "adesk_button_update";
		$("form_submit").value = jsUpdate;
		adesk_ajax_call_cb("awebdeskapi.php", "service.service_get", service_form_load_cb, id);
	} else {
		/*
		if (adesk_js_admin.__CAN_ADD__ != 1) {
			adesk_ui_anchor_set(service_list_anchor());
			alert(service_form_str_cant_insert);
			return;
		}
		*/

		// no adding allowed
		alert(service_form_str_cant_insert);
		return;

		$("form_submit").className = "adesk_button_add";
		$("form_submit").value = jsAdd;
		$("form").className = "adesk_block";
	}
}

function service_form_load_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if ( !ary.id ) {
		adesk_error_show(service_form_str_cant_find);
		adesk_ui_anchor_set(service_list_anchor());
		return;
	}
	service_form_id = ary.id;

	$("form_id").value = ary.id;
	$("nameField").innerHTML = ary.name;
	$("descriptionField").innerHTML = ary.description;

	$("service_facebook_id").value = ary.facebook_app_id;
	$("service_facebook_secret").value = ary.facebook_app_secret;
	$("service_twitter_key").value = ary.twitter_consumer_key;
	$("service_twitter_secret").value = ary.twitter_consumer_secret;

	if (service_form_id == 1) {
		$("service_facebook").show();
	}
	else if (service_form_id == 2) {
		$("service_twitter").show();
	}
	else if (service_form_id == 3) {
		$("service_unbounce").show();
		// generate URL display based on the list that is checked by default
		service_form_gen_url( service_form_lists_checked() );
		$('form_submit').hide();
	}

	$("form").className = "adesk_block";
}

function service_form_save(id) {
	var post = adesk_form_post($("form"));
	adesk_ui_api_call(jsSaving);

	if (id > 0)
		adesk_ajax_post_cb("awebdeskapi.php", "service.service_update_post", service_form_save_cb, post);
	else
		adesk_ajax_post_cb("awebdeskapi.php", "service.service_insert_post", service_form_save_cb, post);
}

function service_form_save_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded && ary.succeeded == "1") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(service_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}
}

function service_form_lists_checked() {
	var inputs = $('parentsList_div').getElementsByTagName('input');
	for (var i = 0; i < inputs.length; i++) {
		if (inputs[i].checked) return inputs[i].value;
	}	
}

// can only check one list at a time, so reset them all to unchecked besides the one checked
function service_form_reset_lists(listid) {
	var inputs = $('parentsList_div').getElementsByTagName('input');
	for (var i = 0; i < inputs.length; i++) {
		if (inputs[i].value != listid) inputs[i].checked = false;
	}
}

// generate the URL based on the list they choose
function service_form_gen_url(listid) {
	$('service_form_url').value = adesk_js_site.p_link + '/manage/awebdeskapi.php?api_user=' + adesk_js_admin.username + '&api_pass_h=' + user_password + '&api_action=subscriber_add&api_output=serialize&listid=' + listid;
}

{/literal}
