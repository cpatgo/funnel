<?php /* Smarty version 2.6.12, created on 2016-07-28 11:05:46
         compiled from exclusion.form.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'exclusion.form.js', 1, false),array('modifier', 'js', 'exclusion.form.js', 1, false),)), $this); ?>
var exclusion_form_str_cant_insert = '<?php echo ((is_array($_tmp=((is_array($_tmp='You do not have permission to add Exclusion Pattern')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var exclusion_form_str_cant_update = '<?php echo ((is_array($_tmp=((is_array($_tmp='You do not have permission to edit Exclusion Pattern')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var exclusion_form_str_cant_find   = '<?php echo ((is_array($_tmp=((is_array($_tmp="Exclusion Pattern not found.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
<?php echo '
var exclusion_form_id = 0;

function exclusion_form_defaults() {
	$("form_id").value = 0;
	$("form_address").value = "";
	$("form_address").disabled = false;
	$("matchtype").value = "exact";
	$("matchtype").disabled = false;

	adesk_dom_boxclear("listid_field");
	adesk_dom_radioset("target_field", "several");

	$("listbox").show();
}

function exclusion_form_load(id) {
	exclusion_form_defaults();
	exclusion_form_id = id;

	if (id > 0) {
		if (adesk_js_admin.pg_list_edit != 1) {
			adesk_ui_anchor_set(exclusion_list_anchor());
			alert(exclusion_form_str_cant_update);
			return;
		}

		adesk_ui_api_call(jsLoading);
		$("form_submit").className = "adesk_button_update";
		$("form_submit").value = jsUpdate;
		adesk_ajax_call_cb("awebdeskapi.php", "exclusion.exclusion_select_row", exclusion_form_load_cb, id);
	} else {
		if (adesk_js_admin.pg_list_edit != 1) {
			adesk_ui_anchor_set(exclusion_list_anchor());
			alert(exclusion_form_str_cant_insert);
			return;
		}

		$("form_submit").className = "adesk_button_add";
		$("form_submit").value = jsAdd;
		$("form").className = "adesk_block";
	}
}

function exclusion_form_load_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();
	if ( !ary.id ) {
		adesk_error_show(exclusion_form_str_cant_find);
		adesk_ui_anchor_set(exclusion_list_anchor());
		return;
	}

	exclusion_form_id = ary.id;

	$("form_id").value = ary.id;
	$("form_address").value = ary.email;
	$("form_address").disabled = true;
	$("matchtype").value = ary.matchtype;
	$("matchtype").disabled = true;

	if (ary.matchall == 1 && $("allradio")) {
		adesk_dom_radioset("target_field", "all");
	} else {
		ary.lists = ary.lists.toString().split(",");

		adesk_dom_radioset("target_field", "several");
		//$$(\'.listid_field\').each(function(e) { e.checked = false; }); if there\'s a bug with more than allowed checks checked, uncomment this one
		adesk_dom_boxset("listid_field", ary.lists);
		$("listbox").show();
	}

	$("form").className = "adesk_block";
}

function exclusion_form_save(id) {
	var post = adesk_form_post_alt($("form"));
	adesk_ui_api_call(jsSaving);

	if (id > 0)
		adesk_ajax_post_cb("awebdeskapi.php", "exclusion.exclusion_update_post", exclusion_form_save_cb, post);
	else
		adesk_ajax_post_cb("awebdeskapi.php", "exclusion.exclusion_insert_post", exclusion_form_save_cb, post);
}

function exclusion_form_save_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(exclusion_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}
}
'; ?>
