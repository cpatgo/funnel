var report_campaign_open_str_totalopens = '{"Total Opens"|alang|js}';
var report_campaign_open_str_uniqueopens = '{"Unique Opens"|alang|js}';
var report_campaign_open_str_unopened = '{"Un-Opened"|alang|js}';
{literal}
var open_table = new ACTable();
var open_list_sort_discerned = false;

open_table.setcol(0, function(row) {
	if ( row.subscriberid > 0 && row.email != 'twitter' ) {
		return Builder.node('a', { href: 'desk.php?action=subscriber_view&id=' + row.subscriberid + '#log-03D-0-0' }, [ Builder._text(row.email) ]);
	} else {
		return Builder.node('span', {}, [ Builder._text(row.email) ]);
	}
});

open_table.setcol(1, function(row) {
	return sql2date(row.tstamp).format(datetimeformat);
});

open_table.setcol(2, function(row) {
	return row.times;
});

function open_totals() {
	adesk_ajax_call_cb("awebdeskapi.php?hash=" + report_campaign_list_hash, "open.open_select_totals", adesk_ajax_cb(open_totals_cb), report_campaign_id, $("messageid").value);
}

function open_totals_cb(ary) {
	var opens = adesk_number_format(ary.opens, ".", ",");
	var uniqueopens = adesk_number_format(ary.uniqueopens, ".", ",");
	var unopens = adesk_number_format(ary.unopens, ".", ",");
	$("open_opens_t").innerHTML         = opens;
	$("open_uniqueopens_t").innerHTML   = uniqueopens;
	$("open_unopens_t").innerHTML       = unopens;
	$("open_opens_p").innerHTML         = sprintf("(%.2f%%)", ary.total_amt > 0 ? 100 * ary.opens / ary.total_amt : 0);
	$("open_uniqueopens_p").innerHTML   = sprintf("(%.2f%%)", ary.total_amt > 0 ? 100 * ary.uniqueopens / ary.total_amt : 0);
	$("open_unopens_p").innerHTML       = sprintf("(%.2f%%)", ary.total_amt > 0 ? 100 * ary.unopens / ary.total_amt : 0);
	//
	$("unopen_opens_t").innerHTML       = opens;
	$("unopen_uniqueopens_t").innerHTML = uniqueopens;
	$("unopen_unopens_t").innerHTML     = unopens;
	$("unopen_opens_p").innerHTML       = sprintf("(%.2f%%)", ary.total_amt > 0 ? 100 * ary.opens / ary.total_amt : 0);
	$("unopen_uniqueopens_p").innerHTML = sprintf("(%.2f%%)", ary.total_amt > 0 ? 100 * ary.uniqueopens / ary.total_amt : 0);
	$("unopen_unopens_p").innerHTML     = sprintf("(%.2f%%)", ary.total_amt > 0 ? 100 * ary.unopens / ary.total_amt : 0);
}

function open_tabelize(rows, offset) {
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("open_table"));

		$("open_noresults").className = "adesk_block";
		$("open_loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		open_totals();
		return;
	}
	$("open_noresults").className = "adesk_hidden";
	adesk_paginator_tabelize(open_table, "open_table", rows, offset);
	$("open_loadingBar").className = "adesk_hidden";

	open_totals();
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function open_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (report_campaign_list_filter > 0)
		$("open_clear").style.display = "inline";
	else
		$("open_clear").style.display = "none";

	report_campaign_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(report_campaign_list_anchor());
	$("open_loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, report_campaign_list_sort, report_campaign_list_offset, this.limit, report_campaign_list_filter, report_campaign_id, $("messageid").value);

	$("open").className = "adesk_block";
}

function open_list_search() {
	var post = adesk_form_post($("open"));
	report_campaign_list_filter = post.listid;
	list_filters_update(0, post.listid, false);
	adesk_ajax_post_cb("awebdeskapi.php", "open.open_filter_post", open_list_search_cb, post);
}

function open_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	report_campaign_list_filter = ary.filterid;
	adesk_ui_anchor_set(report_campaign_list_anchor());
}

function open_list_chsort(newSortId) {
	var oldSortId = ( report_campaign_list_sort.match(/D$/) ? report_campaign_list_sort.substr(0, 2) : report_campaign_list_sort );
	var oldSortObj = $('open_list_sorter' + oldSortId);
	var sortObj = $('open_list_sorter' + newSortId);
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

function open_list_discern_sortclass() {
	if (open_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("open_list_sorter%s", report_campaign_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (report_campaign_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	open_list_sort_discerned = true;
}

function open_list_clear() {
	report_campaign_list_sort = "01";
	report_campaign_list_offset = "0";
	report_campaign_list_filter = "0";
	$("open_search").value = "";
	adesk_ui_anchor_set(report_campaign_list_anchor());
}
{/literal}
