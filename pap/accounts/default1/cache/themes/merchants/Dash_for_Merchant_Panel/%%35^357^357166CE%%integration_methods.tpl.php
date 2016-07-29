<?php /* Smarty version 2.6.18, created on 2016-07-05 14:13:09
         compiled from integration_methods.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'integration_methods.tpl', 5, false),)), $this); ?>
<!-- integration_methods -->

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Integration method'), $this);?>
</div>
		<div class="FormFieldsetHeaderDescription"><?php echo smarty_function_localize(array('str' => 'To setup tracking of sales choose one of the integration methods below.'), $this);?>
</div>
	</div>
  <?php echo "<div id=\"IntegrationMethods\" class=\"IntegrationMethods\"></div>"; ?>
  <div><?php echo "<div id=\"hashScriptNamesCheckBox\"></div>"; ?> <?php echo smarty_function_localize(array('str' => 'Hash script file names (hashed scripts are hard to be blocked by AdBlock), mod_rewrite rules are used, defined in .htaccess file.'), $this);?>
</div>
  <div class="clear"></div>
  <div><?php echo "<div id=\"UseHttps\"></div>"; ?> <?php echo smarty_function_localize(array('str' => 'Use secure connection'), $this);?>
</div>
  <div class="ClearLeft"></div>
  <?php echo "<div id=\"AdvancedOptionsButton\" class=\"FloatLeft\"></div>"; ?>
  <?php echo "<div id=\"AdvancedOptionsPanel\" class=\"ClearLeft\"></div>"; ?>
  <div class="clear"></div>
</div>

<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Integration steps'), $this);?>
</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
	<?php echo "<div id=\"IntegrationMethodBody\"></div>"; ?>
  <div class="clear"></div>
</div>