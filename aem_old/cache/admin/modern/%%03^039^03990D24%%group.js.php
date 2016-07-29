<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:45
         compiled from group.js */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "group.list.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "group.form.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "group.delete.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "group.search.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo '
function group_process(loc, hist) {
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	try {
		var func = eval("group_process_" + args[0]);

		if (typeof func == "function")
			func(args);
	} catch (e) {
		if (typeof group_process_list == "function")
			group_process_list(args);
	}
}

function group_process_list(args) {
	if (args.length < 2)
		args = ["list", group_list_sort, group_list_offset, group_list_filter];

	group_list_sort = args[1];
	group_list_offset = args[2];
	group_list_filter = args[3];

	group_list_discern_sortclass();

	paginators[1].paginate(group_list_offset);
}

function group_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	group_form_load(id);
}

function group_process_delete(args) {
	if (args.length < 2) {
		group_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	group_delete_check(id);
}

function group_process_delete_multi(args) {
	$("list").className = "adesk_block";
	group_delete_check_multi();
}

function group_process_search(args) {
	$("list").className = "adesk_block";
	group_search_check();
}
'; ?>
