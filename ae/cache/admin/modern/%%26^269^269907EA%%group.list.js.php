<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:45
         compiled from group.list.js */ ?>
<?php echo '
var group_table = new ACTable();
var group_list_sort = "01";
var group_list_offset = "0";
var group_list_filter = "0";
var group_list_sort_discerned = false;

group_table.setcol(0, function(row) {
	if (row.id > 3)
		return Builder.node("input", { type: "checkbox", name: "multi[]", value: row.id, onclick: "adesk_form_check_selection_none(this, $(\'acSelectAllCheckbox\'), $(\'selectXPageAllBox\'))" });
	else
		return Builder.node("span");
});

group_table.setcol(1, function(row) {
	var edit = Builder.node("a", { href: sprintf("#form-%d", row.id) }, jsEdit);
	var dele = Builder.node("a", { href: sprintf("#delete-%d", row.id) }, jsDelete);

	// Check permissions

	var ary = [];

	if (typeof group_can_update == "function" && group_can_update()) {
		ary.push(edit);
		ary.push(" ");
	}

	if ( typeof adesk_group_row_options == \'function\' ) ary = adesk_group_row_options(ary, row);

	if (typeof group_can_delete == "function" && group_can_delete() && row.id > 3)
		ary.push(dele);

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

group_table.setcol(2, function(row) {
	if (typeof row.a_istrial != "undefined")
		row.title += " " + row.a_istrial;
	return row.title;
});

function group_list_anchor() {
	return sprintf("list-%s-%s-%s", group_list_sort, group_list_offset, group_list_filter);
}

function group_list_tabelize(rows, offset) {
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("list_table"));

		$("list_noresults").className = "adesk_table_rowgroup";
		adesk_ui_api_callback();
		return;
	}
	$("list_noresults").className = "adesk_hidden";
	$("loadingBar").className = "adesk_hidden";
	$("acSelectAllCheckbox").checked = false;
	$("selectXPageAllBox").className = \'adesk_hidden\';
	adesk_paginator_tabelize(group_table, "list_table", rows, offset);

	// We also need to fill up the list of potential groups to move users to when
	// deleting

	adesk_dom_remove_children($("delete_alt"));
	$("delete_alt").appendChild(Builder.node("option", { value: "0" }, jsNoGroup));

	for (var i = 0; i < rows.length; i++)
		$("delete_alt").appendChild(Builder.node("option", { value: rows[i].id }, rows[i].title));

	$("delete_alt").getElementsByTagName("option")[0].selected = true;
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function group_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (group_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	group_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(group_list_anchor());
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, group_list_sort, group_list_offset, group_list_filter);

	$("list").className = "adesk_block";
}

function group_list_clear() {
	group_list_sort = "01";
	group_list_offset = "0";
	group_list_filter = "0";
	$("list_search").value = "";
	group_search_defaults();
	adesk_ui_anchor_set(group_list_anchor());
}

function group_list_search() {
	var post = adesk_form_post($("list"));
	adesk_ajax_post_cb("awebdeskapi.php", "group!adesk_group_filter_post", group_list_search_cb, post);
}

function group_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml, null);

	group_list_filter = ary.filterid;
	adesk_ui_anchor_set(group_list_anchor());
}

function group_list_chsort(newSortId) {
	var sortlen = group_list_sort.length;
	var oldSortId = ( group_list_sort.substr(sortlen-1, 1) == \'D\' ? group_list_sort.substr(0, 2) : group_list_sort );
	var oldSortObj = $(\'list_sorter\' + oldSortId);
	var sortObj = $(\'list_sorter\' + newSortId);
	// if sort column didn\'t change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( group_list_sort.substr(sortlen-1, 1) == \'D\' ) {
			// was DESC
			newSortId = group_list_sort.substr(0, 2);
			sortObj.className = \'adesk_sort_asc\';
		} else {
			// was ASC
			newSortId = group_list_sort + \'D\';
			sortObj.className = \'adesk_sort_desc\';
		}
	} else {
		// remove old group_list_sort
		if ( oldSortObj ) oldSortObj.className = \'adesk_sort_other\';
		// set sort field
		sortObj.className = \'adesk_sort_asc\';
	}
	group_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(group_list_anchor());
	return false;
}

function group_list_discern_sortclass() {
	if (group_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", group_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (group_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	group_list_sort_discerned = true;
}

'; ?>
