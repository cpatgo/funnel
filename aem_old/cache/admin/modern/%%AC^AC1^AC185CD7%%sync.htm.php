<?php /* Smarty version 2.6.12, created on 2016-07-08 14:18:25
         compiled from sync.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'sync.htm', 4, false),array('function', 'adesk_calendar', 'sync.htm', 22, false),array('function', 'adesk_js', 'sync.htm', 23, false),array('modifier', 'alang', 'sync.htm', 31, false),)), $this); ?>
<script>
// define variables
<?php echo smarty_function_jsvar(array('name' => 'manageAction','var' => $this->_tpl_vars['mode']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'manageID','var' => $this->_tpl_vars['data']['id']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'sortID','var' => $this->_tpl_vars['syncsort']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'blank','var' => $this->_tpl_vars['data']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'syncs','var' => $this->_tpl_vars['syncs']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'syncsCnt','var' => $this->_tpl_vars['syncsCnt']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'fields','var' => $this->_tpl_vars['fields']), $this);?>


var processID = 0;

var steps = [ 'DB', 'Tables', 'Fields', 'Rules' ];

var somethingChanged = false;
var syncarray = null;
var frmArr = null;

</script>
<?php echo smarty_function_adesk_calendar(array('base' => $this->_tpl_vars['app_url']), $this);?>

<?php echo smarty_function_adesk_js(array('lib' => "really/simplehistory.js",'base' => $this->_tpl_vars['app_url']), $this);?>

<?php echo smarty_function_adesk_js(array('lib' => "templates/sync.js"), $this);?>

<?php echo smarty_function_adesk_js(array('lib' => "templates/sync.list.js"), $this);?>

<?php echo smarty_function_adesk_js(array('lib' => "templates/sync.form.js"), $this);?>

<?php echo smarty_function_adesk_js(array('lib' => "templates/sync.run.js"), $this);?>

<?php echo smarty_function_adesk_js(array('lib' => "templates/sync.delete.js"), $this);?>



<h3 class="m-b"><?php echo ((is_array($_tmp='Database Sync Utility')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 (<span id="syncsCount"><?php echo $this->_tpl_vars['syncsCnt']; ?>
</span>)<span id="syncModeTitle"></span></h3>




<?php if (isset ( $this->_tpl_vars['sync_header_template'] )): ?>
	<?php if ($this->_tpl_vars['sync_header_template'] != ''): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['sync_header_template'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "sync.list.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "sync.form.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "sync.run.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "sync.delete.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script>
// initialize RealSimpleHistory
adesk_ui_rsh_init(sync_page_changed, true);
//runPage();
// set session ping
adesk_ui_session_ping_admin();
</script>