<?php /* Smarty version 2.6.12, created on 2016-07-08 14:48:21
         compiled from report_campaign_unopen.inc.js */ ?>
<?php echo '
var unopen_table = new ACTable();
var unopen_list_sort_discerned = false;

unopen_table.setcol(0, function(row) {
	if ( row.subscriberid > 0 && row.email != \'twitter\' ) {
		return Builder.node(\'a\', { href: \'desk.php?action=subscriber_view&id=\' + row.subscriberid + \'#log-03D-0-0\' }, [ Builder._text(row.email) ]);
	} else {
		return Builder.node(\'span\', {}, [ Builder._text(row.email) ]);
	}
});

unopen_table.setcol(1, function(row) {
	return "-";
});

function unopen_tabelize(rows, offset) {
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("unopen_table"));

		$("unopen_noresults").className = "adesk_block";
		$("unopen_loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		open_totals();
		return;
	}
	$("unopen_noresults").className = "adesk_hidden";
	adesk_paginator_tabelize(unopen_table, "unopen_table", rows, offset);
	$("unopen_loadingBar").className = "adesk_hidden";

	open_totals();
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function unopen_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (report_campaign_list_filter > 0)
		$("unopen_clear").style.display = "inline";
	else
		$("unopen_clear").style.display = "none";

	report_campaign_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(report_campaign_list_anchor());
	$("unopen_loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, report_campaign_list_sort, report_campaign_list_offset, this.limit, report_campaign_list_filter, report_campaign_id);

	$("unopen").className = "adesk_block";
}

function unopen_list_search() {
	var post = adesk_form_post($("open"));
	report_campaign_list_filter = post.listid;
	list_filters_update(0, post.listid, false);
	adesk_ajax_post_cb("awebdeskapi.php", "unopen.unopen_filter_post", unopen_list_search_cb, post);
}

function unopen_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	report_campaign_list_filter = ary.filterid;
	adesk_ui_anchor_set(report_campaign_list_anchor());
}

function unopen_list_chsort(newSortId) {
	var oldSortId = ( report_campaign_list_sort.match(/D$/) ? report_campaign_list_sort.substr(0, 2) : report_campaign_list_sort );
	var oldSortObj = $(\'unopen_list_sorter\' + oldSortId);
	var sortObj = $(\'unopen_list_sorter\' + newSortId);
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

function unopen_list_discern_sortclass() {
	if (unopen_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("unopen_list_sorter%s", report_campaign_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (report_campaign_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	unopen_list_sort_discerned = true;
}

function unopen_list_clear() {
	report_campaign_list_sort = "01";
	report_campaign_list_offset = "0";
	report_campaign_list_filter = "0";
	$("unopen_search").value = "";
	adesk_ui_anchor_set(report_campaign_list_anchor());
}
'; ?>
