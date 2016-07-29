<?php /* Smarty version 2.6.12, created on 2016-07-27 12:32:21
         compiled from filter.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'filter.js', 6, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "filter.list.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "filter.form.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "filter.delete.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "filter.search.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

var filter_listfilter = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['listfilter']), $this);?>
;

<?php echo '
function filter_process(loc, hist) {
	if ( loc == \'\' ) {
		loc = \'list-\' + filter_list_sort + \'-\' + filter_list_offset + \'-\' + filter_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden_ie";
	$("form").className = "adesk_hidden_ie";
	$("filter_list_count").className = "adesk_hidden_ie";
	var func = null;
	try {
		var func = eval("filter_process_" + args[0]);

	} catch (e) {
		if (typeof filter_process_list == "function")
			filter_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function filter_process_list(args) {
	if (args.length < 2)
		args = ["list", filter_list_sort, filter_list_offset, filter_list_filter];

	filter_list_sort = args[1];
	filter_list_offset = args[2];
	filter_list_filter = args[3];

	if ( filter_listfilter > 0 ) $(\'JSListManager\').value = filter_listfilter;

	filter_list_discern_sortclass();

	paginators[1].paginate(filter_list_offset);
}

function filter_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	filter_form_load(id);
}

function filter_process_delete(args) {
	if (args.length < 2) {
		filter_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	filter_delete_check(id);
}

function filter_process_delete_multi(args) {
	$("list").className = "adesk_block";
	filter_delete_check_multi();
}

function filter_process_search(args) {
	$("list").className = "adesk_block";
	filter_search_check();
}
'; ?>
