<?php /* Smarty version 2.6.12, created on 2016-07-08 14:15:46
         compiled from subscriber.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'subscriber.js', 8, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "subscriber.list.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "subscriber.exportlist.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "subscriber.form.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "subscriber.delete.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "subscriber.search.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "subscriber.optin.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

var subscriber_listfilter = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['listfilter']), $this);?>
;
var subscriber_statfilter = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['statfilter']), $this);?>
;
var subscriber_canadd = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['canAddSubscriber']), $this);?>
;
var subscriber_canimport = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['canImportSubscriber']), $this);?>
;

<?php echo '
function subscriber_process(loc, hist) {
	if ( loc == \'\' ) {
		loc = \'list-\' + subscriber_list_sort + \'-\' + subscriber_list_offset + \'-\' + subscriber_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	$("subscriber_list_count").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("subscriber_process_" + args[0]);

	} catch (e) {
		if (typeof subscriber_process_list == "function")
			subscriber_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function subscriber_process_list(args) {
	if (args.length < 2)
		args = ["list", subscriber_list_sort, subscriber_list_offset, subscriber_list_filter];

	subscriber_list_sort = args[1];
	subscriber_list_offset = args[2];
	subscriber_list_filter = args[3];

	if ( subscriber_listfilter > 0 ) $(\'JSListManager\').value = subscriber_listfilter;

	subscriber_list_discern_sortclass();

	paginators[1].paginate(subscriber_list_offset);
}

function subscriber_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	subscriber_form_load(id);
}

function subscriber_process_delete(args) {
	if (args.length < 2) {
		subscriber_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	subscriber_delete_check(id);
}

function subscriber_process_export(args) {
	$("list").className = "adesk_block";
	subscriber_list_export();
}

function subscriber_process_exportlist(args) {
	if (args.length < 2)
		args = subscriber_process_list(["list", "0"]);

	$("list").className = "adesk_block";

	var id = parseInt(args[1], 10);
	subscriber_exportlist_check(id);
}

function subscriber_process_delete_multi(args) {
	$("list").className = "adesk_block";
	subscriber_delete_check_multi();
}

function subscriber_process_search(args) {
	$("list").className = "adesk_block";
	subscriber_search_check();
}

function subscriber_process_optin(args) {
	if (args.length < 2) {
		subscriber_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	subscriber_optin_check(id);
}

function subscriber_process_optin_multi(args) {
	$("list").className = "adesk_block";
	subscriber_optin_check_multi();
}

'; ?>
