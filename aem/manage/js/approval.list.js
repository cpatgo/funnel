{literal}
var approval_table = new ACTable();
var approval_list_sort = "0";
var approval_list_offset = "0";
var approval_list_sort_discerned = false;

approval_table.setcol(0, function(row) {
	var view = Builder.node("a", { href: sprintf("../index.php?action=approve&a=%s&c=%s&h=%s", row.id, row.campaignid, row.hash), onclick: 'adesk_ui_openwindow(this.href);return false;' }, jsOptionView);
	//var dele = Builder.node("a", { href: sprintf("#delete-%d", row.id) }, jsOptionDelete);

	var ary = [];

	ary.push(view);
	//ary.push(" ");
	//ary.push(dele);

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

approval_table.setcol(1, function(row) {
	return row.campaignname;
});

approval_table.setcol(2, function(row) {
	return row.username;
});

approval_table.setcol(3, function(row) {
	return row.groupname;
});

approval_table.setcol(4, function(row, td) {
	td.align = 'center';
	return sql2date(row.sdate).format(datetimeformat);
});

approval_table.setcol(5, function(row, td) {
	td.align = 'center';
	return row.approvals;
});

function approval_list_anchor() {
	return sprintf("list-%s-%s", approval_list_sort, approval_list_offset);
}

function approval_list_tabelize(rows, offset) {
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
	$("list_noresults").className = "adesk_hidden";
	adesk_paginator_tabelize(approval_table, "list_table", rows, offset);
	$("loadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function approval_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	approval_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(approval_list_anchor());
	$("loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, approval_list_sort, approval_list_offset, this.limit, 0);

	$("list").className = "adesk_block";
}

function approval_list_chsort(newSortId) {
	var oldSortId = ( approval_list_sort.match(/D$/) ? approval_list_sort.substr(0, 2) : approval_list_sort );
	var oldSortObj = $('list_sorter' + oldSortId);
	var sortObj = $('list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( approval_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = approval_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = approval_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old approval_list_sort
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	approval_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(approval_list_anchor());
	return false;
}

function approval_list_discern_sortclass() {
	if (approval_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", approval_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (approval_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	approval_list_sort_discerned = true;
}

{/literal}
