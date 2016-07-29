<?php /* Smarty version 2.6.12, created on 2016-07-08 16:21:25
         compiled from service.list.js */ ?>
<?php echo '
var service_table = new ACTable();
var service_list_sort = "0";
var service_list_offset = "0";
var service_list_filter = "0";
var service_list_sort_discerned = false;

service_table.setcol(0, function(row) {
	var edit = Builder.node("a", { href: sprintf("#form-%d", row.id) }, jsOptionEdit);
	var dele = Builder.node("a", { href: sprintf("#delete-%d", row.id) }, jsOptionDelete);

	var ary = [];

	//if (adesk_js_admin.__CAN_EDIT__) {
		ary.push(edit);
		ary.push(" ");
	//}

	//if (adesk_js_admin.__CAN_DELETE__) {
		//ary.push(dele);
	//}

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

service_table.setcol(1, function(row) {
		return row.name;
});

service_table.setcol(2, function(row) {
	return row.description;
});

function service_list_anchor() {
	return sprintf("list-%s-%s-%s", service_list_sort, service_list_offset, service_list_filter);
}

function service_list_tabelize(rows, offset) {
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("list_table"));

		$("list_noresults").className = "adesk_block";
		/*
		if ($("list_delete_button") !== null)
			$("list_delete_button").className = "adesk_hidden";
		*/
		$("loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		return;
	}
	$("list_noresults").className = "adesk_hidden";
	/*
	if ($("list_delete_button") !== null)
		$("list_delete_button").className = "adesk_inline";
	$("list_delete_button").className = "adesk_inline";
	*/
	$("loadingBar").className = "adesk_hidden";
	//$("acSelectAllCheckbox").checked = false;
	$("selectXPageAllBox").className = "adesk_hidden";
	adesk_paginator_tabelize(service_table, "list_table", rows, offset);
	$("loadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function service_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	/*
	if (service_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";
	*/

	service_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(service_list_anchor());
	$("loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, service_list_sort, service_list_offset, this.limit, service_list_filter);

	$("list").className = "adesk_block";
}

function service_list_chsort(newSortId) {
	var oldSortId = ( service_list_sort.match(/D$/) ? service_list_sort.substr(0, 2) : service_list_sort );
	var oldSortObj = $(\'list_sorter\' + oldSortId);
	var sortObj = $(\'list_sorter\' + newSortId);
	// if sort column didn\'t change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( service_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = service_list_sort.substr(0, 2);
			sortObj.className = \'adesk_sort_asc\';
		} else {
			// was ASC
			newSortId = service_list_sort + \'D\';
			sortObj.className = \'adesk_sort_desc\';
		}
	} else {
		// remove old service_list_sort
		if ( oldSortObj ) oldSortObj.className = \'adesk_sort_other\';
		// set sort field
		sortObj.className = \'adesk_sort_asc\';
	}
	service_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(service_list_anchor());
	return false;
}

function service_list_discern_sortclass() {
	if (service_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", service_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (service_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	service_list_sort_discerned = true;
}

'; ?>
