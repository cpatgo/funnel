<?php /* Smarty version 2.6.18, created on 2016-07-06 12:43:34
         compiled from installer_finished.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'installer_finished.tpl', 4, false),)), $this); ?>
<!-- installer_finished -->

<p>
<?php echo smarty_function_localize(array('str' => 'The installation finished successfully. Thank you for choosing our product.'), $this);?>
<br/>
<a href="../"><?php echo smarty_function_localize(array('str' => 'Click here to go to introduction screen'), $this);?>
</a>
</p>