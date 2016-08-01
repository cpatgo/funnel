{literal}
report_user_table.setcol(0, function(row) {
	return Builder.node(
		'div',
		{ title: row.email },
		[
			Builder._text(row.username + ' - ' + row.first_name + ' ' + row.last_name)
		]
	);
});

report_user_table.setcol(1, function(row, td) {
	td.align = 'center';
	return ( row.campaigns ? row.campaigns : Builder.node('em', [ Builder._text('0') ]) );
});

report_user_table.setcol(2, function(row, td) {
	td.align = 'center';
	return ( row.emails ? row.emails : Builder.node('em', [ Builder._text('0') ]) );
});

report_user_table.setcol(3, function(row, td) {
	td.align = 'center';
	return ( row.epd ? ( Math.round(row.epd * 100) / 100 ) : Builder.node('em', [ Builder._text('0.00') ]) );
});

function report_user_list_tabelize(rows, offset) {
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("list_table"));

		$("list_noresults").className = "adesk_block";
		$("loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		return;
	}
	$("list_noresults").className = "adesk_hidden";
	adesk_paginator_tabelize(report_user_table, "list_table", rows, offset);
	$("loadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function report_user_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (report_user_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	report_user_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(report_user_list_anchor());
	$("loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, report_user_list_sort, report_user_list_offset, this.limit, report_user_list_filter, report_user_id);

	$("general").className = "adesk_block";
}

{/literal}
