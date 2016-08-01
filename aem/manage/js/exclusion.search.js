{literal}
function exclusion_search_defaults() {
	$("search_content").value = '';

	var boxes = $("search").getElementsByTagName("input");

	for (var i = 0; i < boxes.length; i++) {
		if (boxes[i].type == "checkbox")
			boxes[i].checked = true;
	}

	if ( exclusion_listfilter && typeof(exclusion_listfilter) == 'object' ) {
		adesk_form_select_multiple($('JSListFilter'), exclusion_listfilter);
	} else if ( exclusion_listfilter > 0 ) {
		$('JSListFilter').value = exclusion_listfilter;
	} else {
		adesk_form_select_multiple_all($('JSListFilter'));
	}
}

function exclusion_search_check() {
	adesk_dom_display_block("search");
}

function exclusion_search() {
	var post = adesk_form_post($("search"));

	$("list_search").value = post.content;
	exclusion_listfilter = post.listid;

	adesk_ajax_post_cb("awebdeskapi.php", "exclusion.exclusion_filter_post", exclusion_search_cb, post);
}

function exclusion_search_cb(xml) {
	adesk_dom_toggle_display("search", "block");
	exclusion_list_search_cb(xml);
}
{/literal}
