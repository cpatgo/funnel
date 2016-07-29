<?php /* Smarty version 2.6.12, created on 2016-07-08 14:21:03
         compiled from design.search.js */ ?>
<?php echo '
function design_search_defaults() {
	$("search_content").value = \'\';

	var boxes = $("search").getElementsByTagName("input");

	for (var i = 0; i < boxes.length; i++) {
		if (boxes[i].type == "checkbox")
			boxes[i].checked = true;
	}
}

function design_search_check() {
	adesk_dom_display_block("search");
}

function design_search() {
	var post = adesk_form_post($("search"));

	$("list_search").value = post.content;

	adesk_ajax_post_cb("awebdeskapi.php", "design.design_filter_post", design_search_cb, post);
}

function design_search_cb(xml) {
	adesk_dom_toggle_display("search", "block");
	design_list_search_cb(xml);
}
'; ?>
