var report_list_str_group_limit = "{'%s per %s'|alang|js}";
{literal}
var report_list_str_group_limit_types = {
{/literal}
	day: "{'%s per day'|alang|js}",
	week: "{'%s per week'|alang|js}",
	month: "{'%s per month'|alang|js}",
	month1st: "{'%s per calendar month (counting from the 1st)'|alang|js}",
	monthcdate: "{'%s per calendar month (counting from the user\'s creation date)'|alang|js}",
	year: "{'%s per year'|alang|js}",
	ever: "{'%s total'|alang|js}"
{literal}
};


report_list_table.setcol(0, function(row) {
	return Builder.node('a', { href: 'desk.php?action=report_list&id=' + row.id }, [ Builder._text(row.name) ]
	);
});

report_list_table.setcol(1, function(row, td) {
	td.align = 'center';
	return ( row.subscribed ? row.subscribed : Builder.node('em', [ Builder._text('0') ]) );
});

report_list_table.setcol(2, function(row, td) {
	td.align = 'center';
	return ( row.unconfirmed ? row.unconfirmed : Builder.node('em', [ Builder._text('0') ]) );
});

report_list_table.setcol(3, function(row, td) {
	td.align = 'center';
	return ( row.unsubscribed ? row.unsubscribed : Builder.node('em', [ Builder._text('0') ]) );
});

report_list_table.setcol(4, function(row, td) {
	td.align = 'center';
	return ( row.bounced ? row.bounced : Builder.node('em', [ Builder._text('0') ]) );
});
/*
report_list_table.setcol(5, function(row, td) {
	td.align = 'center';
	return ( row.campaigns ? row.campaigns : Builder.node('em', [ Builder._text('0') ]) );
});
*/
report_list_table.setcol(5, function(row, td) {
	td.align = 'center';
	return ( row.emails ? row.emails : Builder.node('em', [ Builder._text('0') ]) );
});

report_list_table.setcol(6, function(row, td) {
	td.align = 'center';
	return ( row.epd ? ( Math.round(row.epd * 100) / 100 ) : Builder.node('em', [ Builder._text('0.00') ]) );
});

function report_list_list_tabelize(rows, offset) {
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("list_table"));

		$("list_noresults").className = "adesk_block";
		$("loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		return;
	}
	$("list_noresults").className = "adesk_hidden";
	adesk_paginator_tabelize(report_list_table, "list_table", rows, offset);
	$("loadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function report_list_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (report_list_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	report_list_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(report_list_list_anchor());
	$("loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, report_list_list_sort, report_list_list_offset, this.limit, report_list_list_filter, report_list_id);

	$("general").className = "adesk_block";
}

{/literal}
