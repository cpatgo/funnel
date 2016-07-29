<?php /* Smarty version 2.6.12, created on 2016-07-08 16:53:15
         compiled from list.search.js */ ?>
<?php echo '
function list_search_defaults() {
	$("search_content").value = \'\';

	var boxes = $("search").getElementsByTagName("input");

	for (var i = 0; i < boxes.length; i++) {
		if (boxes[i].type == "checkbox")
			boxes[i].checked = true;
	}
}

function list_search_check() {
	adesk_dom_display_block("search");
}

function list_search() {
	var post = adesk_form_post($("search"));

	$("list_search").value = post.content;

	adesk_ajax_post_cb("awebdeskapi.php", "list.list_filter_post", list_search_cb, post);
}

function list_search_cb(xml) {
	adesk_dom_toggle_display("search", "block");
	list_list_search_cb(xml);
}
'; ?>
