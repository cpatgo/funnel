<?php /* Smarty version 2.6.12, created on 2016-07-08 14:48:21
         compiled from report_campaign_link.inc.js */ ?>
<?php echo '
var link_table = new ACTable();
var link_list_sort_discerned = false;
var link_offset_was = "0";

link_table.setcol(0, function(row) {
	if (row.name != \'\') {
		return Builder.node("a", { onclick: sprintf("link_info(%d); return false", row.id), href: "#" , title: row.link }, row.name);
	} else {
		return Builder.node("a", { onclick: sprintf("link_info(%d); return false", row.id), href: "#" }, row.link);
	}
});

link_table.setcol(1, function(row) {
	return row.a_unique;
});

link_table.setcol(2, function(row) {
	return row.a_total;
});

function link_info(id) {
	report_campaign_list_linkid = id;
	report_campaign_list_mode   = "linkinfo";
	link_offset_was = report_campaign_list_offset;
	report_campaign_list_offset = "0";
	adesk_ui_anchor_set(report_campaign_list_anchor());
}

function link_totals() {
	adesk_ajax_call_cb("awebdeskapi.php?hash=" + report_campaign_list_hash, "link.link_select_totals", adesk_ajax_cb(link_totals_cb), report_campaign_id, $("messageid").value);
}

function link_totals_cb(ary) {
	var linkclicks = adesk_number_format(ary.linkclicks, ".", ",");
	var uniquelinkclicks = adesk_number_format(ary.uniquelinkclicks, ".", ",");
	var didntclick = adesk_number_format(ary.didntclick, ".", ",");
	$("link_total_t").innerHTML  = linkclicks;
	$("link_unique_t").innerHTML = uniquelinkclicks;
	$("link_didnt_t").innerHTML  = didntclick;
	$("link_avg").innerHTML      = sprintf("%.2f", ary.avgclicks);
	$("link_didnt_p").innerHTML  = sprintf("(%.2f%%)", ary.total_amt > 0 ? 100 * ary.didntclick / ary.total_amt : 0);
}

function link_tabelize(rows, offset) {
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("link_table"));

		$("link_noresults").className = "adesk_block";
		$("link_loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		link_totals();
		return;
	}
	$("link_noresults").className = "adesk_hidden";
	window.t_rows = rows;
	adesk_paginator_tabelize(link_table, "link_table", rows, offset);
	$("link_loadingBar").className = "adesk_hidden";

	link_totals();
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function link_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (report_campaign_list_filter > 0)
		$("link_clear").style.display = "inline";
	else
		$("link_clear").style.display = "none";

	report_campaign_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(report_campaign_list_anchor());
	$("link_loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, report_campaign_list_sort, report_campaign_list_offset, this.limit, report_campaign_list_filter, report_campaign_id, $("messageid").value);

	$("link").className = "adesk_block";
}

function link_list_search() {
	var post = adesk_form_post($("link"));
	report_campaign_list_filter = post.listid;
	list_filters_update(0, post.listid, false);
	adesk_ajax_post_cb("awebdeskapi.php", "link.link_filter_post", link_list_search_cb, post);
}

function link_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	report_campaign_list_filter = ary.filterid;
	adesk_ui_anchor_set(report_campaign_list_anchor());
}

function link_list_chsort(newSortId) {
	var oldSortId = ( report_campaign_list_sort.match(/D$/) ? report_campaign_list_sort.substr(0, 2) : report_campaign_list_sort );
	var oldSortObj = $(\'link_list_sorter\' + oldSortId);
	var sortObj = $(\'link_list_sorter\' + newSortId);
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

function link_list_discern_sortclass() {
	if (link_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("link_list_sorter%s", report_campaign_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (report_campaign_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	link_list_sort_discerned = true;
}

function link_list_clear() {
	report_campaign_list_sort = "01";
	report_campaign_list_offset = "0";
	report_campaign_list_filter = "0";
	$("link_search").value = "";
	adesk_ui_anchor_set(report_campaign_list_anchor());
}
'; ?>
