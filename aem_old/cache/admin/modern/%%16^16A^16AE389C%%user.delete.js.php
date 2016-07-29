<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:34
         compiled from user.delete.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'user.delete.js', 1, false),array('modifier', 'js', 'user.delete.js', 1, false),)), $this); ?>
var user_str_check = '<?php echo ((is_array($_tmp=((is_array($_tmp="Are you sure you want to delete the user %s (%s %s)?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var user_str_check_multi = '<?php echo ((is_array($_tmp=((is_array($_tmp="Are you sure you want to delete the following users?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var user_delete_str_cant_delete = '<?php echo ((is_array($_tmp=((is_array($_tmp='You do not have permission to delete users')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
<?php echo '
var user_delete_id = 0;
var user_delete_id_multi = "";

function user_delete_check(id) {
	if (typeof user_can_delete == "function" && !user_can_delete()) {
		adesk_ui_anchor_set(user_list_anchor());
		alert(user_delete_str_cant_delete);
		return;
	}
	if (id < 1) {
		user_delete_check_multi();
		return;
	}

	adesk_ajax_call_cb("awebdeskapi.php", "user!adesk_user_select_row", user_delete_check_cb, id);
}

function user_delete_check_cb(xml, text) {
	var ary = adesk_dom_read_node(xml, null);

	adesk_dom_remove_children($("delete_list"));
	user_delete_id = ary.id;

	$("delete_message").innerHTML =
		sprintf(jsUserDeleteMessage, ary.username, ary.first_name, ary.last_name);

	if (typeof user_delete_check_extra == "function")
		user_delete_check_extra(ary);

	adesk_dom_display_block("delete");
}

function user_delete_check_cancel() {
	adesk_dom_toggle_display("delete", "block");
	adesk_ui_anchor_set(user_list_anchor());
}

function user_delete(id) {
	if (user_delete_id_multi != "") {
		user_delete_multi();
		return;
	}

	if (typeof user_delete_custom == "function")
		user_delete_custom(id);
	else
		adesk_ajax_call_cb("awebdeskapi.php", "user!adesk_user_delete", user_delete_cb, id);

	adesk_dom_toggle_display("delete", "block");
}

function user_delete_cb(xml, text) {
	var ary = adesk_dom_read_node(xml, null);

	if (ary.succeeded) {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(user_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}
}

function user_delete_check_multi() {
	if (typeof user_can_delete == "function" && !user_can_delete()) {
		adesk_ui_anchor_set(user_list_anchor());
		alert(user_delete_str_cant_delete);
		return;
	}
	if (!adesk_form_check_selection_check($("list_table"), "multi[]", jsNothingSelected, jsNothingFound)) {
		return;
	}

	var sel = adesk_form_check_selection_get($("list_table"), "multi[]");

	if (typeof user_delete_check_extra == "function")
		user_delete_check_extra();

	adesk_ajax_call_cb("awebdeskapi.php", "user!adesk_user_select_array", user_delete_check_multi_cb, 0, 0, sel.join(","));
	user_delete_id_multi = sel.join(",");
}

function user_delete_check_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml, null);

	adesk_dom_remove_children($("delete_list"));
	$("delete_message").innerHTML = user_str_check_multi;

	adesk_dom_remove_children($("delete_list"));
	if (!selectAllSwitch) {
		for (var i = 0; i < ary.row.length; i++)
			$("delete_list").appendChild(Builder.node("li", [ ary.row[i].username ]));
	} else {
		t$("delete_list").appendChild(Builder.node("li", [ jsAllItemsWillBeDeleted ]));
	}

	adesk_dom_display_block("delete");
}

function user_delete_multi() {
	if (typeof user_delete_multi_custom == "function")
		user_delete_multi_custom(user_delete_id_multi);
	else
		adesk_ajax_call_cb("awebdeskapi.php", "user!adesk_user_delete_multi", user_delete_multi_cb, user_delete_id_multi);

	user_delete_id_multi = "";
}

function user_delete_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml, null);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(user_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
}
'; ?>
