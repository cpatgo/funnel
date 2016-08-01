// api.js


function adesk_api_call(action, callback, msg, vars) {
	adesk_ui_api_call(msg);
	if ( typeof callback != 'function' ) callback = null;
	adesk_ajax_call_cb('awebdeskapi.php', action, callback, vars);
}

function adesk_api_post(action, callback, msg, vars) {
	adesk_ui_api_call(msg);
	if ( typeof callback != 'function' ) callback = null;
	adesk_ajax_post_cb('awebdeskapi.php', action, callback, vars);
}

function adesk_api_handle(xml, txt, func) {
	var ary = adesk_dom_read_node(xml, null);
	adesk_ui_api_callback();
	if ( typeof ary.error != 'undefined' ) {
		// internal error?
	}
	if ( typeof func == 'function' ) func(ary);
}

function adesk_api_result(xml) {
	var ary = adesk_dom_read_node(xml, null);
	adesk_ui_api_callback();
	if ( ary.succeeded && ary.succeeded == 1 ) {
		adesk_result_show(ary.message);
	} else {
		adesk_error_show(ary.message);
	}
}
