{literal}
function filter_search_defaults() {
	$("search_content").value = '';

	var boxes = $("search").getElementsByTagName("input");

	for (var i = 0; i < boxes.length; i++) {
		if (boxes[i].type == "checkbox")
			boxes[i].checked = true;
	}
}

function filter_search_check() {
	adesk_dom_display_block("search");
}

function filter_search() {
	var post = adesk_form_post($("search"));

	$("list_search").value = post.content;

	adesk_ajax_post_cb("awebdeskapi.php", "filter.filter_filter_post", filter_search_cb, post);
}

function filter_search_cb(xml) {
	adesk_dom_toggle_display("search", "block");
	filter_list_search_cb(xml);
}
{/literal}
