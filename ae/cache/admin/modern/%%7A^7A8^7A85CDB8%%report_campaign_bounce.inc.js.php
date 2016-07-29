<?php /* Smarty version 2.6.12, created on 2016-07-08 14:48:21
         compiled from report_campaign_bounce.inc.js */ ?>
<?php echo '
var bounce_table = new ACTable();
var bounce_list_sort_discerned = false;

bounce_table.setcol(0, function(row) {
	if ( row.subscriberid > 0 ) {
		return Builder.node(\'a\', { href: \'desk.php?action=subscriber_view&id=\' + row.subscriberid }, [ Builder._text(row.email) ]);
	} else {
		return Builder.node(\'span\', {}, [ Builder._text(row.email) ]);
	}
});

bounce_table.setcol(1, function(row) {
	return sql2date(row.tstamp).format(datetimeformat);
});

bounce_table.setcol(2, function(row) {
	if (row.code == "9.1.1")
		return "-";
	else
		return row.code;
});

bounce_table.setcol(3, function(row) {
	return row.type;
});

bounce_table.setcol(4, function(row) {
	return Builder.node("span", { title: row.reason }, row.descript);
});

function bounce_totals() {
	adesk_ajax_call_cb("awebdeskapi.php?hash=" + report_campaign_list_hash, "bounce_data.bounce_data_select_totals", adesk_ajax_cb(bounce_totals_cb), report_campaign_id, $("messageid").value);
}

function bounce_totals_cb(ary) {
	var totalbounces = adesk_number_format(ary.totalbounces, ".", ",");
	var hardbounces = adesk_number_format(ary.hardbounces, ".", ",");
	var softbounces = adesk_number_format(ary.softbounces, ".", ",");
	$("bounce_total_t").innerHTML = totalbounces;
	$("bounce_hard_t").innerHTML  = hardbounces;
	$("bounce_soft_t").innerHTML  = softbounces;
	$("bounce_total_p").innerHTML = sprintf("(%.2f%%)", ary.total_amt > 0 ? 100 * ary.totalbounces / ary.total_amt : 0);
	$("bounce_hard_p").innerHTML  = sprintf("(%.2f%%)", ary.total_amt > 0 ? 100 * ary.hardbounces / ary.total_amt : 0);
	$("bounce_soft_p").innerHTML  = sprintf("(%.2f%%)", ary.total_amt > 0 ? 100 * ary.softbounces / ary.total_amt : 0);
}

function bounce_tabelize(rows, offset) {
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("bounce_table"));

		$("bounce_noresults").className = "adesk_block";
		$("bounce_loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		bounce_totals();
		return;
	}
	$("bounce_noresults").className = "adesk_hidden";
	window.t_rows = rows;
	adesk_paginator_tabelize(bounce_table, "bounce_table", rows, offset);
	$("bounce_loadingBar").className = "adesk_hidden";

	bounce_totals();
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function bounce_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (report_campaign_list_filter > 0)
		$("bounce_clear").style.display = "inline";
	else
		$("bounce_clear").style.display = "none";

	report_campaign_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(report_campaign_list_anchor());
	$("bounce_loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, report_campaign_list_sort, report_campaign_list_offset, this.limit, report_campaign_list_filter, report_campaign_id, $("messageid").value);

	$("bounce").className = "adesk_block";
}

function bounce_list_search() {
	var post = adesk_form_post($("bounce"));
	report_campaign_list_filter = post.listid;
	list_filters_update(0, post.listid, false);
	adesk_ajax_post_cb("awebdeskapi.php", "bounce_data.bounce_data_filter_post", bounce_list_search_cb, post);
}

function bounce_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	report_campaign_list_filter = ary.filterid;
	adesk_ui_anchor_set(report_campaign_list_anchor());
}

function bounce_list_chsort(newSortId) {
	var oldSortId = ( report_campaign_list_sort.match(/D$/) ? report_campaign_list_sort.substr(0, 2) : report_campaign_list_sort );
	var oldSortObj = $(\'bounce_list_sorter\' + oldSortId);
	var sortObj = $(\'bounce_list_sorter\' + newSortId);
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

function bounce_list_discern_sortclass() {
	if (bounce_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("bounce_list_sorter%s", report_campaign_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (report_campaign_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	bounce_list_sort_discerned = true;
}

function bounce_list_clear() {
	report_campaign_list_sort = "01";
	report_campaign_list_offset = "0";
	report_campaign_list_filter = "0";
	$("bounce_search").value = "";
	adesk_ui_anchor_set(report_campaign_list_anchor());
}
'; ?>
