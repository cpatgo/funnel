var form_table = new ACTable();
var form_list_sort = "01";
var form_list_offset = "0";
var form_list_filter = {jsvar var=$filterid};
var form_list_sort_discerned = false;

{literal}
form_table.setcol(0, function(row) {
	if (parseInt(row.id, 10) != 1000) {
		return Builder.node("input", { type: "checkbox", name: "multi[]", value: row.id, onclick: "adesk_form_check_selection_none(this, $('acSelectAllCheckbox'), $('selectXPageAllBox'))" });
	}
	else {
		return "";
	}
});

form_table.setcol(1, function(row) {
	var edit = Builder.node("a", { href: sprintf("#form-%d", row.id) }, jsOptionEdit);
	var dele = Builder.node("a", { href: sprintf("#delete-%d", row.id) }, jsOptionDelete);
	var view = Builder.node("a", { href: sprintf("#view-%d", row.id) }, jsOptionView);

	var ary = [];

	if (adesk_js_admin.pg_form_edit) {
		ary.push(edit);
		ary.push(" ");
	}

	if (parseInt(row.id, 10) != 1000) {
		ary.push(view);
		ary.push(" ");

		if (adesk_js_admin.pg_form_delete) {
			ary.push(dele);
		}
	}

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

form_table.setcol(2, function(row) {
	// name
	if (parseInt(row.id, 10) == 1000) {
		return Builder.node("strong", [ Builder._text(row.name) ]);
	}
	else {
		return row.name;
	}
});

form_table.setcol(3, function(row) {
	// lists
	if (parseInt(row.id, 10) != 1000) {
		return row.lists;
	}
	else {
		return "-";
	}
});

function form_list_anchor() {
	return sprintf("list-%s-%s-%s", form_list_sort, form_list_offset, form_list_filter);
}

function form_list_tabelize(rows, offset) {
	if (rows.length < 1) {
		if (!form_list_filter || form_list_filter == 0) {
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
	adesk_paginator_tabelize(form_table, "list_table", rows, offset);

	$("form_list_count").innerHTML = " (" + adesk_number_format(paginators[1].total, decimalDelim, commaDelim) + ")";
	$("form_list_count").className = "adesk_inline";
	if ( $('selectXPageAllBox') ) {
		var spans = $('selectXPageAllBox').getElementsByTagName('span');
		if ( spans.length > 2 ) {
			spans[2].innerHTML = adesk_number_format(paginators[1].total, decimalDelim, commaDelim);
		}
	}

	$("loadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function form_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (form_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	form_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(form_list_anchor());
	$("loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, form_list_sort, form_list_offset, this.limit, form_list_filter);

	$("list").className = "adesk_block";
}

function form_list_clear() {
	form_list_sort = "01";
	form_list_offset = "0";
	form_list_filter = "0";
	form_listfilter = null;
	$("JSListManager").value = 0;
	$("list_search").value = "";
	list_filters_update(0, 0, true);
	form_search_defaults();
	adesk_ui_anchor_set(form_list_anchor());
}

function form_list_search() {
	var post = adesk_form_post($("list"));
	form_listfilter = post.listid;
	list_filters_update(0, post.listid, false);
	adesk_ajax_post_cb("awebdeskapi.php", "form.form_filter_post", form_list_search_cb, post);
}

function form_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	form_list_filter = ary.filterid;
	adesk_ui_anchor_set(form_list_anchor());
}

function form_list_chsort(newSortId) {
	var oldSortId = ( form_list_sort.match(/D$/) ? form_list_sort.substr(0, 2) : form_list_sort );
	var oldSortObj = $('list_sorter' + oldSortId);
	var sortObj = $('list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( form_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = form_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = form_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old form_list_sort
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	form_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(form_list_anchor());
	return false;
}

function form_list_discern_sortclass() {
	if (form_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", form_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (form_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	form_list_sort_discerned = true;
}

// cycle between "Other options" divs
function form_list_other_cycle(section) {
	var available = [ 'api', 'advanced' ];
	if (adesk_js_site.general_public) available.push('public');
	// show the chosen one
	$('form_list_other_' + section).show();
	$('form_list_other_li_' + section).className = 'currenttab';
	for (var i = 0; i < available.length; i++) {
		// hide the rest
		if (available[i] != section) {
			$('form_list_other_' + available[i]).hide();
			$('form_list_other_li_' + available[i]).className = 'othertab';
		}
	}
}

function form_list_other_api_load(filename) {
	adesk_ui_api_call(jsLoading);
	adesk_ajax_call_cb("awebdeskapi.php", "form.form_list_other_api_load", form_list_other_api_load_cb, filename);
	$('form_list_other_api_filename').innerHTML = filename;
}

function form_list_other_api_load_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.row) {
		var content = ary.row;
		adesk_dom_remove_children( $('form_list_other_api_content_div') );
		var textarea = Builder.node(
			'textarea',
			{
				id: 'form_list_other_api_content',
				className: 'brush: php',
				style: 'height: 600px; width: 100%;',
				wrap: 'off'
			},
			''
		);
		$('form_list_other_api_content_div').appendChild(textarea);
		var content_node = document.createTextNode(content);
		$('form_list_other_api_content').appendChild(content_node);
		SyntaxHighlighter.highlight();
	}
}

{/literal}
