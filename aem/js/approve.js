// approve.js

{jsvar name=approval var=$approval}

{literal}

function approval_approve() {
	if ( !confirm(jsAreYouSure) ) return;
	adesk_ui_api_call(jsApproving);
	adesk_ajax_call_cb("awebdeskapi.php", "approval.approval_approve", approval_approve_cb, approval.id, approval.hash);
}

function approval_approve_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded == 1) {
		adesk_result_show(ary.message);
		adesk_dom_toggle_class('infobox', 'adesk_block', 'adesk_hidden');
		adesk_dom_toggle_class('approvedbox', 'adesk_block', 'adesk_hidden');
		//window.setTimeout(function() { window.close(); }, 1000);
	} else {
		adesk_error_show(ary.message);
	}
}

function approval_decline_toggle() {
	adesk_dom_toggle_class('declinebox', 'adesk_block', 'adesk_hidden');
	adesk_dom_toggle_class('infobox', 'adesk_block', 'adesk_hidden');
}

function approval_decline() {
	if ( !confirm(jsAreYouSure) ) return;
	var post = adesk_form_post($('declinebox'));
	post.id = approval.id;
	post.hash = approval.hash;

	adesk_ui_api_call(jsLoading);
	adesk_ajax_post_cb("awebdeskapi.php", "approval.approval_decline", approval_decline_cb, post);
}

function approval_decline_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded == 1) {
		adesk_result_show(ary.message);
		adesk_dom_toggle_class('declinebox', 'adesk_block', 'adesk_hidden');
		adesk_dom_toggle_class('declinedbox', 'adesk_block', 'adesk_hidden');
		//window.setTimeout(function() { window.close(); }, 1000);
	} else {
		adesk_error_show(ary.message);
	}
}

{/literal}
