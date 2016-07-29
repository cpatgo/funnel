<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:45
         compiled from group.form.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'group.form.js', 1, false),array('modifier', 'js', 'group.form.js', 1, false),)), $this); ?>
var group_form_str_cant_update = '<?php echo ((is_array($_tmp=((is_array($_tmp='You do not have permission to edit groups')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var group_form_str_cant_insert = '<?php echo ((is_array($_tmp=((is_array($_tmp='You do not have permission to add groups')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var group_form_str_notitle     = '<?php echo ((is_array($_tmp=((is_array($_tmp="You need to give this group a name.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
<?php echo '
var group_form_id = 0;

function group_form_defaults() {
	$("form_id").value = 0;
	$("form_title").value = \'\';
	$("form_descript").value = \'\';

	if (typeof group_form_defaults_extended == "function")
		group_form_defaults_extended();
}

function group_form_load(id) {
	group_form_defaults();
	group_form_id = id;

	if (id > 0) {
		if (typeof group_can_edit == "function" && !group_can_edit()) {
			adesk_ui_anchor_set(group_list_anchor());
			alert(group_form_str_cant_update);
			return;
		}
		adesk_ui_api_call(jsLoading);
		if ($("form_submit")) {
			$("form_submit").className = "adesk_button_update";
			$("form_submit").value = jsUpdate;
		}
		adesk_ajax_call_cb("awebdeskapi.php", "group!adesk_group_select_row", group_form_load_cb, id);
	} else {
		if (typeof group_can_add == "function" && !group_can_add()) {
			adesk_ui_anchor_set(group_list_anchor());
			alert(group_form_str_cant_insert);
			return;
		}
		if ($("form_submit")) {
			$("form_submit").className = "adesk_button_add";
			$("form_submit").value = jsAdd;
		}
		$("form").className = "adesk_block";
	}
}

function group_form_load_cb(xml) {
	var ary = adesk_dom_read_node(xml, null);
	adesk_ui_api_callback();
	group_form_id = ary.id;

	$("form_id").value = ary.id;
	$("form_title").value = ary.title;
	$("form_descript").value = ary.descript;

	if (ary.id == 3) {
		$("form_admin_limitations").style.display = "";
	} else {
		$("form_admin_limitations").style.display = "none";
	}

	if (typeof group_form_load_cb_extended == "function")
		group_form_load_cb_extended(ary);

	$("form").className = "adesk_block";
}

function group_form_save(id) {
	var post = adesk_form_post($("form"));

	if (post.title == "" || post.title.match(/^ +$/)) {
		alert(group_form_str_notitle);
		return false;
	}

	if (typeof group_form_save_extended_check == "function") {
		if (!group_form_save_extended_check())
			return false;
	}

	adesk_ui_api_call(jsSaving);

	if (id > 0)
		adesk_ajax_post_cb("awebdeskapi.php", "group!adesk_group_update_post", group_form_save_cb, post);
	else
		adesk_ajax_post_cb("awebdeskapi.php", "group!adesk_group_insert_post", group_form_save_cb, post);
}

function group_form_save_cb(xml) {
	var ary = adesk_dom_read_node(xml, null);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(group_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}
}
'; ?>
