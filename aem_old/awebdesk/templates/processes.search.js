{literal}
function processes_search_defaults() {
	$("search_content").value = '';

	var boxes = $("search").getElementsByTagName("input");

	for (var i = 0; i < boxes.length; i++) {
		if (boxes[i].type == "checkbox")
			boxes[i].checked = true;
	}

	// action filter
	if ( processes_actionfilter && typeof(processes_actionfilter) == 'object' ) {
		adesk_form_select_multiple($('JSActionFilter'), processes_actionfilter);
	} else if ( processes_actionfilter > 0 ) {
		$('JSActionFilter').value = processes_actionfilter;
	} else {
		adesk_form_select_multiple_all($('JSActionFilter'), true);
	}
	// status filter
	if ( processes_statusfilter && typeof(processes_statusfilter) == 'object' ) {
		adesk_form_select_multiple($('JSStatusFilter'), processes_statusfilter);
	} else if ( processes_statusfilter > 0 ) {
		$('JSStatusFilter').value = processes_statusfilter;
	} else {
		adesk_form_select_multiple_all($('JSStatusFilter'), true);
	}
}

function processes_search_check() {
	adesk_dom_display_block("search");
}

function processes_search() {
	var post = adesk_form_post($("search"));

	$("list_search").value = post.content;

	adesk_ajax_post_cb("awebdeskapi.php", "processes!adesk_processes_filter_post", processes_search_cb, post);
}

function processes_search_cb(xml) {
	adesk_dom_toggle_display("search", "block");
	processes_list_search_cb(xml);
}
{/literal}
