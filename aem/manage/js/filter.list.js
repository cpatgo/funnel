var filter_table = new ACTable();
var filter_list_sort = "01";
var filter_list_offset = "0";
var filter_list_filter = {jsvar var=$filterid};
var filter_list_sort_discerned = false;
var filter_list_str_showsubs = '{"Show subscribers"|alang|js}';

{literal}

filter_table.setcol(0, function(row) {
	return Builder.node("input", { type: "checkbox", name: "multi[]", value: row.id, onclick: "adesk_form_check_selection_none(this, $('acSelectAllCheckbox'), $('selectXPageAllBox'))" });
});

filter_table.setcol(1, function(row) {
	var edit = Builder.node("a", { href: sprintf("#form-%d", row.id) }, jsOptionEdit);
	var dele = Builder.node("a", { href: sprintf("#delete-%d", row.id) }, jsOptionDelete);
	var show = Builder.node("a", { href: sprintf("desk.php?action=subscriber&filterid=%d", row.id) }, filter_list_str_showsubs);

	var ary = [];

	if (adesk_js_admin.pg_subscriber_filters) {
		ary.push(edit);
		ary.push(" ");
	}

	if (adesk_js_admin.pg_subscriber_filters) {
		ary.push(dele);
		ary.push(" ");
	}

	ary.push(show);

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

filter_table.setcol(2, function(row) {
	return row.name;
});

function filter_list_anchor() {
	return sprintf("list-%s-%s-%s", filter_list_sort, filter_list_offset, filter_list_filter);
}

function filter_list_tabelize(rows, offset) {
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("list_table"));

		$("list_noresults").className = "adesk_block";
		if ($("list_delete_button") !== null)
			$("list_delete_button").className = "adesk_hidden";
		$("loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		return;
	}
	$("list_noresults").className = "adesk_hidden_ie";
	if ($("list_delete_button") !== null)
		$("list_delete_button").className = "adesk_inline";
	adesk_paginator_tabelize(filter_table, "list_table", rows, offset);

	$("filter_list_count").innerHTML = " (" + adesk_number_format(paginators[1].total, decimalDelim, commaDelim) + ")";
	$("filter_list_count").className = "adesk_inline";
	if ( $('selectXPageAllBox') ) {
		var spans = $('selectXPageAllBox').getElementsByTagName('span');
		if ( spans.length > 2 ) {
			spans[2].innerHTML = adesk_number_format(paginators[1].total, decimalDelim, commaDelim);
		}
	}

	$("loadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function filter_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (filter_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	filter_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(filter_list_anchor());
	$("loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, filter_list_sort, filter_list_offset, this.limit, filter_list_filter);

	$("list").className = "adesk_block";
}

function filter_list_clear() {
	filter_list_sort = "01";
	filter_list_offset = "0";
	filter_list_filter = "0";
	$("list_search").value = "";
	$("JSListManager").value = 0;
	list_filters_update(0, 0, true);
	filter_search_defaults();
	adesk_ui_anchor_set(filter_list_anchor());
}

function filter_list_search() {
	var post = adesk_form_post($("list"));
	list_filters_update(0, post.listid, false);
	adesk_ajax_post_cb("awebdeskapi.php", "filter.filter_filter_post", filter_list_search_cb, post);
}

function filter_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	filter_list_filter = ary.filterid;
	adesk_ui_anchor_set(filter_list_anchor());
}

function filter_list_chsort(newSortId) {
	var oldSortId = ( filter_list_sort.match(/D$/) ? filter_list_sort.substr(0, 2) : filter_list_sort );
	var oldSortObj = $('list_sorter' + oldSortId);
	var sortObj = $('list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( filter_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = filter_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = filter_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old filter_list_sort
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	filter_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(filter_list_anchor());
	return false;
}

function filter_list_discern_sortclass() {
	if (filter_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", filter_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (filter_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	filter_list_sort_discerned = true;
}

{/literal}
