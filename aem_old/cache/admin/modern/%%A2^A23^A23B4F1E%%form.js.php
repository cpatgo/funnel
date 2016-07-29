<?php /* Smarty version 2.6.12, created on 2016-07-08 17:09:18
         compiled from form.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'form.js', 7, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form.list.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form.form.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form.view.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form.delete.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form.search.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

var form_listfilter = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['listfilter']), $this);?>
;

adesk_editor_init_normal();

<?php echo '
function form_process(loc, hist) {
	if ( loc == \'\' ) {
		loc = \'list-\' + form_list_sort + \'-\' + form_list_offset + \'-\' + form_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	$("form_list_count").className = "adesk_hidden";
	$("view").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("form_process_" + args[0]);

	} catch (e) {
		if (typeof form_process_list == "function")
			form_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function form_process_list(args) {
	if (args.length < 2)
		args = ["list", form_list_sort, form_list_offset, form_list_filter];

	form_list_sort = args[1];
	form_list_offset = args[2];
	form_list_filter = args[3];

	if ( form_listfilter > 0 ) $(\'JSListManager\').value = form_listfilter;

	form_list_discern_sortclass();

	// set default tab shown on Integration page
	var form_other_tab = (adesk_js_site.general_public) ? \'public\' : \'api\';
	form_list_other_cycle(form_other_tab);

	paginators[1].paginate(form_list_offset);
}

function form_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	form_form_load(id);
}

function form_process_view(args) {
	if (args.length < 2)
		args = ["view", "0"];

	var id = parseInt(args[1], 10);

	form_view_load(id, \'html\');
}

function form_process_delete(args) {
	if (args.length < 2) {
		form_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	form_delete_check(id);
}

function form_process_delete_multi(args) {
	$("list").className = "adesk_block";
	form_delete_check_multi();
}

function form_process_search(args) {
	$("list").className = "adesk_block";
	form_search_check();
}
'; ?>
