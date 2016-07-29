{literal}
var forward_table = new ACTable();
var forward_list_sort_discerned = false;

forward_table.setcol(0, function(row, td) {
	td.vAlign = 'top';
	if ( row.subscriberid > 0 && row.email != 'twitter' ) {
		return Builder.node('a', { href: 'desk.php?action=subscriber_view&id=' + row.subscriberid }, [ Builder._text(row.email_from) ]);
	} else {
		return Builder.node('span', {}, [ Builder._text(row.email_from) ]);
	}
});

forward_table.setcol(1, function(row, td) {
	td.vAlign = 'top';
	return sql2date(row.tstamp).format(datetimeformat);
});

forward_table.setcol(2, function(row, td) {
	td.vAlign = 'top';
	return row.a_times;
});

forward_table.setcol(3, function(row, td) {
	td.vAlign = 'top';
	var obj = Builder.node('div');
	obj.innerHTML = nl2br(row.brief_message);
	return obj;
});

function forward_totals() {
	adesk_ajax_call_cb("awebdeskapi.php?hash=" + report_campaign_list_hash, "forward.forward_select_totals", adesk_ajax_cb(forward_totals_cb), report_campaign_id, $("messageid").value);
}

function forward_totals_cb(ary) {
	var forwards = adesk_number_format(ary.forwards, ".", ",");
	var uniqueforwards = adesk_number_format(ary.uniqueforwards, ".", ",");
	var didntforward = adesk_number_format(ary.didntforward, ".", ",");
	$("forward_total_t").innerHTML  = forwards;
	$("forward_unique_t").innerHTML = uniqueforwards;
	$("forward_didnt_t").innerHTML  = didntforward;
	$("forward_avg").innerHTML      = sprintf("%.2f", ary.avgforwards);
	$("forward_total_p").innerHTML  = sprintf("(%.2f%%)", ary.total_amt > 0 ? 100 * ary.forwards / ary.total_amt : 0);
	$("forward_unique_p").innerHTML = sprintf("(%.2f%%)", ary.total_amt > 0 ? 100 * ary.uniqueforwards / ary.total_amt : 0);
	$("forward_didnt_p").innerHTML  = sprintf("(%.2f%%)", ary.total_amt > 0 ? 100 * ary.didntforward / ary.total_amt : 0);
}

function forward_tabelize(rows, offset) {
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("forward_table"));

		$("forward_noresults").className = "adesk_block";
		$("forward_loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		forward_totals();
		return;
	}
	$("forward_noresults").className = "adesk_hidden";
	window.t_rows = rows;
	adesk_paginator_tabelize(forward_table, "forward_table", rows, offset);
	$("forward_loadingBar").className = "adesk_hidden";

	forward_totals();
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function forward_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (report_campaign_list_filter > 0)
		$("forward_clear").style.display = "inline";
	else
		$("forward_clear").style.display = "none";

	report_campaign_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(report_campaign_list_anchor());
	$("forward_loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, report_campaign_list_sort, report_campaign_list_offset, this.limit, report_campaign_list_filter, report_campaign_id, $("messageid").value);

	$("forward").className = "adesk_block";
}

function forward_list_search() {
	var post = adesk_form_post($("forward"));
	report_campaign_list_filter = post.listid;
	list_filters_update(0, post.listid, false);
	adesk_ajax_post_cb("awebdeskapi.php", "forward.forward_filter_post", forward_list_search_cb, post);
}

function forward_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	report_campaign_list_filter = ary.filterid;
	adesk_ui_anchor_set(report_campaign_list_anchor());
}

function forward_list_chsort(newSortId) {
	var oldSortId = ( report_campaign_list_sort.match(/D$/) ? report_campaign_list_sort.substr(0, 2) : report_campaign_list_sort );
	var oldSortObj = $('forward_list_sorter' + oldSortId);
	var sortObj = $('forward_list_sorter' + newSortId);
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

function forward_list_discern_sortclass() {
	if (forward_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("forward_list_sorter%s", report_campaign_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (report_campaign_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	forward_list_sort_discerned = true;
}

function forward_list_clear() {
	report_campaign_list_sort = "01";
	report_campaign_list_offset = "0";
	report_campaign_list_filter = "0";
	$("forward_search").value = "";
	adesk_ui_anchor_set(report_campaign_list_anchor());
}
{/literal}
