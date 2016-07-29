<?php /* Smarty version 2.6.18, created on 2016-06-29 11:48:56
         compiled from text://032b23a58991310655f2099fb2859447 */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'text://032b23a58991310655f2099fb2859447', 1, false),)), $this); ?>
<font size="2"><span style="font-family: Arial;">Dear <?php echo ((is_array($_tmp=$this->_tpl_vars['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span><br/><br/>
<span style="font-family: Arial;">Thank you for registration in our affiliate program.</span><br/><br/>
<span style="font-family: Arial;">You have been approved and you can login using the following link: </span>
<span style="font-weight: bold; font-family: Arial;"><?php echo $this->_tpl_vars['newPasswordAndLoginLink']; ?>
</span><br/><br/>
<span style="font-family: Arial;">Your username: </span><strong style="font-family: Arial;"><?php echo ((is_array($_tmp=$this->_tpl_vars['username'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</strong><br/>
<span style="font-family: Arial;">Sincerely,</span><br/><br/>
<span style="font-family: Arial;">Your Affiliate manager</span><br/></font>