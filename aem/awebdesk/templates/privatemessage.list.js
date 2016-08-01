{jsvar name=filterid var=$filterid}
{jsvar name=filterid_sent var=$filterid_sent}
var privatemessage_str_read = '{"Read"|alang|js}';
var privatemessage_str_unread = '{"Unread"|alang|js}';

{literal}
var privatemessage_table = new ACTable();
var privatemessage_list_sort = "0";
var privatemessage_list_offset = "0";
var privatemessage_list_filter = filterid;
var privatemessage_list_sort_discerned = false;

var setcol_counter = 0;

privatemessage_table.setcol(setcol_counter, function(row, td) {

	td = privatemessage_list_table_cell(row, td, false);

	return Builder.node("input", { type: "checkbox", name: "multi[]", value: row.id, onclick: "adesk_form_check_selection_none(this, $('acSelectAllCheckbox'), $('selectXPageAllBox'))" });
});
setcol_counter++;

/* Options links
privatemessage_table.setcol(setcol_counter, function(row) {
	var view = Builder.node("a", { href: sprintf("#form-%d", row.id) }, jsOptionView);
	var dele = Builder.node("a", { href: sprintf("#delete-%d", row.id) }, jsOptionDelete);
	var reply = Builder.node("a", { href: sprintf("#delete-%d", row.id) }, jsOptionReply);

	var ary = [];

	ary.push(view);
	ary.push(" ");

	if (adesk_js_admin.pg_privmsg_delete && $("privatemessage_filter").value == "Inbox") {
		ary.push(dele);
	}

	//ary.push(reply);

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});
setcol_counter++;
*/

privatemessage_table.setcol(setcol_counter, function(row, td) {

	td = privatemessage_list_table_cell(row, td, true);

	if (row.user_to == adesk_js_admin["id"]) {
		// Inbox view
		return row.user_from_moreinfo[0].first_name + " " + row.user_from_moreinfo[0].last_name + " (" + row.user_from_moreinfo[0].username + ")";
	}
	else {
		// Sent view
		return row.user_to_moreinfo[0].first_name + " " + row.user_to_moreinfo[0].last_name + " (" + row.user_to_moreinfo[0].username + ")";
	}

});
setcol_counter++;

privatemessage_table.setcol(setcol_counter, function(row, td) {

	td = privatemessage_list_table_cell(row, td, true);

	if (row.threadid > 0) {
		var thread_image = Builder.node("img", {src: "images/thread.gif", title: "Replied"});

		var span_class = "privatemessage_thread";
	}
	else {
		var thread_image = "";

		var span_class = "";
	}

	var subject_link = Builder.node("a", { href: sprintf("#form-%d", row.id) }, row.title);

	var title_display = Builder.node("span", { onmouseover: "adesk_tooltip_show('" + adesk_b64_encode( strip_tags(row.content) ) + "', 150, '', true)", onmouseout: "adesk_tooltip_hide()" }, subject_link);

	var ary = [];

	ary.push(thread_image);

	ary.push(title_display);

	return Builder.node("div", {}, ary);
});
setcol_counter++;

privatemessage_table.setcol(setcol_counter, function(row, td) {

	td = privatemessage_list_table_cell(row, td, true);

	return sql2date(row.cdate).format(adesk_js_site["datetimeformat"]);
});
setcol_counter++;

privatemessage_table.setcol(setcol_counter, function(row, td) {

	td = privatemessage_list_table_cell(row, td, true);

	// Hide "Status" column if user is viewing their own Inbox
	if ( $("privatemessage_filter").value == "user_to" ) {
		td.className = 'adesk_hidden';
		$("list_table_column_status").className = 'adesk_hidden';
	}
	else {
		td.className = '';
		$("list_table_column_status").className = '';
	}

	var ary = [];

	if (row.is_read) {

		var str_read = Builder.node("span", { style: "color: green; font-weight: bold;", onmouseover: "adesk_tooltip_show('" + adesk_b64_encode( sql2date(row.rdate).format(adesk_js_site["datetimeformat"]) ) + "', 150, '', true)", onmouseout: "adesk_tooltip_hide()" }, privatemessage_str_read);
		ary.push(str_read);
	}
	else {

		var str_unread = Builder.node("span", { style: "color: red; font-weight: bold;" }, privatemessage_str_unread);

		ary.push(str_unread);
	}

	return Builder.node("div", {}, ary);
});
setcol_counter++;

function privatemessage_list_anchor() {
	return sprintf("list-%s-%s-%s", privatemessage_list_sort, privatemessage_list_offset, privatemessage_list_filter);
}

function privatemessage_list_tabelize(rows, offset) {
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("list_table"));

		$("list_noresults").className = "adesk_table_rowgroup";

		$("list_delete_button").className = "adesk_hidden";
		$("loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		return;
	}

	$("list_noresults").className = "adesk_hidden";

	$("list_delete_button").className = "adesk_inline";
	$("loadingBar").className = "adesk_hidden";
	$("acSelectAllCheckbox").checked = false;
	$("selectXPageAllBox").className = 'adesk_hidden';

	adesk_paginator_tabelize(privatemessage_table, "list_table", rows, offset);
	$("loadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function privatemessage_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (privatemessage_list_filter != filterid && privatemessage_list_filter != filterid_sent)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	privatemessage_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(privatemessage_list_anchor());
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, privatemessage_list_sort, privatemessage_list_offset, this.limit, privatemessage_list_filter);

	$("list").className = "adesk_block";
}

function privatemessage_list_clear() {
	privatemessage_list_sort = "0";
	privatemessage_list_offset = "0";
	privatemessage_list_filter = filterid;
	$("list_search").value = "";
	privatemessage_search_defaults();
	adesk_ui_anchor_set(privatemessage_list_anchor());
}

function privatemessage_list_search() {
	var post = adesk_form_post($("list"));

	if (post.privatemessage_filter == "all") {
		privatemessage_list_clear();
	}
	else {
		adesk_ajax_post_cb("awebdeskapi.php", "privatemessage!adesk_privatemessage_filter_post", privatemessage_list_search_cb, post);
	}
}

function privatemessage_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml, null);

	privatemessage_list_filter = ary.filterid;

	adesk_ui_anchor_set(privatemessage_list_anchor());
}

function privatemessage_list_chsort(newSortId) {
	var oldSortId = ( privatemessage_list_sort.match(/D$/) ? privatemessage_list_sort.substr(0, 2) : privatemessage_list_sort );
	var oldSortObj = $('list_sorter' + oldSortId);
	var sortObj = $('list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( privatemessage_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = privatemessage_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = privatemessage_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old privatemessage_list_sort
		oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	privatemessage_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(privatemessage_list_anchor());
	return false;
}

function privatemessage_list_discern_sortclass() {
	if (privatemessage_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", privatemessage_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (privatemessage_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	privatemessage_list_sort_discerned = true;
}

{/literal}
