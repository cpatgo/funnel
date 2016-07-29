<?php /* Smarty version 2.6.18, created on 2016-06-29 11:48:55
         compiled from text://b61461ed614d6e1e18d137526972f0da */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'text://b61461ed614d6e1e18d137526972f0da', 1, false),array('modifier', 'escape', 'text://b61461ed614d6e1e18d137526972f0da', 1, false),)), $this); ?>
<font size="2"><span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Dear'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'You have been declined in affiliate program.'), $this);?>
</span><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Sincerely,'), $this);?>
</span><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Your Affiliate manager'), $this);?>
</span><br/></font>