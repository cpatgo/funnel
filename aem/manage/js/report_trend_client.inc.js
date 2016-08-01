{literal}

report_trend_client_table.setcol(0, function(row, td) {
	return Builder.node('a', { href: 'desk.php?action=report_trend_client_list&id=' + row.id }, [ Builder._text(row.name) ]);
});

report_trend_client_table.setcol(1, function(row, td) {
	//td.align = 'center';
	if(row.tstamp != "")
		return sql2date(row.tstamp).format(datetimeformat);
	else
		return Builder._text(jsNotAvailable);
});

report_trend_client_table.setcol(2, function(row, td) {
	td.align = 'center';
	var clienthits = parseInt(row.bestclienthits, 10);
	if ( isNaN(clienthits) || clienthits == 0 ) return Builder.node('em', [ Builder._text(jsNotAvailable) ]);
	// we have some hits
	//var clientua = ( row.bestclientua ? row.bestclientua : jsUnknown );
	var clientname = ( row.bestclient ? row.bestclient : jsUnknown );
	var cn = ( row.bestclient ? adesk_str_shorten(clientname, 30) : jsUnknown );
	if ( cn != clientname ) {
		return Builder.node('div', { title: clientname }, [ Builder._text(cn) ]);
	}
	return clientname;
});

report_trend_client_table.setcol(3, function(row, td) {
	td.align = 'center';
	return ( row.uniqueopens ? row.uniqueopens : Builder.node('em', [ Builder._text('0') ]) );
});

function report_trend_client_list_tabelize(rows, offset) {
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("list_table"));

		$("list_noresults").className = "adesk_block";
		$("loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		return;
	}
	$("list_noresults").className = "adesk_hidden";
	adesk_paginator_tabelize(report_trend_client_table, "list_table", rows, offset);
	$("loadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function report_trend_client_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (report_trend_client_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	report_trend_client_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(report_trend_client_list_anchor());
	$("loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, report_trend_client_list_sort, report_trend_client_list_offset, this.limit, report_trend_client_list_filter);

	$("general").className = "adesk_block";
}

{/literal}
