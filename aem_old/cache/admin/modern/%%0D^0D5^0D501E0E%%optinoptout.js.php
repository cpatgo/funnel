<?php /* Smarty version 2.6.12, created on 2016-07-18 12:02:57
         compiled from optinoptout.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'optinoptout.js', 14, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "optinoptout.list.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "optinoptout.form.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "optinoptout.delete.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "optinoptout.search.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

//adesk_editor_init_word_object.plugins += ",ota_personalize,ota_conditional,ota_template";
//adesk_editor_init_word_object.theme_advanced_buttons1_add += ",ota_personalize,ota_conditional,ota_template";
//adesk_editor_init_word_object.mode = "none";
//adesk_editor_init_word_object.elements = "messageEditor";
adesk_editor_init_word_object.language = _twoletterlangid;
adesk_editor_init_word();
adesk_editor_init_word_object.plugins += ",fullpage";

var optinoptout_listfilter = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['listfilter']), $this);?>
;

<?php echo '
function optinoptout_process(loc, hist) {
	if ( loc == \'\' ) {
		loc = \'list-\' + optinoptout_list_sort + \'-\' + optinoptout_list_offset + \'-\' + optinoptout_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	$("optinoptout_list_count").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("optinoptout_process_" + args[0]);

	} catch (e) {
		if (typeof optinoptout_process_list == "function")
			optinoptout_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function optinoptout_process_list(args) {
	if (args.length < 2)
		args = ["list", optinoptout_list_sort, optinoptout_list_offset, optinoptout_list_filter];

	optinoptout_list_sort = args[1];
	optinoptout_list_offset = args[2];
	optinoptout_list_filter = args[3];

	if ( optinoptout_listfilter > 0 ) $(\'JSListManager\').value = optinoptout_listfilter;

	optinoptout_list_discern_sortclass();

	paginators[1].paginate(optinoptout_list_offset);
}

function optinoptout_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	optinoptout_form_load(id);
}

function optinoptout_process_delete(args) {
	if (args.length < 2) {
		optinoptout_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	optinoptout_delete_check(id);
}

function optinoptout_process_delete_multi(args) {
	$("list").className = "adesk_block";
	optinoptout_delete_check_multi();
}

function optinoptout_process_search(args) {
	$("list").className = "adesk_block";
	optinoptout_search_check();
}

function optinoptout_ie_fix() {
	// focus on first input field - IE 8 & 9 was having trouble allowing editing any form field
	if ( $("optnameField") ) $("optnameField").select();
}
'; ?>