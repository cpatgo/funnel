var user_list_str_edit = '{"Edit"|alang}';
var user_list_str_delete = '{"Delete"|alang}';
{literal}
var usertable = new ACTable();
var user_list_sort = "01";
var user_list_offset = "0";
var user_list_filter = "0";
var user_list_sort_discerned = false;

usertable.setcol(0, function(row) {
	if (row.id > 1)
		return Builder.node("input", { type: "checkbox", name: "multi[]", value: row.absid, onclick: "adesk_form_check_selection_none(this, $('acSelectAllCheckbox'), $('selectXPageAllBox'))" });
	else
		return Builder.node("span");
});

// Options
usertable.setcol(1, function(row) {
	var edit = " ";
	var dele = " ";

	edit = Builder.node("a", { href: sprintf("#form-%d", row.absid) }, user_list_str_edit);

	if (row.absid > 1 && row.absid != adesk_js_admin.id)
		dele = Builder.node("a", { href: sprintf("#delete-%d", row.absid) }, user_list_str_delete);

	// Check permissions

	var ary = [];

	if (typeof user_can_update == "function" && user_can_update()) {
		ary.push(edit);
		ary.push(" ");
	}

	if (typeof user_can_delete == "function" && user_can_delete())
		ary.push(dele);

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

// Username
usertable.setcol(2, function(row) {
	return row.username;
});

// Name
usertable.setcol(3, function(row) {
	return row.first_name + " " + row.last_name;
});

// Email
usertable.setcol(4, function(row) {
	return row.email;
});

usertable.setcol(5, function(row) {
	return row.groups;
});

function user_list_anchor() {
	return sprintf("list-%s-%s-%s", user_list_sort, user_list_offset, user_list_filter);
}

function user_list_tabelize(rows, offset, ary) {
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("list_table"));

		$("list_noresults").className = "adesk_table_rowgroup";
		adesk_ui_api_callback();
		return;
	}

	if ($("list_addspan")) {
		if (ary.adminsleft > 0)
			$("list_addspan").style.display = "";
		else
			$("list_addspan").style.display = "none";
	}

	$("list_noresults").className = "adesk_hidden";
	$("loadingBar").className = "adesk_hidden";
	$("acSelectAllCheckbox").checked = false;
	$("selectXPageAllBox").className = 'adesk_hidden';
	adesk_paginator_tabelize(usertable, "list_table", rows, offset);
}

function user_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (user_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	user_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(user_list_anchor());
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, user_list_sort, user_list_offset, user_list_filter);

	$("list").className = "adesk_block";
}

function user_list_clear() {
	user_list_sort = "01";
	user_list_offset = "0";
	user_list_filter = "0";
	$("list_search").value = "";
	user_search_defaults();
	adesk_ui_anchor_set(user_list_anchor());
}

function user_list_search() {
	var post = adesk_form_post($("list"));

	if ($("list_search_group").value > 0)
		post.groupid = $("list_search_group").value;

	if (typeof user_list_search_extended == "function")
		post = user_list_search_extended(post);

	adesk_ajax_post_cb("awebdeskapi.php", "user!adesk_user_filter_post", user_list_search_cb, post);
}

function user_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml, null);

	user_list_filter = ary.filterid;
	adesk_ui_anchor_set(user_list_anchor());
}

function user_list_chsort(newSortId) {
	var sortlen = user_list_sort.length;
	var oldSortId = ( user_list_sort.substr(sortlen-1, 1) == 'D' ? user_list_sort.substr(0, 2) : user_list_sort );
	var oldSortObj = $('list_sorter' + oldSortId);
	var sortObj = $('list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( user_list_sort.substr(sortlen-1, 1) == 'D' ) {
			// was DESC
			newSortId = user_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = user_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old user_list_sort
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	user_list_sort = newSortId;

	if (arguments.length < 2) {
		adesk_ui_api_call(jsSorting);
		adesk_ui_anchor_set(user_list_anchor());
	}

	return false;
}

function user_list_discern_sortclass() {
	if (user_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", user_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (user_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	user_list_sort_discerned = true;
}

function user_export() {
	$("export").style.display = "none";

	var url = window.location.href.replace(/#.*$/, "");
	url += sprintf("&export=1&filterid=%d&export_user=%s&export_name=%s&export_email=%s", user_list_filter, $("export_user").checked, $("export_name").checked, $("export_email").checked);

	window.location.href = url;
}

{/literal}
