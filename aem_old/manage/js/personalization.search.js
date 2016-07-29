{literal}
function personalization_search_defaults() {
	$("search_content").value = '';

	var boxes = $("search").getElementsByTagName("input");

	for (var i = 0; i < boxes.length; i++) {
		if (boxes[i].type == "checkbox")
			boxes[i].checked = true;
	}

	if ( personalization_listfilter && typeof(personalization_listfilter) == 'object' ) {
		adesk_form_select_multiple($('JSListFilter'), personalization_listfilter);
	} else if ( personalization_listfilter > 0 ) {
		$('JSListFilter').value = personalization_listfilter;
	} else {
		adesk_form_select_multiple_all($('JSListFilter'));
	}
}

function personalization_search_check() {
	adesk_dom_display_block("search");
}

function personalization_search() {
	var post = adesk_form_post($("search"));

	$("list_search").value = post.content;
	personalization_listfilter = post.listid;

	adesk_ajax_post_cb("awebdeskapi.php", "personalization.personalization_filter_post", personalization_search_cb, post);
}

function personalization_search_cb(xml) {
	adesk_dom_toggle_display("search", "block");
	personalization_list_search_cb(xml);
}
{/literal}
