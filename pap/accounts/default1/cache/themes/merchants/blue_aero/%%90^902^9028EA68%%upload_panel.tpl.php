<?php /* Smarty version 2.6.18, created on 2016-07-06 14:15:32
         compiled from upload_panel.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'upload_panel.tpl', 3, false),)), $this); ?>
<!-- upload_panel -->
<fieldset>
	<legend><?php echo smarty_function_localize(array('str' => 'Attachments'), $this);?>
</legend>
	<?php echo "<div id=\"uploadedFiles\"></div>"; ?>
</fieldset>