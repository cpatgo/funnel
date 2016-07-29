<?php /* Smarty version 2.6.12, created on 2016-07-08 16:53:15
         compiled from list.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'list.js', 1, false),array('modifier', 'alang', 'list.js', 2, false),array('modifier', 'js', 'list.js', 2, false),)), $this); ?>
var canAddList = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['canAddList']), $this);?>
;
var list_str_list_limit = '<?php echo ((is_array($_tmp=((is_array($_tmp='You have exceeded the number of allowed Lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list.list.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list.form.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list.delete.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list.copy.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "list.search.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


<?php echo '


// load HTML editor
tinyMCE.init({
	mode : "none",
	theme : "advanced",
	tab_focus : ":prev,:next"
	//onchange_callback : "editorContentChanged"
});


function list_process(loc, hist) {
	if ( loc == \'\') {
		loc = \'list-\' + list_list_sort + \'-\' + list_list_offset + \'-\' + list_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	$("list_list_count").className = "adesk_hidden";
	var func = null;
	try {
		func = eval("list_process_" + args[0]);
	} catch (e) {
		if (typeof list_process_list == "function")
			list_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function list_process_list(args) {
	if (args.length < 4)
		args = ["list", list_list_sort, list_list_offset, list_list_filter];

	list_list_sort = args[1];
	list_list_offset = args[2];
	list_list_filter = args[3];

	list_list_discern_sortclass();
	requestedtab = \'general\';

	paginators[1].paginate(list_list_offset);
}

function list_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	list_form_load(id);
}

function list_process_delete(args) {
	if (args.length < 2) {
		list_process_list(["list", list_list_sort, list_list_offset, list_list_filter]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	list_delete_check(id);
}

function list_process_copy(args) {
	if (args.length < 2) {
		list_process_list(["list", list_list_sort, list_list_offset, list_list_filter]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	list_copy_check(id);
}

function list_process_delete_multi(args) {
	$("list").className = "adesk_block";
	list_delete_check_multi();
}

function list_process_search(args) {
	$("list").className = "adesk_block";
	list_search_check();
}
'; ?>
