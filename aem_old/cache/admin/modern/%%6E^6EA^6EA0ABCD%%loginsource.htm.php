<?php /* Smarty version 2.6.12, created on 2016-07-08 16:22:26
         compiled from loginsource.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'adesk_js', 'loginsource.htm', 1, false),array('modifier', 'alang', 'loginsource.htm', 9, false),)), $this); ?>
<?php echo smarty_function_adesk_js(array('lib' => "really/simplehistory.js",'base' => $this->_tpl_vars['site']['p_link']), $this);?>

<script type="text/javascript">
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "loginsource.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</script>

<?php if (isset ( $this->_tpl_vars['loginsource_usersettings_header'] )): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "user-settings.header.inc.htm", 'smarty_include_vars' => array('userpage' => 'loginsource')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php else: ?>
	<h3 class="m-b"><?php echo ((is_array($_tmp='Login Sources')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "loginsource.list.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "loginsource.form.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script type="text/javascript">
  adesk_ui_rsh_init(loginsource_process, true);
</script>