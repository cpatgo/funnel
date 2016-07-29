<?php /* Smarty version 2.6.18, created on 2016-06-29 11:48:55
         compiled from text://e268a22d65f2dd86a07427fc8cefcc0e */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'text://e268a22d65f2dd86a07427fc8cefcc0e', 2, false),array('modifier', 'escape', 'text://e268a22d65f2dd86a07427fc8cefcc0e', 2, false),)), $this); ?>
<font size="2">
    <span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Dear'), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
,</span><br/><br/>
    <span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Thank you for your registration in our affiliate program.'), $this);?>
</span><br/>
    <span style="font-family: Arial;"><br/><?php echo smarty_function_localize(array('str' => 'We review every application'), $this);?>
 <span style="font-weight: bold;"><?php echo smarty_function_localize(array('str' => 'manually'), $this);?>
</span>, <?php echo smarty_function_localize(array('str' => 'and your registration is waiting for manual approval.'), $this);?>
 <?php echo smarty_function_localize(array('str' => 'Please, be patient.'), $this);?>
</span><br/><br/>
    <span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'After confirming your registration, you will receive one more email with all the necessary information.'), $this);?>
</span><br/><br/><span style="font-family: Arial;">--</span><br/>
    <span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Sincerely,'), $this);?>
</span><br/><br/>
    <span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Your Affiliate manager'), $this);?>
</span></font>