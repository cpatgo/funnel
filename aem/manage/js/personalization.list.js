var personalization_table = new ACTable();
var personalization_list_sort = "02";
var personalization_list_offset = "0";
var personalization_list_filter = {jsvar var=$filterid};
var personalization_list_sort_discerned = false;

{literal}
personalization_table.setcol(0, function(row) {
	return Builder.node("input", { type: "checkbox", name: "multi[]", value: row.id, onclick: "adesk_form_check_selection_none(this, $('acSelectAllCheckbox'), $('selectXPageAllBox'))" });
});

personalization_table.setcol(1, function(row) {
	var edit = Builder.node("a", { href: sprintf("#form-%d", row.id) }, jsOptionEdit);
	var dele = Builder.node("a", { href: sprintf("#delete-%d", row.id) }, jsOptionDelete);

	var ary = [];

	if (adesk_js_admin.pg_template_edit) {
		ary.push(edit);
		ary.push(" ");
	}

	if (adesk_js_admin.pg_template_delete) {
		ary.push(dele);
	}

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

personalization_table.setcol(2, function(row) {
	// name
	return '%' + row.tag + '%';
});

personalization_table.setcol(3, function(row) {
	// name
	return row.name;
});

personalization_table.setcol(4, function(row) {
	// format
	return row.format;
});

personalization_table.setcol(5, function(row) {
	// lists
	return row.lists;
});

function personalization_list_anchor() {
	return sprintf("list-%s-%s-%s", personalization_list_sort, personalization_list_offset, personalization_list_filter);
}

function personalization_list_tabelize(rows, offset) {
	if (rows.length < 1) {
		if (!personalization_list_filter || personalization_list_filter == 0) {
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
	adesk_paginator_tabelize(personalization_table, "list_table", rows, offset);
	$("loadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function personalization_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (personalization_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	personalization_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(personalization_list_anchor());
	$("loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, personalization_list_sort, personalization_list_offset, this.limit, personalization_list_filter);

	$("list").className = "adesk_block";
}

function personalization_list_clear() {
	personalization_list_sort = "02";
	personalization_list_offset = "0";
	personalization_list_filter = "0";
	personalization_listfilter = null;
	$("JSListManager").value = 0;
	$("list_search").value = "";
	list_filters_update(0, 0, true);
	personalization_search_defaults();
	adesk_ui_anchor_set(personalization_list_anchor());
}

function personalization_list_search() {
	var post = adesk_form_post($("list"));
	personalization_listfilter = post.listid;
	list_filters_update(0, post.listid, false);
	adesk_ajax_post_cb("awebdeskapi.php", "personalization.personalization_filter_post", personalization_list_search_cb, post);
}

function personalization_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	personalization_list_filter = ary.filterid;
	adesk_ui_anchor_set(personalization_list_anchor());
}

function personalization_list_chsort(newSortId) {
	var oldSortId = ( personalization_list_sort.match(/D$/) ? personalization_list_sort.substr(0, 2) : personalization_list_sort );
	var oldSortObj = $('list_sorter' + oldSortId);
	var sortObj = $('list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( personalization_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = personalization_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = personalization_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old personalization_list_sort
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	personalization_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(personalization_list_anchor());
	return false;
}

function personalization_list_discern_sortclass() {
	if (personalization_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", personalization_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (personalization_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	personalization_list_sort_discerned = true;
}


function personalization_list_export(id) {
	adesk_dom_toggle_class('personalization_export' + id, 'adesk_offer', 'adesk_hidden');
	return false;
}

{/literal}
