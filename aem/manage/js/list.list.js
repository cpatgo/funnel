var list_list_str_copy = '{"Copy"|alang|js}';
{literal}
var list_table = new ACTable();
var list_list_sort = "01";
var list_list_offset = "0";
var list_list_filter = "0";
var list_list_sort_discerned = false;

list_table.setcol(0, function(row) {
	return Builder.node("input", { type: "checkbox", name: "multi[]", value: row.id, onclick: "adesk_form_check_selection_none(this, $('acSelectAllCheckbox'), $('selectXPageAllBox'))" });
});

list_table.setcol(1, function(row) {
	var edit = Builder.node("a", { href: sprintf("#form-%d", row.id) }, jsOptionEdit);
	var dele = Builder.node("a", { href: sprintf("#delete-%d", row.id) }, jsOptionDelete);
	var stat = Builder.node("a", { href: "desk.php?action=report_list&id=" + row.id }, jsOptionReports);
	var copy = Builder.node("a", { href: sprintf("#copy-%d", row.id) }, list_list_str_copy);

	var ary = [];

	if (adesk_js_admin.pg_list_edit == 1) {
		ary.push(edit);
		ary.push(" ");
	}

	if (adesk_js_admin.pg_reports_list == 1) {
		ary.push(stat);
		ary.push(" ");
	}

	if (adesk_js_admin.pg_list_edit == 1 && canAddList) {
		ary.push(copy);
		ary.push(" ");
	}

	if (adesk_js_admin.pg_list_delete == 1) {
		ary.push(dele);
		//ary.push(" ");
	}

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

list_table.setcol(2, function(row, td) {
	return Builder._text(row.name);
});

list_table.setcol(3, function(row) {
	return Builder.node("div", { align: "center" }, [ Builder._text( adesk_number_format(row.subscribers, decimalDelim, commaDelim) ) ]);
});

list_table.setcol(4, function(row) {
	return Builder.node("div", { align: "center" }, [ Builder._text( adesk_number_format(row.campaigns, decimalDelim, commaDelim) ) ]);
});

list_table.setcol(5, function(row) {
	return Builder.node("div", { align: "center" }, [ Builder._text( adesk_number_format(row.emails, decimalDelim, commaDelim) ) ]);
});

function list_list_anchor() {
	return sprintf("list-%s-%s-%s", list_list_sort, list_list_offset, list_list_filter);
}

function list_list_tabelize(rows, offset) {
	if (rows.length < 1) {
		if (!list_list_filter || list_list_filter == 0) {
			adesk_ui_api_callback();
			adesk_ui_anchor_set('form-0');
			return;
		}
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("list_table"));

		$("list_noresults").className = "adesk_block";

		if (adesk_js_admin.pg_list_delete == 1) {
			if($("list_delete_button")) $("list_delete_button").className = "adesk_hidden";
		}

		$("loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		return;
	}
	$("list_noresults").className = "adesk_hidden";

	if (adesk_js_admin.pg_list_delete == 1) {
		if($("list_delete_button")) $("list_delete_button").className = "adesk_inline";
	}

	adesk_paginator_tabelize(list_table, "list_table", rows, offset);

	$("list_list_count").innerHTML = " (" + adesk_number_format(paginators[1].total, decimalDelim, commaDelim) + ")";
	$("list_list_count").className = "adesk_inline";
	if ( $('selectXPageAllBox') ) {
		var spans = $('selectXPageAllBox').getElementsByTagName('span');
		if ( spans.length > 2 ) {
			spans[2].innerHTML = adesk_number_format(paginators[1].total, decimalDelim, commaDelim);
		}
	}

	$("loadingBar").className = "adesk_hidden";
	list_list_offset = parseInt(offset, 10);
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function list_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (list_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	list_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(list_list_anchor());
	$("loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, list_list_sort, list_list_offset, this.limit, list_list_filter);

	$("list").className = "adesk_block";
}

function list_list_limitize(limit) {
	// save new admin limit locally
	adesk_js_admin.lists_per_page = limit;
	// save new admin limit remotelly
	adesk_ajax_call_cb('awebdeskapi.php', 'user.user_update_value', null, 'lists_per_page', limit);
	// set new limit
	this.limit = limit;
	// fetch new list
	this.paginate(this.offset);
}

function list_list_clear() {
	list_list_sort = "01";
	list_list_offset = "0";
	list_list_filter = "0";
	$("list_search").value = "";
	list_search_defaults();
	adesk_ui_anchor_set(list_list_anchor());
}

function list_list_search() {
	var post = adesk_form_post($("list"));
	adesk_ajax_post_cb("awebdeskapi.php", "list.list_filter_post", list_list_search_cb, post);
}

function list_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	list_list_filter = ary.filterid;
	adesk_ui_anchor_set(list_list_anchor());
}

function list_list_chsort(newSortId) {
	var oldSortId = ( list_list_sort.match(/D$/) ? list_list_sort.substr(0, 2) : list_list_sort );
	var oldSortObj = $('list_sorter' + oldSortId);
	var sortObj = $('list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( list_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = list_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = list_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old list_list_sort
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	list_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(list_list_anchor());
	return false;
}

function list_list_discern_sortclass() {
	if (list_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", list_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (list_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	list_list_sort_discerned = true;
}

{/literal}
