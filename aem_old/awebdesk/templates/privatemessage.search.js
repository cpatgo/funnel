{literal}
function privatemessage_search_defaults() {
	$("search_content").value = '';

	var boxes = $("search").getElementsByTagName("input");

	for (var i = 0; i < boxes.length; i++) {
		if (boxes[i].type == "checkbox")
			boxes[i].checked = true;
	}
}

function privatemessage_search_check() {
	adesk_dom_display_block("search");
}

function privatemessage_search() {
	var post = adesk_form_post($("search"));

	$("list_search").value = post.content;

	adesk_ajax_post_cb("awebdeskapi.php", "privatemessage!adesk_privatemessage_filter_post", privatemessage_search_cb, post);
}

function privatemessage_search_cb(xml) {
	adesk_dom_toggle_display("search", "block");
	privatemessage_list_search_cb(xml);
}
{/literal}
