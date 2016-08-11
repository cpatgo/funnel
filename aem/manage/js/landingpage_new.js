{literal}

function landingpage_form_save() {
	var post = adesk_form_post($("form"));
	console.log(post);
	adesk_ajax_post_cb("awebdeskapi.php", "landingpage.landingpage_insert_post", landingpage_form_save_cb, post);
}

function landingpage_form_save_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	console.log(ary);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		requestedtab = null;
		list_form_id = -1;
		if(xml.localName=="landingpage_insert_post") {
			adesk_dom_display_block('added');
		}
		adesk_ui_anchor_set(list_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}
}

{/literal}