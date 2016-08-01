var report_trend_client_list_outof = "{'Out of %s hits.'|alang|js}";

{literal}

report_trend_client_list_list_table.setcol(0, function(row, td) {
	if ( row.ua && row.ua != row.name ) {
		return Builder.node('div', { title: row.ua }, [ Builder._text(row.name) ]);
	}
	return row.name;
});

report_trend_client_list_list_table.setcol(1, function(row, td) {
	td.align = 'center';
	return row.hits;
});

report_trend_client_list_list_table.setcol(2, function(row, td) {
	td.align = 'center';
	return Builder.node('div', { title: sprintf(report_trend_client_list_outof, row.cnt) }, [ Builder._text(Math.round(parseFloat(row.perc) * 100) / 100 + '%') ]);
});

function report_trend_client_list_list_tabelize(rows, offset) {
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("list_table"));

		$("list_noresults").className = "adesk_block";
		$("loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		return;
	}
	$("list_noresults").className = "adesk_hidden";
	adesk_paginator_tabelize(report_trend_client_list_list_table, "list_table", rows, offset);
	$("loadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function report_trend_client_list_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (report_trend_client_list_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	report_trend_client_list_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(report_trend_client_list_list_anchor());
	$("loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, report_trend_client_list_list_sort, report_trend_client_list_list_offset, this.limit, report_trend_client_list_list_filter, report_trend_client_list_id);

	$("general").className = "adesk_block";
}

{/literal}
