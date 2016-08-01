{literal}
function subscriber_action_search_defaults() {
	$("search_content").value = '';

	var boxes = $("search").getElementsByTagName("input");

	for (var i = 0; i < boxes.length; i++) {
		if (boxes[i].type == "checkbox")
			boxes[i].checked = true;
	}

	if ( subscriber_action_listfilter && typeof(subscriber_action_listfilter) == 'object' ) {
		adesk_form_select_multiple($('JSListFilter'), subscriber_action_listfilter);
	} else if ( subscriber_action_listfilter > 0 ) {
		$('JSListFilter').value = subscriber_action_listfilter;
	} else {
		adesk_form_select_multiple_all($('JSListFilter'));
	}
}

function subscriber_action_search_check() {
	adesk_dom_display_block("search");
}

function subscriber_action_search() {
	var post = adesk_form_post($("search"));

	$("list_search").value = post.content;
	subscriber_action_listfilter = post.listid;

	adesk_ajax_post_cb("awebdeskapi.php", "subscriber_action.subscriber_action_filter_post", subscriber_action_search_cb, post);
}

function subscriber_action_search_cb(xml) {
	adesk_dom_toggle_display("search", "block");
	subscriber_action_list_search_cb(xml);
}
{/literal}
