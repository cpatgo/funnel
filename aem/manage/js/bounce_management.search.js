{literal}
function bounce_management_search_defaults() {
	$("search_content").value = '';

	var boxes = $("search").getElementsByTagName("input");

	for (var i = 0; i < boxes.length; i++) {
		if (boxes[i].type == "checkbox")
			boxes[i].checked = true;
	}

	if ( bounce_management_listfilter && typeof(bounce_management_listfilter) == 'object' ) {
		adesk_form_select_multiple($('JSListFilter'), bounce_management_listfilter);
	} else if ( bounce_management_listfilter > 0 ) {
		$('JSListFilter').value = bounce_management_listfilter;
	} else {
		adesk_form_select_multiple_all($('JSListFilter'));
	}
}

function bounce_management_search_check() {
	adesk_dom_display_block("search");
}

function bounce_management_search() {
	var post = adesk_form_post($("search"));

	$("list_search").value = post.content;
	bounce_management_listfilter = post.listid;

	adesk_ajax_post_cb("awebdeskapi.php", "bounce_management.bounce_management_filter_post", bounce_management_search_cb, post);
}

function bounce_management_search_cb(xml) {
	adesk_dom_toggle_display("search", "block");
	bounce_management_list_search_cb(xml);
}
{/literal}
