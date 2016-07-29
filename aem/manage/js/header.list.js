var header_table = new ACTable();
var header_list_sort = "01";
var header_list_offset = "0";
var header_list_filter = {jsvar var=$filterid};
var header_list_sort_discerned = false;

{literal}
header_table.setcol(0, function(row) {
	return Builder.node("input", { type: "checkbox", name: "multi[]", value: row.id, onclick: "adesk_form_check_selection_none(this, $('acSelectAllCheckbox'), $('selectXPageAllBox'))" });
});

header_table.setcol(1, function(row) {
	var edit = Builder.node("a", { href: sprintf("#form-%d", row.id) }, jsOptionEdit);
	var dele = Builder.node("a", { href: sprintf("#delete-%d", row.id) }, jsOptionDelete);

	var ary = [];

	if (adesk_js_admin.pg_list_edit == 1) {
		ary.push(edit);
		ary.push(" ");
	}

	if (adesk_js_admin.pg_list_delete == 1) {
		ary.push(dele);
	}

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

header_table.setcol(2, function(row) {
	return row.title;
});

header_table.setcol(3, function(row) {
	return row.name + ': ' + row.value;
});

header_table.setcol(4, function(row) {
	return row.lists;
});

function header_list_anchor() {
	return sprintf("list-%s-%s-%s", header_list_sort, header_list_offset, header_list_filter);
}

function header_list_tabelize(rows, offset) {
	if (rows.length < 1) {
		if (!header_list_filter || header_list_filter == 0) {
			adesk_ui_api_callback();
			adesk_ui_anchor_set('form-0');
			return;
		}
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("list_table"));

		$("list_noresults").className = "adesk_block";
		if($("list_delete_button")) $("list_delete_button").className = "adesk_hidden";
		$("loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		return;
	}
	$("list_noresults").className = "adesk_hidden";
	if($("list_delete_button")) $("list_delete_button").className = "adesk_inline";
	adesk_paginator_tabelize(header_table, "list_table", rows, offset);
	$("loadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function header_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (header_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	header_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(header_list_anchor());
	$("loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, header_list_sort, header_list_offset, this.limit, header_list_filter);

	$("list").className = "adesk_block";
}

function header_list_clear() {
	header_list_sort = "01";
	header_list_offset = "0";
	header_list_filter = "0";
	header_listfilter = null;
	$("JSListManager").value = 0;
	$("list_search").value = "";
	list_filters_update(0, 0, true);
	header_search_defaults();
	adesk_ui_anchor_set(header_list_anchor());
}

function header_list_search() {
	var post = adesk_form_post($("list"));
	header_listfilter = post.listid;
	list_filters_update(0, post.listid, false);
	adesk_ajax_post_cb("awebdeskapi.php", "header.header_filter_post", header_list_search_cb, post);
}

function header_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	header_list_filter = ary.filterid;
	adesk_ui_anchor_set(header_list_anchor());
}

function header_list_chsort(newSortId) {
	var oldSortId = ( header_list_sort.match(/D$/) ? header_list_sort.substr(0, 2) : header_list_sort );
	var oldSortObj = $('list_sorter' + oldSortId);
	var sortObj = $('list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( header_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = header_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = header_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old header_list_sort
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	header_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(header_list_anchor());
	return false;
}

function header_list_discern_sortclass() {
	if (header_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", header_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (header_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	header_list_sort_discerned = true;
}

{/literal}
