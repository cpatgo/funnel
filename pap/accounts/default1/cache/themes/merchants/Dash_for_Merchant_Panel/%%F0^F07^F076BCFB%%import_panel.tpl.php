<?php /* Smarty version 2.6.18, created on 2016-07-05 14:13:09
         compiled from import_panel.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'import_panel.tpl', 4, false),)), $this); ?>
<!--    import_panel    -->
<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Import source'), $this);?>
</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
  <?php echo "<div id=\"delimiter\"></div>"; ?>
  <?php echo "<div id=\"source\" class=\"ImportRadioGroup\"></div>"; ?>
  <?php echo "<div id=\"url\"></div>"; ?>
  <?php echo "<div id=\"uploadFile\"></div>"; ?>
  <?php echo "<div id=\"exportFilesGrid\"></div>"; ?> 
  <?php echo "<div id=\"serverFile\"></div>"; ?>
</div>

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Drop modules'), $this);?>
</div>
		<div class="FormFieldsetHeaderDescription"><?php echo smarty_function_localize(array('str' => 'Mark of any module will cause deleting of all data in that module, so if only update is needed marking of modules is not necessary.'), $this);?>
</div>
		<?php echo "<div id=\"showDropModulesButton\"></div>"; ?>
	</div>
  <?php echo "<div id=\"importExportGrid\"></div>"; ?>
</div>
<div class="pad_top pad_left">
<?php echo "<div id=\"importButton\"></div>"; ?>
</div>