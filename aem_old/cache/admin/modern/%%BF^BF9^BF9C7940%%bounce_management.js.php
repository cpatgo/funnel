<?php /* Smarty version 2.6.12, created on 2016-07-18 12:02:28
         compiled from bounce_management.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'bounce_management.js', 7, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "bounce_management.list.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "bounce_management.form.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "bounce_management.delete.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "bounce_management.search.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "bounce_management.log.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

var bounce_management_listfilter = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['listfilter']), $this);?>
;

<?php echo '
function bounce_management_process(loc, hist) {
	if ( loc == \'\' ) {
		loc = \'list-\' + bounce_management_list_sort + \'-\' + bounce_management_list_offset + \'-\' + bounce_management_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("bounce_management_process_" + args[0]);

	} catch (e) {
		if (typeof bounce_management_process_list == "function")
			bounce_management_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function bounce_management_process_list(args) {
	if (args.length < 2)
		args = ["list", bounce_management_list_sort, bounce_management_list_offset, bounce_management_list_filter];

	bounce_management_list_sort = args[1];
	bounce_management_list_offset = args[2];
	bounce_management_list_filter = args[3];

	if ( bounce_management_listfilter > 0 ) $(\'JSListManager\').value = bounce_management_listfilter;

	bounce_management_list_discern_sortclass();

	paginators[1].paginate(bounce_management_list_offset);
}

function bounce_management_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	bounce_management_form_load(id);
}

function bounce_management_process_delete(args) {
	if (args.length < 2) {
		bounce_management_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	bounce_management_delete_check(id);
}

function bounce_management_process_delete_multi(args) {
	$("list").className = "adesk_block";
	bounce_management_delete_check_multi();
}

function bounce_management_process_search(args) {
	$("list").className = "adesk_block";
	bounce_management_search_check();
}

function bounce_management_process_log(args) {
	if (args.length < 2) {
		cron_process_list(["list", bounce_management_list_sort, bounce_management_list_offset, bounce_management_list_filter]);
		return;
	}

	var id = parseInt(args[1], 10);

	bounce_management_log(id);
}
'; ?>
