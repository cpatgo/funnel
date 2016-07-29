<?php /* Smarty version 2.6.18, created on 2016-06-29 11:48:55
         compiled from text://89cda12afe78a2176b8f3459bd3cde9d */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'text://89cda12afe78a2176b8f3459bd3cde9d', 2, false),array('modifier', 'escape', 'text://89cda12afe78a2176b8f3459bd3cde9d', 4, false),)), $this); ?>
<font size="2">
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'You have been contacted'), $this);?>
</span>
<span style="font-family: Arial;"> <?php echo smarty_function_localize(array('str' => 'at'), $this);?>
 <?php echo $this->_tpl_vars['date']; ?>
.<br></span></font><font size="2">
<span style="font-family: Arial;"></span><strong style="font-family: Arial;"><?php echo ((is_array($_tmp=$this->_tpl_vars['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</strong></font>
<font size="2"><span style="font-family: Arial;">(<?php echo ((is_array($_tmp=$this->_tpl_vars['useremail'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
)</span></font>
<font size="2"><span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'filled "Contact us" form.'), $this);?>
</span><br/><br/>
<strong style="font-family: Arial;"></strong></font><hr style="font-family: Arial; height: 2px;"><font size="2">
<br/><span style="font-family: Arial; color: rgb(42, 42, 42);"><?php echo ((is_array($_tmp=$this->_tpl_vars['emailtext'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span><br/></font>
<hr style="font-family: Arial; height: 2px;">
<font size="2"><br/><span style="font-family: Arial;">
--</span><br/><span style="font-family: Arial;">
<?php echo smarty_function_localize(array('str' => 'Regards,'), $this);?>
</span><br/><br/><span style="font-family: Arial;">
<?php echo smarty_function_localize(array('str' => 'Your'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['postAffiliatePro'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span><br/></font>