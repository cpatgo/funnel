<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:45
         compiled from group.search.js */ ?>
<?php echo '
function group_search_defaults() {
	$("search_content").value = \'\';

	var boxes = $("search").getElementsByTagName("input");

	for (var i = 0; i < boxes.length; i++) {
		if (boxes[i].type == "checkbox")
			boxes[i].checked = true;
	}
}

function group_search_check() {
	adesk_dom_display_block("search");
}

function group_search() {
	var post = adesk_form_post($("search"));

	$("list_search").value = post.content;

	adesk_ajax_post_cb("awebdeskapi.php", "group!adesk_group_filter_post", group_search_cb, post);
}

function group_search_cb(xml) {
	adesk_dom_toggle_display("search", "block");
	group_list_search_cb(xml);
}
'; ?>
