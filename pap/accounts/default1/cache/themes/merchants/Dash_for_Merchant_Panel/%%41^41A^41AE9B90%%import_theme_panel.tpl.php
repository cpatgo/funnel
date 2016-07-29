<?php /* Smarty version 2.6.18, created on 2016-07-05 14:13:09
         compiled from import_theme_panel.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'import_theme_panel.tpl', 2, false),)), $this); ?>
<!-- import_theme_panel -->
<div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Import theme'), $this);?>
</div>
<div class="FormFieldsetHeaderDescription"><?php echo smarty_function_localize(array('str' => 'Import zip file, zip file of custom theme can be created via theme editor.'), $this);?>
</div>
<?php echo "<div id=\"uploadFile\"></div>"; ?>
<div class="ClearBoth"></div>

<?php echo "<div id=\"FormMessage\"></div>"; ?>
<?php echo "<div id=\"importButton\"></div>"; ?>