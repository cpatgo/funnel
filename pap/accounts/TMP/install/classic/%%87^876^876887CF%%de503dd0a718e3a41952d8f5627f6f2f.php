<?php /* Smarty version 2.6.18, created on 2016-06-29 11:48:55
         compiled from text://de503dd0a718e3a41952d8f5627f6f2f */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'text://de503dd0a718e3a41952d8f5627f6f2f', 2, false),array('modifier', 'escape', 'text://de503dd0a718e3a41952d8f5627f6f2f', 3, false),)), $this); ?>
<font size="2">
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Dear merchant,'), $this);?>
</span><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Affiliate'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo smarty_function_localize(array('str' => 'signed-up to your affiliate program at'), $this);?>
 <?php echo $this->_tpl_vars['date']; ?>
.</span><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Affiliate name'), $this);?>
: <span style="font-weight: bold;"><?php echo ((is_array($_tmp=$this->_tpl_vars['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></span><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Affiliate email'), $this);?>
: <span style="font-weight: bold;"><?php echo ((is_array($_tmp=$this->_tpl_vars['username'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></span><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Affiliate ID'), $this);?>
: <span style="font-weight: bold;"><?php echo $this->_tpl_vars['userid']; ?>
</span></span><br/>
<br/>

<?php if ($this->_tpl_vars['new_user_signup_status'] != 'A'): ?>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'If you want to APPROVE new affiliate click here'), $this);?>
: </span><br/>
<span style="font-family: Arial;"><a href="<?php echo $this->_tpl_vars['new_user_signup_approve_link']; ?>
"><?php echo $this->_tpl_vars['new_user_signup_approve_link']; ?>
</a></span>
<br/><br/>
<?php endif; ?>

<?php if ($this->_tpl_vars['new_user_signup_status'] != 'D'): ?>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'If you want to DECLINE new affiliate click here'), $this);?>
: </span><br/>
<span style="font-family: Arial;"><a href="<?php echo $this->_tpl_vars['new_user_signup_decline_link']; ?>
"><?php echo $this->_tpl_vars['new_user_signup_decline_link']; ?>
</a></span>
<br/><br/>
<?php endif; ?>

<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Regards'), $this);?>
,</span><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Your'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['postAffiliatePro'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
.</span>
</font>