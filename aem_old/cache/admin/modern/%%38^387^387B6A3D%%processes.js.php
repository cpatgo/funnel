<?php /* Smarty version 2.6.12, created on 2016-07-08 14:41:08
         compiled from processes.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'processes.js', 6, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "processes.list.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "processes.form.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "processes.delete.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "processes.search.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

var processes_actionfilter = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['actionfilter']), $this);?>
;
var processes_statusfilter = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['statusfilter']), $this);?>
;

<?php echo '
function processes_process(loc, hist) {
	if ( loc == \'\' ) {
		loc = \'list-\' + processes_list_sort + \'-\' + processes_list_offset + \'-\' + processes_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("processes_process_" + args[0]);

	} catch (e) {
		if (typeof processes_process_list == "function")
			processes_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function processes_process_list(args) {
	if (args.length < 2)
		args = ["list", processes_list_sort, processes_list_offset, processes_list_filter];

	processes_list_sort = args[1];
	processes_list_offset = args[2];
	processes_list_filter = args[3];

	if ( processes_actionfilter > 0 ) $(\'JSActionManager\').value = processes_actionfilter;
	if ( processes_statusfilter > 0 ) $(\'JSStatusManager\').value = processes_statusfilter;

	processes_list_discern_sortclass();

	paginators[1].paginate(processes_list_offset);
}

function processes_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	// first remove old timers (progress bars)
	for ( var i in adesk_progress_bars ) {
		adesk_progressbar_unregister(i);
	}

	processes_form_load(id);
}

function processes_process_delete(args) {
	if (args.length < 2) {
		processes_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	processes_delete_check(id);
}

function processes_process_delete_multi(args) {
	$("list").className = "adesk_block";
	processes_delete_check_multi();
}

function processes_process_search(args) {
	$("list").className = "adesk_block";
	processes_search_check();
}
'; ?>
