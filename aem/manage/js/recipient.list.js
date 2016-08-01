{literal}
var recipient_table = new ACTable();
var recipient_list_sort = "0";
var recipient_list_offset = "0";
var recipient_list_filter = "0";
var recipient_list_sort_discerned = false;

recipient_table.setcol(0, function(row) {
	var view = Builder.node("a", { href: sprintf("desk.php?action=subscriber_view&id=%d", row.subscriberid) }, jsOptionView);

	var ary = [];

	ary.push(view);
	ary.push(" ");

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

recipient_table.setcol(1, function(row) {
	return row.email;
});

recipient_table.setcol(2, function(row) {
	return row.name;
});

recipient_table.setcol(3, function(row) {
	return row.sdate;
});

recipient_table.setcol(4, function(row) {
	return row.ip;
});

function recipient_list_anchor() {
	return sprintf("list-%s-%s-%s", recipient_list_sort, recipient_list_offset, recipient_list_filter);
}

function recipient_list_tabelize(rows, offset) {
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("list_table"));

		$("list_noresults").className = "adesk_block";
		$("loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		return;
	}
	$("list_noresults").className = "adesk_hidden";
	$("loadingBar").className = "adesk_hidden";
	//$("acSelectAllCheckbox").checked = false;
	//$("selectXPageAllBox").className = "adesk_hidden";
	adesk_paginator_tabelize(recipient_table, "list_table", rows, offset);
	$("loadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function recipient_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (recipient_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	recipient_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(recipient_list_anchor());
	$("loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, recipient_list_sort, recipient_list_offset, this.limit, recipient_list_filter, sendid);

	$("list").className = "adesk_block";
}

function recipient_list_clear() {
	recipient_list_sort = "0";
	recipient_list_offset = "0";
	recipient_list_filter = "0";
	$("list_search").value = "";
	recipient_search_defaults();
	adesk_ui_anchor_set(recipient_list_anchor());
}

function recipient_list_search() {
	var post = adesk_form_post($("list"));
	adesk_ajax_post_cb("awebdeskapi.php", "recipient.recipient_filter_post", recipient_list_search_cb, post);
}

function recipient_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	recipient_list_filter = ary.filterid;
	adesk_ui_anchor_set(recipient_list_anchor());
}

function recipient_list_chsort(newSortId) {
	var oldSortId = ( recipient_list_sort.match(/D$/) ? recipient_list_sort.substr(0, 2) : recipient_list_sort );
	var oldSortObj = $('list_sorter' + oldSortId);
	var sortObj = $('list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( recipient_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = recipient_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = recipient_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old recipient_list_sort
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	recipient_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(recipient_list_anchor());
	return false;
}

function recipient_list_discern_sortclass() {
	if (recipient_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", recipient_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (recipient_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	recipient_list_sort_discerned = true;
}

{/literal}
