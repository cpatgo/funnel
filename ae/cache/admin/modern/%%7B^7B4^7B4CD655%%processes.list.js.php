<?php /* Smarty version 2.6.12, created on 2016-07-08 14:41:08
         compiled from processes.list.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'processes.list.js', 1, false),array('modifier', 'js', 'processes.list.js', 1, false),array('function', 'jsvar', 'processes.list.js', 18, false),)), $this); ?>
var processes_list_str_created = '<?php echo ((is_array($_tmp=((is_array($_tmp="Created: ")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var processes_list_str_completed = '<?php echo ((is_array($_tmp=((is_array($_tmp="Completed: ")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var processes_list_str_status = '<?php echo ((is_array($_tmp=((is_array($_tmp="Status: ")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var processes_list_str_status_paused = '<?php echo ((is_array($_tmp=((is_array($_tmp='Paused')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var processes_list_str_status_running = '<?php echo ((is_array($_tmp=((is_array($_tmp='Running')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var processes_list_str_status_stalled = '<?php echo ((is_array($_tmp=((is_array($_tmp='Stalled')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var processes_list_str_status_completed = '<?php echo ((is_array($_tmp=((is_array($_tmp='Completed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var processes_list_str_confirm_run = '<?php echo ((is_array($_tmp=((is_array($_tmp="Are you sure you wish to run (requeue) this process?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var processes_list_str_confirm_pause = '<?php echo ((is_array($_tmp=((is_array($_tmp="Are you sure you wish to pause this process?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var processes_list_str_confirm_resume = '<?php echo ((is_array($_tmp=((is_array($_tmp="Are you sure you wish to resume this process?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var processes_list_str_confirm_restart = '<?php echo ((is_array($_tmp=((is_array($_tmp="Are you sure you wish to restart this process?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';


var processes_table = new ACTable();
var processes_list_sort = "01";
var processes_list_offset = "0";
var processes_list_filter = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['filterid']), $this);?>
;
var processes_list_sort_discerned = false;

var processes_list_spawn = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['spawn']), $this);?>
;


<?php echo '

processes_table.setcol(0, function(row, td) {
	td.vAlign = \'top\';
	return Builder.node("input", { type: "checkbox", name: "multi[]", value: row.id, onclick: "adesk_form_check_selection_none(this, $(\'acSelectAllCheckbox\'), $(\'selectXPageAllBox\'))" });
});

processes_table.setcol(1, function(row, td) {
	td.vAlign = \'top\';

	var ary = [];

	// edit
	ary.push(Builder.node("a", { href: sprintf("#form-%d", row.id) }, jsOptionEdit));
	ary.push(" ");
	if ( row.remaining > 0 ) {
		if ( row.ldate == \'\' ) {
			ary.push(Builder.node("a", { href: \'#\', onclick: "return processes_list_resume(" + row.id + ");" }, jsOptionResume));
			ary.push(" ");
		} else {
			if ( row.stall && row.stall > 4 * 60 ) {
				ary.push(Builder.node("a", { href: \'#\', onclick: "return processes_list_run(" + row.id + ");" }, jsOptionRun));
				ary.push(" ");
			}
			ary.push(Builder.node("a", { href: \'#\', onclick: "return processes_list_pause(" + row.id + ");" }, jsOptionPause));
			ary.push(" ");
		}
	} else {
		ary.push(Builder.node("a", { href: \'#\', onclick: "return processes_list_restart(" + row.id + ");" }, jsOptionRestart));
		ary.push(" ");
	}
	// delete
	ary.push(Builder.node("a", { href: sprintf("#delete-%d", row.id) }, jsOptionDelete));

	return Builder.node("div", { className: "adesk_table_row_options" }, ary);
});

processes_table.setcol(2, function(row, td) {
	td.vAlign = \'top\';
	var nodes = [];
	// title
	if ( row.descript && row.descript != \'\' ) {
		nodes.push(Builder.node(\'div\', [ Builder._text(row.descript) ]));
	}
	nodes.push(Builder.node(\'div\', [ Builder._text(processes_list_str_created), Builder._text(row.cdate) ]));
	nodes.push(
		Builder.node(
			\'div\',
			[
				Builder._text(processes_list_str_completed),
				Builder.node(\'span\', { id: \'processes_list_view_stats\' + row.id }, [ Builder._text(row.completed + \' / \' + row.total) ]),
			]
		)
	);
	nodes.push(
		Builder.node(
			\'div\',
			[
				Builder._text(processes_list_str_status),
				Builder.node(\'strong\', { id: \'processes_list_view_status\' + row.id }, [ Builder._text(process_status(row)) ]),
			]
		)
	);
	return Builder.node(
		\'div\',
		[
			Builder.node(
				\'div\',
				[ Builder.node(\'strong\', [ Builder._text(row.name) ]) ]
			),
			Builder.node(
				\'div\',
				{ id: \'processes_list_info\' + row.id/*, className: \'adesk_hidden\'*/ },
				nodes
			)
		]
	);
});

processes_table.setcol(3, function(row, td) {
	td.vAlign = \'top\';
	return ( row.ldate ? sql2date(row.ldate).format(datetimeformat) : jsNotAvailable );
});

