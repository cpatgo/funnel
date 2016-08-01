{literal}
function emailaccount_search_defaults() {
	$("search_content").value = '';

	var boxes = $("search").getElementsByTagName("input");

	for (var i = 0; i < boxes.length; i++) {
		if (boxes[i].type == "checkbox")
			boxes[i].checked = true;
	}

	if ( emailaccount_listfilter && typeof(emailaccount_listfilter) == 'object' ) {
		adesk_form_select_multiple($('JSListFilter'), emailaccount_listfilter);
	} else if ( emailaccount_listfilter > 0 ) {
		$('JSListFilter').value = emailaccount_listfilter;
	} else {
		adesk_form_select_multiple_all($('JSListFilter'));
	}
}

function emailaccount_search_check() {
	adesk_dom_display_block("search");
}

function emailaccount_search() {
	var post = adesk_form_post($("search"));

	$("list_search").value = post.content;
	emailaccount_listfilter = post.listid;

	adesk_ajax_post_cb("awebdeskapi.php", "emailaccount.emailaccount_filter_post", emailaccount_search_cb, post);
}

function emailaccount_search_cb(xml) {
	adesk_dom_toggle_display("search", "block");
	emailaccount_list_search_cb(xml);
}
{/literal}
