<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:58
         compiled from banner_parameters_rebrandpdf_filefield.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'banner_parameters_rebrandpdf_filefield.tpl', 5, false),)), $this); ?>
<!-- banner_parameters_rebrandpdf_foundvars -->

<div class="FormFieldset" style="width:500px;">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Uploaded Pdf File'), $this);?>
</div>
	</div>
  <?php echo smarty_function_localize(array('str' => 'File Name:'), $this);?>
 <?php echo "<div id=\"data3\" class=\"RebrandPDFFileName\"></div>"; ?> 
  <?php echo smarty_function_localize(array('str' => 'File Size:'), $this);?>
 <?php echo "<div id=\"filesize\" class=\"RebrandPDFFileSize\"></div>"; ?>
  <?php echo smarty_function_localize(array('str' => 'In uploaded document were found following variables:'), $this);?>
<br/><br/> 
	<?php echo "<div id=\"variables\"></div>"; ?>
</div>