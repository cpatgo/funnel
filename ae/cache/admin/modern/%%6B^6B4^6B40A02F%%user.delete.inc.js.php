<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:34
         compiled from user.delete.inc.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'user.delete.inc.js', 1, false),)), $this); ?>
var user_delete_check_lists = '<?php echo ((is_array($_tmp='Delete all lists created by this user')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
';
<?php echo '
function user_delete_check_extra() {
	$("delete_message").innerHTML += "<br><br><input type=\'checkbox\' id=\'delete_lists\' value=\'1\'> " + user_delete_check_lists;
}

function user_delete_custom(id) {
	adesk_ajax_call_cb("awebdeskapi.php", "user!adesk_user_delete", user_delete_cb, id, ( $("delete_lists") && $("delete_lists").checked ) ? 1 : 0);
}

function user_delete_multi_custom(multi) {
	adesk_ajax_call_cb("awebdeskapi.php", "user!adesk_user_delete_multi", user_delete_multi_cb, user_delete_id_multi, ( $("delete_lists") && $("delete_lists").checked ) ? 1 : 0);
}
'; ?>
