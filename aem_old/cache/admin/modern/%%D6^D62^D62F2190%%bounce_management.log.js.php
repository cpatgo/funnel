<?php /* Smarty version 2.6.12, created on 2016-07-18 12:02:28
         compiled from bounce_management.log.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'bounce_management.log.js', 1, false),array('modifier', 'js', 'bounce_management.log.js', 1, false),)), $this); ?>
var bounce_management_log_str_cant_find   = '<?php echo ((is_array($_tmp=((is_array($_tmp="Bounces Log not found.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var bounce_management_log_str_row         = '<?php echo ((is_array($_tmp="Email %s parsed as bounced.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
';
var bounce_management_log_str_structured  = '<?php echo ((is_array($_tmp="Email appears to be an improperly structured bounce message. Error: %s")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
';
var bounce_management_log_str_errors      = '<?php echo ((is_array($_tmp="Email %s NOT parsed as bounce! Error: %s")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
';
var bounce_management_log_str_details     = '<?php echo ((is_array($_tmp="Details...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
';

<?php echo '
function bounce_management_log(id) {
	adesk_ui_api_call(jsLoading);
	adesk_ajax_call_cb("awebdeskapi.php", "bounce_management.bounce_management_log", bounce_management_log_cb, id);
}

function bounce_management_log_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if ( !ary.cnt ) {
		adesk_error_show(bounce_management_log_str_cant_find);
		adesk_ui_anchor_set(bounce_management_list_anchor());
		return;
	}
	ary.cnt = parseInt(ary.cnt, 10);

	// set count
	$(\'log_count\').innerHTML = ary.cnt;
	$(\'log_result_box\').className = \'adesk_hidden\';
	$(\'log_list_box\').className = \'adesk_block\';

	// set list
	adesk_dom_remove_children($(\'log_list\'));
	$(\'log_list\' ).className = ( ary.cnt >  0 ? \'adesk_block\' : \'adesk_hidden\' );
	$(\'log_empty\').className = ( ary.cnt == 0 ? \'adesk_block\' : \'adesk_hidden\' );
	if ( ary.cnt > 0 ) {
		for ( var i = 0; i < ary.cnt; i++ ) {
			var row = ary.log[i];
			var txt = sql2date(row.tstamp).format(datetimeformat) + \': \';
			if ( row.error && row.error != \'\' ) {
				if ( row.email && row.email != \'\' ) {
					txt += sprintf(bounce_management_log_str_errors, row.email, row.msg);
				} else {
					txt += sprintf(bounce_management_log_str_structured, row.msg);
				}
			} else {
				txt += sprintf(bounce_management_log_str_row, row.email);
			}

			$(\'log_list\').appendChild(
				Builder.node(
					\'li\',
					{ className: \'bounce_management_log_row\' },
					[
						Builder.node(\'input\', { type: \'hidden\', value: row.id, id: \'log_row_\' + row.id }),
						Builder._text(txt + \' \'),
						Builder.node(
							\'a\',
							{ href: \'#\', onclick: \'return bounce_management_log_show(\' + row.id + \');\' },
							[ Builder._text(bounce_management_log_str_details) ]
						)
					]
				)
			);
		}
	}

	//adesk_dom_toggle_display("log", "block");
	adesk_dom_display_block("log");
}


function bounce_management_log_show(id) {
	adesk_ui_api_call(jsLoading);
	adesk_ajax_call_cb("awebdeskapi.php", "bounce_management.bounce_log_select_row", bounce_management_log_show_cb, id);
	return false;
}

function bounce_management_log_show_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if ( !ary.source ) {
		adesk_error_show(bounce_management_log_str_cant_find);
		return;
	}
	ary.id = parseInt(ary.id, 10);

	// set log info
	var emlstr = \'\';
	if ( ary.email && ary.email != \'\' ) {
		emlstr = ary.email;
		var sid = parseInt(ary.subscriberid, 10);
		if ( sid > 0 ) emlstr = \'<a href="desk.php?action=subscriber_view&id=\' + sid + \'">\' + emlstr + \'</a>\';
	} else {
		emlstr = jsNotAvailable;
	}
	var cid = parseInt(ary.campaignid, 10);
	var cmpstr = \'\';
	if ( cid > 0 && ary.campaign.name ) {
		cmpstr = \'<a href="desk.php?action=report_campaign&id=\' + cid + \'">\' + ary.campaign.name + \'</a>\';
	}
	$(\'log_source\').value       = ary.source;
	$(\'log_result\').innerHTML   = ary.msg;
	$(\'log_campaign\').innerHTML = cmpstr;
	$(\'log_email\').innerHTML    = ( ary.email && ary.email != \'\' ? ary.email : jsNotAvailable );
	$(\'log_date\').innerHTML     = sql2date(ary.tstamp).format(datetimeformat);

	// show the box
	$(\'log_result_box\').className = \'adesk_block\';
	$(\'log_list_box\').className = \'adesk_hidden\';
	$(\'log_campaign_box\').className = ( cid > 0 ? \'adesk_block\' : \'adesk_hidden\' );

	/*
	// hide other log rows
	var rel = $(\'log_list\');
	var rows = rel.getElementsByTagName(\'li\');
	for ( var i = 0; i < rows.length; i++ ) {
		var id = parseInt(rows[i].getElementsByTagName(\'input\')[0].value, 10);
		if ( id != ary.id ) rows[i].className = \'adesk_hidden\';
	}
	*/
}

function bounce_management_log_hide() {
	// discard the source
	$(\'log_source\').value       = \'\';
	$(\'log_result\').innerHTML   = \'\';
	$(\'log_campaign\').innerHTML = \'\';
	$(\'log_email\').innerHTML    = \'\';
	$(\'log_date\').innerHTML     = \'\';

	// show the box
	$(\'log_result_box\').className = \'adesk_hidden\';
	$(\'log_list_box\').className = \'adesk_block\';

	/*
	// show other log rows
	var rel = $(\'log_list\');
	var rows = rel.getElementsByTagName(\'li\');
	for ( var i = 0; i < rows.length; i++ ) {
		rows[i].className = \'\';
	}
	*/
	return false;
}
'; ?>
