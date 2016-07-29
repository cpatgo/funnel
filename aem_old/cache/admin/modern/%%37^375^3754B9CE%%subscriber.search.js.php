<?php /* Smarty version 2.6.12, created on 2016-07-08 14:15:47
         compiled from subscriber.search.js */ ?>
<?php echo '
function subscriber_search_defaults() {
	$("search_content").value = \'\';

	var boxes = $("search").getElementsByTagName("input");

	for (var i = 0; i < boxes.length; i++) {
		if (boxes[i].type == "checkbox")
			boxes[i].checked = true;
	}

	if ( subscriber_listfilter && typeof(subscriber_listfilter) == \'object\' ) {
		adesk_form_select_multiple($(\'JSListFilter\'), subscriber_listfilter);
	} else if ( subscriber_listfilter > 0 ) {
		$(\'JSListFilter\').value = subscriber_listfilter;
	} else {
		adesk_form_select_multiple_all($(\'JSListFilter\'));
	}
	adesk_form_select_multiple_all($(\'JSStatusFilter\'), true);
}

function subscriber_search_check() {
	adesk_dom_display_block("search");
}

function subscriber_search() {
	var post = adesk_form_post($("search"));

	$("list_search").value = post.content;
	subscriber_listfilter = post.listid;

	adesk_ajax_post_cb("awebdeskapi.php", "subscriber.subscriber_filter_post", subscriber_search_cb, post);
}

function subscriber_search_cb(xml) {
	adesk_dom_toggle_display("search", "block");
	subscriber_list_search_cb(xml);
}
'; ?>
