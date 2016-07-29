<?php /* Smarty version 2.6.12, created on 2016-07-08 14:15:46
         compiled from subscriber.list.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'subscriber.list.js', 4, false),array('modifier', 'default', 'subscriber.list.js', 7, false),array('modifier', 'alang', 'subscriber.list.js', 9, false),array('modifier', 'js', 'subscriber.list.js', 9, false),)), $this); ?>
var subscriber_table = new ACTable();
var subscriber_list_sort = "01";
var subscriber_list_offset = "0";
var subscriber_list_filter = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['filterid']), $this);?>
;
var subscriber_list_list_filter = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['listfilter']), $this);?>
;
var subscriber_list_sort_discerned = false;
var subscriber_list_segment = <?php echo ((is_array($_tmp=@$_GET['filterid'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
;

var subscriber_list_str_newlist = '<?php echo ((is_array($_tmp=((is_array($_tmp='New List')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_list_str_optin_resend = '<?php echo ((is_array($_tmp=((is_array($_tmp='Send Email Reminder')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

<?php echo smarty_function_jsvar(array('name' => 'subscriber_fields','var' => $this->_tpl_vars['fields']), $this);?>


<?php echo '
subscriber_table.setcol(0, function(row) {
	return Builder.node("input", { type: "checkbox", name: "multi[]", value: row.id, onclick: "adesk_form_check_selection_none(this, $(\'acSelectAllCheckbox\'), $(\'selectXPageAllBox\'))" });
});

subscriber_table.setcol(1, function(row) {
	var edit = Builder.node("a", { href: sprintf("#form-%d", row.id) }, jsOptionEdit);
	var view = Builder.node("a", { href: sprintf("desk.php?action=subscriber_view&id=%d#log-03D-0-0", row.id) }, jsOptionView);
	var dele = Builder.node("a", { href: sprintf("#delete-%d", row.id) }, jsOptionDelete);
	var optn = Builder.node("a", { href: sprintf("#optin-%d", row.id) }, subscriber_list_str_optin_resend);

	var ary = [];

	var rowbounced = ( typeof row.status != \'undefined\' && row.status == 3 );

	ary.push(view);
	ary.push(" ");

	if (subscriber_canadd && $(\'JSStatusManager\').value == \'0\' && $(\'optin_optid\').getElementsByTagName(\'option\').length > 0 ) {
		ary.push(optn);
		ary.push(" ");
	}

	if (adesk_js_admin.pg_subscriber_delete) {
		ary.push(dele);
	}

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

subscriber_table.setcol(2, function(row, td) {
	//td.className = ( subscriber_dimmed(row.status, \'list\') ) ? "subscriber_dimmed" : "";
	return row.email;
});

subscriber_table.setcol(3, function(row, td) {
	//td.className = ( subscriber_dimmed(row.status, \'list\') ) ? "subscriber_dimmed" : "";
	var name = row.first_name;
	if ( row.first_name != \'\' && row.last_name != \'\' ) name += \' \';
	name += row.last_name;
	return name;
});

subscriber_table.setcol(4, function(row, td) {
	//td.className = ( subscriber_dimmed(row.status, \'list\') ) ? "subscriber_dimmed" : "";
	var val = row.cdate;
	if ($(\'JSListManager\').value != 0) {
		if (typeof row.status != "undefined" && typeof row.sdate != "undefined" && typeof row.udate != "undefined") {
			if (row.status != 2)
				val = row.sdate;
			else
				val = row.udate;
		}
	}
	return ( val ? sql2date(val).format(dateformat) : jsNotAvailable );
});

function subscriber_dimmed(row_status, view) {
	if (view == \'list\') {
		if (
				 // List Filter is filtered; Status filter is set to "All Subscribers"; and the individual row\'s status is 0 (unconfirmed)
			   ($("JSListManager").value != "0" && $("JSStatusManager").value == "" && row_status == "0") ||

			   // OR... Status filter is set to 0 (unconfirmed) or 2 (unsubscribed)
			   (
				   (
				     $("JSStatusManager").value == "0" || $("JSStatusManager").value == "2"
				   )
				 )
	     )
	  {
			return true;
		}
	}
	else {
		// view == \'form\'
		if (row_status == 0 || row_status == 2) {
			return true;
		}
	}

	return false;
}

function subscriber_list_anchor() {
	return sprintf("list-%s-%s-%s", subscriber_list_sort, subscriber_list_offset, subscriber_list_filter);
}

function subscriber_list_tabelize(rows, offset) {
	if (rows.length < 1) {
		/*
		if (!subscriber_list_filter || subscriber_list_filter == 0) {
			adesk_ui_api_callback();
			adesk_ui_anchor_set(\'form-0\');
			return;
		}
		*/
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("list_table"));

		$("list_noresults").className = "adesk_block";

		if (adesk_js_admin.pg_subscriber_delete) {
			if($("list_delete_button")) $("list_delete_button").className = "adesk_hidden";
		}

		$("loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		return;
	}

	var fidx = 5;
	for (var i in subscriber_fields) {
		var field = subscriber_fields[i];

		if (typeof field.id == "undefined")
			continue;

		if ( field.show_in_list && ( subscriber_list_list_filter && (field.relid == 0 || field.relid == $("JSListManager").value) ) ) {
			(function(field, fjdx) {
			 subscriber_table.setcol(fjdx, function(row) {
				var rowidx = sprintf("field%d", field.id);
				if (typeof row[rowidx] != "undefined") {
					if (row[rowidx] !== "") {
						if (row[rowidx] == "checked") {
							return Builder.node("img", { alt: row[rowidx], src: sprintf("%s/manage/images/checked.gif", plink) });
						} else if (row[rowidx] == "unchecked") {
							return Builder.node("img", { alt: row[rowidx], src: sprintf("%s/manage/images/unchecked.gif", plink) });
						} else {
							return Builder.node("span", { title: row[rowidx] }, adesk_str_shorten(row[rowidx], 60));
						}
					} else {
						return " ";
					}
				} else
					return " ";
			})})(field, fidx++);
		}
	}

	$("list_noresults").className = "adesk_hidden";

	if (adesk_js_admin.pg_subscriber_delete) {
		$("list_delete_button").className = "adesk_inline";
	}

	adesk_paginator_tabelize(subscriber_table, "list_table", rows, offset);
	$("loadingBar").className = "adesk_hidden";

	$("subscriber_list_count").innerHTML = " (" + adesk_number_format(paginators[1].total, decimalDelim, commaDelim) + ")";
	$("subscriber_list_count").className = "adesk_inline";
	if ( $(\'selectXPageAllBox\') ) {
		var spans = $(\'selectXPageAllBox\').getElementsByTagName(\'span\');
		if ( spans.length > 2 ) {
			spans[2].innerHTML = adesk_number_format(paginators[1].total, decimalDelim, commaDelim);
		}
	}

	/*
	if (parseInt(subscriber_list_filter, 10) > 0)
		$("list_button_newlist").style.display = "";
	else
		$("list_button_newlist").style.display = "none";
	*/
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function subscriber_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (subscriber_list_filter > 0 && $(\'list_search\').value)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	subscriber_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(subscriber_list_anchor());
	$("loadingBar").className = "adesk_block";
	// we hard-code action=subscriber#list-01-0-0 for the Menu link, so the Status drop-down should assume the default status filter for paginator
	if (subscriber_list_filter == 0) $(\'JSStatusManager\').value = \'1\';
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, subscriber_list_sort, subscriber_list_offset, this.limit, subscriber_list_filter);

	$("list").className = "adesk_block";
}

function subscriber_list_limitize(limit) {
	// save new admin limit locally
	adesk_js_admin.subscribers_per_page = limit;
	// save new admin limit remotelly
	adesk_ajax_call_cb(\'awebdeskapi.php\', \'user.user_update_value\', null, \'subscribers_per_page\', limit);
	// set new limit
	this.limit = limit;
	// fetch new list
	this.paginate(this.offset);
}

function subscriber_list_clear() {
	subscriber_list_sort = "01";
	subscriber_list_offset = "0";
	subscriber_list_filter = "0";
	subscriber_listfilter = null;
	$("list_search").value = "";
	$("JSListManager").value = 0;
	$("JSStatusManager").value = \'1\';
	list_filters_update(0, 0, true);
	subscriber_search_defaults();
	// new type (refresh the page)
	if (subscriber_list_segment > 0)
		window.location.href = \'desk.php?action=subscriber&filterid=\' + subscriber_list_segment;
	else
		window.location.href = \'desk.php?action=subscriber\';
	//window.location.reload(true);
	//adesk_ui_anchor_set(subscriber_list_anchor());
}

function subscriber_list_search() {
	var post = adesk_form_post($("list"));
	post.segmentid = subscriber_list_segment;
	post.filterid  = subscriber_list_filter;
	post.listid    = $("JSListManager").value;
	//subscriber_listfilter = post.listid;
	list_filters_update(0, post.listid, false);
	adesk_ajax_post_cb("awebdeskapi.php", "subscriber.subscriber_filter_post", subscriber_list_search_cb, post);
}

function subscriber_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	subscriber_list_filter = ary.filterid;
	//adesk_ui_anchor_set(subscriber_list_anchor());
	// Force a page reload.
	//adesk_ui_rsh_stop();
	if (subscriber_list_segment > 0) {
		window.location.href = \'desk.php?action=subscriber&filterid=\' + subscriber_list_segment + \'&search=\' + subscriber_list_filter + \'&content=\' + encodeURIComponent(ary.content) + \'#\' + subscriber_list_anchor();
	}
	else {
		adesk_ui_anchor_set(subscriber_list_anchor());
		//window.location.href = \'desk.php?action=subscriber#\' + subscriber_list_anchor();
		//window.location.reload(true);
	}
}

function subscriber_list_chsort(newSortId) {
	var oldSortId = ( subscriber_list_sort.match(/D$/) ? subscriber_list_sort.substr(0, 2) : subscriber_list_sort );
	var oldSortObj = $(\'list_sorter\' + oldSortId);
	var sortObj = $(\'list_sorter\' + newSortId);
	// if sort column didn\'t change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( subscriber_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = subscriber_list_sort.substr(0, 2);
			sortObj.className = \'adesk_sort_asc\';
		} else {
			// was ASC
			newSortId = subscriber_list_sort + \'D\';
			sortObj.className = \'adesk_sort_desc\';
		}
	} else {
		// remove old subscriber_list_sort
		if ( oldSortObj ) oldSortObj.className = \'adesk_sort_other\';
		// set sort field
		sortObj.className = \'adesk_sort_asc\';
	}
	subscriber_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(subscriber_list_anchor());
	return false;
}

function subscriber_list_discern_sortclass() {
	if (subscriber_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", subscriber_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (subscriber_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	subscriber_list_sort_discerned = true;
}

function subscriber_list_export() {
	/*
	if (parseInt(subscriber_list_filter, 10) > 0) {
		if ($("list_export_newlist") === null)
			$("list_export_type").appendChild(Builder.node("option", { id: "list_export_newlist", value: "newlist" }, subscriber_list_str_newlist));
	} else {
		if ($("list_export_newlist") !== null)
			$("list_export_type").removeChild($("list_export_newlist"));
	}
	*/

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
	var rel = $(\'exportFields\');
	adesk_dom_remove_children(rel);
	if ( $(\'JSListManager\').value != 0 ) {
		//alert(\'2do: fetch list info and grab list fields\');
	}
}

function subscriber_list_export_build() {
	var post = adesk_form_post($("exportOffer"));
	post.filter = subscriber_list_filter;
	post.offset = subscriber_list_offset;
	post.limit  = paginators[1].limit;
	post.segmentid = subscriber_list_segment;

	var fieldtmp = [];

	// Figure out which custom fields to show.
	$A(document.getElementsByTagName("input")).each(function(inp) {
			if (inp.type == "checkbox" && inp.name == "fields[]" && inp.checked)
				fieldtmp.push(inp.value);
		});

	post.fields = fieldtmp.join(",");
	export_link_build(\'subscriber\', post);
}

function subscriber_list_exportformat(val) {
	/*
	if (val == "newlist") {
		subscriber_list_export();
		if (parseInt(subscriber_list_filter, 10) > 0)
			window.location.href = \'#exportlist-\' + subscriber_list_filter.toString();

		$("list_export_type").value = "csv";
	}
	*/
}

'; ?>
