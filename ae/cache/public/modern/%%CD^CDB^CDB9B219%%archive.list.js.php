<?php /* Smarty version 2.6.12, created on 2016-07-20 15:27:10
         compiled from archive.list.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'archive.list.js', 3, false),)), $this); ?>
var archive_list_table = new ACTable();
var archive_list_sort = "01";
var archive_list_filter = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['filterid']), $this);?>
;

<?php echo '

archive_list_table.setcol(0, function(row) {
	var list_name = Builder.node("a", { href: row.url }, row.name);

	return list_name;
});

archive_list_table.setcol(1, function(row) {
	return row.campaigns;
});

function archive_list_tabelize(rows, offset) {

	if (rows.length > 0) {
		adesk_paginator_tabelize(archive_list_table, "archive_list_list", rows, offset);
	}
	else {
		$("archive_list_paginator").className = "adesk_hidden";
		$("archive_list_noresults").className = "";
	}

	/*
	adesk_dom_remove_children($("archive_list_list"));

	var archive_html = "";

	for (var i = 0; i < rows.length; i++) {

		archive_html += "<tr class=\\"adesk_table_row\\">";

		archive_html += "<td><a href=\'" + rows[i].url + "\'>" + rows[i].name + "</a></td>";
		archive_html += "<td>" + rows[i].campaigns + "</td>";

		archive_html += "</tr>";
	}

	$("archive_list_list").innerHTML = archive_html;
	*/
}

function archive_list_paginate(offset) {
//	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
//		adesk_ui_api_call(jsLoading);

	if (archive_list_filter > 0 && archive_list_filter != $("filterid").value)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	archive_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(archive_list_anchor());
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, archive_list_sort, archive_list_offset, 25, archive_list_filter);
}

function archive_list_search() {
	var post = adesk_form_post($("list"));

	post.private = 0;

	adesk_ajax_post_cb("awebdeskapi.php", "list.list_filter_post", archive_list_search_cb, post);
}

function archive_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml, null);

	archive_list_filter = ary.filterid;

	adesk_ui_anchor_set(archive_list_anchor());
}

function archive_list_clear() {
	archive_list_sort = "01";
	archive_list_offset = "0";
	archive_list_filter = $("filterid").value;
	archive_search_defaults();
	$("archive_list_noresults").className = "adesk_hidden";
	adesk_ui_anchor_set(archive_list_anchor());
}

'; ?>