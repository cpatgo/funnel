<?php /* Smarty version 2.6.18, created on 2016-06-29 11:48:55
         compiled from text://ebd6dcea6c1ba6d6ce7441e25e1fc2a6 */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'text://ebd6dcea6c1ba6d6ce7441e25e1fc2a6', 2, false),array('modifier', 'escape', 'text://ebd6dcea6c1ba6d6ce7441e25e1fc2a6', 2, false),array('modifier', 'currency', 'text://ebd6dcea6c1ba6d6ce7441e25e1fc2a6', 5, false),)), $this); ?>
<font size="2">
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Dear'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
,</span><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'I would like to let you know that we paid your commissions earned in our affiliate program.'), $this);?>
</span><br/><br/><strong style="font-family: Arial;">.:Payout preview:.</strong><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Date'), $this);?>
: </span><strong style="font-family: Arial;"><?php echo $this->_tpl_vars['date']; ?>
</strong><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Amount paid'), $this);?>
: </span><strong style="font-family: Arial;"><?php echo ((is_array($_tmp=$this->_tpl_vars['payment'])) ? $this->_run_mod_handler('currency', true, $_tmp) : smarty_modifier_currency($_tmp)); ?>
</strong><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Payout method'), $this);?>
: </span><strong style="font-family: Arial;"><?php echo ((is_array($_tmp=$this->_tpl_vars['payoutmethod'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</strong><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Sincerely'), $this);?>
,</span><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Your Affiliate manager'), $this);?>
</span>
</font>