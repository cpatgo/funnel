<?php /* Smarty version 2.6.12, created on 2016-07-08 16:21:25
         compiled from service.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'service.js', 4, false),array('modifier', 'alang', 'service.js', 6, false),array('modifier', 'js', 'service.js', 6, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "service.list.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "service.form.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['__ishosted'],'name' => 'service_ishosted'), $this);?>


var service_edit_no = '<?php echo ((is_array($_tmp=((is_array($_tmp="You do not have permission to edit this External Service.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var service_delete_no = '<?php echo ((is_array($_tmp=((is_array($_tmp="You do not have permission to delete External Services.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

<?php echo '
function service_process(loc, hist) {
	if ( loc == \'\' ) {
		loc = \'list-\' + service_list_sort + \'-\' + service_list_offset + \'-\' + service_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("service_process_" + args[0]);

	} catch (e) {
		if (typeof service_process_list == "function")
			service_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function service_process_list(args) {
	if (args.length < 2)
		args = ["list", service_list_sort, service_list_offset, service_list_filter];

	service_list_sort = args[1];
	service_list_offset = args[2];
	service_list_filter = args[3];

	service_list_discern_sortclass();

	paginators[1].paginate(service_list_offset);
}

function service_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	service_form_load(id);
}

function service_process_delete(args) {
	alert(service_delete_no);
	service_process_list(["list", "0", "0", "0"]);
	return;

	if (args.length < 2) {
		service_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	service_delete_check(id);
}

function service_process_delete_multi(args) {
	$("list").className = "adesk_block";
	service_delete_check_multi();
}

function service_process_search(args) {
	$("list").className = "adesk_block";
	service_search_check();
}
'; ?>
