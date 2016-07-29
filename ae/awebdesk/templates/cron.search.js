{literal}
function cron_search_defaults() {
	$("search_content").value = '';

	var boxes = $("search").getElementsByTagName("input");

	for (var i = 0; i < boxes.length; i++) {
		if (boxes[i].type == "checkbox")
			boxes[i].checked = true;
	}
}

function cron_search_check() {
	adesk_dom_display_block("search");
}

function cron_search() {
	var post = adesk_form_post($("search"));

	$("list_search").value = post.content;

	adesk_ajax_post_cb("awebdeskapi.php", "cron!adesk_cron_filter_post", cron_search_cb, post);
}

function cron_search_cb(xml) {
	adesk_dom_toggle_display("search", "block");
	cron_list_search_cb(xml);
}
{/literal}
