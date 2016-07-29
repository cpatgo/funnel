<?php /* Smarty version 2.6.12, created on 2016-07-28 11:05:46
         compiled from exclusion.list.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'exclusion.list.js', 4, false),array('modifier', 'alang', 'exclusion.list.js', 6, false),array('modifier', 'js', 'exclusion.list.js', 6, false),)), $this); ?>
var exclusion_table = new ACTable();
var exclusion_list_sort = "01";
var exclusion_list_offset = "0";
var exclusion_list_filter = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['filterid']), $this);?>
;
var exclusion_list_sort_discerned = false;
var exclusion_list_str_newlist = '<?php echo ((is_array($_tmp=((is_array($_tmp='New List')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

<?php echo '
exclusion_table.setcol(0, function(row) {
	return Builder.node("input", { type: "checkbox", name: "multi[]", value: row.id, onclick: "adesk_form_check_selection_none(this, $(\'acSelectAllCheckbox\'), $(\'selectXPageAllBox\'))" });
});

exclusion_table.setcol(1, function(row) {
	var edit = Builder.node("a", { href: sprintf("#form-%d", row.id) }, jsOptionEdit);
	var dele = Builder.node("a", { href: sprintf("#delete-%d", row.id) }, jsOptionDelete);

	var ary = [];

	if (adesk_js_admin.pg_list_edit) {
		if (adesk_js_admin.id == 1 || parseInt(row.listid, 10) != 0) {
			ary.push(edit);
			ary.push(" ");
		}
	}

	if (adesk_js_admin.pg_list_edit) {
		ary.push(dele);
	}

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

exclusion_table.setcol(2, function(row) {
	return Builder._text(row.pattern);
});

exclusion_table.setcol(3, function(row) {
	return Builder._text(row.lists);
});

function exclusion_list_anchor() {
	return sprintf("list-%s-%s-%s", exclusion_list_sort, exclusion_list_offset, exclusion_list_filter);
}

function exclusion_list_tabelize(rows, offset) {
	if (rows.length < 1) {
		if (!exclusion_list_filter || exclusion_list_filter == 0) {
			adesk_ui_api_callback();
			adesk_ui_anchor_set(\'form-0\');
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
	adesk_paginator_tabelize(exclusion_table, "list_table", rows, offset);

	$("exclusion_list_count").innerHTML = " (" + adesk_number_format(paginators[1].total, decimalDelim, commaDelim) + ")";
	$("exclusion_list_count").className = "adesk_inline";

	$("loadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function exclusion_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (exclusion_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	exclusion_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(exclusion_list_anchor());
	$("loadingBar").className = "adesk_block";

	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, exclusion_list_sort, exclusion_list_offset, this.limit, exclusion_list_filter);

	$("list").className = "adesk_block";
}

function exclusion_list_clear() {
	exclusion_list_sort = "01";
	exclusion_list_offset = "0";
	exclusion_list_filter = "0";
	exclusion_listfilter = null;
	$("JSListManager").value = 0;
	$("list_search").value = "";
	list_filters_update(0, 0, true);
	exclusion_search_defaults();
	adesk_ui_anchor_set(exclusion_list_anchor());
}

function exclusion_list_search() {
	var post = adesk_form_post($("list"));
	exclusion_listfilter = post.listid;
	list_filters_update(0, post.listid, false);
	adesk_ajax_post_cb("awebdeskapi.php", "exclusion.exclusion_filter_post", exclusion_list_search_cb, post);
}

function exclusion_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	exclusion_list_filter = ary.filterid;
	adesk_ui_anchor_set(exclusion_list_anchor());
}

function exclusion_list_chsort(newSortId) {
	var oldSortId = ( exclusion_list_sort.match(/D$/) ? exclusion_list_sort.substr(0, 2) : exclusion_list_sort );
	var oldSortObj = $(\'list_sorter\' + oldSortId);
	var sortObj = $(\'list_sorter\' + newSortId);
	// if sort column didn\'t change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( exclusion_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = exclusion_list_sort.substr(0, 2);
			sortObj.className = \'adesk_sort_asc\';
		} else {
			// was ASC
			newSortId = exclusion_list_sort + \'D\';
			sortObj.className = \'adesk_sort_desc\';
		}
	} else {
		// remove old exclusion_list_sort
		if ( oldSortObj ) oldSortObj.className = \'adesk_sort_other\';
		// set sort field
		sortObj.className = \'adesk_sort_asc\';
	}
	exclusion_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(exclusion_list_anchor());
	return false;
}

function exclusion_list_discern_sortclass() {
	if (exclusion_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", exclusion_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (exclusion_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	exclusion_list_sort_discerned = true;
}

function exclusion_list_export() {
	if (parseInt(exclusion_list_filter, 10) > 0) {
		if ($("list_export_newlist") === null)
			$("list_export_type").appendChild(Builder.node("option", { id: "list_export_newlist", value: "newlist" }, exclusion_list_str_newlist));
	} else {
		if ($("list_export_newlist") !== null)
			$("list_export_type").removeChild($("list_export_newlist"));
	}

	$("list_export_type").value = "csv";
	var show = $(\'exportOffer\').className == \'adesk_hidden\';
	adesk_dom_toggle_class(\'exportOffer\', \'adesk_block\', \'adesk_hidden\');
	// if showing, then populate the offer
	if ( !show ) return false;
	// show "all pages" link only if more than one page
	if ( paginators[1].linksCnt == 1 ) {
		$(\'exportOfferWhat\').value = \'page\';
		$(\'exportOfferAllPages\').className = \'adesk_hidden\';
	} else {
		$(\'exportOfferAllPages\').className = \'\';
	}
	//var rel = $(\'exportFields\');
	//adesk_dom_remove_children(rel);
	if ( $(\'JSListManager\').value != 0 ) {
		//alert(\'2do: fetch list info and grab list fields\');
	}
}

function exclusion_list_export_build() {
	var post = adesk_form_post($("exportOffer"));
	post.filter = exclusion_list_filter;
	post.offset = exclusion_list_offset;
	post.limit  = paginators[1].limit;

	var fieldtmp = [];

	// Figure out which custom fields to show.
	$A(document.getElementsByTagName("input")).each(function(inp) {
			if (inp.type == "checkbox" && inp.name == "fields[]" && inp.checked)
				fieldtmp.push(inp.value);
		});

	post.fields = fieldtmp.join(",");
	export_link_build(\'exclusion\', post);
}

function exclusion_list_exportformat(val) {
	if (val == "newlist") {
		exclusion_list_export();
		if (parseInt(exclusion_list_filter, 10) > 0)
			window.location.href = \'#exportlist-\' + exclusion_list_filter.toString();

		$("list_export_type").value = "csv";
	}
}

'; ?>
