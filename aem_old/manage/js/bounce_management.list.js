var bounce_management_list_none = '{"- No Bounce Management -"|alang|js}';
var bounce_management_list_pipe = '{"- PIPE -"|alang|js}';
var bounce_management_list_pop3 = '{"- POP3 -"|alang|js}';

var bounce_management_confirm_run = '{"Connection has been successfully established."|alang|js}\n'
	+ '{"Bounce Check will be opened in a separate window."|alang|js}\n\n'
	+ '{"Do you wish to continue?"|alang|js}';

var bounce_management_table = new ACTable();
var bounce_management_list_sort = "01";
var bounce_management_list_offset = "0";
var bounce_management_list_filter = {jsvar var=$filterid};
var bounce_management_list_sort_discerned = false;

{literal}
bounce_management_table.setcol(0, function(row) {
	if ( row.id != 1 ) {
		return Builder.node("input", { type: "checkbox", name: "multi[]", value: row.id, onclick: "adesk_form_check_selection_none(this, $('acSelectAllCheckbox'), $('selectXPageAllBox'))" });
	} else {
		return Builder._text(" ");
	}
});

bounce_management_table.setcol(1, function(row) {
	var edit = Builder.node("a", { href: sprintf("#form-%d", row.id) }, jsOptionEdit);
	var test = Builder.node("a", { href: sprintf("#test-%d", row.id), onclick: sprintf('return bounce_management_run(%d, 1);', row.id) }, jsOptionTest);
	var run  = Builder.node("a", { href: sprintf("#run-%d", row.id), onclick: sprintf('return bounce_management_run(%d, 0);', row.id) }, jsOptionRun);
	var log  = Builder.node("a", { href: sprintf("#log-%d", row.id) }, jsOptionLog);
	var dele = Builder.node("a", { href: sprintf("#delete-%d", row.id) }, jsOptionDelete);

	var ary = [];

	if (adesk_js_admin.pg_list_bounce) {
		ary.push(edit);
		ary.push(" ");
		if ( row.type == 'pop3' ) {
			ary.push(test);
			ary.push(" ");
			ary.push(run);
			ary.push(" ");
		}
		if ( row.type != 'none' ) {
			ary.push(log);
			ary.push(" ");
		}
	}

	if ( row.id != 1 ) {
		if (adesk_js_admin.pg_list_bounce) {
			ary.push(dele);
		}
	} else {
		ary.push(Builder.node("strong", [ Builder._text(jsDefault) ]));
	}

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

bounce_management_table.setcol(2, function(row) {
	if ( row.type == 'none' ) {
		var txt = Builder.node('em', [ Builder._text(bounce_management_list_none) ]);
	} else {
		var txt = Builder._text(row.email);
	}
	return txt;
});

bounce_management_table.setcol(3, function(row) {
	if ( row.type == 'none' ) {
		var txt = Builder.node('em', [ Builder._text(jsNotAvailable) ]);
	} else if ( row.type == 'pipe' ) {
		var txt = Builder.node('em', [ Builder._text(bounce_management_list_pipe) ]);
	} else {
		var txt = Builder._text(row.host);
	}
	return txt;
});

bounce_management_table.setcol(4, function(row) {
	if ( row.type == 'none' ) {
		var txt = Builder.node('em', [ Builder._text(jsNotAvailable) ]);
	} else if ( row.type == 'pipe' ) {
		var txt = Builder.node('em', [ Builder._text(bounce_management_list_pipe) ]);
	} else {
		var txt = Builder._text(row.user);
	}
	return txt;
});

bounce_management_table.setcol(5, function(row) {
	return Builder._text(parseInt(row.lists));
});

function bounce_management_list_anchor() {
	return sprintf("list-%s-%s-%s", bounce_management_list_sort, bounce_management_list_offset, bounce_management_list_filter);
}

function bounce_management_list_tabelize(rows, offset) {
	if (rows.length < 1) {
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
	adesk_paginator_tabelize(bounce_management_table, "list_table", rows, offset);
	$("loadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function bounce_management_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (bounce_management_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	bounce_management_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(bounce_management_list_anchor());
	$("loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, bounce_management_list_sort, bounce_management_list_offset, this.limit, bounce_management_list_filter);

	$("list").className = "adesk_block";
}

function bounce_management_list_clear() {
	bounce_management_list_sort = "01";
	bounce_management_list_offset = "0";
	bounce_management_list_filter = "0";
	bounce_management_listfilter = null;
	$("JSListManager").value = 0;
	$("list_search").value = "";
	list_filters_update(0, 0, true);
	bounce_management_search_defaults();
	adesk_ui_anchor_set(bounce_management_list_anchor());
}

function bounce_management_list_search() {
	var post = adesk_form_post($("list"));
	bounce_management_listfilter = post.listid;
	list_filters_update(0, post.listid, false);
	adesk_ajax_post_cb("awebdeskapi.php", "bounce_management.bounce_management_filter_post", bounce_management_list_search_cb, post);
}

function bounce_management_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	bounce_management_list_filter = ary.filterid;
	adesk_ui_anchor_set(bounce_management_list_anchor());
}

function bounce_management_list_chsort(newSortId) {
	var oldSortId = ( bounce_management_list_sort.match(/D$/) ? bounce_management_list_sort.substr(0, 2) : bounce_management_list_sort );
	var oldSortObj = $('list_sorter' + oldSortId);
	var sortObj = $('list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( bounce_management_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = bounce_management_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = bounce_management_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old bounce_management_list_sort
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	bounce_management_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(bounce_management_list_anchor());
	return false;
}

function bounce_management_list_discern_sortclass() {
	if (bounce_management_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", bounce_management_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (bounce_management_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	bounce_management_list_sort_discerned = true;
}


function bounce_management_run(id, isTest) {
	adesk_ui_api_call(jsWorking);
	adesk_ajax_call_cb("awebdeskapi.php", "bounce_management.bounce_management_run", bounce_management_run_cb, id, isTest);
	return false;
}

function bounce_management_run_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		if ( ary.istest == "0" ) {
			if ( confirm(bounce_management_confirm_run) ) {
				adesk_ui_openwindow('functions/crons/bounceparser.php?debug=1&id=' + ary.id);
			}
		}
	} else {
		adesk_error_show(ary.message);
	}
}

{/literal}
