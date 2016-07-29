{literal}
function optinoptout_search_defaults() {
	$("search_content").value = '';

	var boxes = $("search").getElementsByTagName("input");

	for (var i = 0; i < boxes.length; i++) {
		if (boxes[i].type == "checkbox")
			boxes[i].checked = true;
	}

	if ( optinoptout_listfilter && typeof(optinoptout_listfilter) == 'object' ) {
		adesk_form_select_multiple($('JSListFilter'), optinoptout_listfilter);
	} else if ( optinoptout_listfilter > 0 ) {
		$('JSListFilter').value = optinoptout_listfilter;
	} else {
		adesk_form_select_multiple_all($('JSListFilter'));
	}
}

function optinoptout_search_check() {
	adesk_dom_display_block("search");
}

function optinoptout_search() {
	var post = adesk_form_post($("search"));

	$("list_search").value = post.content;
	optinoptout_listfilter = post.listid;

	adesk_ajax_post_cb("awebdeskapi.php", "optinoptout.optinoptout_filter_post", optinoptout_search_cb, post);
}

function optinoptout_search_cb(xml) {
	adesk_dom_toggle_display("search", "block");
	optinoptout_list_search_cb(xml);
}
{/literal}
