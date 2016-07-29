<?php /* Smarty version 2.6.18, created on 2016-07-06 14:13:02
         compiled from add_gadget_select_url.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'add_gadget_select_url.tpl', 2, false),)), $this); ?>
<!-- add_gadget_select_url -->
<p><?php echo smarty_function_localize(array('str' => 'Here you can add any RSS feed not listed in our library. To add feed, define name and input URL to feed definition file.'), $this);?>
</p>
<?php echo "<div id=\"name\"></div>"; ?>
<?php echo "<div id=\"url\"></div>"; ?>
<?php echo "<div id=\"NextButton\"></div>"; ?>