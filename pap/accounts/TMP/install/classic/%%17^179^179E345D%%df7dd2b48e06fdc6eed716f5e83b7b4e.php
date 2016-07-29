<?php /* Smarty version 2.6.18, created on 2016-06-29 11:48:55
         compiled from text://df7dd2b48e06fdc6eed716f5e83b7b4e */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'text://df7dd2b48e06fdc6eed716f5e83b7b4e', 1, false),array('modifier', 'escape', 'text://df7dd2b48e06fdc6eed716f5e83b7b4e', 1, false),)), $this); ?>
<font size="2"><span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Dear'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['parent_firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['parent_lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
,</span><br>
<br>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'You just signed up a new sub-affiliate for our Affiliate Program.'), $this);?>
</span><br>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'The name of the affiliate is'), $this);?>
 <span style="font-weight: bold;"><?php echo ((is_array($_tmp=$this->_tpl_vars['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>. </span><br>
<br>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Keep up the good work!'), $this);?>
</span><br>
<br>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'You can check your stats here:'), $this);?>
 <?php echo $this->_tpl_vars['affiliateLoginUrl']; ?>
</span><br>
<br>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Sincerely'), $this);?>
,</span><br style="font-family: Arial;"><span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'The Quality Unit Affiliate Program team'), $this);?>
</span><br>
<br /><br />
<?php echo smarty_function_localize(array('str' => 'To disable these notifications, please follow the link below:'), $this);?>

<br />
<a href="<?php echo $this->_tpl_vars['unsubscribeLink']; ?>
"><?php echo $this->_tpl_vars['unsubscribeLink']; ?>
</a>
</font>