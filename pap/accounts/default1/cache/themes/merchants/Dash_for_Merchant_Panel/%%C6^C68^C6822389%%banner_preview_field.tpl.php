<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:58
         compiled from banner_preview_field.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'banner_preview_field.tpl', 4, false),)), $this); ?>
<!--    banner_preview_field   -->

<div class="Preview">
  <div class="FormFieldsetSectionTitle"><?php echo smarty_function_localize(array('str' => 'Preview'), $this);?>

  <?php echo smarty_function_localize(array('str' => 'You can preview rebranded PDF document - same result will see your affiliates. Variables will be replaced with values related to selected affiliate from list box.'), $this);?>
<br/>
  <?php echo "<div id=\"affiliateText\"></div>"; ?><?php echo "<div id=\"affiliateBox\"></div>"; ?>    
  <?php echo "<div id=\"previewButton\"></div>"; ?>
  <?php echo "<div id=\"infoLabel\"></div>"; ?>
</div>