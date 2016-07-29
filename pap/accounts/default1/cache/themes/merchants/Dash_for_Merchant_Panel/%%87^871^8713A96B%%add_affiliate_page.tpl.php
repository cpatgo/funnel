<?php /* Smarty version 2.6.18, created on 2016-07-05 14:12:46
         compiled from add_affiliate_page.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'localize', 'add_affiliate_page.tpl', 4, false),)), $this); ?>
<!-- add_affiliate_page -->
<div class="FormFieldset">
	<div class="FormFieldsetHeader">
		<div class="FormFieldsetHeaderTitle"><?php echo smarty_function_localize(array('str' => 'Add page'), $this);?>
</div>
		<div class="FormFieldsetHeaderDescription"></div>
	</div>
  <?php echo "<div id=\"PageHeader\"></div>"; ?>
  <?php echo "<div id=\"PageSettings\"></div>"; ?>
  <div class="clear"></div>
  
  <?php echo "<div id=\"FormMessage\"></div>"; ?>
  <?php echo "<div id=\"AddButton\"></div>"; ?>
</div>