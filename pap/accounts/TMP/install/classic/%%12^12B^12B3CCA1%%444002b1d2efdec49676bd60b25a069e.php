<?php /* Smarty version 2.6.18, created on 2016-06-29 11:48:56
         compiled from text://444002b1d2efdec49676bd60b25a069e */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'text://444002b1d2efdec49676bd60b25a069e', 2, false),)), $this); ?>
<font size="2">
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Dear merchant'), $this);?>
 ,</span><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Your license is invalid or expired:'), $this);?>
 <?php echo $this->_tpl_vars['installationUrl']; ?>
</span><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Please check your installation:'), $this);?>
</span><br/>
<?php echo $this->_tpl_vars['installationLink']; ?>
<br/><br/>

<br/>

<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Regards'), $this);?>
,</span><br/><br/>
<span style="font-family: Arial;"><?php echo smarty_function_localize(array('str' => 'Quality Unit Team'), $this);?>
</span>
</font>