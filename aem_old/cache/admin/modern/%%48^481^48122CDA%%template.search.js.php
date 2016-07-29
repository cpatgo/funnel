<?php /* Smarty version 2.6.12, created on 2016-07-08 14:47:32
         compiled from template.search.js */ ?>
<?php echo '
function template_search_defaults() {
	$("search_content").value = \'\';

	var boxes = $("search").getElementsByTagName("input");

	for (var i = 0; i < boxes.length; i++) {
		if (boxes[i].type == "checkbox")
			boxes[i].checked = true;
	}

	if ( template_listfilter && typeof(template_listfilter) == \'object\' ) {
		adesk_form_select_multiple($(\'JSListFilter\'), template_listfilter);
	} else if ( template_listfilter > 0 ) {
		$(\'JSListFilter\').value = template_listfilter;
	} else {
		adesk_form_select_multiple_all($(\'JSListFilter\'));
	}
}

function template_search_check() {
	adesk_dom_display_block("search");
}

function template_search() {
	var post = adesk_form_post($("search"));

	$("list_search").value = post.content;
	template_listfilter = post.listid;

	adesk_ajax_post_cb("awebdeskapi.php", "template.template_filter_post", template_search_cb, post);
}

function template_search_cb(xml) {
	adesk_dom_toggle_display("search", "block");
	template_list_search_cb(xml);
}
'; ?>
