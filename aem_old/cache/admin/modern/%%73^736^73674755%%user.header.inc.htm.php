<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:34
         compiled from user.header.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'user.header.inc.htm', 4, false),)), $this); ?>
<?php if (! $this->_tpl_vars['__ishosted'] && $this->_tpl_vars['admin']['id'] == 1 && isset ( $this->_tpl_vars['site']['adminsMax'] ) && isset ( $this->_tpl_vars['site']['adminsCnt'] ) && isset ( $this->_tpl_vars['site']['adminsLeft'] )): ?>
<div class="adesk_help_inline">
 
  <?php echo ((is_array($_tmp="You have a total of %d admin user spots. %d admin user spots are left")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['site']['adminsMax'], $this->_tpl_vars['site']['adminsLeft']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['site']['adminsMax'], $this->_tpl_vars['site']['adminsLeft'])); ?>

</div>
<?php endif; ?>