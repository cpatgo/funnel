<?php /* Smarty version 2.6.12, created on 2016-07-08 17:09:18
         compiled from form.delete.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'form.delete.js', 1, false),array('modifier', 'js', 'form.delete.js', 1, false),)), $this); ?>
var form_delete_str = '<?php echo ((is_array($_tmp=((is_array($_tmp="Are you sure you want to delete Subscription Form %s?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var form_delete_str_multi = '<?php echo ((is_array($_tmp=((is_array($_tmp="Are you sure you want to delete the following Subscription Form?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var form_delete_str_cant_delete = '<?php echo ((is_array($_tmp=((is_array($_tmp='You do not have permission to delete Subscription Forms')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
<?php echo '
var form_delete_id = 0;
var form_delete_id_multi = "";

function form_delete_check(id) {
	if (adesk_js_admin.pg_form_delete != 1) {
		adesk_ui_anchor_set(form_list_anchor());
		alert(form_delete_str_cant_delete);
		return;
	}

	if (id < 1) {
		form_delete_check_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "form.form_select_row", form_delete_check_cb, id);
}

function form_delete_check_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	adesk_dom_remove_children($("delete_list"));

	form_delete_id = ary.id;
	$("delete_message").innerHTML = sprintf(form_delete_str, ary.name);
	adesk_dom_display_block("delete");	// can\'t use toggle here in IE
}

function form_delete_check_multi() {
	if (adesk_js_admin.pg_form_delete != 1) {
		adesk_ui_anchor_set(form_list_anchor());
		alert(form_delete_str_cant_delete);
		return;
	}

	if (!adesk_form_check_selection_check($("list_table"), "multi[]", jsNothingSelected, jsNothingFound)) {
		adesk_ui_anchor_set(form_list_anchor());
		return;
	}

	var sel = adesk_form_check_selection_get($("list_table"), "multi[]");
	adesk_ajax_call_cb("awebdeskapi.php", "form.form_select_array", form_delete_check_multi_cb, 0, sel.join(","));
	form_delete_id_multi = sel.join(",");
}

function form_delete_check_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	$("delete_message").innerHTML = form_delete_str_multi;

	adesk_dom_remove_children($("delete_list"));
	if (!selectAllSwitch) {
		for (var i = 0; i < ary.row.length; i++)
			$("delete_list").appendChild(Builder.node("li", [ ary.row[i].name ]));
	} else {
		$("delete_list").appendChild(Builder.node("li", [ jsAllItemsWillBeDeleted ]));
	}

	adesk_dom_display_block("delete");
}

function form_delete(id) {
	if (form_delete_id_multi != "") {
		form_delete_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "form.form_delete", form_delete_cb, id);
}

function form_delete_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(form_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
}

function form_delete_multi() {
	if (selectAllSwitch) {
		adesk_ajax_call_cb("awebdeskapi.php", "form.form_delete_multi", form_delete_multi_cb, "_all", form_list_filter);
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "form.form_delete_multi", form_delete_multi_cb, form_delete_id_multi);
	form_delete_id_multi = "";
}

function form_delete_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(form_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
	$("acSelectAllCheckbox").checked = false;
	$(\'selectXPageAllBox\').className = \'adesk_hidden\';
}
'; ?>
