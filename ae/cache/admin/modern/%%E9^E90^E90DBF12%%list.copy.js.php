<?php /* Smarty version 2.6.12, created on 2016-07-08 16:53:15
         compiled from list.copy.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'list.copy.js', 1, false),array('modifier', 'js', 'list.copy.js', 1, false),)), $this); ?>
var list_copy_str = '<?php echo ((is_array($_tmp=((is_array($_tmp="Are you sure you want to copy list %s?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var list_copy_str_cant_copy = '<?php echo ((is_array($_tmp=((is_array($_tmp='You do not have permission to copy lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
<?php echo '
var list_copy_id = 0;

function list_copy_check(id) {
	if (!canAddList || adesk_js_admin.pg_list_edit != 1) {
		adesk_ui_anchor_set(list_list_anchor());
		alert(list_copy_str_cant_copy);
		return;
	}

	// List Limit check
	if (!canAddList) {
		adesk_ui_anchor_set(list_list_anchor());
		alert(list_str_list_limit);
		return;
	}

	adesk_ajax_call_cb("awebdeskapi.php", "list.list_select_row", list_copy_check_cb, id);
}

function list_copy_check_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	list_copy_id = ary.id;
	$("copy_message").innerHTML = sprintf(list_copy_str, ary.name);

	$("copy_bounce").checked          = true;
	$("copy_exclusion").checked       = true;
	$("copy_filter").checked          = true;
	$("copy_header").checked          = true;
	$("copy_personalization").checked = true;
	$("copy_template").checked        = true;
	$("copy_field").checked           = true;
	$("copy_form").checked            = true;
	$("copy_subscriber").checked      = false;

	$("copy").style.display = "";
}

function list_copy(id) {
	var post = adesk_form_post("copy_pref");
	post.id = id;

	adesk_ajax_post_cb("awebdeskapi.php", "list.list_copy", list_copy_cb, post);
}

function list_copy_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(list_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	$("copy").style.display = "none";
}

'; ?>
