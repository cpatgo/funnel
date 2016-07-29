<?php /* Smarty version 2.6.12, created on 2016-07-08 14:15:47
         compiled from subscriber.optin.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'subscriber.optin.js', 1, false),array('modifier', 'js', 'subscriber.optin.js', 1, false),)), $this); ?>
var subscriber_optin_str = '<?php echo ((is_array($_tmp=((is_array($_tmp="Are you sure you want to send an email reminder to an unconfirmed subscriber %s?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_optin_str_multi = '<?php echo ((is_array($_tmp=((is_array($_tmp="Are you sure you want to send an email reminder to the following subscribers?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_optin_str_cant_send = '<?php echo ((is_array($_tmp=((is_array($_tmp='You do not have permission to send email reminders')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_optin_str_all = '<?php echo ((is_array($_tmp=((is_array($_tmp='Email reminder will be sent to all unconfirmed subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
<?php echo '
var subscriber_optin_id = 0;
var subscriber_optin_id_multi = "";

function subscriber_optin_check(id) {
	if (!subscriber_canadd) {
		adesk_ui_anchor_set(subscriber_list_anchor());
		alert(subscriber_optin_str_cant_send);
		return;
	}

	if (id < 1) {
		subscriber_optin_check_multi();
		return;
	}

	adesk_dom_remove_children($("optin_list"));

	adesk_ajax_call_cb("awebdeskapi.php", "subscriber.subscriber_select_row", subscriber_optin_check_cb, id);
}

function subscriber_optin_check_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	subscriber_optin_id = ary.id;
	$("optin_message").innerHTML = sprintf(subscriber_optin_str, ary.email);
	adesk_dom_display_block("optin");
}

function subscriber_optin_check_multi() {
	if (!subscriber_canadd) {
		adesk_ui_anchor_set(subscriber_list_anchor());
		alert(subscriber_optin_str_cant_send);
		return;
	}

	if (!adesk_form_check_selection_check($("list_table"), "multi[]", jsNothingSelected, jsNothingFound)) {
		adesk_ui_anchor_set(subscriber_list_anchor());
		return;
	}

	var sel = adesk_form_check_selection_get($("list_table"), "multi[]");
	adesk_dom_remove_children($("optin_list"));
	adesk_ajax_call_cb("awebdeskapi.php", "subscriber.subscriber_select_array_alt", subscriber_optin_check_multi_cb, 0, sel.join(","));
	subscriber_optin_id_multi = sel.join(",");
}

function subscriber_optin_check_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	$("optin_message").innerHTML = subscriber_optin_str_multi;

	adesk_dom_remove_children($("optin_list"));

	if (!selectAllSwitch) {
		for (var i = 0; i < ary.row.length; i++)
			$("optin_list").appendChild(Builder.node("li", [ ary.row[i].email ]));
	} else {
		$("optin_list").appendChild(Builder.node("li", [ subscriber_optin_str_all ]));
	}

	adesk_dom_display_block("optin");
}

function subscriber_optin(id) {
	if (subscriber_optin_id_multi != "") {
		subscriber_optin_multi();
		return;
	}

	var post = adesk_form_post("optin");
	post.id = id;

	adesk_ajax_post_cb("awebdeskapi.php", "subscriber.subscriber_optin_post", subscriber_optin_cb, post);
}

function subscriber_optin_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(subscriber_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("optin", "block");
}

function subscriber_optin_multi() {
	var post = adesk_form_post("optin");

	if (selectAllSwitch) {
		post.ids = "_all";
		post.filter = subscriber_list_filter;
		adesk_ajax_post_cb("awebdeskapi.php", "subscriber.subscriber_optin_multi_post", subscriber_optin_multi_cb, post);
		return;
	}

	post.ids = subscriber_optin_id_multi;
	adesk_ajax_post_cb("awebdeskapi.php", "subscriber.subscriber_optin_multi_post", subscriber_optin_multi_cb, post);
	subscriber_optin_id_multi = "";
}

function subscriber_optin_multi_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(subscriber_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("optin", "block");
}
'; ?>
