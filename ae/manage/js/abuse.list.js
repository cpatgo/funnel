var abuse_str_list_abuseratio = "{'%s%% (Limit: %s%%)'|alang|js}";
var abuse_str_list_suspended = "{'(SUSPENDED)'|alang|js}";

{literal}
var abuse_table = new ACTable();
var abuse_list_sort = "0";
var abuse_list_offset = "0";
var abuse_list_sort_discerned = false;

abuse_table.setcol(0, function(row) {
	if ( adesk_js_site.general_url_rewrite ) {
		var lnk = sprintf("../complaint/?g=%s&h=%s", row.id, row.hash);
	} else {
		var lnk = sprintf("../index.php?action=complaint&g=%s&h=%s", row.id, row.hash);
	}
	var view = Builder.node("a", { href: lnk, onclick: 'adesk_ui_openwindow(this.href);return false;' }, jsOptionView);

	var ary = [];

	if ( row.ratio > row.abuseratio ) {
		ary.push(view);
	}

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

abuse_table.setcol(1, function(row, td) {
	var nodes = [ Builder.node('span', [ Builder._text(row.title) ]) ];
	if ( row.ratio > row.abuseratio ) {
		//td.className = 'adesk_row_disabled';
		nodes.push(Builder._text(" "));
		nodes.push(Builder.node('span', { className: 'row_suspended' }, [ Builder._text(abuse_str_list_suspended) ]));
	}
	return Builder.node('div', [ nodes ]);
});

abuse_table.setcol(2, function(row, td) {
	td.align = 'center';
	return ( row.sent == '' ? jsNotAvailable : row.sent );
});

abuse_table.setcol(3, function(row, td) {
	td.align = 'center';
	return row.abuses;
});

abuse_table.setcol(4, function(row, td) {
	td.align = 'center';
	return sprintf(abuse_str_list_abuseratio, row.ratio, row.abuseratio);
});

function abuse_list_anchor() {
	return sprintf("list-%s-%s-%s", abuse_list_sort, abuse_list_offset);
}

function abuse_list_tabelize(rows, offset) {
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
	if ($("list_delete_button") !== null)
		$("list_delete_button").className = "adesk_inline";
	adesk_paginator_tabelize(abuse_table, "list_table", rows, offset);
	$("loadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function abuse_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	abuse_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(abuse_list_anchor());
	$("loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, abuse_list_sort, abuse_list_offset, this.limit, 0);

	$("list").className = "adesk_block";
}

function abuse_list_chsort(newSortId) {
	var oldSortId = ( abuse_list_sort.match(/D$/) ? abuse_list_sort.substr(0, 2) : abuse_list_sort );
	var oldSortObj = $('list_sorter' + oldSortId);
	var sortObj = $('list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( abuse_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = abuse_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = abuse_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old abuse_list_sort
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	abuse_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(abuse_list_anchor());
	return false;
}

function abuse_list_discern_sortclass() {
	if (abuse_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", abuse_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (abuse_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	abuse_list_sort_discerned = true;
}

{/literal}
