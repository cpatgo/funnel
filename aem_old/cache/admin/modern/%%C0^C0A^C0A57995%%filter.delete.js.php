<?php /* Smarty version 2.6.12, created on 2016-07-27 12:32:21
         compiled from filter.delete.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'filter.delete.js', 1, false),array('modifier', 'js', 'filter.delete.js', 1, false),)), $this); ?>
var filter_delete_str = '<?php echo ((is_array($_tmp=((is_array($_tmp="Are you sure you want to delete filter %s?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var filter_delete_str_multi = '<?php echo ((is_array($_tmp=((is_array($_tmp="Are you sure you want to delete the following filter?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var filter_delete_str_cant_delete = '<?php echo ((is_array($_tmp=((is_array($_tmp='You do not have permission to delete filters')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
<?php echo '
var filter_delete_id = 0;
var filter_delete_id_multi = "";

function filter_delete_check(id) {
	if (adesk_js_admin.pg_subscriber_filters != 1) {
		adesk_ui_anchor_set(filter_list_anchor());
		alert(filter_delete_str_cant_delete);
		return;
	}

	if (id < 1) {
		filter_delete_check_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "filter.filter_select_row", filter_delete_check_cb, id);
}

function filter_delete_check_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	adesk_dom_remove_children($("delete_list"));

	filter_delete_id = ary.id;
	$("delete_message").innerHTML = sprintf(filter_delete_str, ary.name);
	adesk_dom_display_block("delete");	// can\'t use toggle here in IE
}

function filter_delete_check_multi() {
	if (adesk_js_admin.pg_subscriber_filters != 1) {
		adesk_ui_anchor_set(filter_list_anchor());
		alert(filter_delete_str_cant_delete);
		return;
	}

	if (!adesk_form_check_selection_check($("list_table"), "multi[]", jsNothingSelected, jsNothingFound)) {
		adesk_ui_anchor_set(filter_list_anchor());
		return;
	}

	var sel = adesk_form_check_selection_get($("list_table"), "multi[]");
	adesk_ajax_call_cb("awebdeskapi.php", "filter.filter_select_array", filter_delete_check_multi_cb, 0, sel.join(","));
	filter_delete_id_multi = sel.join(",");
}

function filter_delete_check_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	$("delete_message").innerHTML = filter_delete_str_multi;

	adesk_dom_remove_children($("delete_list"));
	if (!selectAllSwitch) {
		for (var i = 0; i < ary.row.length; i++)
			$("delete_list").appendChild(Builder.node("li", [ ary.row[i].name ]));
	} else {
		$("delete_list").appendChild(Builder.node("li", [ jsAllItemsWillBeDeleted ]));
	}

	adesk_dom_display_block("delete");
}

function filter_delete(id) {
	if (filter_delete_id_multi != "") {
		filter_delete_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "filter.filter_hide", filter_delete_cb, id);
}

function filter_delete_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(filter_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
}

function filter_delete_multi() {
	if (selectAllSwitch) {
		adesk_ajax_call_cb("awebdeskapi.php", "filter.filter_hide_multi", filter_delete_multi_cb, "_all", filter_list_filter);
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "filter.filter_hide_multi", filter_delete_multi_cb, filter_delete_id_multi);
	filter_delete_id_multi = "";
}

function filter_delete_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(filter_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
	$("acSelectAllCheckbox").checked = false;
	$(\'selectXPageAllBox\').className = \'adesk_hidden\';
}
'; ?>
