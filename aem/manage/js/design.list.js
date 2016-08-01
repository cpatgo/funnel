{literal}
var design_table = new ACTable();
var design_list_sort = "0";
var design_list_offset = 0;
var design_list_filter = 0;
var design_list_sort_discerned = false;

design_table.addcol(function(row) {
	var edit = Builder.node("a", { href: sprintf("#form-%d", row.id) }, jsOptionEdit);
	//var dele = Builder.node("a", { href: sprintf("#delete-%d", row.id) }, jsOptionDelete);

	var ary = [];

	ary.push(edit);
	//ary.push(" ");
	//ary.push(dele);

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

design_table.addcol(function(row) {
	return row.title;
});

design_table.addcol(function(row) {
	return row.site_name;
});

function design_list_anchor() {
	return sprintf("list-%s-%s-%s", design_list_sort, design_list_offset, design_list_filter);
}

function design_list_tabelize(rows, offset) {
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
	adesk_paginator_tabelize(design_table, "list_table", rows, offset);
	$("loadingBar").className = "adesk_hidden";
	if ( rows.length == 1 && design_list_filter == 0 ) {
		adesk_ui_anchor_set('form-' + rows[0].id);
	}
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function design_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (design_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	design_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(design_list_anchor());
	$("loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, design_list_sort, design_list_offset, this.limit, design_list_filter);

	$("list").className = "adesk_block";
}

function design_list_clear() {
	design_list_sort = "0";
	design_list_offset = 0;
	design_list_filter = 0;
	$("list_search").value = "";
	design_search_defaults();
	adesk_ui_anchor_set(design_list_anchor());
}

function design_list_search() {
	var post = adesk_form_post($("list"));
	adesk_ajax_post_cb("awebdeskapi.php", "design.design_filter_post", design_list_search_cb, post);
}

function design_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	design_list_filter = ary.filterid;
	adesk_ui_anchor_set(design_list_anchor());
}

function design_list_chsort(newSortId) {
	var oldSortId = ( design_list_sort.match(/D$/) ? design_list_sort.substr(0, 2) : design_list_sort );
	var oldSortObj = $('list_sorter' + oldSortId);
	var sortObj = $('list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( design_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = design_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = design_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old design_list_sort
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	design_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(design_list_anchor());
	return false;
}

function design_list_discern_sortclass() {
	if (design_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", design_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (design_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	design_list_sort_discerned = true;
}

{/literal}
