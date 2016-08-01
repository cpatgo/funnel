var settings_delivery_str_viewfbl = '{"View Abuse Report"|alang|js}';

{literal}

function settings_delivery_viewfbl(id) {
	$("fbllist_table").hide();
	$("fbllist_noresults").hide();
	$("fblloadingBar").show();

	adesk_ajax_call_cb("awebdeskapi.php", "feedbackloop.feedbackloop_select_array_paginator", adesk_ajax_cb(settings_delivery_viewfbl_cb), id);
}

function settings_delivery_viewfbl_cb(ary) {
	$("fblloadingBar").hide();

	if (!ary.row || ary.row.length == 0) {
		$("fbllist_noresults").show();
	} else {
		adesk_dom_remove_children($("fbllist_table"));
		for (var i = 0; i < ary.row.length; i++) {
			var rdate = Builder.node("td", ary.row[i].rdate);
			var title = Builder.node("td", Builder.node("a", { target: "_blank", href: sprintf("desk.php?action=report_campaign&id=%d", ary.row[i].campaignid) }, ary.row[i].a_campaigntitle));
			$("fbllist_table").appendChild(Builder.node("tr", [ rdate, title ]));
		}
		$("fbllist_table").show();
	}

	$("settings_delivery_viewfbl").show();
}

var fbl_table = new ACTable();
var fbl_list_sort = "01";
var fbl_list_offset = 0;
var fbl_list_filter = 0;
var fbl_list_sort_discerned = false;

fbl_table.setcol(0, function(row) {
	var edit = Builder.node("a", { target: "_blank", href: sprintf("desk.php?action=feedbackloop#form-%d", row.id) }, jsEdit);
	var view = Builder.node("a", { href: "#", onclick: sprintf("settings_delivery_viewfbl(%d); return false", row.id) }, settings_delivery_str_viewfbl);

	// Check permissions

	var ary = [];

	ary.push(edit);
	ary.push(" ");
	ary.push(view);

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

fbl_table.setcol(1, function(row) {
	if (typeof row.a_istrial != "undefined")
		row.title += " " + row.a_istrial;
	return row.title;
});

fbl_table.setcol(2, function(row) {
	return row.abuses;
});

fbl_table.setcol(3, function(row) {
	if (row.abuses > 0)
		return sprintf("%0.2f%%", (row.abuses / row.sent) * 100.0);
	else
		return "0.00%";
});

function fbl_list_tabelize(rows, offset) {
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("fbllist_table"));

		$("fbllist_noresults").show();
		adesk_ui_api_callback();
		return;
	}
	$("fbllist_noresults").hide();
	$("fblloadingBar").hide();
	adesk_paginator_tabelize(fbl_table, "fbllist_table", rows, offset);
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function fbl_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	fbl_list_offset = parseInt(offset, 10);

	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, fbl_list_sort, fbl_list_offset, this.limit, fbl_list_filter);
}

function fbl_list_chsort(newSortId) {
	var sortlen = fbl_list_sort.length;
	var oldSortId = ( fbl_list_sort.substr(sortlen-1, 1) == 'D' ? fbl_list_sort.substr(0, 2) : fbl_list_sort );
	var oldSortObj = $('list_sorter' + oldSortId);
	var sortObj = $('list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( fbl_list_sort.substr(sortlen-1, 1) == 'D' ) {
			// was DESC
			newSortId = fbl_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = fbl_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old fbl_list_sort
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	fbl_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	paginators[2].paginate(fbl_list_offset);
	return false;
}

function fbl_list_discern_sortclass() {
	if (fbl_list_sort_discerned)
		return;

	var elems = $("fbllist_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", fbl_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (fbl_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	fbl_list_sort_discerned = true;
}

{/literal}