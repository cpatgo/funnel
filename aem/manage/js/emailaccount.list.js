var emailaccount_list_pipe = '{"- PIPE -"|alang|js}';
var emailaccount_list_pop3 = '{"- POP3 -"|alang|js}';
var emailaccount_list_sub = '{"Subscribe"|alang|js}';
var emailaccount_list_unsub = '{"Unsubscribe"|alang|js}';

var emailaccount_confirm_run = '{"Connection has been successfully established."|alang|js}\n'
	+ '{"Email Check will be opened in a separate window."|alang|js}\n\n'
	+ '{"Do you wish to continue?"|alang|js}';

var emailaccount_table = new ACTable();
var emailaccount_list_sort = "02";
var emailaccount_list_offset = "0";
var emailaccount_list_filter = {jsvar var=$filterid};
var emailaccount_list_sort_discerned = false;

{literal}
emailaccount_table.addcol(function(row) {
	return Builder.node("input", { type: "checkbox", name: "multi[]", value: row.id, onclick: "adesk_form_check_selection_none(this, $('acSelectAllCheckbox'), $('selectXPageAllBox'))" });
});

emailaccount_table.addcol(function(row) {
	var edit = Builder.node("a", { href: sprintf("#form-%d", row.id) }, jsOptionEdit);
	var test = Builder.node("a", { href: sprintf("#test-%d", row.id), onclick: sprintf('return emailaccount_run(%d, 1);', row.id) }, jsOptionTest);
	var run  = Builder.node("a", { href: sprintf("#run-%d", row.id), onclick: sprintf('return emailaccount_run(%d, 0);', row.id) }, jsOptionRun);
	var log  = Builder.node("a", { href: sprintf("#log-%d", row.id) }, jsOptionLog);
	var dele = Builder.node("a", { href: sprintf("#delete-%d", row.id) }, jsOptionDelete);

	var ary = [];

	if (adesk_js_admin.pg_list_edit) {
		ary.push(edit);
		ary.push(" ");
		if ( row.type == 'pop3' ) {
			ary.push(test);
			ary.push(" ");
			ary.push(run);
			ary.push(" ");
		}
		ary.push(log);
		ary.push(" ");
	}

	if (adesk_js_admin.pg_list_edit) {
		ary.push(dele);
	}

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

emailaccount_table.addcol(function(row) {
	return Builder._text( row.action == 'unsub' ? emailaccount_list_unsub : emailaccount_list_sub );
});

emailaccount_table.addcol(function(row) {
	return txt = Builder._text(row.email);
});
{/literal}

{if !$__ishosted}

{literal}
emailaccount_table.addcol(function(row) {
	if ( row.type == 'pipe' ) {
		var txt = Builder.node('em', [ Builder._text(emailaccount_list_pipe) ]);
	} else {
		var txt = Builder._text(row.host);
	}
	return txt;
});

emailaccount_table.addcol(function(row) {
	if ( row.type == 'pipe' ) {
		var txt = Builder.node('em', [ Builder._text(emailaccount_list_pipe) ]);
	} else {
		var txt = Builder._text(row.user);
	}
	return txt;
});
{/literal}

{/if}

{literal}
emailaccount_table.addcol(function(row) {
	return Builder._text(parseInt(row.lists));
});

function emailaccount_list_anchor() {
	return sprintf("list-%s-%s-%s", emailaccount_list_sort, emailaccount_list_offset, emailaccount_list_filter);
}

function emailaccount_list_tabelize(rows, offset) {
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("list_table"));

		$("list_noresults").className = "adesk_block";
		if($("list_delete_button")) $("list_delete_button").className = "adesk_hidden";
		$("loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		return;
	}
	$("list_noresults").className = "adesk_hidden";
	if($("list_delete_button")) $("list_delete_button").className = "adesk_inline";
	adesk_paginator_tabelize(emailaccount_table, "list_table", rows, offset);
	$("loadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function emailaccount_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (emailaccount_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	emailaccount_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(emailaccount_list_anchor());
	$("loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, emailaccount_list_sort, emailaccount_list_offset, this.limit, emailaccount_list_filter);

	$("list").className = "adesk_block";
}

function emailaccount_list_clear() {
	emailaccount_list_sort = "02";
	emailaccount_list_offset = "0";
	emailaccount_list_filter = "0";
	emailaccount_listfilter = null;
	$("JSListManager").value = 0;
	$("list_search").value = "";
	list_filters_update(0, 0, true);
	emailaccount_search_defaults();
	adesk_ui_anchor_set(emailaccount_list_anchor());
}

function emailaccount_list_search() {
	var post = adesk_form_post($("list"));
	emailaccount_listfilter = post.listid;
	list_filters_update(0, post.listid, false);
	adesk_ajax_post_cb("awebdeskapi.php", "emailaccount.emailaccount_filter_post", emailaccount_list_search_cb, post);
}

function emailaccount_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	emailaccount_list_filter = ary.filterid;
	adesk_ui_anchor_set(emailaccount_list_anchor());
}

function emailaccount_list_chsort(newSortId) {
	var oldSortId = ( emailaccount_list_sort.match(/D$/) ? emailaccount_list_sort.substr(0, 2) : emailaccount_list_sort );
	var oldSortObj = $('list_sorter' + oldSortId);
	var sortObj = $('list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( emailaccount_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = emailaccount_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = emailaccount_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old emailaccount_list_sort
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	emailaccount_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(emailaccount_list_anchor());
	return false;
}

function emailaccount_list_discern_sortclass() {
	if (emailaccount_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", emailaccount_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (emailaccount_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	emailaccount_list_sort_discerned = true;
}


function emailaccount_run(id, isTest) {
	// Increase delay to 60 seconds, to account for timeouts when processing POP accounts.
	adesk_ui_api_call(jsWorking, 60);
	adesk_ajax_call_cb("awebdeskapi.php", "emailaccount.emailaccount_run", emailaccount_run_cb, id, isTest);
	return false;
}

function emailaccount_run_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		if ( ary.istest == "0" ) {
			if ( confirm(emailaccount_confirm_run) ) {
				adesk_ui_openwindow('functions/crons/emailparser.php?debug=1&id=' + ary.id);
			}
		}
	} else {
		adesk_error_show(ary.message);
	}
}

{/literal}
