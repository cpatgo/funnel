<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:58
         compiled from banner_parameters_rebrandpdf_allvars.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'banner_parameters_rebrandpdf_allvars.tpl', 5, false),)), $this); ?>
<!-- banner_parameters_rebrandpdf_allvars -->

<div class="FormFieldset" style="width:500px;">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Supported Variables'), $this);?>
</div>
		<div class="FormFieldsetHeaderDescription"><?php echo smarty_function_localize(array('str' => 'Below is list of supported variables you can use in your PDF document.
 Variables will be replaced by values related to affiliate, under which is PDF downloaded.
  Meaning of fields data1 - data10 you can customize in menu Configuration -> Affiliate Signup -> tab Fields'), $this);?>
</div>
	</div>
	<?php echo "<div id=\"variableList\"></div>"; ?>
</div>