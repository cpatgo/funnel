<?php /* Smarty version 2.6.12, created on 2016-07-08 16:22:26
         compiled from loginsource.list.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'loginsource.list.js', 1, false),array('modifier', 'js', 'loginsource.list.js', 1, false),)), $this); ?>
var loginsource_list_str_makedefault = '<?php echo ((is_array($_tmp=((is_array($_tmp='Make default')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
<?php echo '
var loginsource_table = new ACTable();
var loginsource_list_sort = "02";
var loginsource_list_offset = "0";
var loginsource_list_filter = "0";
var loginsource_list_sort_discerned = false;
//
var loginsource_list_length = 0;

loginsource_table.setcol(0, function(row) {
	var edit = Builder.node("a", { href: sprintf("#form-%d", row.id) }, jsOptionEdit);

	var ary = [];

	if (ary.file != \'local.php\') {
		ary.push(edit);
	}

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

loginsource_table.setcol(1, function(row) {
	if (!row.enabled)
		return row.order;

	return Builder.node("span", [
		row.order,
		" ",
		row.order > 1 ? Builder.node("a", { href: "#", onclick: sprintf("loginsource_list_reorder(%d, \'u\'); return false", row.id) }, "up") : "",
		" ",
		row.order < loginsource_list_length ? Builder.node("a", { href: "#", onclick: sprintf("loginsource_list_reorder(%d, \'d\'); return false", row.id) }, "down") : "",
	]);
});

loginsource_table.setcol(2, function(row) {
	if (row.enabled)
		return row.ident;
	else
		return Builder.node("span", [
			Builder.node("strike", row.ident),
			" ",
			Builder.node("em", "(disabled)")
		]);
});
loginsource_table.setcol(3, function(row) {
	 
		return row.id;
	 
});

function loginsource_list_anchor() {
	return sprintf("list-%s-%s-%s", loginsource_list_sort, loginsource_list_offset, loginsource_list_filter);
}

function loginsource_list_tabelize(rows, offset) {
	loginsource_list_length = rows.length;
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("list_table"));

		$("list_noresults").className = "adesk_table_rowgroup";
		$("loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		return;
	}
	$("list_noresults").className = "adesk_hidden";
	adesk_paginator_tabelize(loginsource_table, "list_table", rows, offset);
	$("loadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function loginsource_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	loginsource_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(loginsource_list_anchor());
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, loginsource_list_sort, loginsource_list_offset, this.limit, loginsource_list_filter);

	$("list").className = "adesk_block";
}

function loginsource_list_chsort(newSortId) {
	var oldSortId = ( loginsource_list_sort.match(/D$/) ? loginsource_list_sort.substr(0, 2) : loginsource_list_sort );
	var oldSortObj = $(\'list_sorter\' + oldSortId);
	var sortObj = $(\'list_sorter\' + newSortId);
	// if sort column didn\'t change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( loginsource_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = loginsource_list_sort.substr(0, 2);
			sortObj.className = \'adesk_sort_asc\';
		} else {
			// was ASC
			newSortId = loginsource_list_sort + \'D\';
			sortObj.className = \'adesk_sort_desc\';
		}
	} else {
		// remove old loginsource_list_sort
		if ( oldSortObj ) oldSortObj.className = \'adesk_sort_other\';
		// set sort field
		sortObj.className = \'adesk_sort_asc\';
	}
	loginsource_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(loginsource_list_anchor());
	return false;
}

function loginsource_list_discern_sortclass() {
	if (loginsource_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", loginsource_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (loginsource_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	loginsource_list_sort_discerned = true;
}

function loginsource_list_reorder(id, dir) {
	adesk_ajax_call_cb("awebdeskapi.php", "loginsource!adesk_loginsource_reorder", adesk_ajax_cb(loginsource_list_reorder_cb), id, dir);
}

function loginsource_list_reorder_cb(ary) {
	paginators[1].paginate(loginsource_list_offset);
}

function loginsource_list_makedefault(id) {
	adesk_ajax_call_cb("awebdeskapi.php", "loginsource!adesk_loginsource_recognize", adesk_ajax_cb(loginsource_list_makedefault_cb), id, 1);
}

function loginsource_list_makedefault_cb(ary) {
	paginators[1].paginate(loginsource_list_offset);
}

'; ?>
