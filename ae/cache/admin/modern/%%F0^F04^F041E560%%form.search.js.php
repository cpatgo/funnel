<?php /* Smarty version 2.6.12, created on 2016-07-08 17:09:18
         compiled from form.search.js */ ?>
<?php echo '
function form_search_defaults() {
	$("search_content").value = \'\';

	var boxes = $("search").getElementsByTagName("input");

	for (var i = 0; i < boxes.length; i++) {
		if (boxes[i].type == "checkbox")
			boxes[i].checked = true;
	}

	if ( form_listfilter && typeof(form_listfilter) == \'object\' ) {
		adesk_form_select_multiple($(\'JSListFilter\'), form_listfilter);
	} else if ( form_listfilter > 0 ) {
		$(\'JSListFilter\').value = form_listfilter;
	} else {
		adesk_form_select_multiple_all($(\'JSListFilter\'));
	}
}

function form_search_check() {
	adesk_dom_display_block("search");
}

function form_search() {
	var post = adesk_form_post($("search"));

	$("list_search").value = post.content;
	form_listfilter = post.listid;

	adesk_ajax_post_cb("awebdeskapi.php", "form.form_filter_post", form_search_cb, post);
}

function form_search_cb(xml) {
	adesk_dom_toggle_display("search", "block");
	form_list_search_cb(xml);
}
'; ?>
