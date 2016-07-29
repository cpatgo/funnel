<?php /* Smarty version 2.6.12, created on 2016-07-13 11:54:42
         compiled from report_trend_read.inc.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'report_trend_read.inc.js', 1, false),array('modifier', 'js', 'report_trend_read.inc.js', 1, false),)), $this); ?>
var report_trend_read_str_group_limit = "<?php echo ((is_array($_tmp=((is_array($_tmp='%s per %s')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
";
<?php echo '
var report_trend_read_str_group_limit_types = {
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


report_trend_read_table.setcol(0, function(row, td) {
	var linkaction = ( report_trend_read_id ? \'report_campaign\' : \'report_trend_read\' );
	return Builder.node(\'a\', { href: \'desk.php?action=\' + linkaction + \'&id=\' + row.id }, [ Builder._text(row.name) ]);
});

report_trend_read_table.setcol(1, function(row, td) {
	//td.align = \'center\';
	if(row.tstamp != "")
		return sql2date(row.tstamp).format(datetimeformat);
	else
		return Builder._text(jsNotAvailable);
});

report_trend_read_table.setcol(2, function(row, td) {
	td.align = \'center\';
	if ( isNaN(parseInt(row.besthour, 10)) ) return Builder.node(\'em\', [ Builder._text(jsNotAvailable) ]);
	var leadzero = ( row.besthour.length == 1 ? \'0\' : \'\' );
	return leadzero + \'\' + row.besthour;
});

report_trend_read_table.setcol(3, function(row, td) {
	td.align = \'center\';
	return ( !isNaN(parseInt(row.bestweek, 10)) ? row.bestweeklabel : Builder.node(\'em\', [ Builder._text(jsNotAvailable) ]) );
});
/*
report_trend_read_table.setcol(4, function(row, td) {
	td.align = \'center\';
	if ( isNaN(parseInt(row.worsthour, 10)) ) return Builder.node(\'em\', [ Builder._text(jsNotAvailable) ]);
	var leadzero = ( row.worsthour.length == 1 ? \'0\' : \'\' );
	return leadzero + \'\' + row.worsthour;
});

report_trend_read_table.setcol(5, function(row, td) {
	td.align = \'center\';
	return ( !isNaN(parseInt(row.worstweek, 10)) ? row.worstweeklabel : Builder.node(\'em\', [ Builder._text(jsNotAvailable) ]) );
});
*/
report_trend_read_table.setcol(4, function(row, td) {
	td.align = \'center\';
	return ( row.uniqueopens ? row.uniqueopens : Builder.node(\'em\', [ Builder._text(\'0\') ]) );
});

function report_trend_read_list_tabelize(rows, offset) {
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("list_table"));

		$("list_noresults").className = "adesk_block";
		$("loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		return;
	}
	$("list_noresults").className = "adesk_hidden";
	adesk_paginator_tabelize(report_trend_read_table, "list_table", rows, offset);
	$("loadingBar").className = "adesk_hidden";
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function report_trend_read_list_paginate(offset) {
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (report_trend_read_list_filter > 0)
		$("list_clear").style.display = "inline";
	else
		$("list_clear").style.display = "none";

	report_trend_read_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(report_trend_read_list_anchor());
	$("loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, report_trend_read_list_sort, report_trend_read_list_offset, this.limit, report_trend_read_list_filter, report_trend_read_id);

	$("general").className = "adesk_block";
}

'; ?>
