<?php /* Smarty version 2.6.18, created on 2016-06-29 11:48:56
         compiled from text://086a1c6ba1c03f480c1cee008987e7eb */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'text://086a1c6ba1c03f480c1cee008987e7eb', 2, false),array('modifier', 'escape', 'text://086a1c6ba1c03f480c1cee008987e7eb', 3, false),)), $this); ?>
<font size="2">
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Dear merchant'), $this);?>
,</span><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Affiliate'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo smarty_function_localize(array('str' => 'added new directlink at'), $this);?>
 <?php echo $this->_tpl_vars['date']; ?>
.</span><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'URL'), $this);?>
: <span style="font-weight: bold;"><?php echo ((is_array($_tmp=$this->_tpl_vars['directlink_url'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></span><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Note'), $this);?>
: <span style="font-weight: bold;"><?php echo ((is_array($_tmp=$this->_tpl_vars['directlink_note'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></span><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'If you like to APPROVE it click here'), $this);?>
: <a href="<?php echo $this->_tpl_vars['directlink_approve']; ?>
"><?php echo $this->_tpl_vars['directlink_approve']; ?>
</a></span><br/></font><font size="2"><br/><br/> 
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'If you like to DECLINE it click here'), $this);?>
: <a href="<?php echo $this->_tpl_vars['directlink_decline']; ?>
"><?php echo $this->_tpl_vars['directlink_decline']; ?>
</a></span><br/></font><font size="2"><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Regards'), $this);?>
,</span><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Your'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['postAffiliatePro'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
.</span>
</font>