<?php /* Smarty version 2.6.12, created on 2016-07-08 14:41:25
         compiled from cron.delete.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'cron.delete.js', 1, false),array('modifier', 'js', 'cron.delete.js', 1, false),)), $this); ?>
var cron_delete_str = '<?php echo ((is_array($_tmp=((is_array($_tmp="Are you sure you want to delete Cron Job %s?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var cron_delete_str_multi = '<?php echo ((is_array($_tmp=((is_array($_tmp="Are you sure you want to delete the following Cron Job?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var cron_delete_str_cant_delete = '<?php echo ((is_array($_tmp=((is_array($_tmp="You do not have permission to delete Cron Jobs.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
<?php echo '
var cron_delete_id = 0;
var cron_delete_id_multi = "";

function cron_delete_check(id) {
	if (adesk_js_admin.id != 1 || id <= cron_protected) {
		adesk_ui_anchor_set(cron_list_anchor());
		alert(cron_delete_str_cant_delete);
		return;
	}

	if (id < 1) {
		cron_delete_check_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "cron!adesk_cron_select_row", cron_delete_check_cb, id);
}

function cron_delete_check_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	adesk_dom_remove_children($("delete_list"));

	cron_delete_id = ary.id;
	$("delete_message").innerHTML = sprintf(cron_delete_str, ary.name);
	adesk_dom_display_block("delete");	// can\'t use toggle here in IE
}

function cron_delete_check_multi() {
	if (adesk_js_admin.id != 1) {
		adesk_ui_anchor_set(cron_list_anchor());
		alert(cron_delete_str_cant_delete);
		return;
	}

	if (!adesk_form_check_selection_check($("list_table"), "multi[]", jsNothingSelected, jsNothingFound)) {
		adesk_ui_anchor_set(cron_list_anchor());
		return;
	}

	var sel = adesk_form_check_selection_get($("list_table"), "multi[]");
	// clean up protected ones
	for ( var i = 1; i <= cron_protected; i++ ) {
		sel = adesk_array_remove(i, sel, true);
	}
	if ( sel.length == 0 ) {
		alert(jsNothingSelected);
		adesk_ui_anchor_set(cron_list_anchor());
		return;
	}

	adesk_ajax_call_cb("awebdeskapi.php", "cron!adesk_cron_select_array", cron_delete_check_multi_cb, 0, sel.join(","));
	cron_delete_id_multi = sel.join(",");
}

function cron_delete_check_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	$("delete_message").innerHTML = cron_delete_str_multi;

	adesk_dom_remove_children($("delete_list"));
	if (!selectAllSwitch) {
		for (var i = 0; i < ary.row.length; i++)
			$("delete_list").appendChild(Builder.node("li", [ ary.row[i].name ]));
	} else {
		$("delete_list").appendChild(Builder.node("li", [ jsAllItemsWillBeDeleted ]));
	}

	adesk_dom_display_block("delete");
}

function cron_delete(id) {
	if (cron_delete_id_multi != "") {
		cron_delete_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "cron!adesk_cron_delete", cron_delete_cb, id);
}

function cron_delete_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(cron_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
}

function cron_delete_multi() {
	if (selectAllSwitch) {
		adesk_ajax_call_cb("awebdeskapi.php", "cron!adesk_cron_delete_multi", cron_delete_multi_cb, "_all", cron_list_filter);
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "cron!adesk_cron_delete_multi", cron_delete_multi_cb, cron_delete_id_multi);
	cron_delete_id_multi = "";
}

function cron_delete_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(cron_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
	$("acSelectAllCheckbox").checked = false;
}
'; ?>
