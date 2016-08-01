var cron_list_str_confirm_run = '{"Are you sure you wish to run this Cron Job?"|alang|js}';
var cron_list_str_confirm_enable = '{"Are you sure you wish to enable this Cron Job?"|alang|js}';
var cron_list_str_confirm_disable = '{"Are you sure you wish to disable this Cron Job?"|alang|js}';

var cron_table = new ACTable();
var cron_list_sort = "01";
var cron_list_offset = "0";
var cron_list_filter = "0";
var cron_list_sort_discerned = false;
var cron_protected = {jsvar var=$cron_protected};

{literal}
cron_table.setcol(0, function(row, td) {
	td.className = ( row.active == 1 ? '' : 'adesk_cronlist_disabled' );
	return Builder.node("input", { type: "checkbox", name: "multi[]", value: row.id, onclick: "adesk_form_check_selection_none(this, $('acSelectAllCheckbox'), $('selectXPageAllBox'))" });
});

cron_table.setcol(1, function(row, td) {
	td.className = ( row.active == 1 ? '' : 'adesk_cronlist_disabled' );
	var edit = Builder.node("a", { href: sprintf("#form-%d", row.id) }, jsOptionEdit);
	var dele = Builder.node("a", { href: sprintf("#delete-%d", row.id) }, jsOptionDelete);
	var run  = Builder.node("a", { href: '#', onclick: "return cron_list_run(" + row.id + ");" }, jsOptionRun);
	var log  = Builder.node("a", { href: sprintf("#log-%d", row.id) }, jsOptionLog);
	var dsbl = Builder.node("a", { href: '#', onclick: "return cron_list_status(" + row.id + ", 0);" }, jsOptionDisable);
	var enbl = Builder.node("a", { href: '#', onclick: "return cron_list_status(" + row.id + ", 1);" }, jsOptionEnable);

	var ary = [];

	if (adesk_js_admin.id == 1) {
		ary.push(edit);
		ary.push(" ");
	}

	if (adesk_js_admin.id == 1) {
		if ( row.active != 1 ) {
			ary.push(enbl);
		} else {
			ary.push(run);
			ary.push(" ");
			ary.push(dsbl);
		}
		ary.push(" ");
		ary.push(log);
		ary.push(" ");
	}

	if (adesk_js_admin.id == 1 && row.id > cron_protected) {
		ary.push(dele);
	}

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

cron_table.setcol(2, function(row, td) {
	td.className = ( row.active == 1 ? '' : 'adesk_cronlist_disabled' );
	return Builder.node("div", { title: row.line }, [ Builder._text(row.name) ]);
});

cron_table.setcol(3, function(row, td) {
	td.className = ( row.active == 1 ? '' : 'adesk_cronlist_disabled' );
	return Builder.node("div", { title: row.command }, [ Builder._text(row.descript ? row.descript : jsNotAvailable) ]);
});

cron_table.setcol(4, function(row, td) {
	td.className = ( row.active == 1 ? '' : 'adesk_cronlist_disabled' );
	return Builder._text(row.lastrun ? sql2date(row.lastrun).format(datetimeformat) : jsNotAvailable);
});

function cron_list_anchor() {
	return sprintf("list-%s-%s-%s", cron_list_sort, cron_list_offset, cron_list_filter);
}

function cron_list_tabelize(rows, offset) {
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
	$("loadingBar").className = "adesk_hidden";
	$("acSelectAllCheckbox").checked = false;
	$("selectXPageAllBox").className = 'adesk_hidden';
	adesk_paginator_tabelize(cron_table, "list_table", rows, offset);
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function cron_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (cron_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	cron_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(cron_list_anchor());
	$("loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, cron_list_sort, cron_list_offset, this.limit, cron_list_filter);

	$("list").className = "adesk_block";
}

function cron_list_clear() {
	cron_list_sort = "01";
	cron_list_offset = "0";
	cron_list_filter = "0";
	$("list_search").value = "";
	cron_search_defaults();
	adesk_ui_anchor_set(cron_list_anchor());
}

function cron_list_search() {
	var post = adesk_form_post($("list"));
	adesk_ajax_post_cb("awebdeskapi.php", "cron!adesk_cron_filter_post", cron_list_search_cb, post);
}

function cron_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	cron_list_filter = ary.filterid;
	adesk_ui_anchor_set(cron_list_anchor());
}

function cron_list_chsort(newSortId) {
	var oldSortId = ( cron_list_sort.match(/D$/) ? cron_list_sort.substr(0, 2) : cron_list_sort );
	var oldSortObj = $('list_sorter' + oldSortId);
	var sortObj = $('list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( cron_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = cron_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = cron_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old cron_list_sort
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	cron_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(cron_list_anchor());
	return false;
}

function cron_list_discern_sortclass() {
	if (cron_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", cron_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (cron_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	cron_list_sort_discerned = true;
}


function cron_list_status(id, active) {
	if ( active == 1 ) {
		if ( !confirm(cron_list_str_confirm_enable) ) return false;
	} else {
		if ( !confirm(cron_list_str_confirm_disable) ) return false;
	}
	adesk_ui_api_call( active == 1 ? jsEnabling : jsDisabling );
	adesk_ajax_call_cb("awebdeskapi.php", "cron!adesk_cron_status", cron_list_trigger_cb, id, active);
	return false;
}

function cron_list_run(id) {
	if ( !confirm(cron_list_str_confirm_run) ) return false;
	adesk_ui_api_call(jsStarting);
	adesk_ajax_call_cb("awebdeskapi.php", "cron!adesk_cron_run", cron_list_trigger_cb, id);
	return false;
}

function cron_list_trigger_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		// rebuild this list
		paginators[1].paginate(cron_list_offset);
	} else {
		adesk_error_show(ary.message);
	}
}

{/literal}
