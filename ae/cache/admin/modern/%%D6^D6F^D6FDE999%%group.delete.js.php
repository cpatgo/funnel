<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:45
         compiled from group.delete.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'group.delete.js', 1, false),array('modifier', 'js', 'group.delete.js', 1, false),)), $this); ?>
var group_str_check = '<?php echo ((is_array($_tmp=((is_array($_tmp="Are you sure you want to delete the group %s?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var group_str_check_multi = '<?php echo ((is_array($_tmp=((is_array($_tmp="Are you sure you want to delete the following groups?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var group_delete_str_cant_delete = '<?php echo ((is_array($_tmp=((is_array($_tmp='You do not have permission to delete groups')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
<?php echo '
var group_delete_id = 0;
var group_delete_id_multi = "";

function group_delete_check(id) {
	if (typeof group_can_delete == "function" && !group_can_delete()) {
		adesk_ui_anchor_set(group_list_anchor());
		alert(group_delete_str_cant_delete);
		return;
	}
	if (id < 1) {
		group_delete_check_multi();
		return;
	}
	adesk_ajax_call_cb("awebdeskapi.php", "group!adesk_group_select_row", group_delete_check_cb, id);
}

function group_delete_check_cb(xml) {
	var ary = adesk_dom_read_node(xml, null);

	adesk_dom_remove_children($("delete_list"));

	group_delete_id = ary.id;
	if ( $("group_" + ary.id) ) $("group_" + ary.id).style.display = "none"; // hide the group we are deleting in "move to" list
	$("delete_message").innerHTML = sprintf(group_str_check, ary.title);
	adesk_dom_display_block("delete");
}

function group_delete_check_multi() {
	if (typeof group_can_delete == "function" && !group_can_delete()) {
		adesk_ui_anchor_set(group_list_anchor());
		alert(group_delete_str_cant_delete);
		return;
	}
	if (!adesk_form_check_selection_check($("list_table"), "multi[]", jsNothingSelected, jsNothingFound)) {
		return;
	}

	var sel = adesk_form_check_selection_get($("list_table"), "multi[]");
	adesk_ajax_call_cb("awebdeskapi.php", "group!adesk_group_select_array", group_delete_check_multi_cb, 0, sel.join(","));
	group_delete_id_multi = sel.join(",");
}

function group_delete_check_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml, null);

	$("delete_message").innerHTML = group_str_check_multi;

	adesk_dom_remove_children($("delete_list"));
	if (!selectAllSwitch) {
		for (var i = 0; i < ary.row.length; i++) {
			$("delete_list").appendChild(Builder.node("li", [ ary.row[i].title ]));
			if ( $("group_" + ary.row[i].id) ) $("group_" + ary.row[i].id).style.display = "none"; // hide the group we are deleting in "move to" list
		}
	} else {
		t$("delete_list").appendChild(Builder.node("li", [ jsAllItemsWillBeDeleted ]));
	}

	adesk_dom_display_block("delete");
}

function group_delete(id) {
	if (group_delete_id_multi != "") {
		group_delete_multi();
		return;
	}
	var alt = $("delete_alt").value;
	adesk_ajax_call_cb("awebdeskapi.php", "group!adesk_group_delete", group_delete_cb, id, alt);
}

function group_delete_cb(xml) {
	var ary = adesk_dom_read_node(xml, null);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(group_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
}

function group_delete_multi() {
	var alt = $("delete_alt").value;
	adesk_ajax_call_cb("awebdeskapi.php", "group!adesk_group_delete_multi", group_delete_multi_cb, group_delete_id_multi, alt);
	group_delete_id_multi = "";
}

function group_delete_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml, null);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(group_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("delete", "block");
}
'; ?>
