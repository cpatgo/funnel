<?php /* Smarty version 2.6.12, created on 2016-07-08 14:47:32
         compiled from template.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'template.js', 7, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "template.list.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "template.form.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "template.import.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "template.delete.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "template.search.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

var template_listfilter = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['listfilter']), $this);?>
;

<?php echo '
function template_process(loc, hist) {
	if ( loc == \'\' ) {
		loc = \'list-\' + template_list_sort + \'-\' + template_list_offset + \'-\' + template_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	$("template_list_count").className = "adesk_hidden";
	$("import").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("template_process_" + args[0]);

	} catch (e) {
		if (typeof template_process_list == "function")
			template_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function template_process_list(args) {
	if (args.length < 2)
		args = ["list", template_list_sort, template_list_offset, template_list_filter];

	template_list_sort = args[1];
	template_list_offset = args[2];
	template_list_filter = args[3];

	if ( template_listfilter > 0 ) $(\'JSListManager\').value = template_listfilter;

	template_list_discern_sortclass();

	paginators[1].paginate(template_list_offset);
}

function template_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	template_form_load(id);
}

function template_process_import(args) {
	if (args.length < 1)
		args = ["import"];

	template_import_load();
}

function template_process_delete(args) {
	if (args.length < 2) {
		template_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	template_delete_check(id);
}

function template_process_delete_multi(args) {
	$("list").className = "adesk_block";
	template_delete_check_multi();
}

function template_process_search(args) {
	$("list").className = "adesk_block";
	template_search_check();
}
'; ?>
