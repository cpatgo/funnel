<?php /* Smarty version 2.6.12, created on 2016-07-08 14:47:32
         compiled from template.list.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'template.list.js', 4, false),array('modifier', 'alang', 'template.list.js', 7, false),array('modifier', 'js', 'template.list.js', 7, false),)), $this); ?>
var template_table = new ACTable();
var template_list_sort = "01";
var template_list_offset = "0";
var template_list_filter = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['filterid']), $this);?>
;
var template_list_sort_discerned = false;

var template_list_str_exportas = '<?php echo ((is_array($_tmp=((is_array($_tmp="Export As:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var template_txt_global = '<?php echo ((is_array($_tmp='Global')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
';

<?php echo '
template_table.setcol(0, function(row) {
	return Builder.node("input", { type: "checkbox", name: "multi[]", value: row.id, onclick: "adesk_form_check_selection_none(this, $(\'acSelectAllCheckbox\'), $(\'selectXPageAllBox\'))" });
});

template_table.setcol(1, function(row) {
	var edit = Builder.node("a", { href: sprintf("#form-%d", row.id) }, jsOptionEdit);
	var dele = Builder.node("a", { href: sprintf("#delete-%d", row.id) }, jsOptionDelete);
	var exprt = Builder.node("a", { href: "#", onclick: sprintf("return template_list_export(%d);", row.id) }, jsOptionExport);

	var ary = [];
	var nodes = [ ];

	if (adesk_js_admin.pg_template_edit) {
		ary.push(edit);
		ary.push(" ");
	}

	ary.push(exprt);
	ary.push(" ");

	if (adesk_js_admin.pg_template_delete) {
		ary.push(dele);
	}

	nodes.push(Builder._text(template_list_str_exportas));
	nodes.push(Builder.node(\'br\'));
	nodes.push(Builder.node(\'a\', { href: "export.php?action=template&type=xml&id=" + row.id }, [ Builder._text(strImportTypeXML) ] ));
	nodes.push(" · ");
	nodes.push(Builder.node(\'a\', { href: "export.php?action=template&type=html&id=" + row.id }, [ Builder._text(strImportTypeHTML) ] ));
	ary.push(Builder.node(\'div\', { id: \'template_export\' + row.id, className: \'adesk_hidden\' }, nodes ));

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

template_table.setcol(2, function(row) {
	// name
	return row.name;
});

template_table.setcol(3, function(row) {
	// lists
	return (row.is_global) ? Builder.node(\'em\', { style: \'font-style: italic;\' }, template_txt_global) : row.lists;
});

function template_list_anchor() {
	return sprintf("list-%s-%s-%s", template_list_sort, template_list_offset, template_list_filter);
}

function template_list_tabelize(rows, offset) {
	if (rows.length < 1) {
		/*
		if (!template_list_filter || template_list_filter == 0) {
			adesk_ui_api_callback();
			adesk_ui_anchor_set(\'form-0\');
			return;
		}
		*/
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
	adesk_paginator_tabelize(template_table, "list_table", rows, offset);

	$("template_list_count").innerHTML = " (" + adesk_number_format(paginators[1].total, decimalDelim, commaDelim) + ")";
	$("template_list_count").className = "adesk_inline";

	$("loadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function template_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (template_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	template_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(template_list_anchor());
	$("loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, template_list_sort, template_list_offset, this.limit, template_list_filter);

	$("list").className = "adesk_block";
}

function template_list_clear() {
	template_list_sort = "01";
	template_list_offset = "0";
	template_list_filter = "0";
	template_listfilter = null;
	$("JSListManager").value = 0;
	$("list_search").value = "";
	list_filters_update(0, 0, true);
	template_search_defaults();
	adesk_ui_anchor_set(template_list_anchor());
}

function template_list_search() {
	var post = adesk_form_post($("list"));
	template_listfilter = post.listid;
	list_filters_update(0, post.listid, false);
	adesk_ajax_post_cb("awebdeskapi.php", "template.template_filter_post", template_list_search_cb, post);
}

function template_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	template_list_filter = ary.filterid;
	adesk_ui_anchor_set(template_list_anchor());
}

function template_list_chsort(newSortId) {
	var oldSortId = ( template_list_sort.match(/D$/) ? template_list_sort.substr(0, 2) : template_list_sort );
	var oldSortObj = $(\'list_sorter\' + oldSortId);
	var sortObj = $(\'list_sorter\' + newSortId);
	// if sort column didn\'t change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( template_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = template_list_sort.substr(0, 2);
			sortObj.className = \'adesk_sort_asc\';
		} else {
			// was ASC
			newSortId = template_list_sort + \'D\';
			sortObj.className = \'adesk_sort_desc\';
		}
	} else {
		// remove old template_list_sort
		if ( oldSortObj ) oldSortObj.className = \'adesk_sort_other\';
		// set sort field
		sortObj.className = \'adesk_sort_asc\';
	}
	template_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(template_list_anchor());
	return false;
}

function template_list_discern_sortclass() {
	if (template_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", template_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (template_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	template_list_sort_discerned = true;
}


function template_list_export(id) {
	adesk_dom_toggle_class(\'template_export\' + id, \'adesk_offer\', \'adesk_hidden\');
	return false;
}

'; ?>
