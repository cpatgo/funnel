<?php /* Smarty version 2.6.12, created on 2016-07-28 11:05:46
         compiled from exclusion.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'exclusion.js', 6, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "exclusion.list.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "exclusion.form.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "exclusion.delete.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "exclusion.search.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

var exclusion_listfilter = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['listfilter']), $this);?>
;

<?php echo '
function exclusion_process(loc, hist) {
	if ( loc == \'\' ) {
		loc = \'list-\' + exclusion_list_sort + \'-\' + exclusion_list_offset + \'-\' + exclusion_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	$("exclusion_list_count").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("exclusion_process_" + args[0]);

	} catch (e) {
		if (typeof exclusion_process_list == "function")
			exclusion_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function exclusion_process_list(args) {
	if (args.length < 2)
		args = ["list", exclusion_list_sort, exclusion_list_offset, exclusion_list_filter];

	exclusion_list_sort = args[1];
	exclusion_list_offset = args[2];
	exclusion_list_filter = args[3];

	if ( exclusion_listfilter > 0 ) $(\'JSListManager\').value = exclusion_listfilter;

	exclusion_list_discern_sortclass();

	paginators[1].paginate(exclusion_list_offset);
}

function exclusion_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	exclusion_form_load(id);
}

function exclusion_process_delete(args) {
	if (args.length < 2) {
		exclusion_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	exclusion_delete_check(id);
}

function exclusion_process_delete_multi(args) {
	$("list").className = "adesk_block";
	exclusion_delete_check_multi();
}

function exclusion_process_search(args) {
	$("list").className = "adesk_block";
	exclusion_search_check();
}
'; ?>