processes_table.setcol(4, function(row, td) {
	td.vAlign = \'top\';
	var progressBar = Builder.node(\'div\', { id: \'processes_list_progress\' + row.id, className: \'adesk_progressbar\' });
	return progressBar;
});

function processes_list_anchor() {
	return sprintf("list-%s-%s-%s", processes_list_sort, processes_list_offset, processes_list_filter);
}

function processes_list_tabelize(rows, offset) {
	if (rows.length < 1) {
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
	$("loadingBar").className = "adesk_hidden";
	$("acSelectAllCheckbox").checked = false;
	$("selectXPageAllBox").className = \'adesk_hidden\';
	// first remove old timers (progress bars)
	for ( var i in adesk_progress_bars ) {
		adesk_progressbar_unregister(i);
	}
	adesk_paginator_tabelize(processes_table, "list_table", rows, offset);
	for ( i = 0; i < rows.length; i++ ) {
		var id = rows[i][\'id\'];
		var divid = \'processes_list_progress\' + id;
		adesk_progressbar_register(
			divid,
			id,
			rows[i][\'percentage\'],
			( rows[i][\'remaining\'] > 0 ? 10 : 0 ),
			( processes_list_spawn && rows[i][\'stall\'] && parseInt(rows[i][\'stall\']) > 4 * 60 ),
			processes_list_progress_ihook
		);
		if ( rows[i][\'remaining\'] == 0 ) {
			adesk_progressbar_unregister(divid);
		}
	}
	$("loadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function processes_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (processes_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	processes_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(processes_list_anchor());
	$("loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, processes_list_sort, processes_list_offset, this.limit, processes_list_filter);

	$("list").className = "adesk_block";
}

function processes_list_clear() {
	processes_list_sort = "01";
	processes_list_offset = "0";
	processes_list_filter = "0";
	processes_actionfilter = null;
	processes_statusfilter = null;
	$("list_search").value = "";
	$("JSActionManager").value = \'\';
	$("JSStatusManager").value = \'\';
	processes_search_defaults();
	adesk_ui_anchor_set(processes_list_anchor());
}

function processes_list_search() {
	var post = adesk_form_post($("list"));
	processes_actionfilter = post.action;
	processes_statusfilter = post.status;
	adesk_ajax_post_cb("awebdeskapi.php", "processes!adesk_processes_filter_post", processes_list_search_cb, post);
}

function processes_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	processes_list_filter = ary.filterid;
	adesk_ui_anchor_set(processes_list_anchor());
}

function processes_list_chsort(newSortId) {
	var oldSortId = ( processes_list_sort.match(/D$/) ? processes_list_sort.substr(0, 2) : processes_list_sort );
	var oldSortObj = $(\'list_sorter\' + oldSortId);
	var sortObj = $(\'list_sorter\' + newSortId);
	// if sort column didn\'t change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( processes_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = processes_list_sort.substr(0, 2);
			sortObj.className = \'adesk_sort_asc\';
		} else {
			// was ASC
			newSortId = processes_list_sort + \'D\';
			sortObj.className = \'adesk_sort_desc\';
		}
	} else {
		// remove old processes_list_sort
		if ( oldSortObj ) oldSortObj.className = \'adesk_sort_other\';
		// set sort field
		sortObj.className = \'adesk_sort_asc\';
	}
	processes_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(processes_list_anchor());
	return false;
}

function processes_list_discern_sortclass() {
	if (processes_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", processes_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (processes_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	processes_list_sort_discerned = true;
}



function processes_list_run(id) {
	if ( !confirm(processes_list_str_confirm_run) ) return false;
	adesk_ui_api_call(jsStarting);
	adesk_ajax_call_cb("awebdeskapi.php", "processes!adesk_processes_trigger", processes_list_trigger_cb, id, \'run\');
	return false;
}

function processes_list_pause(id) {
	if ( !confirm(processes_list_str_confirm_pause) ) return false;
	adesk_ui_api_call(jsPausing);
	adesk_ajax_call_cb("awebdeskapi.php", "processes!adesk_processes_trigger", processes_list_trigger_cb, id, \'pause\');
	return false;
}

function processes_list_resume(id) {
	if ( !confirm(processes_list_str_confirm_resume) ) return false;
	adesk_ui_api_call(jsResuming);
	adesk_ajax_call_cb("awebdeskapi.php", "processes!adesk_processes_trigger", processes_list_trigger_cb, id, \'resume\');
	return false;
}

function processes_list_restart(id) {
	if ( !confirm(processes_list_str_confirm_restart) ) return false;
	adesk_ui_api_call(jsRestarting);
	adesk_ajax_call_cb("awebdeskapi.php", "processes!adesk_processes_trigger", processes_list_trigger_cb, id, \'restart\');
	return false;
}

function processes_list_progress_ihook(ary) {
	var rel = $(\'processes_list_view_stats\' + ary.id);
	if ( rel ) rel.innerHTML = ary.completed + \' / \' + ary.total;
	var rel = $(\'processes_list_view_status\' + ary.id);
	if ( rel ) rel.innerHTML = process_status(ary);
}

function processes_list_trigger_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		// rebuild this list
		paginators[1].paginate(processes_list_offset);
	} else {
		adesk_error_show(ary.message);
	}
}



function process_status(ary) {
	if ( ary.remaining == 0 ) {
		return processes_list_str_status_completed;
	} else if ( !ary.ldate ) {
		return processes_list_str_status_paused;
	} else if ( ary.stall && ary.stall > 4 * 60 ) {
		return processes_list_str_status_stalled;
	} else {
		return processes_list_str_status_running;
	}
}

function processes_list_spawn_toggle(newval) {
	// instant doesnt work
	processes_list_spawn = newval;
	// gotta refresh
	var go2 = \'desk.php?action=processes\';
	if ( newval ) {
		//
		go2 += \'&spawn=1\';
	} else {
		//
	}
	go2 += \'#\' + processes_list_anchor();;
	window.location.href = go2;
}

'; ?>
