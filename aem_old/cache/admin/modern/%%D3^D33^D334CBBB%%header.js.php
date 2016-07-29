<?php /* Smarty version 2.6.12, created on 2016-07-18 12:02:44
         compiled from header.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'header.js', 6, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.list.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.form.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.delete.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.search.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

var header_listfilter = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['listfilter']), $this);?>
;

<?php echo '
function header_process(loc, hist) {
	if ( loc == \'\' ) {
		loc = \'list-\' + header_list_sort + \'-\' + header_list_offset + \'-\' + header_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	var func = null;
	try {
		func = eval("header_process_" + args[0]);
	} catch (e) {
		if (typeof header_process_list == "function")
			header_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function header_process_list(args) {
	if (args.length < 2)
		args = ["list", header_list_sort, header_list_offset, header_list_filter];

	header_list_sort = args[1];
	header_list_offset = args[2];
	header_list_filter = args[3];

	if ( header_listfilter > 0 ) $(\'JSListManager\').value = header_listfilter;

	header_list_discern_sortclass();

	paginators[1].paginate(header_list_offset);
}

function header_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	header_form_load(id);
}

function header_process_delete(args) {
	if (args.length < 2) {
		header_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	header_delete_check(id);
}

function header_process_delete_multi(args) {
	$("list").className = "adesk_block";
	header_delete_check_multi();
}

function header_process_search(args) {
	$("list").className = "adesk_block";
	header_search_check();
}
'; ?>
