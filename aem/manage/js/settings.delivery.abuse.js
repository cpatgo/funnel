var settings_delivery_str_viewabuse = '{"View Abuse Reports"|alang|js}';

{literal}

function settings_delivery_viewabuse(id) {
	$("viewlist_table").hide();
	$("viewlist_noresults").hide();
	$("viewloadingBar").show();

	adesk_ajax_call_cb("awebdeskapi.php", "abuse.abuse_report", adesk_ajax_cb(settings_delivery_viewabuse_cb), id);
}

function settings_delivery_viewabuse_cb(ary) {
	$("viewloadingBar").hide();

	if (!ary.row || ary.row.length == 0) {
		$("viewlist_noresults").show();
	} else {
		adesk_dom_remove_children($("viewlist_table"));
		for (var i = 0; i < ary.row.length; i++) {
			var rdate = Builder.node("td", ary.row[i].rdate);
			var title = Builder.node("td", Builder.node("a", { target: "_blank", href: sprintf("desk.php?action=report_campaign&id=%d", ary.row[i].campaignid) }, ary.row[i].a_campaigntitle));
			$("viewlist_table").appendChild(Builder.node("tr", [ rdate, title ]));
		}
		$("viewlist_table").show();
	}

	$("settings_delivery_viewabuse").show();
}

var group_table = new ACTable();
var group_list_sort = "01";
var group_list_offset = 0;
var group_list_filter = 0;
var group_list_sort_discerned = false;

group_table.setcol(0, function(row) {
	if ( adesk_js_site.general_url_rewrite ) {
		var lnk = sprintf("../complaint/?g=%s&h=%s", row.id, row.hash);
	} else {
		var lnk = sprintf("../index.php?action=complaint&g=%s&h=%s", row.id, row.hash);
	}
	var edit = Builder.node("a", { href: lnk, onclick: 'adesk_ui_openwindow(this.href);return false;' }, jsOptionEdit);
	var view = Builder.node("a", { href: "#", onclick: sprintf("settings_delivery_viewabuse(%d); return false", row.id) }, settings_delivery_str_viewabuse);

	// Check permissions

	var ary = [];

	ary.push(edit);
	ary.push(" ");
	ary.push(view);

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

group_table.setcol(1, function(row) {
	if (typeof row.a_istrial != "undefined")
		row.title += " " + row.a_istrial;
	return row.title;
});

group_table.setcol(2, function(row) {
	return row.abuses;
});

group_table.setcol(3, function(row) {
	if (row.abuses > 0)
		return sprintf("%0.2f%%", (row.abuses / row.sent) * 100.0);
	else
		return "0.00%";
});

function group_list_tabelize(rows, offset) {
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("abuselist_table"));

		$("abuselist_noresults").show();
		adesk_ui_api_callback();
		return;
	}
	$("abuselist_noresults").hide();
	$("abuseloadingBar").hide();
	adesk_paginator_tabelize(group_table, "abuselist_table", rows, offset);
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function group_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	group_list_offset = parseInt(offset, 10);

	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, group_list_sort, group_list_offset, this.limit, group_list_filter);
}

function group_list_chsort(newSortId) {
	var sortlen = group_list_sort.length;
	var oldSortId = ( group_list_sort.substr(sortlen-1, 1) == 'D' ? group_list_sort.substr(0, 2) : group_list_sort );
	var oldSortObj = $('list_sorter' + oldSortId);
	var sortObj = $('list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( group_list_sort.substr(sortlen-1, 1) == 'D' ) {
			// was DESC
			newSortId = group_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = group_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old group_list_sort
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	group_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	paginators[1].paginate(group_list_offset);
	return false;
}

function group_list_discern_sortclass() {
	if (group_list_sort_discerned)
		return;

	var elems = $("abuselist_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", group_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (group_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	group_list_sort_discerned = true;
}

{/literal}