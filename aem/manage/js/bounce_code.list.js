var bounce_code_list_str_type_hard = '{"Hard"|alang|js}';
var bounce_code_list_str_type_soft = '{"Soft"|alang|js}';
{literal}
var bounce_code_table = new ACTable();
var bounce_code_list_sort = "01";
var bounce_code_list_offset = "0";
var bounce_code_list_filter = "0";
var bounce_code_list_sort_discerned = false;

bounce_code_table.setcol(0, function(row) {
	return Builder.node("input", { type: "checkbox", name: "multi[]", value: row.id, onclick: "adesk_form_check_selection_none(this, $('acSelectAllCheckbox'), $('selectXPageAllBox'))" });
});

bounce_code_table.setcol(1, function(row) {
	var edit = Builder.node("a", { href: sprintf("#form-%d", row.id) }, jsOptionEdit);
	var dele = Builder.node("a", { href: sprintf("#delete-%d", row.id) }, jsOptionDelete);

	var ary = [];

	if (adesk_js_admin.id == 1) {
		ary.push(edit);
		ary.push(" ");
	}

	if (adesk_js_admin.id == 1) {
		ary.push(dele);
	}

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

bounce_code_table.setcol(2, function(row) {
	return row.code;
});

bounce_code_table.setcol(3, function(row) {
	return row.match;
});

bounce_code_table.setcol(4, function(row) {
	return ( row.type == 'hard' ? bounce_code_list_str_type_hard : bounce_code_list_str_type_soft );
});

bounce_code_table.setcol(5, function(row) {
	return row.descript;
});

function bounce_code_list_anchor() {
	return sprintf("list-%s-%s-%s", bounce_code_list_sort, bounce_code_list_offset, bounce_code_list_filter);
}

function bounce_code_list_tabelize(rows, offset) {
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
	adesk_paginator_tabelize(bounce_code_table, "list_table", rows, offset);
	$("loadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function bounce_code_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (bounce_code_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	bounce_code_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(bounce_code_list_anchor());
	$("loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, bounce_code_list_sort, bounce_code_list_offset, this.limit, bounce_code_list_filter);

	$("list").className = "adesk_block";
}

function bounce_code_list_clear() {
	bounce_code_list_sort = "01";
	bounce_code_list_offset = "0";
	bounce_code_list_filter = "0";
	$("list_search").value = "";
	bounce_code_search_defaults();
	adesk_ui_anchor_set(bounce_code_list_anchor());
}

function bounce_code_list_search() {
	var post = adesk_form_post($("list"));
	adesk_ajax_post_cb("awebdeskapi.php", "bounce_code.bounce_code_filter_post", bounce_code_list_search_cb, post);
}

function bounce_code_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	bounce_code_list_filter = ary.filterid;
	adesk_ui_anchor_set(bounce_code_list_anchor());
}

function bounce_code_list_chsort(newSortId) {
	var oldSortId = ( bounce_code_list_sort.match(/D$/) ? bounce_code_list_sort.substr(0, 2) : bounce_code_list_sort );
	var oldSortObj = $('list_sorter' + oldSortId);
	var sortObj = $('list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( bounce_code_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = bounce_code_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = bounce_code_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old bounce_code_list_sort
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	bounce_code_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(bounce_code_list_anchor());
	return false;
}

function bounce_code_list_discern_sortclass() {
	if (bounce_code_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", bounce_code_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (bounce_code_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	bounce_code_list_sort_discerned = true;
}

{/literal}
