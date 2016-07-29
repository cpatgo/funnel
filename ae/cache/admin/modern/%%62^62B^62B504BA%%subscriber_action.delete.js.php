<?php /* Smarty version 2.6.12, created on 2016-07-18 12:03:31
         compiled from subscriber_action.delete.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'subscriber_action.delete.js', 1, false),array('modifier', 'js', 'subscriber_action.delete.js', 1, false),)), $this); ?>
var subscriber_action_delete_str = '<?php echo ((is_array($_tmp=((is_array($_tmp="Are you sure you want to delete subscription rule %s?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_action_delete_str_multi = '<?php echo ((is_array($_tmp=((is_array($_tmp="Are you sure you want to delete the following subscription rule?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_action_delete_str_cant_delete = '<?php echo ((is_array($_tmp=((is_array($_tmp='You do not have permission to delete subscription rule')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
<?php echo '
var subscriber_action_delete_id = 0;
var subscriber_action_delete_id_multi = "";

function subscriber_action_delete_check(id) {
	if (adesk_js_admin.pg_subscriber_add != 1 ||adesk_js_admin.pg_subscriber_delete != 1) {
		adesk_ui_anchor_set(subscriber_action_list_anchor());
		alert(subscriber_action_delete_str_cant_delete);
		return;
	}

	if (id < 1) {
		subscriber_action_delete_check_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "subscriber_action.subscriber_action_select_row", subscriber_action_delete_check_cb, id);
}

function subscriber_action_delete_check_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	adesk_dom_remove_children($("delete_list"));

	subscriber_action_delete_id = ary.id;
	$("delete_message").innerHTML = sprintf(subscriber_action_delete_str, ary.name);
	adesk_dom_display_block("delete");
}

function subscriber_action_delete_check_multi() {
	if (adesk_js_admin.pg_subscriber_delete != 1) {
		adesk_ui_anchor_set(subscriber_action_list_anchor());
		alert(subscriber_action_delete_str_cant_delete);
		return;
	}

	if (!adesk_form_check_selection_check($("list_table"), "multi[]", jsNothingSelected, jsNothingFound)) {
		adesk_ui_anchor_set(subscriber_action_list_anchor());
		return;
	}

	var sel = adesk_form_check_selection_get($("list_table"), "multi[]");
	adesk_ajax_call_cb("awebdeskapi.php", "subscriber_action.subscriber_action_select_array", subscriber_action_delete_check_multi_cb, 0, sel.join(","));
	subscriber_action_delete_id_multi = sel.join(",");
}

function subscriber_action_delete_check_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	$("delete_message").innerHTML = subscriber_action_delete_str_multi;

	adesk_dom_remove_children($("delete_list"));
	if (!selectAllSwitch) {
		for (var i = 0; i < ary.row.length; i++)
			$("delete_list").appendChild(Builder.node("li", [ ary.row[i].name ]));
	} else {
		t$("delete_list").appendChild(Builder.node("li", [ jsAllItemsWillBeDeleted ]));
	}

	adesk_dom_display_block("delete");
}

function subscriber_action_delete(id) {
	if (subscriber_action_delete_id_multi != "") {
		subscriber_action_delete_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "subscriber_action.subscriber_action_delete", subscriber_action_delete_cb, id);
}

function subscriber_action_delete_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(subscriber_action_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
}

function subscriber_action_delete_multi() {
	if (selectAllSwitch) {
		adesk_ajax_call_cb("awebdeskapi.php", "subscriber_action.subscriber_action_delete_multi", subscriber_action_delete_multi_cb, "_all", subscriber_action_list_filter);
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "subscriber_action.subscriber_action_delete_multi", subscriber_action_delete_multi_cb, subscriber_action_delete_id_multi);
	subscriber_action_delete_id_multi = "";
}

function subscriber_action_delete_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(subscriber_action_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
	$(\'selectXPageAllBox\').className = \'adesk_hidden\'; 
	$(\'acSelectAllCheckbox\').checked = false;
}
'; ?>
