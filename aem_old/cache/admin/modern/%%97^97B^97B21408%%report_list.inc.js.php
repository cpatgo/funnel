<?php /* Smarty version 2.6.12, created on 2016-07-18 15:28:21
         compiled from report_list.inc.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'report_list.inc.js', 1, false),array('modifier', 'js', 'report_list.inc.js', 1, false),)), $this); ?>
var report_list_str_group_limit = "<?php echo ((is_array($_tmp=((is_array($_tmp='%s per %s')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
";
<?php echo '
var report_list_str_group_limit_types = {
'; ?>

	day: "<?php echo ((is_array($_tmp=((is_array($_tmp='%s per day')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
",
	week: "<?php echo ((is_array($_tmp=((is_array($_tmp='%s per week')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
",
	month: "<?php echo ((is_array($_tmp=((is_array($_tmp='%s per month')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
",
	month1st: "<?php echo ((is_array($_tmp=((is_array($_tmp='%s per calendar month (counting from the 1st)')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
",
	monthcdate: "<?php echo ((is_array($_tmp=((is_array($_tmp='%s per calendar month (counting from the user\'s creation date)')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
",
	year: "<?php echo ((is_array($_tmp=((is_array($_tmp='%s per year')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
",
	ever: "<?php echo ((is_array($_tmp=((is_array($_tmp='%s total')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
"
<?php echo '
};


report_list_table.setcol(0, function(row) {
	return Builder.node(\'a\', { href: \'desk.php?action=report_list&id=\' + row.id }, [ Builder._text(row.name) ]
	);
});

report_list_table.setcol(1, function(row, td) {
	td.align = \'center\';
	return ( row.subscribed ? row.subscribed : Builder.node(\'em\', [ Builder._text(\'0\') ]) );
});

report_list_table.setcol(2, function(row, td) {
	td.align = \'center\';
	return ( row.unconfirmed ? row.unconfirmed : Builder.node(\'em\', [ Builder._text(\'0\') ]) );
});

report_list_table.setcol(3, function(row, td) {
	td.align = \'center\';
	return ( row.unsubscribed ? row.unsubscribed : Builder.node(\'em\', [ Builder._text(\'0\') ]) );
});

report_list_table.setcol(4, function(row, td) {
	td.align = \'center\';
	return ( row.bounced ? row.bounced : Builder.node(\'em\', [ Builder._text(\'0\') ]) );
});
/*
report_list_table.setcol(5, function(row, td) {
	td.align = \'center\';
	return ( row.campaigns ? row.campaigns : Builder.node(\'em\', [ Builder._text(\'0\') ]) );
});
*/
report_list_table.setcol(5, function(row, td) {
	td.align = \'center\';
	return ( row.emails ? row.emails : Builder.node(\'em\', [ Builder._text(\'0\') ]) );
});

report_list_table.setcol(6, function(row, td) {
	td.align = \'center\';
	return ( row.epd ? ( Math.round(row.epd * 100) / 100 ) : Builder.node(\'em\', [ Builder._text(\'0.00\') ]) );
});

function report_list_list_tabelize(rows, offset) {
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("list_table"));

		$("list_noresults").className = "adesk_block";
		$("loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		return;
	}
	$("list_noresults").className = "adesk_hidden";
	adesk_paginator_tabelize(report_list_table, "list_table", rows, offset);
	$("loadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function report_list_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (report_list_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	report_list_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(report_list_list_anchor());
	$("loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, report_list_list_sort, report_list_list_offset, this.limit, report_list_list_filter, report_list_id);

	$("general").className = "adesk_block";
}

'; ?>
