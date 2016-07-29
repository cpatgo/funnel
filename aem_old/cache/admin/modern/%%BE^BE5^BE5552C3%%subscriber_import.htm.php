<?php /* Smarty version 2.6.12, created on 2016-07-08 14:17:52
         compiled from subscriber_import.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'adesk_js', 'subscriber_import.htm', 1, false),array('modifier', 'alang', 'subscriber_import.htm', 10, false),array('modifier', 'js', 'subscriber_import.htm', 17, false),)), $this); ?>
<?php echo smarty_function_adesk_js(array('lib' => "really/simplehistory.js"), $this);?>

<script language="JavaScript" type="text/javascript">
<!--
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "subscriber_import.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
-->
</script>

<div id="subscriber_import">

<h3 class="m-b"><?php echo ((is_array($_tmp='Import Subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>



<?php if ($this->_tpl_vars['formSubmitted'] && $this->_tpl_vars['submitResult']['section'] == 'generic'): ?>
<?php if (! $this->_tpl_vars['submitResult']['status']): ?>
<script>
adesk_error_show('<?php echo ((is_array($_tmp=$this->_tpl_vars['submitResult']['message'])) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
');
</script>
<?php else: ?>
<script>
adesk_result_show('<?php echo ((is_array($_tmp=$this->_tpl_vars['submitResult']['message'])) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
');
</script>
<?php endif; ?>
<?php endif; ?>

<div id="import_limit_warning" class="<?php if (! $this->_tpl_vars['canImportSubscriber']): ?>adesk_block<?php else: ?>adesk_hidden<?php endif; ?>">
	<?php echo ((is_array($_tmp="Some subscribers might not be imported as it would exceed your currently allowed subscribers.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

</div>

<?php if (! $this->_tpl_vars['configured']): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "subscriber_import.step1.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php else: ?>
<div id="step2" class="adesk_block">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "subscriber_import.step2.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
<div id="step3" style="display: none;">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "subscriber_import.step3.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "subscriber_import.report.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

</div>

<script type="text/javascript">
  adesk_ui_rsh_init(import_process, true);
</script>