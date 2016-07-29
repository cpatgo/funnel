<?php /* Smarty version 2.6.12, created on 2016-07-08 14:48:21
         compiled from report_campaign_linkinfo.inc.js */ ?>
<?php echo '
var linkinfo_table = new ACTable();
var linkinfo_list_sort_discerned = false;

linkinfo_table.setcol(0, function(row) {
	if ( row.subscriberid > 0 && row.email != \'twitter\' ) {
		return Builder.node(\'a\', { href: \'desk.php?action=subscriber_view&id=\' + row.subscriberid + \'#log-03D-0-0\' }, [ Builder._text(row.email) ]);
	} else {
		return Builder.node(\'span\', {}, [ Builder._text(row.email) ]);
	}
});

linkinfo_table.setcol(1, function(row) {
	return sql2date(row.tstamp).format(datetimeformat);
});

linkinfo_table.setcol(2, function(row) {
	return row.times;
});

function linkinfo_totals() {
	adesk_ajax_call_cb("awebdeskapi.php?hash=" + report_campaign_list_hash, "linkinfo.linkinfo_select_totals", adesk_ajax_cb(linkinfo_totals_cb), report_campaign_id, report_campaign_list_linkid, $("messageid").value);
}

function linkinfo_totals_cb(ary) {
	var didntclick = ary.a_total_amt - ary.a_uniqueclicks;
	if (didntclick < 0)
		didntclick = 0;
	$("linkinfo_total_t").innerHTML  = ary.a_clicks;
	$("linkinfo_unique_t").innerHTML = ary.a_uniqueclicks;
	$("linkinfo_didnt_t").innerHTML  = didntclick;
	$("linkinfo_avg").innerHTML      = sprintf("%.2f", ary.a_avg);
	$("linkinfo_didnt_p").innerHTML  = sprintf("(%.2f%%)", ary.a_total_amt > 0 ? 100 * didntclick / ary.a_total_amt : 0);

	if (ary.name == \'\')
		$("linkinfo_name").innerHTML = ary.link;
	else
		$("linkinfo_name").innerHTML = ary.name;
}

function linkinfo_tabelize(rows, offset) {
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("linkinfo_table"));

		$("linkinfo_noresults").className = "adesk_block";
		$("linkinfo_loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		linkinfo_totals();
		return;
	}
	$("linkinfo_noresults").className = "adesk_hidden";
	window.t_rows = rows;
	adesk_paginator_tabelize(linkinfo_table, "linkinfo_table", rows, offset);
	$("linkinfo_loadingBar").className = "adesk_hidden";

	linkinfo_totals();
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function linkinfo_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (report_campaign_list_filter > 0)
		$("linkinfo_clear").style.display = "inline";
	else
		$("linkinfo_clear").style.display = "none";

	report_campaign_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(report_campaign_list_anchor());
	$("linkinfo_loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, report_campaign_list_sort, report_campaign_list_offset, this.limit, report_campaign_list_filter, report_campaign_list_linkid);

	$("linkinfo").className = "adesk_block";
}

function linkinfo_list_search() {
	var post = adesk_form_post($("linkinfo"));
	report_campaign_list_filter = post.listid;
	list_filters_update(0, post.listid, false);
	adesk_ajax_post_cb("awebdeskapi.php", "linkinfo.linkinfo_filter_post", linkinfo_list_search_cb, post);
}

function linkinfo_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	report_campaign_list_filter = ary.filterid;
	adesk_ui_anchor_set(report_campaign_list_anchor());
}

function linkinfo_list_chsort(newSortId) {
	var oldSortId = ( report_campaign_list_sort.match(/D$/) ? report_campaign_list_sort.substr(0, 2) : report_campaign_list_sort );
	var oldSortObj = $(\'linkinfo_list_sorter\' + oldSortId);
	var sortObj = $(\'linkinfo_list_sorter\' + newSortId);
	// if sort column didn\'t change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( report_campaign_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = report_campaign_list_sort.substr(0, 2);
			sortObj.className = \'adesk_sort_asc\';
		} else {
			// was ASC
			newSortId = report_campaign_list_sort + \'D\';
			sortObj.className = \'adesk_sort_desc\';
		}
	} else {
		// remove old report_campaign_list_sort
		if ( oldSortObj ) oldSortObj.className = \'adesk_sort_other\';
		// set sort field
		sortObj.className = \'adesk_sort_asc\';
	}
	report_campaign_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(report_campaign_list_anchor());
	return false;
}

function linkinfo_list_discern_sortclass() {
	if (linkinfo_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("linkinfo_list_sorter%s", report_campaign_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (report_campaign_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	linkinfo_list_sort_discerned = true;
}

function linkinfo_list_clear() {
	report_campaign_list_sort = "01";
	report_campaign_list_offset = "0";
	report_campaign_list_filter = "0";
	$("linkinfo_search").value = "";
	adesk_ui_anchor_set(report_campaign_list_anchor());
}
'; ?>
