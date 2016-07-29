{literal}
var update_table = new ACTable();
var update_list_sort_discerned = false;

update_table.setcol(0, function(row, td) {
	td.vAlign = 'top';
	if ( row.subscriberid > 0 && row.email != 'twitter' ) {
		return Builder.node('a', { href: 'desk.php?action=subscriber_view&id=' + row.subscriberid + '#log-03D-0-0' }, [ Builder._text(row.a_email) ]);
	} else {
		return Builder.node('span', {}, [ Builder._text(row.a_email) ]);
	}
});

update_table.setcol(1, function(row, td) {
	td.vAlign = 'top';
	return sql2date(row.tstamp).format(datetimeformat);
});

function update_totals() {
	adesk_ajax_call_cb("awebdeskapi.php?hash=" + report_campaign_list_hash, "update.update_select_totals", adesk_ajax_cb(update_totals_cb), report_campaign_id, $("messageid").value);
}

function update_totals_cb(ary) {
	var updates = adesk_number_format(ary.updates, ".", ",");
	var didntupdate = adesk_number_format(ary.didntupdate, ".", ",");
	$("update_total_t").innerHTML  = updates;
	$("update_didnt_t").innerHTML  = didntupdate;
	$("update_total_p").innerHTML  = sprintf("(%.2f%%)", ary.total_amt > 0 ? 100 * ary.updates / ary.total_amt : 0);
	$("update_didnt_p").innerHTML  = sprintf("(%.2f%%)", ary.total_amt > 0 ? 100 * ary.didntupdate / ary.total_amt : 0);
}

function update_tabelize(rows, offset) {
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("update_table"));

		$("update_noresults").className = "adesk_block";
		$("update_loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		update_totals();
		return;
	}
	$("update_noresults").className = "adesk_hidden";
	window.t_rows = rows;
	adesk_paginator_tabelize(update_table, "update_table", rows, offset);
	$("update_loadingBar").className = "adesk_hidden";

	update_totals();
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function update_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (report_campaign_list_filter > 0)
		$("update_clear").style.display = "inline";
	else
		$("update_clear").style.display = "none";

	report_campaign_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(report_campaign_list_anchor());
	$("update_loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, report_campaign_list_sort, report_campaign_list_offset, this.limit, report_campaign_list_filter, report_campaign_id, $("messageid").value);

	$("update").className = "adesk_block";
}

function update_list_search() {
	var post = adesk_form_post($("update"));
	report_campaign_list_filter = post.listid;
	list_filters_update(0, post.listid, false);
	adesk_ajax_post_cb("awebdeskapi.php", "update.update_filter_post", update_list_search_cb, post);
}

function update_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	report_campaign_list_filter = ary.filterid;
	adesk_ui_anchor_set(report_campaign_list_anchor());
}

function update_list_chsort(newSortId) {
	var oldSortId = ( report_campaign_list_sort.match(/D$/) ? report_campaign_list_sort.substr(0, 2) : report_campaign_list_sort );
	var oldSortObj = $('update_list_sorter' + oldSortId);
	var sortObj = $('update_list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( report_campaign_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = report_campaign_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = report_campaign_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old report_campaign_list_sort
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	report_campaign_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(report_campaign_list_anchor());
	return false;
}

function update_list_discern_sortclass() {
	if (update_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("update_list_sorter%s", report_campaign_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (report_campaign_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	update_list_sort_discerned = true;
}

function update_list_clear() {
	report_campaign_list_sort = "01";
	report_campaign_list_offset = "0";
	report_campaign_list_filter = "0";
	$("update_search").value = "";
	adesk_ui_anchor_set(report_campaign_list_anchor());
}
{/literal}
