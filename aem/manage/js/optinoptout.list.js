var optinoptout_table = new ACTable();
var optinoptout_list_sort = "01";
var optinoptout_list_offset = "0";
var optinoptout_list_filter = {jsvar var=$filterid};
var optinoptout_list_sort_discerned = false;

var optinoptout_list_attachments_str = '{"Attachments:"|alang|js}';

{literal}
optinoptout_table.setcol(0, function(row) {
	if ( row.id != 1 ) {
		return Builder.node("input", { type: "checkbox", name: "multi[]", value: row.id, onclick: "adesk_form_check_selection_none(this, $('acSelectAllCheckbox'), $('selectXPageAllBox'))" });
	} else {
		return Builder._text(" ");
	}
});

optinoptout_table.setcol(1, function(row) {
	var edit = Builder.node("a", { href: sprintf("#form-%d", row.id) }, jsOptionEdit);
	var dele = Builder.node("a", { href: sprintf("#delete-%d", row.id) }, jsOptionDelete);

	var ary = [];

	if (adesk_js_admin.pg_list_edit) {
		ary.push(edit);
		ary.push(" ");
	}

	if ( row.id != 1 ) {
		if (adesk_js_admin.pg_list_edit) {
			ary.push(dele);
		}
	} else {
		ary.push(Builder.node("strong", [ Builder._text(jsDefault) ]));
	}

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

optinoptout_table.setcol(2, function(row) {
	if ( row.name != '' ) {
		return Builder._text(row.name);
	} else {
		return Builder.node('em', [ Builder._text(jsNotAvailable) ]);
	}
});

optinoptout_table.setcol(3, function(row) {
	if ( row.optin_files && row.optin_files.length > 0 ) {
		// "attachment" tooltip
		var msg = optinoptout_list_attachments_str + '<br />';
		for ( var i = 0; i < row.optin_files.length; i++ ) {
			msg += row.optin_files[i].name + ' (' + row.optin_files[i].humansize + ')<br />';
		}
		return Builder.node(
			'img',
			{
				src: 'images/document_attachment.png',
				onmouseout: "adesk_tooltip_hide();",
				onmouseover: "adesk_tooltip_show('" + adesk_b64_encode(msg) + "', 150, '', true);"
			}
		);
	} else {
		return Builder._text(' ');
	}
});

optinoptout_table.setcol(4, function(row) {
	if ( row.optin_confirm == 1 ) {
		// "from" tooltip
		var tooltip = { style: "width: 100%;" };
		if ( row.optin_from_email != '' ) {
			var msg = row.optin_from_email;
			if ( row.optin_from_name != '' ) msg = '"' + row.optin_from_name + '" <' + msg + '>';
			tooltip.onmouseout = "adesk_tooltip_hide();";
			tooltip.onmouseover = "adesk_tooltip_show('" + msg + "', 200);";
		}
		return Builder.node("div", tooltip, [ Builder._text(row.optin_subject) ]);
	} else {
		return Builder.node('em', [ Builder._text(jsNone) ]);
	}
});

optinoptout_table.setcol(5, function(row) {
	if ( row.optout_files && row.optout_files.length > 0 ) {
		// "attachment" tooltip
		var msg = 'Attachments:' + '<br />';
		for ( var i = 0; i < row.optout_files.length; i++ ) {
			msg += row.optout_files[i].name + ' (' + row.optout_files[i].humansize + ')<br />';
		}
		return Builder.node(
			'img',
			{
				src: 'images/document_attachment.png',
				onmouseout: "adesk_tooltip_hide();",
				onmouseover: "adesk_tooltip_show('" + adesk_b64_encode(msg) + "', 150, '', true);"
			}
		);
	} else {
		return Builder._text(' ');
	}
});

optinoptout_table.setcol(6, function(row) {
	if ( row.optout_confirm == 1 ) {
		// "from" tooltip
		var tooltip = { style: "width: 100%;" };
		if ( row.optout_from_email != '' ) {
			var msg = row.optout_from_email;
			if ( row.optout_from_name != '' ) msg = '"' + row.optout_from_name + '" <' + msg + '>';
			tooltip.onmouseout = "adesk_tooltip_hide();";
			tooltip.onmouseover = "adesk_tooltip_show('" + msg + "', 200);";
		}
		return Builder.node("div", tooltip, [ Builder._text(row.optout_subject) ]);
	} else {
		return Builder.node('em', [ Builder._text(jsNone) ]);
	}
});

optinoptout_table.setcol(7, function(row) {
	return Builder._text(parseInt(row.lists));
});

function optinoptout_list_anchor() {
	return sprintf("list-%s-%s-%s", optinoptout_list_sort, optinoptout_list_offset, optinoptout_list_filter);
}

function optinoptout_list_tabelize(rows, offset) {
	if (rows.length < 1) {
		if (!optinoptout_list_filter || optinoptout_list_filter == 0) {
			adesk_ui_api_callback();
			adesk_ui_anchor_set('form-0');
			return;
		}
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("list_table"));

		$("list_noresults").className = "adesk_block";
		if ($("list_delete_button") !== null)
			$("list_delete_button").className = "adesk_hidden";
		$("loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		return;
	}
	$("list_noresults").className = "adesk_hidden";
	if ($("list_delete_button") !== null)
		$("list_delete_button").className = "adesk_inline";
	adesk_paginator_tabelize(optinoptout_table, "list_table", rows, offset);

	$("optinoptout_list_count").innerHTML = " (" + adesk_number_format(paginators[1].total, decimalDelim, commaDelim) + ")";
	$("optinoptout_list_count").className = "adesk_inline";
	if ( $('selectXPageAllBox') ) {
		var spans = $('selectXPageAllBox').getElementsByTagName('span');
		if ( spans.length > 2 ) {
			spans[2].innerHTML = adesk_number_format(paginators[1].total, decimalDelim, commaDelim);
		}
	}

	$("loadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function optinoptout_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (optinoptout_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	optinoptout_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(optinoptout_list_anchor());
	$("loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, optinoptout_list_sort, optinoptout_list_offset, this.limit, optinoptout_list_filter);

	$("list").className = "adesk_block";
}

function optinoptout_list_clear() {
	optinoptout_list_sort = "01";
	optinoptout_list_offset = "0";
	optinoptout_list_filter = "0";
	optinoptout_listfilter = null;
	$("JSListManager").value = 0;
	$("list_search").value = "";
	list_filters_update(0, 0, true);
	optinoptout_search_defaults();
	adesk_ui_anchor_set(optinoptout_list_anchor());
}

function optinoptout_list_search() {
	var post = adesk_form_post($("list"));
	optinoptout_listfilter = post.listid;
	list_filters_update(0, post.listid, false);
	adesk_ajax_post_cb("awebdeskapi.php", "optinoptout.optinoptout_filter_post", optinoptout_list_search_cb, post);
}

function optinoptout_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	optinoptout_list_filter = ary.filterid;
	adesk_ui_anchor_set(optinoptout_list_anchor());
}

function optinoptout_list_chsort(newSortId) {
	var oldSortId = ( optinoptout_list_sort.match(/D$/) ? optinoptout_list_sort.substr(0, 2) : optinoptout_list_sort );
	var oldSortObj = $('list_sorter' + oldSortId);
	var sortObj = $('list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( optinoptout_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = optinoptout_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = optinoptout_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old optinoptout_list_sort
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	optinoptout_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(optinoptout_list_anchor());
	return false;
}

function optinoptout_list_discern_sortclass() {
	if (optinoptout_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", optinoptout_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (optinoptout_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	optinoptout_list_sort_discerned = true;
}

{/literal}
