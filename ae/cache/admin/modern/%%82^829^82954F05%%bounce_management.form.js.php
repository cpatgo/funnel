<?php /* Smarty version 2.6.12, created on 2016-07-18 12:02:28
         compiled from bounce_management.form.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'bounce_management.form.js', 1, false),array('modifier', 'js', 'bounce_management.form.js', 1, false),)), $this); ?>
var bounce_management_form_str_cant_insert = '<?php echo ((is_array($_tmp=((is_array($_tmp='You do not have permission to add Bounce Setting')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var bounce_management_form_str_cant_update = '<?php echo ((is_array($_tmp=((is_array($_tmp='You do not have permission to edit Bounce Setting')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var bounce_management_form_str_cant_find   = '<?php echo ((is_array($_tmp=((is_array($_tmp="Bounce Setting not found.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var bounce_management_form_str_email_invalid = '<?php echo ((is_array($_tmp=((is_array($_tmp="Email Address is not valid.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var bounce_management_form_str_host_missing = '<?php echo ((is_array($_tmp=((is_array($_tmp="Host name not entered.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var bounce_management_form_str_user_missing = '<?php echo ((is_array($_tmp=((is_array($_tmp="Account username not entered.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
<?php echo '
var bounce_management_form_id = 0;

function bounce_management_form_defaults() {
	$("form_id").value = 0;
	bounce_defaults();
	if ( bounce_management_listfilter && typeof(bounce_management_listfilter) == \'object\' ) {
		for (var i in bounce_management_listfilter) {
			if ( $(\'p_\' + bounce_management_listfilter[i]) ) $(\'p_\' + bounce_management_listfilter[i]).checked = true;
		}
	} else if ( bounce_management_listfilter > 0 ) {
		if ( $(\'p_\' + bounce_management_listfilter) ) $(\'p_\' + bounce_management_listfilter).checked = true;
	} else {
		var list_inputs = $(\'parentsList_div\').getElementsByTagName(\'input\');
		// check all lists first
		for (var i = 0; i < list_inputs.length; i++) {
			list_inputs[i].checked = true;
		}
	}
}

function bounce_management_form_load(id) {
	bounce_management_form_defaults();
	bounce_management_form_id = id;

	if (id > 0) {
		if (adesk_js_admin.pg_list_bounce != 1) {
			adesk_ui_anchor_set(bounce_management_list_anchor());
			alert(bounce_management_form_str_cant_update);
			return;
		}

		adesk_ui_api_call(jsLoading);
		$("form_submit").className = "adesk_button_update";
		$("form_submit").value = jsUpdate;
		adesk_ajax_call_cb("awebdeskapi.php", "bounce_management.bounce_management_select_row_ajax", bounce_management_form_load_cb, id);
	} else {
		if (adesk_js_admin.pg_list_bounce != 1) {
			adesk_ui_anchor_set(bounce_management_list_anchor());
			alert(bounce_management_form_str_cant_insert);
			return;
		}

		$("form_submit").className = "adesk_button_add";
		$("form_submit").value = jsAdd;
		$("form").className = "adesk_block";
	}
}

function bounce_management_form_load_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if ( !ary.id ) {
		adesk_error_show(bounce_management_form_str_cant_find);
		adesk_ui_anchor_set(bounce_management_list_anchor());
		return;
	}

	bounce_management_form_id = ary.id;

	$("form_id").value = ary.id;

	bounce_update(ary);

	var list_inputs = $(\'parentsList_div\').getElementsByTagName(\'input\');
	// uncheck all lists first
	for (var i = 0; i < list_inputs.length; i++) {
		list_inputs[i].checked = false;
	}
	var lists = (ary.lists + \'\').split(\'-\');
	for (var i = 0; i < lists.length; i++) {
		if ( $(\'p_\' + lists[i]) ) $(\'p_\' + lists[i]).checked = true;
	}

	$("form").className = "adesk_block";
}

function bounce_management_form_save(id) {
	var post = adesk_form_post($("form"));

	if ( post.type != \'none\' ) {
		if ( !adesk_str_email(post.email) ) {
			alert(bounce_management_form_str_email_invalid);
			$(\'bounceemailField\').focus();
			return;
		}
		if ( post.type == \'pop3\' ) {
			if ( post.host == \'\' ) {
				alert(bounce_management_form_str_host_missing);
				$(\'bouncehostField\').focus();
				return;
			}
			if ( post.user == \'\' ) {
				alert(bounce_management_form_str_user_missing);
				$(\'bounceuserField\').focus();
				return;
			}
		}
	}

	adesk_ui_api_call(jsSaving);
	if (id > 0)
		adesk_ajax_post_cb("awebdeskapi.php", "bounce_management.bounce_management_update_post", bounce_management_form_save_cb, post);
	else
		adesk_ajax_post_cb("awebdeskapi.php", "bounce_management.bounce_management_insert_post", bounce_management_form_save_cb, post);
}

function bounce_management_form_save_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(bounce_management_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}
}
'; ?>
