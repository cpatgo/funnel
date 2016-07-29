<?php /* Smarty version 2.6.12, created on 2016-07-28 11:03:35
         compiled from archive.campaign.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'archive.campaign.js', 3, false),)), $this); ?>
var archive_campaign_table = new ACTable();
var archive_list_sort = "06D";
var archive_list_filter = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['filterid']), $this);?>
;
var archive_list_stringid = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['list_stringid']), $this);?>
;

<?php echo '

archive_campaign_table.setcol(0, function(row) {
	var campaign_name = Builder.node("a", { href: row.url }, row.messagesubject);

	return campaign_name;
});

archive_campaign_table.setcol(1, function(row) {
	if(row.cdate != "")
		return sql2date(row.cdate).format(dateformat);
	else
		return Builder._text(jsNotAvailable);
});

function archive_campaign_tabelize(rows, offset) {

	if (rows.length > 0) {
		adesk_paginator_tabelize(archive_campaign_table, "archive_campaign_list", rows, offset);
	}
	else {
		$("archive_campaign_paginator").className = "adesk_hidden";
		$("archive_campaign_noresults").className = "";
	}

	/*
	adesk_dom_remove_children($("archive_campaign_list"));

	if (rows.length > 0) {
		var archive_html = "";

		for (var i = 0; i < rows.length; i++) {

			archive_html += "<tr class=\\"adesk_table_row\\">";

			archive_html += "<td><a href=\'" + rows[i].url + "\'>" + rows[i].messagesubject + "</a></td>";
			archive_html += "<td>" + sql2date(rows[i].cdate).format(dateformat) + "</td>";

			archive_html += "</tr>";

			$("archive_campaign_list").innerHTML = archive_html;
		}
	}
	else {
		$("archive_campaign_paginator").className = "adesk_hidden";
		$("archive_campaign_noresults").className = "";
	}
	*/
}

function archive_campaign_paginate(offset) {
//	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
//		adesk_ui_api_call(jsLoading);

	if (archive_list_filter > 0 && archive_list_filter != $("filterid").value)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	archive_list_offset = parseInt(offset, 10);

	//adesk_ui_anchor_set(archive_list_anchor());
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, archive_list_sort, archive_list_offset, 25, archive_list_filter, 1, archive_list_stringid);
}

function archive_campaign_search() {
	var post = adesk_form_post($("list"));

	post.status = new Array(1,2,3,4,5);
	post.public = 1;

	adesk_ajax_post_cb("awebdeskapi.php", "campaign.campaign_filter_post", archive_campaign_search_cb, post);
}

function archive_campaign_search_cb(xml) {
	var ary = adesk_dom_read_node(xml, null);

	archive_list_filter = ary.filterid;

	adesk_ui_anchor_set(archive_list_anchor());
}

function archive_campaign_clear() {
	archive_list_sort = "06D";
	archive_list_offset = "0";
	archive_list_filter = $("filterid").value;
	archive_search_defaults();
	$("archive_campaign_noresults").className = "adesk_hidden";
	adesk_ui_anchor_set(archive_list_anchor());
}

function archive_campaign_back() {
	var url = plink;
	if ( adesk_js_site.general_url_rewrite == 1 ) {
		url += \'/archive/?\';
	} else {
		url += \'/index.php?action=archive&\';
	}
	url += \'nl=0\';
	window.location.href = url;
}

'; ?>