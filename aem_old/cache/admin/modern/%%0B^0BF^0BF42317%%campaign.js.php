<?php /* Smarty version 2.6.12, created on 2016-07-08 14:14:32
         compiled from campaign.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'campaign.js', 5, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign.list.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign.delete.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "campaign.search.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

var campaign_listfilter = <?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['listfilter']), $this);?>
;

<?php echo '
function campaign_process(loc, hist) {
	if ( loc == \'\' ) {
		loc = \'list-\' + campaign_list_sort + \'-\' + campaign_list_offset + \'-\' + campaign_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("campaign_list_count").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("campaign_process_" + args[0]);

	} catch (e) {
		if (typeof campaign_process_list == "function")
			campaign_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}

function campaign_process_list(args) {
	if (args.length < 2)
		args = ["list", campaign_list_sort, campaign_list_offset, campaign_list_filter];

	campaign_list_sort = args[1];
	campaign_list_offset = args[2];
	campaign_list_filter = args[3];

	if ( campaign_listfilter > 0 ) $(\'JSListManager\').value = campaign_listfilter;

	campaign_list_discern_sortclass();

	paginators[1].paginate(campaign_list_offset);
}

function campaign_process_delete(args) {
	if (args.length < 2) {
		campaign_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	campaign_delete_check(id);
}

function campaign_process_share(args) {
	if (args.length < 2) {
		campaign_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	campaign_share_check(id);
}

function campaign_process_delete_multi(args) {
	$("list").className = "adesk_block";
	campaign_delete_check_multi();
}

function campaign_process_search(args) {
	$("list").className = "adesk_block";
	campaign_search_check();
}

'; ?>
