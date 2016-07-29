<?php /* Smarty version 2.6.12, created on 2016-07-08 14:41:25
         compiled from cron.log.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'cron.log.js', 1, false),array('modifier', 'js', 'cron.log.js', 1, false),)), $this); ?>
var cron_log_str_cant_find   = '<?php echo ((is_array($_tmp=((is_array($_tmp="Cron Log not found.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var cron_log_str_row         = '<?php echo ((is_array($_tmp=((is_array($_tmp="Process started at %s and finished at %s.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var cron_log_str_stalled     = '<?php echo ((is_array($_tmp=((is_array($_tmp="an unknown time (possible timeout or error)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var cron_log_str_errors      = '<?php echo ((is_array($_tmp=((is_array($_tmp="Errors: ")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

<?php echo '
function cron_log(id) {
	adesk_ui_api_call(jsLoading);
	adesk_ajax_call_cb("awebdeskapi.php", "cron!adesk_cron_log", cron_log_cb, id);
}

function cron_log_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if ( !ary.cnt ) {
		adesk_error_show(cron_log_str_cant_find);
		adesk_ui_anchor_set(cron_list_anchor());
		return;
	}
	ary.cnt = parseInt(ary.cnt, 10);

	// set count
	$(\'log_count\').innerHTML = ary.cnt;

	// set list
	adesk_dom_remove_children($(\'log_list\'));
	$(\'log_list\' ).className = ( ary.cnt >  0 ? \'adesk_block\' : \'adesk_hidden\' );
	$(\'log_empty\').className = ( ary.cnt == 0 ? \'adesk_block\' : \'adesk_hidden\' );
	if ( ary.cnt > 0 ) {
		for ( var i = 0; i < ary.cnt; i++ ) {
			var row = ary.log[i];
			var enddate = ( row.edate ? sql2date(row.edate).format(datetimeformat) : cron_log_str_stalled );
			// add errors if exist
			if ( row.errors && row.errors != \'\' ) {
				enddate += cron_log_str_errors + row.errors;
			}
			$(\'log_list\').appendChild(
				Builder.node(
					\'li\',
					{ className: \'cron_log_row\' },
					[
						Builder._text(sprintf(cron_log_str_row, sql2date(row.sdate).format(datetimeformat), enddate))
					]
				)
			);
		}
	}

	//adesk_dom_toggle_display("log", "block");
	adesk_dom_display_block("log");
}
'; ?>
