{literal}
function campaign_search_defaults() {
	$("search_content").value = '';

	var boxes = $("search").getElementsByTagName("input");

	for (var i = 0; i < boxes.length; i++) {
		if (boxes[i].type == "checkbox")
			boxes[i].checked = true;
	}

	if ( campaign_listfilter && typeof(campaign_listfilter) == 'object' ) {
		adesk_form_select_multiple($('JSListFilter'), campaign_listfilter);
	} else if ( campaign_listfilter > 0 ) {
		$('JSListFilter').value = campaign_listfilter;
	} else {
		adesk_form_select_multiple_all($('JSListFilter'));
	}
	adesk_form_select_multiple_all($('JSTypeFilter'), true);
	adesk_form_select_multiple_all($('JSStatusFilter'), true);
}

function campaign_search_check() {
	adesk_dom_display_block("search");
}

function campaign_search() {
	var post = adesk_form_post($("search"));

	$("list_search").value = post.content;
	campaign_listfilter = post.listid;

	adesk_ajax_post_cb("awebdeskapi.php", "campaign.campaign_filter_post", campaign_search_cb, post);
}

function campaign_search_cb(xml) {
	adesk_dom_toggle_display("search", "block");
	campaign_list_search_cb(xml);
}
{/literal}
