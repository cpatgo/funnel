<?php /* Smarty version 2.6.12, created on 2016-07-18 12:02:44
         compiled from header.search.js */ ?>
<?php echo '
function header_search_defaults() {
	$("search_content").value = \'\';

	var boxes = $("search").getElementsByTagName("input");

	for (var i = 0; i < boxes.length; i++) {
		if (boxes[i].type == "checkbox")
			boxes[i].checked = true;
	}

	if ( header_listfilter && typeof(header_listfilter) == \'object\' ) {
		adesk_form_select_multiple($(\'JSListFilter\'), header_listfilter);
	} else if ( header_listfilter > 0 ) {
		$(\'JSListFilter\').value = header_listfilter;
	} else {
		adesk_form_select_multiple_all($(\'JSListFilter\'));
	}
}

function header_search_check() {
	adesk_dom_display_block("search");
}

function header_search() {
	var post = adesk_form_post($("search"));

	$("list_search").value = post.content;
	header_listfilter = post.listid;

	adesk_ajax_post_cb("awebdeskapi.php", "header.header_filter_post", header_search_cb, post);
}

function header_search_cb(xml) {
	adesk_dom_toggle_display("search", "block");
	header_list_search_cb(xml);
}
'; ?>
