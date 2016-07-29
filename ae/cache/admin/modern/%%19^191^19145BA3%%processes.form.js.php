<?php /* Smarty version 2.6.12, created on 2016-07-08 14:41:08
         compiled from processes.form.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'processes.form.js', 1, false),array('modifier', 'js', 'processes.form.js', 1, false),)), $this); ?>
var processes_form_str_cant_insert = '<?php echo ((is_array($_tmp=((is_array($_tmp='You do not have permission to add Process')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var processes_form_str_cant_update = '<?php echo ((is_array($_tmp=((is_array($_tmp='You do not have permission to edit Process')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var processes_form_str_cant_find   = '<?php echo ((is_array($_tmp=((is_array($_tmp="Process not found.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var processes_form_id = 0;
var processes_form_ary = null;

var processes_form_spawn = false;

<?php echo '

function processes_form_defaults() {
	$("form_id").value = 0;
	// this is also for edit, not for add
	// fields
	$(\'restartField\').checked = false;
	$(\'activeField\').checked = false;
	$(\'scheduleField\').checked = false;
	$(\'scheduleField\').disabled = false;
	$(\'spawnField\').checked = false;
	$(\'spawnField\').disabled = false;
	// panels
	$(\'restartBox\').className = \'adesk_hidden\';
	$(\'activeBox\').className = \'adesk_hidden\';
	$(\'activeInBox\').className = \'adesk_hidden\';
	$(\'scheduleBox\').className = \'adesk_hidden\';
	$(\'dateBox\').className = \'adesk_hidden\';
	$(\'spawnBox\').className = \'adesk_hidden\';
}

function processes_form_load(id) {
	processes_form_defaults();
	processes_form_id = id;

	if (id > 0) {
		adesk_ui_api_call(jsLoading);
		$("form_submit").className = "adesk_button_update";
		$("form_submit").value = jsUpdate;
		adesk_ajax_call_cb("awebdeskapi.php", "processes!adesk_processes_select_row", processes_form_load_cb, id);
	} else {
		if ( !ary.id ) {
			adesk_error_show(processes_form_str_cant_find);
			adesk_ui_anchor_set(processes_list_anchor());
			return;
		}
		$("form_submit").className = "adesk_button_add";
		$("form_submit").value = jsAdd;
		$("form").className = "adesk_block";
	}
}

function processes_form_load_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if ( !ary.id ) {
		adesk_error_show(processes_form_str_cant_find);
		adesk_ui_anchor_set(processes_list_anchor());
		return;
	}
	processes_form_id  = ary.id;
	processes_form_ary = ary;

	// labels
	$(\'nameLabel\').innerHTML = ary.name;
	if ( ary.descript && ary.descript != \'\' ) {
		$(\'descriptLabel\').innerHTML = ary.descript;
		$(\'descriptLabel\').className = \'adesk_block\';
	} else {
		$(\'descriptLabel\').className = \'adesk_hidden\';
	}
	$(\'cdateLabel\').innerHTML = ary.cdate;
	$(\'ldateLabel\').innerHTML = ( ary.ldate ? ary.ldate : jsNotAvailable );
	$(\'percentageLabel\').innerHTML = ary.completed + \' / \' + ary.total;
	$(\'statusLabel\').innerHTML = process_status(ary);

	// progress bar
	adesk_progressbar_register(
		\'progressBar\',
		ary.id,
		ary.percentage,
		( ary.remaining > 0 ? 10 : 0 ),
		( processes_form_spawn && ary.stall && ary.stall > 4 * 60 ),
		processes_form_progress_ihook
	);
	if ( ary.remaining == 0 ) {
		adesk_progressbar_unregister(\'progressBar\');
	}

	// fields
	process_form_fields(ary);

	// panels
	$(\'activeBox\' ).className = ( ary.remaining != 0 ? \'adesk_block\' : \'adesk_hidden\' );
	$(\'restartBox\').className = ( ary.remaining == 0 ? \'adesk_block\' : \'adesk_hidden\' );
	process_form_panels(ary);

	$("form").className = "adesk_block";
}

function processes_form_save(id) {
	var post = adesk_form_post($("form"));
	adesk_ui_api_call(jsSaving);

	if (id > 0)
		adesk_ajax_post_cb("awebdeskapi.php", "processes!adesk_processes_update_post", processes_form_save_cb, post);
	else
		adesk_ajax_post_cb("awebdeskapi.php", "processes!adesk_processes_insert_post", processes_form_save_cb, post);
}

function processes_form_save_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(processes_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}
}



function processes_form_active_changed() {
	$(\'scheduleField\').checked = ( processes_form_ary.stall < 0 );
	$(\'ldateField\').value = ( processes_form_ary.stall < 0 ? processes_form_ary.ldate : \'\' );
	process_form_panels(processes_form_ary);
}

function processes_form_schedule_changed() {
	$(\'dateBox\').className = ( $(\'scheduleField\').checked ? \'adesk_inline\' : \'adesk_hidden\' );
	if ( $(\'scheduleField\').checked ) {
		// date box
		$(\'ldateField\').value = \'\';
		// spawn box
		$(\'spawnField\').checked = false;
		$(\'spawnField\').disabled = true;
	} else {
		// release spawn box
		$(\'spawnField\').disabled = false;
	}
}

function processes_form_spawn_changed() {
	if ( $(\'spawnField\').checked ) {
		// schedule box
		$(\'scheduleField\').checked = false;
		$(\'scheduleField\').disabled = true;
		$(\'dateBox\').className = \'adesk_hidden\';
	} else {
		// release schedule box
		$(\'scheduleField\').disabled = false;
	}
}

function process_form_fields(ary) {
	// fields
	$(\'form_id\').value = ary.id;
	$(\'activeField\').checked = ( ary.remaining != 0 && ary.ldate );
	$(\'scheduleField\').checked = ( ary.remaining > 0 && ary.ldate && ary.stall < 0 );
	$(\'ldateField\').value = ( $(\'scheduleField\').checked ? ary.ldate : \'\' );
}

function process_form_panels(ary) {
	// panels
	$(\'activeInBox\').className = ( $(\'activeField\').checked ? \'adesk_block\' : \'adesk_hidden\' );
	if ( $(\'activeField\').checked ) {
		$(\'scheduleBox\').className = \'adesk_block\';
		$(\'dateBox\').className = ( ary.remaining == 0 || ary.stall < 0 ? \'adesk_inline\' : \'adesk_hidden\' );
		$(\'spawnBox\').className = ( ary.remaining == 0 || ary.stall > 4 * 60 ? \'adesk_block\' : \'adesk_hidden\' );
	}
}

function processes_form_progress_ihook(ary) {
	var rel = $(\'percentageLabel\');
	if ( rel ) rel.innerHTML = ary.completed + \' / \' + ary.total;
	var rel = $(\'statusLabel\');
	if ( rel ) rel.innerHTML = process_status(ary);
	if ( ary.remaining == 0 ) {
		processes_form_defaults();
		processes_form_ary = ary;
		process_form_fields();
		$(\'activeBox\' ).className = ( ary.remaining != 0 ? \'adesk_block\' : \'adesk_hidden\' );
		$(\'restartBox\').className = ( ary.remaining == 0 ? \'adesk_block\' : \'adesk_hidden\' );
		process_form_panels();
	}
}

'; ?>
